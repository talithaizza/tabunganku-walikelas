<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Tabungan;
use App\Transaksi;
use App\siswa;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Session;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class SetorTunaiController extends Controller
{
    public function create()
    {
        return view('setortunai.create');
    }

    public function store(Request $request)
    {
		// Validasi
        $this->validate($request, [
            'nis' => 'required|numeric|min:10' ,
            'jenis_tabungan' => 'required|alpha_dash' ,
			'nominal' => 'required|numeric|min:0|not_in:0' ,
        ], [
			'nis.required' => 'Anda belum memasukan nomor induk siswa!',
			'nis.numeric' => 'nis hanya dapat terdiri dari angka!',
            'nis.min' => 'nis tidak valid!',
			'jenis_tabungan.required' => 'Anda belum memasukan jenis tabungan!',
			'jenis_tabungan.alpha_dash' => 'Kelas hanya dapat terdiri dari alfabet, angka, _ , dan - . contoh : Reguler_12',
            'nominal.required' => 'Anda belum memasukan nominal transaksi!',
			'nominal.numeric' => 'nominal hanya dapat terdiri dari angka!',
			'nominal.min' => 'nominal tidak valid!',
			'nominal.not_in' => 'nominal tidak valid!',
        ]);
		
        //Menyimpan Data pada Transaksi
        //Menyiapkan Kode Transaksi
        //Ex : T1REG123
        //Tarik = T + Count() Transaksi where request->nis + 3 char pertama kapital jenis tabungan + NIS

		//Cek apakah siswa terdaftar
		$cek_siswa = siswa::where('nis', $request->nis)->where('aktif', 1)->first();
		$cek_tabungan = DB::table('jenis_tabungan')->where('nama', $request->jenis_tabungan)->first();
        $tabsiswa = Tabungan::where('nis', $request->nis)->where('jenis_tabungan', $request->jenis_tabungan)->first();
        if ($cek_siswa!=null and $tabsiswa!=null and $cek_tabungan!=null ){
            //Menambahkan Saldo pada Tabungan
            $updated = Tabungan::where('nis', $request->nis)->where('jenis_tabungan', $request->jenis_tabungan)->update([
                'saldo' => $tabsiswa->saldo + $request->nominal,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            //Menambahkan Detail Transaksi
            $transaksi = Transaksi::create([
                'nis' => $request->nis,
                'kode_transaksi' => "S4REG123",
                'jenis_tabungan' => $request->jenis_tabungan,
                'jenis_transaksi' => "setor",
                'nominal' => "$request->nominal",
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            Session::flash("flash_notification", [
                "level" => "success",
                "icon" => "fa fa-check",
                "message" => "Transaksi dengan nominal ".$request->nominal." Berhasil!"
            ]);
        }
		else if ($cek_siswa!=null and $tabsiswa==null and $cek_tabungan!=null){
			//Membuat + Menambahkan Saldo pada Tabungan
			$created = Tabungan::create([
				'nis' => $request->nis,
				'jenis_tabungan' => $request->jenis_tabungan,
				'saldo' => $request->nominal,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
			]);

            //Menambahkan Detail Transaksi
            $transaksi = Transaksi::create([
                'nis' => $request->nis,
                'kode_transaksi' => "S4REG123",
                'jenis_tabungan' => $request->jenis_tabungan,
                'jenis_transaksi' => "setor",
                'nominal' => $request->nominal,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            Session::flash("flash_notification", [
                "level" => "success",
                "icon" => "fa fa-check",
                "message" => "Transaksi dengan nominal ".$request->nominal." Berhasil!"
            ]);
		}
		else{
            Session::flash("flash_notification", [
                "level" => "error",
                "icon" => "fa fa-ban",
                "message" => "Transaksi Gagal! Siswa/Jenis Tabungan Tidak Terdaftar/Tidak Aktif!"
            ]);
        }
        $this->broadcastMessage($request->nis, $request->jenis_tabungan, $request->nominal);
        return redirect()->route('mutasi.index');
    }


    
    private function broadcastMessage($nis, $jenis_tabungan, $nominal){
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
    
        $notificationBuilder = new PayloadNotificationBuilder('Transaksi Telah Diterima');
        $notificationBuilder->setBody('Transaksi tabungan '.$jenis_tabungan.' telah diterima sebesar Rp.'.number_format($nominal,0,",","."))
                            ->setSound('default');
        //                     ->setClickAction('https://localhost:3000/home')
        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'nis' => $nis,
            'jenis_tabungan' => $jenis_tabungan,
            'nominal' => $nominal
        ]);
                            
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        
        $tokens = siswa::where('nis',$nis)->pluck('firebase_token')->toArray();
    
        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        return $downstreamResponse->numberSuccess();
    }
    
}

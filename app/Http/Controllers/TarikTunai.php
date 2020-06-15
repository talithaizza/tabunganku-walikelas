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

class TarikTunai extends Controller
{
    public function create()
    {
        return view('tariktunai.create');
    }

    public function store(Request $request)
    {
		$this->validate($request, [
            'nis' => 'required|numeric|min:10' ,
            'jenis_tabungan' => 'required|alpha_dash' ,
            'nominal' => 'required|numeric' ,
			
        ], [
			'nis.required' => 'Anda belum memasukan nomor induk siswa!',
			'nis.numeric' => 'nis hanya dapat terdiri dari angka!',
            'nis.min' => 'nis tidak valid!',
			'jenis_tabungan.required' => 'Anda belum memasukan nama tabungan',
			'jenis_tabungan.alpha_dash' => 'nama hanya dapat terdiri dari alfabet, angka, _ , dan - . contoh : Reguler_12',
			'nominal.required' => 'Anda belum memasukan nominal transaksi!',
			'nominal.numeric' => 'Nominal hanya dapat terdiri dari angka!',
        ]);
		
        //Menyimpan Data pada Transaksi
        //Menyiapkan Kode Transaksi
        //Ex : T1REG123
        //Tarik = T + Count() Transaksi where request->nis + 3 char pertama kapital jenis tabungan + NIS

        //Cek apakah saldo yang akan diambil mencukupi dan status tabungan aktif
        $tabsiswa = Tabungan::where('nis', $request->nis)->where('jenis_tabungan', $request->jenis_tabungan)->first();
        $tabungan = DB::table('jenis_tabungan')->where('nama', $request->jenis_tabungan)->first();
		$cek_siswa = siswa::where('nis', $request->nis)->where('aktif', 1)->first();
		$cek_tabungan = DB::table('jenis_tabungan')->where('nama', $request->jenis_tabungan)->first();
		
		if($cek_siswa!=null and $tabsiswa!=null and $cek_tabungan!=null){
			if($tabungan->aktif != 1){
				$statusTabungan = false;
			}
			else{
				$statusTabungan = true;
			}
			
			if ($tabsiswa->saldo >= $request->nominal and $statusTabungan){
				//Menambahkan Saldo pada Tabungan
				$updated = Tabungan::where('nis', $request->nis)->where('jenis_tabungan', $request->jenis_tabungan)->update([
					'saldo' => $tabsiswa->saldo - $request->nominal,
					'updated_at' => date('Y-m-d H:i:s')
				]);

				//Menambahkan Detail Transaksi
				$transaksi = Transaksi::create([
					'nis' => $request->nis,
					'kode_transaksi' => "T4REG123",
					'jenis_tabungan' => $request->jenis_tabungan,
					'jenis_transaksi' => "tarik",
					'nominal' => "$request->nominal",
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
				]);

				Session::flash("flash_notification", [
					"level" => "success",
					"icon" => "fa fa-check",
					"message" => "Transaksi tarik dengan nominal ".$request->nominal." berhasil!"
				]);
			}else{
				Session::flash("flash_notification", [
					"level" => "error",
					"icon" => "fa fa-check",
					"message" => "Transaksi Gagal! Saldo Tidak Mencukupi!"
				]);
			}
		}
		else if ($cek_siswa!=null && $tabsiswa==null){
			Session::flash("flash_notification", [
				"level" => "error",
				"icon" => "fa fa-check",
				"message" => "Transaksi Gagal! Tabungan Belum Terdaftar!"
			]);
		}
		else{
			Session::flash("flash_notification", [
				"level" => "error",
				"icon" => "fa fa-check",
				"message" => "Transaksi Gagal! Siswa Tidak Terdaftar!"
			]);
		}
		$this->broadcastMessage($request->nis, $request->jenis_tabungan, $request->nominal);
        return redirect()->route('mutasi.index');
	}
	private function broadcastMessage($nis, $jenis_tabungan, $nominal){
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
    
        $notificationBuilder = new PayloadNotificationBuilder('Transaksi Telah Diterima');
        $notificationBuilder->setBody('Transaksi penarikan '.$jenis_tabungan.' telah diterima sebesar Rp.'.number_format($nominal,0,",","."))
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

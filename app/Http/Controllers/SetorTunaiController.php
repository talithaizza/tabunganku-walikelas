<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        //Menyimpan Data pada Transaksi
        //Menyiapkan Kode Transaksi
        //Ex : T1REG123
        //Tarik = T + Count() Transaksi where request->nis + 3 char pertama kapital jenis tabungan + NIS

        //Cek apakah saldo yang akan diambil mencukupi
        $tabsiswa = Tabungan::where('nis', $request->nis)->where('jenis_tabungan', $request->jenis_tabungan)->first();
        if ($tabsiswa!=null){
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
                "message" => "Transaksi Berhasil!"
            ]);
        }else{
            Session::flash("flash_notification", [
                "level" => "success",
                "icon" => "fa fa-check",
                "message" => "Transaksi Gagal!"
            ]);
        }
        $this->broadcastMessage($request->nis, $request->jenis_tabungan, $request->nominal);
        return redirect()->route('mutasi.index');
    }

    private function broadcastMessage($nis, $jenis_tabungan, $nominal){
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);
    
        $notificationBuilder = new PayloadNotificationBuilder('Transaksi Berhasil');
        $notificationBuilder->setBody($nis, $jenis_tabungan, $nominal)
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
        
        $tokens = siswa::all()->pluck('firebase_token')->toArray();
        
        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        return $downstreamResponse->numberSuccess();
    }
    
    // public function sendTopicFCM($title, $message, $receiver){

        
    //     $notificationBuilder = new PayloadNotificationBuilder($title);
    //     $notificationBuilder->setBody($message)
    //     ->setSound('default');
  
    //     $notification = $notificationBuilder->build();
  
    //     $topic = new Topics();
    //     $topic->topic('general');
  
    //     $topicResponse = FCM::sendToTopic($topic, null, $notification, null);
  
    //     $topicResponse->isSuccess();
    //     $topicResponse->shouldRetry();
    //     $topicResponse->error();
        
    //     return response()->json($topicResponse->isSuccess());
        
    //   }
}

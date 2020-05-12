<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Tabungan;
use App\Transaksi;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Session;

class TarikTunai extends Controller
{
    public function create()
    {
        return view('tariktunai.create');
    }

    public function store(Request $request)
    {
        //Menyimpan Data pada Transaksi
        //Menyiapkan Kode Transaksi
        //Ex : T1REG123
        //Tarik = T + Count() Transaksi where request->nis + 3 char pertama kapital jenis tabungan + NIS

        //Cek apakah saldo yang akan diambil mencukupi dan status tabungan aktif
        $tabsiswa = Tabungan::where('nis', $request->nis)->where('jenis_tabungan', $request->jenis_tabungan)->first();
        $tabungan = DB::table('jenis_tabungan')->where('nama', $request->jenis_tabungan)->first();
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
                "message" => "Transaksi Berhasil!"
            ]);
        }else{
            Session::flash("flash_notification", [
                "level" => "success",
                "icon" => "fa fa-check",
                "message" => "Transaksi Gagal!"
            ]);
        }
        return redirect()->route('mutasi.index');
    }

}

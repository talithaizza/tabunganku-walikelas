<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class mutasiController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
		//cari kelas dari guru yang login
		$nama_guru = Auth::user()->name;
		$kelas_guru = DB::table('kelas')->where('wali_kelas',$nama_guru)->first();
		$data_siswa = DB::table('siswa')->where('kelas',$kelas_guru->kelas)->get();
		$indeks = 0;
		$data_nis = array();
		foreach($data_siswa as $siswa){
			$data_nis[$indeks] = $siswa->nis;
			$indeks++;
		}
		
        if ($request->ajax()) {
            $data = DB::table('transaksi')->whereIn('nis', $data_nis)->get();
            return Datatables::of($data)
                ->addColumn('nama', function($data) {
                    //fungsi persotoyan
                    $temp = DB::table('siswa')->where('nis', $data->nis)->first();
                    $nama_siswa = $temp->nama_lengkap;
                    return $nama_siswa;
                })
                ->make(true);
        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'kode_transaksi', 'name' => 'kode_transaksi', 'title' => 'Kode Transaksi'])
            ->addColumn(['data' => 'nis', 'name' => 'nis', 'title' => 'NIS'])
            ->addColumn(['data' => 'nama', 'name' => 'nama', 'title' => 'Nama Siswa'])
            ->addColumn(['data' => 'jenis_tabungan', 'name' => 'jenis_tabungan', 'title' => 'Jenis Tabungan'])
            ->addColumn(['data' => 'jenis_transaksi', 'name' => 'jenis_transaksi', 'title' => 'Jenis Transaksi'])
            ->addColumn(['data' => 'nominal', 'name' => 'nominal', 'title' => 'Nominal'])
            ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tanggal Transaksi']);
            
        return view('mutasi.index')->with(compact('html'));
    }
}

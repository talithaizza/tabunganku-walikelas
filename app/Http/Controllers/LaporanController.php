<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Tabungan;

class LaporanController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {
		// $from = date('2020-06-10 00:00:00');
		// $to = date('2020-07-10 00:00:00');
        //$tabsis='reguler';
		//Reservation::whereBetween('reservation_from', [$from, $to])->get();
        
        $from = strtotime($request->input('tanggal_awal'));
		$from_date = date('Y-m-d H:i:s', $from); 
		$to = strtotime($request->input('tanggal_akhir'));
		$to_date = date('Y-m-d H:i:s', $to); 
        $tabsis = $request->input('jenistab');
        // $from = $request->input('tanggal_awal');
        // $to = $request->input('tanggal_akhir' );
        //dd($from_date);
       
		$data = DB::table('transaksi')->where('jenis_tabungan',$tabsis)->whereBetween('created_at' , [$from_date, $to_date])->get();
        dd($data);
        if ($request->ajax()) {
            //$data = DB::table('transaksi')->whereBetween(DB::raw('DATE(created_at)'), array($from_date, $to_date))->get();
			//$data = DB::table('transaksi')->where('jenis_tabungan',$request->jenistab)->get();
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
            ->addColumn(['data' => 'created_at', 'name' => 'created_at', 'title' => 'Tanggal Transaksi', 'orderable' => 'false', 'searchable' => false]);
            
        return view('laporan.index')->with(compact('html'));
        //return abort(404, 'Page not found');
		//return $request->jenistab;
    }
	public function periode()
    {
        return view('laporan.create');
    }
}
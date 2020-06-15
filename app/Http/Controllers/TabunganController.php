<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laratrust\LaratrustFacade as Laratrust;
use App\siswa;
use App\tabungan;
use App\Kelas;
use App\Book;
use App\Author;
use App\BorrowLog;
use Yajra\Datatables\Html\Builder;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\BookException;
use Session;
use Excel;
use PDF;
use Validator;

class TabunganController extends Controller
{
    //
    public function index(Request $request, Builder $htmlBuilder)
    {	
        if ($request->ajax()) {
            if (Laratrust::hasRole('walikelas')) {
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
				$data = DB::table('tabungan')->whereIn('nis', $data_nis)->get();
			}
			else if (Laratrust::hasRole('admin')) {
				$data = DB::table('tabungan')->get();
			}
            return Datatables::of($data)
                ->addColumn('nama', function($data) {
                    //fungsi persotoyan
                    $temp = DB::table('siswa')->where('nis', $data->nis)->first();
                    $nama_siswa = $temp->nama_lengkap;
                    return $nama_siswa;
                })
                ->addColumn('kelas', function($data) {
                    //fungsi persotoyan
                    $siswa = DB::table('siswa')->where('nis', $data->nis)->first();
                    $temp = $siswa->kelas;
                    return $temp;
                })
                ->addColumn('walikelas', function($data) {
                    //fungsi persotoyan
                    $siswa = DB::table('siswa')->where('nis', $data->nis)->first();
                    $kelas = DB::table('kelas')->where('kelas', $siswa->kelas)->first();
                    $temp = $kelas->wali_kelas;
                    return $temp;
                })
                ->make(true);
        }
		$indeks = 0;

        $html = $htmlBuilder
            ->addColumn(['data' => 'nis', 'name' => 'nis', 'title' => 'NIS'])
            ->addColumn(['data' => 'nama', 'name' => 'nama', 'title' => 'Nama Siswa'])
            ->addColumn(['data' => 'jenis_tabungan', 'name' => 'jenis_tabungan', 'title' => 'Jenis Tabungan'])
            ->addColumn(['data' => 'saldo', 'name' => 'saldo', 'title' => 'Saldo'])
            ->addColumn(['data' => 'kelas', 'name' => 'kelas', 'title' => 'Kelas'])
            ->addColumn(['data' => 'walikelas', 'name' => 'walikelas', 'title' => 'Wali Kelas']);

        return view('tabungan.index')->with(compact('html'));
    }
    public function export()
    {
        return view('tabungan.export');
    }

    public function exportPost(Request $request)
    {
        $dataTabungan = Tabungan::whereIn('nis', $request->get('id_siswa'))->get();
        //Data Enrichment
        // $siswa = DB::table('siswa')->where('nis', $dataTabungan->nis)->get();
        // $kelas = DB::table('kelas')->where('kelas', $siswa->kelas)->get();
        // $dataTabungan->nama_siswa = $siswa->nama_lengkap;
        // $dataTabungan->kelas = $siswa->kelas;
        // $dataTabungan->walikelas = $kelas->wali_kelas;
        foreach($dataTabungan as $data){
            $siswa = siswa::where('nis', $data->nis)->first();
            $kelas = DB::table('kelas')->where('kelas', $siswa->kelas)->first();
            $data->nama_siswa = $siswa->nama_lengkap;
            $data->kelas = $siswa->kelas;
            $data->walikelas = $kelas->wali_kelas;
        }
        $handler = 'export' . ucfirst($request->get('type'));

        return $this->$handler($dataTabungan);
    }

    private function exportXls($dataTabungan)
    {
        Excel::create('DataTabungan', function($excel) use ($dataTabungan) {
            // Set property
            $excel->setTitle('Data Tabungan')
                ->setCreator(Auth::user()->name);

            $excel->sheet('Data Tabungan', function($sheet) use ($dataTabungan) {
                $row = 1;
                $sheet->row($row, [
                    'nis',
                    'nama',
                    'jenis_tabungan',
                    'saldo',
                    'kelas',
                    'walikelas'
                ]);
                foreach ($dataTabungan as $tabungan) {
                    $sheet->row(++$row, [
                        $tabungan->nis,
                        $tabungan->nama_siswa,
                        $tabungan->jenis_tabungan,
                        $tabungan->saldo,
                        $tabungan->kelas,
                        $tabungan->walikelas
                    ]);
                }
            });
        })->export('xls');
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laratrust\LaratrustFacade as Laratrust;
use App\Http\Requests;
use App\Book;
use App\siswa;
use App\Walikelas;
use App\Kelas;
use App\JenisTabungan;
use App\Author;
use App\Role;
use App\BorrowLog;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Laratrust::hasRole('member')) {

            $borrowLogs = Auth::user()->borrowLogs()->borrowed()->get();

            return view('dashboard.member', compact('borrowLogs'));
        }
		else if (Laratrust::hasRole('walikelas')) {
			//ambil data siswa dari kelas guru yang login
			//cari kelas dari guru yang login
			$nama_guru = Auth::user()->name;
			$kelas_guru = DB::table('kelas')->where('wali_kelas',$nama_guru)->first();
			$data_siswa = DB::table('siswa')->where('kelas',$kelas_guru->kelas)->get();
			$jumlah_data_siswa = DB::table('siswa')->where('kelas',$kelas_guru->kelas)->count(); //PASS THIS VARIABLE
			//=============================================================================
			//ambil data total transaksi
			//ambil nis semua siswanya karna di tabel transaksi pake nis
			//hitung total transaksi
			$indeks = 0;
			$data_nis = array();
			foreach($data_siswa as $siswa){
				$data_nis[$indeks] = $siswa->nis;
				$indeks++;
			}
			
			//Hitung Total Saldo 
			$total_saldo_keseluruhan = 0; //PASS THIS VARIABLE
			$total_tarik_harian = 0; //PASS THIS VARIABLE
			$total_setor_harian = 0; //PASS THIS VARIABLE
			foreach($data_nis as $nis){
				$tab_siswa = DB::table('tabungan')->where('nis', $nis)->get();
				if ($tab_siswa!=null){
					foreach($tab_siswa as $tabungan){
						$total_saldo_keseluruhan = $total_saldo_keseluruhan + $tabungan->saldo;
					}
				}
				//Hitung Total Saldo Transaksi Harian
				$transaksi_siswa = DB::table('transaksi')->where('nis', $nis)->get();
				if ($transaksi_siswa!=null){
					foreach($transaksi_siswa as $transaksi){
						//cek tanggal dulu
						$tanggal_transaksi = $transaksi->updated_at;
						$kemarin = date("Y-m-d", mktime(0, 0, 0, date("m") , date("d")-1,date("Y")));
						$hari_transaksi = date('d', strtotime($tanggal_transaksi)); //integer tanggal transaksi
						$hari_kemarin = date('d', strtotime($kemarin)); //integer tanggal kemarin
						//$hari_kemarin+1 == $hari_transaksi
						if (true){
							if ($transaksi->jenis_transaksi == "setor"){
								$total_setor_harian = $total_setor_harian + $transaksi->nominal;
							}
							else if ($transaksi->jenis_transaksi == "tarik"){
								$total_tarik_harian = $total_tarik_harian + $transaksi->nominal;
							}
						}
					}
				}	
			}
			
			//=============================================================================
            return view('dashboard.guru',compact('jumlah_data_siswa', 'total_saldo_keseluruhan', 'total_tarik_harian', 'total_setor_harian'));
        }
        else if (Laratrust::hasRole('admin')) {
			//Data Guru
			$total_walikelas_aktif = 0; //Pass This!
			$total_walikelas_nonaktif = 0; //Pass This!
			$total_walikelas = Walikelas::count(); //Pass This!
			$data_walikelas = Walikelas::all();
			if ($data_walikelas!=null){
				foreach($data_walikelas as $walikelas){
					if ($walikelas->aktif == 1) {
						$total_walikelas_aktif++;
					}
					else{
						$total_walikelas_nonaktif++;
					}
				}
			}
			
			//Data Siswa
			$total_siswa_aktif = 0; //Pass This!
			$total_siswa_nonaktif = 0; //Pass This!
			$total_siswa = siswa::count(); //Pass This!
			$data_siswa = siswa::all();
			if ($data_siswa!=null){
				foreach($data_siswa as $siswa){
					if ($siswa->aktif == 1) {
						$total_siswa_aktif++;
					}
					else{
						$total_siswa_nonaktif++;
					}
				}
			}
			
			//Data Saldo dan Tabungan
			$total_jenis_tabungan = JenisTabungan::count(); //Pass This!
			$total_saldo_keseluruhan = 0; //Pass This!
			$total_saldo_pertabungan = array(); //Assosiative Array, Pass This!
			$Tabungan = JenisTabungan::all();
			if ($Tabungan!=null){
				foreach($Tabungan as $tabungan){
					$total_saldo_pertabungan[$tabungan->nama] = 0;
				}
			}
			$tab_siswa = DB::table('tabungan')->get();
			if ($tab_siswa!=null){
				foreach($tab_siswa as $tabungan){
					$total_saldo_pertabungan[$tabungan->jenis_tabungan] = $total_saldo_pertabungan[$tabungan->jenis_tabungan] + $tabungan->saldo;
					$total_saldo_keseluruhan = $total_saldo_keseluruhan + $tabungan->saldo;
				}
			}

			//Data Kelas
            $total_kelas = Kelas::count(); //Pass This!

            return view('dashboard.admin', compact('total_walikelas_aktif', 'total_walikelas_nonaktif', 'total_walikelas', 'total_siswa_aktif', 'total_siswa_nonaktif','total_siswa','total_jenis_tabungan','total_saldo_keseluruhan','total_saldo_pertabungan','total_kelas'));
        }
        return view('login');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\siswa;
use Illuminate\Support\Facades\DB;


class myAPIController extends Controller
{
	//API yang dipakai
	//Login menggunakan milik melani, tapi belum menggunakan username dan masih menggunakan email untuk masuknya
	public function siswaLogin(Request $request)
    {
		$username = $request->username;
		$password = $request->password;
        $datasiswa = siswa::where('nama_pengguna', $username)->where('katasandi', $password)->first();
        if ($datasiswa!=null) {
            $tokensiswa = new siswa; //Instansiasi Objek biar bisa panggil static function
			$datasiswa->token = $tokensiswa->GenerateToken();
			$datasiswa->save();
			return response()->json([
				'status' => 'success',
				'data_siswa' => $datasiswa
			]);
        }
		else{
			return response()->json([
			'status' => 'fail',
		]);}
	}

	//Fungsi untuk memasukan token firebase pada tabel siswa
	public function firebaseAddToken(Request $request){
		$datasiswa = siswa::where('token', $request->token)->first();
		if ($datasiswa!=null) {
			$datasiswa->firebase_token = $request->firebase_token;
			$datasiswa->save();
			return response()->json([
				'status' => 'success',
				'data_siswa' => $datasiswa
			]);
        }
		else{
			return response()->json([
			'status' => 'fail',
		]);}
	}
	
	//Logout pakai api melani
	public function siswaLogout(Request $request)
    {
        $datasiswa = siswa::where('token', $request->token)->first();
        if ($datasiswa!=null) {
			if($datasiswa->token != null){
				$tokensiswa = new siswa;
				$datasiswa->token = $tokensiswa->RemoveToken();
				$datasiswa->save();
				$res['message'] = "Logout Success!";
				return response($res);
			}else{
				$res['message'] = "Logout Success!";
				return response($res);
			}
        }
		else{
			$res['message'] = "Data Not Found!";
			return response($res);
		}
    }

	//Menampilkan data siswa, seperti saat login tapi pakai method get
	public function getSiswa($token){
		$datasiswa = siswa::where('token', $token)->first();
		return response()->json([
			'status' => 'success',
			'data_siswa' => $datasiswa
		]);
	}

	
	//List transaksi dari getTransaksi tanpa $jenis_tabungan
	public function getTransaksiSiswa($token){
		$datasiswa = siswa::where('token',$token)->first();
		if($datasiswa!=null){
			$data_transaksi = DB::table('transaksi')->where('nis',$datasiswa->nis)->orderByDesc('created_at')->get(); 
			if(count($data_transaksi)>0){
				return response()->json([
					'status' => 'success',
					'data_transaksi' => $data_transaksi
				]);
			}
			else{
				return response()->json([
					'status' => 'empty',
				]);
			}
		}else{
			return response()->json([
				'status' => 'error!',
			]);
		}
	}	

	//Menampilkan saldo dan masa periode tabungan, syntax utk menampilkan tahun periode dan status yang diambil dari getSaldo.
	public function getSaldoSiswa($token){
		$datasiswa = siswa::where('token', $token)->first();
		if ($datasiswa!=null) {
			$saldoSiswa = DB::table('tabungan')->where('nis', $datasiswa->nis)->get();
			foreach($saldoSiswa as $transaksi){
				$date = $transaksi->updated_at;
				$month = date('Y', strtotime($date));
				$year = date('Y', strtotime($date));
				//kalo lewat dari bulan juli(7 keatas) maka tahun +1
				if($month>7){
					$tahun1 = (string)$year."/";
					$tahun2Int = (int)$year + 1;
					$tahun2 = (string)$tahun2Int; 
					$periode = $tahun1.$tahun2;
					$transaksi->periode = $periode;
				}else{
					$tahun1 = (string)$year;
					$tahun2Int = (int)$year - 1;
					$tahun2 = (string)$tahun2Int."/"; 
					$periode = $tahun2.$tahun1;
					$transaksi->periode = $periode;
				}
				
				//Get status tabungan
				$dataTabungan = DB::table('jenis_tabungan')->where('nama', $transaksi->jenis_tabungan)->first();
				$statusTabungan = $dataTabungan->aktif;
				if($statusTabungan!=1){
					$transaksi->status_tabungan = "Non Aktif";
				}
				else{
					$transaksi->status_tabungan = "Aktif";
				}

			}
			if(count($saldoSiswa)>0){
				return response()->json([
						'status' => 'success',
						'data_saldo' => $saldoSiswa
					]);
			}
			else{
				$res['message'] = "Empty!";
				return response($res);
			}
		}else{
			$res['message'] = "Login Needed!";
			return response($res);
		}
	}

	//List seluruh jenis tabungan yang terdapat pada tabel jenis_tabungan
	public function getJenisTabungan(){
		$data_jenis = DB::table('jenis_tabungan')->get();
		if(count($data_jenis)>0){
			return response()->json([
				'status' => 'success',
				'data_jenis' => $data_jenis
			]);
		}
		else{
			return response()->json([
				'status' => 'error'
			]);
		}
	}
	
	//Pakai edit no_telp milik melani
	public function editNomor(Request $request){
		$datasiswa = siswa::where('token', $request->token)->first();
		if ($datasiswa!=null) {
			$datasiswa = siswa::where('token', $request->token)->update([
				'telp_ortu' => $request->telp_ortu
			]);
			$res['message'] = "Edit nomor telepon sukses";
			return response($res);
		}else{
			$res['message'] = "Siswa Tidak ditemukan!";
			return response($res);
		}
	}

	//Parameter 1) token 2) katasandilama 3) katasandibaru
	public function editPassword(Request $request){
		$datasiswa = siswa::where('token', $request->token)->where('katasandi', $request->katasandilama)->first();
		if ($datasiswa!=null) {
			$datasiswa = siswa::where('token', $request->token)->update([
				'katasandi' => $request->katasandibaru
			]);
			$res['message'] = "Password Berhasil Diubah!";
			return response($res);
		}else{
			$res['message'] = "Password Lama Salah!";
			return response($res);
		}
	}

	//saldo all dari api melani
	public function getSaldoAll($token){
		$datasiswa = siswa::where('token', $token)->first();
		if ($datasiswa!=null) {
			$saldoSiswa = DB::table('tabungan')->where('nis', $datasiswa->nis)->get();
			if(count($saldoSiswa)>0){
				return response($saldoSiswa);
			}
			else{
				$res['message'] = "Empty!";
				return response($res);
			}
		}else{
			$res['message'] = "Login Needed!";
			return response($res);
		}
	}

	//menampilkan seluruh list transaksi yaitu id, jenis tabungan saja, dan nis milik siswa.
	public function getAllTransaksi($token){
		$datasiswa = siswa::where('token', $token)->first();
		if($datasiswa!=null){
			$data_transaksi = DB::table('transaksi')->get(); 
			if(count($data_transaksi)>0){
				return response()->json([
					'status' => 'success',
					'data_transaksi' => $data_transaksi
				]);
			}
			else{
				return response()->json([
					'status' => 'empty',
				]);
			}
		}
		else{
			return response()->json([
				'status' => 'error',
			]);
		}
	}	

	//TIDAK DIPAKAI
	/*public function siswaLogin(Request $request)
    {
		$email = $request->email;
		$password = $request->password;
        $datasiswa = siswa::where('email', $email)->where('katasandi', $password)->first();
        if ($datasiswa!=null) {
            $tokensiswa = new siswa; //Instansiasi Objek biar bisa panggil static function
			$datasiswa->token = $tokensiswa->GenerateToken();
			$datasiswa->save();
			return response()->json([
				'status' => 'success',
				'data_siswa' => $datasiswa
			]);
        }
		else{
			return response()->json([
			'status' => 'fail',
		]);}
		}*/

	/*public function siswaLogout($token)
    {
        $datasiswa = siswa::where('token', $token)->first();
        if ($datasiswa!=null) {
			if($datasiswa->token != null){
				$tokensiswa = new siswa;
				$datasiswa->token = $tokensiswa->RemoveToken();
				$datasiswa->save();
				$res['message'] = "Logout Success!";
				return response($res);
			}else{
				$res['message'] = "Logout Success!";
				return response($res);
			}
        }
		else{
			$res['message'] = "Data Not Found!";
			return response($res);
		}
	}*/

	public function getTransaksi($jenistabungan,$token){
		$datasiswa = siswa::where('token', $token)->first();
		if ($datasiswa!=null) {
			$data = DB::table('transaksi')->where('jenis_tabungan', $jenistabungan)->where('nis', $datasiswa->nis)->get();		
			if(count($data)>0){
				return response()->json([
					'status' => 'success',
					'data' => $data
				]);
			}
			else{
				return response()->json([
					'status' => 'empty',
				]);
			}
		}else{
			$res['message'] = "Login Needed!";
			return response($res);
		}
	}

	//SALDO ALL USING NIS
	public function getSaldoAllNis($nis){
		$datasiswa = siswa::where('nis', $nis)->first();
		if ($datasiswa!=null) {
			$saldoSiswa = DB::table('tabungan')->where('nis', $datasiswa->nis)->get();
			if(count($saldoSiswa)>0){
				return response($saldoSiswa);
			}
			else{
				$res['message'] = "Empty!";
				return response($res);
			}
		}else{
			$res['message'] = "Login Needed!";
			return response($res);
		}
	}

	//GET SALDO SISWA USING TOKEN
	public function getSaldo($jenistabungan, $token){
		$datasiswa = siswa::where('token', $token)->first();
		if ($datasiswa!=null) {
			$saldoSiswa = DB::table('tabungan')->where('nis', $datasiswa->nis)->where('jenis_tabungan', $jenistabungan)->get();
			if(count($saldoSiswa)>0){
				return response()->json([
						'status' => 'success',
						'data_transaksi' => $saldoSiswa
					]);
			}
			else{
				$res['message'] = "Empty!";
				return response($res);
			}
		}else{
			$res['message'] = "Login Needed!";
			return response($res);
		}
	}

	//Detail Tabungan
	public function getDetailTabungan($jenistabungan,$token){
		$datasiswa = siswa::where('token', $token)->first();
		if ($datasiswa!=null) {
			//ambil status tabungan
			$data = DB::table('jenis_tabungan')->where('nama',$jenistabungan)->get();
			if(count($data)>0){
				foreach($data as $tabungan){
					$deskripsi_tabungan=$tabungan->deskripsi;
					if($tabungan->aktif == 1){
						$status_tabungan="aktif";
					}
					else{
						$status_tabungan="nonaktif";
					}
				}
			}
			else{
				$status_tabungan="error!";
				$deskripsi_tabungan="error!";
			}

			//ambil total saldo tabungan 
			$data = DB::table('tabungan')->where('nis',$datasiswa->nis)->where('jenis_tabungan',$jenistabungan)->get();
			if(count($data)>0){
				foreach($data as $datasaldo){
					$saldoTabungan=$datasaldo->saldo;
				}
			}
			else{
				$saldoTabungan=-1;
			}

			//ambil nama wali kelas
			//1_ambil kelas mengunakan nis
			//2_ambil nama wali kelas dari tabel kelas
			$data = DB::table('siswa')->where('token', $token)->get();
			if(count($data)>0){
				foreach($data as $datasiswa){
					$kelas_siswa = $datasiswa->kelas;
					$data = DB::table('kelas')->where('kelas',$kelas_siswa)->get();
					if(count($data)>0){
						foreach($data as $dataguru){
							$nama_guru=$dataguru->wali_kelas;
						}
					}
					else{
						$nama_guru="-";
					}
				}
			}
			else{
				$nama_guru="-";
			}

			//ambil detail transaksi terakhir
			$data = DB::table('transaksi')->where('nis',$datasiswa->nis)->orderByDesc('created_at')->get();
			if(count($data)>0){
				foreach($data as $transaksi){
					$nominal_trans=$transaksi->nominal;
					$jenis_trans=$transaksi->jenis_transaksi;
					$tanggal_trans=$transaksi->created_at;
					break;
				}
			}
			else{
				$nominal_trans=-1;
				$jenis_trans="error!";
				$tanggal_trans="error!";
			}
			$res['message'] = "Success!";
			$res['nis'] = $datasiswa->nis;
			$res['jenis_tabungan'] = $jenistabungan;
			$res['status'] = $status_tabungan;
			$res['deskripsi'] = $deskripsi_tabungan;
			$res['total_saldo'] = $saldoTabungan;
			$res['wali_kelas'] = $nama_guru;
			$res['nominal_terakhir'] = $nominal_trans;
			$res['jenis_transaksi'] = $jenis_trans;
			$res['tanggal'] = $tanggal_trans;
		}else{
			$res['message'] = "Failed!";
			$res['nis'] = $nis;
			$res['jenis_tabungan'] = $jenistabungan;
			$status_tabungan="error!";
			$deskripsi_tabungan="error!";
			$res['total_saldo'] = -1;
			$res['wali_kelas'] = "-";
			$res['nominal_terakhir'] = -1;
			$res['jenis_transaksi'] = "error!";
			$res['tanggal'] = "error!";
		}
        return response($res);
	}
		//public function siswaLogin($username,$password)
    //{
    //    $datasiswa = siswa::where('nama_pengguna', $username)->where('katasandi', $password)->first();
    //    if ($datasiswa!=null) {
    //        $tokensiswa = new siswa; //Instansiasi Objek biar bisa panggil static function
	//		$datasiswa->token = $tokensiswa->GenerateToken();
	//		$datasiswa->save();
	//		return response($datasiswa);
    //    }
	//	else{
	//		$res['message'] = "Login Failed!";
	//		return response($res);
	//	}
	//}
}

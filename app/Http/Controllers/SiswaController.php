<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\siswa;
use App\Kelas;
use App\KelasModel;
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

class SiswaController extends Controller
{
    public function index(Request $request, Builder $htmlBuilder)
    {	
        if ($request->ajax()) {
            $data = DB::table('siswa')->get(); 
            return Datatables::of($data)//->make(true);
                 ->addColumn('action', function($data) { // menambahkan tabel berisikan 2 fungsi. Penambahan kolom jenis ini dapat dilakukan
                    return view('datatable._action', [   //  apabila informasi dari kolom berada diluar dari tabel  yang ingin di tampilkan 
                        'model'             => $data,     
                        'form_url'          => route('siswa.destroy', $data->id), //fungsi untuk menghapus siswa ynng dipilih
                        'edit_url'          => route('siswa.edit', $data->id), //fungsi untuk menghapus siswa ynng dipilih
                        'confirm_message'    => 'Yakin mau menghapus ' . $data->nama_lengkap . '?' //menampilkan pop-up sebelum data akan dihapus
                    ]);
				})
				->addColumn('status', function($data) {
						//fungsi persotoyan
						if ($data->aktif == 1){
							return "aktif";
						}
						else{
							return "nonaktif";
						}
				
					})
			->make(true);
        }
        $html = $htmlBuilder
            ->addColumn(['data' => 'nis', 'name' => 'nis', 'title' => 'NIS']) //menambahkan kolom NIS, data dan name diisikan field pada tabel
            ->addColumn(['data' => 'nama_lengkap', 'name' => 'nama_lengkap', 'title' => 'Nama Lengkap']) //menambahkan kolom Nama Lengkap
            ->addColumn(['data' => 'kelas', 'name' => 'kelas', 'title' => 'Kelas']) //menambahkan kolom Kelas
            ->addColumn(['data' => 'angkatan', 'name' => 'angkatan', 'title' => 'Angkatan']) //menambahkan kolom Angkatan
            ->addColumn(['data' => 'ttl', 'name' => 'ttl', 'title' => 'TTL']) //menambahkan kolom TTL
            ->addColumn(['data' => 'telp_ortu', 'name' => 'telp_ortu', 'title' => 'Telp Ortu']) //menambahkan kolom Telp Ortu
            ->addColumn(['data' => 'email', 'name' => 'email', 'title' => 'Email']) //menambahkan kolom Email
            ->addColumn(['data' => 'nama_pengguna', 'name' => 'nama_pengguna', 'title' => 'Nama Pengguna']) //menambahkan kolom Nama Pengguna
            //->addColumn(['data' => 'katasandi', 'name' => 'katasandi', 'title' => 'Kata Sandi']) //menambahkan kolom Kata Sandi
            //->addColumn(['data' => 'aktif', 'name' => 'aktif', 'title' => 'Aktif']) //menambahkan kolom Aktif
			->addColumn(['data' => 'status', 'name' => 'status', 'title' => 'Status'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false]);
            // menambahkan kolom Action, kolom ini tidak dapat di urutkan maupun dicari, maka perlu dilakukan penambahan 'orderable' => false,
            // 'searchable' => false
        return view('siswa.index')->with(compact('html')); //Mengirimkan Data Tables untuk di tampilkan pada view siswa.index
    }
    public function create()
    {
        return view('siswa.create');
    }
    public function destroy(Request $request, $id)
    {
		$data_siswa = DB::table('siswa')->where('id',$id)->first();
		$nama_siswa = $data_siswa->nama_lengkap;
        $data = DB::table('siswa')->where('id',$id)->delete();

        // Handle hapus buku via ajax
        if ($request->ajax()) return response()->json(['id' => $id]);

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Data siswa ".$nama_siswa." berhasil dihapus!"
        ]);

        return redirect()->route('siswa.index');
    }

    public function edit($id)
    {
        $data = DB::table('siswa')->where('id',$id)->first();
        return view('siswa.edit')->with(compact('data'));
    }

    public function update(Request $request, $id)
    {
		$tahun = date("Y");
		$this->validate($request, [
            'nis' => 'required|numeric|min:10|unique:siswa,nis,'.$id ,
            'nama_lengkap' => 'required|regex:/^[\pL\s\-]+$/u',
            'avatar' => 'nullable',
			'kelas' => 'required|alpha_dash|exists:kelas,kelas' ,
			'angkatan' => 'required|numeric|max:'.(int)$tahun ,
			'email' => 'required|email|unique:siswa,email,'.$id,
			'nama_pengguna' => 'required|alpha_dash' ,
			'katasandi' => 'required',
			'telp_ortu' => 'required|numeric',
			'ttl' => 'required|date_format:Y-m-d',
        ], [
			'nis.required' => 'Anda belum memasukan nomor induk siswa!',
			'nis.numeric' => 'nis hanya dapat terdiri dari angka!',
            'nis.min' => 'nis tidak valid!',
			'nis.unique' => 'nis sudah terdaftar!',
			'nama_lengkap.required' => 'Anda belum memasukan nama siswa!',
			'nama_lengkap.regex' => 'nama hanya dapat terdiri dari alfabet dan spasi!',            
			'kelas.required' => 'Anda belum memasukan kelas siswa!',
			'kelas.alpha_dash' => 'Kelas hanya dapat terdiri dari alfabet, angka, _ , dan - . contoh : VII-A',
			'kelas.exist' => 'Kelas belum terdaftar!',
			'angkatan.required' => 'Anda belum memasukan angkatan siswa!',
			'angkatan.numeric' => 'Angkatan hanya dapat terdiri dari angka!',
			'angkatan.max' => 'Angkatan melebihi tahun sekarang-_-!',
			'telp_ortu.required' => 'Anda belum memasukan nomor telefon walimurid!',
			'telp_ortu.numeric' => 'Nomor telefon hanya dapat terdiri dari angka!',
			'email.required' => 'Anda belum memasukan email siswa!',
			'email.email' => 'Email tidak valid!',
			'email.unique' => 'Email sudah terdaftar pada sistem!',
			'nama_pengguna.required' => 'Anda belum memasukan nama_pengguna siswa!',
			'nama_pengguna.alpha_dash' => 'Nama pengguna hanya dapat terdiri dari alfabet, angka, _ , dan - . contoh : Tina_12',
			'katasandi.required' => 'Anda belum memasukan katasandi!',
			'ttl.required' => 'Anda belum memasukan TTL!',
			'ttl.date_format' => 'Format tanggal salah! Format: Tahun-Bulan-Hari',
        ]);
		
        if($request->aktif!=1){
            $request->aktif=0;
        }
        $data = DB::table('siswa')->where('id',$id)->first();
        $updated = DB::table('siswa')->where('id',$id)->update([
            'nis' => $request->nis,
            'nama_lengkap' => $request->nama_lengkap,
            'kelas' => $request->kelas,
            'angkatan' => $request->angkatan,
            'ttl' => $request->ttl,
            'telp_ortu' => $request->telp_ortu,
            'email' => $request->email,
            'nama_pengguna' => $request->nama_pengguna,
            'katasandi' => bcrypt($request->katasandi),
            'aktif' => $request->aktif,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // UpdateAvatar
        if ($request->hasFile('avatar')) {

            // Mengambil cover yang diupload berikut ekstensinya
            $filename = null;
            $uploaded_avatar = $request->file('avatar');
            $extension = $uploaded_avatar->getClientOriginalExtension();

            // Membuat nama file random dengan extension
            $filename = md5(time()) . '.' . $extension;
            $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';

            // Memindahkan file ke folder public/img
            $uploaded_avatar->move($destinationPath, $filename);

            // Hapus cover lama, jika ada
            if ($data->avatar!=null) {
                $old_avatar = $data->avatar;

                // Jika tidak menggunakan member_avatar.png / admin_avatar.png hapus avatar
                if (!$old_avatar == "member_avatar.png" || "admin_avatar.png") {
                    $filepath = public_path() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $data->avatar;

                    try {
                        File::delete($filepath);
                    } catch (FileNotFoundException $e) {
                        // File sudah dihapus/tidak ada
                    }
                }
            }
            $updated = DB::table('siswa')->where('id',$data->id)->update([
                'avatar' => $filename
            ]);
        }
        
        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Siswa ".$request->nama_lengkap." berhasil diubah! "
        ]);
        
        return redirect()->route('siswa.index');

    }    

    public function store(Request $request)
    {
		$tahun = date("Y");
		$this->validate($request, [
            'nis' => 'required|numeric|min:10|unique:siswa,nis' ,
            'nama_lengkap' => 'required|regex:/^[\pL\s\-]+$/u',
            'avatar' => 'nullable',
			'kelas' => 'required|alpha_dash|exists:kelas,kelas' ,
			'angkatan' => 'required|numeric|max:'.(int)$tahun ,
			'email' => 'required|email|unique:siswa,email',
			'nama_pengguna' => 'required|alpha_dash' ,
			'katasandi' => 'required',
			'telp_ortu' => 'required|numeric',
			'ttl' => 'required|date_format:Y-m-d',
        ], [
			'nis.required' => 'Anda belum memasukan nomor induk siswa!',
			'nis.numeric' => 'nis hanya dapat terdiri dari angka!',
            'nis.min' => 'nis tidak valid!',
			'nis.unique' => 'nis sudah terdaftar!',
			'nama_lengkap.required' => 'Anda belum memasukan nama siswa!',
			'nama_lengkap.regex' => 'nama hanya dapat terdiri dari alfabet dan spasi!',            
			'kelas.required' => 'Anda belum memasukan kelas siswa!',
			'kelas.alpha_dash' => 'Kelas hanya dapat terdiri dari alfabet, angka, _ , dan - . contoh : VII-A',
			'kelas.exists' => 'Kelas belum terdaftar!',
			'angkatan.required' => 'Anda belum memasukan angkatan siswa!',
			'angkatan.numeric' => 'Angkatan hanya dapat terdiri dari angka!',
			'angkatan.max' => 'Angkatan melebihi tahun sekarang-_-!',
			'telp_ortu.required' => 'Anda belum memasukan nomor telefon walimurid!',
			'telp_ortu.numeric' => 'Nomor telefon hanya dapat terdiri dari angka!',
			'email.required' => 'Anda belum memasukan email siswa!',
			'email.email' => 'Email tidak valid!',
			'email.unique' => 'Email sudah terdaftar pada sistem!',
			'nama_pengguna.required' => 'Anda belum memasukan nama_pengguna siswa!',
			'nama_pengguna.alpha_dash' => 'Nama pengguna hanya dapat terdiri dari alfabet, angka, _ , dan - . contoh : Tina_12',
			'katasandi.required' => 'Anda belum memasukan katasandi!',
			'ttl.required' => 'Anda belum memasukan TTL!',
			'ttl.date_format' => 'Format tanggal salah! Format: Tahun-Bulan-Hari',
        ]);
		
        if($request->aktif!=1){
            $request->aktif=0;
        }

        $created = siswa::create([
            'nis' => $request->nis,
            'nama_lengkap' => $request->nama_lengkap,
            'kelas' => $request->kelas,
            'angkatan' => $request->angkatan,
            'ttl' => $request->ttl,
            'telp_ortu' => $request->telp_ortu,
            'email' => $request->email,
            'nama_pengguna' => $request->nama_pengguna,
            'katasandi' => bcrypt($request->katasandi),
            'aktif' => $request->aktif,
            'token' => null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Isi field cover jika ada cover yang diupload
        if ($request->hasFile('avatar')) {

            // Mengambil cover yang diupload berikut ekstensinya
            $filename = null;
            $uploaded_avatar = $request->file('avatar');
            $extension = $uploaded_avatar->getClientOriginalExtension();

            // Membuat nama file random dengan extension
            $filename = md5(time()) . '.' . $extension;
            $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';

            // Memindahkan file ke folder public/img
            $uploaded_avatar->move($destinationPath, $filename);

            // Hapus cover lama, jika ada
            if ($created->avatar!=null) {
                $old_avatar = $created->avatar;

                // Jika tidak menggunakan member_avatar.png / admin_avatar.png hapus avatar
                if (!$old_avatar == "member_avatar.png" || "admin_avatar.png") {
                    $filepath = public_path() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $created->avatar;

                    try {
                        File::delete($filepath);
                    } catch (FileNotFoundException $e) {
                        // File sudah dihapus/tidak ada
                    }
                }
            }

            $created->avatar = $filename;
        }
        $created->save();

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Siswa ".$request->nama_lengkap." berhasil ditambahkan! "
        ]);
        return redirect()->route('siswa.index');
    }
	public function konfirmasi()
    {
        return view('siswa.konfirmasi');
    }
	public function perbaruistatus(Request $request)
    {
        $this->validate($request, [
            'konfirmasi' => 'required|in:Konfirmasi Ubah Status Siswa'
        ], [
			'konfirmasi.required' => 'Anda belum memasukan konfirmasi!',
			'konfirmasi.in' => 'Pesan Konfirmasi Salah!',
        ]);
		
		//if ($request->konfirmasi == "Konfirmasi Ubah Status Siswa"){
			$tahun_ini = date("Y");
			$int_tahun_ini = date("Y", strtotime($tahun_ini));
			
			//Update Data Siswa
			$data = DB::table('siswa')->get();
			if ($data != null){
				foreach($data as $siswa){
					if ($int_tahun_ini - $siswa->angkatan > 3){
						$updated = DB::table('siswa')->where('nis',$siswa->nis)->update([
						'aktif' => 0,
						'updated_at' => date('Y-m-d H:i:s')
						]);
					}
				}
				Session::flash("flash_notification", [
				"level" => "success",
				"icon" => "fa fa-check",
				"message" => "Berhasil mengubah status siswa! "
				]);
			}
			else{
				Session::flash("flash_notification", [
				"level" => "warning",
				"icon" => "fa fa-check",
				"message" => "Gagal! Belum terdapat data siswa!"
				]);
			}
			
		//}
		//else{
		//	Session::flash("flash_notification", [
		//		"level" => "warning",
		//		"icon" => "fa fa-check",
		//		"message" => "Pesan Konfirmasi Tidak Valid!"
		//	]);
		//}
		return redirect()->route('siswa.index');
    }
    
    // public function upload(Request $request, $id)
    // {
    //     $data = DB::table('siswa')->where('id',$id)->first();

    //     // Isi field cover jika ada cover yang diupload
    //     if ($request->hasFile('avatar')) {

    //         // Mengambil cover yang diupload berikut ekstensinya
    //         $filename = null;
    //         $uploaded_avatar = $request->file('avatar');
    //         $extension = $uploaded_avatar->getClientOriginalExtension();

    //         // Membuat nama file random dengan extension
    //         $filename = md5(time()) . '.' . $extension;
    //         $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';

    //         // Memindahkan file ke folder public/img
    //         $uploaded_avatar->move($destinationPath, $filename);

    //         // Hapus cover lama, jika ada
    //         if ($data->avatar!=null) {
    //             $old_avatar = $data->avatar;

    //             // Jika tidak menggunakan member_avatar.png / admin_avatar.png hapus avatar
    //             if (!$old_avatar == "member_avatar.png" || "admin_avatar.png") {
    //                 $filepath = public_path() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $data->avatar;

    //                 try {
    //                     File::delete($filepath);
    //                 } catch (FileNotFoundException $e) {
    //                     // File sudah dihapus/tidak ada
    //                 }
    //             }
    //         }
    //         // Ganti field cover dengan cover yang baru
    //         //$user->avatar = $filename;
    //         $updated = DB::table('siswa')->where('id',$id)->update([
    //             'avatar' => $filename,
    //             'updated_at' => date('Y-m-d H:i:s')
    //         ]);
    //     }
    //     //$user->save();

    //     Session::flash("flash_notification", [
    //         "level" => "success",
    //         "icon" => "fa fa-check",
    //         "message" => "Foto berhasil diupload"
    //     ]);

    //     return redirect()->route('siswa.index');
    // }
}

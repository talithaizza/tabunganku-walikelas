<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\siswa;
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
    //
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $data = DB::table('siswa')->get();
            return Datatables::of($data)//->make(true);
                 ->addColumn('action', function($data) {
                    return view('datatable._action', [
                        'model'             => $data,
                        'form_url'          => route('siswa.destroy', $data->id),
                        'edit_url'          => route('siswa.edit', $data->id),
                        'confirm_message'    => 'Yakin mau menghapus ' . $data->nama_lengkap . '?'
                    ]);
            })->make(true);
        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'nis', 'name' => 'nis', 'title' => 'NIS'])
            ->addColumn(['data' => 'nama_lengkap', 'name' => 'nama_lengkap', 'title' => 'Nama Lengkap'])
            ->addColumn(['data' => 'kelas', 'name' => 'kelas', 'title' => 'Kelas'])
            ->addColumn(['data' => 'angkatan', 'name' => 'angkatan', 'title' => 'Angkatan'])
            ->addColumn(['data' => 'ttl', 'name' => 'ttl', 'title' => 'TTL'])
            ->addColumn(['data' => 'telp_ortu', 'name' => 'telp_ortu', 'title' => 'Telp Ortu'])
            ->addColumn(['data' => 'email', 'name' => 'email', 'title' => 'Email'])
            ->addColumn(['data' => 'nama_pengguna', 'name' => 'nama_pengguna', 'title' => 'Nama Pengguna'])
            ->addColumn(['data' => 'katasandi', 'name' => 'katasandi', 'title' => 'Kata Sandi'])
            ->addColumn(['data' => 'aktif', 'name' => 'aktif', 'title' => 'Aktif'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false]);

        return view('siswa.index')->with(compact('html'));
    }
    public function create()
    {
        return view('siswa.create');
    }
    public function destroy(Request $request, $id)
    {
        $data = DB::table('siswa')->where('id',$id)->delete();

        // Handle hapus buku via ajax
        if ($request->ajax()) return response()->json(['id' => $id]);

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Data berhasil dihapus"
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
            'katasandi' => $request->katasandi,
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
            "message" => "Berhasil menyimpan! "//.$data->nama_lengkap
        ]);
        
        return redirect()->route('siswa.index');

    }    

    public function store(Request $request)
    {        
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
            'katasandi' => $request->katasandi,
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
            "message" => "Berhasil Menambahkan Data! "//.$data->nama_lengkap
        ]);
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

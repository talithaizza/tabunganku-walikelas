<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Book;
use App\User;
use App\Walikelas;
use App\Role;
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

class WalikelasController extends Controller
{
    //
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $data = DB::table('wali_kelas')->get();
            return Datatables::of($data) //->make(true);
                 ->addColumn('action', function($data) {
                     return view('datatable._action', [
                        'model'             => $data,
                        'form_url'          => route('walikelas.destroy', $data->id),
                        'edit_url'          => route('walikelas.edit', $data->id),
                        'confirm_message'    => 'Yakin mau menghapus ' . $data->nama_lengkap . '?'
                     ]);
            })->make(true);
        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'nip', 'name' => 'nip', 'title' => 'NIP'])
            ->addColumn(['data' => 'nama_lengkap', 'name' => 'nama_lengkap', 'title' => 'Nama Lengkap'])
            ->addColumn(['data' => 'alamat', 'name' => 'alamat', 'title' => 'Alamat'])
            ->addColumn(['data' => 'telepon', 'name' => 'telepon', 'title' => 'Telepon'])
            ->addColumn(['data' => 'email', 'name' => 'email', 'title' => 'Email'])
            ->addColumn(['data' => 'nama_pengguna', 'name' => 'nama_pengguna', 'title' => 'Nama Pengguna'])
            ->addColumn(['data' => 'katasandi', 'name' => 'katasandi', 'title' => 'Kata Sandi'])
            ->addColumn(['data' => 'aktif', 'name' => 'aktif', 'title' => 'Aktif'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false]);

        return view('walikelas.index')->with(compact('html'));
    }

    public function create()
    {
        return view('walikelas.create');
    }
    public function destroy(Request $request, $id)
    {
        $walikelas = DB::table('wali_kelas')->where('id',$id)->first();
        $data = DB::table('wali_kelas')->where('id',$id)->delete();
        $data = DB::table('users')->where('name',$walikelas->nama_lengkap)->delete();

        // Handle hapus buku via ajax
        if ($request->ajax()) return response()->json(['id' => $id]);

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Data berhasil dihapus"
        ]);

        return redirect()->route('walikelas.index');
    }

    public function edit($id)
    {
        $data = DB::table('wali_kelas')->where('id',$id)->first();
        return view('walikelas.edit')->with(compact('data'));
    }

    public function update(Request $request, $id)
    {
        if($request->aktif!=1){
            $request->aktif=0;
        }
        $data = DB::table('wali_kelas')->where('id',$id)->first();

        //Update Tabel Walikelas
        $updateOne = DB::table('wali_kelas')->where('id',$id)->update([
            'nip' => $request->nip,
            'nama_lengkap' => $request->nama_lengkap,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'nama_pengguna' => $request->nama_pengguna,
            'katasandi' => $request->katasandi,
            'aktif' => $request->aktif,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        //Update Tabel Users
        $updateTwo = DB::table('users')->where('name',$data->nama_lengkap)->update([
            'name' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => bcrypt($request->katasandi),
            'updated_at' => date('Y-m-d H:i:s')
        ]);



        // Remove File lama
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
            $updateOne = DB::table('wali_kelas')->where('id',$id)->update([
                'avatar' => $filename
            ]);
            $updateTwo = DB::table('users')->where('name',$data->nama_lengkap)->update([
                'avatar' => $filename
            ]);
        }
        //$updateTwo->save();

        
        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Berhasil menyimpan! "//.$data->nama_lengkap
        ]);
        return redirect()->route('walikelas.index');
    }    

    public function store(Request $request)
    {   
        if($request->aktif!=1){
            $request->aktif=0;
        }
        
        $theFileName=null;
        //Register To System
        $user = User::create([
            'name' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => bcrypt($request->katasandi),
            'is_verified'=> true,
        ]);

        // Isi field cover jika ada avatar yang diupload
        if ($request->hasFile('avatar')) {
            // Mengambil file yang diupload
            $uploaded_avatar = $request->file('avatar');

            // Mengambil extension file
            $extension = $uploaded_avatar->getClientOriginalExtension();

            // Membuat nama file random berikut extension
            $filename = md5(time()) . "." . $extension;

            // Menyimpan cover ke folder public/img
            $destinationPath = public_path() . DIRECTORY_SEPARATOR . 'img';
            $uploaded_avatar->move($destinationPath, $filename);

            // Mengisi field cover di book dengan filename yang baru dibuat
            $user->avatar = $filename;
            $theFileName = $filename;
            $user->save();

        } else {

            // Jika tidak ada cover yang diupload, pilih member_avatar.png
            $filename = "member_avatar.png";
            $theFileName = $filename;
            $user->avatar = $filename;
            $user->save();
        }
        $memberRole = Role::where('name', 'walikelas')->first();
        $user->attachRole($memberRole);

        $created = Walikelas::create([
            'nip' => $request->nip,
            'nama_lengkap' => $request->nama_lengkap,
            'alamat' => $request->alamat,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'nama_pengguna' => $request->nama_pengguna,
            'katasandi' => $request->katasandi,
            'aktif' => $request->aktif,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'avatar' => $theFileName
        ]);

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Berhasil Menambahkan Data! "//.$data->nama_lengkap
        ]);
        $user->verify();
        return redirect()->route('walikelas.index');
    }    

    public function upload(Request $request, $id)
    {
        $getName = DB::table('wali_kelas')->where('id',$id)->first();
        $user = User::where('name',$getName->nama_lengkap)->first();

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
            if ($user->avatar!=null) {
                $old_avatar = $user->avatar;

                // Jika tidak menggunakan member_avatar.png / admin_avatar.png hapus avatar
                if (!$old_avatar == "member_avatar.png" || "admin_avatar.png") {
                    $filepath = public_path() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $user->avatar;

                    try {
                        File::delete($filepath);
                    } catch (FileNotFoundException $e) {
                        // File sudah dihapus/tidak ada
                    }
                }
            }
            // Ganti field cover dengan cover yang baru
            $user->avatar = $filename;
            $user->save();
            $updated = DB::table('wali_kelas')->where('nama_lengkap',$user->name)->update([
                'avatar' => $filename
            ]);
        }
        //$user->save();

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Foto berhasil diupload"
        ]);

        return redirect()->route('walikelas.index');
    }
}

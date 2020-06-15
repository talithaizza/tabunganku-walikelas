<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

class SiswaViewController extends Controller
{
    //
    public function index(Request $request, Builder $htmlBuilder)
    {
        //cari kelas dari guru yang login
		$nama_guru = Auth::user()->name;
		$kelas_guru = DB::table('kelas')->where('wali_kelas',$nama_guru)->first();
		
        if ($request->ajax()) {
            $data = DB::table('siswa')->where('kelas',$kelas_guru->kelas)->get();
            return Datatables::of($data)//->make(true);
                ->addColumn('aktif', function($data) {
                //mengubah status string dari boolean
                if ($data->aktif == 1){
                    return "aktif";
                }
                else{
                    return "nonaktif";
                }
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
            //->addColumn(['data' => 'katasandi', 'name' => 'katasandi', 'title' => 'Kata Sandi'])
            ->addColumn(['data' => 'aktif', 'name' => 'aktif', 'title' => 'Aktif']);
            //->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false]);

        return view('siswa_view.index')->with(compact('html'));
    }

    // public function destroy($id)
    // {
    //     $data = DB::table('siswa')->where('id',$id)->delete();

    //     // Handle hapus buku via ajax
    //     if ($request->ajax()) return response()->json(['id' => $id]);

    //     Session::flash("flash_notification", [
    //         "level" => "success",
    //         "icon" => "fa fa-check",
    //         "message" => "Data berhasil dihapus"
    //     ]);

    //     return redirect()->route('siswa_view.index');
    // }
}

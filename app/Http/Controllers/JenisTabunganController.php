<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Book;
use App\JenisTabungan;
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

class JenisTabunganController extends Controller
{
    //
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $data = DB::table('jenis_tabungan')->get();
            return Datatables::of($data)//->make(true);
                 ->addColumn('action', function($data) {
                     return view('datatable._action', [
                        'model'             => $data,
                        'form_url'          => route('jenistabungan.destroy', $data->id),
                        'edit_url'          => route('jenistabungan.edit', $data->id),
                        'confirm_message'    => 'Yakin mau menghapus Tabungan ' . $data->nama . '?'
                     ]);
            })->make(true);
        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'nama', 'name' => 'nama', 'title' => 'Nama Tabungan'])
            ->addColumn(['data' => 'deskripsi', 'name' => 'deskripsi', 'title' => 'Deskripsi'])
            ->addColumn(['data' => 'aktif', 'name' => 'aktif', 'title' => 'Aktif'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false]);

        return view('jenistabungan.index')->with(compact('html'));
    }

    public function create()
    {
        return view('jenistabungan.create');
    }
    public function destroy(Request $request, $id)
    {
        $data = DB::table('jenis_tabungan')->where('id',$id)->delete();

        // Handle hapus buku via ajax
        if ($request->ajax()) return response()->json(['id' => $id]);

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Data berhasil dihapus"
        ]);

        return redirect()->route('jenistabungan.index');
    }

    public function edit($id)
    {
        $data = DB::table('jenis_tabungan')->where('id',$id)->first();
        return view('jenistabungan.edit')->with(compact('data'));
    }

    public function update(Request $request, $id)
    {
        if($request->aktif!=1){
            $request->aktif=0;
        }
        
        $data = DB::table('jenis_tabungan')->where('id',$id)->first();
        $updated = DB::table('jenis_tabungan')->where('id',$id)->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'aktif' => $request->aktif,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Berhasil menyimpan! "//.$data->nama_lengkap
        ]);
        return redirect()->route('jenistabungan.index');
    }    

    public function store(Request $request)
    {
        $created = JenisTabungan::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'aktif' => $request->aktif,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Berhasil Menambahkan Data! "//.$data->nama_lengkap
        ]);
        return redirect()->route('jenistabungan.index');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Book;
use App\Kelas;
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

class KelasController extends Controller
{
    //
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $data = DB::table('kelas')->get();
            return Datatables::of($data)//->make(true);
                 ->addColumn('action', function($data) {
                     return view('datatable._action', [
                        'model'             => $data,
                        'form_url'          => route('kelas.destroy', $data->id),
                        'edit_url'          => route('kelas.edit', $data->id),
                        'confirm_message'    => 'Yakin mau menghapus ' . $data->kelas . '?'
                     ]);
            })->make(true);
        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'kelas', 'name' => 'kelas', 'title' => 'Kelas'])
            ->addColumn(['data' => 'wali_kelas', 'name' => 'wali_kelas', 'title' => 'Wali Kelas'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false]);

        return view('kelas.index')->with(compact('html'));
    }

    public function create()
    {
        return view('kelas.create');
    }
    public function destroy(Request $request, $id)
    {
        $data = DB::table('kelas')->where('id',$id)->delete();

        // Handle hapus buku via ajax
        if ($request->ajax()) return response()->json(['id' => $id]);

        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Data berhasil dihapus"
        ]);

        return redirect()->route('kelas.index');
    }

    public function edit($id)
    {
        $data = DB::table('kelas')->where('id',$id)->first();
        return view('kelas.edit')->with(compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = DB::table('kelas')->where('id',$id)->first();
        $updated = DB::table('kelas')->where('id',$id)->update([
            'kelas' => $request->kelas,
            'wali_kelas' => $request->wali_kelas,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Berhasil menyimpan! "//.$data->nama_lengkap
        ]);
        return redirect()->route('kelas.index');
    }    

    public function store(Request $request)
    {
        $created = Kelas::create([
            'kelas' => $request->kelas,
            'wali_kelas' => $request->wali_kelas,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Berhasil Menambahkan Data! "//.$data->nama_lengkap
        ]);
        return redirect()->route('kelas.index');
    }
}

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

class JenisTabunganViewController extends Controller
{
    //
    public function index(Request $request, Builder $htmlBuilder)
    {
        if ($request->ajax()) {
            $data = DB::table('jenis_tabungan')->get();
            return Datatables::of($data)//->make(true);
            ->addColumn('aktif', function($data) {
                if ($data->aktif == 1){
                    return "aktif";
                }
                else{
                    return "nonaktif";
                }
        })->make(true);
            

        }

        $html = $htmlBuilder
            ->addColumn(['data' => 'nama', 'name' => 'nama', 'title' => 'Nama Tabungan'])
            ->addColumn(['data' => 'deskripsi', 'name' => 'deskripsi', 'title' => 'Deskripsi'])
            ->addColumn(['data' => 'aktif', 'name' => 'aktif', 'title' => 'Aktif']);
            
        return view('jenistabungan_view.index')->with(compact('html'));
    }

    // public function destroy($id)
    // {
    //     $data = DB::table('walikelas')->where('id',$id)->delete();

    //     // Handle hapus buku via ajax
    //     if ($request->ajax()) return response()->json(['id' => $id]);

    //     Session::flash("flash_notification", [
    //         "level" => "success",
    //         "icon" => "fa fa-check",
    //         "message" => "Data berhasil dihapus"
    //     ]);

    //     return redirect()->route('walikelas.index');
    // }
}

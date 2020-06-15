<?php

namespace App\Http\Controllers;
use Laratrust\LaratrustFacade as Laratrust;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Book;
use App\Tabungan;
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
		
		if (Laratrust::hasRole('walikelas')) {
			$html = $htmlBuilder
				->addColumn(['data' => 'nama', 'name' => 'nama', 'title' => 'Nama Tabungan'])
				->addColumn(['data' => 'deskripsi', 'name' => 'deskripsi', 'title' => 'Deskripsi'])
				->addColumn(['data' => 'status', 'name' => 'status', 'title' => 'Status']);
		}
		else if (Laratrust::hasRole('admin')) {
			$html = $htmlBuilder
				->addColumn(['data' => 'nama', 'name' => 'nama', 'title' => 'Nama Tabungan'])
				->addColumn(['data' => 'deskripsi', 'name' => 'deskripsi', 'title' => 'Deskripsi'])
				->addColumn(['data' => 'status', 'name' => 'status', 'title' => 'Status'])
				->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false]);
		}
        return view('jenistabungan.index')->with(compact('html'));
    }

    public function create()
    {
        return view('jenistabungan.create');
    }
    public function destroy(Request $request, $id)
    {
		//Cek apakah data masih ada di tabel Tabungan
		$jenisTab = JenisTabungan::where('id',$id)->first();
		$dataTabungan = DB::table('tabungan')->where('jenis_tabungan', $jenisTab->nama)->first();
		//Kalo udah gaada data lagi boleh diapus
		if($dataTabungan == null){
			$data = DB::table('jenis_tabungan')->where('id',$id)->delete();
			// Handle hapus buku via ajax
			if ($request->ajax()) return response()->json(['id' => $id]);
			Session::flash("flash_notification", [
				"level" => "success",
				"icon" => "fa fa-check",
				"message" => "Data ".$jenisTab->nama." berhasil dihapus!",
			]);
		}
		else if ($dataTabungan != null){
			Session::flash("flash_notification", [
				"level" => "warning",
				"icon" => "fa fa-check",
				"message" => "Gagal! Data ".$jenisTab->nama." masih terdapat pada tabel Tabungan!",
			]);
		}
        return redirect()->route('jenistabungan.index');
    }

    public function edit($id)
    {
        $data = DB::table('jenis_tabungan')->where('id',$id)->first();
        return view('jenistabungan.edit')->with(compact('data'));
    }

    public function update(Request $request, $id)
    {
		// Validasi
        $this->validate($request, [
            //'nama' => 'required|alpha_dash|unique:jenis_tabungan,nama,'.$id,
            'deskripsi' => 'required',
        ], [
            //'nama.required' => 'Anda belum memasukan nama tabungan',
			//'nama.alpha_dash' => 'nama hanya dapat terdiri dari alfabet, angka, _ , dan - . contoh : Reguler_12',
			//'nama.unique' => 'Jenis tabungan sudah terdaftar!',
			'deskripsi.required' => 'Anda belum memasukan deskripsi',
        ]);
		
        if($request->aktif!=1){
            $request->aktif=0;
        }
        
        $data = DB::table('jenis_tabungan')->where('id',$id)->first();
        $updated = DB::table('jenis_tabungan')->where('id',$id)->update([
            //'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'aktif' => $request->aktif,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        Session::flash("flash_notification", [
            "level" => "success",
            "icon" => "fa fa-check",
            "message" => "Tabungan ".$data->nama." Berhasil di Perbarui!"
        ]);
        return redirect()->route('jenistabungan.index');
    }    

    public function store(Request $request)
    {
		// Validasi
        $this->validate($request, [
            'nama' => 'required|alpha_dash|unique:jenis_tabungan,nama',
            'deskripsi' => 'required',
        ], [
            'nama.required' => 'Anda belum memasukan nama tabungan',
			'nama.alpha_dash' => 'nama hanya dapat terdiri dari alfabet, angka, _ , dan - . contoh : Reguler_12',
			'nama.unique' => 'Jenis tabungan sudah terdaftar!',
			'deskripsi.required' => 'Anda belum memasukan deskripsi',
        ]);
		
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
            "message" => "Tabungan ".$request->nama." Berhasil di Tambahkan!"
        ]);
        return redirect()->route('jenistabungan.index');
    }
}

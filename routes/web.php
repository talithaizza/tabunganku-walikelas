<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//DIPAKAI

//Login Siswa
Route::post('api/login', 'myAPIController@siswaLogin');

//Logout
//Route::get('api/logout/{token}', 'myAPIController@siswaLogout');
Route::post('api/logout/{token}', 'myAPIController@siswaLogout');

//Profile Siswa
Route::get('api/detail_profile/{token}', 'myAPIController@getSiswa');

//List Transaksi Siswa
Route::get('api/transaksi/{token}', 'myAPIController@getTransaksiSiswa');

//GET SALDO SISWA
Route::get('api/getSaldo/{token}', 'myAPIController@getSaldoSiswa');

//List Tabungan
Route::get('api/tabungan', 'myAPIController@getJenisTabungan');

//EDIT NO TELP ORTU
Route::put('api/editTelp/{token}', 'myAPIController@editNomor');

//EDIT Password
//Route::put('api/editPasswd/{token}', 'myAPIController@editNomor');
Route::put('api/editPasswd', 'myAPIController@editPassword');

//GET SALDO
Route::get('api/getSaldoAll/{token}', 'myAPIController@getSaldoAll');

//GET WALI KELAS 
Route::get('api/getKelas/{token}', 'myAPIController@getKelas');

//TRANSAKSI TERATAS
Route::get('api/getTransaksiTerakhir/{token}', 'myAPIController@getTransaksiTerakhir');

//TIDAK DIPAKAI

//Registrasi Wali kelas
Route::get('registrasi/name/{name}/email/{email}/password/{password}', 'Auth\RegisterController@createNewUser');
//Route::get('api/login/{username}/pass/{password}', 'myAPIController@siswaLogin');

//List Transaksi 
Route::get('api/{jenistabungan}/t/{token}','myAPIController@getTransaksi');

//Detail Transaksi
Route::get('api/detail/{jenistabungan}/t/{token}', 'myAPIController@getDetailTabungan');

Route::get('api/getSaldo/{jenistabungan}/{token}', 'myAPIController@getSaldo');

Route::group(['midlleware' => 'web'], function() {

    // Auth
    Auth::routes();

    // Index
    Route::get('/', 'HomeController@index');

    Route::get('/home', 'HomeController@index');

    Route::resource('tabungansiswa', 'TabunganController');
    //
    // Member
    //

    // Daftar Peminjaman
    Route::get('member/books', 'BooksController@memberBook');

    // Daftar Buku untuk dipinjam
    Route::get('books/{books}/borrow', [
        'middleware' => ['auth', 'role:member'],
        'as' => 'member.books.borrow',
        'uses' => 'BooksController@borrow'
    ]);

    // Pengembalian buku
    Route::put('books/{book}/return', [
        'middleware' => ['auth', 'role:member'],
        'as' => 'member.books.return',
        'uses' => 'BooksController@returnBack'
    ]);

    //
    // Berlaku untuk Member & Admin
    //

    // Profile
    Route::get('settings/profile', 'SettingsController@profile')->name('settings.profile');

    // Edit Profile
    Route::get('settings/profile/edit', 'SettingsController@editProfile');

    // Update Profile
    Route::post('settings/profile', 'SettingsController@updateProfile');

    // Ubah password
    Route::get('settings/password', 'SettingsController@editPassword');
    Route::post('settings/password', 'SettingsController@updatePassword');

	// Buatan Arfan
	//Route::get('registrasi/name/{name}/email/{email}/password/{password}', 'Auth\RegisterController@createNewUser');
	//Route::get('api/login/{username}/pass/{password}', 'myAPIController@siswaLogin');

    //
    // Aktiviasi & Verifikasi Email
    //

    // Kirim Email Verifikasi waktu Register
    Route::get('auth/verify/{token}', 'Auth\RegisterController@verify');

    // Kirim Ulang email Verifikasi
    Route::get('auth/send-verification', 'Auth\RegisterController@sendVerification');

    // Admin
    Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function() {
        Route::resource('siswa', 'SiswaController');
        Route::resource('walikelas', 'WalikelasController');
        Route::resource('jenistabungan', 'JenisTabunganController');
        Route::resource('kelas', 'KelasController');
        Route::resource('authors', 'AuthorsController');
        Route::resource('books', 'BooksController');
        Route::resource('members', 'MembersController', [
            'only' => ['index', 'show', 'destroy']
        ]);
        
        // Daftar peminjaman
        Route::get('statistics', [
            'as' => 'statistics.index',
            'uses' => 'StatisticsController@index'
        ]);

        // Export buku
        Route::get('export/books', [
            'as' => 'export.books',
            'uses' => 'BooksController@export'
        ]);

        Route::post('export/books', [
            'as' => 'export.books.post',
            'uses' => 'BooksController@exportPost'
        ]);
        Route::post('export/upload', [
            'as' => 'export.books.post',
            'uses' => 'BooksController@exportPost'
        ]);
		
		// Export Tabungan
        Route::get('export/tabungan', [
            'as' => 'export.tabungan',
            'uses' => 'TabunganController@export'
        ]);
        Route::post('export/tabungan', [
            'as' => 'export.tabungan.post',
            'uses' => 'TabunganController@exportPost'
        ]);

        // Download template buku
        Route::get('template/books', [
            'as' => 'template.books',
            'uses' => 'BooksController@generateExcelTemplate'
        ]);

        // Import dari excel
        Route::post('import/books', [
            'as' => 'import.books',
            'uses' => 'BooksController@importExcel'
        ]);

        Route::put('walikelas/upload', [
            'as' => 'walikelas.upload',
            'uses' => 'WalikelasController@upload'
        ]);

        // Route::put('walikelas/upload', [
        //     'as' => 'walikelas.upload',
        //     'uses' => 'WalikelasController@upload'
        // ]);
        Route::put('walikelas/update/{id}', [
            'as' => 'walikelas.upload',
            'uses' => 'WalikelasController@upload'
        ]);
        Route::put('siswa/update/{id}', [
            'as' => 'siswa.upload',
            'uses' => 'SiswaController@upload'
        ]);
        
        Route::post('/walikelas/update/{id}', 'WalikelasController@upload');
		
		//Cek Status Aktif Siswa
		Route::get('status', [
            'as' => 'siswa.konfirmasi',
            'uses' => 'SiswaController@konfirmasi'
        ]);
		Route::post('status/perbaruistatus', [
            'as' => 'siswa.perbarui',
            'uses' => 'SiswaController@perbaruistatus'
        ]);
    });

    // Walikelas
    Route::group(['prefix' => 'walikelas', 'middleware' => ['auth', 'role:walikelas']], function() {
        Route::resource('tariktunai', 'TarikTunai');
        Route::resource('laporan', 'LaporanController');
        Route::resource('setortunai', 'SetorTunaiController');
        Route::resource('siswa_view', 'SiswaViewController');
        Route::resource('jenistabungan_view', 'JenisTabunganViewController');
		//Route::resource('jenistabungansiswa', 'JenisTabunganController');
        //Route::resource('tabungan', 'TabunganController');
        Route::resource('tabungan_view', 'TabunganViewController');

        // Daftar peminjaman
        Route::get('mutasi', [
            'as' => 'mutasi.index',
            'uses' => 'mutasiController@index'
        ]);

        // Setor
        Route::get('tariktunai', [
            'as' => 'tariktunai.index',
            'uses' => 'TransaksiController@transaksiTarik'
        ]);
		
		Route::get('/periode', 'LaporanController@periode')->name('laporan.periode');
		Route::get('/laporan', 'LaporanController@index')->name('laporan.index');
		Route::post('/laporan', 'LaporanController@index')->name('laporan.store');

    });
});

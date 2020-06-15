<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Walikelas extends Model
{
    //
    protected $table = "wali_kelas";
	protected $fillable = [
        'nip', 'nama_lengkap', 'alamat', 'telepon', 'email', 'nama_pengguna', 'katasandi', 'aktif','created_at','updated_at','avatar'
    ];
}

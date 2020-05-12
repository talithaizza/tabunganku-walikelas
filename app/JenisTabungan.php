<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisTabungan extends Model
{
    //
    protected $table = "jenis_tabungan";
	protected $fillable = [
        'nama', 'deskripsi','aktif','created_at','updated_at'
    ];
}

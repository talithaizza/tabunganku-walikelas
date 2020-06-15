<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    //
    protected $table = "kelas";
	protected $fillable = [
        'kelas', 'wali_kelas','created_at','updated_at'
    ];
}

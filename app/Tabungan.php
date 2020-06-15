<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tabungan extends Model
{
    //
    protected $table = "tabungan";
	protected $fillable = [
        'nis', 'jenis_tabungan', 'saldo', 'created_at','updated_at'
    ];
}

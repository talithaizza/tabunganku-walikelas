<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    //
    protected $table = "transaksi";
	protected $fillable = [
        'kode_transaksi', 'nis', 'jenis_tabungan', 'jenis_transaksi', 'nominal', 'created_at','updated_at', 'massage'
    ];
}

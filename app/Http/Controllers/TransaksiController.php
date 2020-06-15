<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function transaksiTarik()
    {
        return view('tariktunai.index');
    }
}

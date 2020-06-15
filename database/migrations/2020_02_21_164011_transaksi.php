<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Transaksi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('transaksi', function (Blueprint $table){
			$table->bigIncrements('id');
			$table->string('kode_transaksi');
			$table->string('nis');
			$table->string('jenis_tabungan');
			$table->string('jenis_transaksi');
			$table->integer('nominal');
			$table->timestamps();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Siswa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nis');
            $table->string('nama_lengkap');
            $table->string('kelas');
            $table->integer('angkatan');
            $table->date('ttl');
            $table->string('telp_ortu');
            $table->string('email');
            $table->string('nama_pengguna');
            $table->string('katasandi');
			$table->string('token');
            $table->boolean('aktif');
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

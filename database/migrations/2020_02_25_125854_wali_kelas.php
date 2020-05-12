<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WaliKelas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('wali_kelas', function (Blueprint $table) {
            $table->bigIncrements('id');
                $table->string('nip');
                $table->string('nama_lengkap');
                $table->string('alamat');
                $table->string('telepon');
                $table->string('email');
                $table->string('nama_pengguna');
                $table->string('katasandi');
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

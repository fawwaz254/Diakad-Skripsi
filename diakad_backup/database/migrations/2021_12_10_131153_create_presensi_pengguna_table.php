<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePresensiPenggunaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presensi_pengguna', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_presensi_pengguna', 40)->primary();
            $table->string('id_pengguna', 40);
            $table->boolean('status_join_table')->nullable()->comment('1 = Pegawai; 2 = Guru; 3 = Siswa; 4 = Wali Murid; 5 = Pelatih Ekskul;');
            $table->date('date');
            $table->time('check_in');
            $table->time('check_out');
            $table->string('request_absence_id', 40)->nullable();
            $table->timestamps();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
            $table->softDeletes();
            $table->string('deleted_by', 40)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presensi_pengguna');
    }
}

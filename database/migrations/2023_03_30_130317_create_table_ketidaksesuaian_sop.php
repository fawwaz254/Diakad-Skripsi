<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableKetidaksesuaianSop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ketidaksesuaian_sop', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_ketidaksesuaian_sop', 40)->primary();
            $table->string('id_pengguna', 40);
            $table->string('id_pengguna_input', 40);
            $table->string('catatan_pelanggaran', 256);
            $table->timestamp('tgl_pelanggaran');
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
    { }
}

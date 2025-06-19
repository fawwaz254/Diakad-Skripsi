<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRpbUnitHabisPakaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpb_unit_habis_pakai', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_rpb_unit_habis_pakai', 40)->primary();
            $table->string('id_semester', 40)->comment('FK: semester.id_semester');
            $table->string('id_unit_kerja', 40)->comment('FK: unit_kerja.id_unit_kerja');
            $table->string('id_inventaris_habis_pakai', 40)->comment('FK: inventaris_habis_pakai.id_inventaris_habis_pakai');
            $table->decimal('harga_satuan', 10, 0)->nullable()->comment('diisi oleh sistem berdasarkan harga terbaru tabel inventaris_habis_pakai');
            $table->integer('qty')->nullable();
            $table->date('tgl_rpb_unit_habis_pakai')->nullable();
            $table->boolean('prioritas')->nullable()->comment('1 = Rendah; 2 = Sedang; 3 = Tinggi;');
            $table->string('id_pengguna_kepala_unit', 40)->nullable()->comment('FK: pengguna.id_pengguna, kepala unit yg melakukan approve permintaan');
            $table->string('id_pengguna_kepala_sarpras', 40)->nullable()->comment('FK: pengguna.id_pengguna, kepala sarpras yg melakukan approve permintaan');
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
        Schema::dropIfExists('rpb_unit_habis_pakai');
    }
}

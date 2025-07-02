<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRpbSarprasHabisPakaiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rpb_sarpras_habis_pakai', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_rpb_sarpras_habis_pakai', 40)->primary();
            $table->string('id_semester', 40)->comment('FK: semester.id_semester');
            $table->string('id_supplier', 40)->comment('FK: supplier.id_supplier');
            $table->string('id_inventaris_habis_pakai', 40)->comment('FK: inventaris_habis_pakai.id_inventaris_habis_pakai');
            $table->decimal('harga_satuan', 10, 0)->nullable();
            $table->integer('qty')->nullable();
            $table->date('tgl_rpb_sarpras_habis_pakai')->nullable();
            $table->boolean('prioritas')->nullable()->comment('1 = Rendah; 2 = Sedang; 3 = Tinggi;');
            $table->string('id_pengguna_kepala_sarpras', 40)->nullable()->comment('FK: pengguna.id_pengguna, kepala sarpras yg melakukan approve rpb sarpras');
            $table->string('id_pengguna_kepala_sarpras_approve', 40)->nullable()->comment('FK: pengguna.id_pengguna, pimpinan yg melakukan approve akhir (apabila > 5 juta maka approval oleh PIMPINAN)');
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
        Schema::dropIfExists('rpb_sarpras_habis_pakai');
    }
}

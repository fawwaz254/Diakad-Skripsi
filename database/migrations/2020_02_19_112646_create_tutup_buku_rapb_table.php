<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTutupBukuRapbTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tutup_buku_rapb', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_tutup_buku_rapb', 40)->primary();
            $table->string('id_semester_mulai', 40)->comment('FK: semester.id_semester');
            $table->string('id_semester_selesai', 40)->comment('FK: semester.id_semester');
            $table->string('nm_tutup_buku_rapb', 1024)->nullable();
            $table->float('jml_rapb_penerimaan', 10, 0)->nullable();
            $table->float('jml_realisasi_penerimaan', 10, 0)->nullable();
            $table->float('jml_selisih_penerimaan', 10, 0)->nullable();
            $table->float('jml_rapb_pengeluaran', 10, 0)->nullable();
            $table->float('jml_realisasi_pengeluaran', 10, 0)->nullable();
            $table->float('jml_selisih_pengeluaran', 10, 0)->nullable();
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
        Schema::dropIfExists('tutup_buku_rapb');
    }
}

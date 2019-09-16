<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKesimpulanPelanggaranTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kesimpulan_pelanggaran', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_kesimpulan_pelanggaran', 40)->primary();
            $table->string('nm_kesimpulan_pelanggaran', 128)->nullable();
            $table->float('poin_bawah_kesimpulan_pelanggaran', 10, 0)->nullable();
            $table->float('poin_atas_kesimpulan_pelanggaran', 10, 0)->nullable();
            $table->text('deskripsi_kesimpulan_pelanggaran_1')->nullable();
            $table->text('deskripsi_kesimpulan_pelanggaran_2')->nullable();
            $table->text('deskripsi_kesimpulan_pelanggaran_3')->nullable();
            $table->string('id_sekolah', 40)->comment('FK: sekolah.id_sekolah');
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
        Schema::dropIfExists('kesimpulan_pelanggaran');
    }
}

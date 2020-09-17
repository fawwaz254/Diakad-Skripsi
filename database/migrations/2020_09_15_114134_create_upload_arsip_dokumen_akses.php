<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadArsipDokumenAkses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('arsip_dokumen_akses', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_arsip_dokumen_akses', 40)->primary();
            $table->string('id_arsip_dokumen', 40)->comment('FK: arsip_dokumen.id_arsip_dokumen');
            $table->boolean('status_join_table')->nullable()->comment('Digunakan untuk hak akses status join table user');
            $table->string('id_unit_kerja', 40)->nullable()->comment('FK: unit_kerja.id_unit_kerja');
            
            $table->timestamps();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
            $table->softDeletes();
            $table->string('deleted_by', 40)->nullable();
        });

        Schema::table('arsip_dokumen', function (Blueprint $table) {
            $table->string('id_unit_kerja', 40)->nullable()->comment('FK: unit_kerja.id_unit_kerja (merupakan unit kerja owner)')->change();
            $table->boolean('is_publik')->default(0)->after('is_upload')->comment('0 = Tidak untuk semua role; 1 = Diakses untuk semua role;');
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

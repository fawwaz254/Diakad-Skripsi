<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDokumenTandaTanganDigitalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dokumen_tanda_tangan_digital', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_tanda_tangan_digital', 40)->primary();
            $table->integer('no_dokumen')->nullable();
            $table->text('perihal_dokumen')->nullable();
            $table->text('isi_dokumen')->nullable();
            $table->string('link_dokumen', 150)->nullable();
            $table->boolean('is_approve')->comment('0 = sudah di approve; 1=belum di approve')->nullable();
            $table->string('approve_by', 40)->comment('FK: pengguna.id_pengguna')->nullable();
            $table->timestamp('approve_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('created_by', 40)->nullable();
            $table->string('updated_by', 40)->nullable();
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
        Schema::dropIfExists('dokumen_tanda_tangan_digital');
    }
}

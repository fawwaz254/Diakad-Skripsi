<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeMateriAjarViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('materi_ajar_view');
        Schema::create('materi_ajar_view', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_materi_ajar_view', 40)->primary();
            $table->string('id_materi_ajar', 40)->nullable();
            $table->string('id_pengguna', 40)->nullable();
            $table->datetime('time')->nullable();
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
        Schema::dropIfExists('materi_ajar_view');
    }
}

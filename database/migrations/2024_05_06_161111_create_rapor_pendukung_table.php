<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaporPendukungTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rapor_pendukung', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_rapor_pendukung', 40)->primary();
            $table->string('nm_rapor')->nullable();
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
        Schema::dropIfExists('rapor_pendukung');
    }
}

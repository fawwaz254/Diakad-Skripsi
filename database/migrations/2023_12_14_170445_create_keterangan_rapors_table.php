<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeteranganRaporsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keterangan_rapor', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_keterangan_rapor', 40)->primary();
            $table->string('id_rapor', 40);
            $table->longText('keterangan_a');
            $table->longText('keterangan_b');
            $table->longText('keterangan_c');
            $table->longText('keterangan_d');
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

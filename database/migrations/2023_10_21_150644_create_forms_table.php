<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_form', 40)->primary();
            $table->string('id_role', 40);
            $table->string('nm_form', 128);
            $table->integer('is_harian');
            $table->integer('is_aktif');
            $table->time('start_time')->nullable();;
            $table->time('end_time')->nullable();;
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

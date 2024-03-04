<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomFormKomponenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_form_komponen', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_custom_form_komponen', 40)->primary();
            $table->string('id_custom_form', 40)->nullable();
            $table->string('nm_custom_form_komponen')->nullable();
            $table->string('label_custom_form_komponen')->nullable();
            $table->string('tipe_custom_form_komponen')->nullable();
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

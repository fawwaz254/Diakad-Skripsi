<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFpDevice220607 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('fp_devices');
        Schema::create('fp_devices', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_fp_device', 40)->primary();
            $table->string('nm_fp_device', 256)->nullable();
            $table->string('sn', 64)->nullable();
            $table->string('ip_address_wan', 64)->nullable();
            $table->string('ip_address_lan', 64)->nullable();
            $table->string('comm_key', 32)->nullable();
            $table->string('port', 8)->nullable();
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
        Schema::dropIfExists('fp_devices');
    }
}

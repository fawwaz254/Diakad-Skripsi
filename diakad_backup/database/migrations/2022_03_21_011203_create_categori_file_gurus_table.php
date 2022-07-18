<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriFileGurusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_file_guru', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('category_file_guru_id', 40)->primary();
            $table->string('id_pengguna', 40);
            // $table->string('id_pengguna', false)->comment('FK: pengguna.id_pengguna');
            $table->string('category_file_mgmp_id', 40)->comment('FK: category_file_mgmp.category_file_mgmp_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_file_guru');
    }
}

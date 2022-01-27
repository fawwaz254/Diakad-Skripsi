<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryFileRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_file_role', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('category_file_role_id', 40)->primary();
            $table->integer('id_role', false)->comment('FK: role.id_role');
            $table->string('category_file_id', 40)->comment('FK: category_file.category_file_id');
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
        Schema::dropIfExists('category_file_role');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_file', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('category_file_id', 40)->primary();
            $table->text('category_file_name');
            $table->text('category_file_explanation')->nullable();
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
        Schema::dropIfExists('category_file');
    }
}

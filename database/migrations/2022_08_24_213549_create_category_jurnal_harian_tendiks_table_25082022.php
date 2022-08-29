<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryJurnalHarianTendiksTable25082022 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_jurnal_harian_tendik', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_category_jh_tendik', 40)->primary();
            $table->string('id_unit_kerja',40);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('category_jurnal_harian_tendik');
    }
}

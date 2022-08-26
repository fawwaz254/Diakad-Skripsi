<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryKelompokJurnalHarianTendiksTable24082022 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_kelompok_jurnal_harian_tendik', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_c_k_jh_tendik', 40)->primary();
            $table->string('id_pengguna', 40);
            $table->string('id_category_jh_tendik', 40);
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
        Schema::dropIfExists('category_kelompok_jurnal_harian_tendik');
    }
}

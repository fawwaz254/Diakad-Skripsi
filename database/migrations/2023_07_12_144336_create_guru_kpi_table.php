<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuruKpiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guru_kpi', function (Blueprint $table) {
            $table->engine = 'InnoDB';
			$table->string('id_guru_kpi', 40)->primary();
			$table->string('id_pengguna', 40)->comment('FK: pengguna.id_pengguna (bisa guru atau tendik)');
			$table->integer('is_aktif')->nullable();
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
        Schema::dropIfExists('guru_kpi');
    }
}

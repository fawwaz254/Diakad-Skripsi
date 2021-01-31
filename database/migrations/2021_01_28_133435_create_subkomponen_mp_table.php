<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubkomponenMpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subkomponen_mp', function (Blueprint $table) {
            $table->engine = 'InnoDB';
			$table->string('id_subkomponen_mp', 40)->primary();
			$table->string('id_komponen_mp', 40)->comment('FK: komponen_mp.id_komponen_mp');
			$table->string('kd_subkomponen_mp', 32)->nullable();
			$table->string('nm_subkomponen_mp', 32)->nullable();
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
        Schema::dropIfExists('subkomponen_mp');
    }
}

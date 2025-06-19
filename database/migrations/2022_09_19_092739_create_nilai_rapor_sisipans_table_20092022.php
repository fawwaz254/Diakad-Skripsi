<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNilaiRaporSisipansTable20092022 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_rapor_sisipan', function (Blueprint $table) {
            $table->engine = 'InnoDB';
			$table->string('id_nilai_rapor_sisipan', 40)->primary();
			$table->string('id_rapor_sisipan', 40);
            $table->string('id_komponen_nilai');
            $table->string('id_siswa', 40);
			// $table->text('nm_nilai')->nullable();
			$table->decimal('nilai', 10, 0)->nullable();
            // $table->integer('urutan')->nullable();
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
        Schema::dropIfExists('nilai_rapor_sisipan');
    }
}

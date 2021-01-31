<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNilaiMpSubkomponenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilai_mp_subkomponen', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_nilai_mp_subkomponen', 40)->primary();
            $table->string('id_pengambilan_mp', 40)->comment('FK: pengambilan_mp.id_pengambilan_mp');
            $table->string('id_subkomponen_mp', 40)->comment('FK: subkomponen_mp.id_subkomponen_mp');
            $table->float('besar_nilai_mp', 10, 0)->nullable();
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
        Schema::dropIfExists('nilai_mp_subkomponen');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaporsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rapor', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_rapor', 40)->primary();;
            $table->string('id_semester', 40)->nullable();
            $table->string('id_kelas', 40)->nullable();
            $table->string('id_mata_pelajaran', 40)->nullable();
            // $table->string('id_pengguna', 40)->nullable();
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
        // Schema::dropIfExists('rapors');
    }
}

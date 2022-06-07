<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFpAttendance220607 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('fp_attendances');
        Schema::create('fp_attendances', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id_fp_attendance');
            $table->string('username', 512)->nullable();
            $table->integer('status')->nullable()->comment('0:Datang; 1:Keluar; 4:Lembur Datang; 5:Lembur Keluar;');
            $table->date('tanggal')->nullable();
            $table->datetime('fp_date')->nullable();
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
        Schema::dropIfExists('fp_attendances');
    }
}

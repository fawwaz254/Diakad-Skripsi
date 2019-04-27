<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeIdStaffTablePemasukanBiaya extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pemasukan_biaya', function (Blueprint $table) {
            $table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff (staff yg melakukan input pemasukan) (null apabila guru yg melakukan input pemasukan)')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

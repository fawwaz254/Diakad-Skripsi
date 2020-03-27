<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCommentKepalaSarprasApproveTabelRpbSarpras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rpb_sarpras', function (Blueprint $table) {
            $table->string('id_pengguna_kepala_sarpras_approve', 40)->nullable()->after('id_pengguna_kepala_sarpras')->comment('FK: pengguna.id_pengguna, kepala sarpras yg melakukan approve pemilihan harga, qty, termin dari supplier (apabila > 5 juta maka approval oleh PIMPINAN)')->change();
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

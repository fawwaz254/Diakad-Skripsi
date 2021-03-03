<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LogResetPassword extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_reset_password', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_log_reset_password', 40)->primary();
			$table->string('id_pengguna', 40)->comment('FK: pengguna.id_pengguna');
			$table->timestamps();
			$table->string('created_by', 40)->comment('FK: pengguna.id_pengguna');
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

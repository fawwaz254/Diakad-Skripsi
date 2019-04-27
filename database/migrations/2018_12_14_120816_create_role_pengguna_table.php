<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRolePenggunaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('role_pengguna', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->integer('id_role_pengguna', true);
			$table->integer('id_role')->comment('FK: role.ID_ROLE');
			$table->string('id_pengguna', 40)->comment('FK: pengguna.id_pengguna');
			$table->string('keterangan_role_pengguna', 128)->nullable();
			$table->boolean('is_aktif')->nullable()->comment('1 = role yang sedang aktif diakses;');
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
		Schema::drop('role_pengguna');
	}

}

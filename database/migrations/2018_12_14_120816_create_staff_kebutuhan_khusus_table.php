<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStaffKebutuhanKhususTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('staff_kebutuhan_khusus', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_staff_kebutuhan_khusus', 40)->primary();
			$table->string('id_staff', 40)->nullable()->comment('FK: staff.id_staff');
			$table->boolean('id_kebutuhan_khusus')->nullable()->comment('FK: kebutuhan_khusus.id_kebutuhan_khusus (kebutuhan khusus yg bisa ditangani oleh staff)');
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
		Schema::drop('staff_kebutuhan_khusus');
	}

}

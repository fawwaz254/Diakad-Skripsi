<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVoucherTarifTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('voucher_tarif', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_voucher_tarif', 40)->primary();
			$table->string('id_semester', 40)->comment('FK: semester.id_semester');
			$table->string('id_jurusan', 40)->nullable()->comment('FK: jurusan.id_jurusan (diisi apabila ada tarif khusus untuk jurusan)');
			$table->decimal('tarif', 10, 0)->nullable();
			$table->string('deskripsi', 128)->nullable();
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
		Schema::drop('voucher_tarif');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBukuIndukRouteTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('buku_induk_route', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->string('id_buku_induk_route', 40)->primary();
			$table->string('nm_buku_induk_route', 128)->nullable();
			$table->text('path_buku_induk_route', 65535)->nullable()->comment('path pada route laravel untuk cetak buku induk');
			$table->boolean('is_aktif')->nullable()->comment('0 = tidak aktif; 1 = aktif; (pastikan yg is_aktif = 1 hanya ada 1)');
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
		Schema::drop('buku_induk_route');
	}

}

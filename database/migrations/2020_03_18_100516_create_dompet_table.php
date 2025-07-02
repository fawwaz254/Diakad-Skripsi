<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDompetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dompet', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_dompet', 40)->primary();
            $table->string('id_bank', 40)->comment('FK: bank.id_bank')->nullable();
            $table->boolean('is_tunai')->comment('khusus tunai diisi 1')->nullable();
            $table->decimal('saldo_dompet', 10, 0)->nullable();
            $table->boolean('is_spp')->comment('khusus bank digunakan menampung rekening diisi 1')->nullable();
            $table->string('keterangan_dompet', 1024)->nullable();
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
        Schema::dropIfExists('dompet');
    }
}

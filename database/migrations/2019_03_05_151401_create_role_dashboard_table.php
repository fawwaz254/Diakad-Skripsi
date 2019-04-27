<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleDashboardTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_dashboard', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id_role_dashboard', 40)->primary();
            $table->integer('id_role')->comment('FK: role.id_role');
            $table->text('isi_dashboard')->nullable();
            $table->boolean('is_aktif')->nullable()->comment('0 = tidak aktif; 1 = aktif;');
            $table->string('id_sekolah', 40)->comment('FK: sekolah.id_sekolah');
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
        Schema::dropIfExists('role_dashboard');
    }
}

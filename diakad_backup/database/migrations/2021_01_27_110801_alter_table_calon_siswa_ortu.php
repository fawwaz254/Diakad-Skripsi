<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCalonSiswaOrtu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('calon_siswa_ortu', function (Blueprint $table) {

            $table->string('alamat_jalan_ayah', 128)->after('id_kebutuhan_khusus_ayah')->nullable();
            $table->string('alamat_dusun_ayah', 64)->after('id_kebutuhan_khusus_ayah')->nullable();
            $table->string('alamat_kelurahan_ayah', 64)->after('id_kebutuhan_khusus_ayah')->nullable();
            $table->string('almat_rt_ayah', 4)->after('id_kebutuhan_khusus_ayah')->nullable();
            $table->string('alamat_rw_ayah', 4)->after('id_kebutuhan_khusus_ayah')->nullable();
            $table->string('alamat_kecamatan_ayah', 64)->after('id_kebutuhan_khusus_ayah')->nullable();
            $table->string('alamat_kodepos_ayah', 8)->after('id_kebutuhan_khusus_ayah')->nullable();
            $table->string('alamat_kota_ayah', 40)->after('id_kebutuhan_khusus_ayah')->nullable()->comment('FK: kota.id_kota');
            $table->boolean('alamat_provinsi_ayah')->after('id_kebutuhan_khusus_ayah')->nullable()->comment('FK: provinsi.id_provinsi');

            $table->string('alamat_jalan_ibu', 128)->after('id_kebutuhan_khusus_ibu')->nullable();
            $table->string('alamat_dusun_ibu', 64)->after('id_kebutuhan_khusus_ibu')->nullable();
            $table->string('alamat_kelurahan_ibu', 64)->after('id_kebutuhan_khusus_ibu')->nullable();
            $table->string('almat_rt_ibu', 4)->after('id_kebutuhan_khusus_ibu')->nullable();
            $table->string('alamat_rw_ibu', 4)->after('id_kebutuhan_khusus_ibu')->nullable();
            $table->string('alamat_kecamatan_ibu', 64)->after('id_kebutuhan_khusus_ibu')->nullable();
            $table->string('alamat_kodepos_ibu', 8)->after('id_kebutuhan_khusus_ibu')->nullable();
            $table->string('alamat_kota_ibu', 40)->after('id_kebutuhan_khusus_ibu')->nullable()->comment('FK: kota.id_kota');
            $table->boolean('alamat_provinsi_ibu')->after('id_kebutuhan_khusus_ibu')->nullable()->comment('FK: provinsi.id_provinsi');

        });
    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {   
        Schema::table('calon_siswa_ortu', function (Blueprint $table) {

            $table->dropColumn('alamat_jalan_ayah');
            $table->dropColumn('alamat_dusun_ayah');
            $table->dropColumn('alamat_kelurahan_ayah');
            $table->dropColumn('alamat_rt_ayah');
            $table->dropColumn('alamat_rw_ayah');
            $table->dropColumn('alamat_kecamatan_ayah');
            $table->dropColumn('alamat_kodepos_ayah');
            $table->dropColumn('alamat_kota_ayah');
            $table->dropColumn('alamat_provinsi_ayah');

            $table->dropColumn('alamat_jalan_ibu');
            $table->dropColumn('alamat_dusun_ibu');
            $table->dropColumn('alamat_kelurahan_ibu');
            $table->dropColumn('alamat_rt_ibu');
            $table->dropColumn('alamat_rw_ibu');
            $table->dropColumn('alamat_kecamatan_ibu');
            $table->dropColumn('alamat_kodepos_ibu');
            $table->dropColumn('alamat_kota_ibu');
            $table->dropColumn('alamat_provinsi_ibu');

        });
    }
}

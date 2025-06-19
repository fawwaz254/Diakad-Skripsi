<?php

use App\Models\Pengguna;
use App\Models\RolePengguna;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleAkunAdminYayasanToRolePenggunaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('role_pengguna', function (Blueprint $table) {
        //     $yayasan = Pengguna::where('username','yayasan')->first();
        //     $role_pengguna = new RolePengguna;
        //     $role_pengguna->id_role = 15;
        //     $role_pengguna->id_pengguna = $yayasan->id_pengguna;
        //     $role_pengguna->is_aktif = 1;
        //     $role_pengguna->created_by = 'migration';
        //     $role_pengguna->save();

        //     $role_pengguna1 = new RolePengguna;
        //     $role_pengguna1->id_role = 16;
        //     $role_pengguna1->id_pengguna = $yayasan->id_pengguna;
        //     $role_pengguna1->is_aktif = 0;
        //     $role_pengguna1->created_by = 'migration';
        //     $role_pengguna1->save();

        //     $role_pengguna2 = new RolePengguna;
        //     $role_pengguna2->id_role = 7;
        //     $role_pengguna2->id_pengguna = $yayasan->id_pengguna;
        //     $role_pengguna2->keterangan_role_pengguna = 'Input Administrator';
        //     $role_pengguna2->is_aktif = 0;
        //     $role_pengguna2->created_by = 'migration';
        //     $role_pengguna2->save();      

        //     $role_pengguna3 = new RolePengguna;
        //     $role_pengguna3->id_role = 5;
        //     $role_pengguna3->id_pengguna = $yayasan->id_pengguna;
        //     $role_pengguna3->keterangan_role_pengguna = 'Input Administrator';
        //     $role_pengguna3->is_aktif = 0;
        //     $role_pengguna3->created_by = 'migration';
        //     $role_pengguna3->save();

        //     $role_pengguna4 = new RolePengguna;
        //     $role_pengguna4->id_role = 19;
        //     $role_pengguna4->id_pengguna = $yayasan->id_pengguna;
        //     $role_pengguna4->keterangan_role_pengguna = 'Input Administrator';
        //     $role_pengguna4->is_aktif = 0;
        //     $role_pengguna4->created_by = 'migration';
        //     $role_pengguna4->save();

        //     $role_pengguna5 = new RolePengguna;
        //     $role_pengguna5->id_role = 6;
        //     $role_pengguna5->id_pengguna = $yayasan->id_pengguna;
        //     $role_pengguna5->keterangan_role_pengguna = 'Input Administrator';
        //     $role_pengguna5->is_aktif = 0;
        //     $role_pengguna5->created_by = 'migration';
        //     $role_pengguna5->save();

        //     $role_pengguna6 = new RolePengguna;
        //     $role_pengguna6->id_role = 9;
        //     $role_pengguna6->id_pengguna = $yayasan->id_pengguna;
        //     $role_pengguna6->keterangan_role_pengguna = 'Input Administrator';
        //     $role_pengguna6->is_aktif = 0;
        //     $role_pengguna6->created_by = 'migration';
        //     $role_pengguna6->save();

        //     $role_pengguna7 = new RolePengguna;
        //     $role_pengguna7->id_role = 1;
        //     $role_pengguna7->id_pengguna = $yayasan->id_pengguna;
        //     $role_pengguna7->keterangan_role_pengguna = 'Input Administrator';
        //     $role_pengguna7->is_aktif = 0;
        //     $role_pengguna7->created_by = 'migration';
        //     $role_pengguna7->save();

        //     $role_pengguna8 = new RolePengguna;
        //     $role_pengguna8->id_role = 11;
        //     $role_pengguna8->id_pengguna = $yayasan->id_pengguna;
        //     $role_pengguna8->keterangan_role_pengguna = 'Input Administrator';
        //     $role_pengguna8->is_aktif = 0;
        //     $role_pengguna8->created_by = 'migration';
        //     $role_pengguna8->save();
            
        //     $role_pengguna9 = new RolePengguna;
        //     $role_pengguna9->id_role = 17;
        //     $role_pengguna9->id_pengguna = $yayasan->id_pengguna;
        //     $role_pengguna9->keterangan_role_pengguna = 'Input Administrator';
        //     $role_pengguna9->is_aktif = 0;
        //     $role_pengguna9->created_by = 'migration';
        //     $role_pengguna9->save();

        //     $role_pengguna10 = new RolePengguna;
        //     $role_pengguna10->id_role = 10;
        //     $role_pengguna10->id_pengguna = $yayasan->id_pengguna;
        //     $role_pengguna10->keterangan_role_pengguna = 'Input Administrator';
        //     $role_pengguna10->is_aktif = 0;
        //     $role_pengguna10->created_by = 'migration';
        //     $role_pengguna10->save();
            
        //     $role_pengguna11 = new RolePengguna;
        //     $role_pengguna11->id_role = 14;
        //     $role_pengguna11->id_pengguna = $yayasan->id_pengguna;
        //     $role_pengguna11->keterangan_role_pengguna = 'Input Administrator';
        //     $role_pengguna11->is_aktif = 0;
        //     $role_pengguna11->created_by = 'migration';
        //     $role_pengguna11->save();

        //     $role_pengguna12 = new RolePengguna;
        //     $role_pengguna12->id_role = 8;
        //     $role_pengguna12->id_pengguna = $yayasan->id_pengguna;
        //     $role_pengguna12->keterangan_role_pengguna = 'Input Administrator';
        //     $role_pengguna12->is_aktif = 0;
        //     $role_pengguna12->created_by = 'migration';
        //     $role_pengguna12->save();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_pengguna', function (Blueprint $table) {
            
        });
    }
}

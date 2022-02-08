<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShiftPenggunasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('shift_penggunas', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->timestamps();
        // });
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $role_id = Role::where('nm_role', 'Humas')->first()->id_role;

        $modul = Modul::create([
            "id_role"       => $role_id,
            "nm_modul"      => "Shift",
            "route"         => "shift",
            "urutan"        => 1,
            "akses"         => 1,
            "created_at"    => $now
        ]);

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Shift Pengguna",
                "page"         => "shift_penggunas",
                "urutan"       => 1,
                "akses"        => 1,
                "created_at"   => $now
            ],
        ]);



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shift_penggunas');
    }
}

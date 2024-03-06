<?php

use App\Models\Modul;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuCustomFormRekapInFormBuilderModul extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $role_id = Role::where('nm_role', 'Humas')->first()->id_role;

        $modul = Modul::where('id_role', $role_id)->where('nm_modul', 'Form Builder')->first();

        $modul->menus()->createMany([
            [
                "nm_menu"      => "Rekap Custom Form",
                "page"         => "form-rekap",
                "urutan"       => 5,
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
        Schema::dropIfExists('menu_custom_form_rekap_in_form_builder_modul');
    }
}

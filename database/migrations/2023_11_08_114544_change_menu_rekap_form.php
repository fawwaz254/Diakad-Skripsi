<?php

use App\Models\Menu;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMenuRekapForm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu = Menu::where('nm_menu', 'Rekap Form Bebas')->get();
        foreach ($menu as $m) {
            $m->akses = 0;
            $m->save();
        }

        $menu = Menu::where('nm_menu', 'Rekap Form Harian')->get();
        foreach ($menu as $m) {
            $m->page = 'rekap-form';
            $m->nm_menu = 'Rekap Form';
            $m->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

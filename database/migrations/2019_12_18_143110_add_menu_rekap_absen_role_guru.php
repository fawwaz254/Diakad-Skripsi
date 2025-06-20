<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use Carbon\Carbon;

class AddMenuRekapAbsenRoleGuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now();

        // update menu
        // $menu               = Menu::find(47);
        // $menu->page         = "rekap-absen";
        // $menu->updated_at   = $now;
        // $menu->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // rollback
        // $menu               = Menu::find(47);
        // $menu->page         = null;
        // $menu->updated_at   = null;
        // $menu->save();
    }
}

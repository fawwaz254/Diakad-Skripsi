<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Models\Menu;
use Carbon\Carbon;

class AddingRouteOnMenuUpdateFotoModulSiswaKesiswaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu = Menu::find(243);
        $menu->page = 'update-foto';
        $menu->updated_at = Carbon::now(env('APP_TIMEZONE', ''));
        $menu->save();
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

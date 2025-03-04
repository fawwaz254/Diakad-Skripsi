<?php

use App\Models\Menu;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateMenuKunjunganMagangOnRoleGuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $menu = Menu::where('nm_menu', 'Kunjungan magang')
            ->where('page', 'add-kunjungan-magang')
            ->update([
                'page' => 'list-kunjungan-magang',
            ]);
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

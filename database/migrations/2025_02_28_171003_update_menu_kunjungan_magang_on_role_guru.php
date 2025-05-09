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
        $menu = Menu::where('page', 'add-kunjungan-magang')
            ->first();

        if (!$menu) {
            echo "Menu sudah di migrattion" . "\n";
        } else {
            $menu->update([
                'page' => 'list-kunjungan-magang',
            ]);
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

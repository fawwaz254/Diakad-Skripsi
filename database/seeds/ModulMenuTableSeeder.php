<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

use App\Models\Modul as Modul;
use App\Models\Menu as Menu;

// php artisan make:seeder ModulMenuTableSeeder

class ModulMenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$now = Carbon::now(env('APP_TIMEZONE', ''));

        // make object to find id
        $modul              = Modul::find(68);
        $modul->route       = "perawatan-sarpras";
        $modul->updated_at  = $now;
        $modul->save();

        // make object to find id
        $menu              	= Menu::find(234);
        $menu->page       	= "input-perawatan-rutin";
        $menu->updated_at  	= $now;
        $menu->save();

    }
}

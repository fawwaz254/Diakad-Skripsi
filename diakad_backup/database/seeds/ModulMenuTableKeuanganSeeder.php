<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

use App\Models\Modul as Modul;
use App\Models\Menu as Menu;

class ModulMenuTableKeuanganSeeder extends Seeder
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
        $menu              	= new Menu;
        $menu->id_modul		= 28;
        $menu->nm_menu		= "Data Biaya Internal";
        $menu->page       	= "biaya-internal";
        $menu->urutan		= 2;
        $menu->akses		= 1;
        $menu->updated_at  	= $now;
        $menu->save();

        // make object to find id
        $menu              	= new Menu;
        $menu->id_modul		= 28;
        $menu->nm_menu		= "Data Detail Biaya Internal";
        $menu->page       	= "detail-biaya-internal";
        $menu->urutan		= 3;
        $menu->akses		= 1;
        $menu->updated_at  	= $now;
        $menu->save();

        // make object to find id
        $menu              	= Menu::find(110);
        $menu->urutan      	= 4;
        $menu->updated_at  	= $now;
        $menu->save();

        // make object to find id
        $menu              	= Menu::find(111);
        $menu->urutan      	= 5;
        $menu->updated_at  	= $now;
        $menu->save();

        // make object to find id
        $menu              	= Menu::find(113);
        $menu->urutan      	= 6;
        $menu->updated_at  	= $now;
        $menu->save();
    }
}

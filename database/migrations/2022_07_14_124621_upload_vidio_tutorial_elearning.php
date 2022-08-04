<?php

use App\Models\Menu;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UploadVidioTutorialElearning extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Menu::where('id_modul', 119)->where('nm_menu', 'Soal')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/EqLnXz0zIvg'
        ));

        Menu::where('id_modul', 119)->where('nm_menu', 'Paket Soal')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/uGhsSsnFpCA'
        ));

        Menu::where('id_modul', 119)->where('nm_menu', 'Hasil Test')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/VHOJzKyUJSw'
        ));
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

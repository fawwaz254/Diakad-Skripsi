<?php
use App\Models\Menu;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDataUrlYoutubeOnTutorial3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Menu::where('id_modul', 113)->where('nm_menu', 'Histori Absensi')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/NzQbur7EwrI'
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

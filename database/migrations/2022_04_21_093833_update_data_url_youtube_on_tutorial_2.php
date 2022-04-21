<?php

use App\Models\Menu;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDataUrlYoutubeOnTutorial2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Menu::where('id_modul', 8)->where('nm_menu', 'Data Prestasi')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/cPAeUJR4Hf0'
        ));

        Menu::where('id_modul', 8)->where('nm_menu', 'Data Kegiatan')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/5wbwcF35j44'
        ));

        Menu::where('id_modul', 9)->where('nm_menu', 'Jadwal Ujian')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/ObHGReDALFI'
        ));

        Menu::where('id_modul', 11)->where('nm_menu', 'Rekap Nilai')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/2FVeWgFNt-Q'
        ));

        Menu::where('id_modul', 34)->where('nm_menu', 'Data Pelanggaran Siswa KBM')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/SNACPBVN3KM'
        ));

        Menu::where('id_modul', 34)->where('nm_menu', 'Data Pelanggaran Siswa Non KBM')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/PFekAvFfWg0'
        ));

        Menu::where('id_modul', 110)->where('nm_menu', 'Video')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/PAaUbCzXUjI'
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

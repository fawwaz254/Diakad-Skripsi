<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Menu;

class UpdateDataUrlYoutubeOnTutorial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Menu::where('id_modul', 9)->where('nm_menu', 'Kalender Akademik')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/u_tOOpQKMnY?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));

        Menu::where('id_modul', 8)->where('nm_menu', 'Data Pribadi')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/7Hh2XgpWR4g?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));

        Menu::where('id_modul', 78)->where('nm_menu', 'Mengisi Form Kesehatan')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/-N5hHXbH9Es?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));

        Menu::where('id_modul', 9)->where('nm_menu', 'Jadwal KBM')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/l6MidQLRqkY?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));

        Menu::where('id_modul', 10)->where('nm_menu', 'Absensi Siswa')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/x70aCzdaX9A?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));


        Menu::where('id_modul', 10)->where('nm_menu', 'Rekap Absen')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/9NDOdCitgAM?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));

        Menu::where('id_modul', 10)->where('nm_menu', 'Absensi Tanpa Jadwal')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/EvCC49Qi2Rs?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));


        Menu::where('id_modul', 10)->where('nm_menu', 'Rekap Absen Tanpa Jadwal')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/nnR87VAkrqA?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));


        Menu::where('id_modul', 11)->where('nm_menu', 'Komponen Nilai')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/vudRVtfZNBw?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));


        Menu::where('id_modul', 11)->where('nm_menu', 'Input Nilai KBM/Try Out')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/75n1HKa7peI?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));


        Menu::where('id_modul', 34)->where('nm_menu', 'Input Pelanggaran Siswa')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/X8wVbmgB0Cc?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));


        Menu::where('id_modul', 73)->where('nm_menu', 'Input Reward Siswa')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/Q9rNuD_79bg?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));


        Menu::where('id_modul', 73)->where('nm_menu', 'Rekap Reward Siswa')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/YEKmLNNU_yY?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));


        Menu::where('id_modul', 61)->where('nm_menu', 'Komplain Inventaris/Sarpras')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/gCHNv4Agk-Y?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));

        Menu::where('id_modul', 75)->where('nm_menu', 'Setting Kelas Daring')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/Ci5O871w8pQ?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));

        Menu::where('id_modul', 35)->where('nm_menu', 'Absensi Harian Siswa')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/t2_7Xku9J6E?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));

        Menu::where('id_modul', 35)->where('nm_menu', 'Monitoring Kelas Kosong')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/0U3ypsIEAPs?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));

        Menu::where('id_modul', 35)->where('nm_menu', 'Rekap Kesehatan Siswa')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/kCQlSotTO4o?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));

        Menu::where('id_modul', 35)->where('nm_menu', 'Rekap Absen Tanpa Jadwal')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/T6_xV5Zddj4?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));

        Menu::where('id_modul', 9)->where('nm_menu', 'Input Jadwal')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/njD-WrYBOZQ?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));

        Menu::where('id_modul', 75)->where('nm_menu', 'Jadwal Kelas Daring')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/kyWsBgIu5gM?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));

        Menu::where('id_modul', 75)->where('nm_menu', 'Laporan Absen')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/LisQPijpfJI?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
        ));

        Menu::where('id_modul', 107)->where('nm_menu', 'Manajemen Materi Ajar')->update(array(
            'link_youtube' => 'https://www.youtube.com/embed/VjVs0h4JIgk?list=PL0vzqs5ULTuOvVg9wZGHAHeu4UJUEZ7kz'
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

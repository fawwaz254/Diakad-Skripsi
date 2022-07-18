<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Carbon\Carbon;

use App\Models\Modul;
use App\Models\Menu;
use App\Models\Role;

class AddingMenuForKegiatanHarian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $now = Carbon::now(env('APP_TIMEZONE', ''));
        
        $role = new Role;
        $role->nm_role = 'Humas';
        $role->path = 'humas';
        $role->is_mobile = 0;
        $role->created_at = $now;
        $role->created_by = 'A8bT515358553655b8b4b05a6d86';
        $role->save();

        $modul              = new Modul;
        $modul->id_role     = 19;
        $modul->nm_modul    = "Kegiatan Harian";
        $modul->route       = "kegiatan-harian";
        $modul->urutan      = 1;
        $modul->akses       = 1;
        $modul->created_at  = $now;
        $modul->save();

        // insert menu
        $menu               = new Menu;
        $menu->id_modul     = 77;
        $menu->nm_menu      = "Input Kegiatan";
        $menu->page         = "input-kegiatan";
        $menu->urutan       = 1;
        $menu->akses        = 1;
        $menu->created_at   = $now;
        $menu->save();

        $menu               = new Menu;
        $menu->id_modul     = 77;
        $menu->nm_menu      = "Input Pertanyaan";
        $menu->page         = "input-pertanyaan";
        $menu->urutan       = 2;
        $menu->akses        = 1;
        $menu->created_at   = $now;
        $menu->save();

        $menu               = new Menu;
        $menu->id_modul     = 77;
        $menu->nm_menu      = "Rekap Kesehatan Guru/Tendik";
        $menu->page         = "rekap-kesahatan";
        $menu->urutan       = 3;
        $menu->akses        = 1;
        $menu->created_at   = $now;
        $menu->save();

        $modul              = new Modul;
        $modul->id_role     = 2;
        $modul->nm_modul    = "Kegiatan Harian";
        $modul->route       = "kegiatan-harian";
        $modul->urutan      = 1;
        $modul->akses       = 1;
        $modul->created_at  = $now;
        $modul->save();

        $menu               = new Menu;
        $menu->id_modul     = 78;
        $menu->nm_menu      = "Mengisi Form Kesehatan";
        $menu->page         = "mengisi-form-kesehatan";
        $menu->urutan       = 1;
        $menu->akses        = 1;
        $menu->created_at   = $now;
        $menu->save();

        $modul              = new Modul;
        $modul->id_role     = 15;
        $modul->nm_modul    = "Kegiatan Harian";
        $modul->route       = "kegiatan-harian";
        $modul->urutan      = 1;
        $modul->akses       = 1;
        $modul->created_at  = $now;
        $modul->save();

        $menu               = new Menu;
        $menu->id_modul     = 79;
        $menu->nm_menu      = "Mengisi Form Kesehatan";
        $menu->page         = "mengisi-form-kesehatan";
        $menu->urutan       = 1;
        $menu->akses        = 1;
        $menu->created_at   = $now;
        $menu->save();
        
        $modul              = new Modul;
        $modul->id_role     = 3;
        $modul->nm_modul    = "Kegiatan Harian";
        $modul->route       = "kegiatan-harian";
        $modul->urutan      = 1;
        $modul->akses       = 1;
        $modul->created_at  = $now;
        $modul->save();

        $menu               = new Menu;
        $menu->id_modul     = 80;
        $menu->nm_menu      = "Mengisi Form Kesehatan";
        $menu->page         = "mengisi-form-kesehatan";
        $menu->urutan       = 1;
        $menu->akses        = 1;
        $menu->created_at   = $now;
        $menu->save();

        $menu               = new Menu;
        $menu->id_modul     = 35;
        $menu->nm_menu      = "Rekap Kesehatan Siswa";
        $menu->page         = "rekap-kesehatan";
        $menu->urutan       = 5;
        $menu->akses        = 1;
        $menu->created_at   = $now;
        $menu->save();

        $menu               = new Menu;
        $menu->id_modul     = 36;
        $menu->nm_menu      = "Rekap Kesehatan Siswa";
        $menu->page         = "rekap-kesehatan";
        $menu->urutan       = 9;
        $menu->akses        = 1;
        $menu->created_at   = $now;
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

<?php

use App\Models\Menu;
use App\Models\Modul;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HideModulAndMenuNotUseInRoleKesiswaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul_ijazah = Modul::where('id_role', 6)->where('nm_modul', 'Ijazah')->first();
        if ($modul_ijazah) {
            $modul_ijazah->akses = '0';
            $modul_ijazah->save();
        }

        $modul_wisuda = Modul::where('id_role', 6)->where('nm_modul', 'Wisuda')->first();
        if ($modul_wisuda) {
            $menu = Menu::where('id_modul', $modul_wisuda->id_modul)->where('nm_menu', 'Laporan Wisuda')->first();
            if ($menu) {
                $menu->akses = '0';
                $menu->save();
            }
        }

        $modul_pendaftaran = Modul::where('id_role', 6)->where('nm_modul', 'Pendaftaran')->first();
        if ($modul_pendaftaran) {
            $modul_pendaftaran->akses = '0';
            $modul_pendaftaran->save();
        }

        $modul_penanganan_siswa = Modul::where('id_role', 6)->where('nm_modul', 'Penanganan Siswa')->first();
        if ($modul_penanganan_siswa) {
            $modul_penanganan_siswa->akses = '0';
            $modul_penanganan_siswa->save();
        }

        $modul_siswa = Modul::where('id_role', 6)->where('nm_modul', 'Siswa')->first();
        if ($modul_siswa) {
            $menu = Menu::where('id_modul', $modul_siswa->id_modul)->where('nm_menu', 'Data Status Siswa')->first();
            if ($menu) {
                $menu->akses = '0';
                $menu->save();
            }

            $menu = Menu::where('id_modul', $modul_siswa->id_modul)->where('nm_menu', 'Cari Siswa')->first();
            if ($menu) {
                $menu->akses = '0';
                $menu->save();
            }
            //Pembayaran
            $menu = Menu::where('id_modul', $modul_siswa->id_modul)->where('nm_menu', 'Pembayaran')->first();
            if ($menu) {
                $menu->akses = '0';
                $menu->save();
            }
            //Siswa Aktif
            $menu = Menu::where('id_modul', $modul_siswa->id_modul)->where('nm_menu', 'Siswa Aktif')->first();
            if ($menu) {
                $menu->akses = '0';
                $menu->save();
            }
        }

        $modul_skpi = Modul::where('id_role', 6)->where('nm_modul', 'SKPI')->first();
        if ($modul_skpi) {
            $modul_skpi->akses = '0';
            $modul_skpi->save();
        }
        //Data Kesiswaan
        $modul_data_kesiswaan = Modul::where('id_role', 6)->where('nm_modul', 'Data Kesiswaan')->first();
        if ($modul_data_kesiswaan) {
            //History Admisi Siswa
            $menu = Menu::where('id_modul', $modul_data_kesiswaan->id_modul)->where('nm_menu', 'History Admisi Siswa')->first();
            if ($menu) {
                $menu->akses = '0';
                $menu->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { }
}

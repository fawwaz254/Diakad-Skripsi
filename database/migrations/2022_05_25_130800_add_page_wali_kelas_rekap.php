<?php

use App\Models\Menu;
use Illuminate\Database\Migrations\Migration;

class AddPageWaliKelasRekap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $rekap_nilai_kelas = Menu::where('id_menu', '140')->first();
        $rekap_nilai_kelas->page = "rekap-nilai-kelas";
        $rekap_nilai_kelas->save();

        $rekap_keuangan_kelas = Menu::where('id_menu', '141')->first();
        $rekap_keuangan_kelas->page = "rekap-keuangan-kelas";
        $rekap_keuangan_kelas->save();

        $rekap_pelanggaran_kelas = Menu::where('id_menu', '142')->first();
        $rekap_pelanggaran_kelas->page = "rekap-pelanggaran-kelas";
        $rekap_pelanggaran_kelas->save();
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

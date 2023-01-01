<?php

use App\Models\CalonSiswaOrtu;
use App\Models\Siswa;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumberWaliMuridInBiodata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $siswa = Siswa::with('calon_siswa.calon_siswa_ortu', 'wali_murid')->whereNotNull('id_wali_murid')->whereHas('calon_siswa.calon_siswa_ortu', function ($query) {
            $query->whereNull('nomor_hp_ortu');
        })->get();

        if ($siswa) {
            foreach ($siswa as $s) {
                $calon_siswa_ortu = CalonSiswaOrtu::where('id_c_siswa', $s->id_c_siswa)->first();
                if (strlen($s->wali_murid->nomor_hp_wali_murid) < 17) {
                    $calon_siswa_ortu->nomor_telp_ortu  = $s->wali_murid->nomor_hp_wali_murid;
                    $calon_siswa_ortu->nomor_hp_ortu  = $s->wali_murid->nomor_hp_wali_murid;
                    $calon_siswa_ortu->updated_by = 'Migration';
                    $calon_siswa_ortu->save();
                }
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

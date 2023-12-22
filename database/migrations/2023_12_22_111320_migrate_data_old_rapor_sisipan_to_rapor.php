<?php

use App\Models\JenisRapor;
use App\Models\KelasRapor;
use App\Models\KelasSisipan;
use App\Models\KelompokMapelRapor;
use App\Models\KelompokSisipan;
use App\Models\KomponenJenisRapor;
use App\Models\KomponenNilaiRaporSisipan;
use App\Models\MataPelajaranRapor;
use App\Models\MataPelajaranSisipan;
use App\Models\NilaiRapor;
use App\Models\NilaiRaporSisipan;
use App\Models\Rapor;
use App\Models\RaporSisipan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MigrateDataOldRaporSisipanToRapor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        set_time_limit(-1);
        $kelompok_sisipans = KelompokSisipan::get();
        foreach ($kelompok_sisipans as $kelompok_sisipan) {
            $kelompok_mapel_rapor = new KelompokMapelRapor;
            $kelompok_mapel_rapor->id_kelompok_mapel_rapor = $kelompok_sisipan->id_kelompok_sisipan;
            $kelompok_mapel_rapor->nm_kelompok_mapel_rapor = $kelompok_sisipan->nm_kelompok_sisipan;
            $kelompok_mapel_rapor->urutan = $kelompok_sisipan->urutan;
            $kelompok_mapel_rapor->nm_rapor = 'sisipan';
            $kelompok_mapel_rapor->created_by = 'migration';
            $kelompok_mapel_rapor->save();
        }

        $komponen_nilai_rapor_sisipans = KomponenNilaiRaporSisipan::get();
        $jenis_rapor = JenisRapor::where('nm_jenis_rapor', 'sisipan')->first();
        foreach ($komponen_nilai_rapor_sisipans as $komponen_nilai_rapor_sisipan) {
            $komponen_jenis_rapor = new KomponenJenisRapor;
            $komponen_jenis_rapor->id_komponen_jenis_rapor = $komponen_nilai_rapor_sisipan->id_komponen_nilai;
            $komponen_jenis_rapor->id_jenis_rapor = $jenis_rapor->id_jenis_rapor;
            $komponen_jenis_rapor->nm_komponen_jenis_rapor = $komponen_nilai_rapor_sisipan->nm_nilai;
            $komponen_jenis_rapor->urutan = $komponen_nilai_rapor_sisipan->urutan;
            $komponen_jenis_rapor->created_by = 'migration';
            $komponen_jenis_rapor->save();
        }

        $rapor_sisipans = RaporSisipan::get();
        foreach ($rapor_sisipans as $rapor_sisipan) {
            $rapor = new Rapor;
            $rapor->id_rapor = $rapor_sisipan->id_rapor_sisipan;
            $rapor->id_semester = $rapor_sisipan->id_semester;
            $rapor->id_kelas = $rapor_sisipan->id_kelas;
            $rapor->id_mata_pelajaran = $rapor_sisipan->id_mata_pelajaran;
            $rapor->nm_rapor = 'sisipan';
            $rapor->created_by = $rapor_sisipan->created_by;
            $rapor->updated_by = 'migration';
            $rapor->save();
        }

        $nilai_rapor_sisipans = NilaiRaporSisipan::get();
        foreach ($nilai_rapor_sisipans as $nilai_rapor_sisipan) {
            $nilai_rapor = new NilaiRapor;
            $nilai_rapor->id_nilai_rapor = $nilai_rapor_sisipan->id_nilai_rapor_sisipan;
            $nilai_rapor->id_rapor = $nilai_rapor_sisipan->id_rapor_sisipan;
            $nilai_rapor->id_komponen_jenis_rapor = $nilai_rapor_sisipan->id_komponen_nilai;
            $nilai_rapor->id_siswa = $nilai_rapor_sisipan->id_siswa;
            $nilai_rapor->nilai = $nilai_rapor_sisipan->nilai;
            $nilai_rapor->created_by = 'migration';
            $nilai_rapor->save();
        }

        $mata_pelajaran_sisipans = MataPelajaranSisipan::get();
        foreach ($mata_pelajaran_sisipans as $mata_pelajaran_sisipan) {
            $mata_pelajaran_rapor = new MataPelajaranRapor;
            $mata_pelajaran_rapor->id_mata_pelajaran_rapor = $mata_pelajaran_sisipan->id_mata_pelajaran_sisipan;
            $mata_pelajaran_rapor->id_kelompok_mapel_rapor = $mata_pelajaran_sisipan->id_kelompok_sisipan;
            $mata_pelajaran_rapor->id_sub_kelompok_mapel_rapor = $mata_pelajaran_sisipan->id_sub_kelompok_mapel_rapor;
            $mata_pelajaran_rapor->id_mata_pelajaran = $mata_pelajaran_sisipan->id_mata_pelajaran;
            $mata_pelajaran_rapor->urutan = $mata_pelajaran_sisipan->urutan;
            $mata_pelajaran_rapor->jenis = $mata_pelajaran_sisipan->jenis;
            $mata_pelajaran_rapor->keterangan = $mata_pelajaran_sisipan->keterangan;
            $mata_pelajaran_rapor->created_by = 'migration';
            $mata_pelajaran_rapor->save();
        }

        $kelas_sisipans = KelasSisipan::get();
        foreach ($kelas_sisipans as $kelas_sisipan) {
            $kelas_rapor = new KelasRapor;
            $kelas_rapor->id_kelas_rapor = $kelas_sisipan->id_kelas_sisipan;
            $kelas_rapor->id_mata_pelajaran_rapor = $kelas_sisipan->id_mata_pelajaran_sisipan;
            $kelas_rapor->id_kelas = $kelas_sisipan->id_kelas;
            $kelas_rapor->created_by = 'migration';
            $kelas_rapor->save();
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

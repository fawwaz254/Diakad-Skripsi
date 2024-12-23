<?php

namespace App\Http\Controllers\WaliMurid\Akademik;

use Carbon\Carbon;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Setting;
use App\Models\PointKPI;
use App\Models\Semester;
use App\Models\WaliKelas;
use App\Models\KelompokKPI;
use App\Models\PredikatKPI;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Libraries\SumberDaya\LibGuru;
use App\Libraries\Pendidikan\LibSiswa;

class KpiController extends Controller
{
    public function viewKpi(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_anak_murid_aktif = LibSiswa::fetchDataSiswaWaliMurid($auth_data, $auth_data->pengguna->id_pengguna, 1);

        $data_siswa = LibSiswa::fetchDataSiswaByPengguna($auth_data, $data_anak_murid_aktif->id_pengguna);

        $data_semester_aktif = Semester::where('is_aktif_semester', 1)->first();

        return view('wali-murid/akademik/kpi/view-kpi', compact('auth_data', 'data_siswa', 'data_semester_aktif'));
    }

    public function printKpi(Request $request, $id_semester, $id_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = Siswa::where('id_siswa', $id_siswa)->with('kelas')->first();
        $semester = Semester::find($id_semester);
        $kelompok_kpi = KelompokKPI::with([
            'point_kpi' => function ($q) use ($siswa, $semester) {
                return $q->where('id_semester', $semester->id_semester)->where('tingkat_kelas', $siswa->kelas->tingkat);
            }
        ])->get();

        $point_mengaji = PointKPI::where('id_semester', $semester->id_semester)->whereNull('tingkat_kelas')->where('jenis', '3')->get();
        $predikat_kpi = PredikatKPI::where('id_siswa', $id_siswa)->where('id_kelas', $siswa->id_kelas)->get();
        $kelas = WaliKelas::where('id_kelas', $siswa->kelas->id_kelas)->where('is_aktif', true)->first();
        $wali_kelas = Guru::where('id_guru', $kelas->id_guru)->first();

        $data = [];
        $dataMengaji = [];
        foreach ($kelompok_kpi as $unit_kelompok_kpi) {
            foreach ($unit_kelompok_kpi->point_kpi as $point_kpi) {
                if ($point_kpi->jenis == '1') {
                    $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['data']['nama'][] = $point_kpi->nm_point_kpi;
                    $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['data']['jenis'][] = 1;
                    $unit_predikat_kpi = $predikat_kpi->where('id_point_kpi', $point_kpi->id_point_kpi)->first();

                    if ($unit_predikat_kpi && in_array($unit_predikat_kpi->predikat, ['A', 'B', 'C', 'D'])) {
                        $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['data']['predikat'][] = $unit_predikat_kpi->predikat;
                        if (isset($data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['deskripsi'])) {
                            $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['deskripsi'] = $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['deskripsi'] . ', <br>' . $point_kpi->deskripsi[$unit_predikat_kpi->predikat];
                        } else {
                            $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['deskripsi'] = $point_kpi->deskripsi[$unit_predikat_kpi->predikat];
                        }
                    } else {
                        $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['data']['predikat'][] = 0;
                        if (isset($data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['deskripsi'])) {
                            $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['deskripsi'] = $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['deskripsi'] . ', ' . $point_kpi->deskripsi['D'];
                        } else {
                            $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['deskripsi'] = $point_kpi->deskripsi['D'];
                        }
                    }
                } else {
                    $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['data']['nama'][] = $point_kpi->nm_point_kpi;
                    $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['data']['predikat'][] = null;
                    $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['data']['jenis'][] = 0;
                }
            }
        }

        foreach ($point_mengaji as $point_kpi) {
            $unit_predikat_kpi = $predikat_kpi->where('id_point_kpi', $point_kpi->id_point_kpi)->first();
            if ($unit_predikat_kpi) {
                $dataMengaji[$point_kpi->nm_point_kpi] = $unit_predikat_kpi->predikat;
            } else {
                $dataMengaji[$point_kpi->nm_point_kpi] = "-";
            }
        }

        $tanggal = Setting::where('key_setting', 'set_tanggal_cetak_rapor_semester')->first();
        if (isset($tanggal)) {
            $tanggal_cetak = Carbon::parse($tanggal->value)->locale('id')->translatedFormat('j F Y');
        } else {
            $tanggal_cetak = Carbon::now()->locale('id')->translatedFormat('j F Y');
        }

        return view('wali-murid/akademik/kpi/print-kpi-wali-murid', compact('auth_data', 'kelompok_kpi', 'siswa', 'data', 'semester', 'dataMengaji', 'tanggal_cetak', 'wali_kelas'));
    }
}

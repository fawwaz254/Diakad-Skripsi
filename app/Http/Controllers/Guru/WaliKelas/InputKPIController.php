<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use App\Exports\EksportTemplateKPI;
use App\Http\Controllers\Controller;
use App\Imports\UploadNilaiKPI;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KelompokKPI;
use App\Models\PointKPI;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DataImportExcel;
use App\Models\KomponenNilaiRaporSisipan;
use App\Models\NilaiRaporSisipan;
use App\Models\Pengguna;
use App\Models\PredikatKPI;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\WaliKelas;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DB;
use Carbon\Carbon;
use Auth;

class InputKPIController extends Controller
{
    public function viewInputKPI(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        return view('guru/wali-kelas/kpi/input-kpi/view-input-kpi', compact('auth_data', 'semester_aktif'));
    }


    public function datatablesInputKPI(Request  $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);
        $list_data = Siswa::where('id_kelas', $wali_kelas->id_kelas)->with('predikat_kpi', 'pengguna')
            ->whereHas('predikat_kpi.point_kpi', function ($query) use ($semester_aktif) {
                $query->where('id_semester', $semester_aktif->id_semester);
            });
        $kelas = Kelas::find($wali_kelas->id_kelas);
        $jumlah_point = PointKPI::where('tingkat_kelas', $kelas->tingkat)->where('id_semester', $semester_aktif->id_semester)->where('jenis', 1)->count();

        return Datatables::of($list_data)->addColumn('jumlah_point', function () use ($jumlah_point) {
            return $jumlah_point;
        })->addColumn('jumlah_point_terisi', function ($item) {
            if ($item->predikat_kpi) {
                return $item->predikat_kpi->count();
            } else {
                return '0';
            }
        })->addColumn('action', function ($item) {
            $data = array(
                'id' => $item->id_siswa,
            );
            return $data;
        })->make(true);
    }
    public function downloadTemplateKPI(Request $request)
    {
        set_time_limit(-1);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);
        $kelas = Kelas::find($wali_kelas->id_kelas);
        $data['kelompok_kpi']  = KelompokKPI::whereHas('point_kpi', function ($query) use ($kelas, $semester_aktif) {
            $query->where('id_semester',  $semester_aktif->id_semester)->where('tingkat_kelas', $kelas->tingkat);
        })->get();

        $data['list_siswa'] = Siswa::where('id_kelas', $wali_kelas->id_kelas)->with('pengguna')
            ->get();

        $data['color'][0] = "#ff91c3";
        $data['color'][1] = "#ffef91";
        $data['color'][2] = "#91e7ff";
        $data['color'][3] = "#baff91";
        $data['color'][4] = "#b491ff";
        $data['color'][5] = "#91f6ff";


        return Excel::download(new EksportTemplateKPI($data), 'KPI Kelas (' . $wali_kelas->nm_kelas . ').xlsx');
    }

    public function viewImportKPI(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        return view('guru/wali-kelas/kpi/input-kpi/view-import-excel-kpi', compact('auth_data'));
    }

    public function actionImportKPI(Request $request)
    {
        set_time_limit(-1);
        if ($request->hasFile('file-excel')) {
            try {
                $data = Excel::toArray(new DataImportExcel, $request->file('file-excel'));
                $data = $data[0];
                if (count($data)) {
                    $now = Carbon::now(env('APP_TIMEZONE', ''));
                    $id_pengguna = Auth::id();
                    $semester_aktif = Semester::where('is_aktif_semester', '1')->first();
                    $guru = Guru::where('id_pengguna', '=', $id_pengguna)->first();
                    $wali_kelas = WaliKelas::where('id_semester', $semester_aktif->id_semester)->where('id_guru', $guru->id_guru)->where('is_aktif', 1)->first();

                    $kelas = Kelas::where('id_kelas', $wali_kelas->id_kelas)->first();
                    $list_siswa = Siswa::where('id_kelas', $wali_kelas->id_kelas)->get();
                    $sekolah = Sekolah::first();

                    $pointKPIs = PointKPI::where('id_semester',  $semester_aktif->id_semester)->where('tingkat_kelas', $kelas->tingkat)->where('jenis', 1)->get();
                    $predikatKPIs = PredikatKPI::whereIn('id_siswa', $list_siswa->pluck('id_siswa'))->whereIn('id_point_kpi', $pointKPIs->pluck('id_point_kpi'))->with('siswa')->get();
                    foreach ($data as $row) {
                        foreach ($pointKPIs as $pointKPI) {
                            if (isset($row[strtolower(str_replace(["__", "___"], ["_", "_"], str_replace([".", "'", " ", "-", "(", ")", ":", "/"], ["", "", "_", "_", "", "", "", ""], $pointKPI->nm_point_kpi)))])) {
                                $siswa = $list_siswa->where('nis_siswa', $row['nis'])->first();
                                if ($siswa) {
                                    $predikatKPI = $predikatKPIs->where('id_siswa', $siswa->id_siswa)->where('id_point_kpi', $pointKPI->id_point_kpi)->first();
                                    if ($predikatKPI) {
                                        if ($predikatKPI->predikat != $row[strtolower(str_replace(["__", "___"], ["_", "_"], str_replace([".", "'", " ", "-", "(", ")", ":", "/"], ["", "", "_", "_", "", "", "", ""], $pointKPI->nm_point_kpi)))]) {
                                            $predikatKPI->predikat = $row[strtolower(str_replace(["__", "___"], ["_", "_"], str_replace([".", "'", " ", "-", "(", ")", ":", "/"], ["", "", "_", "_", "", "", "", ""], $pointKPI->nm_point_kpi)))];
                                            $predikatKPI->updated_by = $id_pengguna;
                                            $predikatKPI->updated_at = $now;
                                            $predikatKPI->save();
                                        }
                                    } else {
                                        $predikatKPI = new PredikatKPI;
                                        $predikatKPI->id_predikat_kpi = $sekolah->prefix . strtotime($now) . uniqid();
                                        $predikatKPI->id_point_kpi = $pointKPI->id_point_kpi;
                                        $predikatKPI->id_kelas = $siswa->id_kelas;
                                        $predikatKPI->id_siswa = $siswa->id_siswa;
                                        $predikatKPI->predikat = $row[strtolower(str_replace(["__", "___"], ["_", "_"], str_replace([".", "'", " ", "-", "(", ")", ":", "/"], ["", "", "_", "_", "", "", "", ""], $pointKPI->nm_point_kpi)))];
                                        $predikatKPI->created_by = $id_pengguna;
                                        $predikatKPI->save();
                                    }
                                }
                            } else {
                                return [
                                    'status'     => 300, // FAILED
                                    // 'message'     => "Eror pada kolom" .  $pointKPI->nm_point_kpi
                                    'message'     => strtolower(str_replace(["__", "___"], ["_", "_"], str_replace([".", "'", " ", "-", "(", ")", ":", "/"], ["", "", "_", "_", "", "", "", ""], $pointKPI->nm_point_kpi)))
                                ];
                            }
                        }
                        //untuk nilai mengaji
                        // if(isset)





                    }
                    return [
                        'status'     => 300, // FAILED
                        'message'     => "Behasil Input Data"
                    ];
                }
                return [
                    'status'     => 300, // FAILED
                    'message'     => "File Excel kosong"
                ];
            } catch (\Exception $e) {
                return [
                    'status'     => 200, // FAILED
                    'message'     => $e->getMessage()
                ];
            }
        } else {
            return [
                'status'     => 300, // FAILED
                'message'     => "File Excel tidak ditemukan"
            ];
        }
    }

    public function printKPI(Request $request, $id_semester, $id_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = Siswa::where('id_siswa', $id_siswa)->with('kelas')->first();
        $semester = Semester::find($id_semester);
        $kelompok_kpi  = KelompokKPI::whereHas('point_kpi', function ($query) use ($siswa, $semester) {
            $query->where('id_semester',  $semester->id_semester)->where('tingkat_kelas', $siswa->kelas->tingkat);
        })->get();
        $predikat_kpi = PredikatKPI::where('id_siswa', $id_siswa)->where('id_kelas', $siswa->id_kelas)->get();

        $data = [];

        foreach ($kelompok_kpi as  $unit_kelompok_kpi) {
            foreach ($unit_kelompok_kpi->point_kpi as  $point_kpi) {
                if ($point_kpi->jenis == '1') {
                    $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['data']['nama'][] = $point_kpi->nm_point_kpi;
                    $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['data']['jenis'][] = 1;
                    $unit_predikat_kpi =  $predikat_kpi->where('id_point_kpi', $point_kpi->id_point_kpi)->first();
                    if ($unit_predikat_kpi && in_array($unit_predikat_kpi->predikat, ['A', 'B', 'C', 'D'])) {
                        $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['data']['predikat'][] = $unit_predikat_kpi->predikat;
                        if (isset($data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['deskripsi'])) {
                            $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['deskripsi'] = $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['deskripsi'] . ', <br>' . $point_kpi->deskripsi[$unit_predikat_kpi->predikat];
                        } else {
                            $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['deskripsi'] =  $point_kpi->deskripsi[$unit_predikat_kpi->predikat];
                        }
                    } else {
                        $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['data']['predikat'][] = 0;
                        if (isset($data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['deskripsi'])) {
                            $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['deskripsi'] = $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['deskripsi'] . ', ' . $point_kpi->deskripsi['D'];
                        } else {
                            $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['deskripsi'] =  $point_kpi->deskripsi['D'];
                        }
                    }
                } else {
                    $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['data']['nama'][] = $point_kpi->nm_point_kpi;
                    $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['data']['predikat'][] = null;
                    $data[$unit_kelompok_kpi->id_kelompok_kpi][$point_kpi->urutan]['data']['jenis'][] = 0;
                }
            }
        }


        return view('guru/wali-kelas/kpi/input-kpi/print-kpi-siswa', compact('auth_data', 'kelompok_kpi', 'siswa', 'data', 'semester'));
    }
}

<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use Validator;
use Carbon\Carbon;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\RaporPendukung;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\KomponenRaporPendukung;
use App\Models\IndikatorRaporPendukung;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\Kelas;
use App\Models\PredikatRaporPendukung;

class RaporPendukungController extends Controller
{
    public function viewListRaporPendukung()
    {
        return view('guru/wali-kelas/rapor-pendukung/view-list-rapor-pendukung');
    }

    public function datatablesRaporPendukung()
    {
        $list_data = RaporPendukung::with('komponen_rapor_pendukung')->get();

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_rapor_pendukung,
                    'total_komponen' => $item->komponen_rapor_pendukung->count()
                );
                return $data;
            })
            ->make(true);
    }

    public function actionRaporPendukung(Request $request)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_rapor' => 'required',
        ]);

        if ($validator->fails() && $input->mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            $now = Carbon::now();

            if ($input->mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $rapor                               = new RaporPendukung();
                $rapor->id_rapor_pendukung  = $id;
                $rapor->nm_rapor                  = $input->nm_rapor;
                $rapor->created_by                   = $input->auth_data->pengguna->id_pengguna;
                $rapor->save();

                return [
                    'status' => 203, // SUCCESS AND LOAD CONTENT
                    'path' => 'wali-kelas/rapor-pendukung',
                    'message' => 'Save Data Succesfully'
                ];
            } elseif ($input->mode == 'update') {
                $rapor = RaporPendukung::findOrFail($input->id_rapor_pendukung);
                $rapor->nm_rapor                  = $input->nm_rapor;
                $rapor->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                $rapor->save();

                return [
                    'status' => 203, // SUCCESS AND LOAD CONTENT
                    'path' => 'wali-kelas/rapor-pendukung',
                    'message' => 'Save Data Succesfully'
                ];
            } elseif ($input->mode == 'delete') {
                $rapor = RaporPendukung::findOrFail($input->id_rapor_pendukung);

                foreach ($rapor->komponen_rapor_pendukung()->get() as $komponen) {

                    foreach ($komponen->indikator_rapor_pendukung()->get() as $indikator) {

                        foreach ($indikator->predikat_rapor_pendukung()->get() as $predikat) {

                            $predikat->deleted_by = $input->auth_data->pengguna->id_pengguna;
                            $predikat->save();
                            $predikat->delete();
                        }

                        $indikator->deleted_by = $input->auth_data->pengguna->id_pengguna;
                        $indikator->save();
                        $indikator->delete();
                    }

                    $komponen->deleted_by = $input->auth_data->pengguna->id_pengguna;
                    $komponen->save();
                    $komponen->delete();
                }

                $rapor->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $rapor->save();
                $rapor->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Data Jenis succesfully'
                ];
            }
        }
    }

    public function getRaporPendukung(Request $request)
    {
        $item = RaporPendukung::select('id_rapor_pendukung', 'nm_rapor')->find($request->id_rapor_pendukung);

        return response()->json([
            'status_code'     => 200,
            'status_text'     => 'Success',
            "data" => $item
        ]);
    }

    public function viewListKomponenRaporPendukung(Request $request, $global_role, $global_modul, $id_rapor_pendukung)
    {
        $rapor_pendukung = RaporPendukung::findOrFail($id_rapor_pendukung);

        return view('guru/wali-kelas/rapor-pendukung/view-list-komponen-rapor-pendukung', compact('rapor_pendukung'));
    }

    public function datatablesKomponenRaporPendukung(Request $request, $global_role, $global_modul, $id_rapor_pendukung)
    {
        $list_data = KomponenRaporPendukung::where('id_rapor_pendukung', $id_rapor_pendukung)->get();

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_komponen_rapor_pendukung,
                );
                return $data;
            })
            ->make(true);
    }

    public function actionKomponenRaporPendukung(Request $request, $global_role, $global_modul, $id_rapor_pendukung)
    {
        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'nm_komponen' => 'required',
            'urutan' => 'required',
        ]);

        if ($validator->fails() && $input->mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            $now = Carbon::now();

            if ($input->mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $komponen                               = new KomponenRaporPendukung();
                $komponen->id_komponen_rapor_pendukung  = $id;
                $komponen->id_rapor_pendukung           = $id_rapor_pendukung;
                $komponen->nm_komponen                  = $input->nm_komponen;
                $komponen->urutan                       = $input->urutan;
                $komponen->created_by                   = $input->auth_data->pengguna->id_pengguna;
                $komponen->save();

                return [
                    'status' => 203, // SUCCESS AND LOAD CONTENT
                    'path' => 'wali-kelas/rapor-pendukung/komponen/' . $id_rapor_pendukung,
                    'message' => 'Save Data Succesfully'
                ];
            } elseif ($input->mode == 'update') {
                $komponen = KomponenRaporPendukung::findOrFail($input->id_komponen_rapor_pendukung);
                $komponen->nm_komponen                  = $input->nm_komponen;
                $komponen->urutan                       = $input->urutan;
                $komponen->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                $komponen->save();

                return [
                    'status' => 203, // SUCCESS AND LOAD CONTENT
                    'path' => 'wali-kelas/rapor-pendukung/komponen/' . $id_rapor_pendukung,
                    'message' => 'Save Data Succesfully'
                ];
            } elseif ($input->mode == 'delete') {
                $komponen = KomponenRaporPendukung::findOrFail($input->id_komponen_rapor_pendukung);

                foreach ($komponen->indikator_rapor_pendukung()->get() as $indikator) {

                    foreach ($indikator->predikat_rapor_pendukung()->get() as $predikat) {

                        $predikat->deleted_by = $input->auth_data->pengguna->id_pengguna;
                        $predikat->save();
                        $predikat->delete();
                    }

                    $indikator->deleted_by = $input->auth_data->pengguna->id_pengguna;
                    $indikator->save();
                    $indikator->delete();
                }

                $komponen->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $komponen->save();
                $komponen->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Data Jenis succesfully'
                ];
            }
        }
    }

    public function getKomponenRaporPendukung(Request $request)
    {
        $item = KomponenRaporPendukung::select('id_komponen_rapor_pendukung', 'nm_komponen', 'urutan')->find($request->id_komponen_rapor_pendukung);

        return response()->json([
            'status_code'     => 200,
            'status_text'     => 'Success',
            "data" => $item
        ]);
    }

    public function viewListIndikatorRaporPendukung(Request $request, $global_role, $global_modul, $id_komponen_rapor_pendukung)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $komponen = KomponenRaporPendukung::select('id_komponen_rapor_pendukung', 'id_rapor_pendukung', 'nm_komponen')->find($id_komponen_rapor_pendukung);

        $semester = Semester::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('thn_akademik_semester', 'asc')->orderBy('nm_semester', 'asc')->get();

        return view('guru/wali-kelas/rapor-pendukung/view-list-indikator-rapor-pendukung', compact('semester', 'komponen'));
    }

    public function datatablesIndikatorRaporPendukung(Request $request, $global_role, $global_modul, $id_komponen_rapor_pendukung)
    {
        $list_data = IndikatorRaporPendukung::with('semester')
            ->where('id_komponen_rapor_pendukung', $id_komponen_rapor_pendukung)
            ->get();

        return Datatables::of($list_data)
            ->addColumn('semester', function ($item) {
                return $item->semester->tahun_ajaran . ' (' . $item->semester->nm_semester . ')';
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_indikator_rapor_pendukung,
                );
                return $data;
            })
            ->make(true);
    }

    public function actionIndikatorRaporPendukung(Request $request, $global_role, $global_modul, $id_komponen_rapor_pendukung)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'mode' => 'required',
            'id_semester' => 'required',
            'tingkat_kelas' => 'required',
            'nm_indikator' => 'required',
            'urutan' => 'required',
        ]);

        if ($validator->fails() && $input->mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            $now = Carbon::now();

            if ($input->mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $indikator                               = new IndikatorRaporPendukung();
                $indikator->id_indikator_rapor_pendukung = $id;
                $indikator->id_komponen_rapor_pendukung  = $id_komponen_rapor_pendukung;
                $indikator->id_semester                  = $input->id_semester;
                $indikator->tingkat_kelas                = $input->tingkat_kelas;
                $indikator->nm_indikator                 = $input->nm_indikator;
                $indikator->urutan                       = $input->urutan;
                $indikator->created_by                   = $input->auth_data->pengguna->id_pengguna;
                $indikator->save();

                return [
                    'status' => 203, // SUCCESS AND LOAD CONTENT
                    'path' => 'wali-kelas/rapor-pendukung/indikator/' . $id_komponen_rapor_pendukung,
                    'message' => 'Save Data Succesfully'
                ];
            } elseif ($input->mode == 'update') {
                $indikator = IndikatorRaporPendukung::findOrFail($input->id_indikator_rapor_pendukung);
                $indikator->id_semester                  = $input->id_semester;
                $indikator->tingkat_kelas                = $input->tingkat_kelas;
                $indikator->nm_indikator                 = $input->nm_indikator;
                $indikator->urutan                       = $input->urutan;
                $indikator->updated_by                   = $input->auth_data->pengguna->id_pengguna;
                $indikator->save();

                return [
                    'status' => 203, // SUCCESS AND LOAD CONTENT
                    'path' => 'wali-kelas/rapor-pendukung/indikator/' . $id_komponen_rapor_pendukung,
                    'message' => 'Save Data Succesfully'
                ];
            } elseif ($input->mode == 'delete') {
                $indikator = IndikatorRaporPendukung::findOrFail($input->id_indikator_rapor_pendukung);

                foreach ($indikator->predikat_rapor_pendukung()->get() as $child) {
                    $child->delete();
                }

                $indikator->deleted_by   = $input->auth_data->pengguna->id_pengguna;
                $indikator->save();
                $indikator->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Data Jenis succesfully'
                ];
            }
        }
    }

    public function getIndikatorRaporPendukung(Request $request)
    {
        $item = IndikatorRaporPendukung::select('id_indikator_rapor_pendukung', 'id_semester', 'tingkat_kelas', 'nm_indikator', 'urutan')->find($request->id_indikator_rapor_pendukung);

        return response()->json([
            'status_code'     => 200,
            'status_text'     => 'Success',
            "data" => $item
        ]);
    }

    public function viewInputPredikatRaporPendukung(Request $request, $global_role, $global_modul, $id_rapor_pendukung)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);
        $kelas = Kelas::where('id_kelas', $wali_kelas->id_kelas)->first();


        $list_siswa = Siswa::where('id_kelas', $wali_kelas->id_kelas)
            ->with(['kelas', 'pengguna', 'pengguna.status_pengguna'])
            ->whereHas('pengguna.status_pengguna', function ($q) {
                $q->where('aktif_status_pengguna', 1);
            })
            ->orderBy('nis_siswa')
            ->get();

        $komponen_rapor = KomponenRaporPendukung::with(['indikator_rapor_pendukung' => function ($query) use ($kelas) {
            $query->where('tingkat_kelas', $kelas->tingkat);
        }, 'indikator_rapor_pendukung.predikat_rapor_pendukung'])
            ->where('id_rapor_pendukung', $id_rapor_pendukung)
            ->orderBy('urutan')
            ->get();

        $list_data = [];

        foreach ($list_siswa as $key_siswa => $siswa) {
            $list_data[$key_siswa]['NIS Siswa'] = $siswa->nis_siswa;
            $list_data[$key_siswa]['Nama Siswa'] = $siswa->pengguna->nm_pengguna;

            foreach ($komponen_rapor as $komponen) {
                foreach ($komponen->indikator_rapor_pendukung as $indikator) {
                    if ($indikator->predikat_rapor_pendukung->count() > 0) {
                        foreach ($indikator->predikat_rapor_pendukung as $predikat) {
                            if ($predikat->id_siswa == $siswa->id_siswa) {
                                $list_data[$key_siswa][$indikator->nm_indikator] = $predikat->nilai;
                            }
                        }
                    } else {
                        $list_data[$key_siswa][$indikator->nm_indikator] = '';
                    }
                }
            }
        }

        $rapor = RaporPendukung::findOrFail($id_rapor_pendukung);

        return view('guru/wali-kelas/rapor-pendukung/view-input-predikat-rapor-pendukung', compact('rapor', 'komponen_rapor', 'list_data'));
    }

    public function actionInputPredikatRaporPendukung(Request $request, $global_role, $global_modul, $id_rapor_pendukung)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        $list_predikat = PredikatRaporPendukung::where('id_kelas', $wali_kelas->id_kelas)->get();

        $list_siswa = Siswa::where('id_kelas', $wali_kelas->id_kelas)
            ->with(['pengguna', 'pengguna.status_pengguna'])
            ->whereHas('pengguna.status_pengguna', function ($q) {
                $q->where('aktif_status_pengguna', 1);
            })
            ->orderBy('nis_siswa')
            ->get();

        foreach ($list_siswa as $siswa) {

            foreach ($input->data as $data) {

                if ($siswa->nis_siswa == $data["nis_siswa"]) {

                    foreach ($data["data"] as $value) {

                        $predikatExist = $list_predikat
                            ->where('id_indikator_rapor_pendukung', $value["id_indikator_rapor_pendukung"])
                            ->where('id_kelas', $wali_kelas->id_kelas)
                            ->where('id_siswa', $siswa->id_siswa)
                            ->first();

                        if (!$predikatExist) {

                            $id = $input->auth_data->sekolah_data->prefix . strtotime(now()) . uniqid();

                            $predikat = new PredikatRaporPendukung();
                            $predikat->id_predikat_rapor_pendukung = $id;
                            $predikat->id_indikator_rapor_pendukung = $value["id_indikator_rapor_pendukung"];
                            $predikat->id_kelas = $wali_kelas->id_kelas;
                            $predikat->id_siswa = $siswa->id_siswa;
                            $predikat->nilai = $value["nilai"];
                            $predikat->save();
                        } else {

                            $predikatExist->nilai = $value["nilai"];
                            $predikatExist->save();
                        }
                    }
                }
            }
        }

        return [
            'status' => 203, // SUCCESS AND LOAD CONTENT
            'path' => 'wali-kelas/rapor-pendukung/predikat/' . $id_rapor_pendukung,
            'message' => 'Save Data Succesfully'
        ];
    }

    public function cetakRaporPendukung(Request $request, $global_role, $global_modul, $id_rapor_pendukung)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);
        $kelas = Kelas::where('id_kelas', $wali_kelas->id_kelas)->first();

        $list_siswa = Siswa::where('id_kelas', $wali_kelas->id_kelas)
            ->with(['pengguna', 'pengguna.status_pengguna'])
            ->whereHas('pengguna.status_pengguna', function ($q) {
                $q->where('aktif_status_pengguna', 1);
            })
            ->orderBy('nis_siswa')
            ->get();

        $rapor = RaporPendukung::where('id_rapor_pendukung', $id_rapor_pendukung)->first();

        $list_komponen_rapor = KomponenRaporPendukung::with('indikator_rapor_pendukung.predikat_rapor_pendukung')
            ->where('id_rapor_pendukung', $id_rapor_pendukung)
            ->orderBy('urutan')
            ->get();

        return view('guru/wali-kelas/rapor-pendukung/print-rapor-pendukung', compact('auth_data', 'semester_aktif', 'kelas', 'list_siswa', 'rapor', 'list_komponen_rapor'));
    }
}

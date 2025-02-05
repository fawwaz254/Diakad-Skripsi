<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use Validator;
use Carbon\Carbon;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Setting;
use App\Models\Semester;
use Illuminate\Http\Request;
use App\Models\RaporPendukung;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\KomponenRaporPendukung;
use App\Models\PredikatRaporPendukung;
use App\Models\IndikatorRaporPendukung;
use App\Libraries\Pendidikan\LibDataAkademik;

class RaporPendukungController extends Controller
{
    public function viewListRaporPendukung()
    {
        return view('guru/wali-kelas/rapor-pendukung/view-list-rapor-pendukung');
    }

    public function viewTemplateRaporPendukung(Request $request, $global_role, $global_modul, $id_rapor_pendukung)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);
        $kelas = Kelas::findOrFail($wali_kelas->id_kelas);

        $list_siswa = Siswa::whereHas('pengguna.status_pengguna', function ($q) {
            $q->where('aktif_status_pengguna', 1);
        })
            ->with('kelas', 'pengguna')
            ->where('id_kelas', $kelas->id_kelas)
            ->orderBy('nis_siswa')
            ->get();

        $komponen_rapor = KomponenRaporPendukung::with(['indikator_rapor_pendukung' => function ($q) use ($kelas, $semester_aktif) {
            $q->where('tingkat_kelas', $kelas->tingkat)->where('id_semester', $semester_aktif->id_semester);
        }])
            ->where('id_rapor_pendukung', $id_rapor_pendukung)
            ->orderBy('urutan')
            ->get();

        // $list_catatan_siswa = PredikatRaporPendukung::where('id_rapor_pendukung', $id_rapor_pendukung)->whereNull('id_indikator_rapor_pendukung')->get();

        $header = [
            'Komponen',
            'Indikator',
        ];

        $headers = array_merge($header, $list_siswa->pluck('nis_siswa')->toArray());
        $bodies = [];

        foreach ($komponen_rapor->sortBy('urutan')->values() as $komponen) {
            foreach ($komponen->indikator_rapor_pendukung->sortBy('urutan')->values() as $key => $indikator) {
                $temp = [];
                $temp[] = $komponen->nm_komponen;
                $temp[] = $indikator->urutan . '. ' . $indikator->nm_indikator;
                $bodies[] = $temp;
            }
        }

        return Excel::download(new TemplateExcel($headers, $bodies), 'Template Excel pengisian rapor pendukung Kelas ' . $kelas->nm_kelas . ' Semester ' . $semester_aktif->kode_semester . '.xlsx');
    }

    public function datatablesRaporPendukung()
    {
        $list_data = RaporPendukung::withCount('komponen_rapor_pendukung')->get();

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                return [
                    'id' => $item->id_rapor_pendukung,
                    'total_komponen' => $item->komponen_rapor_pendukung_count
                ];
            })
            ->make(true);
    }

    public function getRaporPendukung(Request $request)
    {
        $item = RaporPendukung::findOrFail($request->id_rapor_pendukung);

        return response()->json([
            'status_code' => 200,
            'status_text' => 'Success',
            'data' => $item
        ]);
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
        }

        DB::beginTransaction();

        try {
            $now = Carbon::now();

            if ($input->mode == 'add') {
                $rapor = new RaporPendukung();
                $rapor->id_rapor_pendukung = $input->auth_data->sekolah_data->prefix . $now->timestamp . uniqid();
                $rapor->nm_rapor = $input->nm_rapor;
                $rapor->created_by = $input->auth_data->pengguna->id_pengguna;
                $rapor->save();
            } elseif ($input->mode == 'update') {
                $rapor = RaporPendukung::findOrFail($input->id_rapor_pendukung);
                $rapor->nm_rapor = $input->nm_rapor;
                $rapor->updated_by = $input->auth_data->pengguna->id_pengguna;
                $rapor->save();
            } elseif ($input->mode == 'delete') {
                $rapor = RaporPendukung::findOrFail($input->id_rapor_pendukung);
                $rapor->deleted_by = $input->auth_data->pengguna->id_pengguna;
                $rapor->save();

                $rapor->komponen_rapor_pendukung()->each(function ($komponen) {
                    $komponen->indikator_rapor_pendukung()->each(function ($indikator) {
                        $indikator->predikat_rapor_pendukung()->delete();
                        $indikator->delete();
                    });
                    $komponen->delete();
                });

                $rapor->delete();
            }

            DB::commit();

            return [
                'status' => 203, // SUCCESS AND LOAD CONTENT
                'path' => 'wali-kelas/rapor-pendukung',
                'message' => 'Save Data Successfully'
            ];
        } catch (\Exception $e) {
            DB::rollback();

            return [
                'status' => 500, // INTERNAL SERVER ERROR
                'message' => 'Error occurred: ' . $e->getMessage()
            ];
        }
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

    public function getKomponenRaporPendukung(Request $request)
    {
        $item = KomponenRaporPendukung::select('id_komponen_rapor_pendukung', 'nm_komponen', 'urutan')->findOrFail($request->id_komponen_rapor_pendukung);

        return response()->json([
            'status_code' => 200,
            'status_text' => 'Success',
            'data' => $item
        ]);
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
        }

        DB::beginTransaction();

        try {
            $now = Carbon::now();

            if ($input->mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $komponen = KomponenRaporPendukung::create([
                    'id_komponen_rapor_pendukung' => $id,
                    'id_rapor_pendukung' => $id_rapor_pendukung,
                    'nm_komponen' => $input->nm_komponen,
                    'urutan' => $input->urutan,
                    'created_by' => $input->auth_data->pengguna->id_pengguna
                ]);
            } elseif ($input->mode == 'update') {
                $komponen = KomponenRaporPendukung::findOrFail($input->id_komponen_rapor_pendukung);
                $komponen->nm_komponen = $input->nm_komponen;
                $komponen->urutan = $input->urutan;
                $komponen->updated_by = $input->auth_data->pengguna->id_pengguna;
                $komponen->save();
            } elseif ($input->mode == 'delete') {
                $komponen = KomponenRaporPendukung::findOrFail($input->id_komponen_rapor_pendukung);

                foreach ($komponen->indikator_rapor_pendukung as $indikator) {
                    foreach ($indikator->predikat_rapor_pendukung as $predikat) {
                        $predikat->update(['deleted_by' => $input->auth_data->pengguna->id_pengguna]);
                        $predikat->delete();
                    }

                    $indikator->update(['deleted_by' => $input->auth_data->pengguna->id_pengguna]);
                    $indikator->delete();
                }

                $komponen->update(['deleted_by' => $input->auth_data->pengguna->id_pengguna]);
                $komponen->delete();
            }

            DB::commit();

            return [
                'status' => 203, // SUCCESS
                'message' => $input->mode == 'delete' ? 'Delete Data Successfully' : 'Save Data Successfully',
                'path' => $input->mode == 'delete' ? null : 'wali-kelas/rapor-pendukung/komponen/' . $id_rapor_pendukung
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => 500, // ERROR
                'message' => 'Error occurred: ' . $e->getMessage()
            ];
        }
    }

    public function viewListIndikatorRaporPendukung(Request $request, $global_role, $global_modul, $id_komponen_rapor_pendukung)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $komponen = KomponenRaporPendukung::select('id_komponen_rapor_pendukung', 'id_rapor_pendukung', 'nm_komponen')->find($id_komponen_rapor_pendukung);

        $semester = Semester::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->orderBy('thn_akademik_semester', 'asc')->orderBy('nm_semester', 'asc')->get();

        return view('guru/wali-kelas/rapor-pendukung/view-list-indikator-rapor-pendukung', compact('auth_data', 'semester', 'komponen'));
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

    public function getIndikatorRaporPendukung(Request $request)
    {
        $item = IndikatorRaporPendukung::findOrFail($request->id_indikator_rapor_pendukung);

        return response()->json([
            'status_code' => 200,
            'status_text' => 'Success',
            'data' => $item
        ]);
    }

    public function actionIndikatorRaporPendukung(Request $request, $global_role, $global_modul, $id_komponen_rapor_pendukung)
    {
        $input = (object) $request->input();

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
        }

        DB::beginTransaction();

        try {
            $now = Carbon::now();

            if ($input->mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $indikator = IndikatorRaporPendukung::create([
                    'id_indikator_rapor_pendukung' => $id,
                    'id_komponen_rapor_pendukung' => $id_komponen_rapor_pendukung,
                    'id_semester' => $input->id_semester,
                    'tingkat_kelas' => $input->tingkat_kelas,
                    'nm_indikator' => $input->nm_indikator,
                    'urutan' => $input->urutan,
                    'created_by' => $input->auth_data->pengguna->id_pengguna
                ]);
            } elseif ($input->mode == 'update') {
                $indikator = IndikatorRaporPendukung::findOrFail($input->id_indikator_rapor_pendukung);
                $indikator->id_semester = $input->id_semester;
                $indikator->tingkat_kelas = $input->tingkat_kelas;
                $indikator->nm_indikator = $input->nm_indikator;
                $indikator->urutan = $input->urutan;
                $indikator->updated_by = $input->auth_data->pengguna->id_pengguna;
                $indikator->save();
            } elseif ($input->mode == 'delete') {
                $indikator = IndikatorRaporPendukung::findOrFail($input->id_indikator_rapor_pendukung);

                $indikator->predikat_rapor_pendukung()->delete();

                $indikator->update(['deleted_by' => $input->auth_data->pengguna->id_pengguna]);
                $indikator->delete();
            }

            DB::commit();

            return [
                'status' => 203, // SUCCESS
                'message' => $input->mode == 'delete' ? 'Delete Data Successfully' : 'Save Data Successfully',
                'path' => $input->mode == 'delete' ? null : 'wali-kelas/rapor-pendukung/indikator/' . $id_komponen_rapor_pendukung
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            return [
                'status' => 500, // ERROR
                'message' => 'Error occurred: ' . $e->getMessage()
            ];
        }
    }

    public function viewInputPredikatRaporPendukung(Request $request, $global_role, $global_modul, $id_rapor_pendukung)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);
        $kelas = Kelas::findOrFail($wali_kelas->id_kelas);

        $list_siswa = Siswa::whereHas('pengguna.status_pengguna', function ($q) {
            $q->where('aktif_status_pengguna', 1);
        })
            ->with(['kelas', 'pengguna'])
            ->where('id_kelas', $kelas->id_kelas)
            ->orderBy('nis_siswa')
            ->get();

        if ($kelas->tingkat == 1) {
            $tingkat = 10;
        } elseif ($kelas->tingkat == 2) {
            $tingkat = 11;
        } elseif ($kelas->tingkat == 3) {
            $tingkat = 12;
        }

        $komponen_rapor = KomponenRaporPendukung::with('indikator_rapor_pendukung.predikat_rapor_pendukung')
            ->join('indikator_rapor_pendukung as irp', 'irp.id_komponen_rapor_pendukung', '=', 'komponen_rapor_pendukung.id_komponen_rapor_pendukung')
            ->where('irp.tingkat_kelas', $tingkat)
            ->where('id_rapor_pendukung', $id_rapor_pendukung)
            ->orderBy('komponen_rapor_pendukung.urutan')
            ->orderBy('irp.urutan')
            ->get();

        $list_catatan_siswa = PredikatRaporPendukung::where('id_rapor_pendukung', $id_rapor_pendukung)->whereNull('id_indikator_rapor_pendukung')->get();

        $list_data = [];

        foreach ($list_siswa as $siswa) {
            $data_siswa = [
                'NIS Siswa' => $siswa->nis_siswa,
                'Nama Siswa' => $siswa->pengguna->nm_pengguna,
            ];

            // PREDIKAT SISWA
            foreach ($komponen_rapor as $komponen) {
                foreach ($komponen->indikator_rapor_pendukung as $indikator) {
                    $predikat_siswa = $indikator->predikat_rapor_pendukung
                        ->where('id_siswa', $siswa->id_siswa)
                        ->first();

                    $data_siswa['[' . $komponen->nm_komponen . '] ' . $indikator->nm_indikator] = $predikat_siswa ? $predikat_siswa->nilai : '';
                }
            }

            // CATATAN SISWA
            $catatan_siswa = $list_catatan_siswa->first(function ($catatan) use ($siswa) {
                return $catatan->id_siswa == $siswa->id_siswa;
            });

            $data_siswa['Catatan'] = $catatan_siswa ? $catatan_siswa->nilai : '';

            $list_data[] = $data_siswa;
        }

        $rapor = RaporPendukung::findOrFail($id_rapor_pendukung);

        return view('guru/wali-kelas/rapor-pendukung/view-input-predikat-rapor-pendukung', compact('rapor', 'komponen_rapor', 'list_data', 'list_catatan_siswa'));
    }

    public function actionInputPredikatRaporPendukung(Request $request, $global_role, $global_modul, $id_rapor_pendukung)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data = json_decode($input->data);
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

            foreach ($data as $item) {

                if ($siswa->nis_siswa == $item->nis_siswa) {

                    foreach ($item->data as $key => $value) {

                        if ($key < count($item->data) - 1) {

                            $predikatExist = $list_predikat
                                ->where('id_indikator_rapor_pendukung', $value->id_indikator_rapor_pendukung)
                                ->where('id_kelas', $wali_kelas->id_kelas)
                                ->where('id_siswa', $siswa->id_siswa)
                                ->first();

                            if (!$predikatExist) {

                                $id = $input->auth_data->sekolah_data->prefix . strtotime(now()) . uniqid();

                                $predikat = new PredikatRaporPendukung();
                                $predikat->id_predikat_rapor_pendukung = $id;
                                $predikat->id_indikator_rapor_pendukung = $value->id_indikator_rapor_pendukung;
                                $predikat->id_kelas = $wali_kelas->id_kelas;
                                $predikat->id_siswa = $siswa->id_siswa;
                                $predikat->nilai = $value->nilai;
                                $predikat->tipe = 'PREDIKAT';
                                $predikat->save();
                            } else {

                                $predikatExist->nilai = $value->nilai;
                                $predikatExist->save();
                            }
                        } else {

                            $catatanExist = $list_predikat
                                ->whereNull('id_indikator_rapor_pendukung')
                                ->where('id_rapor_pendukung', $id_rapor_pendukung)
                                ->where('id_kelas', $wali_kelas->id_kelas)
                                ->where('id_siswa', $siswa->id_siswa)
                                ->first();

                            if (!$catatanExist) {

                                $id = $input->auth_data->sekolah_data->prefix . strtotime(now()) . uniqid();

                                $predikat = new PredikatRaporPendukung();
                                $predikat->id_predikat_rapor_pendukung = $id;
                                $predikat->id_rapor_pendukung = $id_rapor_pendukung;
                                $predikat->id_kelas = $wali_kelas->id_kelas;
                                $predikat->id_siswa = $siswa->id_siswa;
                                $predikat->nilai = $value->nilai;
                                $predikat->tipe = 'CATATAN';
                                $predikat->save();
                            } else {

                                $catatanExist->nilai = $value->nilai;
                                $catatanExist->save();
                            }
                        }
                    }
                }
            }
        }

        // dump($list_siswa, $list_predikat);
        dd($list_siswa, $list_predikat);

        return [
            'status' => 203, // SUCCESS AND LOAD CONTENT
            'path' => 'wali-kelas/rapor-pendukung/predikat/' . $id_rapor_pendukung,
            'message' => 'Save Data Successfully'
        ];
    }

    public function cetakRaporPendukung(Request $request, $global_role, $global_modul, $id_rapor_pendukung)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $guru = Guru::with('pengguna')->where('id_pengguna', $auth_data->pengguna->id_pengguna)->first();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        $list_siswa = Siswa::with(['pengguna.status_pengguna'])
            ->where('id_kelas', $wali_kelas->id_kelas)
            ->whereHas('pengguna.status_pengguna', function ($q) {
                $q->where('aktif_status_pengguna', 1);
            })
            ->orderBy('nis_siswa')
            ->get();

        $kelas = Kelas::findOrFail($wali_kelas->id_kelas);

        $rapor = RaporPendukung::findOrFail($id_rapor_pendukung);

        if ($kelas->tingkat == 1) {
            $tingkat = 10;
        } elseif ($kelas->tingkat == 2) {
            $tingkat = 11;
        } elseif ($kelas->tingkat == 3) {
            $tingkat = 12;
        }

        $list_komponen_rapor = KomponenRaporPendukung::with(['indikator_rapor_pendukung' => function ($q) use ($tingkat) {
            $q->where('tingkat_kelas', $tingkat);
        }])
            ->where('id_rapor_pendukung', $id_rapor_pendukung)
            ->whereHas('indikator_rapor_pendukung', function ($q) use ($tingkat) {
                $q->where('tingkat_kelas', $tingkat);
            })
            ->orderBy('urutan')
            ->get();

        $tanggal = Setting::where('key_setting', 'set_tanggal_cetak_rapor_semester')->first();
        if (isset($tanggal)) {
            $tanggal_cetak = Carbon::parse($tanggal->value)->locale('id')->translatedFormat('j F Y');
        } else {
            $tanggal_cetak = Carbon::now()->locale('id')->translatedFormat('j F Y');
        }

        if (str_contains($rapor->nm_rapor, 'P5')) {
            $list_catatan_siswa = PredikatRaporPendukung::where(
                'id_rapor_pendukung',
                $rapor->id_rapor_pendukung,
            )
                ->whereNull('id_indikator_rapor_pendukung')
                ->get();

            return view('guru/wali-kelas/rapor-pendukung/print-custom-p5-rapor-pendukung', compact('auth_data', 'semester_aktif', 'kelas', 'list_siswa', 'rapor', 'list_komponen_rapor', 'list_catatan_siswa', 'tanggal_cetak', 'wali_kelas'));
        } else {
            $predikat_rapor_pendukung = DB::select("select prp.id_siswa, irp.id_indikator_rapor_pendukung, prp.tipe, prp.nilai 
                    from indikator_rapor_pendukung irp
                    left join predikat_rapor_pendukung prp on prp.id_indikator_rapor_pendukung = irp.id_indikator_rapor_pendukung and prp.deleted_at is null and prp.id_kelas = '$kelas->id_kelas'
                    where irp.deleted_at is null
                    and irp.tingkat_kelas = $kelas->tingkat
                    and irp.id_semester = '$semester_aktif->id_semester'");

            $predikat_rapor_pendukung = collect($predikat_rapor_pendukung);

            return view('guru/wali-kelas/rapor-pendukung/print-rapor-pendukung', compact('auth_data', 'semester_aktif', 'kelas', 'list_siswa', 'rapor', 'list_komponen_rapor', 'predikat_rapor_pendukung', 'tanggal_cetak'));
        }
    }
}

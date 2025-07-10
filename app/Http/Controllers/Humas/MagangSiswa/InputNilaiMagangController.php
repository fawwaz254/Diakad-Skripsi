<?php

namespace App\Http\Controllers\Humas\MagangSiswa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\PengambilanMagang as PengajuanSiswaMagang;
use App\Models\KomponenMagang as KomponenMagang;
use App\Models\PeriodeMagang as PeriodeMagang;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Siswa as Siswa;
use App\Models\Pengguna as Pengguna;
use App\Models\PeraturanNilai as PeraturanNilai;
use App\Models\NilaiMagang as NilaiMagang;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibMagangSiswa;
use Illuminate\Support\Facades\Redirect;

use Auth;
use DB;
use Session;
use Validator;

class InputNilaiMagangController extends BaseController
{
    public function viewPeriodeMagang(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_periode_magang = LibMagangSiswa::fetchDataPeriodeMagang($auth_data);

        return view('humas/magang-siswa/input-nilai-magang/view-periode-magang', compact('auth_data', 'data_periode_magang'));
    }

    public function actionViewKomponenInputNilaiMagang(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $validator = Validator::make($request->all(), [
            'id_periode_magang' => 'required'
        ]);
        $list_data = KomponenMagang::select('persentase_komponen_magang')->where('id_periode_magang', '=', $input->id_periode_magang)->get();
        $data = array_values($list_data->toArray());

        $persentase = array();
        for ($i = 0; $i < count($data); $i++) {
            array_push($persentase, $data[$i]['persentase_komponen_magang']);
        }

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            if (array_sum($persentase) < 100) {
                return [
                    'status' => 300, // FAILED
                    'message' => "Tidak Dapat Meng-Input Nilai. Persentase Komponen Kurang Dari 100%."
                ];
            } elseif (array_sum($persentase) > 100) {
                return [
                    'status' => 300, // FAILED
                    'message' => "Tidak Dapat Meng-Input Nilai. Persentase Komponen Lebih Dari 100%."
                ];
            } else {

                return [
                    'status' => 204, // SUCCESS AND LOAD CONTENT
                    'path' => 'magang-siswa/input-nilai-magang/view-komponen/' . $input->id_periode_magang
                ];
            }
        }
    }

    public function viewKomponenInputNilaiMagang(Request $request, $id_periode_magang)
    {

        $input = (object) $request->input();
        $auth_data = auth_data();

        $periodeMagang = PeriodeMagang::leftJoin('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')->where('periode_magang.id_periode_magang', '=', $id_periode_magang)->first();

        $list_data = KomponenMagang::where('id_periode_magang', '=', $id_periode_magang)->get();

        $list_siswa = PengajuanSiswaMagang::join('siswa', 'siswa.id_siswa', '=', 'pengambilan_magang.id_siswa')
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->join('rekanan_magang', 'rekanan_magang.id_rekanan_magang', '=', 'pengambilan_magang.id_rekanan_magang')
            ->join('periode_magang', 'periode_magang.id_periode_magang', '=', 'pengambilan_magang.id_periode_magang')
            ->join('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
            ->where('pengambilan_magang.id_periode_magang', '=', $id_periode_magang)
            ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->where('status_magang', 1)
            ->get();

        $list_nilai = NilaiMagang::select('nilai_magang.id_pengambilan_magang', 'nilai_magang.id_komponen_magang', 'nilai_magang.besar_nilai_magang', 'komponen_magang.urutan_komponen_magang')->join('komponen_magang', 'nilai_magang.id_komponen_magang', '=', 'komponen_magang.id_komponen_magang')
            ->where('komponen_magang.id_periode_magang', '=', $id_periode_magang)
            ->get();

        $nilai_magang_siswa = [];
        if ($list_siswa) {
            $nilai = $list_nilai->toArray();
            foreach ($nilai as $komponen => $nilaiMagang) {
                foreach ($nilaiMagang as $nama_komponen => $besarNilai) {
                    $nilai_magang_siswa[$nilaiMagang['id_pengambilan_magang'] . $nilaiMagang['id_komponen_magang']] = $nilaiMagang['besar_nilai_magang'];
                }
            }
        }

        return view('humas/magang-siswa/input-nilai-magang/view-komponen-input-nilai-magang', compact('auth_data', 'id_periode_magang', 'periodeMagang', 'list_data', 'list_siswa', 'nilai_magang_siswa'));
    }

    public function datatablesKomponenNilaiMagang(Request $request, $id_periode_magang)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $nilai_siswa = NilaiMagang::select(
            'nilai_magang.id_nilai_magang',
            'nilai_magang.id_komponen_magang',
            'nilai_magang.besar_nilai_magang',
            'komponen_magang.nm_komponen_magang',
            'periode_magang.nm_periode_magang',
            'siswa.nis_siswa',
            'pengguna.nm_pengguna',
            'semester.nm_semester',
            'semester.tahun_ajaran'
        )
            ->join('komponen_magang', 'komponen_magang.id_komponen_magang', '=', 'nilai_magang.id_komponen_magang')
            ->join('periode_magang', 'periode_magang.id_periode_magang', '=', 'komponen_magang.id_periode_magang')
            ->join('pengambilan_magang', 'pengambilan_magang.id_periode_magang', '=', 'periode_magang.id_periode_magang')
            ->join('siswa', 'siswa.id_siswa', '=', 'pengambilan_magang.id_siswa')
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->join('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
            ->where('pengambilan_magang.id_periode_magang', '=', $id_periode_magang)->get();

        $list_siswa = LibMagangSiswa::fetchDataPengajuanSiswaMagangDetailPeriode($auth_data, $id_periode_magang);

        return Datatables::of($list_siswa)
            ->addColumn('siswa', function ($item) {
                return $item->nis_siswa . ' - ' . $item->nm_pengguna;
            })
            ->addColumn('nm_rekanan_magang', function ($item) {
                return $item->nm_rekanan_magang;
            })
            ->addColumn('semester', function ($item) {
                return $item->nm_semester . ' - ' . $item->tahun_ajaran;
            })
            ->addColumn('nilai_magang_komponen', function ($item) {
                return $item->nm_komponen_magang;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->nis_siswa
                );
                return $data;
            })
            ->make(true);
    }

    public function actionInputNilaiMagang(Request $request, $mode, $id = null)
    {
        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = auth_data();

        $validator = Validator::make($request->all(), [
            'id_periode_magang' => 'required'
        ]);
        $id_periode_magang = $id;
        $periodeMagang = PeriodeMagang::leftJoin('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')->where('periode_magang.id_periode_magang', '=', $id_periode_magang)->first();
        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            // ACTION SAVE
            if ($mode == 'save') {
                $input_array = (array) $input;
                $list_data = KomponenMagang::where('id_periode_magang', '=', $id)->get();
                $list_siswa = LibMagangSiswa::fetchDataPengajuanSiswaMagangDetailPeriode($auth_data, $id);

                $rentangNilai = PeraturanNilai::join('standar_nilai', 'standar_nilai.id_standar_nilai', '=', 'peraturan_nilai.id_standar_nilai')->where('standar_nilai.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->where('peraturan_nilai.is_mata_pelajaran', '=', 0)->get();
                $nilai_akhir_arr = array();
                $nilai_akhir_final = array();
                foreach ($list_siswa as $dataSiswa => $siswa) {
                    foreach ($list_data as $dataKomponen => $data) {
                        $nameInput = 'nilai' . $data->id_komponen_magang . '-' . $siswa->id_siswa;
                        if (isset($input_array[$nameInput])) {
                            $nilaiCount = ($input_array[$nameInput] * ($data->persentase_komponen_magang / 100));
                        } else {
                            $nilaiCount = 0;
                        }
                        $nilai_akhir_final['nilai_angka' . $siswa->id_siswa][$data->id_komponen_magang] = $nilaiCount;
                    }
                    $namePengambilan = 'id_pengambilan_magang_' . $siswa->id_siswa;
                    $idSiswa = substr($namePengambilan, 22);
                    if ($idSiswa == $siswa->id_siswa) {

                        if (!isset($input_array[$namePengambilan])) {
                            $pengajuanMagang = PengajuanSiswaMagang::where('id_periode_magang', '=', $input->id_periode_magang)
                                ->where('id_siswa', '=', $idSiswa)
                                ->first();
                            $input_array[$namePengambilan] = $pengajuanMagang->id_pengambilan_magang;
                        } else {
                            $pengajuanMagang = PengajuanSiswaMagang::where('id_periode_magang', '=', $input->id_periode_magang)
                                ->where('id_siswa', '=', $idSiswa)
                                ->where('id_pengambilan_magang', '=', $input_array[$namePengambilan])
                                ->first();
                        }
                        if ($pengajuanMagang) {
                            //input nilai per-komponen
                            if ($pengajuanMagang->nilai_angka == NULL && isset($input->$nameInput)) {
                                foreach ($nilai_akhir_final['nilai_angka' . $idSiswa] as $key => $value) {
                                    // dd($key);
                                    // if(!isset($input->$nameInput)){
                                    //     $input->$nameInput = NULL;
                                    // }
                                    $id_nilai_magang = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                                    $nameInput = 'nilai' . $key . '-' . $idSiswa;
                                    $nilaiMagang                            = new NilaiMagang;
                                    $nilaiMagang->id_nilai_magang           = $id_nilai_magang;
                                    $nilaiMagang->id_pengambilan_magang     = $input_array[$namePengambilan];
                                    $nilaiMagang->id_komponen_magang        = $key;
                                    $nilaiMagang->besar_nilai_magang        = $input->$nameInput;
                                    $nilaiMagang->created_by                = auth_data()->pengguna->id_pengguna;
                                    $nilaiMagang->save();
                                }
                            } else {
                                foreach ($nilai_akhir_final['nilai_angka' . $idSiswa] as $key => $value) {
                                    // dd(!is_null($input->$nameInput) ? $input->$nameInput);
                                    if (isset($input->$nameInput) && !is_null($input->$nameInput)) {
                                        // dd($input->$nameInput);
                                        $nameInput = 'nilai' . $key . '-' . $idSiswa;
                                        $nilaiMagang                      = NilaiMagang::where('id_pengambilan_magang', '=', $pengajuanMagang->id_pengambilan_magang)->where('id_komponen_magang', '=', $key)->first();
                                        $nilaiMagang->besar_nilai_magang   = $input->$nameInput ? $input->$nameInput : NULL;
                                        $nilaiMagang->updated_by           = auth_data()->pengguna->id_pengguna;
                                        $nilaiMagang->save();
                                    }
                                }
                            }
                            //Input Nilai Magang
                            $inputNilaiMagang                  = PengajuanSiswaMagang::find($input_array[$namePengambilan]);
                            $inputNilaiMagang->nilai_angka       = array_sum($nilai_akhir_final['nilai_angka' . $idSiswa]);
                            $inputNilaiMagang->status_magang   = 1;
                            $inputNilaiMagang->updated_by      = auth_data()->pengguna->id_pengguna;
                            $inputNilaiMagang->save();
                        } else {
                            return [
                                'status' => 202,
                                'message' => 'Save Successfully',
                                'path' => 'magang-siswa/input-nilai-magang/view-komponen/' . $id_periode_magang
                            ];
                        }
                    } else {
                        return [
                            'status' => 202,
                            'message' => 'Save Successfully',
                            'path' => 'magang-siswa/input-nilai-magang/view-komponen/' . $id_periode_magang
                        ];
                    }
                }
                return [
                    'status' => 202,
                    'message' => 'Save Successfully',
                    'path' => 'magang-siswa/input-nilai-magang/view-komponen/' . $id_periode_magang
                ];
            }
        }
    }
}

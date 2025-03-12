<?php

namespace App\Http\Controllers\Guru\Penilaian;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Models\Siswa as Siswa;
use App\Models\Semester as Semester;
use App\Models\Guru as Guru;
use App\Models\KomponenMp as KomponenMp;
use App\Models\PengambilanMp as PengambilanMp;
use App\Models\PeraturanNilai as PeraturanNilai;
use App\Models\NilaiMp as NilaiMp;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Akademik\LibAkademik;
use App\Libraries\SumberDaya\LibGuru;
use App\Models\NilaiMpSubKomponen;
use App\Models\SubKomponenMp;
use Auth;
use DB;
use Session;
use Validator;

class InputNilaiController extends BaseController
{
    public function viewInputNilai(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        // get all data kelas_mp by id_pengguna guru
        $data_kelas = LibGuru::fetchDataKelasGuru($auth_data, $auth_data->pengguna->id_pengguna, $semester_aktif->id_semester);

        /** groupping by tahun_ajaran and nm_semester */
        $grup_semester_kelas = $data_kelas->groupBy('tahun_ajaran')->transform(function ($item, $k) {
            return $item->groupBy('nm_semester');
        });

        return view('guru/penilaian/input-nilai/view-input-nilai', compact('auth_data', 'data_kelas', 'grup_semester_kelas'));
    }

    public function actionViewKelasInputNilai(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $validator = Validator::make($request->all(), [
            'id_kelas_mp' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'penilaian/input-nilai/view-kelas/' . $input->id_kelas_mp
            ];
        }
    }

    public function viewKelasInputNilai(Request $request, $id_kelas_mp)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_kelas = LibGuru::fetchDataKelasMp($auth_data, $id_kelas_mp);

        $komponenData = KomponenMp::select('id_komponen_mp', 'nm_komponen_mp', 'persentase_komponen_mp')
            ->where('id_kelas_mp', '=', $id_kelas_mp)->get();

        $jumlah_komponen = $komponenData->sum('persentase_komponen_mp');
        $jumlah_subkomponen = 0;
        $list_data = [];
        if (!empty($komponenData)) {
            $count = [];
            foreach ($komponenData->pluck('id_komponen_mp') as $x) {
                $count[] = SubKomponenMp::where('id_komponen_mp', $x)->count();
            }
            $jumlah_subkomponen = in_array(0, $count) ? 0 : collect($count)->sum();

            foreach ($komponenData as $komp) {
                $list_data[$komp->nm_komponen_mp] = LibGuru::fetchDataSubKomponenNilai($auth_data, $komp->id_komponen_mp);
            }
        }

        $list_siswa = PengambilanMp::select('siswa.nis_siswa', 'pengguna.nm_pengguna', 'pengambilan_mp.nilai_angka', 'pengambilan_mp.nilai_huruf', 'siswa.id_siswa', 'pengambilan_mp.id_pengambilan_mp', 'pengambilan_mp.id_kelas_mp')
            ->join('siswa', 'siswa.id_siswa', '=', 'pengambilan_mp.id_siswa')
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->where('pengambilan_mp.id_kelas_mp', '=', $id_kelas_mp)->get();

        $pengambilan_mp = PengambilanMp::where('pengambilan_mp.id_kelas_mp', '=', $id_kelas_mp)->first();

        $list_siswa = $list_siswa->map(function ($row) use ($list_data) {
            $list_subkomponen = [];
            foreach ($list_data as $komponen) {
                foreach ($komponen as $subKomponen) {
                    $nilai = NilaiMpSubKomponen::where('id_subkomponen_mp', $subKomponen->id_subkomponen_mp)
                        ->where('id_pengambilan_mp', $row->id_pengambilan_mp)
                        ->first();
                    $list_subkomponen[] = [
                        'id_komponen_mp' => $subKomponen->id_komponen_mp,
                        'id_subkomponen_mp' => $subKomponen->id_subkomponen_mp,
                        'nilai_subkomponen_mp' => !empty($nilai) ? $nilai->besar_nilai_mp : 0
                    ];
                }
            }
            $data = $row;
            $data->nilai_siswa_komponen = $list_subkomponen;
            return $data;
        });

        return view('guru/penilaian/input-nilai/view-kelas-input-nilai', compact('auth_data', 'data_kelas', 'list_data', 'jumlah_komponen', 'jumlah_subkomponen', 'list_siswa', 'pengambilan_mp'));
    }

    public function actionInputNilai(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kelas_mp' => 'required'
        ]);

        if ($validator->fails() && $mode != 'tampil' && $mode != 'kbm') { // kbm == input-nilai
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            // ACTION ADD
            if ($mode == 'tampil') {
                DB::beginTransaction();

                try {
                    $pengambilanMp = PengambilanMp::find($id);
                    $pengambilanMp->is_tampil = 1;
                    $pengambilanMp->updated_by = auth_data()->pengguna->id_pengguna;
                    $pengambilanMp->save();

                    DB::commit();

                    return [
                        'status' => 200, // SUCCESS AND LOAD TABLE
                        'message' => 'Nilai Berhasil Ditampilkan'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();

                    return [
                        'status'     => 300,
                        'message' => 'Aksi gagal'
                    ];
                }
            } elseif ($mode == 'kbm') {
                DB::beginTransaction();

                try {

                    $validator = Validator::make($request->only('id_kelas_mp'), [
                        // dari type hidden
                        'id_kelas_mp' => 'required'
                    ]);

                    if ($validator->fails()) {
                        return [
                            'status' => 300, // FAILED
                            'message' => $validator->errors()->first()
                        ];
                    }

                    $data_validation = $request->except(['_token', 'id_kelas_mp', 'primary_table_length', 'auth_data']);
                    $key = [];
                    foreach ($data_validation as $keydv => $dv) {
                        $key[$keydv] = 'numeric';
                    }

                    $validator = Validator::make($data_validation, $key);

                    if ($validator->fails()) {
                        return [
                            'status' => 300, // FAILED
                            'message' => $validator->errors()->first()
                        ];
                    }

                    $list_data = KomponenMp::where('id_kelas_mp', '=', $input->id_kelas_mp)->get();
                    $list_siswa = PengambilanMp::select('siswa.nis_siswa', 'pengguna.nm_pengguna', 'pengambilan_mp.nilai_angka', 'pengambilan_mp.nilai_huruf', 'siswa.id_siswa', 'pengambilan_mp.id_pengambilan_mp')
                        ->join('siswa', 'siswa.id_siswa', '=', 'pengambilan_mp.id_siswa')
                        ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
                        ->where('pengambilan_mp.id_kelas_mp', '=', $input->id_kelas_mp)->get();

                    $nilai_akhir_final = array();
                    $nilai_per_komponen = [];

                    $keys = collect($data_validation)->keys()->map(function ($m) {
                        $pos = strpos($m, '-');
                        $str = substr($m, $pos + 1);
                        return $str;
                    })->toArray();

                    foreach ($list_siswa as $dataSiswa => $siswa) { //tiap siswa
                        // check if input exist in $siswa
                        if (in_array($siswa->id_siswa, $keys)) {
                            foreach ($list_data as $dataKomponen => $komponen) { // tiap komponen
                                $list_subkomponen = SubKomponenMp::where('id_komponen_mp', $komponen->id_komponen_mp)->get();

                                foreach ($list_subkomponen as $data) { // tiap subkomponen
                                    $nameInput = 'nilai' . $data->id_subkomponen_mp . '-' . $siswa->id_siswa;
                                    if (!isset($input->$nameInput)) {
                                        dd($nameInput, $siswa);
                                    }
                                    $nilai_akhir_final['nilai_angka' . $siswa->id_siswa][$data->id_komponen_mp][$data->id_subkomponen_mp]['raw'] = $input->$nameInput;
                                }

                                $c_nilai_akhir = collect($nilai_akhir_final['nilai_angka' . $siswa->id_siswa][$data->id_komponen_mp]);

                                $nilai_per_komponen[$siswa->id_siswa][$komponen->id_komponen_mp]['raw'] = round($c_nilai_akhir->sum('raw') / $c_nilai_akhir->count(), 2);
                                $nilai_per_komponen[$siswa->id_siswa][$komponen->id_komponen_mp]['persentase'] = $komponen->persentase_komponen_mp;
                            }

                            $namePengambilan = 'id_pengambilan_mp_' . $siswa->id_siswa;
                            $idSiswa = substr($namePengambilan, 18);
                            // if siswa found
                            if ($idSiswa == $siswa->id_siswa) {
                                $pengambilanMp = PengambilanMp::where('id_kelas_mp', '=', $input->id_kelas_mp)
                                    ->where('id_siswa', '=', $idSiswa)
                                    ->first();

                                // if siswa has pengambilanMp
                                if ($pengambilanMp) {
                                    $nilai_angka = 0;
                                    foreach ($nilai_akhir_final['nilai_angka' . $idSiswa] as $komponen => $item) {
                                        // save to nilai_mp_sub_komponen
                                        foreach ($item as $subKomponen => $nilai) {
                                            $is_new_subkomponen = 0;
                                            $nameInput  = 'nilai' . $subKomponen . '-' . $idSiswa;

                                            $nilaiMpSub = NilaiMpSubKomponen::where('id_pengambilan_mp', $siswa->id_pengambilan_mp)
                                                ->where('id_subkomponen_mp', $subKomponen)
                                                ->first();

                                            if (empty($nilaiMpSub)) { // if empty, then create new record
                                                $is_new_subkomponen = 1;
                                                $nilaiMpSub                            = new NilaiMpSubKomponen;
                                                $nilaiMpSub->id_nilai_mp_subkomponen   = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                                            }

                                            $nilaiMpSub->id_pengambilan_mp         = $siswa->id_pengambilan_mp;
                                            $nilaiMpSub->id_subkomponen_mp         = $subKomponen;
                                            if ($input->$nameInput == null) {
                                                $nilaiMpSub->besar_nilai_mp        = 0;
                                            } else {
                                                $nilaiMpSub->besar_nilai_mp        = $nilai['raw'];
                                            }
                                            if ($is_new_subkomponen == 1) {
                                                $nilaiMpSub->created_by            = auth_data()->pengguna->id_pengguna;
                                                $nilaiMpSub->created_at            = $now;
                                            } else {
                                                $nilaiMpSub->updated_by            = auth_data()->pengguna->id_pengguna;
                                                $nilaiMpSub->updated_at            = $now;
                                            }
                                            $nilaiMpSub->save();
                                        }

                                        // save to nilai_mp
                                        $is_new_komponen = 0;
                                        $nilaiMp    = NilaiMp::where('id_pengambilan_mp', '=', $pengambilanMp->id_pengambilan_mp)
                                            ->where('id_komponen_mp', '=', $komponen)
                                            ->first();

                                        $nilaiKomponen      = $nilai_per_komponen[$siswa->id_siswa][$komponen]['raw'];
                                        $nilai_angka        = $nilai_angka + (round($nilaiKomponen * ($nilai_per_komponen[$siswa->id_siswa][$komponen]['persentase'] / 100), 2));

                                        if (empty($nilaiMp)) { // if empty, then create new record
                                            $nilaiMp                = new NilaiMp;
                                            $nilaiMp->id_nilai_mp   = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                                            $is_new_komponen = 1;
                                        }

                                        $nilaiMp->id_pengambilan_mp     = $siswa->id_pengambilan_mp;
                                        $nilaiMp->id_komponen_mp        = $komponen;
                                        $nilaiMp->besar_nilai_mp        = !empty($nilaiKomponen) ? $nilaiKomponen : 0;
                                        if ($is_new_komponen == 1) {
                                            $nilaiMp->created_by        = auth_data()->pengguna->id_pengguna;
                                            $nilaiMp->created_at        = $now;
                                        } else {
                                            $nilaiMp->updated_by        = auth_data()->pengguna->id_pengguna;
                                            $nilaiMp->updated_at        = $now;
                                        }
                                        $nilaiMp->save();
                                    }

                                    // save to pengambilan MP as final result (result for rapor)
                                    $nilai_huruf = PeraturanNilai::join('standar_nilai', 'standar_nilai.id_standar_nilai', '=', 'peraturan_nilai.id_standar_nilai')
                                        ->where('peraturan_nilai.is_mata_pelajaran', '=', '1')
                                        ->where('peraturan_nilai.nilai_min_peraturan_nilai', '<=', round($nilai_angka))
                                        ->where('peraturan_nilai.nilai_max_peraturan_nilai', '>=', round($nilai_angka))
                                        ->first();
                                    if ($nilai_huruf) {
                                        $nilai_huruf = $nilai_huruf['nm_standar_nilai'];
                                    } else {
                                        $nilai_huruf = "-";
                                    }
                                    $nilai_pengambilanMp                        = PengambilanMp::find($siswa->id_pengambilan_mp);
                                    $nilai_pengambilanMp->nilai_angka           = $nilai_angka;
                                    $nilai_pengambilanMp->updated_by            = auth_data()->pengguna->id_pengguna;
                                    $nilai_pengambilanMp->updated_at            = $now;
                                    $nilai_pengambilanMp->nilai_huruf           = $nilai_huruf;
                                    $nilai_pengambilanMp->save();
                                }
                            }
                        }
                    }

                    DB::commit();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'message' => 'Input Nilai Sukses',
                        'path' => 'penilaian/input-nilai/view-kelas/' . $input->id_kelas_mp
                    ];
                } catch (\Exception $e) {
                    DB::rollback();

                    return [
                        'status'     => 300,
                        'message' => 'Penilaian gagal'
                    ];
                }
            }
        }
    }
}

<?php

namespace App\Http\Controllers\Kesiswaan\Siswa;

use Auth;
use Session;
use Validator;
use Carbon\Carbon;

use App\Models\Guru as Guru;
use Illuminate\Http\Request;

use App\Models\HomeVisitView;

use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\Pendidikan\LibSiswa;
use App\Models\HomeVisit as HomeVisit;
use Illuminate\Routing\Controller as BaseController;

class HomeVisitController extends BaseController
{
    public function viewHomeVisit(Request $request, $id = null)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data_kelas = LibKelas::fetchDataKelas($auth_data);
        // dd($data_kelas);

        return view('kesiswaan/siswa/home-visit/view-home-visit', compact('auth_data', 'data_kelas', 'id'));
    }

    public function editHomeVisit(Request $request, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        // mengambil waktu sekarang
        $now = Carbon::now();
        $homeVisit = HomeVisit::select(
            'home_visit.id_home_visit',
            'home_visit.nomor_hp_wali_murid',
            'home_visit.alamat_wali_murid',
            'home_visit.rangkuman_home_visit',
            'home_visit.is_berkas_lengkap',
            'home_visit.id_guru_kesiswaan',
            'home_visit.id_guru_wali_kelas',
            'home_visit.dokumentasi_home_visit',
            'semester.nm_semester',
            'semester.tahun_ajaran',
            'p1.nm_pengguna as nm_siswa',
            'siswa.nis_siswa',
            'siswa.nisn_siswa',
            'g1.id_pengguna',
            'p2.nm_pengguna as nm_wali_kelas',
            'p2.gelar_depan as gelar_depan_wali_kelas',
            'p2.gelar_belakang as gelar_belakang_wali_kelas',
            'g2.id_pengguna',
            'p3.nm_pengguna as nm_guru_kesiswaan',
            'p3.gelar_depan as gelar_depan_kesiswaan',
            'p3.gelar_belakang as gelar_belakang_kesiswaan',
            'home_visit.created_at'
        )
            ->join('semester', 'semester.id_semester', '=', 'home_visit.id_semester')
            ->join('siswa', 'siswa.id_siswa', '=', 'home_visit.id_siswa')
            ->join('pengguna as p1', 'p1.id_pengguna', '=', 'siswa.id_pengguna')
            ->join('guru as g1', 'g1.id_guru', '=', 'home_visit.id_guru_wali_kelas')
            ->join('pengguna as p2', 'p2.id_pengguna', '=', 'g1.id_pengguna')
            ->leftJoin('guru as g2', 'g2.id_guru', '=', 'home_visit.id_guru_kesiswaan')
            ->leftJoin('pengguna as p3', 'p3.id_pengguna', '=', 'g2.id_pengguna')
            ->where('home_visit.id_home_visit', '=', $id)
            ->first();

        $data_guru = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();

        // convert format date
        $tgl_home_visit = strftime("%d %B %Y %H:%M:%S", strtotime($homeVisit->created_at));

        return view('kesiswaan/siswa/home-visit/edit-home-visit', compact('auth_data', 'homeVisit', 'data_guru', 'tgl_home_visit'));
    }

    public function datatablesHomeVisit(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // dd($auth_data->pengguna->id_sekolah);
        $id_kelas = '';
        if (!empty($input->id_kelas)) {
            $id_kelas = $input->id_kelas;
        }

        if ($id == 1) {
            $list_data = HomeVisit::select(
                'home_visit.id_home_visit',
                'home_visit.nomor_hp_wali_murid',
                'home_visit.alamat_wali_murid',
                'home_visit.rangkuman_home_visit',
                'home_visit.is_berkas_lengkap',
                'home_visit.id_guru_kesiswaan',
                'home_visit.id_guru_wali_kelas',
                'semester.nm_semester',
                'semester.tahun_ajaran',
                'p1.nm_pengguna as nm_siswa',
                'siswa.nis_siswa',
                'siswa.nisn_siswa',
                'g1.id_pengguna as id_pengguna_wali_kelas',
                'p2.nm_pengguna as nm_wali_kelas',
                'p2.gelar_depan as gelar_depan_wali_kelas',
                'p2.gelar_belakang as gelar_belakang_wali_kelas',
                'g2.id_pengguna as id_pengguna_kesiswaan',
                'p3.nm_pengguna as nm_guru_kesiswaan',
                'p3.gelar_depan as gelar_depan_kesiswaan',
                'p3.gelar_belakang as gelar_belakang_kesiswaan',
                'home_visit.created_at',
                'p4.nm_pengguna as nm_tendik_kesiswaan',
                'p4.gelar_depan as gelar_depan_tendik_kesiswaan',
                'p4.gelar_belakang as gelar_belakang_tendik_kesiswaan',
                'kelas.nm_kelas',
                'p2.id_sekolah',
                'kelas.id_kelas'
            )
                ->join('semester', 'semester.id_semester', '=', 'home_visit.id_semester')
                ->join('siswa', 'siswa.id_siswa', '=', 'home_visit.id_siswa')
                ->join('pengguna as p1', 'p1.id_pengguna', '=', 'siswa.id_pengguna')
                ->join('guru as g1', 'g1.id_guru', '=', 'home_visit.id_guru_wali_kelas')
                ->join('pengguna as p2', 'p2.id_pengguna', '=', 'g1.id_pengguna')
                ->join('kelas', 'kelas.id_kelas', '=', 'home_visit.id_kelas')
                ->leftJoin('guru as g2', 'g2.id_guru', '=', 'home_visit.id_guru_kesiswaan')
                ->leftJoin('pengguna as p3', 'p3.id_pengguna', '=', 'g2.id_pengguna')
                ->leftJoin('pengguna as p4', 'p4.id_pengguna', '=', 'home_visit.updated_by')
                ->where('p2.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                ->where('home_visit.is_berkas_lengkap', '=', $id)
                ->when(!empty($id_kelas), function ($q) use ($id_kelas) {
                    $q->where('kelas.id_kelas', $id_kelas);
                })
                ->orderBy('home_visit.updated_at', 'desc');
        } else {
            $list_data = HomeVisit::select(
                'home_visit.id_home_visit',
                'home_visit.nomor_hp_wali_murid',
                'home_visit.alamat_wali_murid',
                'home_visit.rangkuman_home_visit',
                'home_visit.is_berkas_lengkap',
                'home_visit.id_guru_kesiswaan',
                'home_visit.id_guru_wali_kelas',
                'semester.nm_semester',
                'semester.tahun_ajaran',
                'p1.nm_pengguna as nm_siswa',
                'siswa.nis_siswa',
                'siswa.nisn_siswa',
                'g1.id_pengguna as id_pengguna_wali_kelas',
                'p2.nm_pengguna as nm_wali_kelas',
                'p2.gelar_depan as gelar_depan_wali_kelas',
                'p2.gelar_belakang as gelar_belakang_wali_kelas',
                'g2.id_pengguna as id_pengguna_kesiswaan',
                'p3.nm_pengguna as nm_guru_kesiswaan',
                'p3.gelar_depan as gelar_depan_kesiswaan',
                'p3.gelar_belakang as gelar_belakang_kesiswaan',
                'home_visit.created_at',
                'p4.nm_pengguna as nm_tendik_kesiswaan',
                'p4.gelar_depan as gelar_depan_tendik_kesiswaan',
                'p4.gelar_belakang as gelar_belakang_tendik_kesiswaan',
                'kelas.nm_kelas',
                'p2.id_sekolah',
                'kelas.id_kelas'
            )
                ->join('semester', 'semester.id_semester', '=', 'home_visit.id_semester')
                ->join('siswa', 'siswa.id_siswa', '=', 'home_visit.id_siswa')
                ->join('pengguna as p1', 'p1.id_pengguna', '=', 'siswa.id_pengguna')
                ->join('guru as g1', 'g1.id_guru', '=', 'home_visit.id_guru_wali_kelas')
                ->join('pengguna as p2', 'p2.id_pengguna', '=', 'g1.id_pengguna')
                ->join('kelas', 'kelas.id_kelas', '=', 'home_visit.id_kelas')
                ->leftJoin('guru as g2', 'g2.id_guru', '=', 'home_visit.id_guru_kesiswaan')
                ->leftJoin('pengguna as p3', 'p3.id_pengguna', '=', 'g2.id_pengguna')
                ->leftJoin('pengguna as p4', 'p4.id_pengguna', '=', 'home_visit.updated_by')
                ->where('p2.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
                ->where('home_visit.is_berkas_lengkap', '=', $id)
                ->when(!empty($id_kelas), function ($q) use ($id_kelas) {
                    $q->where('kelas.id_kelas', $id_kelas);
                })
                ->orderBy('home_visit.created_at', 'desc');
        }

        return Datatables::of($list_data)
            ->addColumn('check', function ($item) {
                $data = array(
                    'id' => $item->id_home_visit
                );
                return $data;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_home_visit
                );
                return $data;
            })
            ->addColumn('semester', function ($item) {
                return $item->nm_semester . ' (' . $item->tahun_ajaran . ')';
            })
            ->addColumn('nm_wali_kelas', function ($item) {
                if (!empty($item->gelar_depan_wali_kelas) && !empty($item->gelar_belakang_wali_kelas)) {
                    return $item->gelar_depan_wali_kelas . " " . $item->nm_wali_kelas . ", " . $item->gelar_belakang_wali_kelas;
                } elseif (!empty($item->gelar_depan_wali_kelas)) {
                    return $item->gelar_depan_wali_kelas . " " . $item->nm_wali_kelas;
                } elseif (!empty($item->gelar_belakang_wali_kelas)) {
                    return $item->nm_wali_kelas . ", " . $item->gelar_belakang_wali_kelas;
                } else {
                    return $item->nm_wali_kelas;
                }
            })
            ->addColumn('nm_guru_kesiswaan', function ($item) {
                if (!empty($item->nm_guru_kesiswaan)) {
                    if (!empty($item->gelar_depan_kesiswaan) && !empty($item->gelar_belakang_kesiswaan)) {
                        return $item->gelar_depan_kesiswaan . " " . $item->nm_guru_kesiswaan . ", " . $item->gelar_belakang_kesiswaan;
                    } elseif (!empty($item->gelar_depan_kesiswaan)) {
                        return $item->gelar_depan_kesiswaan . " " . $item->nm_guru_kesiswaan;
                    } elseif (!empty($item->gelar_belakang_kesiswaan)) {
                        return $item->nm_guru_kesiswaan . ", " . $item->gelar_belakang_kesiswaan;
                    } else {
                        return $item->nm_guru_kesiswaan;
                    }
                } elseif (!empty($item->nm_tendik_kesiswaan)) {
                    if (!empty($item->gelar_depan_tendik_kesiswaan) && !empty($item->gelar_belakang_tendik_kesiswaan)) {
                        return $item->gelar_depan_tendik_kesiswaan . " " . $item->nm_tendik_kesiswaan . ", " . $item->gelar_belakang_tendik_kesiswaan;
                    } elseif (!empty($item->gelar_depan_tendik_kesiswaan)) {
                        return $item->gelar_depan_tendik_kesiswaan . " " . $item->nm_tendik_kesiswaan;
                    } elseif (!empty($item->gelar_belakang_tendik_kesiswaan)) {
                        return $item->nm_tendik_kesiswaan . ", " . $item->gelar_belakang_tendik_kesiswaan;
                    } else {
                        return $item->nm_tendik_kesiswaan;
                    }
                } else {
                    return "-";
                }
            })
            ->addColumn('tgl_home_visit', function ($item) {
                return strftime("%A, %d %B %Y", strtotime($item->created_at));
            })
            ->make(true);
    }

    public function actionHomeVisit(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now();

        $validator = Validator::make($request->all(), []);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            if ($mode == 'edit') {
                if ($input->auth_data->pengguna->status_join_table == 2) {
                    // get id_guru
                    $guru = Guru::select('id_guru')
                        ->where('id_pengguna', '=', $input->auth_data->pengguna->id_pengguna)
                        ->first();

                    $id_guru_kesiswaan = $guru->id_guru;
                } else {
                    $id_guru_kesiswaan = null;
                }

                $homeVisit = HomeVisit::find($id);
                $homeVisit->is_berkas_lengkap = $input->is_berkas_lengkap;
                $homeVisit->id_guru_kesiswaan = $id_guru_kesiswaan;
                $homeVisit->updated_at = $now;
                $homeVisit->updated_by = $input->auth_data->pengguna->id_pengguna;
                $homeVisit->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-kesiswaan/home-visit',
                    'message' => 'Save Data Home Visit Successfully'
                ];
            } else if ($mode == 'checkapprove') {
                $selectedIds = $input->selected_ids;
                DB::beginTransaction();
                try {
                    foreach ($selectedIds as $id_visit) {
                        $homeVisit = HomeVisit::where('id_home_visit', $id_visit);
                        if ($input->auth_data->pengguna->status_join_table == 2) {
                            // get id_guru
                            $guru = Guru::select('id_guru')
                                ->where('id_pengguna', '=', $input->auth_data->pengguna->id_pengguna)
                                ->first();

                            $id_guru_kesiswaan = $guru->id_guru;
                        } else {
                            $id_guru_kesiswaan = null;
                        }
                        if (!$homeVisit) {
                            DB::rollback();
                            return [
                                'status' => 404, // NOT fOUND HOMEVISIT
                                'path' => 'data-kesiswaan/home-visit',
                                'message' => 'Home Visit Not Found'
                            ];
                        }
                        $homeVisit->update([
                            'is_berkas_lengkap' => true,
                            'id_guru_kesiswaan' => $id_guru_kesiswaan,
                            'updated_at' => $now,
                            'updated_by' => $input->auth_data->pengguna->id_pengguna,
                        ]);
                    }
                    DB::commit();
                    return [
                        'status' => 202, // ACCEPTED
                        'path' => 'data-kesiswaan/home-visit',
                        'message' => 'Home Visit Approve'
                    ];
                } catch (\Exception $e) {
                    DB::rollback();
                    return [
                        'status' => 203, // GAGAL
                        'path' => 'data-kesiswaan/home-visit',
                        'message' => 'Home Visit Gagal Dilakukan'
                    ];
                }
            }
        }
    }
}

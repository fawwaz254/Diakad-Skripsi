<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Models\Guru;
use App\Models\Pengguna;
use App\Libraries\SumberDaya\LibGuru;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\KegiatanSiswa;
use App\Models\PrestasiSiswa;
use App\Models\Siswa;
use App\Models\TingkatPrestasiSiswa;
use Carbon\Carbon;


use Auth;
use DB;
use Session;
use Validator;

class WaliKelasSKPIController extends Controller
{
    public function viewListSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);

        return view('guru/wali-kelas/skpi/view-list-siswa', compact('auth_data', 'wali_kelas'));
    }

    public function datatablesListSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $guru = Guru::where('id_pengguna', '=', $auth_data->pengguna->id_pengguna)->first();
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $wali_kelas = LibGuru::fetchDataWaliKelasBySemester($auth_data, $guru->id_guru, $semester_aktif->id_semester);


        $list_siswa = Siswa::where('id_kelas', $wali_kelas->id_kelas)->with('pengguna', 'kegiatan_siswa', 'prestasi_siswa', 'informasi_tambahan');

        return Datatables::of($list_siswa)
            ->addColumn('kegiatan_siswa', function ($item) {
                $data = array(
                    'id' => $item->id_siswa,
                    'count' => $item->kegiatan_siswa->count()
                );
                return $data;
            })
            ->addColumn('prestasi_siswa', function ($item) {
                $data = array(
                    'id' => $item->id_siswa,
                    'count' => $item->prestasi_siswa->count()
                );
                return $data;
            })->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_siswa,
                );
                return $data;
            })
            // ->addColumn('informasi_tambahan', function ($item) {
            //     $data = array(
            //         'id' => $item->id_siswa,
            //         'count' => $item->informasi_tambahan->count()
            //     );
            //     return $data;
            // })
            ->make(true);
    }

    public function viewKegiatanSiswa(Request $request, $id_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/wali-kelas/skpi/kegiatan-siswa/view-kegiatan-siswa', compact('auth_data', 'id_siswa'));
    }

    public function datatablesKegiatanSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = KegiatanSiswa::Select(
            'kegiatan_siswa.id_kegiatan_siswa',
            'kegiatan_siswa.nm_kegiatan_siswa',
            'kegiatan_siswa.status',
            'kegiatan_siswa.tgl_kegiatan_siswa',
            'kegiatan_siswa.lokasi_kegiatan_siswa',
            'kegiatan_siswa.penyelenggara_kegiatan_siswa',
            'kegiatan_siswa.nm_kegiatan_scan_sertif',
            'kegiatan_siswa.keterangan'
        )
            ->where('id_siswa', $input->id_siswa)
            ->get();

        return Datatables::of($list_data)
            ->addColumn('keterangan_status', function ($item) {
                if ($item->status == 0) {
                    $status = 'Belum Diapprove';
                    $color = 'pink';
                } elseif ($item->status == 1) {
                    $status = 'Sudah Diapprove';
                    $color = 'teal';
                } elseif ($item->status == 10) {
                    $status = 'Ditolak';
                    $color = 'red';
                }
                $data = array(
                    'status' => $status,
                    'color'  => $color
                );
                return $data;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_kegiatan_siswa,
                    'link_sertifikat' => $item->nm_kegiatan_scan_sertif,
                    'status' => $item->status
                );
                return $data;
            })
            ->make(true);
    }

    public function addKegiatanSiswa(Request $request, $id_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/wali-kelas/skpi/kegiatan-siswa/add-kegiatan-siswa', compact('auth_data', 'id_siswa'));
    }

    public function editKegiatanSiswa(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kegiatan_siswa = KegiatanSiswa::where('id_kegiatan_siswa', $id)->first();
        // dd($kegiatan_siswa);
        return view('guru/wali-kelas/skpi/kegiatan-siswa/edit-kegiatan-siswa', compact('auth_data', 'kegiatan_siswa'));
    }

    public function actionDataKegiatanSiswa(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now();

        // dd($id_kelas);
        $validator = Validator::make($request->all(), [
            'nm_kegiatan_siswa' => 'required',
            'lokasi_kegiatan_siswa' => 'required',
            'penyelenggara_kegiatan_siswa' => 'required',
            // 'id_tingkat_prestasi_siswa' => 'required',
            'tgl_kegiatan_siswa' => 'required',
            'link_sertifikat' => 'required|url'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {

            if ($mode == 'add') {
                $id_kelas = Siswa::where('id_siswa', $input->id_siswa)->pluck('id_kelas')->first();
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $kegiatan = new KegiatanSiswa;
                $kegiatan->id_kegiatan_siswa = $id;
                $kegiatan->id_siswa = $input->id_siswa;
                $kegiatan->id_kelas = $id_kelas;
                $kegiatan->id_semester = LibDataAkademik::fetchDataSemesterAktif($auth_data)->id_semester;
                $kegiatan->nm_kegiatan_siswa = $input->nm_kegiatan_siswa;
                $kegiatan->lokasi_kegiatan_siswa = $input->lokasi_kegiatan_siswa;
                $kegiatan->penyelenggara_kegiatan_siswa = $input->penyelenggara_kegiatan_siswa;
                $kegiatan->id_tingkat_prestasi_siswa = 0;
                $kegiatan->tgl_kegiatan_siswa = date("Y-m-d", strtotime($input->tgl_kegiatan_siswa));
                $kegiatan->nm_kegiatan_scan_sertif = $input->link_sertifikat;
                $kegiatan->created_by = $input->auth_data->pengguna->id_pengguna;
                $kegiatan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'wali-kelas/input-skpi-siswa/kegiatan-siswa/' . $input->id_siswa,
                    'message' => 'Save Kegiatan Successfully'
                ];
            } elseif ($mode == 'edit') {
                $id_kelas = Siswa::where('id_siswa', $input->id_siswa)->pluck('id_kelas')->first();
                $kegiatan = KegiatanSiswa::find($id);
                $kegiatan->id_siswa = $input->id_siswa;
                $kegiatan->id_kelas = $id_kelas;
                $kegiatan->id_semester = LibDataAkademik::fetchDataSemesterAktif($auth_data)->id_semester;
                $kegiatan->nm_kegiatan_siswa = $input->nm_kegiatan_siswa;
                $kegiatan->lokasi_kegiatan_siswa = $input->lokasi_kegiatan_siswa;
                $kegiatan->penyelenggara_kegiatan_siswa = $input->penyelenggara_kegiatan_siswa;
                $kegiatan->id_tingkat_prestasi_siswa = 0;
                $kegiatan->tgl_kegiatan_siswa = date("Y-m-d", strtotime($input->tgl_kegiatan_siswa));
                $kegiatan->nm_kegiatan_scan_sertif = $input->link_sertifikat;
                $kegiatan->updated_at = $now;
                $kegiatan->updated_by = $input->auth_data->pengguna->id_pengguna;
                $kegiatan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'wali-kelas/input-skpi-siswa/kegiatan-siswa/' . $input->id_siswa,
                    'message' => 'Edit Kegiatan Successfully'
                ];
            } elseif ($mode == 'delete') {

                $kegiatan = KegiatanSiswa::find($id);
                $kegiatan->deleted_by  = $input->auth_data->pengguna->id_pengguna;
                $kegiatan->deleted_at  = $now;
                $kegiatan->save();

                $kegiatan->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Kegiatan Successfully'
                ];
            }
        }
    }
    public function datatablesPrestasiSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = PrestasiSiswa::select(
            'prestasi_siswa.nm_prestasi_siswa',
            'prestasi_siswa.keterangan',
            'tingkat_prestasi_siswa.nm_tingkat_prestasi_siswa',
            // 'prestasi_siswa.jenis_prestasi_siswa',
            'prestasi_siswa.peringkat_prestasi_siswa',
            'prestasi_siswa.link_sertif_prestasi_siswa',
            'prestasi_siswa.status',
            'prestasi_siswa.jenis_lomba_siswa',
            'p1.nm_pengguna as nm_siswa',
            'siswa.nisn_siswa',
            'siswa.nis_siswa',
            'semester.nm_semester',
            'semester.tahun_ajaran',
            'kelas.nm_kelas',
            'prestasi_siswa.lokasi_prestasi_siswa',
            'prestasi_siswa.penyelenggara_prestasi_siswa',
            'prestasi_siswa.tgl_prestasi_siswa',
            // 'ekskul.nm_ekskul',
            'prestasi_siswa.id_prestasi_siswa',
            'prestasi_siswa.id_guru_pendamping',
            'p2.nm_pengguna as nm_guru_pendamping',
            'p2.gelar_depan',
            'p2.gelar_belakang'
        )
            ->join('tingkat_prestasi_siswa', 'tingkat_prestasi_siswa.id_tingkat_prestasi_siswa', '=', 'prestasi_siswa.id_tingkat_prestasi_siswa')
            ->join('siswa', 'siswa.id_siswa', '=', 'prestasi_siswa.id_siswa')
            ->join('pengguna as p1', 'p1.id_pengguna', '=', 'siswa.id_pengguna')
            ->join('semester', 'semester.id_semester', '=', 'prestasi_siswa.id_semester')
            ->join('kelas', 'kelas.id_kelas', '=', 'prestasi_siswa.id_kelas')
            // ->leftJoin('ekskul', 'ekskul.id_ekskul', '=', 'prestasi_siswa.id_ekskul')
            ->leftJoin('guru', 'guru.id_guru', '=', 'prestasi_siswa.id_guru_pendamping')
            ->leftJoin('pengguna as p2', 'p2.id_pengguna', '=', 'guru.id_pengguna')
            ->orderBy('prestasi_siswa.created_at', 'desc')
            ->orderBy('semester.thn_akademik_semester', 'desc')
            ->orderBy('semester.nm_semester', 'desc')
            // ->where('p1.id_pengguna', '=', $auth_data->pengguna->id_pengguna)
            // ->where('tingkat_prestasi_siswa.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            // ->where('p1.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->where('prestasi_siswa.id_siswa', $input->id_siswa)
            ->get();

        return Datatables::of($list_data)
            ->addColumn('semester', function ($item) {
                return $item->nm_semester . ' (' . $item->tahun_ajaran . ')';
            })
            ->addColumn('keterangan_status', function ($item) {
                if ($item->status == 0) {
                    $status = 'Belum Diapprove';
                    $color = 'pink';
                } elseif ($item->status == 1) {
                    $status = 'Sudah Diapprove';
                    $color = 'teal';
                } elseif ($item->status == 10) {
                    $status = 'Ditolak';
                    $color = 'red';
                }
                $data = array(
                    'status' => $status,
                    'color'  => $color
                );
                return $data;
            })
            ->addColumn('peringkat_prestasi_siswa', function ($item) {
                if ($item->peringkat_prestasi_siswa == 1) {
                    return "Peringkat 1";
                } elseif ($item->peringkat_prestasi_siswa == 2) {
                    return "Peringkat 2";
                } elseif ($item->peringkat_prestasi_siswa == 3) {
                    return "Peringkat 3";
                } elseif ($item->peringkat_prestasi_siswa == 4) {
                    return "Juara Harapan 1";
                } elseif ($item->peringkat_prestasi_siswa == 5) {
                    return "Juara Harapan 2";
                } elseif ($item->peringkat_prestasi_siswa == 6) {
                    return "Juara Harapan 3";
                } elseif ($item->peringkat_prestasi_siswa == 7) {
                    return "Finalis";
                }
            })
            ->addColumn('tgl_prestasi_siswa', function ($item) {
                return strftime("%d %B %Y", strtotime($item->tgl_prestasi_siswa));
            })
            ->addColumn('nm_guru_pendamping', function ($item) {
                if (!empty($item->gelar_depan) && !empty($item->gelar_belakang)) {
                    return $item->gelar_depan . " " . $item->nm_guru_pendamping . ", " . $item->gelar_belakang;
                } elseif (!empty($item->gelar_depan)) {
                    return $item->gelar_depan . " " . $item->nm_guru_pendamping;
                } elseif (!empty($item->gelar_belakang)) {
                    return $item->nm_guru_pendamping . ", " . $item->gelar_belakang;
                } else {
                    return $item->nm_guru_pendamping;
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_prestasi_siswa,
                    'link_sertifikat' => $item->link_sertif_prestasi_siswa,
                    'status' => $item->status
                );
                return $data;
            })
            ->make(true);
    }
    public function addPrestasiSiswa(Request $request, $id_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $tingkat = TingkatPrestasiSiswa::where('id_sekolah', $auth_data->pengguna->id_sekolah)->get();
        // $jenis_prestasi = [[1,'Sains'],[2,'Seni'],[3,'Olahraga'],[4,'Lain-lain']];
        // $ekskul = Ekskul::where('id_sekolah',$auth_data->pengguna->id_sekolah)->get();
        $guru = Guru::select(
            'id_guru',
            'p2.nm_pengguna as nm_guru_pendamping',
            'p2.gelar_depan',
            'p2.gelar_belakang'
        )
            ->Join('pengguna as p2', 'p2.id_pengguna', '=', 'guru.id_pengguna')
            ->get();

        return view('guru/wali-kelas/skpi/prestasi-siswa/add-prestasi-siswa', compact('auth_data', 'id_siswa', 'tingkat', 'guru'));
    }
    public function editPrestasiSiswa(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $tingkat = TingkatPrestasiSiswa::where('id_sekolah', $auth_data->pengguna->id_sekolah)->get();
        // $jenis_prestasi = [[1,'Sains'],[2,'Seni'],[3,'Olahraga'],[4,'Lain-lain']];
        // $ekskul = Ekskul::where('id_sekolah', $auth_data->pengguna->id_sekolah)->get();
        $guru = Guru::select(
            'id_guru',
            'p2.nm_pengguna as nm_guru_pendamping',
            'p2.gelar_depan',
            'p2.gelar_belakang'
        )
            ->Join('pengguna as p2', 'p2.id_pengguna', '=', 'guru.id_pengguna')
            ->get();

        $prestasi = PrestasiSiswa::findOrFail($id);
        return view('guru/wali-kelas/skpi/prestasi-siswa/edit-prestasi-siswa', compact('auth_data', 'tingkat', 'guru', 'prestasi'));
    }
    public function actionDataPrestasiSiswa(Request $request, $mode, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now();


        $validator = Validator::make($request->all(), [
            'nm_prestasi_siswa' => 'required',
            'peringkat_prestasi_siswa' => 'required',
            'lokasi_prestasi_siswa' => 'required',
            'penyelenggara_prestasi_siswa' => 'required',
            'jenis_lomba_siswa' => 'required',
            'id_tingkat_prestasi_siswa' => 'required',
            'tgl_prestasi_siswa' => 'required',
            'link_sertifikat' => 'required|url'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {

            if ($mode == 'add') {
                $id_kelas = Siswa::where('id_siswa', $input->id_siswa)->pluck('id_kelas')->first();
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $prestasi = new PrestasiSiswa;
                $prestasi->id_prestasi_siswa = $id;
                $prestasi->id_siswa = $input->id_siswa;
                $prestasi->id_kelas = $id_kelas;
                $prestasi->id_semester = LibDataAkademik::fetchDataSemesterAktif($auth_data)->id_semester;
                $prestasi->id_tingkat_prestasi_siswa = $input->id_tingkat_prestasi_siswa;
                // $prestasi->jenis_prestasi_siswa = $input->jenis_prestasi_siswa;
                $prestasi->jenis_lomba_siswa = $input->jenis_lomba_siswa;
                $prestasi->nm_prestasi_siswa = $input->nm_prestasi_siswa;
                $prestasi->lokasi_prestasi_siswa = $input->lokasi_prestasi_siswa;
                $prestasi->penyelenggara_prestasi_siswa = $input->penyelenggara_prestasi_siswa;
                $prestasi->peringkat_prestasi_siswa = $input->peringkat_prestasi_siswa;
                $prestasi->tgl_prestasi_siswa = date("Y-m-d", strtotime($input->tgl_prestasi_siswa));
                $prestasi->created_by = $input->auth_data->pengguna->id_pengguna;

                if (!empty($input->id_guru_pendamping)) {
                    $prestasi->id_guru_pendamping = $input->id_guru_pendamping;
                }

                if (!empty($input->id_ekskul)) {
                    $prestasi->id_ekskul = $input->id_ekskul;
                }

                $prestasi->link_sertif_prestasi_siswa = $input->link_sertifikat;

                $prestasi->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'wali-kelas/input-skpi-siswa/prestasi-siswa/' . $input->id_siswa,
                    'message' => 'Add Prestasi Successfully'
                ];
            } elseif ($mode == 'edit') {
                $prestasi = PrestasiSiswa::findOrFail($id);
                $id_kelas = Siswa::where('id_siswa', $input->id_siswa)->pluck('id_kelas')->first();
                $prestasi->id_siswa = $input->id_siswa;
                $prestasi->id_kelas = $id_kelas;
                $prestasi->id_semester = LibDataAkademik::fetchDataSemesterAktif($auth_data)->id_semester;
                $prestasi->id_tingkat_prestasi_siswa = $input->id_tingkat_prestasi_siswa;
                //    $prestasi->jenis_prestasi_siswa = $input->jenis_prestasi_siswa;
                $prestasi->jenis_lomba_siswa = $input->jenis_lomba_siswa;
                $prestasi->nm_prestasi_siswa = $input->nm_prestasi_siswa;
                $prestasi->lokasi_prestasi_siswa = $input->lokasi_prestasi_siswa;
                $prestasi->penyelenggara_prestasi_siswa = $input->penyelenggara_prestasi_siswa;
                $prestasi->peringkat_prestasi_siswa = $input->peringkat_prestasi_siswa;
                $prestasi->tgl_prestasi_siswa = date("Y-m-d", strtotime($input->tgl_prestasi_siswa));
                $prestasi->updated_at = $now;
                $prestasi->updated_by = $input->auth_data->pengguna->id_pengguna;

                if (!empty($input->id_guru_pendamping)) {
                    $prestasi->id_guru_pendamping = $input->id_guru_pendamping;
                }

                if (!empty($input->id_ekskul)) {
                    $prestasi->id_ekskul = $input->id_ekskul;
                }

                $prestasi->link_sertif_prestasi_siswa = $input->link_sertifikat;

                $prestasi->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'wali-kelas/input-skpi-siswa/prestasi-siswa/' . $input->id_siswa,
                    'message' => 'Edit Prestasi Successfully'
                ];
            } elseif ($mode == 'delete') {
                $prestasi = PrestasiSiswa::findOrFail($id);
                $prestasi->deleted_by  = $input->auth_data->pengguna->id_pengguna;
                $prestasi->deleted_at  = $now;
                $prestasi->save();

                $prestasi->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Prestasi Successfully'
                ];
            }
        }
    }
    public function viewPrestasiSiswa(Request $request, $id_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('guru/wali-kelas/skpi/prestasi-siswa/view-prestasi-siswa', compact('auth_data', 'id_siswa'));
    }
}

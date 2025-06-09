<?php

namespace App\Http\Controllers\Humas\Asrama;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use App\Models\Siswa;
use App\Models\SiswaAsrama;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;

class SiswaAsramaController extends Controller
{
    public function  viewSiswaAsrama(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('humas/asrama/data-siswa/view-data-siswa', compact('auth_data'));
    }

    public function datatablesSiswaAsrama(Request $request, $jenis)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        if ($jenis == 'asrama') {
            $list_siswa = Siswa::whereHas('siswaAsrama')->with('siswaAsrama.ruangan', 'kelas', 'pengguna')->orderBy('created_at', 'DESC');;
        } else {
            $list_siswa = Siswa::doesntHave('siswaAsrama')->with('siswaAsrama', 'kelas', 'pengguna')->orderBy('created_at', 'DESC');;
        }

        return Datatables::of($list_siswa)
            ->addColumn('ruangan', function ($item) {
                if (isset($item->siswaAsrama)) {
                    return $item->siswaAsrama->ruangan->nm_ruangan;
                } else {
                    return '';
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id_siswa' => $item->id_siswa,
                );
                return $data;
            })

            ->make(true);
    }
    public function actionAddSiswaAsrama(Request $request)
    {
        $input = (object) $request->input();
        // dd($input);
        if ($siswa_asrama = SiswaAsrama::where('id_siswa', $input->id_siswa)->first()) { } else {
            $now = Carbon::now();
            $siswa_asrama = new SiswaAsrama;
            $siswa_asrama->id_siswa_asrama = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
            $siswa_asrama->id_siswa = $input->id_siswa;
            $siswa_asrama->save();

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                // 'path' => 'e-learning-soal/paket-soal',
                'message' => 'Berhasil Menambah paket Soal'
            ];
        }
    }

    public function actionDeleteSiswaAsrama(Request $request)
    {
        $input = (object) $request->input();
        if ($siswa_asrama = SiswaAsrama::where('id_siswa', $input->id_siswa)->first()) {
            $siswa_asrama->deleted_by = auth_data()->pengguna->id_pengguna;
            $siswa_asrama->save();
            $siswa_asrama->delete();
            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                // 'path' => 'e-learning-soal/paket-soal',
                'message' => 'Berhasil Menghapus paket Soal'
            ];
        }
    }

    public function viewAddRuangan(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        // $list_siswa = Siswa::whereHas('siswaAsrama')->with('siswaAsrama.ruangan', 'kelas', 'pengguna');
        $list_ruangan = Ruangan::get();
        return view('humas/asrama/data-siswa/view-add-ruangan-siswa', compact('list_ruangan'));
    }

    public function datatablesRuanganSiswa(Request $request, $type)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        if ($type == 'belum') {
            $list_siswa = Siswa::whereHas('siswaAsrama')->doesntHave('siswaAsrama.ruangan')->with('siswaAsrama', 'kelas', 'pengguna')->orderBy('created_at', 'DESC');
        } else {
            $list_siswa = Siswa::whereHas('siswaAsrama.ruangan')->with('siswaAsrama.ruangan', 'kelas', 'pengguna')->orderBy('created_at', 'DESC');;
        }

        return Datatables::of($list_siswa)
            ->addColumn('checkbox', function ($item) {
                $data = array(
                    'id_siswa' => $item->id_siswa
                );
                return $data;
            })
            ->addColumn('ruangan', function ($item) {
                if (isset($item->siswaAsrama)) {
                    return $item->siswaAsrama->ruangan->nm_ruangan;
                } else {
                    return '';
                }
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id_siswa' => $item->id_siswa,
                );
                return $data;
            })

            ->make(true);
    }

    public function setRuanganSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        if (empty($input->id_ruangan) || empty($input->id_siswa)) {
            return [
                'status' => 300, // SUCCESS AND LOAD TABLE
                'message' => 'Pilih Terlebih dahulu'
            ];
        }

        $list_siswa = SiswaAsrama::whereIn('id_siswa', $input->id_siswa)->get();
        foreach ($list_siswa as $siswa) {
            $siswa->id_ruangan = $input->id_ruangan;
            $siswa->save();
        }
        return [
            'status' => 200, // SUCCESS AND LOAD TABLE
            'message' => 'Add Ruangan Siswa Successfully'
        ];
    }

    public function editRuanganSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        if (empty($input->id_ruangan) || empty($input->id_siswa)) {
            return [
                'status' => 300, // SUCCESS AND LOAD TABLE
                'message' => 'Pilih Terlebih dahulu'
            ];
        }

        $list_siswa = SiswaAsrama::whereIn('id_siswa', $input->id_siswa)->get();
        foreach ($list_siswa as $siswa) {
            $siswa->id_ruangan = $input->id_ruangan;
            $siswa->save();
        }
        return [
            'status' => 200, // SUCCESS AND LOAD TABLE
            'message' => 'Edit Ruangan Siswa Successfully'
        ];
    }

    public function deleteRuanganSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        if (empty($input->id_siswa)) {
            return [
                'status' => 300, // SUCCESS AND LOAD TABLE
                'message' => 'Pilih Terlebih dahulu'
            ];
        }
        $list_siswa = SiswaAsrama::whereIn('id_siswa', $input->id_siswa)->get();
        foreach ($list_siswa as $siswa) {
            $siswa->id_ruangan = null;
            $siswa->save();
        }
        return [
            'status' => 200, // SUCCESS AND LOAD TABLE
            'message' => 'Delete Ruangan Siswa Successfully'
        ];
    }
}

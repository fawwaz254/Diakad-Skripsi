<?php

namespace App\Http\Controllers\RaporBukuInduk\BukuInduk;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Validator;


class CetakByKelasBIController extends Controller
{
    public function viewCetakByKelas(Request $request, $id_kelas = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $kelas = Kelas::get();

        return view('rapor-buku-induk/buku-induk/cetak-by-kelas/view-cetak-by-kelas', compact('auth_data', 'id_kelas', 'kelas'));
    }

    public function actionViewCetakByKelas(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'buku-induk/cetak-by-kelas/' . $input->id_kelas
            ];
        }
    }

    public function datatablesCetakByKelas(Request $request, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = Siswa::select('siswa.id_siswa', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'pengguna.nm_pengguna', 'kelas.id_kelas', 'kelas.nm_kelas', 'kelas.tingkat', 'status_pengguna.nm_status_pengguna', 'jalur.nm_jalur')
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            ->join('status_pengguna', 'pengguna.id_status_pengguna', '=', 'status_pengguna.id_status_pengguna')
            ->join('jalur_siswa', function ($join) {
                $join->on('jalur_siswa.id_siswa', '=', 'siswa.id_siswa')
                    ->where('jalur_siswa.is_jalur_aktif', '=', 1);
            })
            ->join('jalur', 'jalur_siswa.id_jalur', '=', 'jalur.id_jalur')
            ->where(function ($query) use ($id_kelas) {
                $query->where('kelas.id_kelas', $id_kelas);
            })
            ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->get();

        return Datatables::of($siswa)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->nis_siswa,
                );
                return $data;
            })
            ->make(true);
    }
}

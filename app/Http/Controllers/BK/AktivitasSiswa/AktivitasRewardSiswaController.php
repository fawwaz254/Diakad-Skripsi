<?php

namespace App\Http\Controllers\BK\AktivitasSiswa;

use App\Http\Controllers\Controller;
use App\Models\AktivitasRewardSiswa;
use App\Models\JenisAktivitasReward;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class AktivitasRewardSiswaController extends Controller
{
    public function viewAktivitasRewardSiswa(Request $request)
    {
        $input = (object) $request->input();
        $list_data = AktivitasRewardSiswa::with('jenisAktivitasReward')->where('is_aktif', '=', 1);

        return view('bk/aktivitas-siswa/view-aktivitas-reward-siswa', compact('list_data'));
    }

    public function datatablesAktivitasRewardSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        if ($request->ajax()) {
            $data = AktivitasRewardSiswa::with('jenisAktivitasReward')
                ->select(['id_aktivitas_reward_siswa', 'id_jenis_aktivitas_reward', 'nm_aktivitas_reward_siswa', 'is_aktif']);

            return DataTables::of($data)
                ->addColumn('jenis_aktivitas', function ($row) {
                    return $row->jenisAktivitasReward ? $row->jenisAktivitasReward->nm_jenis_aktivitas_reward : '-';
                })
                ->addColumn('status', function ($row) {
                    return $row->is_aktif == 1 ? 'Aktif' : 'Tidak Aktif';
                })
                // ->addColumn('action', function ($row) {
                //     $btn = '<a href="edit/' . $row->id . '" class="edit btn btn-primary btn-sm">Edit</a>';
                //     $btn .= '<a href="delete/' . $row->id . '" class="delete btn btn-danger btn-sm">Delete</a>';
                //     return $btn;
                // })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function addAktivitasRewardSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_jenis_aktivitas = JenisAktivitasReward::all();

        return view('bk/aktivitas-siswa/view-add-aktivitas-reward-siswa', compact('data_jenis_aktivitas'));
    }

    public function saveAktivitasRewardSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // $now = Carbon::now();

        $aktivitas_reward_siswa                             = new AktivitasRewardSiswa();
        $aktivitas_reward_siswa->id_jenis_aktivitas_reward  = $input->jenis_aktivitas_reward;
        $aktivitas_reward_siswa->nm_aktivitas_reward_siswa  = $input->nm_aktivitas_reward;
        $aktivitas_reward_siswa->nilai_aktivitas            = $input->nilai_aktivitas;
        $aktivitas_reward_siswa->nilai_karakter             = $input->nilai_karakter;
        $aktivitas_reward_siswa->is_guru                    = $input->is_guru;
        $aktivitas_reward_siswa->is_sekretaris              = $input->is_sekretaris;
        $aktivitas_reward_siswa->is_aktif                   = $input->status;
        $aktivitas_reward_siswa->created_by                 = $input->auth_data->pengguna->id_pengguna;
        $aktivitas_reward_siswa->save();

        return redirect('/bimbingan-konseling#aktivitas-siswa/aktivitas-reward-siswa');
    }

    public function deleteAktivitasRewardSiswa(Request $request, $id_aktivitas_reward_siswa)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data = AktivitasRewardSiswa::find($id_aktivitas_reward_siswa);
        if ($data) {
            $data->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan.'
        ], 404);
    }
}

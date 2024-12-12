<?php

namespace App\Http\Controllers\Siswa\RewardSiswa;

use App\Http\Controllers\Controller;
use App\Models\AktivitasRewardSiswa;
use App\Models\JenisAktivitasReward;
use App\Models\PengisianKegiatanHarian;
use App\Models\RewardSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class RewardSiswaController extends Controller
{
    public function viewInputRewardSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $date_input = '';
        $pengisian_kegiatan_harian = false;

        $now = Carbon::now();

        if($request->segment(3) == 'input-aktivitas-harian'){
            $jenis = 1;
            $title = 'Input Aktivitas Harian';
            $date_input = 'tanggal '.Carbon::now('Asia/Jakarta')->isoFormat('D MMM Y');

            $pengisian_kegiatan_harian = PengisianKegiatanHarian::where('id_kegiatan_harian', 'reward-siswa-harian')->where('tgl_pengisian', $now->format('Y-m-d'))->first();
        }else if($request->segment(3) == 'input-aktivitas-mingguan'){
            $jenis = 2;
            $title = 'Input Aktivitas Mingguan';
            $date_input = 'minggu ini tanggal ('.Carbon::now('Asia/Jakarta')->startOfWeek()->isoFormat('D MMM Y').' - '.Carbon::now('Asia/Jakarta')->endOfWeek()->isoFormat('D MMM Y').')';

            $startOfWeek = $now->startOfWeek()->toDateString();
            $endOfWeek = $now->endOfWeek()->toDateString();
            $pengisian_kegiatan_harian = PengisianKegiatanHarian::where('id_kegiatan_harian', 'reward-siswa-mingguan')->whereBetween('tgl_pengisian', [$startOfWeek, $endOfWeek])->first();
        }else if($request->segment(3) == 'input-aktivitas-bulanan'){
            $jenis = 3;
            $title = 'Input Aktivitas Bulanan';
            $date_input = 'bulan '.Carbon::now('Asia/Jakarta')->startOfWeek()->isoFormat('MMMM');

            $startOfMonth = $now->startOfMonth()->toDateString();
            $endOfMonth = $now->endOfMonth()->toDateString();
            $pengisian_kegiatan_harian = PengisianKegiatanHarian::where('id_kegiatan_harian', 'reward-siswa-bulanan')->whereBetween('tgl_pengisian', [$startOfMonth, $endOfMonth])->first();
        }

        return view('siswa.reward-siswa.input-reward-siswa', compact('jenis', 'title', 'date_input', 'pengisian_kegiatan_harian'));
    }

    public function ajaxGetAktivitasById(Request $request)
    {
        $input = (object) $request->input();
        // dd($input);
        $auth_data = $input->auth_data;

        $id_jenis_aktivitas_reward = $request->input('id_jenis_aktivitas_reward');

        $aktivitas_reward = AktivitasRewardSiswa::where('id_jenis_aktivitas_reward', $id_jenis_aktivitas_reward)
            ->where('is_siswa', 1)
            ->get();


        $tr = '';
        $no = 1;
        if($aktivitas_reward->count() > 0){
            foreach ($aktivitas_reward as $value) {
                $tr .= view('siswa.reward-siswa.view-input-aktivitas', compact('value', 'no'))->render();
                $no++;
            }
        }else{
            $tr .= '<tr><td style="text-align:center" colspan=3>Belum ada aktivitas</td></tr>';
        }

        return response()->json([
            'html' => $tr,
            'jumlah_pertanyaan' => $aktivitas_reward->count()
        ]);
    }

    public function saveInputAktivitasReward(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // dd($auth_data->pengguna->siswa->id_kelas);

        $jawaban = $request->input('jawaban');
        $now = Carbon::now('Asia/Jakarta');

        $jenisAktivitas = JenisAktivitasReward::where('id_jenis_aktivitas_reward', $input->jenis_aktivitas_reward)->first()->nm_jenis_aktivitas_reward;

        if ($jenisAktivitas === 'Harian') {
            $id_kegiatan_harian = 'reward-siswa-harian';
            // Cek jika sudah ada input di hari yang sama
            $exists = RewardSiswa::where('reward_siswa.id_siswa', $auth_data->pengguna->siswa->id_siswa)
                ->leftJoin('aktivitas_reward_siswa', 'reward_siswa.id_event', '=', 'aktivitas_reward_siswa.id_aktivitas_reward_siswa')
                ->where('aktivitas_reward_siswa.id_jenis_aktivitas_reward', '=', 1)
                ->whereDate('reward_siswa.created_at', $now->toDateString())
                ->exists();
            if ($exists) {
                return response()->json(['error' => 'Aktivitas harian hanya bisa diisi 1x per hari.'], 400);
            }
        } elseif ($jenisAktivitas === 'Mingguan') {
            $id_kegiatan_harian = 'reward-siswa-mingguan';
            // Cek jika sudah ada input di minggu ini
            $startOfWeek = Carbon::now('Asia/Jakarta')->startOfWeek()->toDateString();
            $endOfWeek = Carbon::now('Asia/Jakarta')->endOfWeek()->toDateString();
            $exists = RewardSiswa::where('id_siswa', $auth_data->pengguna->siswa->id_siswa)
                ->leftJoin('aktivitas_reward_siswa', 'reward_siswa.id_event', '=', 'aktivitas_reward_siswa.id_aktivitas_reward_siswa')
                ->where('aktivitas_reward_siswa.id_jenis_aktivitas_reward', '=', 2)
                ->whereBetween('reward_siswa.created_at', [$startOfWeek, $endOfWeek])
                ->exists();
            if ($exists) {
                return response()->json(['error' => 'Aktivitas mingguan hanya bisa diisi 1x per minggu.'], 400);
            }
        } elseif ($jenisAktivitas === 'Bulanan') {
            $id_kegiatan_harian = 'reward-siswa-bulanan';
            // Cek jika sudah ada input di bulan ini
            $startOfMonth = Carbon::now('Asia/Jakarta')->startOfMonth()->toDateString();
            $endOfMonth = Carbon::now('Asia/Jakarta')->endOfMonth()->toDateString();
            $exists = RewardSiswa::where('id_siswa', $auth_data->pengguna->siswa->id_siswa)
                ->leftJoin('aktivitas_reward_siswa', 'reward_siswa.id_event', '=', 'aktivitas_reward_siswa.id_aktivitas_reward_siswa')
                ->where('aktivitas_reward_siswa.id_jenis_aktivitas_reward', '=', 3)
                ->whereBetween('reward_siswa.created_at', [$startOfMonth, $endOfMonth])
                ->exists();
            if ($exists) {
                return response()->json(['error' => 'Aktivitas bulanan hanya bisa diisi 1x per bulan.'], 400);
            }
        } elseif ($jenisAktivitas === 'Tahunan') {
            $id_kegiatan_harian = 'reward-siswa-tahunan';
            // Cek jika sudah pernah diisi sebelumnya

            $startOfYear = Carbon::now('Asia/Jakarta')->startOfYear()->toDateString();
            $endOfYear = Carbon::now('Asia/Jakarta')->endOfYear()->toDateString();
            $exists = RewardSiswa::where('id_siswa', $auth_data->pengguna->siswa->id_siswa)
                ->leftJoin('aktivitas_reward_siswa', 'reward_siswa.id_event', '=', 'aktivitas_reward_siswa.id_aktivitas_reward_siswa')
                ->where('aktivitas_reward_siswa.id_jenis_aktivitas_reward', '=', 4)
                ->whereBetween('reward_siswa.created_at', [$startOfYear, $endOfYear])
                ->exists();
            if ($exists) {
                return response()->json(['error' => 'Aktivitas tahunan hanya bisa diisi 1x per tahun.'], 400);
            }
        }

        DB::beginTransaction();
        try {
            $pengisian_kegiatan_harian = new PengisianKegiatanHarian();
            $pengisian_kegiatan_harian->id_pengisian_kegiatan_harian = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
            $pengisian_kegiatan_harian->id_pengguna_pengisi = $auth_data->pengguna->id_pengguna;
            $pengisian_kegiatan_harian->id_kegiatan_harian = $id_kegiatan_harian;
            $pengisian_kegiatan_harian->status_join_table = 3;
            $pengisian_kegiatan_harian->status_pengisian = 1;
            $pengisian_kegiatan_harian->tgl_pengisian = $now->format('Y-m-d');
            $pengisian_kegiatan_harian->warna_keadaan = '#FFFFFF';
            $pengisian_kegiatan_harian->created_by = $auth_data->pengguna->id_pengguna;
            $pengisian_kegiatan_harian->save();

            foreach ($jawaban as $id_aktivitas_reward_siswa => $nilai_jawaban) {
                if ($nilai_jawaban == '1') {
                    $aktivitas_reward = AktivitasRewardSiswa::find($id_aktivitas_reward_siswa);
                    $karakter = explode('#', $aktivitas_reward->nilai_karakter);

                    // dd($jenisAktivitas);
                    foreach ($karakter as $value) {
                        $id_reward_siswa = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                        RewardSiswa::create([
                            'id_reward_siswa'           => $id_reward_siswa,
                            'id_siswa'                  => $auth_data->pengguna->siswa->id_siswa,
                            'id_event'                  => $id_aktivitas_reward_siswa,
                            'model_event'               => 'Siswa - NonKBM',
                            'id_kelas'                  => $auth_data->pengguna->siswa->id_kelas,
                            'id_pengguna_reward_siswa'  => $auth_data->pengguna->id_pengguna,
                            'nm_reward_siswa'           => $value,
                            'is_aproved'                => 0,
                            'created_by'                => $auth_data->pengguna->id_pengguna
                        ]);
                    }
                }
            }
            DB::commit();
            return response()->json(['message' => 'Jawaban berhasil disimpan!'], 200);
        } catch (\Exception $e) {
            // dd($e);
            DB::rollBack();
            return response()->json(['error' => 'Gagal menyimpan jawaban.'], 500);
        }
    }

    public function viewAktivitasRewardSaya(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('siswa.reward-siswa.aktivitas-reward-saya');
    }

    public function datatableAktivitasRewardSaya(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $id_pengguna = $auth_data->pengguna->id_pengguna;

        $listData = RewardSiswa::select(
            'jar.nm_jenis_aktivitas_reward', 
            'ars.nm_aktivitas_reward_siswa',
            'reward_siswa.is_aproved',
            'reward_siswa.created_at',
            'reward_siswa.nm_reward_siswa'
            )
            ->join('aktivitas_reward_siswa as ars', 'ars.id_aktivitas_reward_siswa', '=', 'reward_siswa.id_event')
            ->join('jenis_aktivitas_reward as jar', 'ars.id_jenis_aktivitas_reward', '=', 'jar.id_jenis_aktivitas_reward')
            ->where('reward_siswa.id_pengguna_reward_siswa', $id_pengguna)
            ->whereNull('reward_siswa.deleted_at')
            ->get();

        return DataTables::of($listData)
            ->addIndexColumn()
            ->addColumn('approval', function ($row) {
                return $row->is_aproved ? 'Approved' : 'Belum di Approve';
            })
            ->addColumn('tanggal_pengisian', function ($row) {
                return \Carbon\Carbon::parse($row->created_at)->format('Y-m-d');
            })
            ->make(true);
    }

    public function viewRekapRewardSiswa(Request $request)
    {
        $input = (object) $request->input();

        $now = Carbon::now('Asia/Jakarta');

        $startOfMonth = Carbon::now('Asia/Jakarta')->startOfMonth();
        $endOfMonth = Carbon::now('Asia/Jakarta')->endOfMonth();
        $data_pengisian_kegiatan_harian = PengisianKegiatanHarian::whereBetween('tgl_pengisian', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])->get();

        $dates = CarbonPeriod::create($startOfMonth, $endOfMonth);

        $week_dates = array();

        $i = $startOfMonth;
        while($i < $endOfMonth){
            $week['start'] = $i;
            $week['end'] = Carbon::parse($i)->endOfWeek();

            $week_dates[] = $week;
            $i = Carbon::parse($week['end'])->addDay();
        }

        return view('siswa.reward-siswa.rekap-reward-siswa', compact('now', 'dates', 'week_dates', 'data_pengisian_kegiatan_harian'));
    }

    public function viewApproveRewardSiswa(Request $request)
    {
        $input = (object) $request->input();
        dd($input);
    }
}

<?php

namespace App\Http\Controllers\BK\Absensi;

use App\Http\Controllers\Controller;
use App\Models\PelanggaranSiswa;
use App\Models\PresensiPengguna;
use App\Models\ShiftMaster;
use App\Models\Siswa;
use App\Models\SubkategoriPelanggaran;
use Carbon\Carbon;
use Auth;
use DB;
use Session;
use Validator;


use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Models\Pengguna;
use App\Models\PresensiHarian;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;

class HistoriSiswaTerlambatController extends Controller
{
    public function viewSiswaTerlambat(Request $request, $date = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $terlambat = [];
        if (empty($date)) {
            $date = Carbon::now(env('APP_TIMEZONE', ''))->toDateString();
        } else {
            $date = Carbon::parse($date)->toDateString();
        }

        $options = [
            'join' => ', ',
            'parts' => 2,
            'syntax' => CarbonInterface::DIFF_ABSOLUTE,
        ];

        $pengguna = Pengguna::with([
            'shiftPengguna' => function ($query) use ($date) {
                $query->where('date', $date)->with('shift_master');
            },
            'presensi_pengguna' => function ($query) use ($date) {
                $query->where('date', $date);
            }, 'siswa.pelanggaranTerlambat' => function ($query) use ($date) {
                $query->where('tgl_pelanggaran', $date);
            },
        ],)->whereHas('status_pengguna', function ($query) {
            $query->where('nm_status_pengguna', '=', 'AKTIF');
        })->where('status_join_table', '3')->get();

        foreach ($pengguna as $key => $p) {
            if (empty($p->shiftPengguna)) {
                continue;
            } elseif (empty($p->presensi_pengguna)) {
                $terlambat[$key]['pengguna'] = $p;
                $terlambat[$key]['keterangan'] = 'Belum Absent';
            } elseif ($p->presensi_pengguna->check_in > $p->shiftPengguna->shift_master->start_time) {
                $terlambat[$key]['pengguna'] = $p;
                $terlambat[$key]['keterangan'] = Carbon::parse($p->presensi_pengguna->check_in)->diffForHumans(Carbon::parse($p->shiftPengguna->shift_master->start_time), $options);
            }
        }
        return view('bk/absensi/view-absensi-terlambat', compact('auth_data', 'terlambat', 'date'));
    }


    public function viewAddnotes(Request $request, $id_presensi_pengguna = null)
    {
        $presences = PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->first();
        return view('bk/absensi/add-notes-absensi', compact('presences'));
    }

    public function PrintTerlambat(Request $request, $id = null)
    {
        # code..
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $lebar = 70;
        $nama_sekolah = $auth_data->sekolah_data->nm_sekolah;
        $presences = PresensiPengguna::where('id_presensi_pengguna', $id)->first();

        $siswa = LibSiswa::fetchDataSiswaByPengguna($auth_data, $presences->id_pengguna);

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $date = $presences->date;
        // dd($date->format('d M Y'));

        # printing purpose..
        # option = default/struk
        // $lebar = null;

        return view('bk/absensi/print-absen-terlambat', compact('auth_data', 'siswa', 'semester_aktif', 'nama_sekolah', 'presences', 'lebar'));
    }

    public function updateAddnotes(Request $request, $id_presensi_pengguna = null)
    {
        $input = $request->input();
        $presences = PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->first();
        $presences->update(['status' => $input['status'], 'notes' => $input['notes'], 'check_in' => $input['check_in'], 'check_out' => $input['check_out']]);
        return redirect("bimbingan-konseling#absensi/catat-siswa-terlambat");
    }

    public function postSiswaTerlambat(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $subKategoriPelanggaran = SubkategoriPelanggaran::where('keterangan_subkategori_pelanggaran', 'Terlambat masuk kelas pada jam pelajaran sekolah.')->first();
        // dd($subKategoriPelanggaran);
        $siswa = Siswa::whereIn('id_pengguna', $input->data_siswa)->get();
        // dd(date_format(date_create($input->tanggal),"Y-m-d H:i:s"));
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        try {

            foreach ($siswa as $s) {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
                $pelanggaranSiswa                               = new PelanggaranSiswa();
                $pelanggaranSiswa->id_pelanggaran_siswa         = $id;
                $pelanggaranSiswa->id_siswa                     = $s->id_siswa;
                $pelanggaranSiswa->id_kelas                     = $s->id_kelas;
                $pelanggaranSiswa->id_guru_input                = $input->auth_data->pengguna->id_pengguna;
                $pelanggaranSiswa->id_semester                  = $semester_aktif->id_semester;
                $pelanggaranSiswa->id_subkategori_pelanggaran   = $subKategoriPelanggaran->id_subkategori_pelanggaran;
                $pelanggaranSiswa->catatan_pelanggaran          = 'Terlambat Fingerprint';
                // convert format date
                $pelanggaranSiswa->tgl_pelanggaran              = date_format(date_create($input->tanggal), "Y-m-d H:i:s");
                $pelanggaranSiswa->aktor_input_pelanggaran      = 1;
                $pelanggaranSiswa->is_sudah_tindakan            = 0;
                $pelanggaranSiswa->created_by                   = $input->auth_data->pengguna->id_pengguna;
                $pelanggaranSiswa->save();

                // $pengguna                       = Pengguna::find($id_pengguna);
                // $pengguna->password             = Hash::make($pengguna->username);
                // $pengguna->must_change_password = 1;
                // $pengguna->last_time_password   = $now;
                // $pengguna->updated_by           = $input->auth_data->pengguna->id_pengguna;
                // $pengguna->updated_at           = $now;
                // $pengguna->save();

                // $log = new LogResetPassword;
                // $log->id_log_reset_password = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                // $log->id_pengguna       = $pengguna->id_pengguna;
                // $log->created_by           = $input->auth_data->pengguna->id_pengguna;
                // $log->save();
            }

            DB::commit();

            return response()->json([
                'status_code'     => 200,
                'status_text'     => 'Success',
                'message' => 'Data Pelangaran Terkirim'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong

            return response()->json([
                'status_code'     => 300,
                'status_text'     => 'Failed',
                'message' => (env('APP_DEBUG', 'true') == 'true') ? $e->getMessage() : 'Operation error. Error ' . $e->getLine()
            ]);
        }
    }
}

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
use App\Models\Kelas;
use App\Models\Pengguna;
use App\Models\PresensiHarian;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;

class HistoriSiswaTerlambatController extends Controller
{
    public function viewSiswaTerlambat(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelas = Kelas::get();
        $date = Carbon::now(env('APP_TIMEZONE', ''))->toDateString();
        return view('bk/absensi/view-absensi-terlambat', compact('auth_data', 'date', 'kelas'));
    }

    public function filterSiswaTerlambat(Request $request)
    {
        $input = (object) $request->input();
        return [
            'status' => 204,
            'path' => 'absensi/catat-siswa-terlambat/detail/' . $input->id_kelas . '/' . $input->date
        ];
    }

    public function detailSiswaTerlambat(Request $request, $id_kelas, $date)
    {
        $terlambat = [];
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $date = Carbon::parse($date)->toDateString();
        $kelas = Kelas::get();

        $options = [
            'join' => ', ',
            'parts' => 2,
            'syntax' => CarbonInterface::DIFF_ABSOLUTE,
        ];

        $penggunaQuery = Pengguna::where('status_join_table', '3')->with([
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
        });

        if ($id_kelas != '0') {
            $penggunaQuery->whereHas('siswa', function ($query) use ($id_kelas) {
                $query->where('id_kelas', '=', $id_kelas);
            });
        }

        $pengguna = $penggunaQuery->get();

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
        return view('bk/absensi/detail-absensi-terlambat', compact('auth_data', 'terlambat', 'date', 'kelas', 'id_kelas'));
    }

    public function viewEditNotes(Request $request, $id_presensi_pengguna, $id_kelas, $date)
    {
        $presences = PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->first();
        return view('bk/absensi/edit-notes-absensi', compact('presences', 'id_kelas', 'date'));
    }

    public function viewAddNotes(Request $request, $id_pengguna, $id_kelas, $date)
    {
        return view('bk/absensi/add-notes-absensi', compact('id_pengguna', 'id_kelas', 'date'));
    }

    public function ActionAddnotes(Request $request, $id_pengguna = null)
    {
        $input = (object) $request->input();
        $buttonType = $request->input('button_type');

        $presensi = new PresensiPengguna;
        $presensi->id_pengguna = $id_pengguna;
        $presensi->status_join_table = '3';
        $presensi->date = Carbon::now()->format('Y-m-d');
        $presensi->check_in = $input->check_in;
        $presensi->check_out = $input->check_out;
        $presensi->status = $input->status;
        $presensi->notes = $input->notes;
        $presensi->created_by = $input->auth_data->pengguna->id_pengguna;
        $presensi->save();

        $id_presensi_pengguna = PresensiPengguna::where('id_pengguna', $id_pengguna)->where('date', Carbon::now()->format('Y-m-d'))->first()->id_presensi_pengguna;

        if ($buttonType == 'save') {
            return redirect("bimbingan-konseling#absensi/catat-siswa-terlambat/detail/" . $input->id_kelas . '/' . $input->date);
        } else {
            return redirect("bimbingan-konseling/absensi/catat-siswa-terlambat/print/" . $id_presensi_pengguna);
        };
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

    public function ActionEditnotes(Request $request, $id_presensi_pengguna = null)
    {
        $input = (object) $request->input();
        $buttonType = $request->input('button_type');

        $presences = PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->first();
        $presences->update(['status' => $input->status, 'notes' => $input->notes, 'check_in' => $input->check_in, 'check_out' => $input->check_out]);

        if ($buttonType == 'save') {
            return redirect("bimbingan-konseling#absensi/catat-siswa-terlambat/detail/" . $input->id_kelas . '/' . $input->date);
        } else {
            return redirect("bimbingan-konseling/absensi/catat-siswa-terlambat/print/" . $id_presensi_pengguna);
        };
    }

    public function postSiswaTerlambat(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $subKategoriPelanggaran = SubkategoriPelanggaran::where('keterangan_subkategori_pelanggaran', 'Terlambat masuk kelas pada jam pelajaran sekolah.')->first();
        $siswa = Siswa::whereIn('id_pengguna', $input->data_siswa)->get();
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

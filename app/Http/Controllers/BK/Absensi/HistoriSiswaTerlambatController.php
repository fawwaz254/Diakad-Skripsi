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
use Illuminate\Http\Request;

class HistoriSiswaTerlambatController extends Controller
{  
    public function viewSiswaTerlambat(Request $request, $date = null){
        if (empty($date)) {
            $now = Carbon::now(env('APP_TIMEZONE', ''))->toDateString();
        }
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $absensi_siswa = ShiftMaster::where('type', 'Siswa')->first();
        $now = Carbon::parse($date)->toDateString();
        // dd($now);
        if (!empty($absensi_siswa->start_time)) {
            $terlambat = PresensiPengguna::with('pengguna.siswa.kelas')->where('date', $now)->where('status_join_table', 3)->whereTime('check_in', '>=', $absensi_siswa->start_time)->get();
        }
        $sudah_terkirim = PelanggaranSiswa::where('tgl_pelanggaran', $now)->get();
        // dd($sudah_terkirim);

        if (!empty($absensi_siswa->start_time)) {
            return view('bk/absensi/view-absensi-terlambat', compact('auth_data', 'terlambat', 'now', 'absensi_siswa', 'sudah_terkirim'));
        }
        return view('bk/absensi/view-absensi-terlambat', compact('auth_data', 'now', 'absensi_siswa', 'sudah_terkirim'));
    }

    public function viewAddnotes(Request $request, $id_presensi_pengguna = null)
    {
        $presences = PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->first();
        return view('bk/absensi/add-notes-absensi', compact('presences'));
    }
    public function updateAddnotes(Request $request, $id_presensi_pengguna = null)
    {
        $input = $request->input();
        $presences = PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->first();
        $presences->update(['status' => $input['status'], 'notes' => $input['notes'], 'check_in' => $input['check_in'], 'check_out' => $input['check_out']]);
        return redirect("bimbingan-konseling#absensi/catat-siswa-terlambat");
    }

    public function postSiswaTerlambat(Request $request){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $now = Carbon::now(env('APP_TIMEZONE', ''));
        $subKategoriPelanggaran = SubkategoriPelanggaran::where('keterangan_subkategori_pelanggaran','Terlambat masuk kelas pada jam pelajaran sekolah.')->first();
        // dd($subKategoriPelanggaran);
        $siswa = Siswa::whereIn('id_pengguna', $input->data_siswa)->get();
        // dd(date_format(date_create($input->tanggal),"Y-m-d H:i:s"));
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        try {

            foreach($siswa as $s){
                $id = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();
                $pelanggaranSiswa                               = new PelanggaranSiswa();
                $pelanggaranSiswa->id_pelanggaran_siswa         = $id;
                $pelanggaranSiswa->id_siswa                     = $s->id_siswa;
                $pelanggaranSiswa->id_kelas                     = $s->id_kelas;
                $pelanggaranSiswa->id_guru_input                = $input->auth_data->pengguna->id_pengguna;
                $pelanggaranSiswa->id_semester                  = $semester_aktif->id_semester;
                $pelanggaranSiswa->id_subkategori_pelanggaran   = $subKategoriPelanggaran->id_subkategori_pelanggaran;
                $pelanggaranSiswa->catatan_pelanggaran          = 'Terlambat Fingerprint';
                // convert format date
                $pelanggaranSiswa->tgl_pelanggaran              = date_format(date_create($input->tanggal),"Y-m-d H:i:s");
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
                'status_code' 	=> 200,
                'status_text' 	=> 'Success',
                'message' => 'Data Pelangaran Terkirim'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong

            return response()->json([
                'status_code' 	=> 300,
                'status_text' 	=> 'Failed',
                'message' => (env('APP_DEBUG', 'true') == 'true')? $e->getMessage() : 'Operation error. Error '.$e->getLine()
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\Guru\GuruPiket;

use App\Http\Controllers\Controller;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Models\Kelas;
use App\Models\Pengguna;
use App\Models\PresensiPengguna;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;

class SiswaTerlambatController extends Controller
{
    public function viewSiswaTerlambat(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $kelas = Kelas::where('is_aktif', 1)->get();
        $date = Carbon::now()->toDateString();
        return view('guru/guru-piket/siswa-terlambat/view-siswa-terlambat', compact('auth_data', 'date', 'kelas'));
    }

    public function filterSiswaTerlambat(Request $request)
    {
        $input = (object) $request->input();
        return [
            'status' => 204,
            'path' => 'guru-piket/catat-siswa-terlambat/detail/' . $input->id_kelas . '/' . $input->date
        ];
    }

    public function detailSiswaTerlambat(Request $request, $id_kelas, $date)
    {
        $terlambat = [];
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $date = Carbon::parse($date)->toDateString();
        $kelas = Kelas::where('is_aktif', 1)->get();

        $options = [
            'join' => ', ',
            'parts' => 2,
            'syntax' => CarbonInterface::DIFF_ABSOLUTE,
        ];

        $pengguna = Pengguna::where('status_join_table', '3')->with([
            'shiftPengguna' => function ($query) use ($date) {
                $query->where('date', $date)->with('shift_master');
            },
            'presensi_pengguna' => function ($query) use ($date) {
                $query->where('date', $date);
            },
            'siswa.pelanggaranTerlambat' => function ($query) use ($date) {
                $query->where('tgl_pelanggaran', $date);
            },
            'siswa'
        ])->orderBy('username', 'desc')->get();

        foreach ($pengguna as $key => $p) {
            if (empty($p->siswa)) {
                continue;
            }
            if ($p->siswa->id_kelas && $id_kelas == '0' || $p->siswa->id_kelas == $id_kelas) {
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
        }

        return view('guru/guru-piket/siswa-terlambat/detail-siswa-terlambat', compact('auth_data', 'terlambat', 'date', 'kelas', 'id_kelas'));
    }

    public function viewAddNotes(Request $request, $id_pengguna, $id_kelas, $date)
    {
        return view('guru/guru-piket/siswa-terlambat/add-notes-terlambat', compact('id_pengguna', 'id_kelas', 'date'));
    }

    public function viewEditNotes(Request $request, $id_presensi_pengguna, $id_kelas, $date)
    {
        $presences = PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->first();
        return view('guru/guru-piket/siswa-terlambat/edit-notes-terlambat', compact('presences', 'id_kelas', 'date'));
    }

    public function ActionAddnotes(Request $request, $id_pengguna = null)
    {
        $input = (object) $request->input();
        $buttonType = $request->input('button_type');

        $presensi = new PresensiPengguna();
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
            return redirect("guru#guru-piket/catat-siswa-terlambat/detail/" . $input->id_kelas . '/' . $input->date);
        } else {
            return redirect("guru/guru-piket/catat-siswa-terlambat/print/" . $id_presensi_pengguna);
        };
    }

    public function ActionEditnotes(Request $request, $id_presensi_pengguna = null)
    {
        $input = (object) $request->input();
        $buttonType = $request->input('button_type');

        $presences = PresensiPengguna::where('id_presensi_pengguna', $id_presensi_pengguna)->first();
        $presences->update(['status' => $input->status, 'notes' => $input->notes, 'check_in' => $input->check_in, 'check_out' => $input->check_out]);

        if ($buttonType == 'save') {
            return redirect("guru#guru-piket/catat-siswa-terlambat/detail/" . $input->id_kelas . '/' . $input->date);
        } else {
            return redirect("guru/guru-piket/catat-siswa-terlambat/print/" . $id_presensi_pengguna);
        };
    }

    public function PrintTerlambat(Request $request, $id = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $lebar = 70;
        $nama_sekolah = $auth_data->sekolah_data->nm_sekolah;
        $presences = PresensiPengguna::where('id_presensi_pengguna', $id)->first();

        $siswa = LibSiswa::fetchDataSiswaByPengguna($auth_data, $presences->id_pengguna);

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $date = $presences->date;

        return view('bk/absensi/print-absen-terlambat', compact('auth_data', 'siswa', 'semester_aktif', 'nama_sekolah', 'presences', 'lebar'));
    }
}

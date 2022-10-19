<?php

namespace App\Http\Controllers\Akademik\RaporSisipan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Kelas;
use App\Models\MataPelajaran;
// use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\RaporSisipan;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\KomponenNilaiRaporSisipan;
use App\Models\NilaiRaporSisipan;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Siswa;
use App\Imports\UploadRaporSisipanSTS;
use Auth;
use DB;
use Session;
use Validator;


class RaporTengahSemesterController extends Controller
{
    public function viewRaporTengahSemester(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        // dd($auth_data);
        return view('guru/rapor-sisipan/rapor-tengah-semester/view-rapor-tengah-semester', compact('auth_data'));
    }

    public function datatablesRaporTengahSemester(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = RaporSisipan::with('pengguna', 'mata_pelajaran', 'kelas', 'semester')->get();

        return Datatables::of($list_data)
            ->addColumn('mata_pelajaran', function ($item) {
                return $item->mata_pelajaran->nm_mata_pelajaran;
            })
            ->addColumn('jumlah', function ($item) {
                //semua siswa
                $allSiswa =  Siswa::where('id_kelas', $item->kelas->id_kelas)->count();

                //cari siswa yang ada nilai 0 nya
                $belumTerisi = NilaiRaporSisipan::where('id_rapor_sisipan', $item->id_rapor_sisipan)->where('nilai', 0)->with('siswa.kelas')->whereHas('siswa.kelas', function ($query) use ($item) {
                    $query->where('id_kelas', '=', $item->kelas->id_kelas);
                })->groupBy('id_siswa')
                    ->selectRaw('count(*) as total, id_siswa')
                    ->get()->toArray();

                //hitung ada berapa nilai kosongnya
                $arrayJumlahBelumTerisi = array_count_values(array_column($belumTerisi, 'total'));

                //loop dan cari nilai kosong yang diatas 5
                $nilaiSiswaYangKosong = 0;
                for ($i = 6; $i <= 10; $i++) {
                    if (isset($arrayJumlahBelumTerisi[$i])) {
                        $nilaiSiswaYangKosong += $arrayJumlahBelumTerisi[$i];
                    }
                }

                $data = array(
                    'jumlah_siswa' => $allSiswa,
                    'terisi_siswa' => $allSiswa - $nilaiSiswaYangKosong,
                );
                // dd($data['terisi_siswa']);
                return $data;
            })
            ->editColumn('semester', function ($item) {
                return $item->semester->tahun_ajaran . ' ' . $item->semester->nm_semester;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id'     => $item->id_rapor_sisipan
                );
                return $data;
            })
            ->make(true);
    }

    public function pdfRaporTengahSemester(Request $request, $id_rapor_sisipan)
    {

        set_time_limit(1800);
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $rapor_sisipan = RaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('mata_pelajaran', 'kelas', 'semester','pengguna')->first();


        $list_siswa = Siswa::where('id_kelas', $rapor_sisipan->id_kelas)->with('pengguna')->orderBy('nis_siswa')->get();

        $list_nilai = NilaiRaporSisipan::where('id_rapor_sisipan', $id_rapor_sisipan)->with('siswa')
            ->whereHas('siswa', function ($query) use ($rapor_sisipan) {
                $query->where('id_kelas', '=', $rapor_sisipan->id_kelas);
            })
            ->get();


        $nilai_siswa = [];
        // $nilai_komponen = [];
        if ($list_siswa) {
            $nilai = $list_nilai->toArray();
            foreach ($nilai as $nilaiRapor) {
                foreach ($nilaiRapor as $a) {
                    $nilai_siswa[$nilaiRapor['id_siswa'] . $nilaiRapor['id_rapor_sisipan']] = $nilaiRapor['nilai'];
                }
            }
        }

        // dd($nilai_siswa);
        return view('guru/rapor-sisipan/rapor-tengah-semester/cetak-nilai-rapor-tengah-semester', compact('auth_data', 'id_rapor_sisipan',  'list_siswa', 'nilai_siswa', 'rapor_sisipan'));
    }
}

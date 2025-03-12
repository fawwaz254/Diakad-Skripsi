<?php

namespace App\Http\Controllers\RaporBukuInduk\BukuInduk;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibSiswa;
use App\Models\LogKelasSiswa;
use App\Models\NilaiMp;
use App\Models\PengambilanEkskul;
use App\Models\PengambilanMagang;
use App\Models\PengambilanMp;
use App\Models\PresensiEkskulPeserta;
use App\Models\PresensiMpSiswa;
use App\Models\RaporDeskripsi;
use App\Models\RaporKategori;
use App\Models\RaporKelompok;
use App\Models\RaporKelompokMp;
use App\Models\RaporSiswa;
use App\Models\RaporSubkelompokMp;
use App\Models\Siswa as Siswa;
use App\Models\CalonSiswaBeasiswa;
use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use PDF;
use DB;
use Session;
use Validator;



class CariSiswaBIController extends BaseController
{
    public function viewCariSiswa(Request $request, $nis_nama_siswa = null)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('rapor-buku-induk/buku-induk/cari-siswa/view-cari-siswa', compact('auth_data', 'nis_nama_siswa'));
    }

    public function actionViewCariSiswa(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $validator = Validator::make($request->all(), [
            'nis_nama_siswa' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'buku-induk/cari-siswa/' . $input->nis_nama_siswa
            ];
        }
    }

    public function datatablesCariSiswa(Request $request, $nis_nama_siswa)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $siswa = Siswa::select('siswa.id_siswa', 'siswa.nis_siswa', 'siswa.nisn_siswa', 'pengguna.nm_pengguna', 'kelas.id_kelas', 'kelas.nm_kelas', 'kelas.tingkat', 'status_pengguna.nm_status_pengguna', 'jalur.nm_jalur')
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            ->join('status_pengguna', 'pengguna.id_status_pengguna', '=', 'status_pengguna.id_status_pengguna')
            ->join('jalur_siswa', function ($join) {
                $join->on('jalur_siswa.id_siswa', '=', 'siswa.id_siswa')
                    ->where('jalur_siswa.is_jalur_aktif', '=', 1);
            })
            ->join('jalur', 'jalur_siswa.id_jalur', '=', 'jalur.id_jalur')
            ->where(function ($query) use ($nis_nama_siswa) {
                $query->where('siswa.nis_siswa', 'like', '%' . $nis_nama_siswa . '%')
                    ->orWhere('pengguna.nm_pengguna', 'like', '%' . $nis_nama_siswa . '%')
                    ->orWhere('siswa.nisn_siswa', 'like', '%' . $nis_nama_siswa . '%');
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



    public function printRaporSiswa(Request $request, $nis_siswa)
    {

        $input = (object) $request->input();
        $auth_data = auth_data();

        $siswa = LibSiswa::fetchDataDetailSiswa($auth_data, $nis_siswa);
        $beasiswa = CalonSiswaBeasiswa::where('id_c_siswa', $siswa->id_c_siswa)->get();

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        $data_beasiswa[0]['urutan_1'] = '61.';
        $data_beasiswa[0]['urutan_2'] = 'Menerima Beasiswa';
        $data_beasiswa[0]['urutan_3'] = ': ';

        if ($beasiswa) {
            foreach ($beasiswa as $key => $value) {
                if ($key == 0) {
                    $data_beasiswa[$key]['urutan_1'] = '61.';
                    $data_beasiswa[$key]['urutan_2'] = 'Menerima Beasiswa';
                    $data_beasiswa[$key]['urutan_3'] = $value->keterangan_beasiswa_c_siswa . ' Tahun ' . $value->tahun_mulai_beasiswa_c_siswa . ' - ' . $value->tahun_selesai_beasiswa_c_siswa;
                } else {
                    $data_beasiswa[$key]['urutan_1'] = '';
                    $data_beasiswa[$key]['urutan_2'] = '';
                    $data_beasiswa[$key]['urutan_3'] = $value->keterangan_beasiswa_c_siswa . ' Tahun ' . $value->tahun_mulai_beasiswa_c_siswa . ' - ' . $value->tahun_selesai_beasiswa_c_siswa;
                }
            }
        }

        return view('pendidikan/siswa/insert-update-siswa/view-print-siswa', compact('auth_data', 'siswa', 'data_beasiswa', 'semester_aktif'));
    }

    public function viewPrintSiswaKelas(Request $request, $id_kelas)
    {

        $input = (object) $request->input();
        $auth_data = auth_data();

        $siswa1 = LibSiswa::fetchDataSiswa($auth_data, $id_kelas);
        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);

        return view('pendidikan/siswa/insert-update-siswa/view-print-siswa-kelas', compact('auth_data', 'siswa1', 'semester_aktif'));
    }
}

<?php

namespace App\Http\Controllers\BK\PenangananSiswa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\PelanggaranSiswa;
use App\Models\TindakanPelanggaran;
use App\Models\KategoriPelanggaran;
use App\Models\JenisTindakan;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Pendidikan\LibKelas;
use App\Libraries\BimbinganKonseling\LibDataPelanggaran;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;
use Validator;

class JurnalTindakanController extends BaseController
{
    public function viewJurnalTindakan(Request $request) {
        # code...
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
        $data_semester  = LibDataAkademik::fetchDataNamaSemester($auth_data);
        $data_kelas     = LibKelas::fetchDataKelas($auth_data);

        return view('bk/penanganan-siswa/jurnal-tindakan/view-jurnal-tindakan', compact('auth_data','semester_aktif','data_semester','data_kelas'));
    }

    public function actionPostJurnalTindakan(Request $request)
    {
        # code...
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'id_semester'   => 'required',
            'id_kelas'      => 'required',
            'id_siswa'      => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'penanganan-siswa/jurnal-tindakan/print/'.$input->id_semester.'/'.$input->id_kelas.'/'.$input->id_siswa
            ];
        }
    }

    public function printJurnalTindakan(Request $request, $id_semester, $id_kelas, $id_siswa) {

        # code...
        $input      = (object) $request->input();
        $auth_data  = $input->auth_data;

        $sekolah_data = $auth_data->sekolah_data;

        $siswa      = LibSiswa::fetchDataSiswa($auth_data, $id_kelas, $id_siswa);

        $semester   = Semester::find($id_semester);

        $list_data = LibSiswa::fetchPelanggaranNonKBM($auth_data, $siswa->id_pengguna);

        // $list_data = LibSiswa::fetchPelanggaranKBM($auth_data, $siswa->id_pengguna);

        // dd($list_data);

        return view('bk/penanganan-siswa/jurnal-tindakan/print-jurnal-tindakan', compact('siswa', 'sekolah_data', 'semester', 'list_data'));
    }

}
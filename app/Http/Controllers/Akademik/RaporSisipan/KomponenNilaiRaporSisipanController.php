<?php

namespace App\Http\Controllers\Akademik\RaporSisipan;

use App\Http\Controllers\Controller;
use App\Models\KomponenNilaiRaporSisipan;
use Illuminate\Http\Request;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\RaporSisipan;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;
use App\Libraries\Pendidikan\LibDataAkademik;
use App\Models\KomponenJenisRapor;
// use App\Models\KomponenNilaiRaporSisipan;
use App\Models\NilaiRaporSisipan;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Siswa;
use Auth;
use DB;
use Session;
use Validator;




class KomponenNilaiRaporSisipanController extends Controller
{
    public function viewKomponenNilai(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('akademik/rapor-sisipan/komponen-nilai/view-komponen-nilai', compact('auth_data'));
    }

    public function editKomponenNilai(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $komponen_nilai = KomponenNilaiRaporSisipan::find($id);
        // dd($komponen_nilai);


        return view('akademik/rapor-sisipan/komponen-nilai/edit-komponen-nilai', compact('auth_data', 'komponen_nilai'));
    }

    public function datatablesKomponenNilai(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = auth_data();
        // $list_data = KomponenNilaiRaporSisipan::orderBy('urutan', 'asc')->get();
        $list_data = KomponenJenisRapor::whereHas('jenis_rapor', function ($query) {
            $query->where('nm_jenis_rapor',  'sisipan');
        })->orderByRaw('CAST(urutan AS UNSIGNED)')->get();

        return Datatables::of($list_data)

            // ->addColumn('action', function ($item) {
            //     $data = array(
            //         'id'     => $item->id_komponen_nilai
            //     );
            //     return $data;
            // })
            ->make(true);
    }

    public function actionEditKomponenNilai(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $validator = Validator::make($request->all(), [
            'nm_nilai' => 'required',
            'type' => 'required',
            'status' => 'required'

        ]);
        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }

        $komponen_nilai = KomponenNilaiRaporSisipan::where('id_komponen_nilai', $id)->first();
        $komponen_nilai->nm_nilai = $input->nm_nilai;
        $komponen_nilai->type = $input->type;
        $komponen_nilai->status = $input->status;
        $komponen_nilai->save();

        return [
            'status' => 202, // SUCCESS AND LOAD PAGE
            'message' => 'Edit Komponen Nilai Berhasil!',
            'path' => 'rapor-sisipan/komponen-nilai'
        ];
    }
}

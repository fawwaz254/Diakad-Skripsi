<?php

namespace App\Http\Controllers\Tendik\KegiatanHarian;

use App\Http\Controllers\Controller;
use App\Models\KegiatanHarian;
use App\Models\PengisianJawaban;
use App\Models\PengisianKegiatanHarian;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;
use Validator;

class FormLainnyaController extends Controller
{
    public function viewListFormLainnya(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('tendik/kegiatan-harian/form-lainnya/view-list-form-lainnya', compact('auth_data'));
    }

    public function datatablesListFormLainnya(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = KegiatanHarian::where('nm_kegiatan_harian','!=','Monitoring Kesehatan COV-19')->get();

        return Datatables::of($list_data)
        // ->editColumn('pengguna_pengisi.nm_pengguna', function ($item) {
        //     return $item->pengguna_pengisi->fullname();
        // })
        // ->editColumn('tgl_pengisian', function ($item) {
        //     return date_format(date_create($item->tgl_pengisian), 'd M Y');
        // })
        // ->editColumn('created_at', function ($item) {
        //     return date_format(date_create($item->created_at), 'd M Y H:i') . ' WIB';
        // })
        ->editColumn('is_aktif', function ($item) {
            return $item->is_aktif == '1' ? 'Aktif' : 'Tidak Aktif';
        })
        ->addColumn('action', function ($item) {
            $data = array(
                'id' => $item->id_kegiatan_harian
            );
            return $data;
        })
        ->make(true);

    }

    public function isiFormLainnya(Request $request, $id_form ){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $kegiatan_harian = KegiatanHarian::with('kategori_pertanyaan', 'kategori_pertanyaan.pertanyaan', 'kategori_pertanyaan.pertanyaan.jawaban')->where('is_aktif', 1)->where('id_kegiatan_harian',$id_form)->first();
        $data_kegiatan_harian_kategori = $kegiatan_harian->kategori_pertanyaan;
        return view('tendik/kegiatan-harian/form-lainnya/view-add-form-lainnya', compact('auth_data', 'kegiatan_harian', 'data_kegiatan_harian_kategori'));
    }

    public function viewFormLainnya(Request $request, $id_form){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('tendik/kegiatan-harian/form-lainnya/view-form-lainnya', compact('auth_data','id_form'));


        // dd($id_form);
    }
    public function datatablesFormLainnya(Request $request, $id_form){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $list_data = PengisianKegiatanHarian::where('id_p','!=','Monitoring Kesehatan COV-19')->first();

        return Datatables::of($list_data)
        // ->editColumn('pengguna_pengisi.nm_pengguna', function ($item) {
        //     return $item->pengguna_pengisi->fullname();
        // })
        // ->editColumn('tgl_pengisian', function ($item) {
        //     return date_format(date_create($item->tgl_pengisian), 'd M Y');
        // })
        // ->editColumn('created_at', function ($item) {
        //     return date_format(date_create($item->created_at), 'd M Y H:i') . ' WIB';
        // })
        ->editColumn('is_aktif', function ($item) {
            return $item->is_aktif == '1' ? 'Aktif' : 'Tidak Aktif';
        })
        ->addColumn('action', function ($item) {
            $data = array(
                'id' => $item->id_kegiatan_harian
            );
            return $data;
        })
        ->make(true);
    }
}

<?php

namespace App\Http\Controllers\Siswa\ELearning;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\App;

use App\Models\Guru;
use App\Models\MateriAjar;
use App\Models\MateriAjarFile;
use App\Models\MataPelajaran;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\Siswa;

use Auth;
use DB;
use Session;
use Validator;

class MateriAjarController extends BaseController
{

    public function viewMateriAjar(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('siswa/e-learning/materi-ajar/view-materi-ajar',compact('auth_data'));
    }

    public function viewDetailMateriAjar(Request $request,$id)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        $data['list_mapel'] = MataPelajaran::all();
        $data['list_jurusan'] = Jurusan::all();
        $data['list_kelas'] = Kelas::all();

        $materi_ajar = MateriAjar::with('materi_ajar_file')->find($id);
        return view('siswa/e-learning/materi-ajar/detail-materi-ajar',compact('auth_data','materi_ajar'),$data);
    }

    public function datatablesMateriAjar(Request $request){

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $siswa = Siswa::with('kelas')->where('id_pengguna',$auth_data->pengguna->id_pengguna)->first();
        $list_data = MateriAjar::with('materi_ajar_file','mapel','guru.pengguna')
                            ->where('id_jurusan',$siswa->kelas->id_jurusan)
                            ->where('tingkat', $siswa->kelas->id_kelas)
                            ->get();

        return Datatables::of($list_data)
                ->addColumn('mapel',function($item){
                    return $item->mapel->nm_mata_pelajaran;
                })
                ->addColumn('guru',function($item){
                    return $item->guru->pengguna->nm_pengguna;
                })
                ->addColumn('action', function($item){

                    $file = [];
                    foreach($item->materi_ajar_file as $key => $r){
                        $file[$key]['nm_file'] = $r->nm_file;
                        $file[$key]['type_file'] = $r->type_file;
                        $file[$key]['link_file'] = $r->type_file == "link" ? $r->link_file : Storage::disk('spaces')->url($r->link_file) ;
                    }

                    $data = array(
                        'id'     => $item->id_materi_ajar,
                        'materi_ajar_file' => $file
                    );
                    return $data;
                })
                ->make(true);
    }


}
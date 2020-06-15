<?php

namespace App\Http\Controllers\Keuangan\SIM;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\Guru;
use App\Models\Rapb;
use App\Models\Semester;
use App\Models\Staff;
use App\Models\SubkategoriRapb;

use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class PengeluaranController extends BaseController
{
    public function viewMenuPengeluaran(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        
        return view('keuangan/sim/pengeluaran/view-menu-pengeluaran', compact('auth_data'));
    }

    public function viewMenuInput(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_subkategori = SubkategoriRapb::whereHas('kategori', function($q){
            $q->where('tipe_kategori_rapb', 2);
        })->get();

        return view('keuangan/sim/pengeluaran/view-menu-input', compact('auth_data', 'data_subkategori'));
    }

    public function viewMenuTarget(Request $request, $tahun_akademik_semester = null)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_semester = LibDataAkademik::fetchDataTahunAjaranSemester($auth_data);

        if(empty($tahun_akademik_semester)){
            $semester_aktif = LibDataAkademik::fetchDataSemesterAktif($auth_data);
            $tahun_akademik_semester = $semester_aktif->thn_akademik_semester;
        }

        return view('keuangan/sim/pengeluaran/view-menu-target', compact('auth_data', 'data_semester', 'tahun_akademik_semester'));
    }

    public function datatablesMenuTarget(Request $request){
        $input = (object) $request->input();

        $tahun = $input->tahun;
        $semester_mulai = Semester::where('kode_semester', $tahun.'1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun.'2')->first();

        $list_data = SubkategoriRapb::whereHas('kategori', function($q){
            $q->where('tipe_kategori_rapb', 2);
        });

        $list_rapb = Rapb::selectRaw('
                            nm_kategori_rapb, 
                            kode_subkategori_rapb,
                            rapb.id_subkategori_rapb,
                            nm_subkategori_rapb,
                            tipe_kategori_rapb,
                            dana_perkiraan_rapb')
                        ->join('subkategori_rapb', function($q){
                            $q->on('subkategori_rapb.id_subkategori_rapb', '=' ,'rapb.id_subkategori_rapb')
                                ->whereNull('subkategori_rapb.deleted_at');
                        })
                        ->join('kategori_rapb', function($q){
                            $q->on('kategori_rapb.id_kategori_rapb', '=' ,'subkategori_rapb.id_kategori_rapb')
                                ->whereNull('kategori_rapb.deleted_at');
                        })
                        ->where('id_semester_mulai', $semester_mulai->id_semester)
                        ->where('id_semester_selesai', $semester_selesai->id_semester)
                        ->get();

        return Datatables::of($list_data)
                    ->addColumn('target_rapb', function ($item) use ($list_rapb) {
                        if($rapb = $list_rapb->firstWhere('id_subkategori_rapb', $item->id_subkategori_rapb)){
                            return 'Rp'.number_format($rapb->dana_perkiraan_rapb);
                        }else{
                            return 'Ro0';
                        }
                    })
                    ->addColumn('action', function($item){
                        $data = array(
                            'id' => $item->id_subkategori_rapb
                        );
                        return $data;
                    })
                    ->make(true);
    }

    public function viewMenuEditTarget(Request $request, $tahun_akademik_semester, $id_subkategori_rapb){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $semester_mulai = Semester::where('kode_semester', $tahun_akademik_semester.'1')->first();
        $semester_selesai = Semester::where('kode_semester', $tahun_akademik_semester.'2')->first();

        if($item = Rapb::where(['id_subkategori_rapb' => $id_subkategori_rapb, 'id_semester_mulai' => $semester_mulai->id_semester, 'id_semester_selesai' => $semester_selesai->id_semester ])->first()){

        }else{
            $item = null;
        }

        $subkategori_rapb = SubkategoriRapb::find($id_subkategori_rapb);

        return view('keuangan/sim/pengeluaran/view-menu-input-target', compact('auth_data', 'item', 'semester_mulai', 'tahun_akademik_semester', 'subkategori_rapb'));
    }

    public function actionSaveEditTarget(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $validator = Validator::make($request->all(), [
            'tahun' =>'required',
            'subkategori' =>'required',
            'dana_perkiraan_rapb' =>'required'
        ]);
  
        if($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        }else{
            $now = Carbon::today();

            $tahun_akademik_semester = $input->tahun;
            $semester_mulai = Semester::where('kode_semester', $tahun_akademik_semester.'1')->first();
            $semester_selesai = Semester::where('kode_semester', $tahun_akademik_semester.'2')->first();
            DB::beginTransaction();
            try {
                
                if(!empty($input->id)){
                    $rapb = Rapb::find($id);
                }else{
                    $rapb = Rapb::where(['id_subkategori_rapb' => $input->subkategori, 'id_semester_mulai' => $semester_mulai->id_semester, 'id_semester_selesai' => $semester_selesai->id_semester ])->first();
                }

                if($rapb){
                    $rapb->updated_by          = $input->auth_data->pengguna->id_pengguna;
                }else{
                    $rapb = new Rapb;
                    $rapb->id_rapb = $input->auth_data->sekolah_data->prefix.strtotime($now).uniqid();

                    $rapb->id_semester_mulai            = $semester_mulai->id_semester;
                    $rapb->id_semester_selesai          = $semester_selesai->id_semester;
                    $rapb->id_subkategori_rapb          = $input->subkategori;
                    $rapb->tgl_rapb                     = $now->format('Y-m-d');
                    $rapb->prioritas_rapb               = 3;
                    $rapb->created_by          = $input->auth_data->pengguna->id_pengguna;

                    if($actor = Staff::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)->first()){
                        $rapb->id_unit_kerja                = $actor->id_unit_kerja;
                    }else if($actor = Guru::where('id_pengguna', $input->auth_data->pengguna->id_pengguna)->first()){
                        $rapb->id_unit_kerja                = $actor->id_unit_kerja;
                    }

                    if($kepala_unit_keuangan = Guru::join('pengguna', 'pengguna.id_pengguna', '=', 'guru.id_pengguna')
                            ->where('guru.jenis_jabatan', '=', 2)
                            ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                            ->first()){
                        $rapb->id_pengguna_kepala_unit      = $kepala_unit_keuangan->id_pengguna;
                    }else if($kepala_unit_keuangan = Staff::join('pengguna', 'pengguna.id_pengguna', '=', 'staff.id_pengguna')
                            ->where('staff.jenis_jabatan', '=', 2)
                            ->where('pengguna.id_sekolah', '=', $input->auth_data->pengguna->id_sekolah)
                            ->first()){
                        $rapb->id_pengguna_kepala_keuangan  = $kepala_unit_keuangan->id_pengguna;;
                    }
                }

                $rapb->dana_perkiraan_rapb = $input->dana_perkiraan_rapb;
                $rapb->save();

                DB::commit();
                return response()->json([
                    'status' => 202,
                    'status_text' => 'Success',
                    'path' => 'sim/pengeluaran/target',
                    'message' => 'Success'
                ]);
            } catch (\Exception $e) {
                DB::rollback();

                return response()->json([
                    'status' => 300,
                    'status_text' => 'Failed',
                    'message' => 'Failed'
                ]);
            }
        }
    }
}
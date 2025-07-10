<?php

namespace App\Http\Controllers\Pendidikan\DataAkademik;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\JadwalKegiatan as JadwalKegiatan;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class KalenderAkademikController extends BaseController
{

    /**
     * Instantiate a new KalenderAkademikController instance.
     */
    /*public function __construct()
    {
        setlocale(LC_ALL, 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');
    }*/

    public function viewKalenderAkademik(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data);

        return view('pendidikan/data-akademik/kalender-akademik/view-kalender-akademik', compact('auth_data', 'data_semester'));
    }

    public function actionViewSemesterKalenderAkademik(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $validator = Validator::make($request->all(), [
            'id_semester' => 'required'
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'data-akademik/kalender-akademik/view-semester/' . $input->id_semester
            ];
        }
    }

    public function viewSemesterKalenderAkademik(Request $request, $id_semester)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);

        return view('pendidikan/data-akademik/kalender-akademik/view-semester-kalender-akademik', compact('auth_data', 'data_semester'));
    }

    public function addKalenderAkademik(Request $request, $id_semester)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_kegiatan = LibDataAkademik::fetchDataKegiatan($auth_data);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);

        // mengambil waktu sekarang
        $now = Carbon::now();

        $id_jadwal_kegiatan = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

        return view('pendidikan/data-akademik/kalender-akademik/add-kalender-akademik', compact('auth_data', 'data_kegiatan', 'data_semester', 'id_jadwal_kegiatan'));
    }

    public function editKalenderAkademik(Request $request, $id_semester, $id)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_kegiatan = LibDataAkademik::fetchDataKegiatan($auth_data);

        $data_semester = LibDataAkademik::fetchDataNamaSemester($auth_data, $id_semester);

        $data_kalender_akademik = LibDataAkademik::fetchDataKalenderAkademik($auth_data, $id_semester, $id);

        // convert format date
        $tgl_mulai = strftime("%A, %d %B %Y", strtotime($data_kalender_akademik->tgl_mulai));
        $tgl_selesai = strftime("%A, %d %B %Y", strtotime($data_kalender_akademik->tgl_selesai));

        return view('pendidikan/data-akademik/kalender-akademik/edit-kalender-akademik', compact('auth_data', 'data_kegiatan', 'data_semester', 'data_kalender_akademik', 'tgl_mulai', 'tgl_selesai'));
    }

    public function datatablesKalenderAkademik(Request $request, $id_semester)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = LibDataAkademik::fetchDataKalenderAkademik($auth_data, $id_semester);

        return Datatables::of($list_data)
            ->addColumn('semester', function ($item) {
                return $item->tahun_ajaran . " " . $item->nm_semester;
            })
            ->addColumn('tgl_mulai', function ($item) {
                return strftime("%A, %d %B %Y", strtotime($item->tgl_mulai));
            })
            ->addColumn('tgl_selesai', function ($item) {
                return strftime("%A, %d %B %Y", strtotime($item->tgl_selesai));
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_jadwal_kegiatan
                );
                return $data;
            })
            ->make(true);
    }

    // Action POST
    public function actionKalenderAkademik(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            'id_kegiatan' => 'required',
            'id_semester' => 'required',
            'tgl_mulai' => 'required',
            'tgl_selesai' => 'required',
            'deskripsi' => 'nullable|string'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            // ACTION ADD
            if ($mode == 'add') {

                $jadwalKegiatan = JadwalKegiatan::where('id_kegiatan', '=', $input->id_kegiatan)->where('id_semester', '=', $input->id_semester)->first();

                if ($jadwalKegiatan) {
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Save Kalender Akademik!'
                    ];
                } else {
                    $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                    $jadwalKegiatan                             = new JadwalKegiatan;
                    $jadwalKegiatan->id_jadwal_kegiatan         = $id;
                    $jadwalKegiatan->id_kegiatan                = $input->id_kegiatan;
                    $jadwalKegiatan->id_semester                = $input->id_semester;
                    $jadwalKegiatan->deskripsi                  = $input->deskripsi;
                    // convert format date
                    $jadwalKegiatan->tgl_mulai                  = date_format(date_create($input->tgl_mulai), "Y-m-d");
                    $jadwalKegiatan->tgl_selesai                = date_format(date_create($input->tgl_selesai), "Y-m-d");
                    $jadwalKegiatan->created_by                 = auth_data()->pengguna->id_pengguna;
                    $jadwalKegiatan->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'data-akademik/kalender-akademik/view-semester/' . $input->id_semester,
                        'message' => 'Save Kalender Akademik Successfully'
                    ];
                }
            } elseif ($mode == 'edit') {
                // make object to find id
                $jadwalKegiatan                             = JadwalKegiatan::find($id);
                $jadwalKegiatan->id_kegiatan                = $input->id_kegiatan;
                $jadwalKegiatan->id_semester                = $input->id_semester;
                $jadwalKegiatan->deskripsi                  = $input->deskripsi;
                // convert format date
                $jadwalKegiatan->tgl_mulai                  = date_format(date_create($input->tgl_mulai), "Y-m-d");
                $jadwalKegiatan->tgl_selesai                = date_format(date_create($input->tgl_selesai), "Y-m-d");
                $jadwalKegiatan->updated_by                 = auth_data()->pengguna->id_pengguna;
                $jadwalKegiatan->updated_at                 = $now;
                $jadwalKegiatan->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'data-akademik/kalender-akademik/view-semester/' . $input->id_semester,
                    'message' => 'Update Kalender Akademik Successfully'
                ];
            } elseif ($mode == 'delete') {
                // make object to find id
                $jadwalKegiatan               = JadwalKegiatan::find($id);
                $jadwalKegiatan->deleted_by   = auth_data()->pengguna->id_pengguna;
                $jadwalKegiatan->save();

                $jadwalKegiatan->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Kalender Akademik Successfully'
                ];
            }
        }
    }
}

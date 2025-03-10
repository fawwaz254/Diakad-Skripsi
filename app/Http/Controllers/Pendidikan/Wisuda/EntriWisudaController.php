<?php

namespace App\Http\Controllers\Pendidikan\Wisuda;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\PengajuanWisuda as PengajuanWisuda;
use App\Models\Kelas;
use App\Models\Siswa as Siswa;
use App\Models\PeriodeWisuda as PeriodeWisuda;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use App\Imports\NomorIjasahImport;
use App\Libraries\Pendidikan\LibWisuda;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use DB;
use Session;
use Validator;

class EntriWisudaController extends BaseController
{

    public function viewEntriWisuda(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_periode_wisuda = LibWisuda::fetchDataPeriodeWisuda($auth_data);

        $kelas_calon_lulus = Kelas::orderBy('tingkat', 'desc')->first();
        $data_kelas = Kelas::where('tingkat', $kelas_calon_lulus->tingkat)->where('is_aktif', 1)->orderBy('nm_kelas')->get();

        return view('pendidikan/wisuda/entri-wisuda/view-entri-wisuda', compact('auth_data', 'data_periode_wisuda', 'data_kelas'));
    }

    public function actionViewDetailEntriWisuda(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $validator = Validator::make($request->all(), [
            'id_periode_wisuda' => 'required',
            'id_kelas' => 'required',
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            return [
                'status' => 204, // SUCCESS AND LOAD CONTENT
                'path' => 'wisuda/entri-wisuda/view-detail/' . $input->id_periode_wisuda . '/' . $input->id_kelas
            ];
        }
    }

    public function viewDetailEntriWisuda(Request $request, $id_periode_wisuda, $id_kelas)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_periode_wisuda = LibWisuda::fetchDataPeriodeWisuda($auth_data, $id_periode_wisuda);

        return view('pendidikan/wisuda/entri-wisuda/view-detail-entri-wisuda', compact('auth_data', 'id_periode_wisuda', 'data_periode_wisuda', 'id_kelas'));
    }

    public function inputEntriWisuda(Request $request, $id, $id_periode_wisuda, $id_kelas)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_pengajuan_wisuda = $this->fetchDataEntriWisudaDetail($auth_data, $id);

        // convert format date
        $tgl_pengajuan_wisuda = strftime("%d %B %Y %T", strtotime($data_pengajuan_wisuda->tgl_pengajuan_wisuda));
        if (!empty($data_pengajuan_wisuda->tgl_sk_kelulusan)) {
            $tgl_sk_kelulusan = strftime("%d %B %Y", strtotime($data_pengajuan_wisuda->tgl_sk_kelulusan));
        } else {
            $tgl_sk_kelulusan = "";
        }
        if (!empty($data_pengajuan_wisuda->tgl_kelulusan)) {
            $tgl_kelulusan = strftime("%d %B %Y", strtotime($data_pengajuan_wisuda->tgl_kelulusan));
        } else {
            $tgl_kelulusan = "";
        }

        return view('pendidikan/wisuda/entri-wisuda/input-entri-wisuda', compact('auth_data', 'data_pengajuan_wisuda', 'id_periode_wisuda', 'id_kelas', 'tgl_pengajuan_wisuda', 'tgl_sk_kelulusan', 'tgl_kelulusan'));
    }

    public function datatablesEntriWisuda(Request $request, $id_periode_wisuda, $id_kelas)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = $this->fetchDataEntriWisuda($auth_data, $id_periode_wisuda, $id_kelas);

        return Datatables::of($list_data)
            ->addColumn('nm_periode_wisuda', function ($item) {
                if (!empty($item->id_pengajuan_wisuda) && empty($item->id_periode_wisuda)) {
                    return "Diajukan Di Periode Lain";
                } else {
                    return $item->nm_periode_wisuda;
                }
            })
            ->addColumn('semester', function ($item) {
                if (!empty($item->id_pengajuan_wisuda) && empty($item->id_periode_wisuda)) {
                    return "Diajukan Di Periode Lain";
                } else {
                    return $item->tahun_ajaran . " " . $item->nm_semester;
                }
            })
            ->addColumn('tgl_pengajuan_wisuda', function ($item) {
                return strftime("%d %B %Y %T", strtotime($item->tgl_pengajuan_wisuda));
            })
            ->addColumn('status_biodata', function ($item) {
                if ($item->status_biodata == 0) {
                    return "Belum Lengkap";
                } else {
                    return "Lengkap";
                }
            })
            ->addColumn('status_lab', function ($item) {
                if ($item->status_lab == 0) {
                    return "Ada Tanggungan";
                } else {
                    return "Bebas Tanggungan";
                }
            })
            ->addColumn('status_perpus', function ($item) {
                if ($item->status_perpus == 0) {
                    return "Ada Tanggungan";
                } else {
                    return "Bebas Tanggungan";
                }
            })
            ->addColumn('status_ijasah', function ($item) {
                if ($item->status_ijasah == 0) {
                    return "Belum Cetak";
                } else {
                    return "Sudah Cetak";
                }
            })
            ->addColumn('nomor_sk_kelulusan', function ($item) {
                if (!empty($item->nomor_sk_kelulusan)) {
                    return $item->nomor_sk_kelulusan;
                } else {
                    return "-";
                }
            })
            ->addColumn('tgl_sk_kelulusan', function ($item) {
                if (!empty($item->tgl_sk_kelulusan)) {
                    return strftime("%d %B %Y", strtotime($item->tgl_sk_kelulusan));
                } else {
                    return "-";
                }
            })
            ->addColumn('nomor_ijasah', function ($item) {
                if (!empty($item->nomor_ijasah)) {
                    return $item->nomor_ijasah;
                } else {
                    return "-";
                }
            })
            ->addColumn('tgl_kelulusan', function ($item) {
                if (!empty($item->tgl_kelulusan)) {
                    return strftime("%d %B %Y", strtotime($item->tgl_kelulusan));
                } else {
                    return "-";
                }
            })
            ->addColumn('action', function ($item) {
                if (!empty($item->id_pengajuan_wisuda)) {
                    $data = array(
                        'id' => $item->id_pengajuan_wisuda,
                        'id_siswa' => $item->id_siswa,
                        'id_periode_wisuda' => $item->id_periode_wisuda,
                        'status_wisuda' => $item->status_wisuda
                    );
                } else {
                    $data = array(
                        'id' => null,
                        'id_siswa' => $item->id_siswa,
                        'id_periode_wisuda' => $item->id_periode_wisuda,
                        'status_wisuda' => $item->status_wisuda
                    );
                }

                return $data;
            })
            ->make(true);
    }

    public function fetchDataEntriWisudaDetail($auth_data, $id_pengajuan_wisuda)
    {
        $pengajuanWisuda = PengajuanWisuda::select('pengajuan_wisuda.id_pengajuan_wisuda', 'pengajuan_wisuda.id_periode_wisuda', 'periode_wisuda.nm_periode_wisuda', 'semester.tahun_ajaran', 'semester.nm_semester', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'pengajuan_wisuda.status_biodata', 'pengajuan_wisuda.status_lab', 'pengajuan_wisuda.status_perpus', 'pengajuan_wisuda.status_ijasah', 'pengajuan_wisuda.nomor_sk_kelulusan', 'pengajuan_wisuda.tgl_sk_kelulusan', 'pengajuan_wisuda.nomor_ijasah', 'pengajuan_wisuda.tgl_kelulusan', 'pengajuan_wisuda.tgl_pengajuan_wisuda', 'pengajuan_wisuda.status_wisuda')
            ->join('periode_wisuda', 'periode_wisuda.id_periode_wisuda', '=', 'pengajuan_wisuda.id_periode_wisuda')
            ->join('semester', 'semester.id_semester', '=', 'periode_wisuda.id_semester')
            ->join('siswa', 'siswa.id_siswa', '=', 'pengajuan_wisuda.id_siswa')
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            ->where('pengajuan_wisuda.id_pengajuan_wisuda', '=', $id_pengajuan_wisuda)
            ->first();

        return $pengajuanWisuda;
    }

    public function fetchDataEntriWisuda($auth_data, $id_periode_wisuda, $id_kelas)
    {

        $siswa = Siswa::select('pengajuan_wisuda.id_pengajuan_wisuda', 'siswa.id_siswa', 'periode_wisuda.id_periode_wisuda', 'periode_wisuda.nm_periode_wisuda', 'semester.tahun_ajaran', 'semester.nm_semester', 'siswa.nis_siswa', 'pengguna.nm_pengguna', 'kelas.nm_kelas', 'pengajuan_wisuda.status_biodata', 'pengajuan_wisuda.status_lab', 'pengajuan_wisuda.status_perpus', 'pengajuan_wisuda.status_ijasah', 'pengajuan_wisuda.nomor_sk_kelulusan', 'pengajuan_wisuda.tgl_sk_kelulusan', 'pengajuan_wisuda.nomor_ijasah', 'pengajuan_wisuda.tgl_kelulusan', 'pengajuan_wisuda.tgl_pengajuan_wisuda', 'pengajuan_wisuda.status_wisuda')
            ->join('pengajuan_wisuda', function ($join) {
                $join->on('pengajuan_wisuda.id_siswa', '=', 'siswa.id_siswa')
                    ->where('pengajuan_wisuda.status_wisuda', '<>', 3);
            })
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->join('status_pengguna', 'status_pengguna.id_status_pengguna', '=', 'pengguna.id_status_pengguna')
            ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            ->join('periode_wisuda', function ($join) use ($id_periode_wisuda) {
                if ($id_periode_wisuda != "0") {
                    $join->on('periode_wisuda.id_periode_wisuda', '=', 'pengajuan_wisuda.id_periode_wisuda')
                        ->where('periode_wisuda.id_periode_wisuda', '=', $id_periode_wisuda);
                } else {
                    $join->on('periode_wisuda.id_periode_wisuda', '=', 'pengajuan_wisuda.id_periode_wisuda');
                }
            })
            ->join('semester', 'semester.id_semester', '=', 'periode_wisuda.id_semester')
            ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->where('status_pengguna.aktif_status_pengguna', '=', 1)
            ->orderBy('kelas.tingkat', 'asc')
            ->orderBy('kelas.nm_kelas', 'asc')
            ->orderBy('siswa.nis_siswa', 'asc');

        if (!empty($id_kelas)) {
            $siswa = $siswa->where('kelas.id_kelas', $id_kelas);
        }
        $siswa = $siswa->get();

        return $siswa;
    }


    // Action POST
    public function actionEntriWisuda(Request $request, $mode, $id = null, $id_siswa = null, $id_periode_wisuda = null)
    {

        $input = (object) $request->input();

        $validator = Validator::make($request->all(), [
            // keperluan get url
            'id_kelas' => 'required',
            'id_periode_wisuda' => 'required'
            // ------------------
            /*'status_biodata'        => 'required',
            'status_lab'            => 'required',
            'status_perpus'         => 'required',
            'status_ijasah'         => 'required',
            'nomor_sk_kelulusan'    => 'required',
            'tgl_sk_kelulusan'      => 'required',
            'nomor_ijasah'          => 'required',
            'tgl_kelulusan'         => 'required'*/
        ]);

        if ($validator->fails()) {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now();

            // ACTION ADD
            if ($mode == 'input') {
                $pengajuanWisudaCek = PengajuanWisuda::where('id_pengajuan_wisuda', '=', $id)->where('status_wisuda', '=', 2)->first();

                if ($pengajuanWisudaCek) {
                    return [
                        'status' => 300, // FAILED
                        'message' => 'Failed To Entri Data Wisuda! Siswa Sudah Lulus!'
                    ];
                } else {
                    $pengajuanWisuda                        = PengajuanWisuda::find($id);
                    $pengajuanWisuda->status_biodata        = $input->status_biodata;
                    $pengajuanWisuda->status_lab            = $input->status_lab;
                    $pengajuanWisuda->status_perpus         = $input->status_perpus;
                    $pengajuanWisuda->status_ijasah         = $input->status_ijasah;
                    $pengajuanWisuda->nomor_sk_kelulusan    = $input->nomor_sk_kelulusan;
                    if (!empty($input->tgl_sk_kelulusan)) {
                        $pengajuanWisuda->tgl_sk_kelulusan      = date_format(date_create($input->tgl_sk_kelulusan), "Y-m-d");
                    }
                    $pengajuanWisuda->nomor_ijasah          = $input->nomor_ijasah;
                    if (!empty($input->tgl_kelulusan)) {
                        $pengajuanWisuda->tgl_kelulusan         = date_format(date_create($input->tgl_kelulusan), "Y-m-d");
                    }
                    $pengajuanWisuda->updated_by            = auth_data()->pengguna->id_pengguna;
                    $pengajuanWisuda->updated_at            = $now;
                    $pengajuanWisuda->save();

                    return [
                        'status' => 202, // SUCCESS AND LOAD CONTENT
                        'path' => 'wisuda/entri-wisuda/view-detail/' . $input->id_periode_wisuda . '/' . $input->id_kelas,
                        'message' => 'Entri Data Wisuda Successfully'
                    ];
                }
            }
        }
    }

    public function viewImportNomorIjasah(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('pendidikan/wisuda/entri-wisuda/import-nomor-ijasah', compact('auth_data'));
    }

    public function uploadNomorIjasah(Request $request)
    {
        if ($request->hasFile('file-excel')) {
            Excel::import(new NomorIjasahImport, $request->file('file-excel'));
            return [
                'status'     => 200, // FAILED
                'message'     => "Upload Sukses"
            ];;
        } else {
            return [
                'status'     => 300, // FAILED
                'message'     => "File Excel tidak ditemukan"
            ];
        }
    }
    public function downloadExcel()
    {
        $file = public_path() . "/excel/ExcelTemplateIjazahSKKelulusan.xls";
        ob_end_clean();
        ob_start();
        $headers = [
            'Content-Type' => 'application/xls',
        ];

        return response()->download($file, 'ExcelTemplate.xls', $headers);
    }
}

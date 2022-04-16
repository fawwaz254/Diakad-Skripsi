<?php

namespace App\Http\Controllers\Humas\MagangSiswa;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Models\PengambilanMagang as PengajuanSiswaMagang;
use App\Models\PeriodeMagang as PeriodeMagang;
use App\Models\StatusPengguna as StatusPengguna;
use App\Models\Siswa as Siswa;
use App\Models\Pengguna as Pengguna;
use App\Models\KomponenMagang;
use App\Models\PeraturanNilai as PeraturanNilai;
use App\Models\NilaiMagang as NilaiMagang;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use App\Libraries\Pendidikan\LibMagangSiswa;
use App\Libraries\Pendidikan\LibSiswa;
use App\Models\link_laporan_magang;
use Auth;
use DB;
use Session;
use Validator;


class LaporanMagangController extends BaseController
{

    public function viewLaporanMagang(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_periode_magang = LibMagangSiswa::fetchDataPeriodeMagang($auth_data);

        return view('humas/magang-siswa/laporan-magang/view-laporan-magang', compact('auth_data', 'data_periode_magang'));
    }

    public function openLink(Request $request, $id_rekanan_magang, $id_periode_magang)
    {
        $laporan_magang = link_laporan_magang::select('link_google_drive')->where('id_rekanan_magang', $id_rekanan_magang)
            ->where('id_periode_magang', $id_periode_magang)->first();
        $link = $laporan_magang->link_google_drive;


        return view('humas/magang-siswa/laporan-magang/exsternal-link', compact('link'));
        // dd($laporan_magang);
        // redirect()->to($laporan_magang->link_google_drive)->send();
        // // return redirect()->away($laporan_magang->link_google_drive);
    }

    public function datatablesLaporanMagang(Request $request)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $id_periode_magang = $input->id_periode_magang;

        $data = PengajuanSiswaMagang::with('periode', 'rekanan')
            ->select('id_rekanan_magang', 'id_periode_magang')
            ->groupBy('id_rekanan_magang', 'id_periode_magang')
            ->when($id_periode_magang, function ($q) use ($id_periode_magang) {
                $q->where('pengambilan_magang.id_periode_magang', $id_periode_magang);
            })
            ->get();


        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smkypm3taman') {


            return Datatables::of($data)->editColumn('nm_periode_magang', function ($item) {
                return $item->periode->nm_periode_magang;
            })
                ->editColumn('nm_rekanan_magang', function ($item) {
                    return $item->rekanan->nm_rekanan_magang;
                })
                ->addColumn('action', function ($item) {
                    $data = array(
                        'smkypm3taman' => true,
                        'id' => $item->id_pengambilan_magang,
                        'id_rekanan_magang' => $item->id_rekanan_magang,
                        'id_periode_magang' => $item->id_periode_magang,
                        'laporan' => link_laporan_googledrive($item->id_rekanan_magang, $item->id_periode_magang)
                    );
                    return $data;
                })->make(true);
        } else {

            return Datatables::of($data)->editColumn('nm_periode_magang', function ($item) {
                return $item->periode->nm_periode_magang;
            })
                ->editColumn('nm_rekanan_magang', function ($item) {
                    return $item->rekanan->nm_rekanan_magang;
                })
                ->addColumn('action', function ($item) {
                    $data = array(
                        //setelah migrate ubah jadi false
                        'smkypm3taman' => false,
                        'id' => $item->id_pengambilan_magang,
                        'id_rekanan_magang' => $item->id_rekanan_magang,
                        'id_periode_magang' => $item->id_periode_magang
                    );
                    return $data;
                })->make(true);
        }
    }


    public function viewInputLaporanMagang(Request $request, $id_rekanan_magang, $id_periode_magang)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('humas/magang-siswa/laporan-magang/view-laporan-input-magang', compact('auth_data', 'id_rekanan_magang', 'id_periode_magang'));
    }

    public function editInputLaporanMagang(Request $request, $id_rekanan_magang = null, $id_periode_magang = null)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $data = link_laporan_magang::where(['id_rekanan_magang' => $id_rekanan_magang, 'id_periode_magang' => $id_periode_magang])->first();
        // $data = link_laporan_magang::where('id_rekanan_magang',$id_rekanan_magang)->where('id_periode_magang', $id_periode_magang)->first();

        return view('humas/magang-siswa/laporan-magang/edit-laporan-input-magang', compact('auth_data', 'data'));
    }


    public function actionInputLaporanMagang(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();



        $validator = Validator::make($request->all(), [
            'link_google_drive' => 'required'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            // mengambil waktu sekarang
            $now = Carbon::now(env('APP_TIMEZONE', ''));

            if ($mode == 'add') {
                $id = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

                $linkLaporanMagang                                  = new link_laporan_magang();
                $linkLaporanMagang->link_laporan_magang_id          = $id;
                $linkLaporanMagang->id_rekanan_magang               = $input->id_rekanan_magang;
                $linkLaporanMagang->id_periode_magang               = $input->id_periode_magang;
                $linkLaporanMagang->link_google_drive               = $input->link_google_drive;
                $linkLaporanMagang->created_by                      = $input->auth_data->pengguna->id_pengguna;
                $linkLaporanMagang->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'magang-siswa/laporan-magang',
                    'message' => 'Save Link Laporan Magang Siswa successfully'
                ];
            } else if ($mode == 'edit') {
                $linkLaporanMagang          = link_laporan_magang::find($id);
                $linkLaporanMagang->link_google_drive               = $input->link_google_drive;
                $linkLaporanMagang->updated_by                       = $input->auth_data->pengguna->id_pengguna;
                $linkLaporanMagang->save();
                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'magang-siswa/laporan-magang',
                    'message' => 'Save Link Laporan Magang Siswa successfully'
                ];
            }
        }
    }
    public function actionDeleteLaporanLinkMagang(Request $request, $id_rekanan_magang, $id_periode_magang)
    {

        // make object to find id
        $linkLaporanMagang     = link_laporan_magang::where('id_rekanan_magang', $id_rekanan_magang)->where('id_periode_magang', $id_periode_magang)->first();

        $linkLaporanMagang->delete();

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data_periode_magang = LibMagangSiswa::fetchDataPeriodeMagang($auth_data);

        return view('humas/magang-siswa/laporan-magang/view-laporan-magang', compact('auth_data', 'data_periode_magang'));
    }


    public function printLaporanMagang(Request $request, $id_rekanan_magang, $id_periode_magang)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $data = PengajuanSiswaMagang::where(['id_rekanan_magang' => $id_rekanan_magang, 'id_periode_magang' => $id_periode_magang])->first();
        $list_komponen = KomponenMagang::where('id_periode_magang', $id_periode_magang)->get();

        $list_siswa = PengajuanSiswaMagang::join('siswa', 'siswa.id_siswa', '=', 'pengambilan_magang.id_siswa')
            ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
            ->join('rekanan_magang', 'rekanan_magang.id_rekanan_magang', '=', 'pengambilan_magang.id_rekanan_magang')
            ->join('periode_magang', 'periode_magang.id_periode_magang', '=', 'pengambilan_magang.id_periode_magang')
            ->join('semester', 'semester.id_semester', '=', 'periode_magang.id_semester')
            ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
            ->where('pengambilan_magang.id_periode_magang', '=', $id_periode_magang)
            ->where('pengguna.id_sekolah', '=', $auth_data->pengguna->id_sekolah)
            ->where('status_magang', 1)
            ->get();

        $list_nilai = NilaiMagang::select('nilai_magang.id_pengambilan_magang', 'nilai_magang.id_komponen_magang', 'nilai_magang.besar_nilai_magang', 'komponen_magang.urutan_komponen_magang')->join('komponen_magang', 'nilai_magang.id_komponen_magang', '=', 'komponen_magang.id_komponen_magang')
            ->where('komponen_magang.id_periode_magang', '=', $id_periode_magang)
            ->get();

        $nilai_magang_siswa = [];
        if ($list_siswa) {
            $nilai = $list_nilai->toArray();
            foreach ($nilai as $komponen => $nilaiMagang) {
                foreach ($nilaiMagang as $nama_komponen => $besarNilai) {
                    $nilai_magang_siswa[$nilaiMagang['id_pengambilan_magang'] . $nilaiMagang['id_komponen_magang']] = $nilaiMagang['besar_nilai_magang'];
                }
            }
        }

        return view('humas/magang-siswa/laporan-magang/print-laporan-magang', compact('data', 'list_komponen', 'list_siswa', 'nilai_magang_siswa'));
    }
}

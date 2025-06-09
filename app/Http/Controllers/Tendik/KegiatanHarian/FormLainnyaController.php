<?php

namespace App\Http\Controllers\Tendik\KegiatanHarian;

use App\Http\Controllers\Controller;
use App\Jobs\PengisianMonkes;
use App\Models\KegiatanHarian;
use App\Models\PengisianJawaban;
use App\Models\PengisianKegiatanHarian;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

use App\Models\Bulan;
// use App\Models\KegiatanHarian;
// use App\Models\PengisianKegiatanHarian;
// use App\Models\PengisianJawaban;
use App\Models\KegiatanHarianPertanyaan;
use App\Models\KegiatanHarianJawaban;
use App\Models\KegiatanHarianKategori;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
// use Yajra\Datatables\Datatables;

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
        $auth_data = auth_data();

        return view('tendik/kegiatan-harian/form-lainnya/view-list-form-lainnya', compact('auth_data'));
    }

    public function datatablesListFormLainnya(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = KegiatanHarian::where('nm_kegiatan_harian', '!=', 'Monitoring Kesehatan COV-19')->get();

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

    public function isiFormLainnya(Request $request, $id_form)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $kegiatan_harian = KegiatanHarian::with('kategori_pertanyaan', 'kategori_pertanyaan.pertanyaan', 'kategori_pertanyaan.pertanyaan.jawaban')->where('is_aktif', 1)->where('id_kegiatan_harian', $id_form)->first();
        $data_kegiatan_harian_kategori = $kegiatan_harian->kategori_pertanyaan;
        return view('tendik/kegiatan-harian/form-lainnya/view-add-form-lainnya', compact('auth_data', 'kegiatan_harian', 'data_kegiatan_harian_kategori'));
    }

    public function viewFormLainnya(Request $request, $id_form)
    {
        // dd($id_form);
        $input = (object) $request->input();
        $auth_data = auth_data();
        $kegiatan_harian  = KegiatanHarian::find($id_form);

        return view('tendik/kegiatan-harian/form-lainnya/view-form-lainnya', compact('auth_data', 'id_form', 'kegiatan_harian'));


        // dd($id_form);
    }

    public function postIsiFormLainnya(Request $request, $id_form, $mode)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        // dd($mode);


        // switch ($mode) {
        //     case 'add':
        //         $syarat = [];
        //         break;
        //     case 'delete':
        //         $syarat = [
        //             'id_pengisian_kegiatan_harian' => 'required',
        //         ];
        //         break;
        //     default:
        //         return;
        // }

        // $validator = Validator::make($request->all(), $syarat);

        // if ($validator->fails()) {
        //     return [
        //         'status' => 300, // FAILED
        //         'message' => $validator->errors()->first()
        //     ];
        // } else {


        $now = Carbon::now();
        // $start_1 = Carbon::createFromTimeString('00:00');
        // $end_1 = Carbon::createFromTimeString($end_monkes);

        // $start_2 = Carbon::createFromTimeString($start_monkes);
        // $end_2 = Carbon::createFromTimeString('23:59');

        if (!isset($input->jawaban_pertanyaan)) {
            return [
                'status' => 200, // SUCCESS AND LOAD CONTENT
                'path' => 'kegiatan-harian/form-lainnya/form/' . $id_form . '/isi',
                'message' => 'Pastikan Anda mengisi jawaban yang ada.'
            ];
        }

        if ($mode == 'add') {
            $pengisian_kegiatan_harian_id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

            // switch($request->segment(1)){
            //     case 'tendik':
            //         $status_join = 1; break;
            //     case 'guru':
            //         $status_join = 2; break;
            //     case 'siswa':
            //         $status_join = 3; break;
            //     default:
            //         $status_join = 0; break;
            // }

            $status_join = $auth_data->pengguna->status_join_table;
            if (!$status_join) $status_join = 0;

            // DB::beginTransaction();

            // try {
            $batch_insert_pengisian_jawaban = array();
            foreach ($input->jawaban_pertanyaan as $id_pertanyaan => $id_jawaban) {
                $kegiatan_harian_jawaban = KegiatanHarianJawaban::find($id_jawaban);
                $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();

                $batch_insert_pengisian_jawaban[] = array(
                    'id_pengisian_jawaban'            => $id,
                    'id_pengisian_kegiatan_harian'    => $pengisian_kegiatan_harian_id,
                    'id_kegiatan_harian_pertanyaan'   => $id_pertanyaan,
                    'id_kegiatan_harian_jawaban'      => $id_jawaban,
                    'isi_jawaban_text'                => !empty($input->jawaban_text[$id_jawaban]) ? $input->jawaban_text[$id_jawaban] : null,
                    'bobot_jawaban'                   => $kegiatan_harian_jawaban->bobot_jawaban,
                    'warna_keadaan'                   => $kegiatan_harian_jawaban->warna_keadaan,
                    'created_at'                      => $now,
                    'updated_at'                      => $now
                );
            }

            // $pengisian_jawaban_terbobot = PengisianJawaban::where('id_pengisian_kegiatan_harian', $pengisian_kegiatan_harian_id)->orderBy('bobot_jawaban', 'desc')->first();
            $pengisian_jawaban_terbobot = collect($batch_insert_pengisian_jawaban)->sortByDesc('bobot_jawaban')->first();

            if ($pengisian_jawaban_terbobot['bobot_jawaban'] == 0) {
                $status_pengisian = 1;
            } else if ($pengisian_jawaban_terbobot['bobot_jawaban'] < 5) {
                $status_pengisian = 3;
            } else {
                $status_pengisian = 2;
            }

            $insert_pengisian_kegiatan = array();
            // $pengisian_kegiatan_harian                                 = new PengisianKegiatanHarian;
            $insert_pengisian_kegiatan['id_pengisian_kegiatan_harian']   = $pengisian_kegiatan_harian_id;
            $insert_pengisian_kegiatan['id_pengguna_pengisi']            = auth_data()->pengguna->id_pengguna;
            $insert_pengisian_kegiatan['id_kegiatan_harian']            = $id_form;
            $insert_pengisian_kegiatan['status_join_table']              = $status_join;
            $insert_pengisian_kegiatan['warna_keadaan']                  = $pengisian_jawaban_terbobot['warna_keadaan'];
            $insert_pengisian_kegiatan['status_pengisian']               = $status_pengisian;
            $insert_pengisian_kegiatan['created_by']                     = auth_data()->pengguna->id_pengguna;
            // if ($now->between($start_1, $end_1)) {
            $insert_pengisian_kegiatan['tgl_pengisian']              = Carbon::today()->format('Y-m-d');
            // } else if ($now->between($start_2, $end_2)) {
            // $insert_pengisian_kegiatan['tgl_pengisian']              = Carbon::today()->addDays(1)->format('Y-m-d');
            // }

            // if ($status_pengisian == 2) {
            //     if ($status_join == 3) {
            //         $message = 'Menurut Duta Sehat, Anda disarankan istirahat di rumah. Pastikan tetap mematuhi protokol kesehatan, istirahat yg cukup dan konsumsi makanan yang tingkatkan imun.';
            //     } else {
            //         $message = 'Menurut Duta Sehat, Anda disarankan istirahat di rumah. Pastikan tetap mematuhi protokol kesehatan dan membuat pernyataaan lalu mengunggahnya.';
            //     }
            // } else if ($status_pengisian == 3) {
            //     $message = 'Alhamdulillah, Anda bisa melanjutkan aktivitas. Dengan catatan mohon untuk kegiatan spriritualnya ditingkatkan.';
            // } else {
            //     $message = 'Alhamdulillah, Anda bisa melanjutkan aktivitas. Pastikan tetap mematuhi protokol kesehatan.';
            // }

            PengisianMonkes::dispatch($insert_pengisian_kegiatan, $batch_insert_pengisian_jawaban);
            // DB::commit();

            return [
                'status' => 202, // SUCCESS AND LOAD CONTENT
                'path' => 'kegiatan-harian/form-lainnya/form/' . $id_form,
                'message' => 'Successfully'
            ];
            // } catch (\Exception $e) {
            //     DB::rollback();

            //     return [
            //         'status' => 300, // FAILED
            //         'message' => 'Oopss'
            //     ];
            // }

        } elseif ($mode == 'delete') {
            $pengisian_kegiatan_harian  = PengisianKegiatanHarian::where('id_pengisian_kegiatan_harian', $id_form)->first();
            $pengisian_jawaban          = PengisianJawaban::where('id_pengisian_kegiatan_harian', $id_form)->delete();

            $pengisian_kegiatan_harian->deleted_by   = auth_data()->pengguna->id_pengguna;
            $pengisian_kegiatan_harian->save();

            $pengisian_kegiatan_harian->delete();

            return [
                'status' => 203, // SUCCESS AND LOAD TABLE
                'message' => 'Delete Successfully'
            ];
        }
        // }
    }


    public function datatablesFormLainnya(Request $request, $id_form)
    {

        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = PengisianKegiatanHarian::where('id_kegiatan_harian', $id_form)->where('id_pengguna_pengisi', auth_data()->pengguna->id_pengguna)->with('pengguna_pengisi')->get();
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
            // ->editColumn('is_aktif', function ($item) {
            //     return $item->is_aktif == '1' ? 'Aktif' : 'Tidak Aktif';
            // })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_pengisian_kegiatan_harian
                );
                return $data;
            })
            ->make(true);
    }

    public function viewDetailFormLainnya(Request $request, $id = '-')
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $pengisian_kegiatan_harian = PengisianKegiatanHarian::with('pengguna_pengisi')->where('id_pengisian_kegiatan_harian', $id)->first();

        $data_pengisian_jawaban = PengisianJawaban::with('pertanyaan', 'jawaban')->where('id_pengisian_kegiatan_harian', $id)->get();

        return view('tendik/kegiatan-harian/form-lainnya/view-detail-form-lainnya', compact('auth_data', 'pengisian_kegiatan_harian', 'data_pengisian_jawaban'));
    }
}

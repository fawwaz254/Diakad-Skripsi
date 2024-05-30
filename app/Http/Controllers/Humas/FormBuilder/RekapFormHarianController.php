<?php

namespace App\Http\Controllers\Humas\FormBuilder;

use App\Http\Controllers\Controller;
use App\Models\Bulan;
use App\Models\DetailJawabanForm;
use App\Models\Form;
use App\Models\Guru;
use App\Models\JawabanForm;
use App\Models\Kelas;
use App\Models\Pengguna;
use App\Models\PertanyaanForm;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Carbon\CarbonPeriod;


class RekapFormHarianController extends Controller
{
    public function viewListRekapFormHarian(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('humas/form-builder/rekap-form-harian/view-list-rekap-form-harian', compact('auth_data'));
    }
    public function datatablesListRekapFormHarian(Request $request)
    {
        $list_data = Form::with('role', 'jawaban_form');
        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_form,
                    'is_harian' => $item->is_harian == '1' ? True : False,
                );
                return $data;
            })
            ->editColumn('is_harian', function ($item) {
                return $item->is_harian == '1' ? 'Harian' : 'Bebas';
            })
            ->editColumn('is_aktif', function ($item) {
                return $item->is_aktif == '1' ? 'Aktif' : 'Tidak Aktif';
            })
            ->addColumn('jumlah_jawaban', function ($item) {
                return $item->jawaban_form->count();
            })
            ->make(true);
    }

    public function viewRekapBulananFormHarian(Request $request, $id_form, $bulan = null, $tahun = null, $id_kelas = null, $id_pertanyaan = '0')
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $now = Carbon::now();

        $form = Form::with('pertanyaan_form')->find($id_form);
        $roles = $form->id_role;
        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
        }
        if ($roles == '15') {
            $data_pengguna = Pengguna::whereHas('role_pengguna', function ($q) {
                $q->where('id_role', 15);
            })->whereHas('status_pengguna', function ($q) {
                $q->where('aktif_status_pengguna', 1);
            })->orderBy('nm_pengguna')->get();

            $datas = [];
        } else if ($roles == '2') {

            $data_pengguna = Pengguna::has('guru')->whereHas('status_pengguna', function ($q) {
                $q->where('aktif_status_pengguna', 1);
            })->orderBy('nm_pengguna')->get();

            $datas = [];
        } else if ($roles == '3') {
            if (empty($id_kelas)) {
                $id_kelas = Kelas::where('is_aktif', '1')->first()->id_kelas;
            }

            $allKelas = Kelas::where('is_aktif', 1)->orderBy('tingkat')->orderBy('nm_kelas')->get();

            $data_pengguna = Pengguna::whereHas('status_pengguna', function ($q) {
                $q->where('aktif_status_pengguna', 1);
            })->whereHas('siswa', function ($q) use ($id_kelas) {
                $q->where('id_kelas', $id_kelas);
            })->orderBy('nm_pengguna')->get();

            $datas = [
                'id_kelas' => $id_kelas,
                'allKelas' => $allKelas
            ];
        }

        if (empty($tahun)) {
            $tahun = $now->year;
        }
        if (empty($bulan)) {
            $bulan = $now->month;
        }

        $jawaban_form = JawabanForm::with('detail_jawaban_form.pertanyaan_form')->where('id_form', $id_form)->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->whereIn('created_by', $data_pengguna->pluck('id_pengguna'))
            ->get();

        $dataJawaban = [];

        if ($id_pertanyaan == '0') {
            foreach ($jawaban_form as $j) {
                $date = Carbon::parse($j->created_at)->format('Y-m-d');
                $dataJawaban[$j->created_by . $date] = $j->id_jawaban_form;
            }
        } else {
            // $detail_jawaban_form = DetailJawabanForm::with('pertanyaan_form')->whereIn('created_by', $data_pengguna->pluck('id_pengguna'))->whereIn('id_jawaban_form', $jawaban_form->pluck('id_jawaban_form'))->where('id_pertanyaan_form', $id_pertanyaan)->get();
            foreach ($jawaban_form as $data_jawaban_form) {
                foreach ($data_jawaban_form->detail_jawaban_form as $j) {
                    if ($j->id_pertanyaan_form == $id_pertanyaan) {
                        $date = Carbon::parse($j->created_at)->format('Y-m-d');
                        if ($j->pertanyaan_form->jenis_pertanyaan == '4') {
                            $data_opsi = [];
                            $counter = 0;
                            $warna = ['#ff0000', '#FFA500', '#008000'];
                            if (!empty($j->jawaban)) {
                                $options = json_decode($j->jawaban, true);
                                $others = json_decode($j->jawaban_lainnya, true);
                                $opsi = json_decode($j->pertanyaan_form->options, true);
                                $colors = json_decode($j->pertanyaan_form->label_color, true);
                                $hasil = count($data_opsi) / count($opsi);
                                foreach ($options as $opsi) {
                                    $data_opsi[] = $opsi;
                                }
                                foreach ($others as $lainnya) {
                                    $data_opsi[] = $lainnya;
                                }
                                if ($hasil >= 0.5) {
                                    $data_warna = $warna[2];
                                } else if ($hasil < 0.5) {
                                    $data_warna = $warna[1];
                                } else {
                                    $data_warna = $warna[0];
                                }

                                $data = array(
                                    $data_opsi, $data_warna
                                );
                            }
                            $dataJawaban[$j->created_by . $date] = $data;
                        } else if ($j->pertanyaan_form->jenis_pertanyaan == '3') {

                            $warna = '#d4ffdf';
                            if (!empty($j->jawaban)) {
                                $options = json_decode($j->pertanyaan_form->options, true);
                                $colors = json_decode($j->pertanyaan_form->label_color, true);
                                foreach ($options as $key => $value) {
                                    if ($value == $j->jawaban) {
                                        $warna = $colors[$key];
                                    }
                                }
                            }

                            $dataJawaban[$j->created_by . $date] = [$j->jawaban, $warna];
                        } else {
                            $dataJawaban[$j->created_by . $date] = [$j->jawaban, '#d4ffdf'];
                        }
                    }
                }
            }
        }

        $list_pertanyaan = PertanyaanForm::where('id_form', $id_form)->orderBy('urutan', 'asc')->get();
        // $list_kelas = Kelas::where('is_aktif', 1)->orderBy('tingkat')->orderBy('nm_kelas')->get();
        $start_month = Carbon::create($tahun, $bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $bulan = Bulan::find($bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();

        return view('humas/form-builder/rekap-form-harian/view-detail-rekap-bulanan', compact('auth_data', 'form', 'datas', 'tahun', 'bulan', 'data_bulan', 'dates', 'start_month', 'end_month',  'jawaban_form', 'data_pengguna', 'dataJawaban', 'list_pertanyaan', 'id_pertanyaan'));
    }


    public function getDetailJawaban(Request $request)
    {
        $input = (object) $request->input();
        $jawaban_form = JawabanForm::with('detail_jawaban_form.pertanyaan_form')->find($input->id_jawaban_form);
        return $jawaban_form;
    }

    public function viewHarianFormHarian(Request $request, $id_form, $date = null,  $id_kelas = null)
    {

        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $form = Form::with('pertanyaan_form')->find($id_form);
        $list_pertanyaan = PertanyaanForm::where('id_form', $id_form)->orderBy('urutan', 'asc')->get();

        $roles = $form->id_role;
        if ($date !== '0' || $date == null) {
            $date = Carbon::now()->format('Y-m-d');
        }
        if ($roles == '15') {
            $data_pengguna = Pengguna::whereHas('role_pengguna', function ($q) {
                $q->where('id_role', 15);
            })->whereHas('status_pengguna', function ($q) {
                $q->where('aktif_status_pengguna', 1);
            })->orderBy('nm_pengguna')->get();

            $data = [];
        } else if ($roles == '2') {

            $data_pengguna = Pengguna::has('guru')->whereHas('status_pengguna', function ($q) {
                $q->where('aktif_status_pengguna', 1);
            })->orderBy('nm_pengguna')->get();

            $data = [];
        } else if ($roles == '3') {
            if (empty($id_kelas)) {
                $id_kelas = Kelas::where('is_aktif', '1')->first()->id_kelas;
            }

            $allKelas = Kelas::orderBy('tingkat')->where('is_aktif', 1)->orderBy('nm_kelas')->get();

            $data_pengguna = Pengguna::whereHas('status_pengguna', function ($q) {
                $q->where('aktif_status_pengguna', 1);
            })->whereHas('siswa', function ($q) use ($id_kelas) {
                $q->where('id_kelas', $id_kelas);
            })->orderBy('nm_pengguna')->get();
            $data = [
                'id_kelas' => $id_kelas,
                'allKelas' => $allKelas
            ];
        }


        $jawaban_form = JawabanForm::with('detail_jawaban_form.pertanyaan_form')->where('id_form', $id_form)
            // ->whereMonth('created_at', $bulan)
            // ->whereYear('created_at', $tahun)
            ->when($date !== '0', function ($q) use ($date) {
                $q->whereDate('created_at', $date);
            })
            ->whereIn('created_by', $data_pengguna->pluck('id_pengguna'))
            ->get();

        $dataJawaban = [];

        // dd($jawaban_form);

        // if ($id_pertanyaan == '0') {
        // foreach ($jawaban_form as $j) {
        //     $date = Carbon::parse($j->created_at)->format('Y-m-d');
        //     $dataJawaban[$j->created_by . $date] = $j->id_jawaban_form;
        // }
        // } else {
        // $detail_jawaban_form = DetailJawabanForm::with('pertanyaan_form')->whereIn('created_by', $data_pengguna->pluck('id_pengguna'))->whereIn('id_jawaban_form', $jawaban_form->pluck('id_jawaban_form'))->where('id_pertanyaan_form', $id_pertanyaan)->get();
        foreach ($jawaban_form as $data_jawaban_form) { // loop data jawaban form
            foreach ($data_jawaban_form->detail_jawaban_form as $j) { // loop data detail jawaban form
                // if ($j->id_pertanyaan_form == $id_pertanyaan) {
                // $date = Carbon::parse($j->created_at)->format('Y-m-d');
                if ($j->pertanyaan_form->jenis_pertanyaan == '4') { // jika jenis pertanyaan banyak opsi
                    //  handle jawaban
                    $data_opsi = [];
                    if (!empty($j->jawaban)) { // jika jawaban tidak kosong
                        $options = json_decode($j->jawaban, true); // decode jawaban
                        foreach ($options as $opsi) { // loop jawaban opsi
                            $data_opsi[] = $opsi; // masukkan jawaban ke array
                        }
                    }
                    // handle jawaban lainnya
                    if (!empty($j->jawaban_lainnya)) { // jika jawaban lainnya tidak kosong
                        $data_opsi[] = $j->jawaban_lainnya; // masukkan jawaban lainnya ke array
                    }
                    $dataJawaban[$j->created_by . $j->pertanyaan_form->id_pertanyaan_form] = $data_opsi;
                } else {
                    $dataJawaban[$j->created_by . $j->pertanyaan_form->id_pertanyaan_form] = $j->jawaban;
                }
            }
        }
        /*
        dd($dataJawaban);
        }
        }

        $list_kelas = Kelas::orderBy('tingkat')->where('is_aktif', 1)->orderBy('nm_kelas')->get();
        $start_month = Carbon::create($tahun, $bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $bulan = Bulan::find($bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();
        */

        return view('humas/form-builder/rekap-form-harian/view-detail-rekap-harian', compact('auth_data', 'form', 'data', 'jawaban_form', 'data_pengguna', 'dataJawaban', 'list_pertanyaan', 'date'));
    }
}

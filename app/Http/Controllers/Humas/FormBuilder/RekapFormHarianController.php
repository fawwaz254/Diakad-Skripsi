<?php

namespace App\Http\Controllers\Humas\FormBuilder;

use App\Http\Controllers\Controller;
use App\Models\Bulan;
use App\Models\DetailJawabanForm;
use App\Models\Form;
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
        $list_data = Form::where('is_harian', '1')->with('role', 'jawaban_form');
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

        if (empty($id_kelas)) {
            $id_kelas = Kelas::where('is_aktif', '1')->first()->id_kelas;
        }

        if (empty($tahun)) {
            $tahun = $now->year;
        }
        if (empty($bulan)) {
            $bulan = $now->month;
        }

        $data_pengguna = Pengguna::whereHas('status_pengguna', function ($q) {
            $q->where('aktif_status_pengguna', 1);
        })->whereHas('siswa', function ($q) use ($id_kelas) {
            $q->where('id_kelas', $id_kelas);
        })->orderBy('nm_pengguna')->get();

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
                            if (!empty($j->jawaban)) {
                                $options = json_decode($j->jawaban, true);
                                foreach ($options as $opsi) {
                                    $data_opsi[] = $opsi;
                                }
                            }
                            $dataJawaban[$j->created_by . $date] = $data_opsi;
                        } else {
                            $dataJawaban[$j->created_by . $date] = $j->jawaban;
                        }
                    }
                }
            }
        }

        $form = Form::with('pertanyaan_form')->find($id_form);
        $list_pertanyaan = PertanyaanForm::where('id_form', $id_form)->orderBy('urutan', 'asc')->get();
        // $list_kelas = Kelas::where('is_aktif', 1)->orderBy('tingkat')->orderBy('nm_kelas')->get();
        $start_month = Carbon::create($tahun, $bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $bulan = Bulan::find($bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();
        $allKelas = Kelas::where('is_aktif', 1)->orderBy('tingkat')->orderBy('nm_kelas')->get();

        return view('humas/form-builder/rekap-form-harian/view-detail-rekap-bulanan', compact('auth_data', 'form', 'id_kelas', 'tahun', 'bulan', 'data_bulan', 'allKelas', 'dates', 'start_month', 'end_month',  'jawaban_form', 'data_pengguna', 'dataJawaban', 'list_pertanyaan', 'id_pertanyaan'));
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

        if (empty($id_kelas)) {
            $id_kelas = Kelas::where('is_aktif', '1')->first()->id_kelas;
        }

        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
        }


        $data_pengguna = Pengguna::whereHas('status_pengguna', function ($q) {
            $q->where('aktif_status_pengguna', 1);
        })->whereHas('siswa', function ($q) use ($id_kelas) {
            $q->where('id_kelas', $id_kelas);
        })->orderBy('nm_pengguna')->get();

        $jawaban_form = JawabanForm::with('detail_jawaban_form.pertanyaan_form')->where('id_form', $id_form)
            // ->whereMonth('created_at', $bulan)
            // ->whereYear('created_at', $tahun)
            ->whereDate('created_at', $date)
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
        foreach ($jawaban_form as $data_jawaban_form) {
            foreach ($data_jawaban_form->detail_jawaban_form as $j) {
                // if ($j->id_pertanyaan_form == $id_pertanyaan) {
                // $date = Carbon::parse($j->created_at)->format('Y-m-d');
                if ($j->pertanyaan_form->jenis_pertanyaan == '4') {
                    $data_opsi = [];
                    if (!empty($j->jawaban)) {
                        $options = json_decode($j->jawaban, true);
                        foreach ($options as $opsi) {
                            $data_opsi[] = $opsi;
                        }
                    }
                    $dataJawaban[$j->created_by . $j->pertanyaan_form->id_pertanyaan_form] = $data_opsi;
                } else {
                    $dataJawaban[$j->created_by . $j->pertanyaan_form->id_pertanyaan_form] = $j->jawaban;
                }
            }
        }

        // dd($dataJawaban);
        // }
        // }

        $form = Form::with('pertanyaan_form')->find($id_form);
        $list_pertanyaan = PertanyaanForm::where('id_form', $id_form)->orderBy('urutan', 'asc')->get();
        // $list_kelas = Kelas::orderBy('tingkat')->where('is_aktif', 1)->orderBy('nm_kelas')->get();
        // $start_month = Carbon::create($tahun, $bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        // $end_month = Carbon::create($tahun, $bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        // $dates = CarbonPeriod::create($start_month, $end_month);

        // $bulan = Bulan::find($bulan);
        // $data_bulan = Bulan::orderBy('id_bulan')->get();
        $allKelas = Kelas::orderBy('tingkat')->where('is_aktif', 1)->orderBy('nm_kelas')->get();
        return view('humas/form-builder/rekap-form-harian/view-detail-rekap-harian', compact('auth_data', 'form', 'id_kelas',  'allKelas', 'jawaban_form', 'data_pengguna', 'dataJawaban', 'list_pertanyaan', 'date'));
    }
}

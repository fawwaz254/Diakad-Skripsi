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
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Carbon\CarbonPeriod;


class RekapFormHarianController extends Controller
{
    public function viewListRekapFormHarian(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('humas/form-builder/rekap-form-harian/view-list-rekap-form-harian', compact('auth_data'));
    }

    public function datatablesListRekapFormHarian(Request $request)
    {
        $list_data = Form::with('role')->withCount('jawaban_form');
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
                return $item->jawaban_form_count;
            })
            ->make(true);
    }

    public function viewRekapBulananFormHarian(Request $request, $id_form, $bulan = null, $tahun = null, $id_kelas = null, $id_pertanyaan = '0')
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $now = Carbon::now();

        $form = Form::find($id_form);
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

        $data_jawaban_form = JawabanForm::selectRaw('id_jawaban_form, created_at, created_by')
            ->where('id_form', $id_form)
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->whereIn('created_by', $data_pengguna->pluck('id_pengguna'))
            ->get();

        $dataJawaban = [];

        $nm_pertanyaan = '';

        if ($id_pertanyaan == '0') {
            foreach ($data_jawaban_form as $jawaban_form) {
                $date = Carbon::parse($jawaban_form->created_at)->format('Y-m-d');
                $dataJawaban[$jawaban_form->created_by . $date] = $jawaban_form->id_jawaban_form;
            }
        } else {
            $nm_pertanyaan = PertanyaanForm::find($id_pertanyaan)->nm_pertanyaan_form;

            $data_detail_jawaban = DetailJawabanForm::whereIn('id_jawaban_form', $data_jawaban_form->pluck('id_jawaban_form'))
                                    ->get();
            foreach ($data_detail_jawaban as $detail) {
                if ($detail->id_pertanyaan_form == $id_pertanyaan) {
                    $date = Carbon::parse($detail->created_at)->format('Y-m-d');
                    if ($detail->pertanyaan_form->jenis_pertanyaan == '4') {
                        $data_opsi = [];
                        $counter = 0;
                        $warna = ['#ff0000', '#FFA500', '#008000'];
                        if (!empty($detail->jawaban)) {
                            $options = json_decode($detail->jawaban, true);
                            $others = json_decode($detail->jawaban_lainnya, true);
                            $opsi = json_decode($detail->pertanyaan_form->options, true);
                            $colors = json_decode($detail->pertanyaan_form->label_color, true);
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
                                $data_opsi,
                                $data_warna
                            );
                        }
                        $dataJawaban[$detail->created_by . $date] = $data;
                    } else if ($detail->pertanyaan_form->jenis_pertanyaan == '3') {
                        $warna = '#d4ffdf';
                        if (!empty($detail->jawaban)) {
                            $options = json_decode($detail->pertanyaan_form->options, true);
                            $colors = json_decode($detail->pertanyaan_form->label_color, true);
                            foreach ($options as $key => $value) {
                                if ($value == $detail->jawaban) {
                                    $warna = $colors[$key];
                                }
                            }
                        }

                        $dataJawaban[$detail->created_by . $date] = [$detail->jawaban, $warna];
                    } else {
                        $dataJawaban[$detail->created_by . $date] = [$detail->jawaban, '#d4ffdf'];
                    }
                }
            }
        }

        $list_pertanyaan = PertanyaanForm::where('id_form', $id_form)->orderBy('urutan', 'asc')->get();
        $start_month = Carbon::create($tahun, $bulan, 1, 0, 0, 0, 'Asia/Jakarta');
        $end_month = Carbon::create($tahun, $bulan, 1, 23, 59, 0, 'Asia/Jakarta')->endOfMonth();
        $dates = CarbonPeriod::create($start_month, $end_month);

        $bulan = Bulan::find($bulan);
        $data_bulan = Bulan::orderBy('id_bulan')->get();

        return view('humas/form-builder/rekap-form-harian/view-detail-rekap-bulanan', compact('auth_data', 'form', 'datas', 'tahun', 'bulan', 'data_bulan', 'dates', 'start_month', 'end_month', 'data_pengguna', 'dataJawaban', 'list_pertanyaan', 'id_pertanyaan', 'nm_pertanyaan'));
    }

    public function getDetailJawaban(Request $request)
    {
        $input = (object) $request->input();
        $data_jawaban_form = DetailJawabanForm::with('pertanyaan_form')->where('id_jawaban_form', $input->id_jawaban_form)->get();

        return $data_jawaban_form;
    }

    public function viewHarianFormHarian(Request $request, $id_form, $date = null,  $id_kelas = null)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $form = Form::find($id_form);
        $list_pertanyaan = PertanyaanForm::where('id_form', $id_form)->orderBy('urutan', 'asc')->get();

        $roles = $form->id_role;
        if (!$date) {
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

        $data_jawaban_form = JawabanForm::selectRaw('id_jawaban_form, created_by')->where('id_form', $id_form)
            ->whereDate('created_at', $date)
            ->whereIn('created_by', $data_pengguna->pluck('id_pengguna'))
            ->get();

        $data_detail_jawaban = DetailJawabanForm::whereIn('id_jawaban_form', $data_jawaban_form->pluck('id_jawaban_form'))
            ->get();

        $dataJawaban = [];

        foreach ($data_detail_jawaban as $detail_jawaban) { // loop data detail jawaban form
            $pertanyaan_form = $list_pertanyaan->firstWhere('id_pertanyaan_form', $detail_jawaban->id_pertanyaan_form); // ambil pertanyaan form
            if ($pertanyaan_form->jenis_pertanyaan == '4') { // jika jenis pertanyaan banyak opsi
                //  handle jawaban
                $data_opsi = [];
                if (!empty($detail_jawaban->jawaban)) { // jika jawaban tidak kosong
                    $options = json_decode($detail_jawaban->jawaban, true); // decode jawaban
                    foreach ($options as $opsi) { // loop jawaban opsi
                        $data_opsi[] = $opsi; // masukkan jawaban ke array
                    }
                }
                // handle jawaban lainnya
                if (!empty($detail_jawaban->jawaban_lainnya)) { // jika jawaban lainnya tidak kosong
                    $data_opsi[] = $detail_jawaban->jawaban_lainnya; // masukkan jawaban lainnya ke array
                }
                $dataJawaban[$detail_jawaban->created_by . $detail_jawaban->pertanyaan_form->id_pertanyaan_form] = $data_opsi;
            } else {
                $dataJawaban[$detail_jawaban->created_by . $detail_jawaban->pertanyaan_form->id_pertanyaan_form] = $detail_jawaban->jawaban;
            }
        }

        return view('humas/form-builder/rekap-form-harian/view-detail-rekap-harian', compact('auth_data', 'form', 'data', 'data_pengguna', 'list_pertanyaan', 'dataJawaban', 'date', 'roles'));
    }

    public function exportRekapBulanan(Request $request, $id_form, $bulan = null, $tahun = null, $id_kelas = null, $id_pertanyaan = '0')
    {
        $auth_data = auth_data();
        $form = Form::with('pertanyaan_form')->findOrFail($id_form);
        $roles = $form->id_role;
        // dd($auth_data);

        if ($roles == '15') {
            $data_pengguna = Pengguna::whereHas('role_pengguna', fn($q) => $q->where('id_role', 15))
                ->whereHas('status_pengguna', fn($q) => $q->where('aktif_status_pengguna', 1))
                ->orderBy('nm_pengguna')
                ->get();
        } elseif ($roles == '2') {
            $data_pengguna = Pengguna::has('guru')
                ->whereHas('status_pengguna', fn($q) => $q->where('aktif_status_pengguna', 1))
                ->orderBy('nm_pengguna')
                ->get();
        } elseif ($roles == '3') {
            $id_kelas = $id_kelas ?? Kelas::where('is_aktif', 1)->first()->id_kelas;
            $data_pengguna = Pengguna::whereHas('status_pengguna', fn($q) => $q->where('aktif_status_pengguna', 1))
                ->whereHas('siswa', fn($q) => $q->where('id_kelas', $id_kelas))
                ->orderBy('nm_pengguna')
                ->get();
        }

        $jawaban_form = JawabanForm::with(['detail_jawaban_form.pertanyaan_form'])
            ->where('id_form', $id_form)
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->whereIn('created_by', $data_pengguna->pluck('id_pengguna'))
            ->get();

        $dataJawaban = [];
        foreach ($jawaban_form as $form_jawaban) {
            foreach ($form_jawaban->detail_jawaban_form as $j) {
                $date = \Carbon\Carbon::parse($j->created_at)->format('Y-m-d');
                $key = $j->created_by . $date;

                $jawaban = $j->jawaban;
                $decoded = json_decode($jawaban, true);

                if ((is_null($jawaban) || $jawaban === '' || $jawaban === '[]') && $j->jawaban_lainnya) {
                    $dataJawaban[$key][] = ' ' . $j->jawaban_lainnya;
                } elseif (is_array($decoded)) {
                    $dataJawaban[$key][] = ' ' . implode(', ', $decoded);
                } else {
                    $dataJawaban[$key][] = ' ' . $jawaban;
                }
            }
        }


        foreach ($dataJawaban as $key => $jawabans) {
            $flattened = [];
            foreach ($jawabans as $jawaban) {
                if (is_array($jawaban)) {
                    $flattened = array_merge($flattened, $jawaban);
                } else {
                    $flattened[] = $jawaban;
                }
            }
            $dataJawaban[$key] = $flattened;
        }
        // dd($dataJawaban);

        $bulan = Bulan::findOrFail($bulan);
        $dates = \Carbon\CarbonPeriod::create(
            \Carbon\Carbon::create($tahun, $bulan->id_bulan, 1),
            \Carbon\Carbon::create($tahun, $bulan->id_bulan, 1)->endOfMonth()
        );
        // dd($dates);

        return view('humas/form-builder/rekap-form-harian/export-rekap-bulanan', compact('auth_data', 'form', 'bulan', 'data_pengguna', 'dates', 'dataJawaban'));
    }
}

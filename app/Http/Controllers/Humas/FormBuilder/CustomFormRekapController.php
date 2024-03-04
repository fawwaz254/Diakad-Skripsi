<?php

namespace App\Http\Controllers\Humas\FormBuilder;

use App\Http\Controllers\Controller;
use App\Models\CustomForm;
use App\Models\CustomFormRespon;
use App\Models\CustomFormSheet;
use App\Models\Kelas;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class CustomFormRekapController extends Controller
{
    public function index(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        return view('humas\form-builder\rekap-custom-form\view-list-rekap-custom-form', compact('auth_data'));
    }

    public function indexDataTables(Request $request)
    {
        $list_data = CustomForm::with('role', 'form_komponen.form_respon')->get();

        return Datatables::of($list_data)
            ->addColumn('nm_role', function ($item) {
                return $item->id_role == 99 ? 'PUBLIC' : $item->role->nm_role;
            })
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_custom_form
                );
                return $data;
            })
            ->make(true);
    }

    public function show(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
        $form = CustomForm::with('form_komponen.form_respon', 'role')->findOrFail($id);

        $tabel_kolom = [
            [
                'data' => null,
                'searchable' => false,
                'orderable' => false,
                'className' => 'align-center'
            ],
        ];

        $tabel_kolom[] = [
            'data' => 'nm_pengguna',
            'name' => 'nm_pengguna'
        ];


        foreach ($form->form_komponen as $key => $col) {
            $filter_nama = str_replace(' ', '_', preg_replace('/\s+/', ' ', strtolower(trim($col->nm_custom_form_komponen . $key . $col->id_custom_form_komponen))));

            $data = [
                'data' => $filter_nama,
                'name' => $filter_nama,
            ];



            $tabel_kolom[] = $data;
        }
        if ($form->id_role == 3) {
            $kelas = Kelas::where('is_aktif', 1)->get();

            return view('humas\form-builder\rekap-custom-form\view-rekap-custom-form', compact('auth_data', 'form', 'kelas', 'tabel_kolom'));
        }
        return view('humas\form-builder\rekap-custom-form\view-rekap-custom-form', compact('auth_data', 'form', 'tabel_kolom'));
    }


    public function filterRekapDatatables(Request $request)
    {
        $pengguna = null;
        $kelas = Kelas::where('is_aktif', 1)->first()->id_kelas;
        $date = Carbon::parse($request->date);

        $form = CustomForm::findOrFail($request->id);

        $jenis = $form->jenis_custom_form;

        if (!is_null($form) && $form->id_role !== 99) {
            if ($form->id_role == 3) {
                $id_kelas = isset($request->id_kelas) ? $request->id_kelas : $kelas;
                $pengguna = Pengguna::whereHas('status_pengguna', function ($q) {
                    $q->where('aktif_status_pengguna', 1);
                })->whereHas('siswa', function ($q) use ($id_kelas) {
                    $q->where('id_kelas', $id_kelas);
                })->orderBy('nm_pengguna')->get();
            } else {
                $pengguna = Pengguna::whereHas('role_pengguna', function ($q) use ($form) {
                    $q->where('id_role', $form->id_role);
                })->whereHas('status_pengguna', function ($q) {
                    $q->where('aktif_status_pengguna', 1);
                })->orderBy('nm_pengguna')->get();
            }
        }

        if ($form->id_role != 99) {


            $rekap_tabel =  Datatables::of($pengguna)->addColumn('nm_pengguna', function ($item) {
                return $item->nm_pengguna;
            });


            foreach ($form->form_komponen as $key => $value) {

                $rekap_tabel->addColumn(str_replace(' ', '_', preg_replace('/\s+/', ' ', strtolower(trim($value->nm_custom_form_komponen . $key . $value->id_custom_form_komponen)))), function ($item) use ($value, $date, $jenis) {
                    $respon_first = CustomFormRespon::with('form_komponen')->where('id_custom_form_komponen', $value->id_custom_form_komponen)->where('created_by', $item->id_pengguna);
                    if ($jenis == 'harian' || $jenis == 'biasa') {
                        if ($jenis == 'harian') {
                            $respon_first = $respon_first->whereDate('created_at', $date->toDateString())->first();
                        } else {
                            $respon_first = $respon_first->whereDate('created_at', $date->toDateString())->get();
                            if ($respon_first->isEmpty()) {
                                return null;
                            } else {
                                return ['jenis' => 'biasa', 'data' => $respon_first];
                            }
                        }
                    } else if ($jenis == 'bulanan') {
                        $respon_first =  $respon_first->whereMonth('created_at', $date->format('m'))->whereYear('created_at', $date->format('Y'))->first();
                    }
                    if ($respon_first !== null) {
                        return $respon_first->form_komponen->tipe_custom_form_komponen == 'custom_ttd' ? 
                        [
                            'jenis' => 'image',
                            'data' => Storage::disk('spaces')->url($respon_first->respon) 
                        ] : json_decode($respon_first->respon);
                    } else {
                        return null;
                    }
                });
            }


            return $rekap_tabel->make(true);
        }
        $all_time_biasa = CustomFormSheet::where('id_custom_form', $form->id_custom_form);

        $rekap_tabel = Datatables::of($all_time_biasa)->addColumn('nm_pengguna', function ($items) {
            return $items->created_by;
        });
        foreach ($form->form_komponen as $key => $value) {
            $rekap_tabel->addColumn(str_replace(' ', '_', preg_replace('/\s+/', ' ', strtolower(trim($value->nm_custom_form_komponen . $key . $value->id_custom_form_komponen)))), function ($items) use ($value) {

                $jawa = CustomFormRespon::with('form_komponen')->where('id_custom_form_komponen', $value->id_custom_form_komponen)->where('id_custom_form_sheet', $items->id_custom_form_sheet)->first();
                $tipe = $jawa->form_komponen->tipe_custom_form_komponen;
                return $tipe == 'custom_ttd' || ($tipe == 'file_single' && (isset($jawa->form_komponen->komponen_settings['jenis_file']) && in_array($jawa->form_komponen->komponen_settings['jenis_file'],['png','jpg','svg','jpeg','image/*'])) ) ? 
                [
                    'jenis' => 'image',
                    'data' => json_decode($jawa->respon)
                ]
                : ($tipe == 'file_multiple' || $tipe == 'file_single' ? [
                    'jenis' => 'file',
                    'data' => json_decode($jawa->respon) 
                ] : json_decode($jawa->respon));
            });
        }
        return $rekap_tabel->make(true);
    }
}

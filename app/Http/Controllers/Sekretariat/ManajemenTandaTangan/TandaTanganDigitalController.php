<?php

namespace App\Http\Controllers\Sekretariat\ManajemenTandaTangan;

use App\Models\DokumenTandaTanganDigital;
use App\Models\UnitKerja;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use Auth;
use DB;
use Session;
use Validator;

class TandaTanganDigitalController extends BaseController
{
    public function viewTandaTanganDigital(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        return view('sekretariat/manajemen-tanda-tangan/tanda-tangan-digital/view-tanda-tangan-digital', compact('auth_data'));
    }
    public function addTandaTanganDigital(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();
        return view('sekretariat/manajemen-tanda-tangan/tanda-tangan-digital/add-tanda-tangan-digital', compact('auth_data'));
    }

    public function editTandaTanganDigital(Request $request, $id)
    {
        $input = (object) $request->input();
        $dokumen_tanda_tangan_digital = DokumenTandaTanganDigital::find($id);
        $auth_data = auth_data();
        $link_dokumen = Storage::disk('spaces')->url($dokumen_tanda_tangan_digital->link_dokumen);

        return view('sekretariat/manajemen-tanda-tangan/tanda-tangan-digital/edit-tanda-tangan-digital', compact('auth_data', 'dokumen_tanda_tangan_digital', 'link_dokumen'));
    }

    public function previewDocument(Request $request, $id)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $dokumen_tanda_tangan_digital = DokumenTandaTanganDigital::findOrFail($id);
        $kepala_sekolah = UnitKerja::with('guru.pengguna')->where('nm_unit_kerja', 'Pimpinan')->first();
        $link_dokumen = Storage::disk('spaces')->url($dokumen_tanda_tangan_digital->link_dokumen);

        return view('sekretariat/manajemen-tanda-tangan/tanda-tangan-digital/preview-dokumen-tanda-tangan-digital', compact('kepala_sekolah', 'auth_data', 'dokumen_tanda_tangan_digital', 'link_dokumen'));
    }

    public function datatablesTandaTanganDigital(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $list_data = DokumenTandaTanganDigital::get();

        return Datatables::of($list_data)
            ->addColumn('action', function ($item) {
                $data = array(
                    'id' => $item->id_tanda_tangan_digital
                );
                return $data;
            })
            ->make(true);
    }


    public function actionTandaTanganDigital(Request $request, $mode, $id = null)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        //mengambil waktu sekarang
        $now = Carbon::now();

        switch ($mode) {
            case 'add':
                $add_validator = ['perihal_dokumen' => 'required', 'isi_dokumen' => 'required', 'file' => 'mimes:docx,pdf|required|max:10000'];
                $validator = Validator::make($request->all(), $add_validator);

                if ($validator->fails()) {
                    return [
                        'status' => 300, // FAILED
                        'message' => $validator->errors()->first()
                    ];
                }

                $file = $request->file('file');
                $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                $singkat_sekolah = auth_data()->sekolah_data->nm_singkat_sekolah;
                $uploaded_file = Storage::disk('spaces')->putFile($singkat_sekolah . '/file-pengguna/' . $id, $file, 'public');
                $jumlah_dokumen = DokumenTandaTanganDigital::count();

                $dokumen_tanda_tangan_digital = new DokumenTandaTanganDigital;
                $dokumen_tanda_tangan_digital->id_tanda_tangan_digital = $id;
                $dokumen_tanda_tangan_digital->no_dokumen = $jumlah_dokumen + 1;
                $dokumen_tanda_tangan_digital->perihal_dokumen = $input->perihal_dokumen;
                $dokumen_tanda_tangan_digital->isi_dokumen = $input->isi_dokumen;
                $dokumen_tanda_tangan_digital->link_dokumen = $uploaded_file;
                $dokumen_tanda_tangan_digital->is_approve = 0;
                $dokumen_tanda_tangan_digital->created_by = auth_data()->pengguna->id_pengguna;
                $dokumen_tanda_tangan_digital->save();

                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'manajemen-tanda-tangan/tanda-tangan-digital',
                    'message' => 'Save Dokumen Tanda Tangan Successfully'
                ];
                break;
            case 'edit':
                $edit_validator = ['perihal_dokumen' => 'required', 'file' => 'mimes:docx,pdf|nullable|max:10000'];
                $validator = Validator::make($request->all(), $edit_validator);
                if ($validator->fails()) {
                    return [
                        'status' => 300, // FAILED
                        'message' => $validator->errors()->first()
                    ];
                }
                if ($dokumen_tanda_tangan_digital = DokumenTandaTanganDigital::where('id_tanda_tangan_digital', $id)->first()) {
                    $dokumen_tanda_tangan_digital->perihal_dokumen = $input->perihal_dokumen;
                    $dokumen_tanda_tangan_digital->isi_dokumen = $input->isi_dokumen;
                    $dokumen_tanda_tangan_digital->updated_by = auth_data()->pengguna->id_pengguna;

                    if ($request->hasFile('file')) {
                        $file = $request->file('file');
                        $id = auth_data()->sekolah_data->prefix . strtotime($now) . uniqid();
                        $singkat_sekolah = auth_data()->sekolah_data->nm_singkat_sekolah;
                        $uploaded_file = Storage::disk('spaces')->putFile($singkat_sekolah . '/file-pengguna/' . $id, $file, 'public');
                        $dokumen_tanda_tangan_digital->link_dokumen = $uploaded_file;
                    }
                    $dokumen_tanda_tangan_digital->save();
                }
                return [
                    'status' => 202, // SUCCESS AND LOAD CONTENT
                    'path' => 'manajemen-tanda-tangan/tanda-tangan-digital',
                    'message' => 'Save Dokumen Tanda Tangan Successfully'
                ];
                break;
            case 'delete':
                $dokumen_tanda_tangan_digital = DokumenTandaTanganDigital::find($id);
                $dokumen_tanda_tangan_digital->deleted_by = auth_data()->pengguna->id_pengguna;
                $dokumen_tanda_tangan_digital->save();
                Storage::disk('spaces')->delete($dokumen_tanda_tangan_digital->link_dokumen);
                $dokumen_tanda_tangan_digital->delete();

                return [
                    'status' => 203, // SUCCESS AND LOAD TABLE
                    'message' => 'Delete Dokumen Tanda Tangan Succesfully'
                ];
                break;
            default:
                # code...
                break;
        }
    }
}

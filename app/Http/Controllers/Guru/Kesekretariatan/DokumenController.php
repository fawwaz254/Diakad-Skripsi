<?php

namespace App\Http\Controllers\Guru\Kesekretariatan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Models\ArsipKategori as ArsipKategori;
use App\Models\UnitKerja as UnitKerja;
use App\Models\ArsipDokumen as ArsipDokumen;
use App\Models\ArsipDokumenFile as ArsipDokumenFile;
use App\Models\ArsipPemilik as ArsipPemilik;
use App\Models\ArsipLoker as ArsipLoker;
use App\Models\ArsipSubkategori as ArsipSubkategori;
use App\Models\StatusPengguna;
use App\Models\ArsipDokumenAkses;

use Auth;
use DB;
use Session;
use Validator;

class DokumenController extends BaseController
{
    public function viewDokumen(Request $request){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;
       
        return view('guru/kesekretariatan/dokumen/view-dokumen',compact('auth_data'));
    }

    public function viewDetailDokumen(Request $request, $id){
        # code...
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $dokumen = ArsipDokumen::select('arsip_dokumen.id_arsip_dokumen','arsip_dokumen.kode_katalog','arsip_dokumen.nm_arsip_dokumen','arsip_dokumen.nomor_arsip_dokumen','arsip_dokumen.jumlah_halaman','arsip_dokumen.tgl_penyusunan','arsip_dokumen.tgl_penyusunan','unit_kerja.nm_unit_kerja','arsip_loker.nm_arsip_loker','arsip_pemilik.nm_arsip_pemilik','arsip_subkategori.nm_arsip_subkategori','arsip_kategori.nm_arsip_kategori','arsip_dokumen.id_arsip_dokumen')
                                    ->leftJoin('unit_kerja','unit_kerja.id_unit_kerja','=','arsip_dokumen.id_unit_kerja')
                                    ->join('arsip_loker','arsip_loker.id_arsip_loker','=','arsip_dokumen.id_arsip_loker')
                                    ->join('arsip_pemilik','arsip_pemilik.id_arsip_pemilik','=','arsip_pemilik.id_arsip_pemilik')
                                    ->join('arsip_subkategori','arsip_subkategori.id_arsip_subkategori','=','arsip_dokumen.id_arsip_subkategori')
                                    ->join('arsip_kategori','arsip_kategori.id_arsip_kategori','=','arsip_subkategori.id_arsip_kategori')
                                    ->where('id_arsip_dokumen','=',$id)
                                    ->first();

        $arsip_dokumen_file = ArsipDokumenFile::where('id_arsip_dokumen','=',$id)->get();

        return view('guru/kesekretariatan/dokumen/view-detail-dokumen',compact('auth_data','dokumen','arsip_dokumen_file'));
    }

    public function datatablesDokumen(Request $request){
        $input = (object) $request->input();
        $auth_data = $input->auth_data;

        $list_data = ArsipDokumen::select('arsip_dokumen.id_arsip_dokumen','arsip_dokumen.id_arsip_loker','arsip_dokumen.id_arsip_pemilik','arsip_dokumen.id_arsip_subkategori','arsip_dokumen.id_unit_kerja','arsip_loker.nm_arsip_loker','arsip_pemilik.nm_arsip_pemilik','arsip_subkategori.nm_arsip_subkategori','arsip_kategori.nm_arsip_kategori','unit_kerja.nm_unit_kerja','arsip_dokumen.kode_katalog','arsip_dokumen.nm_arsip_dokumen','arsip_dokumen.nomor_arsip_dokumen','arsip_dokumen.jumlah_halaman','arsip_dokumen.tgl_penyusunan','arsip_dokumen.contact_person','arsip_dokumen.is_upload',
                                    DB::raw("(SELECT COUNT(*) FROM arsip_dokumen_file WHERE arsip_dokumen_file.id_arsip_dokumen = arsip_dokumen.id_arsip_dokumen AND arsip_dokumen_file.deleted_at IS NULL) AS jml_file"))
                                    ->join('arsip_loker','arsip_loker.id_arsip_loker','=','arsip_dokumen.id_arsip_loker')
                                    ->join('arsip_pemilik','arsip_pemilik.id_arsip_pemilik','=','arsip_dokumen.id_arsip_pemilik')
                                    ->join('arsip_subkategori','arsip_subkategori.id_arsip_subkategori','=','arsip_dokumen.id_arsip_subkategori')
                                    ->join('arsip_kategori','arsip_kategori.id_arsip_kategori','=','arsip_subkategori.id_arsip_kategori')
                                    ->leftJoin('unit_kerja','unit_kerja.id_unit_kerja','=','arsip_dokumen.id_unit_kerja');
        
        if($request->segment(1) == 'guru'){
            $list_data = $list_data->leftJoin('arsip_dokumen_akses', function($q){
                                $q->on('arsip_dokumen_akses.id_arsip_dokumen', '=', 'arsip_dokumen.id_arsip_dokumen')
                                    ->where('status_join_table', 2);
                            })
                            ->where('arsip_kategori.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                            ->where(function($q){
                                $q->where('is_publik', 1)
                                    ->orWhereNotNull('id_arsip_dokumen_akses');
                            });
        }else if($request->segment(1) == 'tendik'){
            $list_data = $list_data->leftJoin('arsip_dokumen_akses', function($q){
                                $q->on('arsip_dokumen_akses.id_arsip_dokumen', '=', 'arsip_dokumen.id_arsip_dokumen')
                                    ->where('status_join_table', 1);
                            })
                            ->where('arsip_kategori.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                            ->where(function($q){
                                $q->where('is_publik', 1)
                                    ->orWhereNotNull('id_arsip_dokumen_akses');
                            });
        }else if($request->segment(1) == 'siswa'){
            $list_data = $list_data->leftJoin('arsip_dokumen_akses', function($q){
                                $q->on('arsip_dokumen_akses.id_arsip_dokumen', '=', 'arsip_dokumen.id_arsip_dokumen')
                                    ->where('status_join_table', 3);
                            })
                            ->where('arsip_kategori.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                            ->where(function($q){
                                $q->where('is_publik', 1)
                                    ->orWhereNotNull('id_arsip_dokumen_akses');
                            });
        }else if($request->segment(1) == 'wali-murid'){
            $list_data = $list_data->leftJoin('arsip_dokumen_akses', function($q){
                                $q->on('arsip_dokumen_akses.id_arsip_dokumen', '=', 'arsip_dokumen.id_arsip_dokumen')
                                    ->where('status_join_table', 4);
                            })
                            ->where('arsip_kategori.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                            ->where(function($q){
                                $q->where('is_publik', 1)
                                    ->orWhereNotNull('id_arsip_dokumen_akses');
                            });
        }else if($request->segment(1) == 'pelatih-ekskul'){
            $list_data = $list_data->leftJoin('arsip_dokumen_akses', function($q){
                                $q->on('arsip_dokumen_akses.id_arsip_dokumen', '=', 'arsip_dokumen.id_arsip_dokumen')
                                    ->where('status_join_table', 5);
                            })
                            ->where('arsip_kategori.id_sekolah','=',$auth_data->pengguna->id_sekolah)
                            ->where(function($q){
                                $q->where('is_publik', 1)
                                    ->orWhereNotNull('id_arsip_dokumen_akses');
                            });
        }

        return Datatables::of($list_data)
                ->addColumn('action', function($item){
                    $data = array(
                        'id' => $item->id_arsip_dokumen
                    );
                    return $data;
                })
                ->make(true);
    }
}

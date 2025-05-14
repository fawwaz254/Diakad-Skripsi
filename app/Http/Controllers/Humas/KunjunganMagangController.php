<?php

namespace App\Http\Controllers\Humas;

use App\Http\Controllers\Controller;
use App\Models\KunjunganMagang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class KunjunganMagangController extends Controller
{
    public function index()
    {
        $fiveYearsAgo = Carbon::now()->subYears(3);
        $kunjungan_magang = KunjunganMagang::with('periode_magang', 'rekanan_magang')->where('created_at', '>=', $fiveYearsAgo)->get();
        return view('humas.magang-siswa.kunjungan-magang.view-kunjungan-magang', compact('kunjungan_magang'));
    }

    public function dataKunjunganMagang()
    {
        $fiveYearsAgo = Carbon::now()->subYears(3);
        $kunjungan_magang = KunjunganMagang::with('periode_magang', 'rekanan_magang')
            ->where('created_at', '>=', $fiveYearsAgo)
            ->orderBy('created_at', 'desc')
            ->get();

        return DataTables::of($kunjungan_magang)
            ->addIndexColumn()
            ->addColumn('periode_magang', function ($kunjungan_magang) {
                return $kunjungan_magang->periode_magang->nm_periode_magang;
            })
            ->addColumn('rekanan_magang', function ($kunjungan_magang) {
                return $kunjungan_magang->rekanan_magang->nm_rekanan_magang;
            })
            ->addColumn('action', function ($kunjungan_magang) {
                $id = $kunjungan_magang->id_kunjungan_magang;
                $imgUrl = Storage::disk('spaces')->url($kunjungan_magang->foto_kunjungan); // langsung pakai field
                return '
        <button type="button" class="btn btn-primary waves-effect"
            data-toggle="modal"
            data-target="#myModal' . $id . '">
            Lihat Foto
        </button>

        <!-- Modal -->
        <div class="modal fade" id="myModal' . $id . '" tabindex="-1" role="dialog" aria-labelledby="myModalLabel' . $id . '">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel' . $id . '">Foto Kunjungan</h4>
                    </div>
                    <div class="modal-body text-center">
                        <img class="img-thumbnail" style="max-width:100%;height:auto;"
                            src="' . $imgUrl . '" alt="Foto Kunjungan">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    ';
            })

            ->rawColumns(['action'])
            ->make(true);
    }
}

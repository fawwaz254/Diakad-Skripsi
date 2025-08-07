<?php

namespace App\Http\Controllers\Humas;

use App\Http\Controllers\Controller;
use App\Models\KunjunganMagang;
use App\Models\PeriodeMagang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class KunjunganMagangController extends Controller
{
    public function index()
    {
        $kunjungan_magang = KunjunganMagang::with('periode_magang', 'rekanan_magang')->get();
        $periode_magang = PeriodeMagang::where("is_aktif", 1)->orderBy('created_at', 'desc')->get();
        return view('humas.magang-siswa.kunjungan-magang.view-kunjungan-magang', compact('kunjungan_magang', 'periode_magang'));
    }

    public function dataKunjunganMagang(Request $request)
    {
        $kunjungan_magang = KunjunganMagang::with('periode_magang', 'rekanan_magang')
            ->orderBy('created_at', 'desc');


        if ($request->has('id_periode_magang') && $request->id_periode_magang != '') {
            $kunjungan_magang->where('id_periode_magang', $request->id_periode_magang);
        }

        $kunjungan_magang = $kunjungan_magang->get();



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

        <button type="button" class="btn btn-danger waves-effect"
            data-toggle="modal"
            data-target="#deleteModal' . $id . '">
            Hapus
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

    public function destroy($id)
{
    $data = KunjunganMagang::findOrFail($id);

    // Sementara: skip pengecekan file
    try {
        Storage::disk('spaces')->delete($data->foto_kunjungan);
    } catch (\Exception $e) {
        // Log error tapi lanjutkan
        \Illuminate\Support\Facades\Log::error("Gagal menghapus file: " . $e->getMessage());

    }

    $data->delete();

    return response()->json(['message' => 'Data berhasil dihapus']);
}


}

<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\KunjunganMagang;
use App\Models\PeriodeMagang;
use App\Models\RekananMagang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class MagangController extends Controller
{
    public function index()
    {
        $kunjungan_magang = KunjunganMagang::with('periode_magang', 'rekanan_magang')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guru.magang-siswa.index', compact('kunjungan_magang'));
    }

    public function dataKunjunganMagang()
    {
        $kunjungan_magang = KunjunganMagang::with('periode_magang', 'rekanan_magang')
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
        <a href="/guru#magang-siswa/list-kunjungan-magang/' . $id . '/edit" class="btn btn-warning waves-effect">Edit</a>
        <button class="btn btn-danger waves-effect" data-toggle="modal"
            data-target="#deleteModal' . $id . '">Hapus</button>

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

    public function create()
    {
        // dd($fiveYearsAgo);
        $id_sekolah_pengguna = Auth::user()->id_sekolah;

        $periode_magang = PeriodeMagang::where('is_aktif', 1)->orderBy('created_at', 'desc')->get();
        $rekanan_magang = RekananMagang::where('id_sekolah', $id_sekolah_pengguna)
            ->orderBy('created_at', 'desc')
            ->get();
        // return response()->json($rekanan_magang);
        // dd($rekanan_magang);

        return view('guru.magang-siswa.add-kunjungan-magang', compact('periode_magang', 'rekanan_magang'));
    }

    public function store(Request $request)
    {
        // return response()->json($request->all());

        $path = '';

        $periode_magang = $request->periode_magang;
        $rekanan_magang = $request->rekanan_magang;
        $keterangan_kunjungan = $request->keterangan_kunjungan;

        if (!$periode_magang || !$rekanan_magang || !$keterangan_kunjungan || !$request->hasFile('foto')) {
            return response()->json([
                'code' => 400,
                'status' => false,
                'message' => 'Semua input harus diisi!',
            ]);
        }

        $id_kunjungan_magang = Uuid::uuid4()->toString();
        $id_pengguna = Auth::user()->id_pengguna;
        $kunjungan_magang = new KunjunganMagang();
        $kunjungan_magang->id_kunjungan_magang = $id_kunjungan_magang;
        $kunjungan_magang->id_periode_magang = $request->periode_magang;
        $kunjungan_magang->id_rekanan_magang = $request->rekanan_magang;
        $kunjungan_magang->keterangan_kunjungan = $request->keterangan_kunjungan;
        $kunjungan_magang->created_by = $id_pengguna;
        $kunjungan_magang->updated_by = $id_pengguna;


        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('kunjungan_magang', 'spaces');
            $kunjungan_magang->foto_kunjungan = $path;
            $kunjungan_magang->save();
        } else {
            $kunjungan_magang->save();
        }

        return response()->json([
            'code' => 201,
            'status' => true,
            'path'  => "/guru#magang-siswa/list-kunjungan-magang",
            'message' => 'Berhasil menambahkan data kunjungan magang!',
        ]);
    }

    public function edit($id)
    {
        $kunjungan_magang = KunjunganMagang::where('id_kunjungan_magang', $id)->first();
        // dd($fiveYearsAgo);
        $id_sekolah_pengguna = Auth::user()->id_sekolah;

        $periode_magang = PeriodeMagang::where('is_aktif', 1)->orderBy('created_at', 'desc')->get();
        $rekanan_magang = RekananMagang::where('id_sekolah', $id_sekolah_pengguna)
            ->orderBy('created_at', 'desc')
            ->get();
        // return response()->json($rekanan_magang);
        // dd($rekanan_magang);

        return view('guru.magang-siswa.edit-kunjungan-magang', compact('periode_magang', 'kunjungan_magang', 'rekanan_magang'));

        // return view('guru.magang-siswa.edit-kunjungan-magang', compact('kunjungan_magang'));
    }

    public function update(Request $request, $id)
    {

        $periode_magang = $request->periode_magang;
        $rekanan_magang = $request->rekanan_magang;
        $keterangan_kunjungan = $request->keterangan_kunjungan;

        if (!$periode_magang || !$rekanan_magang || !$keterangan_kunjungan) {
            return response()->json([
                'code' => 400,
                'status' => false,
                'message' => 'Semua input harus diisi!',
            ]);
        }

        $id_pengguna = Auth::user()->id_pengguna;
        $kunjungan_magang = KunjunganMagang::where('id_kunjungan_magang', $id)->first();
        $kunjungan_magang->id_periode_magang = $request->periode_magang;
        $kunjungan_magang->id_rekanan_magang = $request->rekanan_magang;
        $kunjungan_magang->keterangan_kunjungan = $request->keterangan_kunjungan;
        $kunjungan_magang->updated_by = $id_pengguna;


        if ($request->hasFile('foto')) {
            if ($kunjungan_magang->foto_kunjungan && Storage::disk('spaces')->exists($kunjungan_magang->foto_kunjungan)) {
                Storage::disk('spaces')->delete($kunjungan_magang->foto_kunjungan);
            }
            $path = $request->file('foto')->store('kunjungan_magang', 'spaces');
            $kunjungan_magang->foto_kunjungan = $path;
            $kunjungan_magang->save();
        } else {
            $kunjungan_magang->save();
        }

        return response()->json([
            'code' => 201,
            'status' => true,
            'path'  => "/guru#magang-siswa/list-kunjungan-magang",
            'message' => 'Berhasil mengubah data kunjungan magang!',
        ]);
    }



    public function destroy($id)
    {
        // Ambil data berdasarkan ID
        $data = KunjunganMagang::where('id_kunjungan_magang', $id)->first();

        // Jika data tidak ditemukan
        if (!$data) {
            return response()->json([
                'code' => 404,
                'status' => false,
                'message' => 'Data kunjungan magang tidak ditemukan.',
            ], 404);
        }

        // Hapus file dari Spaces jika ada
        if ($data->foto_kunjungan && Storage::disk('spaces')->exists($data->foto_kunjungan)) {
            Storage::disk('spaces')->delete($data->foto_kunjungan);
        }

        // Hapus data dari database
        $data->delete();

        return response()->json([
            'code' => 200,
            'status' => true,
            'message' => 'Berhasil menghapus data kunjungan magang!',
        ]);
    }
}

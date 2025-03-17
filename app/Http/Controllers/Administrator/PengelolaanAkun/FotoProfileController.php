<?php

namespace App\Http\Controllers\Administrator\PengelolaanAkun;

use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FotoProfileController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);

        $pengguna = auth_data()->pengguna;

        if ($pengguna->path_foto_pengguna) {
            \Storage::disk('spaces')->delete($pengguna->path_foto_pengguna);
        }

        $path = $request->file('foto')->store('profiles', 'spaces');

        $pengguna->path_foto_pengguna = $path;
        $pengguna->save();

        if ($pengguna->status_join_table == 1 || $pengguna->status_join_table == 2) {
            return redirect("/tendik#biodata")->with('success', 'Foto profil berhasil diperbarui!');
        }

        return back()->with('error', 'Terjadi kesalahan!');
    }

    public function actionUpdateTdd(Request $request, $mode)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $now = Carbon::now();

        $validator = Validator::make($request->all(), [
            'ttd' => 'file|required|max:2048|mimes:jpg,jpeg,bmp,png'
        ]);

        if ($validator->fails() && $mode != 'delete') {
            return [
                'status' => 300, // FAILED
                'message' => $validator->errors()->first()
            ];
        } else {
            $singkat_sekolah = auth_data()->sekolah_data->nm_singkat_sekolah;
            $pengguna = auth_data()->pengguna->id_pengguna;
            $guru = Guru::where('id_pengguna', $pengguna)->first();
            if ($mode == 'add') {
                DB::beginTransaction();
                try {
                    $file = Storage::disk('spaces')->putFile($singkat_sekolah . '/guru/ttd' . $guru->id_guru, $request->file('ttd'), 'public');

                    $guru->path_foto_ttd = $file;
                    $guru->updated_by = $pengguna->id_pengguna;
                    $guru->save();
                    DB::commit();

                    // return [
                    //     'status' => 202, // SUCCESS AND LOAD CONTENT
                    //     'message' => 'Succes Upload foto ttd',
                    //     'path' => '/guru#biodata'
                    // ];
                    return redirect('/guru#biodata')->with('success', 'Success Upload foto ttd');
                } catch (\Exception $e) {
                    DB::rollback();
                    // return [
                    //     'status' => 300, // GAGAL
                    //     'message' => 'Gagal Upload foto ttd',
                    //     'path' => '/guru#biodata',
                    // ];
                    return redirect('/guru#biodata')->with('error', 'Gagal Upload foto ttd');
                }
            }
        }
    }
}

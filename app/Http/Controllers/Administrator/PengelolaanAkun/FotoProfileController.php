<?php

namespace App\Http\Controllers\Administrator\PengelolaanAkun;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
}


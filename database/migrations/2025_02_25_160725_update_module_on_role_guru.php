<?php

use App\Models\Modul;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateModuleOnRoleGuru extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $modul = Modul::where('nm_modul', 'Magang siswa')
            ->where('id_role', 19)
            ->orderBy('id_modul', 'DESC')
            ->first();

        if ($modul) {
            echo "Modul ditemukan" . "\n";
            $modul->update([
                'id_role' => 2, // guru
                'nm_modul' => 'Magang Siswa',
                'route' => 'magang-siswa',
                'urutan' => 2,
                'akses' => 1,
            ]);
        } else {
            echo "Tidak bisa update, Modul 'Magang Siswa' untuk role 19 tidak ditemukan" . "\n";
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

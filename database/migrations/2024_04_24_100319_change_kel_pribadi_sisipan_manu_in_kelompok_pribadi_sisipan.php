<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use App\Models\KelompokPribadiSisipan;
use App\Models\Sekolah;
use Carbon\Carbon;

class ChangeKelPribadiSisipanManuInKelompokPribadiSisipan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('kelompok_pribadi_sisipan', function (Blueprint $table) {
            DB::beginTransaction();
            try {
                $now = Carbon::now();
                $sekolah = Sekolah::first();

                if ($sekolah->nm_singkat_sekolah == 'manu') {
                    // KelompokPribadiSisipan::truncate();
                    KelompokPribadiSisipan::where('id_kelompok_pribadi_sisipan', 'like', '%%')->delete();

                    // Add new kelompok pribadi sisipan data
                    $kelompok_pribadi_sisipan = new KelompokPribadiSisipan();
                    $kelompok_pribadi_sisipan->id_kelompok_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
                    $kelompok_pribadi_sisipan->nm_kelompok_pribadi_sisipan = 'Ketidak Hadiran';
                    $kelompok_pribadi_sisipan->urutan = '1';
                    $kelompok_pribadi_sisipan->created_at = $now;
                    $kelompok_pribadi_sisipan->updated_at = $now;
                    $kelompok_pribadi_sisipan->save();

                    $kelompok_pribadi_sisipan = new KelompokPribadiSisipan();
                    $kelompok_pribadi_sisipan->id_kelompok_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
                    $kelompok_pribadi_sisipan->nm_kelompok_pribadi_sisipan = 'Ekstra Kurikuler';
                    $kelompok_pribadi_sisipan->urutan = '2';
                    $kelompok_pribadi_sisipan->created_at = $now;
                    $kelompok_pribadi_sisipan->updated_at = $now;
                    $kelompok_pribadi_sisipan->save();

                    $kelompok_pribadi_sisipan = new KelompokPribadiSisipan();
                    $kelompok_pribadi_sisipan->id_kelompok_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
                    $kelompok_pribadi_sisipan->nm_kelompok_pribadi_sisipan = 'Perminatan Khusus';
                    $kelompok_pribadi_sisipan->urutan = '3';
                    $kelompok_pribadi_sisipan->created_at = $now;
                    $kelompok_pribadi_sisipan->updated_at = $now;
                    $kelompok_pribadi_sisipan->save();
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();

                echo "Migration error: " . $e->getMessage() . "\n";
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kelompok_pribadi_sisipan', function (Blueprint $table) {
            //
        });
    }
}

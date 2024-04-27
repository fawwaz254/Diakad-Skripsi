<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use App\Models\KelompokPribadiSisipan;
use App\Models\PribadiSisipan;
use App\Models\Sekolah;
use App\Models\Ekskul;

use Carbon\Carbon;

class ChangeDataPribadiSisipanManuInPribadiSisipan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pribadi_sisipan', function (Blueprint $table) {
            DB::beginTransaction();
            try {

                $now = Carbon::now();
                $sekolah = Sekolah::first();

                if ($sekolah->nm_singkat_sekolah == 'manu') {
                    PribadiSisipan::where('id_pribadi_sisipan', 'like', '%%')->delete();

                    // urutan 1 (ketidak hadiran)
                    $kel_pribadi_sisipan1 = KelompokPribadiSisipan::where('urutan', 1)->first();
                    if ($kel_pribadi_sisipan1) {
                        // a. Sakit
                        $pribadi_sisipan = new PribadiSisipan;
                        $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
                        $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kel_pribadi_sisipan1->id_kelompok_pribadi_sisipan;
                        $pribadi_sisipan->nm_pribadi_sisipan = 'a. Sakit';
                        $pribadi_sisipan->urutan = '1';
                        $pribadi_sisipan->created_at = $now;
                        $pribadi_sisipan->updated_at = $now;
                        $pribadi_sisipan->save();
                        $pribadi_sisipan = null;

                        // b. Ijin
                        $pribadi_sisipan = new PribadiSisipan;
                        $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
                        $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kel_pribadi_sisipan1->id_kelompok_pribadi_sisipan;
                        $pribadi_sisipan->nm_pribadi_sisipan = 'b. Ijin';
                        $pribadi_sisipan->urutan = '2';
                        $pribadi_sisipan->created_at = $now;
                        $pribadi_sisipan->updated_at = $now;
                        $pribadi_sisipan->save();
                        $pribadi_sisipan = null;

                        // c. Tanpa Keterangan
                        $pribadi_sisipan = new PribadiSisipan;
                        $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
                        $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kel_pribadi_sisipan1->id_kelompok_pribadi_sisipan;
                        $pribadi_sisipan->nm_pribadi_sisipan = 'c. Tanpa Keterangan';
                        $pribadi_sisipan->urutan = '3';
                        $pribadi_sisipan->created_at = $now;
                        $pribadi_sisipan->updated_at = $now;
                        $pribadi_sisipan->save();
                        $pribadi_sisipan = null;
                    }

                    // urutan 2 (ekstra kurikuler)
                    $kel_pribadi_sisipan2 = KelompokPribadiSisipan::where('urutan', 2)->first();
                    if ($kel_pribadi_sisipan2) {
                        // Palang Merah Remaja
                        $pribadi_sisipan = new PribadiSisipan;
                        $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
                        $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kel_pribadi_sisipan2->id_kelompok_pribadi_sisipan;
                        $pribadi_sisipan->nm_pribadi_sisipan = 'Palang Merah Remaja';
                        $pribadi_sisipan->urutan = '1';
                        $pribadi_sisipan->created_at = $now;
                        $pribadi_sisipan->updated_at = $now;
                        $pribadi_sisipan->save();
                        $pribadi_sisipan = null;

                        // Karya Ilmiah Remaja
                        $pribadi_sisipan = new PribadiSisipan;
                        $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
                        $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kel_pribadi_sisipan2->id_kelompok_pribadi_sisipan;
                        $pribadi_sisipan->nm_pribadi_sisipan = 'Karya Ilmiah Remaja';
                        $pribadi_sisipan->urutan = '2';
                        $pribadi_sisipan->created_at = $now;
                        $pribadi_sisipan->updated_at = $now;
                        $pribadi_sisipan->save();
                        $pribadi_sisipan = null;

                        // Desain Grafis
                        $pribadi_sisipan = new PribadiSisipan;
                        $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
                        $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kel_pribadi_sisipan2->id_kelompok_pribadi_sisipan;
                        $pribadi_sisipan->nm_pribadi_sisipan = 'Desain Grafis';
                        $pribadi_sisipan->urutan = '3';
                        $pribadi_sisipan->created_at = $now;
                        $pribadi_sisipan->updated_at = $now;
                        $pribadi_sisipan->save();
                        $pribadi_sisipan = null;

                        // Tata Boga
                        $pribadi_sisipan = new PribadiSisipan;
                        $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
                        $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kel_pribadi_sisipan2->id_kelompok_pribadi_sisipan;
                        $pribadi_sisipan->nm_pribadi_sisipan = 'Tata Boga';
                        $pribadi_sisipan->urutan = '4';
                        $pribadi_sisipan->created_at = $now;
                        $pribadi_sisipan->updated_at = $now;
                        $pribadi_sisipan->save();
                        $pribadi_sisipan = null;

                        // Pagar Nusa
                        $pribadi_sisipan = new PribadiSisipan;
                        $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
                        $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kel_pribadi_sisipan2->id_kelompok_pribadi_sisipan;
                        $pribadi_sisipan->nm_pribadi_sisipan = 'Pagar Nusa';
                        $pribadi_sisipan->urutan = '5';
                        $pribadi_sisipan->created_at = $now;
                        $pribadi_sisipan->updated_at = $now;
                        $pribadi_sisipan->save();
                        $pribadi_sisipan = null;

                        // Al Banjari
                        $pribadi_sisipan = new PribadiSisipan;
                        $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
                        $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kel_pribadi_sisipan2->id_kelompok_pribadi_sisipan;
                        $pribadi_sisipan->nm_pribadi_sisipan = 'Al Banjari';
                        $pribadi_sisipan->urutan = '6';
                        $pribadi_sisipan->created_at = $now;
                        $pribadi_sisipan->updated_at = $now;
                        $pribadi_sisipan->save();
                        $pribadi_sisipan = null;

                        // Olahraga Tradisional
                        $pribadi_sisipan = new PribadiSisipan;
                        $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
                        $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kel_pribadi_sisipan2->id_kelompok_pribadi_sisipan;
                        $pribadi_sisipan->nm_pribadi_sisipan = 'Olahraga Tradisional';
                        $pribadi_sisipan->urutan = '7';
                        $pribadi_sisipan->created_at = $now;
                        $pribadi_sisipan->updated_at = $now;
                        $pribadi_sisipan->save();
                        $pribadi_sisipan = null;

                        // Tahfidz
                        $pribadi_sisipan = new PribadiSisipan;
                        $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
                        $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kel_pribadi_sisipan2->id_kelompok_pribadi_sisipan;
                        $pribadi_sisipan->nm_pribadi_sisipan = 'Tahfidz';
                        $pribadi_sisipan->urutan = '8';
                        $pribadi_sisipan->created_at = $now;
                        $pribadi_sisipan->updated_at = $now;
                        $pribadi_sisipan->save();
                        $pribadi_sisipan = null;
                    }

                    // urutan 3 (perminatan khusus)
                    $kel_pribadi_sisipan3 = KelompokPribadiSisipan::where('urutan', 3)->first();
                    if ($kel_pribadi_sisipan3) {
                        // Mapel Perminatan Khusus
                        $pribadi_sisipan = new PribadiSisipan;
                        $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
                        $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kel_pribadi_sisipan3->id_kelompok_pribadi_sisipan;
                        $pribadi_sisipan->nm_pribadi_sisipan = 'Mapel Perminatan Khusus';
                        $pribadi_sisipan->urutan = '1';
                        $pribadi_sisipan->created_at = $now;
                        $pribadi_sisipan->updated_at = $now;
                        $pribadi_sisipan->save();
                        $pribadi_sisipan = null;

                        // Nilai Perminatan Khusus
                        $pribadi_sisipan = new PribadiSisipan;
                        $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
                        $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kel_pribadi_sisipan3->id_kelompok_pribadi_sisipan;
                        $pribadi_sisipan->nm_pribadi_sisipan = 'Nilai Perminatan Khusus';
                        $pribadi_sisipan->urutan = '2';
                        $pribadi_sisipan->created_at = $now;
                        $pribadi_sisipan->updated_at = $now;
                        $pribadi_sisipan->save();
                        $pribadi_sisipan = null;
                    }
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
        Schema::table('pribadi_sisipan', function (Blueprint $table) {
            //
        });
    }
}

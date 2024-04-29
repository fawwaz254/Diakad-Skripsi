<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use App\Models\PribadiSisipan;
use App\Models\KelompokPribadiSisipan;
use App\Models\Sekolah;
use Carbon\Carbon;

class AddEkskulAsPribadiSisipanForManuInPribadiSisipan extends Migration
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
                    $kel_pribadi_sisipan2 = KelompokPribadiSisipan::where('urutan', 2)->first();
                    // Delete all data ekskul
                    PribadiSisipan::where('id_kelompok_pribadi_sisipan', $kel_pribadi_sisipan2->id_kelompok_pribadi_sisipan)->delete();

                    // Add new ekskul data
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

                        // Bulu Tangkis
                        $pribadi_sisipan = new PribadiSisipan;
                        $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
                        $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kel_pribadi_sisipan2->id_kelompok_pribadi_sisipan;
                        $pribadi_sisipan->nm_pribadi_sisipan = 'Bulu Tangkis';
                        $pribadi_sisipan->urutan = '9';
                        $pribadi_sisipan->created_at = $now;
                        $pribadi_sisipan->updated_at = $now;
                        $pribadi_sisipan->save();
                        $pribadi_sisipan = null;

                        // Futsal
                        $pribadi_sisipan = new PribadiSisipan;
                        $pribadi_sisipan->id_pribadi_sisipan = $sekolah->prefix . strtotime($now) . uniqid();
                        $pribadi_sisipan->id_kelompok_pribadi_sisipan = $kel_pribadi_sisipan2->id_kelompok_pribadi_sisipan;
                        $pribadi_sisipan->nm_pribadi_sisipan = 'Volley Ball';
                        $pribadi_sisipan->urutan = '10';
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

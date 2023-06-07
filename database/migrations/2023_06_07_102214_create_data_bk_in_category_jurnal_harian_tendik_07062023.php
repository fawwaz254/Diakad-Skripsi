<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CategoryJurnalHarianTendik;
use App\Models\Sekolah;
use App\Models\UnitKerja;
use Carbon\Carbon;


class CreateDataBkInCategoryJurnalHarianTendik07062023 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sekolah = Sekolah::first();
        $unit_kerja = UnitKerja::where('nm_unit_kerja', 'BK')->first();

        $now = Carbon::now(env('APP_TIMEZONE', ''));
        if ($unit_kerja) {
            $categoryJurnalHarianTendik = new CategoryJurnalHarianTendik;
            $categoryJurnalHarianTendik->id_category_jh_tendik = $sekolah->prefix . strtotime($now) . uniqid();
            $categoryJurnalHarianTendik->id_unit_kerja = $unit_kerja->id_unit_kerja;
            $categoryJurnalHarianTendik->description = 'Bimbingan Konseling';
            $categoryJurnalHarianTendik->created_by = 'Migration';
            $categoryJurnalHarianTendik->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('data_bk_in_category_jurnal_harian_tendik_07062023');
    }
}

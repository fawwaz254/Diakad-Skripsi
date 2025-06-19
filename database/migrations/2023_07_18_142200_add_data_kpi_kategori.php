<?php

use App\Models\KategoriKpi;
use App\Models\KomponenKpi;
use App\Models\Sekolah;
use App\Models\SubkategoriKpi;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataKpiKategori extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // $sekolah_data = Sekolah::first();
        // $now = Carbon::now();

        // $kategori = new KategoriKpi;
        // $kategori->id_kategori_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $kategori->nm_kategori = "A. Kecakapan Penerapan Ibadah";
        // $kategori->tingkat = 1;
        // $kategori->semester = 'Ganjil';
        // $kategori->save();

        // $subkategori = new SubkategoriKpi;
        // $subkategori->id_subkategori_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $subkategori->id_kategori_kpi = $kategori->id_kategori_kpi;
        // $subkategori->nm_subkategori = 'Menghafalkan dengan fasih dan benar.';
        // $subkategori->save();

        // $subkategori1 = new SubkategoriKpi;
        // $subkategori1->id_subkategori_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $subkategori1->id_kategori_kpi = $kategori->id_kategori_kpi;
        // $subkategori1->save();

        // $subkategori2 = new SubkategoriKpi;
        // $subkategori2->id_subkategori_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $subkategori2->id_kategori_kpi = $kategori->id_kategori_kpi;
        // $subkategori2->nm_subkategori = 'Menjelaskan dan mempraktekkan cara menyucikan :';
        // $subkategori2->save();

        // $subkategori4 = new SubkategoriKpi;
        // $subkategori4->id_subkategori_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $subkategori4->id_kategori_kpi = $kategori->id_kategori_kpi;
        // $subkategori4->save();

        // $subkategori5 = new SubkategoriKpi;
        // $subkategori5->id_subkategori_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $subkategori5->id_kategori_kpi = $kategori->id_kategori_kpi;
        // $subkategori5->save();

        // $subkategori3 = new SubkategoriKpi;
        // $subkategori3->id_subkategori_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $subkategori3->id_kategori_kpi = $kategori->id_kategori_kpi;
        // $subkategori3->save();

        // $subkategori6 = new SubkategoriKpi;
        // $subkategori6->id_subkategori_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $subkategori6->id_kategori_kpi = $kategori->id_kategori_kpi;
        // $subkategori6->nm_subkategori = 'Menyebutkan :';
        // $subkategori6->save();

        // $subkategori7 = new SubkategoriKpi;
        // $subkategori7->id_subkategori_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $subkategori7->id_kategori_kpi = $kategori->id_kategori_kpi;
        // $subkategori7->save();

        // $subkategori8 = new SubkategoriKpi;
        // $subkategori8->id_subkategori_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $subkategori8->id_kategori_kpi = $kategori->id_kategori_kpi;
        // $subkategori8->nm_subkategori = 'Menyebutkan hal - hal yang terlarang bagi orang :';
        // $subkategori8->save();

        // $komponen1 = new KomponenKpi;
        // $komponen1->id_komponen_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $komponen1->id_subkategori_kpi = $subkategori->id_subkategori_kpi;
        // $komponen1->nm_komponen = "1. Do'a awal belajar";
        // $komponen1->deskripsi_komponen = "menghafalkan secara fasih do'a awal belajar";
        // $komponen1->save();

        // $komponen2 = new KomponenKpi;
        // $komponen2->id_komponen_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $komponen2->id_subkategori_kpi = $subkategori->id_subkategori_kpi;
        // $komponen2->nm_komponen = "2. Do'a akhir Tatap Muka";
        // $komponen2->deskripsi_komponen = "menghafalkan secara fasih do'a akhir tatap muka dan ";
        // $komponen2->save();

        // $komponen3 = new KomponenKpi;
        // $komponen3->id_komponen_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $komponen3->id_subkategori_kpi = $subkategori->id_subkategori_kpi;
        // $komponen3->nm_komponen = "3. Do'a akhir belajar";
        // $komponen3->deskripsi_komponen = "menghafalkan secara fasih do'a akhir belajar.";
        // $komponen3->save();

        // $komponen4 = new KomponenKpi;
        // $komponen4->id_komponen_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $komponen4->id_subkategori_kpi = $subkategori1->id_subkategori_kpi;
        // $komponen4->nm_komponen = "Menjelaskan Macam-macam air";
        // $komponen4->deskripsi_komponen = "menjelaskan macam-macam air.";
        // $komponen4->save();

        // $komponen5 = new KomponenKpi;
        // $komponen5->id_komponen_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $komponen5->id_subkategori_kpi = $subkategori2->id_subkategori_kpi;
        // $komponen5->nm_komponen = "1. Najis mukhaffafah";
        // $komponen5->deskripsi_komponen = "menjelaskan dan mempraktikkan cara menyucikan najis mukhaffafah";
        // $komponen5->save();

        // $komponen6 = new KomponenKpi;
        // $komponen6->id_komponen_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $komponen6->id_subkategori_kpi = $subkategori2->id_subkategori_kpi;
        // $komponen6->nm_komponen = "2. Najis Mutawassithah";
        // $komponen6->deskripsi_komponen = "menjelaskan dan mempraktikkan cara menyucikan najis mutawasithah serta";
        // $komponen6->save();

        // $komponen7 = new KomponenKpi;
        // $komponen7->id_komponen_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $komponen7->id_subkategori_kpi = $subkategori2->id_subkategori_kpi;
        // $komponen7->nm_komponen = "3. Najis Mughalladhah";
        // $komponen7->deskripsi_komponen = "menjelaskan dan mempraktikkan cara menyucikan najis mughalladhah.";
        // $komponen7->save();

        // $komponen8 = new KomponenKpi;
        // $komponen8->id_komponen_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $komponen8->id_subkategori_kpi = $subkategori4->id_subkategori_kpi;
        // $komponen8->nm_komponen = "Menjelaskan cara istinja'";
        // $komponen8->deskripsi_komponen = "menjelaskan cara istinja.";
        // $komponen8->save();

        // $komponen9 = new KomponenKpi;
        // $komponen9->id_komponen_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $komponen9->id_subkategori_kpi = $subkategori5->id_subkategori_kpi;
        // $komponen9->nm_komponen = "1. Menyebutkan tatacara wudlu secara sempurna dan hal - hal yang membatalkannya";
        // $komponen9->deskripsi_komponen = "menyebutkan tatacara wudlu secara sempurna dan hal - hal yang membatalkannya";
        // $komponen9->save();

        // $komponen10 = new KomponenKpi;
        // $komponen10->id_komponen_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $komponen10->id_subkategori_kpi = $subkategori5->id_subkategori_kpi;
        // $komponen10->nm_komponen = "2. Mempraktekkan wudlu dan do'a setelah wudlu";
        // $komponen10->deskripsi_komponen = "mempraktikkan wudlu dan do'a setelah wudlu.";
        // $komponen10->save();

        // $komponen11 = new KomponenKpi;
        // $komponen11->id_komponen_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $komponen11->id_subkategori_kpi = $subkategori3->id_subkategori_kpi;
        // $komponen11->nm_komponen = "1. Menyebutkan tatacara tayammum secara sempurna dan hal - hal yang membatalkannya";
        // $komponen11->deskripsi_komponen = "menyebutkan tatacara tayammum secara sempurna dan hal - hal yang membatalkannya dan";
        // $komponen11->save();

        // $komponen12 = new KomponenKpi;
        // $komponen12->id_komponen_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $komponen12->id_subkategori_kpi = $subkategori3->id_subkategori_kpi;
        // $komponen12->nm_komponen = "2. Mempraktikkan tayammum  dan do'a setelahnya";
        // $komponen12->deskripsi_komponen = "mempraktikkan tayammum dan do'a setelah wudlu.";
        // $komponen12->save();

        // $komponen13 = new KomponenKpi;
        // $komponen13->id_komponen_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $komponen13->id_subkategori_kpi = $subkategori6->id_subkategori_kpi;
        // $komponen13->nm_komponen = "1. Hal - hal yang mewajibkan mandi besar.";
        // $komponen13->deskripsi_komponen = "menyebutkan hal - hal yang mewajibkan mandi besar,";
        // $komponen13->save();

        // $komponen14 = new KomponenKpi;
        // $komponen14->id_komponen_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $komponen14->id_subkategori_kpi = $subkategori6->id_subkategori_kpi;
        // $komponen14->nm_komponen = "2. fardlu dan sunnah mandi besar.";
        // $komponen14->deskripsi_komponen = "menyebutkan fardlu dan sunnah mandi besar dan";
        // $komponen14->save();

        // $komponen15 = new KomponenKpi;
        // $komponen15->id_komponen_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $komponen15->id_subkategori_kpi = $subkategori6->id_subkategori_kpi;
        // $komponen15->nm_komponen = "3. Melafalkan niat mandi besar bagi laki - laki dan perempuan";
        // $komponen15->deskripsi_komponen = "melafalkan niat mandi besar bagi laki - laki dan perempuan.";
        // $komponen15->save();

        // $komponen16 = new KomponenKpi;
        // $komponen16->id_komponen_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $komponen16->id_subkategori_kpi = $subkategori7->id_subkategori_kpi;
        // $komponen16->nm_komponen = "Mempraktikkan bersuci bagi pemakai pembalut luka pada anggota wudlu";
        // $komponen16->deskripsi_komponen = "mempraktikkan bersuci bagi pemakai pembalut luka pada anggota wudlu.";
        // $komponen16->save();

        // $komponen17 = new KomponenKpi;
        // $komponen17->id_komponen_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $komponen17->id_subkategori_kpi = $subkategori8->id_subkategori_kpi;
        // $komponen17->nm_komponen = "1. Berhadats kecil";
        // $komponen17->deskripsi_komponen = "menyebutkan hal - hal yang terlarang bagi orang berhadats kecil dan";
        // $komponen17->save();

        // $komponen18 = new KomponenKpi;
        // $komponen18->id_komponen_kpi = $sekolah_data->prefix . strtotime($now) . uniqid();
        // $komponen18->id_subkategori_kpi = $subkategori8->id_subkategori_kpi;
        // $komponen18->nm_komponen = "2. Berhadats besar";
        // $komponen18->deskripsi_komponen = "menyebutkan hal - hal yang terlarang bagi orang berhadats besar.";
        // $komponen18->save();
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

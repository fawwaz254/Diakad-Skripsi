<?php

namespace App\Console\Commands;

use App\Models\Ekskul;
use App\Models\KomponenEkskul;
use App\Models\MataPelajaran;
use App\Models\NilaiEkskul;
use App\Models\PengambilanEkskul;
use App\Models\RaporKelompokMp;
use App\Models\Sekolah;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\StandarNilai;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixDbData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-db-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fixing DB Data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $this->info('Fixing database data...');
            // $this->fixDbData2();
            $this->fixDbData8();
            $this->info('Fixing database data completed!');
            DB::commit();
        } catch(\Exception $e) {
            $this->error($e->getMessage());
            DB::rollBack();
        }
    }

    private function fixDbData1(){
        $this->info('Fixing table siswa. Swap/Fix NIS and NISN for siswa...');
        $siswaKelasXI_a = Siswa::where('thn_masuk_siswa', 2019)->where('nisn_siswa', '!=', 0)->get();
        foreach($siswaKelasXI_a as $x) {
            $x->nis_siswa = $x->nisn_siswa;
            $x->nisn_siswa = null;
            $x->save();
        }
        $this->info('...');
        
        $siswaKelasXI_b = Siswa::where('thn_masuk_siswa', 2019)->where('nisn_siswa', '=', 0)->get();
        foreach($siswaKelasXI_b as $x) {
            $x->nis_siswa = substr($x->nis_siswa, 0, 5);
            $x->nisn_siswa = null;
            $x->save();
        }
        $this->info('...');
        
        $siswaKelasX = Siswa::where('thn_masuk_siswa', 2020)->get();
        foreach($siswaKelasX as $x) {
            $x->nis_siswa = substr($x->nis_siswa, 0, 5);
            $x->nisn_siswa = null;
            $x->save();
        }
        $this->info('...');
    }

    private function fixDbData2(){
        $this->info('Fix Pengambilan Ekskul to All Siswa...');
        
        $semester = Semester::where('kode_semester', 20201)->where('id_sekolah', 'E3nJ115358553135b8b4ad12f588')->first();
        $pengambilanEkskulAll = PengambilanEkskul::get();
        foreach($pengambilanEkskulAll as $ekskul){
            $ekskul->id_semester = $semester->id_semester;
            $ekskul->save();
        }

    }
    
    private function fixDbData3(){
        $this->info('Insert Komponen Nilai Ekskul...');
        // $this->info('Insert Nilai Ekskul & Nilai Pengambilan Ekskul to All Siswa...');
        $semester = Semester::where('kode_semester', 20201)->where('id_sekolah', 'E3nJ115358553135b8b4ad12f588')->first();
        $ekskul = Ekskul::get();
        $sekolah_data = Sekolah::where('nm_singkat_sekolah', 'smkpemudakrian')->first();
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        foreach($ekskul as $x){
            $komponenEkskul = new KomponenEkskul();
            $komponenEkskul->id_komponen_ekskul = $sekolah_data->prefix.strtotime($now).uniqid();
            $komponenEkskul->id_ekskul = $x->id_ekskul;
            $komponenEkskul->id_semester = $semester->id_semester;
            $komponenEkskul->nm_komponen_ekskul = 'Komponen Ekskul 1';
            $komponenEkskul->persentase_komponen_ekskul = 100;
            $komponenEkskul->urutan_komponen_ekskul = 1;
            $komponenEkskul->save();
        }
    }
    
    private function fixDbData4(){
        $this->info('Insert Nilai Ekskul...');
        // $this->info('Insert Nilai Ekskul & Nilai Pengambilan Ekskul to All Siswa...');
        $semester = Semester::where('kode_semester', 20201)->where('id_sekolah', 'E3nJ115358553135b8b4ad12f588')->first();
        $ekskul = Ekskul::get();
        $dataSekolah = Sekolah::where('nm_singkat_sekolah', 'smkpemudakrian')->first();
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $paduanSuara = $ekskul->where('nm_ekskul', 'Paduan Suara')->where('id_sekolah', $dataSekolah->id_sekolah)->first();
        $hizbulWathan = $ekskul->where('nm_ekskul', 'Hizbul Wathan')->where('id_sekolah', $dataSekolah->id_sekolah)->first();
        $pengambilanEkskulAll = PengambilanEkskul::get();

        $nilaiPadus = [
            '12437' => 90, 
            '12442' => 90,
            '12459' => 90,
            '12468' => 90,
            '12439' => 90,
            '12440' => 90,
            '12441' => 90,
            '12452' => 90,
            '12453' => 90,
            '12460' => 90,
            '12472' => 90,
            '12474' => 90,
            '12475' => 90,
            '12477' => 90,
            '12480' => 90,
            '12482' => 90,
            '12484' => 90,
            '12486' => 90,
            '12487' => 90,
            '12490' => 90,
            '12492' => 90,
            '12493' => 90,
            '12498' => 90,
            '12501' => 90,
            '12502' => 90,
            '12507' => 90,
            '12518' => 90,
            '12406' => 100,
            '12411' => 100,
            '12425' => 90
        ];

        $ekskulPadus = $pengambilanEkskulAll->where('id_ekskul', $paduanSuara->id_ekskul);
        $this->info('Insert Nilai Ekskul ' . $paduanSuara->nm_ekskul);
        foreach($ekskulPadus as $x){
            $komponen = KomponenEkskul::where('id_ekskul', $x->id_ekskul)->get();
            $siswa = Siswa::findOrFail($x->id_siswa);

            $nilai = null;
            if(collect($nilaiPadus)->has($siswa->nis_siswa)){
                $nilai = $nilaiPadus[$siswa->nis_siswa];
            }

            foreach($komponen as $komp){
                $nilaiEkskul = new NilaiEkskul();
                $nilaiEkskul->id_nilai_ekskul = $dataSekolah->prefix.strtotime($now).uniqid();
                $nilaiEkskul->id_pengambilan_ekskul = $x->id_pengambilan_ekskul;
                $nilaiEkskul->id_komponen_ekskul = $komp->id_komponen_ekskul;
                $nilaiEkskul->besar_nilai_ekskul = $nilai;
                $nilaiEkskul->created_at = $now;
                $nilaiEkskul->save();
            }

            $predikat = ($nilai == 100) ? 'A' : 'B';

            $x->nilai_angka = $nilai;
            $x->nilai_huruf = $predikat;
            $x->save();
        }


        $nilaiHW = [
            '12334' => 80, 
            '12432' => 100,
            '12433' => 80,
            '12434' => 90,
            '12435' => 90,
            '12437' => 100,
            '12442' => 100,
            '12443' => 100,
            '12444' => 80,
            '12447' => 90,
            '12454' => 100,
            '12455' => 90,
            '12456' => 80,
            '12458' => 100,
            '12459' => 90,
            '12464' => 80,
            '12465' => 90,
            '12466' => 100,
            '12467' => 90,
            '12468' => 100,
            '12469' => 90, //
            '12436' => 80,
            '12438' => 90,
            '12439' => 90,
            '12440' => 80,
            '12441' => 100,
            '12445' => 80,
            '12446' => 100,
            '12448' => 100,
            '12449' => 90,
            '12450' => 90,
            '12451' => 80,
            '12452' => 100,
            '12453' => 100,
            '12457' => 80,
            '12460' => 100,
            '12461' => 80,
            '12462' => 80,
            '12463' => 100,
            '12470' => 80,
            '12471' => 80,
            '12472' => 80,
            '12473' => 100, //
            '12474' => 100,
            '12475' => 90,
            '12476' => 80,
            '12477' => 90,
            '12478' => 100,
            '12479' => 100,
            '12480' => 90,
            '12481' => 100,
            '12482' => 90,
            '12483' => 100,
            '12484' => 90,
            '12485' => 100,
            '12486' => 100,
            '12487' => 90,
            '12488' => 90,
            '12489' => 100,
            '12490' => 90,
            '12491' => 100,
            '12492' => 100,
            '12493' => 100,
            '12494' => 100,
            '12495' => 100,
            '12496' => 80,
            '12497' => 90, //
            '12498' => 100,
            '12499' => 100,
            '12500' => 100,
            '12501' => 100,
            '12502' => 100,
            '12503' => 90,
            '12504' => 100,
            '12505' => 90,
            '12506' => 100,
            '12507' => 100,
            '12508' => 100,
            '12509' => 100,
            '12510' => 80,
            '12511' => 90,
            '12512' => 80,
            '12513' => 90,
            '12514' => 90,
            '12515' => 80,
            '12516' => 80,
            '12517' => 100,
            '12518' => 100,
            '12519' => 90,
            '12364' => 90,
            '12366' => 80,
            '12322' => 80,
            '12252' => 90,
            '12290' => 90,
            '12191' => 80,
            '12194' => 100,
            '12195' => 100,
            '12196' => 90,
            '12198' => 80,
            '12202' => 80,
            '12207' => 90,
            '12216' => 90,
            '12218' => 90,
            '12225' => 80,
            '12230' => 80,
            '12231' => 80,
        ];


        $ekskulHW = $pengambilanEkskulAll->where('id_ekskul', $hizbulWathan->id_ekskul);
        $this->info('Insert Nilai Ekskul ' . $hizbulWathan->nm_ekskul);
        foreach($ekskulHW as $x){
            $komponen = KomponenEkskul::where('id_ekskul', $x->id_ekskul)->get();
            $siswa = Siswa::findOrFail($x->id_siswa);

            $nilai = null;
            if(collect($nilaiHW)->has($siswa->nis_siswa)){
                $nilai = $nilaiHW[$siswa->nis_siswa];
                
                foreach($komponen as $komp){
                    $nilaiEkskul = new NilaiEkskul();
                    $nilaiEkskul->id_nilai_ekskul = $dataSekolah->prefix.strtotime($now).uniqid();
                    $nilaiEkskul->id_pengambilan_ekskul = $x->id_pengambilan_ekskul;
                    $nilaiEkskul->id_komponen_ekskul = $komp->id_komponen_ekskul;
                    $nilaiEkskul->besar_nilai_ekskul = $nilai;
                    $nilaiEkskul->created_at = $now;
                    $nilaiEkskul->save();
                }
                if($nilai == 100){
                    $predikat = 'A';
                } elseif($nilai == 90){
                    $predikat = 'B';
                } else {
                    $predikat = 'C';
                }
                
                $x->nilai_angka = $nilai;
                $x->nilai_huruf = $predikat;
                $x->save();
            }
        }

    }

    private function fixDbData5(){
        $this->info('Insert Nilai Ekskul...');
        // $this->info('Insert Nilai Ekskul & Nilai Pengambilan Ekskul to All Siswa...');
        $semester = Semester::where('kode_semester', 20201)->where('id_sekolah', 'E3nJ115358553135b8b4ad12f588')->first();
        $ekskul = Ekskul::get();
        $dataSekolah = Sekolah::where('nm_singkat_sekolah', 'smkpemudakrian')->first();
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $animasi = $ekskul->where('nm_ekskul', 'Animasi')->where('id_sekolah', $dataSekolah->id_sekolah)->first();
        $coding = $ekskul->where('nm_ekskul', 'Coding App')->where('id_sekolah', $dataSekolah->id_sekolah)->first();
        $microStock = $ekskul->where('nm_ekskul', 'Microstock')->where('id_sekolah', $dataSekolah->id_sekolah)->first();
        $paskib = $ekskul->where('nm_ekskul', 'Paskibraka')->where('id_sekolah', $dataSekolah->id_sekolah)->first();
        $voly = $ekskul->where('nm_ekskul', 'Voly')->where('id_sekolah', $dataSekolah->id_sekolah)->first();
        $qiroah = $ekskul->where('nomor_sk_ekskul', 'qiroah-01')->where('id_sekolah', $dataSekolah->id_sekolah)->first();
        $pengambilanEkskulAll = PengambilanEkskul::get();

        $nilaiPaskib = [
            '12324' => 100, 
            '12325' => 100,
            '12326' => 100,
            '12336' => 90,
            '12339' => 100,
            '12342' => 100,
            '12352' => 100,
            '12356' => 100,
            '12358' => 90,
            '12322' => 100,
            '12335' => 100,
            '12343' => 100,
            '12191' => 90,
            '12194' => 90,
            '12195' => 90,
            '12196' => 90,
            '12202' => 100,
            '12203' => 90,
            '12231' => 90
        ];
        $i = 0;
        $ekskulAnimasi = $pengambilanEkskulAll->where('id_ekskul', $animasi->id_ekskul);
        $this->info('Insert Nilai Ekskul ' . $animasi->nm_ekskul);
        foreach($ekskulAnimasi as $x){
            $komponen = KomponenEkskul::where('id_ekskul', $x->id_ekskul)->get();
            $siswa = Siswa::findOrFail($x->id_siswa);

            $nilai = null;
            if(collect($nilaiPaskib)->has($siswa->nis_siswa)){
                $nilai = $nilaiPaskib[$siswa->nis_siswa];
            }

            foreach($komponen as $komp){
                $nilaiEkskul = new NilaiEkskul();
                $nilaiEkskul->id_nilai_ekskul = $dataSekolah->prefix.strtotime($now).uniqid();
                $nilaiEkskul->id_pengambilan_ekskul = $x->id_pengambilan_ekskul;
                $nilaiEkskul->id_komponen_ekskul = $komp->id_komponen_ekskul;
                $nilaiEkskul->besar_nilai_ekskul = $nilai;
                $nilaiEkskul->created_at = $now;
                $nilaiEkskul->save();
            }

            if($nilai == 100){
                $predikat = 'A';
            } elseif($nilai == 90){
                $predikat = 'B';
            } else {
                $predikat = 'C';
            }

            $x->nilai_angka = $nilai;
            $x->nilai_huruf = $predikat;
            $x->save();
            $this->info('... ' . $i);
            $i++;
        }
        
        $ekskulCoding = $pengambilanEkskulAll->where('id_ekskul', $coding->id_ekskul);
        $this->info('Insert Nilai Ekskul ' . $coding->nm_ekskul);
        foreach($ekskulCoding as $x){
            $komponen = KomponenEkskul::where('id_ekskul', $x->id_ekskul)->get();
            $siswa = Siswa::findOrFail($x->id_siswa);

            $nilai = null;
            if(collect($nilaiPaskib)->has($siswa->nis_siswa)){
                $nilai = $nilaiPaskib[$siswa->nis_siswa];
            }

            foreach($komponen as $komp){
                $nilaiEkskul = new NilaiEkskul();
                $nilaiEkskul->id_nilai_ekskul = $dataSekolah->prefix.strtotime($now).uniqid();
                $nilaiEkskul->id_pengambilan_ekskul = $x->id_pengambilan_ekskul;
                $nilaiEkskul->id_komponen_ekskul = $komp->id_komponen_ekskul;
                $nilaiEkskul->besar_nilai_ekskul = $nilai;
                $nilaiEkskul->created_at = $now;
                $nilaiEkskul->save();
            }

            if($nilai == 100){
                $predikat = 'A';
            } elseif($nilai == 90){
                $predikat = 'B';
            } else {
                $predikat = 'C';
            }

            $x->nilai_angka = $nilai;
            $x->nilai_huruf = $predikat;
            $x->save();
        }
        
        $ekskulMicroStock = $pengambilanEkskulAll->where('id_ekskul', $microStock->id_ekskul);
        $this->info('Insert Nilai Ekskul ' . $microStock->nm_ekskul);
        foreach($ekskulMicroStock as $x){
            $komponen = KomponenEkskul::where('id_ekskul', $x->id_ekskul)->get();
            $siswa = Siswa::findOrFail($x->id_siswa);

            $nilai = null;
            if(collect($nilaiPaskib)->has($siswa->nis_siswa)){
                $nilai = $nilaiPaskib[$siswa->nis_siswa];
            }

            foreach($komponen as $komp){
                $nilaiEkskul = new NilaiEkskul();
                $nilaiEkskul->id_nilai_ekskul = $dataSekolah->prefix.strtotime($now).uniqid();
                $nilaiEkskul->id_pengambilan_ekskul = $x->id_pengambilan_ekskul;
                $nilaiEkskul->id_komponen_ekskul = $komp->id_komponen_ekskul;
                $nilaiEkskul->besar_nilai_ekskul = $nilai;
                $nilaiEkskul->created_at = $now;
                $nilaiEkskul->save();
            }

            if($nilai == 100){
                $predikat = 'A';
            } elseif($nilai == 90){
                $predikat = 'B';
            } else {
                $predikat = 'C';
            }

            $x->nilai_angka = $nilai;
            $x->nilai_huruf = $predikat;
            $x->save();
        }
    }
    
    private function fixDbData6(){
        $this->info('Insert Nilai Ekskul...');
        // $this->info('Insert Nilai Ekskul & Nilai Pengambilan Ekskul to All Siswa...');
        $semester = Semester::where('kode_semester', 20201)->where('id_sekolah', 'E3nJ115358553135b8b4ad12f588')->first();
        $ekskul = Ekskul::get();
        $dataSekolah = Sekolah::where('nm_singkat_sekolah', 'smkpemudakrian')->first();
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $paskib = $ekskul->where('nm_ekskul', 'Paskibraka')->where('id_sekolah', $dataSekolah->id_sekolah)->first();
        $voly = $ekskul->where('nm_ekskul', 'Voly')->where('id_sekolah', $dataSekolah->id_sekolah)->first();
        $qiroah = $ekskul->where('nomor_sk_ekskul', 'qiroah-01')->where('id_sekolah', $dataSekolah->id_sekolah)->first();
        $pengambilanEkskulAll = PengambilanEkskul::get();

        $nilaiPaskib = [
            '12478' => 100, 
            '12486' => 100,
            '12487' => 100,
            '12489' => 100,
            '12492' => 100,
            '12495' => 100,
            '12440' => 100,
            '12448' => 100,
            '12325' => 100,
            '12339' => 100,
            '12194' => 100,
            '12198' => 100,
            '12203' => 100,
        ];

        $i = 0;
        $ekskulPaskib = $pengambilanEkskulAll->where('id_ekskul', $paskib->id_ekskul);
        $this->info('Insert Nilai Ekskul ' . $paskib->nm_ekskul);
        foreach($ekskulPaskib as $x){
            $komponen = KomponenEkskul::where('id_ekskul', $x->id_ekskul)->get();
            $siswa = Siswa::findOrFail($x->id_siswa);

            $nilai = null;
            if(collect($nilaiPaskib)->has($siswa->nis_siswa)){
                $nilai = $nilaiPaskib[$siswa->nis_siswa];
            }

            foreach($komponen as $komp){
                $nilaiEkskul = new NilaiEkskul();
                $nilaiEkskul->id_nilai_ekskul = $dataSekolah->prefix.strtotime($now).uniqid();
                $nilaiEkskul->id_pengambilan_ekskul = $x->id_pengambilan_ekskul;
                $nilaiEkskul->id_komponen_ekskul = $komp->id_komponen_ekskul;
                $nilaiEkskul->besar_nilai_ekskul = $nilai;
                $nilaiEkskul->created_at = $now;
                $nilaiEkskul->save();
            }

            if($nilai == 100){
                $predikat = 'A';
            } elseif($nilai == 90){
                $predikat = 'B';
            } else {
                $predikat = 'C';
            }

            $x->nilai_angka = $nilai;
            $x->nilai_huruf = $predikat;
            $x->save();
            $this->info('... ' . $i);
            $i++;
        }
        
        $nilaiQiroah = [
            '12389' => 90, 
            '12326' => 90,
            '12267' => 90,
            '12306' => 90,
            '12311' => 100,
            '12194' => 100,
            '12226' => 100,
            '12230' => 90
        ];

        $i = 0;
        $ekskulQiroah = $pengambilanEkskulAll->where('id_ekskul', $qiroah->id_ekskul);
        $this->info('Insert Nilai Ekskul ' . $qiroah->nm_ekskul);
        foreach($ekskulQiroah as $x){
            $komponen = KomponenEkskul::where('id_ekskul', $x->id_ekskul)->get();
            $siswa = Siswa::findOrFail($x->id_siswa);

            $nilai = null;
            if(collect($nilaiQiroah)->has($siswa->nis_siswa)){
                $nilai = $nilaiQiroah[$siswa->nis_siswa];
            }

            foreach($komponen as $komp){
                $nilaiEkskul = new NilaiEkskul();
                $nilaiEkskul->id_nilai_ekskul = $dataSekolah->prefix.strtotime($now).uniqid();
                $nilaiEkskul->id_pengambilan_ekskul = $x->id_pengambilan_ekskul;
                $nilaiEkskul->id_komponen_ekskul = $komp->id_komponen_ekskul;
                $nilaiEkskul->besar_nilai_ekskul = $nilai;
                $nilaiEkskul->created_at = $now;
                $nilaiEkskul->save();
            }

            if($nilai == 100){
                $predikat = 'A';
            } elseif($nilai == 90){
                $predikat = 'B';
            } else {
                $predikat = 'C';
            }

            $x->nilai_angka = $nilai;
            $x->nilai_huruf = $predikat;
            $x->save();
            $this->info('... ' . $i);
            $i++;
        }
        
        
        
        
    }

    private function fixDbData7(){
        $this->info('Insert Nilai Ekskul...');
        $ekskul = Ekskul::get();
        $dataSekolah = Sekolah::where('nm_singkat_sekolah', 'smkpemudakrian')->first();
        $now = Carbon::now(env('APP_TIMEZONE', ''));

        $voly = $ekskul->where('nm_ekskul', 'Voly')->where('id_sekolah', $dataSekolah->id_sekolah)->first();
        $pengambilanEkskulAll = PengambilanEkskul::get();

        $nilaiVoly = [
            '12494' => 90, 
            '12437' => 90,
            '12334' => 90,
            '12469' => 90,
            '12449' => 90,
            '12389' => 100,
            '12421' => 90,
            '12423' => 90,
            '12348' => 90,
            '12263' => 100,
            '12203' => 100,
            '12239' => 90,
        ];

        $i = 0;
        $ekskulVoly = $pengambilanEkskulAll->where('id_ekskul', $voly->id_ekskul);
        $this->info('Insert Nilai Ekskul ' . $voly->nm_ekskul);
        foreach($ekskulVoly as $x){
            $komponen = KomponenEkskul::where('id_ekskul', $x->id_ekskul)->get();
            $siswa = Siswa::findOrFail($x->id_siswa);

            $nilai = null;
            if(collect($nilaiVoly)->has($siswa->nis_siswa)){
                $nilai = $nilaiVoly[$siswa->nis_siswa];
            }

            foreach($komponen as $komp){
                $nilaiEkskul = new NilaiEkskul();
                $nilaiEkskul->id_nilai_ekskul = $dataSekolah->prefix.strtotime($now).uniqid();
                $nilaiEkskul->id_pengambilan_ekskul = $x->id_pengambilan_ekskul;
                $nilaiEkskul->id_komponen_ekskul = $komp->id_komponen_ekskul;
                $nilaiEkskul->besar_nilai_ekskul = $nilai;
                $nilaiEkskul->created_at = $now;
                $nilaiEkskul->save();
            }

            if($nilai == 100){
                $predikat = 'A';
            } elseif($nilai == 90){
                $predikat = 'B';
            } else {
                $predikat = 'C';
            }

            $x->nilai_angka = $nilai;
            $x->nilai_huruf = $predikat;
            $x->save();
            $this->info('... ' . $i);
            $i++;
        }
    }

    public function fixDbData8(){
        $mapel = ['Pendidikan Agama dan Budi Pekerti', 'PPKN', 'Matematika', 'Sejarah Indonesia', 'Bahasa Indonesia', 'Bahasa Inggris', 'Bahasa Arab', 'Seni Budaya', 'Penjasorkes', 'Kemuhammadiyahan', 'Bahasa Daerah', 'Bahasa Mandarin', 'BP / BK', 'BP/BK'];

        $raporKelompokMp = RaporKelompokMp::get();
        $mapelData = MataPelajaran::get();
        foreach($raporKelompokMp as $x){
            $noUrut = null;
            if($x->id_mata_pelajaran != null){
                $nmMapel = $mapelData->where('id_mata_pelajaran', $x->id_mata_pelajaran)->first()->nm_mata_pelajaran;
                $noUrut = array_search($nmMapel, $mapel);
                
                $x->urutan_rapor_kelompok_mp = $noUrut+1;
                $x->save();
            }
        }
    }
}

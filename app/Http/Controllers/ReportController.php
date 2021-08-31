<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Sekolah;
use App\Models\PelanggaranSiswa;
use App\Models\Role;
use App\Models\Gedung;
use App\Models\Ruangan;
use App\Models\BukuAlat;
use App\Models\LowonganKerja;
use App\Models\Kerjasama;
use App\Models\TagihanBiaya;
use App\Models\PemasukanBiaya;
use App\Models\PengeluaranBiaya;
use App\Models\Guru;
use App\Models\Staff;
use App\Models\Kelas;
use App\Models\WaliKelas;
use App\Models\SekretarisKelas;
use App\Models\Semester;
use App\Models\RuanganKelas;
use App\Models\JadwalKegiatan;
use App\Models\Ekskul;
use App\Models\PelatihEkskulSet;
use App\Models\PembinaEkskulSet;
use App\Models\PesertaEkskulSet;
use App\Models\PresensiEkskul;
use App\Models\ArsipDokumen;
use App\Models\Penerimaan;
use App\Models\CalonSiswaBaru;
use App\Models\Kurikulum;
use App\Models\KurikulumMp;
use App\Models\KelasMp;
use App\Models\PresensiMp;
use App\Models\PresensiMpSiswa;
use App\Models\JadwalKelasMp;

use Carbon\Carbon;
use Yajra\Datatables\Datatables;

use App\Libraries\Pendidikan\LibDataAkademik;

use Auth;
use DB;
use Session;
use Validator;

class ReportController extends BaseController{

    public function viewAllDiakad(){

        $semester_aktif = Semester::where('is_aktif_semester','=',1)->first();
        $role = Role::whereNotIn('nm_role',['Siswa','Wali Murid','Administrator','Dapodik','Alumni','Rapor & Buku Induk','Tenaga Pendidik'])->orderBy('nm_role','asc')->get();
        $sekolah = Sekolah::orderBy('id_sekolah')->first();

        $data = array();

        foreach($role as $key => $r){

            $data[$key]['id_role'] = $r->id_role;
            $data[$key]['role'] = $r->nm_role;

            $data[$key]['status'] = '';
            $data[$key]['catatan'] = '';

            $temp = $this->checkProgress($r->id_role);
            $temp = $temp->original;
            $data[$key]['status'] = $temp['status'];
            $data[$key]['catatan'] = $temp['catatan'];
            $data[$key]['progress'] = $temp['progress'];
            $data[$key]['rowspan'] = count($temp['catatan']);
        }

    	return view('reporting-dashboard.all-diakad',compact('data','semester_aktif','sekolah'));

    }

    public function printAllDiakad(){

        $semester_aktif = Semester::where('is_aktif_semester','=',1)->first();
        $role = Role::whereNotIn('nm_role',['Siswa','Wali Murid','Administrator','Dapodik','Alumni','Rapor & Buku Induk','Tenaga Pendidik'])->orderBy('nm_role','asc')->get();
        $sekolah = Sekolah::orderBy('id_sekolah')->first();

        $data = array();

        foreach($role as $key => $r){

            $data[$key]['id_role'] = $r->id_role;
            $data[$key]['role'] = $r->nm_role;

            $data[$key]['status'] = '';
            $data[$key]['catatan'] = '';

            $temp = $this->checkProgress($r->id_role);
            $temp = $temp->original;
            $data[$key]['status'] = $temp['status'];
            $data[$key]['catatan'] = $temp['catatan'];
            $data[$key]['progress'] = $temp['progress'];
            $data[$key]['rowspan'] = count($temp['catatan']);

        }

        return view('reporting-dashboard.print-all-diakad',compact('data','semester_aktif','sekolah'));

    }

    public function count_multidimension($param){
        $jumlah = 0;
        foreach ($param as $key => $value) {
            if($value['status'] == 1){
                $jumlah++;
            }
        }

        return $jumlah;
    }

    public function checkProgress($id_role){

        $semester_aktif = Semester::where('is_aktif_semester','=',1)->first();
        $tahun_semester_aktif = $semester_aktif->thn_akademik_semester;
        $semester_aktif = $semester_aktif->id_semester;

        $role = Role::find($id_role);

        if($id_role == 1){ // Pendidikan

            $data_kelas = Kelas::count();

            $wali_kelas = WaliKelas::whereIn('id_kelas',Kelas::pluck('id_kelas'))->where('id_semester',$semester_aktif)->count();
            $sekretaris_kelas = SekretarisKelas::whereIn('id_kelas',Kelas::pluck('id_kelas'))->where('id_semester',$semester_aktif)->count();
            $ruangan_kelas = RuanganKelas::whereIn('id_kelas',Kelas::pluck('id_kelas'))->where('id_semester',$semester_aktif)->count();
            $kalender = JadwalKegiatan::where('id_semester',$semester_aktif)->count();

            $param[0]['catatan'] = 'Sudah melakukan input data kelas';
            $param[0]['status'] = 0;

            $param[1]['catatan'] = 'Sudah melakukan input data wali kelas , sekretaris dan ruangan pada semua kelas';
            $param[1]['status'] = 0;

            $param[2]['catatan'] = 'Sudah melakukan input data kalender akademik';
            $param[2]['status'] = 0;

            if($data_kelas){
                $param[0]['status'] = 1;
            }

            if($wali_kelas == $data_kelas && $sekretaris_kelas == $data_kelas && $wali_kelas == $ruangan_kelas ){
                $param[1]['status'] = 1;
            }

            if($kalender){
                $param[2]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if($jumlah_diisi == 0){
                $status = 'Belum Digunakan';
            }

            elseif($jumlah_diisi < count($param)){
                $status = 'Sudah digunakan namun belum maksimal';
            }

            else{
                $status = 'Sudah digunakan dengan maksimal';
            }

        }

        elseif($id_role == 2){ // Guru

            $kelas_mp = KelasMp::where('id_semester',$semester_aktif)->pluck('id_kelas_mp'); 
            $presensi_mp = PresensiMp::whereIn('id_kelas_mp',$kelas_mp)->pluck('id_presensi_mp');
            $presensi_mp_siswa = PresensiMpSiswa::whereIn('id_presensi_mp',$presensi_mp)->count();

            $param[0]['catatan'] = 'Sudah pernah melakukan absen pada saat kbm';
            $param[0]['status'] = 0;

            if($presensi_mp_siswa){
                $param[0]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if($jumlah_diisi == 0){
                $status = 'Belum Digunakan';
            }

            elseif($jumlah_diisi < count($param)){
                $status = 'Sudah digunakan namun belum maksimal';
            }

            else{
                $status = 'Sudah digunakan dengan maksimal';
            }

        }

        elseif($id_role == 5){ // Bimbingan & Konseling

            $pelanggaran_siswa = PelanggaranSiswa::count();
            $tindakan_pelanggaran = PelanggaranSiswa::where('is_sudah_tindakan', 1)->count();

            $param[0]['catatan'] = 'Sudah ada pelanggaran yang diinputkan';
            $param[0]['status'] = 0;

            $param[1]['catatan'] = 'Sudah melakukan tindakan peda pelanggaran';
            $param[1]['status'] = 0;

            if($pelanggaran_siswa){
                $param[0]['status'] = 1;
            }

            if($tindakan_pelanggaran){
                $param[1]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if($jumlah_diisi == 0){
                $status = 'Belum Digunakan';
            }

            elseif($jumlah_diisi < count($param)){
                $status = 'Sudah digunakan namun belum maksimal';
            }

            else{
                $status = 'Sudah digunakan dengan maksimal';
            }

        }

        elseif($id_role == 6){ // Kesiswaan

            $data_ekskul = Ekskul::count();

            $param[0]['catatan'] = 'Sudah ada melakukan input date ekskul beserta pelatih , pembina dan peserta';
            $param[0]['status'] = 0;

            $pelatih_ekskul = PelatihEkskulSet::whereIn('id_ekskul',Ekskul::pluck('id_ekskul'))->where('is_aktif',1)->count();
            $pembina_ekskul = PembinaEkskulSet::whereIn('id_ekskul',Ekskul::pluck('id_ekskul'))->where('is_aktif',1)->count();
            $peserta_ekskul = PesertaEkskulSet::whereIn('id_ekskul',Ekskul::pluck('id_ekskul'))->groupBy('id_ekskul')->count();

            if($pelatih_ekskul == $data_ekskul && $pembina_ekskul == $data_ekskul && $peserta_ekskul == $data_ekskul){
                $param[0]['status'] = 1;
            }
            
            $jumlah_diisi = $this->count_multidimension($param);

            if($jumlah_diisi == 0){
                $status = 'Belum Digunakan';
            }

            elseif($jumlah_diisi < count($param)){
                $status = 'Sudah digunakan namun belum maksimal';
            }

            else{
                $status = 'Sudah digunakan dengan maksimal';
            }


        }

        elseif($id_role == 7){ // Akademik

            $kurikulum = Kurikulum::pluck('id_kurikulum');
            $kurikulum_mp = KurikulumMp::distinct('id_kurikulum')->whereIn('id_kurikulum',$kurikulum)->count('id_kurikulum');

            $kelas_mp = KelasMp::where('id_semester',$semester_aktif)->pluck('id_kelas_mp');

            $jadwal_kelas_mp = JadwalKelasMp::distinct('id_kelas_mp')->whereIn('id_kelas_mp',$kelas_mp)->count('id_kelas_mp');

            $param[0]['catatan'] = 'Sudah mensetting kurikulum beserta mapel mapelnya';
            $param[0]['status'] = 0;

            $param[1]['catatan'] = 'Sudah mensetting usulan mata ajar (semester yang aktif)';
            $param[1]['status'] = 0;

            $param[2]['catatan'] = 'Sudah mensetting jadwal mengajar serta ruangan pada usulan mata ajar (semester yang aktif) ';
            $param[2]['status'] = 0;

            if($kurikulum_mp > 0 && $kurikulum_mp == $kurikulum->count()){
                $param[0]['status'] = 1;
            }

            if($kelas_mp->count()){
                $param[1]['status'] = 1;
            }

            if($jadwal_kelas_mp == $kelas_mp->count()){
                $param[2]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if($jumlah_diisi == 0){
                $status = 'Belum Digunakan';
            }

            elseif($jumlah_diisi < count($param)){
                $status = 'Sudah digunakan namun belum maksimal';
            }

            else{
                $status = 'Sudah digunakan dengan maksimal';
            }

        }

        elseif($id_role == 8){ // Sumber Daya

            $data_guru = Guru::count();
            $data_tendik = Staff::count();

            $param[0]['catatan'] = 'Sudah memasukkan data guru';
            $param[0]['status'] = 0;

            $param[1]['catatan'] = 'Sudah memasukkan data staff';
            $param[1]['status'] = 0;

            if($data_guru){
                $param[0]['status'] = 1;
            }

            if($data_tendik){
                $param[1]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if($jumlah_diisi == 0){
                $status = 'Belum Digunakan';
            }

            elseif($jumlah_diisi < count($param)){
                $status = 'Sudah digunakan namun belum maksimal';
            }

            else{
                $status = 'Sudah digunakan dengan maksimal';
            }

        }

        elseif($id_role == 9){ // Keuangan

            $tagihan_siswa = TagihanBiaya::whereHas('detail_biaya.biaya_sekolah',function($q) use ($semester_aktif){
                                            $q->where('id_semester',$semester_aktif);
                                        })->count();
            $pembayaran_siswa = TagihanBiaya::whereHas('detail_biaya.biaya_sekolah',function($q) use ($semester_aktif){
                                                $q->where('id_semester',$semester_aktif);
                                            })->where('is_tagih',0)->count();
            $pemasukan_biaya = PemasukanBiaya::where('id_semester',$semester_aktif)->count();
            $pengeluaran_biaya = PengeluaranBiaya::where('id_semester',$semester_aktif)->count();
            $pembayaran_online = env("WINPAY_PRIVATE_KEY1");

            $param[0]['catatan'] = 'Sudah melakukan generate tagihan siswa (semester yang aktif)';
            $param[0]['status'] = 0;

            $param[1]['catatan'] = 'Sudah melakukan proses pembayaran siswa (semester yang aktif)';
            $param[1]['status'] = 0;

            $param[2]['catatan'] = 'Sudah melakukan input pemasukan dan pengeluaran (semester yang aktif)';
            $param[2]['status'] = 0;

            $param[3]['catatan'] = 'Sudah menerapkan pembayaran online';
            $param[3]['status'] = 0;

            if($tagihan_siswa) $param[0]['status'] = 1;

            if($pembayaran_siswa) $param[1]['status'] = 1;

            if($pemasukan_biaya && $pengeluaran_biaya)  $param[2]['status'] = 1;

            if($pembayaran_online != "") $param[3]['status'] = 1;

            $jumlah_diisi = $this->count_multidimension($param);

            if($jumlah_diisi == 0){
                $status = 'Belum Digunakan';
            }

            elseif($jumlah_diisi < count($param)){
                $status = 'Sudah digunakan namun belum maksimal';
            }

            else{
                $status = 'Sudah digunakan dengan maksimal';
            }

        }

        elseif($id_role == 10){ // Sarana Prasarana

            $gedung = Gedung::count();
            $ruangan = ruangan::count();
            $bukualat = BukuAlat::count();

            $param[0]['catatan'] = 'Sudah melakukuan input data gedung';
            $param[0]['status'] = 0;

            $param[1]['catatan'] = 'Sudah melakukuan input data ruangan';
            $param[1]['status'] = 0;

            $param[2]['catatan'] = 'Sudah melakukuan input data buku/alat';
            $param[2]['status'] = 0;

            if($gedung){
                $param[0]['status'] = 1;
            }

            if($ruangan){
                $param[1]['status'] = 1;
            }

            if($bukualat){
                $param[2]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if($jumlah_diisi == 0){
                $status = 'Belum Digunakan';
            }

            elseif($jumlah_diisi < count($param)){
                $status = 'Sudah digunakan namun belum maksimal';
            }

            else{
                $status = 'Sudah digunakan dengan maksimal';
            }

        }

        elseif($id_role == 11){ // PPDB

            $penerimaan_online = Penerimaan::where('is_pendaftaran_online',1)
                                        ->whereHas('semester',function($q) use ($tahun_semester_aktif){
                                            $q->where('thn_akademik_semester',$tahun_semester_aktif);
                                        });

            $calon_siswa_online = CalonSiswaBaru::whereIn('id_penerimaan',$penerimaan_online->pluck('id_penerimaan'))->count();

            $param[0]['catatan'] = 'Sudah pernah melakukan penerimaan secara online';
            $param[0]['status'] = 0;

            $param[1]['catatan'] = 'Sudah ada siswa yang mendaftar pada penerimaan secara online';
            $param[1]['status'] = 0;

            if($penerimaan_online->count()){
                $param[0]['status'] = 1;
            }

            if($calon_siswa_online){
                $param[1]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if($jumlah_diisi == 0){
                $status = 'Belum Digunakan';
            }

            elseif($jumlah_diisi < count($param)){
                $status = 'Sudah digunakan namun belum maksimal';
            }

            else{
                $status = 'Sudah digunakan dengan maksimal';
            }

        }

        elseif($id_role == 13){ // Pelatih Ekskul

            $presensi_ekskul = PresensiEkskul::where('id_semester',$semester_aktif)->count();

            $param[0]['catatan'] = 'Sudah melakukuan absensi ekskul';
            $param[0]['status'] = 0;

            if($presensi_ekskul){
                $param[0]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if($jumlah_diisi == 0){
                $status = 'Belum Digunakan';
            }

            elseif($jumlah_diisi < count($param)){
                $status = 'Sudah digunakan namun belum maksimal';
            }

            else{
                $status = 'Sudah digunakan dengan maksimal';
            }

        }

        elseif($id_role == 14){ // Sekretariat

            $arsip_dokumen = ArsipDokumen::count();

            $param[0]['catatan'] = 'Sudah melakukuan input data pada arsip dokumen';
            $param[0]['status'] = 0;

            if($arsip_dokumen){
                $param[0]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if($jumlah_diisi == 0){
                $status = 'Belum Digunakan';
            }

            elseif($jumlah_diisi < count($param)){
                $status = 'Sudah digunakan namun belum maksimal';
            }

            else{
                $status = 'Sudah digunakan dengan maksimal';
            }

        }

        elseif($id_role == 19){ // Humas

            $loker = LowonganKerja::count();
            $kerjasama = Kerjasama::count();

            $param[0]['catatan'] = 'Sudah melakukuan input data pada lowongan kerja';
            $param[0]['status'] = 0;

            $param[1]['catatan'] = 'Sudah melakukuan input data kerja sama';
            $param[1]['status'] = 0;

            if($loker){
                $param[0]['status'] = 1;
            }

            if($kerjasama){
                $param[1]['status'] = 1;
            }

            $jumlah_diisi = $this->count_multidimension($param);

            if($jumlah_diisi == 0){
                $status = 'Belum Digunakan';
            }

            elseif($jumlah_diisi < count($param)){
                $status = 'Sudah digunakan namun belum maksimal';
            }

            else{
                $status = 'Sudah digunakan dengan maksimal';
            }

        }

        $data['nm_role'] = $role->nm_role;
        $data['status'] = $status;
        $data['catatan'] = $param;
        $data['progress'] = $jumlah_diisi / count($param) * 100;

        return response()->json($data);

    }

}
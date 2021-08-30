<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

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
        $role = Role::whereNotIn('nm_role',['Siswa','Wali Murid','Administrator','Dapodik','Alumni','Rapor & Buku Induk','Tenaga Pendidik'])->get();

        $data = array();

        foreach($role as $key => $r){

            $data[$key]['id_role'] = $r->id_role;
            $data[$key]['role'] = $r->nm_role;

            $data[$key]['status'] = '';
            $data[$key]['catatan'] = '';

            if($r->nm_role == 'Bimbingan Konseling'){
                $temp = $this->checkProgress($r->id_role);
                $temp = $temp->original;
                $data[$key]['status'] = $temp['status'];
                $data[$key]['catatan'] = $temp['catatan'];
                $data[$key]['progress'] = $temp['progress'];
            }

            elseif($r->nm_role == 'Sarana Prasarana'){
                $temp = $this->checkProgress($r->id_role);
                $temp = $temp->original;
                $data[$key]['status'] = $temp['status'];
                $data[$key]['catatan'] = $temp['catatan'];
                $data[$key]['progress'] = $temp['progress'];
            }

            elseif($r->nm_role == 'Humas'){
                $temp = $this->checkProgress($r->id_role);
                $temp = $temp->original;
                $data[$key]['status'] = $temp['status'];
                $data[$key]['catatan'] = $temp['catatan'];
                $data[$key]['progress'] = $temp['progress'];
            }

            elseif($r->nm_role == 'Keuangan'){
                $temp = $this->checkProgress($r->id_role);
                $temp = $temp->original;
                $data[$key]['status'] = $temp['status'];
                $data[$key]['catatan'] = $temp['catatan'];
                $data[$key]['progress'] = $temp['progress'];
            }

            elseif($r->nm_role == 'Sumber Daya'){
                $temp = $this->checkProgress($r->id_role);
                $temp = $temp->original;
                $data[$key]['status'] = $temp['status'];
                $data[$key]['catatan'] = $temp['catatan'];
                $data[$key]['progress'] = $temp['progress'];
            }

            elseif($r->nm_role == 'Pendidikan'){
                $temp = $this->checkProgress($r->id_role);
                $temp = $temp->original;
                $data[$key]['status'] = $temp['status'];
                $data[$key]['catatan'] = $temp['catatan'];
                $data[$key]['progress'] = $temp['progress'];
            }

            elseif($r->nm_role == 'Kesiswaan'){
                $temp = $this->checkProgress($r->id_role);
                $temp = $temp->original;
                $data[$key]['status'] = $temp['status'];
                $data[$key]['catatan'] = $temp['catatan'];
                $data[$key]['progress'] = $temp['progress'];
            }

            elseif($r->nm_role == 'Pelatih Ekskul'){
                $temp = $this->checkProgress($r->id_role);
                $temp = $temp->original;
                $data[$key]['status'] = $temp['status'];
                $data[$key]['catatan'] = $temp['catatan'];
                $data[$key]['progress'] = $temp['progress'];
            }

        }

    	return view('reporting-dashboard.all-diakad',compact('data','semester_aktif'));

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

            $pelatih_ekskul = PelatihEkskulSet::where('id_ekskul',[Ekskul::pluck('id_ekskul')])->where('is_aktif',1)->count();
            $pembina_ekskul = PembinaEkskulSet::where('id_ekskul',[Ekskul::pluck('id_ekskul')])->where('is_aktif',1)->count();
            $peserta_ekskul = PesertaEkskulSet::where('id_ekskul',[Ekskul::pluck('id_ekskul')])->groupBy('id_ekskul')->count();

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

            $tagihan_siswa = TagihanBiaya::count();
            $pembayaran_siswa = TagihanBiaya::where('is_tagih',0)->count();
            $pemasukan_biaya = PemasukanBiaya::count();
            $pengeluaran_biaya = PengeluaranBiaya::count();
            $pembayaran_online = env("WINPAY_PRIVATE_KEY1");

            $param[0]['catatan'] = 'Sudah melakukan generate tagihan siswa';
            $param[0]['status'] = 0;

            $param[1]['catatan'] = 'Sudah melakukan proses pembayaran siswa';
            $param[1]['status'] = 0;

            $param[2]['catatan'] = 'Sudah melakukan input pemasukan dan pengeluaran';
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
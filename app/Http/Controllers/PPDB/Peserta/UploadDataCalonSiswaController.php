<?php

namespace App\Http\Controllers\PPDB\Peserta;

use App\Http\Controllers\Controller;
use App\Imports\DataImportExcel;
use App\Libraries\LibGlobal;
use App\Models\Agama;
use App\Models\CalonSiswaBaru;
use App\Models\CalonSiswaSekolah;
use App\Models\Jalur;
use App\Models\JenisLayakPip;
use App\Models\JenisPekerjaan;
use App\Models\JenisPendidikan;
use App\Models\JenisPenghasilan;
use App\Models\JenisTinggal;
use App\Models\JenisTransportasi;
use App\Models\Jurusan;
use App\Models\KebutuhanKhusus;
use App\Models\Kelas;
use App\Models\Kota;
use App\Models\Penerimaan;
use App\Models\Provinsi;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\StatusPengguna;
use App\Models\Voucher;
use Barryvdh\Debugbar\Facades\Debugbar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;

class UploadDataCalonSiswaController extends Controller
{
    public function uploadFileExcel(Request $request, $id_penerimaan)
    {
        set_time_limit(-1);
        // $input = (object) $request->input();
        // $auth_data = auth_data();
        // $now = Carbon::now();
        if ($request->hasFile('file-excel')) {
            $datas = Excel::toArray(new DataImportExcel, $request->file('file-excel'));
            $datas = $datas[0];
            if (count($datas) > 1) {
                foreach ($datas as $data) {

                    $exsistStudent = CalonSiswaBaru::where('nisn_siswa', $data['nisn'])->first();
                    if ($exsistStudent) {
                        return response()->json(['status' => 'error', 'message' => 'NISN ' . $data['nisn'] . ' telah terdaftar!'], 500);
                    }
                    // dd($data);

                    $agama = strtolower($data["agama"]);

                    $siswa = new CalonSiswaBaru();
                    $siswa_sekolah = new CalonSiswaSekolah();
                    $siswa->id_c_siswa = Uuid::uuid4()->toString();
                    $siswa->id_penerimaan = $id_penerimaan;
                    $siswa->kode_voucher = null;
                    $siswa->password = null;
                    $siswa->nm_c_siswa = $data['nama_lengkap'] ?? null;
                    $siswa->nm_panggilan = null;
                    $siswa->nik_siswa = $data['nik'] ?? null;
                    $siswa->jenis_kelamin = $data['jenis_kelamin'] === "L" ? 1 : 2;
                    $siswa->nisn_siswa = $data['nisn'] ?? null;
                    $siswa->status_verifikasi = 2;

                    if ($agama === "islam") {
                        $siswa->id_agama = 1;
                    } else if ($agama === "kristen" || $agama === "protestan") {
                        $siswa->id_agama = 2;
                    } else if ($agama === "katholik") {
                        $siswa->id_agama = 3;
                    } else if ($agama === "hindu") {
                        $siswa->id_agama = 4;
                    } else if ($agama === "budha") {
                        $siswa->id_agama = 5;
                    } else if ($agama === "konghucu") {
                        $siswa->id_agama = 6;
                    } else {
                        $siswa->id_agama = 7;
                    }

                    $siswa->kode_voucher = $data["kode_voucher"] ?? null;
                    $kotaLahir = Kota::where('nm_kota', $data['kota_lahir'])->first();
                    $siswa->id_kota_lahir = $kotaLahir->id_kota;
                    $siswa->tgl_lahir = date("Y-m-d", strtotime($data['tanggal_lahir']));
                    $kotaKSK = Kota::where('nm_kota', $data['nama_kota_ksk'])->first();
                    $siswa->id_kota_ksk = $kotaKSK->id_kota;
                    $siswa->nomor_ksk = $data['nomor_ksk'];
                    $siswa->nomor_identitas = $data['nomor_identitas_ktp_sim_lainya'] ?? null;
                    $siswa->nomor_akta_lahir = $data['nomor_akta_lahir'] ?? null;
                    $siswa->kewarganegaraan = $data['kewarganegaraan'] === "WNI" ? 1 : 0;
                    $siswa->nm_kewarganegaraan = $data['nama_kewarganegaraan'] ?? null;
                    $dataKebutuhanKhusus = KebutuhanKhusus::where('nm_kebutuhan_khusus', $data['kebutuhan_khusus'])->first();
                    $siswa->id_kebutuhan_khusus = $dataKebutuhanKhusus->id_kebutuhan_khusus;
                    $siswa->alamat_jalan = $data['alamat_jalan'] ?? null;
                    $siswa->alamat_dusun = $data['alamat_dusun'] ?? null;
                    $siswa->alamat_kelurahan = $data['alamat_kelurahan'] ?? null;
                    $siswa->alamat_rt = $data['alamat_rt'] ?? null;
                    $siswa->alamat_rw = $data['alamat_rw'] ?? null;
                    $siswa->alamat_kecamatan = $data['alamat_kecamatan'] ?? null;
                    $siswa->alamat_kodepos = $data['alamat_kodepos'] ?? null;
                    $alamatKota = Kota::where('nm_kota', $data['alamat_kota'])->first();
                    $siswa->alamat_kota = $alamatKota->id_kota;
                    $provinsi = Provinsi::where('nm_provinsi', $data['alamat_provinsi'])->first();
                    $siswa->alamat_provinsi = $provinsi->id_provinsi;
                    $siswa->alamat_latitude = $data['alamat_latitude'] ?? null;
                    $siswa->alamat_longitude = $data['alamat_longtitude'] ?? null;
                    // dd($siswa->alamat_longitude);
                    $siswa->nomor_hp = $data['nomor_hp'] ?? null;
                    $jenisTinggal = JenisTinggal::where('nm_jenis_tinggal', $data['jenis_tinggal'])->first();
                    $siswa->id_jenis_tinggal = $jenisTinggal->id_jenis_tinggal;
                    $siswa->bahasa_sehari_hari = null;
                    $siswa->anak_ke = $data['anak_ke'] ?? null;
                    $siswa->dari_x_bersaudara = $data['dari_berapa_saudara'] ?? null;
                    $siswa->jarak_rumah_sekolah = $data['jarak_rumah_ke_sekolah_km'] ?? null;
                    $siswa->waktu_tempuh_sekolah_jam = $data['waktu_tempu_ke_sekolah_jam'] ?? null;
                    $siswa->waktu_tempuh_sekolah_menit = $data['waktu_tempu_ke_sekolah_menit'] ?? null;
                    $jenisTransportasi = JenisTransportasi::where('nm_jenis_transportasi', $data['jenis_transportasi'])->first();
                    $siswa->id_jenis_transportasi = $jenisTransportasi->id_jenis_transportasi;
                    $siswa->nomor_kks = $data['nomor_kartu_keluarga_sejahtera'] ?? null;
                    $siswa->is_penerima_kps = $data['merupakan_penerima_kartu_perlindungan_sosial'] ?? null;
                    $siswa->thn_penerima_kps = null;
                    $siswa->nomor_kps = $data['nomor_kartu_perlindungan_sosial'] ?? null;
                    $siswa->is_punya_kip = $data['merupakan_penerima_kartu_indonesia_pintar'] ?? null;;
                    $siswa->nomor_kip = $data['nomor_kartu_indonesia_pintar'] ?? null;
                    $siswa->nm_tertera_kip = $data['nama_tertera_pada_kip'] ?? null;
                    $siswa->is_layak_pip = $data['layak_pip'] ?? null;
                    $jenisLayakPip = JenisLayakPip::where('nm_jenis_layak_pip', $data['jenis_layak_pip'])->first();
                    $siswa->id_jenis_layak_pip = $jenisLayakPip->id_jenis_layak_pip ?? null;
                    $siswa->asal_sekolah = $data['asal_sekolah'] ?? null;
                    $siswa->nis_siswa = $data['nomor_ijasah_sebelumnya'] ?? null;
                    $siswa->created_by = Auth::user()->id_pengguna;
                    $siswa->updated_by = Auth::user()->id_pengguna;

                    $kotaSekolahAsal = Kota::where('nm_kota', $data['kota_asal_sekolah_sebelumnya'])->first();
                    $siswa_sekolah->id_c_siswa = $siswa->id_c_siswa;
                    $siswa_sekolah->id_kota_sekolah_asal = $kotaSekolahAsal->id_kota;
                    $siswa_sekolah->nm_sekolah_asal = $siswa->asal_sekolah;
                    $siswa_sekolah->nomor_shun = $data['nomor_shun_sebelumnya'] ?? null;
                    $siswa_sekolah->nilai_shun = $data['nilai_shun_sebelumnya'] ?? null;
                    $siswa_sekolah->nomor_ijasah = $data['nomor_ijasah_sebelumnya'] ?? null;
                    $siswa_sekolah->tahun_lulus = $data['tahun_lulus'] ?? null;
                    $siswa_sekolah->nomor_peserta_unas = $data['nomor_peserta_unas'] ?? null;
                    $siswa_sekolah->nisn = $siswa->nisn_siswa;

                    $jurusan1 = Jurusan::where('nm_jurusan', $data['pilihan_jurusan_1'])->first();
                    $jurusan2 = Jurusan::where('nm_jurusan', $data['pilihan_jurusan_2'])->first();
                    $jurusan3 = Jurusan::where('nm_jurusan', $data['pilihan_jurusan_3'])->first();

                    $siswa->id_pilihan_jurusan_1 = $jurusan1->id_jurusan;
                    $siswa->id_pilihan_jurusan_2 = $jurusan2->id_jurusan;
                    $siswa->id_pilihan_jurusan_3 = $jurusan3->id_jurusan;

                    $siswa_sekolah->created_by = Auth::user()->id_pengguna;
                    $siswa_sekolah->updated_by = Auth::user()->id_pengguna;

                    $siswa_sekolah->save();
                    $siswa->save();
                }
                return response()->json(['status' => "OK", 'message' => "Berhasil Mengunggah Data Calon Siswa!"], 201);
            } else {
                return response()->json(['status' => "Error", 'message' => "Data Excel Tidak Boleh Kosong!"], 404);
            }
        } else {
            return response()->json(["status" => "Error", "message" => "File Excel Belum Diunggah!"], 400);
        }
    }
}

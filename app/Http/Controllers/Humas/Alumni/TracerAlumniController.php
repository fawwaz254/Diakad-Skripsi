<?php

namespace App\Http\Controllers\Humas\Alumni;



use Error;
use Exception;
use Carbon\Carbon;
use App\Exports\ExportAlumni;
use App\Exports\ExportAlumni2;
use App\Models\Siswa;
use App\Models\Alumni;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Pengguna;
use App\Models\AlumniKuliah;
use Illuminate\Http\Request;
use App\Models\AlumniBekerja;
use App\Models\AlumniMenunggu;
use App\Models\CalonSiswaBaru;
use App\Models\CalonSiswaOrtu;
use App\Models\StatusPengguna;
use App\Models\AlumniWirausaha;
use App\Models\CalonSiswaFisik;
use Yajra\Datatables\Datatables;
use App\Models\CalonSiswaSekolah;
use App\Libraries\Humas\LibAlumni;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Libraries\Pendidikan\LibSiswa;
use App\Models\AlumniSmp;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TracerAlumniImport;
use App\Imports\DataImportExcel;
use Illuminate\Routing\Controller as BaseController;

class TracerAlumniController extends BaseController
{

    const PATH = 'alumni/tracer-alumni';
    const PATH2 = 'tracer-alumni';
    const PATHGURU = 'wali-kelas/tracer-alumni';
    const FETCH_WORK_ATTRIBUTE = ['nm_instansi', 'alamat_instansi', 'kontak_instansi', 'bidang_usaha_instansi', 'tahun_masuk_instansi', 'kapan_mulai_bekerja', 'lama_bekerja'];
    const FETCH_COLLEGE_ATTRIBUTE = ['nm_perguruan', 'alamat_perguruan', 'fakultas', 'prodi', 'jenjang', 'tahun_masuk_perguruan'];
    const FETCH_ENTERPRENEUR_ATTRIBUTE = ['nm_usaha', 'alamat_usaha', 'kontak_usaha', 'bidang_usaha', 'jumlah_karyawan', 'tahun_rintis'];
    const FETCH_IDLE_ATTRIBUTE = ['status_menunggu'];
    const FETCH_SMP = ['nm_sekolah', 'alamat_sekolah', 'jurusan', 'jenis_sekolah', 'tahun_masuk_sekolah'];

    public function viewTracerAlumni(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpmuh6krian' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm2') {
            return view('humas.alumni.tracer-alumni.view-tracer-alumni-smp');
        } else {
            return view('humas.alumni.tracer-alumni.view-tracer-alumni');
        }
    }

    public function excelTracerAlumni(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        // if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpmuh6krian' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1') {
        //     return view('humas.alumni.tracer-alumni.view-tracer-alumni-smp');
        // } else {
        return view('humas.alumni.tracer-alumni.add-excel-tracer-alumni');
        // }
    }

    public function uploadFileExcel(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $now = Carbon::now();
        if ($request->hasFile('file-excel')) {
            // $path = $request->file('file-excel')->getRealPath();
            // $data = Excel::load($path)->get();
            Excel::import(new TracerAlumniImport($auth_data, $now), $request->file('file-excel'));
            return [
                'status' => 200, // FAILED
                'message' => "Upload Sukses"
            ];;
        } else {
            return [
                'status' => 300, // FAILED
                'message' => "File Excel tidak ditemukan"
            ];
        }
    }

    public function downloadFileExcel()
    {
        $file = public_path() . "/excel/ContohFileExelUploadTracerAlumniSmp.xlsx";
        $headers = [
            'Content-Type' => 'application/xlsx',
        ];

        return response()->download($file, 'ContohFileExelUploadTracerAlumniSmp.xlsx', $headers);
    }


    public function datatablesTracerAlumni(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpmuh6krian' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm2') {
            $alumnis = LibAlumni::getAlumnisSmp();
        } else {
            $alumnis = LibAlumni::getAlumnis();
        }

        return Datatables::of($alumnis)
            ->addColumn('status_verifikasi', function ($item) {
                if ($item->status_verifikasi == 0) {
                    $data['status'] = 'Belum Diverikasi';
                    $data['color'] = 'pink';
                } else {
                    $data['status'] = 'Sudah Diverikasi';
                    $data['color'] = 'teal';
                }
                return $data;
            })
            ->editColumn('status', function ($item) {
                return ucfirst($item->status);
            })
            ->addColumn('action', function ($item) {
                return ['id' => $item->id_alumni];
            })
            ->make(true);
    }

    public function addTracerAlumni(Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_jurusan = Jurusan::all();
        $data_kelas = Kelas::where('is_aktif', 1)->where('tingkat', 12)->orWhere('tingkat', 9)->orWhere('tingkat', 3)->get();
        $alumni = null;

        return view('humas.alumni.tracer-alumni.add-edit-tracer-alumni', compact('auth_data', 'data_jurusan', 'alumni', 'data_kelas'));
    }

    public function getJurusanByKelas(Request $request)
    {
        $input = (object) $request->input();
        $id_kelas = $input->id_kelas;
        if (!$id_kelas) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID Kelas tidak ditemukan'
            ]);
        }
        try {
            $kelas = Kelas::with('jurusan')->find($id_kelas);
            if (!$kelas || !$kelas->jurusan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jurusan tidak ditemukan untuk kelas ini'
                ]);
            }
            return response()->json([
                'status' => 'success',
                'data' => [
                    'id_jurusan' => $kelas->jurusan->id_jurusan,
                    'nm_jurusan' => $kelas->jurusan->nm_jurusan
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengambil data jurusan'
            ]);
        }
    }

    public function getSiswaByKelas(Request $request)
    {
        $input = (object) $request->input();
        $id_kelas = $input->id_kelas;
        if (!$id_kelas) {
            return response()->json([
                'status' => 'error',
                'message' => 'ID Kelas tidak ditemukan'
            ]);
        }
        $kelas = Kelas::with('jurusan')->find($id_kelas);
        $siswa = Siswa::whereHas('kelas', function ($query) use ($id_kelas) {
            $query->where('id_kelas', $id_kelas);
        })
            ->with(['calon_siswa', 'kelas'])
            ->get()
            ->map(function ($item) {
                return [
                    'id_c_siswa' => $item->id_c_siswa,
                    'nama_siswa' => $item->calon_siswa->nm_c_siswa ?? 'Nama tidak tersedia',
                    'nis' => $item->nis_siswa ?? '-'
                ];
            });
        return response()->json([
            'status' => 'success',
            'data' => $siswa,
            'jurusan' => $kelas && $kelas->jurusan ? [
                'id_jurusan' => $kelas->jurusan->id_jurusan,
                'nm_jurusan' => $kelas->jurusan->nm_jurusan
            ] : null
        ]);
    }

    public function editTracerAlumni($id, Request $request)
    {
        # code...
        $input = (object) $request->input();
        $auth_data = auth_data();

        $data_jurusan = Jurusan::all();

        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpmuh6krian' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm2') {
            $data_kelas = Kelas::where('is_aktif', 1)->where('tingkat', 9)->get();
            $alumni = Alumni::where('id_alumni', $id)->with('smp', 'calon_siswa')->first();
            // return view('humas.alumni.tracer-alumni.add-edit-tracer-alumni-smp', compact('auth_data', 'data_jurusan', 'alumni', 'data_kelas'));
        } else {
            $data_kelas = Kelas::where('is_aktif', 1)->where('tingkat', 12)->orWhere('tingkat', 3)->get();
            $alumni = Alumni::where('id_alumni', $id)->with('calon_siswa')->first();
        }
        return view('humas.alumni.tracer-alumni.add-edit-tracer-alumni', compact('auth_data', 'data_jurusan', 'alumni', 'data_kelas'));
    }

    public function actionTracerAlumni(Request $request, $mode, $id = null)
    {

        $input = (object) $request->input();
        $auth_data = auth_data();

        if ($mode == 'add') {
            DB::beginTransaction();

            try {

                if ($request->filled('id_c_siswa')) {
                    $calon_siswa = CalonSiswaBaru::find($request->id_c_siswa);

                    if (!$calon_siswa) {
                        return error_response('Data siswa tidak ditemukan');
                    }
                    $calon_siswa->nomor_hp = $request->nomor_hp;
                    $calon_siswa->alamat_jalan = $request->alamat_siswa;
                    if ($request->filled('jurusan')) {
                        $calon_siswa->id_jurusan = $request->jurusan;
                    }
                    $calon_siswa->updated_by = $auth_data->pengguna->id_pengguna;
                    $calon_siswa->save();

                    $id_c_siswa = $calon_siswa->id_c_siswa;
                } else {
                    $data['nm_c_siswa'] = $request->nama_siswa;
                    $data['nomor_hp'] = $request->nomor_hp;
                    $data['id_jurusan'] = $request->jurusan;
                    $data['alamat_jalan'] = $request->alamat_siswa;
                    $data['id_c_siswa'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                    $data['id_penerimaan'] = 0;
                    $data['status_verifikasi'] = 0;
                    $data['created_by'] = $auth_data->pengguna->id_pengguna;
                    $data['created_at'] = Carbon::now();

                    CalonSiswaBaru::insert($data);

                    $data2 = [
                        'id_c_siswa' => $data['id_c_siswa'],
                        'created_by' => $auth_data->pengguna->id_pengguna,
                        'created_at' => Carbon::now()
                    ];

                    CalonSiswaOrtu::insert($data2);
                    CalonSiswaFisik::insert($data2);
                    CalonSiswaSekolah::insert($data2);

                    $data3['nm_pengguna'] = $request->nama_siswa;
                    $data3['email_pengguna'] = $request->email;
                    $data3['nomor_hp_pengguna'] = $request->nomor_hp;
                    $data3['username'] = str_replace(' ', '_', $request->nama_siswa);
                    $data3['id_status_pengguna'] = StatusPengguna::where('nm_status_pengguna', 'Lulus')->first()->id_status_pengguna;
                    $data3['id_sekolah'] = $auth_data->pengguna->id_sekolah;
                    $data3['id_pengguna'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                    $data3['created_by'] = $auth_data->pengguna->id_pengguna;
                    $data3['created_at'] = Carbon::now();

                    Pengguna::insert($data3);

                    Siswa::insert([
                        'id_siswa' => $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid(),
                        'id_pengguna' => $data3['id_pengguna'],
                        'id_c_siswa' => $data['id_c_siswa'],
                        'created_by' => $auth_data->pengguna->id_pengguna,
                        'created_at' => Carbon::now()
                    ]);

                    $id_c_siswa = $data['id_c_siswa'];
                }

                $data4['id_kelas'] = $request->id_kelas;
                $data4['email'] = $request->email;
                $data4['tahun_lulus'] = $request->tahun_lulus;
                $data4['url_medsos'] = $request->url_medsos;
                $data4['status'] = $request->status;
                $data4['id_alumni'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                $data4['id_c_siswa'] = $id_c_siswa;
                $data4['created_by'] = $auth_data->pengguna->id_pengguna;
                $data4['created_at'] = Carbon::now();

                Alumni::insert($data4);

                switch ($request->status) {
                    case 'bekerja':
                        $data5 = $request->only(self::FETCH_WORK_ATTRIBUTE);
                        $data5['id_alumni_bekerja'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'usaha':
                        $data5 = $request->only(self::FETCH_ENTERPRENEUR_ATTRIBUTE);
                        $data5['id_alumni_wirausaha'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'kuliah':
                        $data5 = $request->only(self::FETCH_COLLEGE_ATTRIBUTE);
                        $data5['id_alumni_kuliah'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'menunggu':
                        $data5 = $request->only(self::FETCH_IDLE_ATTRIBUTE);
                        $data5['id_alumni_menunggu'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'smp';
                        $data5 = $request->only(self::FETCH_SMP);
                        $data5['id_alumni_smp'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                }

                $data5['id_alumni'] = $data4['id_alumni'];
                $data5['created_by'] = $auth_data->pengguna->id_pengguna;
                $data5['created_at'] = Carbon::now();

                if ($request->status == 'bekerja') {
                    LibAlumni::storeWorkplace($data5);
                } elseif ($request->status == 'usaha') {
                    LibAlumni::storeBusiness($data5);
                } elseif ($request->status == 'kuliah') {
                    LibAlumni::storeUniversity($data5);
                } elseif ($request->status == 'menunggu') {
                    LibAlumni::storeIdleAlumni($data5);
                } elseif ($request->status == 'smp') {
                    LibAlumni::storeSMP($data5);
                }

                DB::commit();
                return web_response(202, "Update Successfully", self::PATH);
            } catch (\Exception $e) {
                DB::rollback();
                return error_response($e);
            }
        } elseif ($mode == 'add2') {

            DB::beginTransaction();

            try {

                $jurusan = CalonSiswaBaru::where('id_c_siswa', $request->id_c_siswa)->first();
                $jurusan->id_jurusan = $request->jurusan;
                $jurusan->alamat_jalan = $request->alamat_siswa;
                $jurusan->nomor_hp = $request->nomor_hp;
                $jurusan->save();

                $data4['id_kelas'] = $request->id_kelas;
                $data4['email'] = $request->email;
                $data4['tahun_lulus'] = $request->tahun_lulus;
                $data4['status'] = $request->status;
                $data4['url_medsos'] = $request->url_medsos;
                $data4['id_alumni'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                $data4['id_c_siswa'] = $request->id_c_siswa;
                $data4['created_by'] = $auth_data->pengguna->id_pengguna;
                $data4['created_at'] = Carbon::now();

                Alumni::insert($data4);

                switch ($request->status) {
                    case 'bekerja':
                        $data5 = $request->only(self::FETCH_WORK_ATTRIBUTE);
                        $data5['id_alumni_bekerja'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'usaha':
                        $data5 = $request->only(self::FETCH_ENTERPRENEUR_ATTRIBUTE);
                        $data5['id_alumni_wirausaha'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'kuliah':
                        $data5 = $request->only(self::FETCH_COLLEGE_ATTRIBUTE);
                        $data5['id_alumni_kuliah'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'menunggu':
                        $data5 = $request->only(self::FETCH_IDLE_ATTRIBUTE);
                        $data5['id_alumni_menunggu'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'smp';
                        $data5 = $request->only(self::FETCH_SMP);
                        $data5['id_alumni_smp'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                }

                $data5['id_alumni'] = $data4['id_alumni'];
                $data5['created_by'] = $auth_data->pengguna->id_pengguna;
                $data5['created_at'] = Carbon::now();

                if ($request->status == 'bekerja') {
                    LibAlumni::storeWorkplace($data5);
                } elseif ($request->status == 'usaha') {
                    LibAlumni::storeBusiness($data5);
                } elseif ($request->status == 'kuliah') {
                    LibAlumni::storeUniversity($data5);
                } elseif ($request->status == 'menunggu') {
                    LibAlumni::storeIdleAlumni($data5);
                } elseif ($request->status == 'smp') {
                    LibAlumni::storeSMP($data5);
                }

                DB::commit();


                if (Siswa::where('id_pengguna', $auth_data->pengguna->id_pengguna)->where('id_kelas', null)->first()) {
                    return web_response(202, "Update Successfully", self::PATH);
                } else {
                    return web_response(202, "Update Successfully", self::PATH2);
                }
            } catch (\Exception $e) {

                DB::rollback();

                return error_response($e);
            }
        } elseif ($mode == 'edit') {
            DB::beginTransaction();

            try {
                $jurusan = CalonSiswaBaru::where('id_c_siswa', $request->id_c_siswa)->first();
                $jurusan->id_jurusan = $request->jurusan;
                $jurusan->alamat_jalan = $request->alamat_siswa;
                $jurusan->nomor_hp = $request->nomor_hp;
                $jurusan->save();

                $alumni = Alumni::where('id_alumni', $request->id_alumni)->first();
                $alumni->id_kelas = $request->id_kelas;
                $alumni->email = $request->email;
                $alumni->tahun_lulus = $request->tahun_lulus;
                $alumni->url_medsos = $request->url_medsos;
                $alumni->status = $request->status;
                $alumni->updated_by = $auth_data->pengguna->id_pengguna;
                $alumni->save();

                switch ($request->status) {
                    case 'bekerja':
                        $data5 = $request->only(self::FETCH_WORK_ATTRIBUTE);
                        $data5['id_alumni_bekerja'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'usaha':
                        $data5 = $request->only(self::FETCH_ENTERPRENEUR_ATTRIBUTE);
                        $data5['id_alumni_wirausaha'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'kuliah':
                        $data5 = $request->only(self::FETCH_COLLEGE_ATTRIBUTE);
                        $data5['id_alumni_kuliah'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'menunggu':
                        $data5 = $request->only(self::FETCH_IDLE_ATTRIBUTE);
                        $data5['id_alumni_menunggu'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                    case 'smp';
                        $data5 = $request->only(self::FETCH_SMP);
                        $data5['id_alumni_smp'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                        break;
                }

                $data5['id_alumni'] = $request->id_alumni;
                $data5['updated_by'] = $auth_data->pengguna->id_pengguna;
                $data5['created_by'] = $auth_data->pengguna->id_pengguna;
                $data5['created_at'] = Carbon::now();

                if ($request->old_status == 'bekerja') {
                    $hapus_old_status = AlumniBekerja::where('id_alumni', $request->id_alumni)->first();
                    $hapus_old_status->delete();
                } elseif ($request->old_status == 'usaha') {
                    $hapus_old_status = AlumniWirausaha::where('id_alumni', $request->id_alumni)->first();
                    $hapus_old_status->delete();
                } elseif ($request->old_status == 'kuliah') {
                    $hapus_old_status = AlumniKuliah::where('id_alumni', $request->id_alumni)->first();
                    $hapus_old_status->delete();
                } elseif ($request->old_status == 'menunggu') {
                    $hapus_old_status = AlumniMenunggu::where('id_alumni', $request->id_alumni)->first();
                    $hapus_old_status->delete();
                }
                if ($request->status == 'bekerja') {
                    LibAlumni::storeWorkplace($data5);
                } elseif ($request->status == 'usaha') {
                    LibAlumni::storeBusiness($data5);
                } elseif ($request->status == 'kuliah') {
                    LibAlumni::storeUniversity($data5);
                } elseif ($request->status == 'menunggu') {
                    LibAlumni::storeIdleAlumni($data5);
                } elseif ($request->status == 'smp') {
                    $hapus_old_status = AlumniSmp::where('id_alumni', $request->id_alumni)->first();
                    $hapus_old_status->delete();
                    LibAlumni::storeSMP($data5);
                }

                DB::commit();
                if ($cek = Siswa::where('id_pengguna', $auth_data->pengguna->id_pengguna)->first()) {
                    if (Siswa::where('id_pengguna', $auth_data->pengguna->id_pengguna)->where('id_kelas', null)->first()) {
                        return web_response(202, "Update Successfully", self::PATH);
                    } else {
                        return web_response(202, "Update Successfully", self::PATH2);
                    }
                } else {
                    if ($auth_data->pengguna->role_pengguna->where('is_aktif', 1)->first()->id_role == '2') {
                        return web_response(202, "Update Successfully", self::PATHGURU);
                    }
                    return web_response(202, "Update Successfully", self::PATH);
                }
            } catch (\Exception $e) {

                DB::rollback();

                return error_response($e);
            }
        } elseif ($mode == 'delete') {

            $alumni = Alumni::find($id);
            $alumni->deleted_by = auth_data()->pengguna->id_pengguna;
            $alumni->save();

            $alumni->delete();

            if ($request->segment(1) == 'siswa') {
                return [
                    'status' => 202, // SUCCESS AND LOAD TABLE
                    'path' => 'tracer-alumni',
                    'message' => 'Delete Alumni Successfully'
                ];
            } else {
                return [
                    'status' => 202, // SUCCESS AND LOAD TABLE
                    'path' => $request->segment(1) . '#alumni/tracer-alumni',
                    'message' => 'Delete Alumni Successfully'
                ];
            }
        }
    }

    public function importTracerAlumni()
    {
        return view('humas.alumni.tracer-alumni.import-tracer-alumni');
    }

    public function handleImportTracerAlumni(Request $request)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();

        $request->validate([
            'file-excel' => 'required|mimes:xlsx,xls'
        ]);

        DB::beginTransaction();

        try {
            $data = Excel::toArray(new DataImportExcel, $request->file('file-excel'))[0];
            $nis_gagal = [];
            $nis_berhasil = [];

            foreach ($data as $index => $row) {
                $nis = $row['nis'];

                if (empty($nis)) {
                    continue;
                }

                $siswa = Siswa::where('nis_siswa', $nis)->first();

                if ($siswa) {
                    $existing_alumni = Alumni::where('id_c_siswa', $siswa->id_c_siswa)->first();
                    if ($existing_alumni) {
                        $nis_gagal[] = [
                            'nis' => $nis,
                            'nama' => $row['nama_lengkap'],
                            'alasan' => 'Sudah terdaftar sebagai Alumni'
                        ];
                        continue;
                    }

                    if ($siswa->id_c_siswa) {
                        $calon_siswa = CalonSiswaBaru::find($siswa->id_c_siswa);

                        if (!$calon_siswa) {
                            $nis_gagal[] = [
                                'nis' => $nis,
                                'nama' => $row['nama_lengkap'],
                                'alasan' => 'Data calon siswa tidak ditemukan'
                            ];
                            continue;
                        }

                        $calon_siswa->nomor_hp = $row['nomor_teleponhpwa'];
                        $calon_siswa->alamat_jalan = $row['alamat'];
                        $jurusan = Jurusan::where('nm_jurusan', $row['jurusan'])->first();
                        $calon_siswa->id_jurusan = $jurusan ? $jurusan->id_jurusan : null;
                        $calon_siswa->updated_by = $auth_data->pengguna->id_pengguna;
                        $calon_siswa->save();

                        $id_c_siswa = $calon_siswa->id_c_siswa;
                    } else {
                        $calon_siswa_data = [
                            'nm_c_siswa' => $row['nama_lengkap'],
                            'nomor_hp' => $row['nomor_teleponhpwa'],
                            'alamat_jalan' => $row['alamat'],
                            'id_c_siswa' => $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid(),
                            'id_penerimaan' => 0,
                            'status_verifikasi' => 0,
                            'created_by' => $auth_data->pengguna->id_pengguna,
                            'created_at' => Carbon::now()
                        ];

                        $jurusan = Jurusan::where('nm_jurusan', $row['jurusan'])->first();
                        $calon_siswa_data['id_jurusan'] = $jurusan ? $jurusan->id_jurusan : null;

                        CalonSiswaBaru::insert($calon_siswa_data);

                        $related_data = [
                            'id_c_siswa' => $calon_siswa_data['id_c_siswa'],
                            'created_by' => $auth_data->pengguna->id_pengguna,
                            'created_at' => Carbon::now()
                        ];

                        CalonSiswaOrtu::insert($related_data);
                        CalonSiswaFisik::insert($related_data);
                        CalonSiswaSekolah::insert($related_data);

                        $pengguna_data = [
                            'nm_pengguna' => $row['nama_lengkap'],
                            'email_pengguna' => $row['email'],
                            'nomor_hp_pengguna' => $row['nomor_teleponhpwa'],
                            'username' => str_replace(' ', '_', $row['nama_lengkap']),
                            'id_status_pengguna' => StatusPengguna::where('nm_status_pengguna', 'Lulus')->first()->id_status_pengguna,
                            'id_sekolah' => $auth_data->pengguna->id_sekolah,
                            'id_pengguna' => $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid(),
                            'created_by' => $auth_data->pengguna->id_pengguna,
                            'created_at' => Carbon::now()
                        ];

                        Pengguna::insert($pengguna_data);

                        Siswa::insert([
                            'id_siswa' => $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid(),
                            'id_pengguna' => $pengguna_data['id_pengguna'],
                            'id_c_siswa' => $calon_siswa_data['id_c_siswa'],
                            'nis_siswa' => $nis,
                            'created_by' => $auth_data->pengguna->id_pengguna,
                            'created_at' => Carbon::now()
                        ]);

                        $id_c_siswa = $calon_siswa_data['id_c_siswa'];
                    }

                    $kelas = Kelas::where('nm_kelas', $row['kelas'])->first();
                    $alumni_data = [
                        'id_alumni' => $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid(),
                        'id_c_siswa' => $id_c_siswa,
                        'id_kelas' => $kelas ? $kelas->id_kelas : null,
                        'email' => $row['email'],
                        'tahun_lulus' => $row['tahun_lulus'],
                        'status' => $request->status,
                        'url_medsos' => $row['url_medsos'],
                        'created_by' => $auth_data->pengguna->id_pengguna,
                        'created_at' => Carbon::now()
                    ];

                    Alumni::insert($alumni_data);

                    $status_data = [
                        'id_alumni' => $alumni_data['id_alumni'],
                        'created_by' => $auth_data->pengguna->id_pengguna,
                        'created_at' => Carbon::now()
                    ];

                    switch ($request->status) {
                        case 'bekerja':
                            $status_data['id_alumni_bekerja'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                            $status_data['nm_instansi'] = $row['nama_instansi'];
                            $status_data['alamat_instansi'] = $row['alamat_instansi'];
                            $status_data['kontak_instansi'] = $row['kontak_instansi'];
                            $status_data['bidang_usaha_instansi'] = $row['bidang_usaha_instansi'];
                            $status_data['tahun_masuk_instansi'] = $row['tahun_masuk_instansi'];
                            $status_data['kapan_mulai_bekerja'] = $row['kapan_mulai_bekerja'];
                            $status_data['lama_bekerja'] = $row['lama_bekerja'];
                            LibAlumni::storeWorkplace($status_data);
                            break;

                        case 'usaha':
                            $status_data['id_alumni_wirausaha'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                            $status_data['nm_usaha'] = $row['nama_usaha'];
                            $status_data['alamat_usaha'] = $row['alamat_usaha'];
                            $status_data['kontak_usaha'] = $row['kontak_usaha'];
                            $status_data['bidang_usaha'] = $row['bidang_usaha'];
                            $status_data['jumlah_karyawan'] = $row['jumlah_karyawan'];
                            $status_data['tahun_rintis'] = $row['tahun_rintis'];
                            LibAlumni::storeBusiness($status_data);
                            break;

                        case 'kuliah':
                            $status_data['id_alumni_kuliah'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                            $status_data['nm_perguruan'] = $row['nama_perguruan_tinggi'];
                            $status_data['alamat_perguruan'] = $row['alamat_perguruan_tinggi'];
                            $status_data['fakultas'] = $row['fakultas'];
                            $status_data['prodi'] = $row['program_studi'];
                            $status_data['jenjang'] = $row['jenjang'];
                            $status_data['tahun_masuk_perguruan'] = $row['tahun_masuk_perguruan_tinggi'];
                            LibAlumni::storeUniversity($status_data);
                            break;

                        case 'menunggu':
                            $status_data['id_alumni_menunggu'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                            $status_data['status_menunggu'] = $row['status_menunggu'];
                            LibAlumni::storeIdleAlumni($status_data);
                            break;
                    }

                    $nis_berhasil[] = [
                        'nis' => $nis,
                        'nama' => $row['nama_lengkap'],
                        'status' => ucfirst($request->status)
                    ];
                } else {
                    $nis_gagal[] = [
                        'nis' => $nis,
                        'nama' => $row['nama_lengkap'],
                        'alasan' => 'Siswa tidak ditemukan dengan NIS ini'
                    ];
                }
            }

            DB::commit();

            $total_berhasil = count($nis_berhasil);
            $total_gagal = count($nis_gagal);
            $total_data = $total_berhasil + $total_gagal;

            if ($total_gagal > 0 && $total_berhasil > 0) {
                return response()->json([
                    'status' => 202,
                    'message' => "Import selesai! {$total_berhasil} dari {$total_data} data berhasil diimpor.",
                    'nis_berhasil' => $nis_berhasil,
                    'nis_gagal' => $nis_gagal,
                    'summary' => [
                        'total_data' => $total_data,
                        'berhasil' => $total_berhasil,
                        'gagal' => $total_gagal
                    ]
                ]);
            } else if ($total_gagal > 0 && $total_berhasil == 0) {
                return response()->json([
                    'status' => 300,
                    'message' => "Import gagal! Semua {$total_gagal} data gagal diimpor.",
                    'nis_gagal' => $nis_gagal,
                    'summary' => [
                        'total_data' => $total_data,
                        'berhasil' => $total_berhasil,
                        'gagal' => $total_gagal
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'message' => "Import berhasil! Semua {$total_berhasil} data alumni berhasil diimpor.",
                    'nis_berhasil' => $nis_berhasil,
                    'summary' => [
                        'total_data' => $total_data,
                        'berhasil' => $total_berhasil,
                        'gagal' => $total_gagal
                    ]
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Import Alumni Error: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Gagal mengimpor data: ' . $e->getMessage()
            ]);
        }
    }

    public function downloadFileExcelAlumniBekerja()
    {
        $file = public_path() . "/excel/ContohFileExcelUploadAlumniBekerja.xlsx";
        $headers = [
            'Content-Type' => 'application/xls',
        ];

        return response()->download($file, 'ContohFileExcelUploadAlumniBekerja.xlsx', $headers);
    }

    public function downloadFileExcelAlumniWirausaha()
    {
        $file = public_path() . "/excel/ContohFileExcelUploadAlumniWirausaha.xlsx";
        $headers = [
            'Content-Type' => 'application/xls',
        ];

        return response()->download($file, 'ContohFileExcelUploadAlumniWirausaha.xlsx', $headers);
    }

    public function downloadFileExcelAlumniKuliah()
    {
        $file = public_path() . "/excel/ContohFileExcelUploadAlumniKuliah.xlsx";
        $headers = [
            'Content-Type' => 'application/xls',
        ];

        return response()->download($file, 'ContohFileExcelUploadAlumniKuliah.xlsx', $headers);
    }

    public function downloadFileExcelAlumniMenunggu()
    {
        $file = public_path() . "/excel/ContohFileExcelUploadAlumniMenunggu.xlsx";
        $headers = [
            'Content-Type' => 'application/xls',
        ];

        return response()->download($file, 'ContohFileExcelUploadAlumniMenunggu.xlsx', $headers);
    }

    public function cetakTracerAlumni(Request $request, $id_kelas = null, $tahun_lulus = null)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $data_kelas = Kelas::where('is_aktif', 1)->where('tingkat', 9)->get();
        return view('humas.alumni.tracer-alumni.export-tracer-alumni', compact('auth_data', 'data_kelas', 'id_kelas', 'tahun_lulus'));
    }
    public function cetakTracerAlumni2(Request $request, $id_kelas = null, $tahun_lulus = null)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        $data_kelas = Kelas::where('is_aktif', 1)->where('tingkat', 12)->orWhere('tingkat', 3)->get();
        return view('humas.alumni.tracer-alumni.export-tracer-alumni2', compact('auth_data', 'data_kelas', 'id_kelas', 'tahun_lulus'));
    }

    public function changeTracerAlumni(Request $request)
    {
        $input = (object) $request->input();
        return [
            'status' => 204, // SUCCESS AND LOAD CONTENT
            'path' => 'alumni/tracer-alumni/cetak/' . $input->id_kelas . '/' . $input->tahun_lulus,
        ];
    }


    public function changeTracerAlumni2(Request $request)
    {
        $input = (object) $request->input();
        return [
            'status' => 204, // SUCCESS AND LOAD CONTENT
            'path' => 'alumni/tracer-alumni/cetak2/' . $input->id_kelas . '/' . $input->tahun_lulus,
        ];
    }



    public function exportAlumnni(Request $request, $id_kelas, $tahun_lulus)
    {
        $input = (object) $request->input();
        $alumni = Alumni::where('id_kelas', $id_kelas)->where('tahun_lulus', $tahun_lulus)->with('smp', 'calon_siswa', 'kelas')->get();
        return Excel::download(new ExportAlumni($alumni), 'download_alumni.xlsx');
    }

    public function exportAlumnniPdf(Request $request, $id_kelas, $tahun_lulus)
    {

        $input = (object) $request->input();
        $alumni = Alumni::where('id_kelas', $id_kelas)->where('tahun_lulus', $tahun_lulus)->with('smp', 'calon_siswa', 'kelas')->get();
        // dd($alumni);
        // return response()->json($alumni);
        return view('humas.alumni.tracer-alumni.export-tracer-alumni-pdf', compact('alumni'));
    }


    public function exportAlumnni2(Request $request, $id_kelas, $tahun_lulus)
    {
        $input = (object) $request->input();
        if ($id_kelas == 'all') {
            $alumni['alumni_bekerja'] = Alumni::where('tahun_lulus', $tahun_lulus)->where('status', 'bekerja')->with('calon_siswa', 'kelas', 'bekerja')->get();
            $alumni['alumni_kuliah'] = Alumni::where('tahun_lulus', $tahun_lulus)->where('status', 'kuliah')->with('calon_siswa', 'kelas', 'kuliah')->get();
            $alumni['alumni_menunggu'] = Alumni::where('tahun_lulus', $tahun_lulus)->where('status', 'menunggu')->with('calon_siswa', 'kelas', 'menunggu')->get();
            $alumni['alumni_wirausaha'] = Alumni::where('tahun_lulus', $tahun_lulus)->where('status', 'usaha')->with('calon_siswa', 'kelas', 'usaha')->get();
        } else {
            $alumni['alumni_bekerja'] = Alumni::where('id_kelas', $id_kelas)->where('tahun_lulus', $tahun_lulus)->where('status', 'bekerja')->with('calon_siswa', 'kelas', 'bekerja')->get();
            $alumni['alumni_kuliah'] = Alumni::where('id_kelas', $id_kelas)->where('tahun_lulus', $tahun_lulus)->where('status', 'kuliah')->with('calon_siswa', 'kelas', 'kuliah')->get();
            $alumni['alumni_menunggu'] = Alumni::where('id_kelas', $id_kelas)->where('tahun_lulus', $tahun_lulus)->where('status', 'menunggu')->with('calon_siswa', 'kelas', 'menunggu')->get();
            $alumni['alumni_wirausaha'] = Alumni::where('id_kelas', $id_kelas)->where('tahun_lulus', $tahun_lulus)->where('status', 'usaha')->with('calon_siswa', 'kelas', 'usaha')->get();
        }
        // $alumni = $data;
        // dd($alumni);
        return Excel::download(new ExportAlumni2($alumni), 'download_alumni.xlsx');
    }


    public function datatablesCetakTracerAlumni(Request $request, $id_kelas, $tahun_lulus)
    {
        $input = (object) $request->input();
        $auth_data = auth_data();
        if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smpmuh6krian' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm1' || $auth_data->sekolah_data->nm_singkat_sekolah == 'smpypm2') {
            $alumnis = LibAlumni::getAlumnisSearchSmp($id_kelas, $tahun_lulus);
        } else {
            if ($id_kelas == 'all') {
                $alumnis = LibAlumni::getAlumnisSearchWithoutKelas($tahun_lulus);
            } else {
                $alumnis = LibAlumni::getAlumnisSearch($id_kelas, $tahun_lulus);
            }
        }

        return Datatables::of($alumnis)->make(true);
    }
}

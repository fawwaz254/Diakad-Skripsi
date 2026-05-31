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
        $sekolah = $auth_data->sekolah_data->nm_singkat_sekolah ?? null;


        if (in_array($sekolah, ['smpmuh6krian', 'smpypm1', 'smpypm2'])) {
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

    public function downloadFileExcelAlumniSMP()
    {
        $file = public_path() . "/excel/ContohFileExelUploadAlumniSmp_v2.xlsx";
        $headers = [
            'Content-Type' => 'application/xlsx',
        ];

        return response()->download($file, 'ContohFileExelUploadAlumniSmp_v2.xlsx', $headers);
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



// ============================================================
// REFACTORED v2: actionTracerAlumni dan method-method pendukungnya
// switch + if-else status alumni digabung ke buildAndStoreAlumniStatus()
// Ganti method lama di TracerAlumniController dengan kode ini
// ============================================================

    /**
     * Entry point untuk CRUD tracer alumni.
     * Mendelegasikan ke method yang lebih spesifik berdasarkan $mode.
     */
    public function actionTracerAlumni(Request $request, $mode, $id = null)
    {
        return match ($mode) {
            'add'    => $this->handleAdd($request),
            'add2'   => $this->handleAdd2($request),
            'edit'   => $this->handleEdit($request),
            'delete' => $this->handleDelete($request, $id),
            default  => abort(404),
        };
    }

    // ----------------------------------------------------------------
    // MODE: add — tambah alumni baru (siswa baru atau siswa yang sudah ada)
    // ----------------------------------------------------------------

    private function handleAdd(Request $request)
    {
        $authData = auth_data();

        DB::beginTransaction();
        try {
            $idCalonSiswa = $request->filled('id_c_siswa')
                ? $this->updateExistingStudent($request, $authData)
                : $this->createNewStudent($request, $authData);

            $alumni = $this->insertAlumni($request, $authData, $idCalonSiswa);
            $this->buildAndStoreAlumniStatus($request, $authData, $alumni['id_alumni']);

            DB::commit();
            return web_response(202, "ADD Successfully", self::PATH);
        } catch (\Exception $e) {
            DB::rollback();
            return error_response($e);
        }
    }

    // ----------------------------------------------------------------
    // MODE: add2 — alumni mengisi sendiri datanya (self-service)
    // ----------------------------------------------------------------

    private function handleAdd2(Request $request)
    {
        $authData = auth_data();

        DB::beginTransaction();
        try {
            $this->updateStudentProfile($request, $authData);

            $alumni = $this->insertAlumni($request, $authData, $request->id_c_siswa);
            $this->buildAndStoreAlumniStatus($request, $authData, $alumni['id_alumni']);

            DB::commit();

            $redirectPath = Siswa::where('id_pengguna', $authData->pengguna->id_pengguna)
                ->whereNull('id_kelas')
                ->exists()
                    ? self::PATH
                    : self::PATH2;

            return web_response(202, "ADD Successfully", $redirectPath);
        } catch (\Exception $e) {
            DB::rollback();
            return error_response($e);
        }
    }

    // ----------------------------------------------------------------
    // MODE: edit — ubah data alumni yang sudah ada
    // ----------------------------------------------------------------

    private function handleEdit(Request $request)
    {
        $authData = auth_data();

        DB::beginTransaction();
        try {
            $this->updateStudentProfile($request, $authData);
            $this->updateAlumniRecord($request, $authData);
            $this->deleteOldAlumniStatus($request->old_status, $request->id_alumni);
            $this->buildAndStoreAlumniStatus($request, $authData, $request->id_alumni, isUpdate: true);

            DB::commit();
            return web_response(202, "Update Successfully", $this->resolveEditRedirectPath($request, $authData));
        } catch (\Exception $e) {
            DB::rollback();
            return error_response($e);
        }
    }

    // ----------------------------------------------------------------
    // MODE: delete — hapus (soft delete) data alumni
    // ----------------------------------------------------------------

        private function handleDelete(Request $request, $id)
    {
        $alumni = Alumni::find($id);
        $alumni->deleted_by = auth_data()->pengguna->id_pengguna;
        $alumni->save();
        $alumni->delete();

        return [
            'status'  => 202,
            'path'    => 'alumni/tracer-alumni',
            'message' => 'Delete Alumni Successfully'
        ];
    }

    // ----------------------------------------------------------------
    // HELPER: Operasi pada data siswa / calon siswa
    // ----------------------------------------------------------------

    /**
     * Update data calon siswa yang sudah ada (saat mode add dengan id_c_siswa terisi).
     * Mengembalikan id_c_siswa.
     */
    private function updateExistingStudent(Request $request, $authData): string
    {
        $calonSiswa = CalonSiswaBaru::find($request->id_c_siswa);

        if (!$calonSiswa) {
            throw new \Exception('Data siswa tidak ditemukan');
        }

        $calonSiswa->nomor_hp     = $request->nomor_hp;
        $calonSiswa->alamat_jalan = $request->alamat_siswa;
        if ($request->filled('jurusan')) {
            $calonSiswa->id_jurusan = $request->jurusan;
        }
        $calonSiswa->updated_by = $authData->pengguna->id_pengguna;
        $calonSiswa->save();

        return $calonSiswa->id_c_siswa;
    }

    /**
     * Buat data siswa baru lengkap dengan akun pengguna.
     * Mengembalikan id_c_siswa yang baru dibuat.
     */
    private function createNewStudent(Request $request, $authData): string
    {
        $now            = Carbon::now();
        $prefix         = $authData->sekolah_data->prefix;
        $idPengguna     = $authData->pengguna->id_pengguna;
        $idCalonSiswa   = $prefix . strtotime($now) . uniqid();
        $idPenggunaBaru = $prefix . strtotime($now) . uniqid();

        // 1. Calon siswa baru
        CalonSiswaBaru::insert([
            'id_c_siswa'        => $idCalonSiswa,
            'nm_c_siswa'        => $request->nama_siswa,
            'nomor_hp'          => $request->nomor_hp,
            'id_jurusan'        => $request->jurusan,
            'alamat_jalan'      => $request->alamat_siswa,
            'id_penerimaan'     => 0,
            'status_verifikasi' => 0,
            'created_by'        => $idPengguna,
            'created_at'        => $now,
        ]);

        // 2. Data relasi calon siswa (ortu, fisik, sekolah)
        $relatedData = ['id_c_siswa' => $idCalonSiswa, 'created_by' => $idPengguna, 'created_at' => $now];
        CalonSiswaOrtu::insert($relatedData);
        CalonSiswaFisik::insert($relatedData);
        CalonSiswaSekolah::insert($relatedData);

        // 3. Akun pengguna
        Pengguna::insert([
            'id_pengguna'        => $idPenggunaBaru,
            'nm_pengguna'        => $request->nama_siswa,
            'email_pengguna'     => $request->email,
            'nomor_hp_pengguna'  => $request->nomor_hp,
            'username'           => str_replace(' ', '_', $request->nama_siswa),
            'id_status_pengguna' => StatusPengguna::where('nm_status_pengguna', 'Lulus')->value('id_status_pengguna'),
            'id_sekolah'         => $authData->pengguna->id_sekolah,
            'created_by'         => $idPengguna,
            'created_at'         => $now,
        ]);

        // 4. Record siswa
        Siswa::insert([
            'id_siswa'    => $prefix . strtotime($now) . uniqid(),
            'id_pengguna' => $idPenggunaBaru,
            'id_c_siswa'  => $idCalonSiswa,
            'created_by'  => $idPengguna,
            'created_at'  => $now,
        ]);

        return $idCalonSiswa;
    }

    /**
     * Update profil siswa (jurusan, alamat, nomor HP).
     * Dipakai di mode add2 dan edit.
     */
    private function updateStudentProfile(Request $request, $authData): void
    {
        $calonSiswa = CalonSiswaBaru::where('id_c_siswa', $request->id_c_siswa)->firstOrFail();
        $calonSiswa->id_jurusan   = $request->jurusan;
        $calonSiswa->alamat_jalan = $request->alamat_siswa;
        $calonSiswa->nomor_hp     = $request->nomor_hp;
        $calonSiswa->save();
    }

    // ----------------------------------------------------------------
    // HELPER: Operasi pada data alumni
    // ----------------------------------------------------------------

    /**
     * Insert record alumni baru dan mengembalikan array datanya.
     */
    private function insertAlumni(Request $request, $authData, string $idCalonSiswa): array
    {
        $now = Carbon::now();

        $alumniData = [
            'id_alumni'   => $authData->sekolah_data->prefix . strtotime($now) . uniqid(),
            'id_c_siswa'  => $idCalonSiswa,
            'id_kelas'    => $request->id_kelas,
            'email'       => $request->email,
            'tahun_lulus' => $request->tahun_lulus,
            'url_medsos'  => $request->url_medsos,
            'status'      => $request->status,
            'created_by'  => $authData->pengguna->id_pengguna,
            'created_at'  => $now,
        ];

        Alumni::insert($alumniData);
        return $alumniData;
    }

    /**
     * Update record alumni yang sudah ada.
     */
    private function updateAlumniRecord(Request $request, $authData): void
    {
        $alumni = Alumni::where('id_alumni', $request->id_alumni)->firstOrFail();
        $alumni->id_kelas    = $request->id_kelas;
        $alumni->email       = $request->email;
        $alumni->tahun_lulus = $request->tahun_lulus;
        $alumni->url_medsos  = $request->url_medsos;
        $alumni->status      = $request->status;
        $alumni->updated_by  = $authData->pengguna->id_pengguna;
        $alumni->save();
    }

    // ----------------------------------------------------------------
    // HELPER: Build sekaligus store data status alumni
    // (menggabungkan switch + if-else yang sebelumnya terpisah)
    // ----------------------------------------------------------------

    /**
     * Membangun data status alumni sekaligus menyimpannya via LibAlumni.
     * Menggantikan blok switch + if-elseif yang berulang di add, add2, dan edit.
     */
    private function buildAndStoreAlumniStatus(Request $request, $authData, string $idAlumni, bool $isUpdate = false): void
    {
        $now    = Carbon::now();
        $prefix = $authData->sekolah_data->prefix;

        $baseData = [
            'id_alumni'  => $idAlumni,
            'created_by' => $authData->pengguna->id_pengguna,
            'created_at' => $now,
        ];

        if ($isUpdate) {
            $baseData['updated_by'] = $authData->pengguna->id_pengguna;
        }

        switch ($request->status) {
            case 'bekerja':
                $statusData = array_merge(
                    $baseData,
                    $request->only(self::FETCH_WORK_ATTRIBUTE),
                    ['id_alumni_bekerja' => $prefix . strtotime($now) . uniqid()]
                );
                LibAlumni::storeWorkplace($statusData);
                break;

            case 'usaha':
                $statusData = array_merge(
                    $baseData,
                    $request->only(self::FETCH_ENTERPRENEUR_ATTRIBUTE),
                    ['id_alumni_wirausaha' => $prefix . strtotime($now) . uniqid()]
                );
                LibAlumni::storeBusiness($statusData);
                break;

            case 'kuliah':
                $statusData = array_merge(
                    $baseData,
                    $request->only(self::FETCH_COLLEGE_ATTRIBUTE),
                    ['id_alumni_kuliah' => $prefix . strtotime($now) . uniqid()]
                );
                LibAlumni::storeUniversity($statusData);
                break;

            case 'menunggu':
                $statusData = array_merge(
                    $baseData,
                    $request->only(self::FETCH_IDLE_ATTRIBUTE),
                    ['id_alumni_menunggu' => $prefix . strtotime($now) . uniqid()]
                );
                LibAlumni::storeIdleAlumni($statusData);
                break;

            case 'smp':
                $statusData = array_merge(
                    $baseData,
                    $request->only(self::FETCH_SMP),
                    ['id_alumni_smp' => $prefix . strtotime($now) . uniqid()]
                );
                LibAlumni::storeSMP($statusData);
                break;
        }
    }

    // ----------------------------------------------------------------
    // HELPER: Hapus status alumni lama saat edit
    // ----------------------------------------------------------------

    /**
     * Hapus record status alumni lama sebelum menyimpan status baru (saat edit).
     */
        private function deleteOldAlumniStatus(?string $oldStatus, string $idAlumni): void
    {
        if (!$oldStatus) return;

        $modelMap = [
            'bekerja'  => AlumniBekerja::class,
            'usaha'    => AlumniWirausaha::class,
            'kuliah'   => AlumniKuliah::class,
            'menunggu' => AlumniMenunggu::class,
            'smp'      => AlumniSmp::class,
        ];

        if (isset($modelMap[$oldStatus])) {
            $modelMap[$oldStatus]::where('id_alumni', $idAlumni)->first()?->delete();
        }
    }

    // ----------------------------------------------------------------
    // HELPER: Resolusi path redirect setelah edit
    // ----------------------------------------------------------------

    /**
     * Menentukan path redirect setelah proses edit selesai,
     * berdasarkan role dan kondisi kelas pengguna yang login.
     */
    private function resolveEditRedirectPath(Request $request, $authData): string
    {
        $pengguna = $authData->pengguna;

        // Jika user adalah siswa
        $siswa = Siswa::where('id_pengguna', $pengguna->id_pengguna)->first();
        if ($siswa) {
            return $siswa->id_kelas === null ? self::PATH : self::PATH2;
        }

        // Jika user adalah guru (role 2)
        $activeRole = $pengguna->role_pengguna->where('is_aktif', 1)->first();
        if ($activeRole && $activeRole->id_role == '2') {
            return self::PATHGURU;
        }

        return self::PATH;
    }

    public function importTracerAlumni()
    {
        return view('humas.alumni.tracer-alumni.import-tracer-alumni');
    }

    public function importTracerAlumnismp()
    {
        return view('humas.alumni.tracer-alumni.import-tracer-alumni-smp');
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
                        case 'smp':
                            $status_data['id_alumni_smp'] = $auth_data->sekolah_data->prefix . strtotime(Carbon::now()) . uniqid();
                            $status_data['nama_lengkap'] = $row['nama_lengkap'];
                            $status_data['nisn'] = $row['nisn'];
                            $status_data['jenjang'] = $row['jenjang'];
                            $status_data['nama_sekolah'] = $row['nama_sekolah'];
                            $status_data['alamat_sekolah'] = $row['alamat_sekolah'];
                            $status_data['jurusan'] = $row['jurusan'] ?? null;
                            $status_data['tahun_masuk'] = $row['tahun_masuk'];
                            LibAlumni::storeSMP($status_data);
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

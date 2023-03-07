<?php

namespace App\Http\Controllers\Akademik\Ujian;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use Yajra\Datatables\Datatables;

use App\Models\Semester as Semester;
use App\Models\Siswa as Siswa;
use App\Models\UjianMp as UjianMp;
use App\Models\UjianMpRuangan as UjianMpRuangan;
use App\Models\UjianMpPresensi as UjianMpPresensi;
use App\Models\KelasMp as KelasMp;
use App\Models\Kegiatan as Kegiatan;
use App\Models\Ruangan as Ruangan;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Libraries\Pendidikan\LibDataAkademik;
use App\Libraries\Pendidikan\LibSiswa;
use App\Libraries\Akademik\LibAkademik;

use Auth;
use DB;
use Session;
use Validator;

class UjianUTSController extends BaseController
{
  public function viewUjianUts(Request $request, $id = 0)
  {
    # code...
    $input = (object) $request->input();
    $auth_data = $input->auth_data;
    $semester = Semester::where('semester.is_aktif_semester', '=', 1)->first();

    return view('akademik/ujian/ujian-uts-reguler-online/view-ujian-uts-reguler-online', compact('auth_data', 'id', 'semester'));
  }

  public function addUjianUts(Request $request, $id)
  {
    # code..
    $input = (object) $request->input();
    $auth_data = $input->auth_data;
    $kegiatan     = Kegiatan::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->where('kode_kegiatan', '=', 'UTS')->get();

    return view('akademik/ujian/ujian-uts-reguler-online/view-add-ujian-uts-reguler-online', compact('auth_data', 'kegiatan', 'id'));
  }

  public function addDataUjianUts(Request $request, $online, $id)
  {
    # code..
    $input = (object) $request->input();
    $auth_data = $input->auth_data;
    $kegiatan     = Kegiatan::where('id_sekolah', '=', $auth_data->pengguna->id_sekolah)->where('kode_kegiatan', '=', 'UTS')->first();
    $kelas_mp = KelasMp::join('kelas', 'kelas_mp.id_kelas', '=', 'kelas.id_kelas')->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
      ->where('id_kelas_mp', '=', $id)->first();
    $ruangan  = Ruangan::join('gedung', 'gedung.id_gedung', '=', 'ruangan.id_gedung')->where('gedung.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();

    return view('akademik/ujian/ujian-uts-reguler-online/add-ujian-uts', compact('auth_data', 'kegiatan', 'id', 'online', 'kegiatan', 'kelas_mp', 'ruangan'));
  }

  public function editDataUjianUts(Request $request, $id_ujian_mp)
  {
    # code..
    $input = (object) $request->input();
    $auth_data = $input->auth_data;
    $ujian = UjianMp::select('ujian_mp.nm_ujian_mp', 'kegiatan.nm_kegiatan', 'ujian_mp.is_online', 'ujian_mp.tgl_ujian_mp', 'ujian_mp.jam_mulai', 'ujian_mp.jam_selesai', 'ujian_mp.keterangan', 'ruangan.nm_ruangan', 'ruangan.kapasitas_ujian', 'gedung.kode_gedung', 'ujian_mp.id_ujian_mp', 'ujian_mp.id_kelas_mp', 'kelas_mp.nm_kelas_mp', 'kelas_mp.id_semester', 'semester.nm_semester', 'semester.tahun_ajaran', 'mata_pelajaran.nm_mata_pelajaran', 'mata_pelajaran.kd_mata_pelajaran', 'ruangan.id_ruangan', 'kelas.nm_kelas', 'ujian_mp.id_kegiatan')
      ->join('kegiatan', 'kegiatan.id_kegiatan', '=', 'ujian_mp.id_kegiatan')
      ->leftJoin('ujian_mp_ruangan', 'ujian_mp_ruangan.id_ujian_mp', '=', 'ujian_mp.id_ujian_mp')
      ->leftJoin('ruangan', 'ujian_mp_ruangan.id_ruangan', '=', 'ruangan.id_ruangan')
      ->leftJoin('gedung', 'gedung.id_gedung', '=', 'ruangan.id_gedung')
      ->join('kelas_mp', 'kelas_mp.id_kelas_mp', '=', 'ujian_mp.id_kelas_mp')
      ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
      ->join('semester', 'semester.id_semester', '=', 'kelas_mp.id_semester')
      ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
      ->where('ujian_mp.id_ujian_mp', '=', $id_ujian_mp)
      ->first();
    $ruangan  = Ruangan::join('gedung', 'gedung.id_gedung', '=', 'ruangan.id_gedung')->where('gedung.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->get();
    $tgl_ujian_mp = strftime("%d %B %Y", strtotime($ujian->tgl_ujian_mp));


    return view('akademik/ujian/ujian-uts-reguler-online/edit-ujian-uts', compact('auth_data', 'ruangan', 'ujian', 'id_ujian_mp', 'tgl_ujian_mp'));
  }

  public function assignUjianUTS(Request $request, $id)
  {
    # code..
    $input = (object) $request->input();
    $auth_data = $input->auth_data;
    $kelas = UjianMp::join('kelas_mp', 'kelas_mp.id_kelas_mp', '=', 'ujian_mp.id_kelas_mp')
      ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
      ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
      ->where('id_ujian_mp', '=', $id)->first();
    $peserta = UjianMpPresensi::where('id_ujian_mp', '=', $id)->first();

    return view('akademik/ujian/ujian-uts-reguler-online/view-peserta-kelas', compact('auth_data', 'id', 'kelas', 'peserta'));
  }

  public function datatablesUjianUts(Request $request, $online)
  {
    $input = (object) $request->input();
    $auth_data = $input->auth_data;

    //kode kegiatan diganti sesuai jenis ujiannya
    $list_data = UjianMp::select('ujian_mp.nm_ujian_mp', 'kegiatan.nm_kegiatan', 'ujian_mp.is_online', 'ujian_mp.tgl_ujian_mp', 'ujian_mp.jam_mulai', 'ujian_mp.jam_selesai', 'ujian_mp.keterangan', 'ruangan.nm_ruangan', 'ruangan.kapasitas_ujian', 'gedung.kode_gedung', 'ujian_mp.id_ujian_mp', 'ujian_mp.id_kelas_mp', 'kelas_mp.nm_kelas_mp', 'kelas_mp.id_semester', 'semester.nm_semester', 'semester.tahun_ajaran', 'mata_pelajaran.nm_mata_pelajaran', 'mata_pelajaran.kd_mata_pelajaran', 'kegiatan.id_kegiatan')
      ->join('kegiatan', 'kegiatan.id_kegiatan', '=', 'ujian_mp.id_kegiatan')
      ->leftJoin('ujian_mp_ruangan', 'ujian_mp_ruangan.id_ujian_mp', '=', 'ujian_mp.id_ujian_mp')
      ->leftJoin('ruangan', 'ujian_mp_ruangan.id_ruangan', '=', 'ruangan.id_ruangan')
      ->leftJoin('gedung', 'gedung.id_gedung', '=', 'ruangan.id_gedung')
      ->join('kelas_mp', 'kelas_mp.id_kelas_mp', '=', 'ujian_mp.id_kelas_mp')
      ->join('semester', 'semester.id_semester', '=', 'kelas_mp.id_semester')
      ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
      ->where('ujian_mp.is_online', '=', $online)
      ->where('semester.is_aktif_semester', '=', 1)
      ->where('kegiatan.kode_kegiatan', '=', 'UTS')
      ->orderBy('ujian_mp.created_at', 'desc')
      ->get();

    return Datatables::of($list_data)
      ->addColumn('ruangan_ujian', function ($item) {
        if ($item->nm_gedung == null) {
          return $item->nm_ruangan;
        } elseif ($item->nm_ruangan == null && $item->nm_gedung == null) {
          return "-";
        } else {
          return $item->nm_ruangan . " (" . $item->nm_gedung . ")";
        }
      })
      ->addColumn('jenis_ujian', function ($item) {
        if ($item->is_online == 1) {
          return $item->nm_kegiatan . " Online";
        } elseif ($item->is_online == 0) {
          return $item->nm_kegiatan . " Reguler";
        }
      })
      ->addColumn('semester', function ($item) {
        return $item->nm_semester . ' (' . $item->tahun_ajaran . ')';
      })
      ->addColumn('mata_pelajaran', function ($item) {
        return $item->nm_mata_pelajaran . ' (' . $item->kd_mata_pelajaran . ')';
      })
      ->addColumn('action', function ($item) {
        $data = array(
          'id' => $item->id_kelas_mp,
          'id_ujian' => $item->id_ujian_mp
        );
        return $data;
      })
      ->make(true);
  }

  public function datatablesDaftarMataPelajaran(Request $request, $online)
  {
    $input = (object) $request->input();
    $auth_data = $input->auth_data;

    //kode kegiatan diganti sesuai jenis ujiannya
    $list_data = KelasMp::select('kelas_mp.nm_kelas_mp', 'kelas_mp.id_kelas', 'kelas_mp.id_mata_pelajaran', 'kelas_mp.id_semester', 'kelas_mp.id_kelas_mp', 'kelas.nm_kelas', 'mata_pelajaran.nm_mata_pelajaran', 'mata_pelajaran.kd_mata_pelajaran', 'semester.nm_semester', 'semester.tahun_ajaran')
      ->join('kelas', 'kelas.id_kelas', '=', 'kelas_mp.id_kelas')
      ->join('mata_pelajaran', 'mata_pelajaran.id_mata_pelajaran', '=', 'kelas_mp.id_mata_pelajaran')
      ->join('semester', 'semester.id_semester', '=', 'kelas_mp.id_semester')
      ->join('jurusan', 'jurusan.id_jurusan', '=', 'kelas.id_jurusan')
      ->whereNotExists(function ($query) use ($online) {
        $query->select(DB::raw(1))
          ->from('ujian_mp')
          ->join('kegiatan', 'kegiatan.id_kegiatan', '=', 'ujian_mp.id_kegiatan')
          ->whereRaw('ujian_mp.id_kelas_mp = kelas_mp.id_kelas_mp')
          ->whereRaw('ujian_mp.is_online = "' . $online . '"')
          ->whereRaw('kegiatan.kode_kegiatan = "UTS"');
      })
      ->where('jurusan.id_sekolah', '=', $auth_data->pengguna->id_sekolah)->where('semester.is_aktif_semester', '=', 1)
      ->get();

    return Datatables::of($list_data)
      ->addColumn('semester', function ($item) {
        return $item->nm_semester . ' (' . $item->tahun_ajaran . ')';
      })
      ->addColumn('action', function ($item) {
        $data = array(
          'id' => $item->id_kelas_mp
        );
        return $data;
      })
      ->make(true);
  }

  public function datatablesDaftarSiswa(Request $request, $id)
  {
    $input = (object) $request->input();
    $auth_data = $input->auth_data;
    $kelas = UjianMp::where('id_ujian_mp', '=', $id)->first();

    $list_data = Siswa::select('siswa.nis_siswa', 'siswa.nisn_siswa', 'pengguna.nm_pengguna', 'siswa.id_kelas', 'kelas.nm_kelas', 'kelas_mp.nm_kelas_mp', 'siswa.id_siswa')
      ->join('kelas', 'kelas.id_kelas', '=', 'siswa.id_kelas')
      ->join('kelas_mp', 'kelas_mp.id_kelas', '=', 'kelas.id_kelas')
      ->join('pengguna', 'pengguna.id_pengguna', '=', 'siswa.id_pengguna')
      ->where('kelas_mp.id_kelas_mp', '=', $kelas->id_kelas_mp)
      ->get();

    return Datatables::of($list_data)
      ->addColumn('checkbox', function ($item) {
        $data = array(
          'id' => $item->id_siswa
        );
        return $data;
      })
      ->make(true);
  }

  public function actionUjianUts(Request $request, $mode, $id = null)
  {
    $input = (object) $request->input();
    $auth_data = $input->auth_data;
    $now = Carbon::now(env('APP_TIMEZONE', ''));

    $validator = Validator::make($request->all(), [
      'nm_ujian_mp' => 'required',
      'tgl_ujian_mp' => 'required',
      'jam_mulai' => 'required',
      'jam_selesai' => 'required'
    ]);

    if ($validator->fails() && $mode != 'delete' && $mode != 'assign') {
      return [
        'status' => 300, // FAILED
        'message' => $validator->errors()->first()
      ];
    } else {
      if ($mode == 'add') {
        $id_ujian_mp          = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
        $id_ruangan_ujian_mp  = $input->auth_data->sekolah_data->prefix . strtotime($now) . uniqid();

        $ujian                         = new UjianMp;
        $ujian->id_ujian_mp            = $id_ujian_mp;
        $ujian->id_kegiatan            = $input->id_kegiatan;
        $ujian->id_kelas_mp           = $input->id_kelas_mp;
        $ujian->nm_ujian_mp           = $input->nm_ujian_mp;
        $ujian->tgl_ujian_mp           = date_format(date_create($input->tgl_ujian_mp), "Y-m-d");
        $ujian->jam_mulai             = $input->jam_mulai;
        $ujian->jam_selesai            = $input->jam_selesai;
        $ujian->keterangan            = $input->keterangan;
        $ujian->is_online              = $input->is_online;
        $ujian->created_by            = $input->auth_data->pengguna->id_pengguna;
        $ujian->created_at            = $now;
        $ujian->save();

        $ruangan                      = new UjianMpRuangan;
        $ruangan->id_ujian_mp_ruangan = $id_ruangan_ujian_mp;
        $ruangan->id_ujian_mp         = $id_ujian_mp;
        $ruangan->id_ruangan          = $input->id_ruangan;
        $ruangan->created_by          = $input->auth_data->pengguna->id_pengguna;
        $ruangan->created_at          = $now;
        $ruangan->save();

        return [
          'status' => 202, // SUCCESS AND LOAD CONTENT
          'path' => 'ujian/ujian-uts-reguler-online/add/' . $input->is_online,
          'message' => 'Save Ujian UTS Successfully'
        ];
      } elseif ($mode == 'edit') {
        $ujian                        = UjianMp::find($id);
        $ujian->id_kegiatan           = $input->id_kegiatan;
        $ujian->id_kelas_mp           = $input->id_kelas_mp;
        $ujian->nm_ujian_mp           = $input->nm_ujian_mp;
        $ujian->tgl_ujian_mp          = date_format(date_create($input->tgl_ujian_mp), "Y-m-d");
        $ujian->jam_mulai             = $input->jam_mulai;
        $ujian->jam_selesai           = $input->jam_selesai;
        $ujian->keterangan            = $input->keterangan;
        $ujian->is_online             = $input->is_online;
        $ujian->updated_by            = $input->auth_data->pengguna->id_pengguna;
        $ujian->updated_at            = $now;
        $ujian->save();

        $ruangan                      = UjianMpRuangan::where('ujian_mp_ruangan.id_ujian_mp', '=', $id)->first();
        $ruangan->id_ruangan          = $input->id_ruangan;
        $ruangan->updated_by          = $input->auth_data->pengguna->id_pengguna;
        $ruangan->updated_at          = $now;
        $ruangan->save();

        return [
          'status' => 202, // SUCCESS AND LOAD CONTENT
          'path' => 'ujian/ujian-uts-reguler-online',
          'message' => 'Save Ujian UTS Successfully'
        ];
      } elseif ($mode == 'delete') {
        $peserta = UjianMpPresensi::where('id_ujian_mp', '=', $id)->first();
        if ($peserta) {
          return [
            'status' => 300, // SUCCESS AND LOAD TABLE
            'message' => 'Failed To Delete Ujian'
          ];
        } else {
          $ujian                        = UjianMp::find($id);
          $ujian->deleted_by            = $input->auth_data->pengguna->id_pengguna;
          $ujian->save();
          $ujian->forceDelete();

          $ruangan                      = UjianMpRuangan::where('ujian_mp_ruangan.id_ujian_mp', '=', $id)->first();
          $ruangan->deleted_by          = $input->auth_data->pengguna->id_pengguna;
          $ruangan->save();
          $ruangan->forceDelete();

          return [
            'status' => 203, // SUCCESS AND LOAD TABLE
            'message' => 'Delete Ujian Berhasil'
          ];
        }
      } elseif ($mode == 'assign') {
        DB::beginTransaction();
        try {
          foreach ($input->id_siswa as $id_siswa) {
            $cekSiswa = UjianMpPresensi::where('ujian_mp_presensi.id_ujian_mp', '=', $id)->where('ujian_mp_presensi.id_siswa', '=', $id_siswa)->first();
            if ($cekSiswa) {
              // DB::rollback();
              // return [
              //   'status' => 203, // GAGAL
              //   'message' => 'Tambah Peserta UTS Gagal Dilakukan!'
              // ];
            } else {
              $id_presensi = $auth_data->sekolah_data->prefix . strtotime($now) . uniqid();
              $presensi                       = new UjianMpPresensi;
              $presensi->id_ujian_mp_presensi = $id_presensi;
              $presensi->id_ujian_mp          = $id;
              $presensi->id_siswa             = $id_siswa;
              $presensi->created_at           = $now;
              $presensi->created_by           = $input->auth_data->pengguna->id_pengguna;
              $presensi->save();
            }
          }
          DB::commit();
          return [
            'status' => 202, // SUCCESS AND LOAD CONTENT
            'path' => 'ujian/ujian-uts-reguler-online/',
            'message' => 'Tambah Peserta UTS Successfully'
          ];
        } catch (\Exception $e) {
          DB::rollback();
          //     // something went wrong

          return [
            'status' => 203, // GAGAL
            'message' => $e
          ];
        }
      }
    }
  }
}

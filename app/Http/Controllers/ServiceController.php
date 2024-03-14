<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use App\Models\RolePengguna;
use App\Models\Siswa;
use App\Models\WaliMurid;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use DB;
use Illuminate\Support\Facades\Hash;

class ServiceController extends BaseController
{
    public function actionRemoveDoubleDataWaliMurid(Request $request)
    {
        DB::update(`delete p from wali_murid wm
        left join pengguna p on p.id_pengguna = wm.id_pengguna
        left join siswa s on s.id_wali_murid = wm.id_wali_murid
        where s.id_siswa is null`);

        DB::update(`delete wm from wali_murid wm
        left join pengguna p on p.id_pengguna = wm.id_pengguna
        left join siswa s on s.id_wali_murid = wm.id_wali_murid
        where s.id_siswa is null`);
    }

    public function actionUpdateNomorPenggunaWaliMuridNotSameCalonSiswaOrtu(Request $request)
    {
        DB::update(`update siswa s
        left join calon_siswa_ortu cso on cso.id_c_siswa = s.id_c_siswa
        left join wali_murid wm on wm.id_wali_murid = s.id_wali_murid
        left join pengguna p on wm.id_pengguna = p.id_pengguna
        
        set p.username = cso.nomor_hp_ortu, wm.nomor_hp_wali_murid = cso.nomor_hp_ortu
        
        where s.id_wali_murid is not null
        and wm.nomor_hp_wali_murid <> cso.nomor_hp_ortu;`);
    }

    public function actionCreatePenggunaFromTableCalonSiswaOrtu(Request $request)
    {
        set_time_limit(-1);

        $kumpulan_data_salah = DB::select('select s.id_siswa, cso.nm_ayah, cso.nomor_hp_ortu
        from siswa s
        left join calon_siswa_ortu cso on cso.id_c_siswa = s.id_c_siswa
        
        left join wali_murid wm on wm.id_wali_murid = s.id_wali_murid
        left join pengguna p on wm.id_pengguna = p.id_pengguna
        where s.deleted_at is null
        and s.id_wali_murid is null
        and cso.nomor_hp_ortu is not null');

        DB::beginTransaction();
        try {
            foreach ($kumpulan_data_salah as $key => $data_salah) {
                $nama_ortu = $data_salah->nm_ayah;
                $nomor_hp_ortu = $data_salah->nomor_hp_ortu;
                $id_siswa = $data_salah->id_siswa;

                $wali_murid = new WaliMurid();
                $wali_murid->id_wali_murid = generate_id();
                $wali_murid->id_pengguna = generate_id();
                $wali_murid->nm_wali_murid = $nama_ortu;
                $wali_murid->is_aktif = 1;
                $wali_murid->nomor_hp_wali_murid = $nomor_hp_ortu;
                $wali_murid->created_by = 'SYSTEM-FUNCTION';
                $wali_murid->save();

                $pengguna = new Pengguna();
                $pengguna->id_pengguna = $wali_murid->id_pengguna;
                $pengguna->nm_pengguna = $wali_murid->nm_wali_murid;
                $pengguna->id_sekolah = get_id_sekolah();
                $pengguna->id_status_pengguna = "Fh2L415358554335b8b4b49e1659";
                $pengguna->username = $wali_murid->nomor_hp_wali_murid;
                $pengguna->password = Hash::make($wali_murid->nomor_hp_wali_murid);
                $pengguna->status_join_table = 4;
                $pengguna->created_by = 'SYSTEM-FUNCTION';
                $pengguna->save();

                $role_wali_murid = new RolePengguna();
                $role_wali_murid->id_role = 4;
                $role_wali_murid->id_pengguna = $wali_murid->id_pengguna;
                $role_wali_murid->keterangan_role_pengguna = "Generate Wali Murid";
                $role_wali_murid->is_aktif = 1;
                $role_wali_murid->created_by = 'SYSTEM-FUNCTION';
                $role_wali_murid->save();

                $siswa = Siswa::where('id_siswa', $id_siswa)->first();
                $siswa->id_wali_murid = $wali_murid->id_wali_murid;
                $siswa->save();
            }

            DB::commit();
            return response()->json([
                'status_code'     => 200,
                'status_text'     => 'Success',
                'message' => 'Update Successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status_code'     => 300,
                'status_text'     => 'Failed',
                'message' => 'Update gagal',
                "status" => $e->getMessage()
            ]);
        }
    }
}

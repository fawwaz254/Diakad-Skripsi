<table class="table table-bordered">
    <thead>
        <tr>
            <td style="text-align: center;font-weight: bold;">NOMOR</td>
            <th style="text-align: center;font-weight: bold;">KODE VOUCHER</th>
            <td style="text-align: center;font-weight: bold;">NIS</td>
            <td style="text-align: center;font-weight: bold;">NAMA SISWA</td>
            <td style="text-align: center;font-weight: bold;">JENIS KELAMIN</td>
            <td style="text-align: center;font-weight: bold;">NOMOR HP</td>
            <td style="text-align: center;font-weight: bold;">NIK</td>
            <td style="text-align: center;font-weight: bold;">TEMPAT LAHIR</td>
            <td style="text-align: center;font-weight: bold;">TANGGAL LAHIR</td>
            <td style="text-align: center;font-weight: bold;">AGAMA</td>
            <td style="text-align: center;font-weight: bold;">BAHASA</td>
            <td style="text-align: center;font-weight: bold;">KEWARGANEGARAAN</td>
            <td style="text-align: center;font-weight: bold;">ALAMAT JALAN</td>
            <td style="text-align: center;font-weight: bold;">RT</td>
            <td style="text-align: center;font-weight: bold;">RW</td>
            <td style="text-align: center;font-weight: bold;">DUSUN</td>
            <td style="text-align: center;font-weight: bold;">KELURAHAN/DESA</td>
            <td style="text-align: center;font-weight: bold;">KECAMATAN</td>
            <td style="text-align: center;font-weight: bold;">KODEPOS</td>
            <td style="text-align: center;font-weight: bold;">ALAMAT PROVINSI</td>
            <td style="text-align: center;font-weight: bold;">ALAMAT KOTA</td>
            <td style="text-align: center;font-weight: bold;">ANAK KE</td>
            <td style="text-align: center;font-weight: bold;">JUMLAH SAUDARA</td>
            <td style="text-align: center;font-weight: bold;">NAMA AYAH</td>
            <td style="text-align: center;font-weight: bold;">NOMOR TELEPON AYAH</td>
            <td style="text-align: center;font-weight: bold;">NIK AYAH</td>
            <td style="text-align: center;font-weight: bold;">PENDIDIKAN AYAH</td>
            <td style="text-align: center;font-weight: bold;">PEKERJAAN AYAH</td>
            <td style="text-align: center;font-weight: bold;">PENGHASILAN AYAH</td>
            <td style="text-align: center;font-weight: bold;">NAMA IBU</td>
            <td style="text-align: center;font-weight: bold;">NOMOR TELEPON IBU</td>
            <td style="text-align: center;font-weight: bold;">NIK IBU</td>
            <td style="text-align: center;font-weight: bold;">PENDIDIKAN IBU</td>
            <td style="text-align: center;font-weight: bold;">PEKERJAAN IBU</td>
            <td style="text-align: center;font-weight: bold;">PENGHASILAN IBU
            <td style="text-align: center;font-weight: bold;">DATA WALI</td>
            <td style="text-align: center;font-weight: bold;">PEKERJAAN</td>
            <td style="text-align: center;font-weight: bold;">ASAL SEKOLAH</td>
            <td style="text-align: center;font-weight: bold;">KOTA ASAL SEKOLAH</td>

        </tr>
    </thead>
    <tbody>
        @php
            $no = 0;
        @endphp
        @foreach ($data as $calon_siswa_baru)
            <tr>
                <td style="text-align: center">{{ ++$no }}</td>
                <td style="text-align: center">{{ $calon_siswa_baru->kode_voucher }} </td>
                <td style="color: red;text-align: center">(isi manual)</td>
                <td>{{ $calon_siswa_baru->nm_c_siswa }}</td>
                <th>{{ $calon_siswa_baru->jenis_kelamin == '1' ? 'Laki-laki' : 'Perempuan' }}</th>
                <th>{{ $calon_siswa_baru->nomor_hp }}</th>
                <th>{{ $calon_siswa_baru->nik_siswa }}</th>
                <th>{{ isset($calon_siswa_baru->kota_lahir) ? $calon_siswa_baru->kota_lahir->nm_kota : '' }}</th>
                <th>{{ $calon_siswa_baru->tgl_lahir }}</th>
                <th>{{ isset($calon_siswa_baru->agama) ? $calon_siswa_baru->agama->nm_agama : '' }}</th>
                <th>{{ $calon_siswa_baru->bahasa_sehari_hari }}</th>
                <th>{{ $calon_siswa_baru->kewarganegaraan == '1' ? 'WNI' : 'WNA' }}</th>
                <th>{{ $calon_siswa_baru->alamat_jalan }}</th>
                <th>{{ $calon_siswa_baru->alamat_rt }}</th>
                <th>{{ $calon_siswa_baru->alamat_rw }}</th>
                <th>{{ $calon_siswa_baru->alamat_dusun }}</th>
                <th>{{ $calon_siswa_baru->alamat_kelurahan }}</th>
                <th>{{ $calon_siswa_baru->alamat_kecamatan }}</th>
                <th>{{ $calon_siswa_baru->alamat_kodepos }}</th>
                <th>{{ isset($calon_siswa_baru->provinsi) ? $calon_siswa_baru->provinsi->nm_provinsi : '' }}</th>
                <th>{{ isset($calon_siswa_baru->kota) ? $calon_siswa_baru->kota->nm_kota : '' }}</th>
                <th>{{ $calon_siswa_baru->anak_ke }}</th>
                <th>{{ $calon_siswa_baru->dari_x_bersaudara }}</th>
                {{-- data ayah --}}
                @if (isset($calon_siswa_baru->calon_siswa_ortu))
                    <th>{{ $calon_siswa_baru->calon_siswa_ortu->nm_ayah }}</th>
                    <th>{{ $calon_siswa_baru->calon_siswa_ortu->nomor_telp_ortu }}</th>
                    <th>{{ $calon_siswa_baru->calon_siswa_ortu->nik_ayah }}</th>
                    <th>{{ isset($calon_siswa_baru->calon_siswa_ortu->jenis_pendidikan_ayah) ? $calon_siswa_baru->calon_siswa_ortu->jenis_pendidikan_ayah->nm_jenis_pendidikan : '' }}
                    </th>
                    <th>{{ isset($calon_siswa_baru->calon_siswa_ortu->jenis_pekerjaan_ayah) ? $calon_siswa_baru->calon_siswa_ortu->jenis_pekerjaan_ayah->nm_jenis_pekerjaan : '' }}
                    </th>
                    <th>{{ isset($calon_siswa_baru->calon_siswa_ortu->jenis_penghasilan_ayah) ? $calon_siswa_baru->calon_siswa_ortu->jenis_penghasilan_ayah->nm_jenis_penghasilan : '' }}
                    </th>
                    {{-- data ibu  --}}
                    <th>{{ $calon_siswa_baru->calon_siswa_ortu->nm_ibu }}</th>
                    <th>{{ $calon_siswa_baru->calon_siswa_ortu->nomor_telp_ortu }}</th>
                    <th>{{ $calon_siswa_baru->calon_siswa_ortu->nik_ibu }}</th>
                    <th>{{ isset($calon_siswa_baru->calon_siswa_ortu->jenis_pendidikan_ibu) ? $calon_siswa_baru->calon_siswa_ortu->jenis_pendidikan_ibu->nm_jenis_pendidikan : '' }}
                    </th>
                    <th>{{ isset($calon_siswa_baru->calon_siswa_ortu->jenis_pekerjaan_ibu) ? $calon_siswa_baru->calon_siswa_ortu->jenis_pekerjaan_ibu->nm_jenis_pekerjaan : '' }}
                    </th>
                    <th>{{ isset($calon_siswa_baru->calon_siswa_ortu->jenis_penghasilan_ibu) ? $calon_siswa_baru->calon_siswa_ortu->jenis_penghasilan_ibu->nm_jenis_penghasilan : '' }}
                    </th>
                    <th>{{ $calon_siswa_baru->nm_wali }}</th>
                    <th>{{ isset($calon_siswa_baru->calon_siswa_ortu->jenis_pekerjaan_wali) ? $calon_siswa_baru->calon_siswa_ortu->jenis_pekerjaan_wali->nm_jenis_pekerjaan : '' }}
                    </th>
                @endif
                <th>{{ isset($calon_siswa_baru->calon_siswa_sekolah) ? $calon_siswa_baru->calon_siswa_sekolah->nm_sekolah_asal : '' }}
                </th>
                <th>{{ isset($calon_siswa_baru->calon_siswa_sekolah->kota_asal_sekolah) ? $calon_siswa_baru->calon_siswa_sekolah->kota_asal_sekolah->nm_kota : '' }}
                </th>

                {{-- <th>{{ $calon_siswa_baru }}</th>
                    <th>{{ $calon_siswa_baru }}</th>
                    <th>{{ $calon_siswa_baru }}</th>
                    <th>{{ $calon_siswa_baru }}</th>
                    <th>{{ $calon_siswa_baru }}</th> --}}

            </tr>
        @endforeach
    </tbody>

</table>

<style>
    .center {
        text-align: center;
        vertical-align: middle;
        background-color: red;
    }
</style>

<table class="table table-bordered">
    <thead>
        <tr>
            <td style="text-align: center;font-weight: bold;">NOMOR</td>
            <th style="text-align: center;font-weight: bold;">ID</th>
            <td style="text-align: center;font-weight: bold;">NIS</td>
            <td style="text-align: center;font-weight: bold;">NAMA SISWA</td>
            @foreach ($data['list_data'] as $nilai)
                <td style="text-align: center;font-weight: bold;">{{ $nilai->nm_komponen_jenis_rapor }}</td>
                <td style="text-align: center;font-weight: bold;">{{ 'Keterangan ' . $nilai->nm_komponen_jenis_rapor }}
                </td>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php
            $no = 0;
        @endphp
        @foreach ($data['list_siswa'] as $siswa)
            <tr>
                <td style="text-align: center">{{ ++$no }}</td>
                <td style="text-align: center">{{ $data['id_rapor'] }} </td>
                <td style="text-align: center">'{{ $siswa->nis_siswa }}</td>
                <td>{{ $siswa->pengguna->nm_pengguna }}</td>
                @foreach ($data['list_data'] as $nilai)
                    <td style="text-align: center">
                        {{ isset($data['nilai_siswa'][$nilai->id_komponen_jenis_rapor . $siswa->id_siswa . $data['id_rapor'] . 'nilai']) && $data['nilai_siswa'][$nilai->id_komponen_jenis_rapor . $siswa->id_siswa . $data['id_rapor'] . 'nilai'] != '0' ? $data['nilai_siswa'][$nilai->id_komponen_jenis_rapor . $siswa->id_siswa . $data['id_rapor'] . 'nilai'] : null }}
                    </td>
                    <td style="text-align: center">
                        {{ isset($data['nilai_siswa'][$nilai->id_komponen_jenis_rapor . $siswa->id_siswa . $data['id_rapor'] . 'keterangan']) && !empty($data['nilai_siswa'][$nilai->id_komponen_jenis_rapor . $siswa->id_siswa . $data['id_rapor'] . 'keterangan']) ? $data['nilai_siswa'][$nilai->id_komponen_jenis_rapor . $siswa->id_siswa . $data['id_rapor'] . 'keterangan'] : null }}
                    </td>
                @endforeach
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

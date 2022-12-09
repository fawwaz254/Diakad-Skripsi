<table class="table table-bordered">




    <thead>
        <tr>
            <td style="text-align: center;font-weight: bold;">NOMOR</td>
            <th style="text-align: center;font-weight: bold;">ID</th>
            <td style="text-align: center;font-weight: bold;">NIS</td>
            <td style="text-align: center;font-weight: bold;">NAMA SISWA</td>
            @foreach ($data['list_data'] as $nilai)
                <td style="text-align: center;font-weight: bold;">{{ $nilai->nm_nilai }}</td>
            @endforeach
            <td style="text-align: center;font-weight: bold;">NAMA SISWA</td>
            <td style="text-align: center;font-weight: bold;">NAMA SISWA</td>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 0;
        @endphp
        @foreach ($data['list_siswa'] as $siswa)
            <tr>
                <td style="text-align: center">{{ ++$no }}</td>
                <td style="text-align: center">{{ $data['id_rapor_sisipan'] }} </td>
                <td style="text-align: center">{{ $siswa->nis_siswa }}</td>
                <td>{{ $siswa->pengguna->nm_pengguna }}</td>
                @foreach ($data['list_data'] as $nilai)
                    <td style="text-align: center">
                        {{ $data['nilai_siswa'][$nilai->id_komponen_nilai . $siswa->id_siswa . $data['id_rapor_sisipan']] }}
                    </td>
                @endforeach
                {{-- <td style="text-align: center;">
                {{ round(($data['nilai_komponen'][$siswa->id_siswa . 'nilai_sumasi1'] + $data['nilai_komponen'][$siswa->id_siswa . 'nilai_sumasi2']) / 2) }}
                </td>
                <td style="text-align: center;">
                   {{ round(($data['nilai_komponen'][$siswa->id_siswa . 'nilai_sumasi1'] + $data['nilai_komponen'][$siswa->id_siswa . 'nilai_sumasi2'] + $data['nilai_komponen'][$siswa->id_siswa . 'sts']) / 3) }}
                </td> --}}
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

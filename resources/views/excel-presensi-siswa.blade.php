<table class="table table-bordered">
    <thead>
        <tr>
            <td style="text-align: center;font-weight: bold;">No</td>
            <th style="text-align: center;font-weight: bold;">Ekskul</th>
            <th style="text-align: center;font-weight: bold;">Semester</th>
            <th style="text-align: center;font-weight: bold;">Jam Mulai</th>
            <th style="text-align: center;font-weight: bold;">Jam Selesai</th>
            <th style="text-align: center;font-weight: bold;">Tanggal</th>
            <th style="text-align: center;font-weight: bold;">Pertemuan ke</th>
            <th style="text-align: center;font-weight: bold;">Materi</th>
            @foreach ($data['data_siswa'] as $siswa)
                <td style="text-align: center;font-weight: bold;">{{ $siswa->siswa->pengguna->nm_pengguna }}</td>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php
            $no = 0;
        @endphp
        @foreach ($data['tanggal'] as $tanggal)
            <tr>
                <td style="text-align: center">{{ ++$no }}</td>
                <td style="text-align: center">{{ $data['data_ekskul']->nm_ekskul }} </td>
                <td style="text-align: center">{{ $data['data_semester']->kode_semester }} </td>
                <td style="text-align: center">{{ $data['jam_mulai'] }}</td>
                <td style="text-align: center">{{ $data['jam_selesai'] }}</td>

                <td style="text-align: center">{{ $tanggal }}</td>
                <td style="text-align: center">{{ $no }}</td>
                <td style="text-align: center;background-color: yellow">-</td>

                @foreach ($data['data_siswa'] as $siswa)
                    <td style="text-align: center; background-color:yellow">H</td>
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

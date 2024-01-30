<table class="table table-bordered">
    <thead>
        <tr>
            <td style="text-align: center;font-weight: bold;">No</td>
            <td style="text-align: center;font-weight: bold;">NIS</td>
            <td style="text-align: center;font-weight: bold;">Nama Lengkap</td>
            <td style="text-align: center;font-weight: bold;">Nama Wali Murid</td>
            <td style="text-align: center;font-weight: bold;">Telp Wali Murid</td>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 0;
        @endphp
        @foreach ($data as $siswa)
            <tr>
                <td style="text-align: center">{{ ++$no }}</td>
                <td style="text-align: center">{{ $siswa->nis_siswa }}</td>
                <td>{{ $siswa->pengguna->nm_pengguna }}</td>
                @if (isset($siswa->wali_murid->pengguna))
                    <td>{{ $siswa->wali_murid->pengguna->nm_pengguna }}</td>
                    <td>{{ "'" . $siswa->wali_murid->pengguna->username }}</td>
                @endif
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

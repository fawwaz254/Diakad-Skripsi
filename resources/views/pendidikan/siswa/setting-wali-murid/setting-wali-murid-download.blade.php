<table>
    <thead>
        <tr>
            <th>Nomor</th>
            <th>NIS</th>
            <th>NISN</th>
            <th>Nama Siswa</th>
            <th>Nama Wali Murid</th>
            <th>Telp Wali Murid</th>
            <th>Gelar Depan</th>
            <th>Gelar Belakang</th>
        </tr>
    </thead>
    @php
        $nomor = 1;
    @endphp
    <tbody>
        @foreach($list_data as $data)
        <tr>
            <td>{{$nomor++}}</td>
            <td>{{$data->nis_siswa}}</td>
            <td>{{$data->nisn_siswa}}</td>
            <td>{{$data->pengguna->nm_pengguna}}</td>
            @if(!empty($data->wali_murid))
            <td>{{$data->wali_murid->nm_wali_murid}}</td>
            <td>{{$data->wali_murid->nomor_hp_wali_murid}}</td>
            <td>{{$data->wali_murid->pengguna->gelar_depan}}</td>
            <td>{{$data->wali_murid->pengguna->gelar_belakang}}</td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
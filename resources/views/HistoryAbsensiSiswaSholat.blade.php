<table class="table table-bordered" width="600px">
    <thead style="background:#9C27B0;color:white">
        <tr>
            <th style="text-align: center;">#</th>
            <th style="text-align: center;">Kelas</th>
            <th style="text-align: center;">NIS</th>
            <th>Nama</th>

            <th>Subuh</th>
            <th>Dzuhur</th>
            <th>Maghrib</th>
            <th>Isya</th>
            {{-- <th>Status</th> --}}
            {{-- @if (Request::segment(1) == 'humas') --}}
            {{-- <th style="text-align: center;">Action</th> --}}
            {{-- @endif --}}
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($products as $r)
            @if ($no % 2 == 0)
                <tr style="background: #DDA0DD">
                @else
                <tr>
            @endif
            {{-- @if ($r['shift']) --}}
            {{-- @if ($r['status'] == $status || $status == '0') --}}
            <td style="text-align: center;">{{ $no++ }}</td>
            <td style="text-align: center;">{{ $r['kelas'] }}</td>
            <td style="text-align: center;">{{ $r['nis'] }}</td>
            <td style="text-align: center;">{{ $r['nm_pengguna'] }}</td>

            <td>{{ $r['subuh'] }}</td>
            <td>{{ $r['dzuhur'] }}</td>
            <td>{{ $r['maghrib'] }}</td>
            <td>{{ $r['isya'] }}</td>
            {{-- <td
                        @if ($r['status'] == 'Masuk') style="background: #b5ffe0" @elseif($r['status'] == 'Alpha') style="background: #ff9494" @else style="background: #fffdb5" @endif>
                        {{ $r['status'] }}</td>

                    <td style="text-align: center;display:flex;justify-content:center">
                        @if ($r['id_presensi_pengguna'] == '')
                            <button type="button" class="btn bg-teal waves-effect"
                                onclick="addAbsensi('{{ $r['id_pengguna'] }}')">
                                <i class="material-icons">edit</i>
                            </button>
                        @else
                            <button type="button" class="btn bg-teal waves-effect"
                                onclick="editAbsensi('{{ $r['id_presensi_pengguna'] }}')">
                                <i class="material-icons">edit</i>
                            </button>
                            <button data-id="{{ $r['id_presensi_pengguna'] }}"
                                style="margin-left:3px;"
                                class="btn bg-red waves-effect delete-record">
                                <i class="material-icons">delete</i>
                            </button>
                        @endif --}}
            {{-- </td> --}}
            </tr>
            {{-- @endif --}}
            {{-- @else --}}
            {{-- @endif --}}
        @endforeach
    </tbody>
</table>
<table class="table table-bordered">
    <thead>

        <tr></tr>
        <tr>
            <th></th>
            <th>
                Bulan: {{ $bulan }}
            </th>
        </tr>
        <tr>
            <th></th>
            <th>
                Tahun: {{ $tahun }}
            </th>
        </tr>
        <tr>
            <th></th>
            <th> 
                Kelas: {{$nama_kelas->nm_kelas}}</th>
        </tr>

        <tr>
            <th rowspan="2" style="text-align: center;vertical-align:middle;">No</th>
            <th rowspan="2" style="text-align: center;vertical-align:middle;">Nama</th>
            <th colspan="{{ $dates->count() }}" style="text-align: center; vertical-align: middle;">Tanggal</th>
        </tr>

        <tr>
            @foreach ($dates as $date)
                <th style="text-align: center; vertical-align: middle; width: 30px;">
                    {{ substr(\Carbon\Carbon::create($date)->isoFormat('dddd'), 0, 3) }}
                    <br>
                    {{ $date->format('d') }}
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($pengguna as $p)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ isset($hasil[$p->id_pengguna]['nm_pengguna']) ? $hasil[$p->id_pengguna]['nm_pengguna'] : '' }}
                </td>
                @foreach ($dates as $date)
                    @php
                        $status = '';
                        if (isset($hasil[$p->id_pengguna][$date->format('Y-m-d')])) {
                            $status = $hasil[$p->id_pengguna][$date->format('Y-m-d')];
                        }
                    @endphp
                    <td
                        @if ($status == 'M') style="text-align: center;background: #b5ffe0" @elseif($status == 'A') style="text-align: center;background: #ff9494" @else style="text-align: center;" @endif>
                        {{ $status }}
                    </td>
                @endforeach

            </tr>
        @endforeach
    </tbody>
</table>

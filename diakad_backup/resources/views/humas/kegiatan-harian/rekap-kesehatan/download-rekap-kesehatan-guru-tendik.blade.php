<table>
    <thead>
        <tr>
            <th>No. </th>
            <th>Nama</th>
            <th colspan="{{$dates->count()}}">Tanggal</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            @foreach($dates as $date)
            <th>
                {{substr($date->format('l'), 0, 3)}} {{$date->format('d')}}
            </th>

            @php
                $total_pengisi[$date->format('d')] = 0;
                $total_normal[$date->format('d')] = 0;
                $total_warning[$date->format('d')] = 0;
            @endphp
            @endforeach
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach($data_pengguna as $pengguna)
        <tr>
            <td>{{$no++}}</td>
            <td>{{$pengguna->fullname()}}</td>
            @foreach($dates as $date)
                @php
                    $all_pengisian = $data_pengisian->where('tgl_pengisian', $date->format('Y-m-d'))->where('id_pengguna_pengisi', $pengguna->id_pengguna)->sortByDesc('status_pengisian')->all();
                @endphp
                @if(count($all_pengisian) > 0)
                <td style="background: #{{collect($all_pengisian)->first()->warna_keadaan}}; text-align:center; vertical-align:middle !important;">
                    <b>{{count($all_pengisian)}}x</b>
                </td>
                    @php
                        $total_pengisi[$date->format('d')]++;
                    @endphp
                    @if(collect($all_pengisian)->first()->status_pengisian == 1)
                        @php
                            $total_normal[$date->format('d')]++;
                        @endphp
                    @elseif(collect($all_pengisian)->first()->status_pengisian == 2)
                        @php
                            $total_warning[$date->format('d')]++;
                        @endphp
                    @endif
                @else
                <td></td>
                @endif
            @endforeach
        </tr>
        @endforeach
        <tr>
            <td colspan=2>Total Normal</td>
            @foreach($dates as $date)
            <td>{{$total_normal[$date->format('d')]}}</td>
            @endforeach
        </tr>
        <tr>
            <td colspan=2>Total Warning</td>
            @foreach($dates as $date)
            <td>{{$total_warning[$date->format('d')]}}</td>
            @endforeach
        </tr>
        <tr>
            <td colspan=2>Total pengisi</td>
            @foreach($dates as $date)
            <td>{{$total_pengisi[$date->format('d')]}}</td>
            @endforeach
        </tr>
    </tbody>
</table>
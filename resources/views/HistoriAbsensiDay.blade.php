<table class="table table-bordered">
    <thead>

        <tr>
            <th>Tanggal: {{ $products[0]['date'] }}</th>
        </tr>
        <tr>
        </tr>

        <tr>
            <th style="text-align: center; background-color: #d8d8d8">No</th>
            <th style="background-color: #d8d8d8">Nama</th>
            <th style="background-color: #d8d8d8">
                @if (isset($products[0]['unit_kerja']))
                    Unit kerja
                @else
                    Kelas
                @endif
            <th style="background-color: #d8d8d8">Check In</th>
            <th style="background-color: #d8d8d8">Check Out</th>
            <th style="background-color: #d8d8d8">Status</th>
            <th style="background-color: #d8d8d8">Notes</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($products as $r)
            @if ($r['shift'])
                @if ($no % 2 == 1)
                    <tr>
                    @else
                    <tr>
                @endif
                <td style="text-align: center;">{{ $no++ }}</td>
                <td>{{ $r['nm_pengguna'] }}</td>
                <td>{{ isset($r['unit_kerja']) ? $r['unit_kerja'] : $r['kelas'] }}</td>
                <td>{{ $r['check_in'] }}</td>
                <td>{{ $r['check_out'] }}</td>
                @if ($r['status'] == 'sakit' || $r['status'] == 'izin')
                    <td style="background-color: #fffc5e">{{ $r['status'] }}</td>
                @elseif($r['status'] == 'Alpha')
                    <td style="background-color: #ff5e79">{{ $r['status'] }}</td>
                @elseif($r['status'] == 'masuk')
                    <td style="background-color: #6cff5e">{{ $r['status'] }}</td>
                @else
                    <td>{{ $r['status'] }}</td>
                @endif
                <td>{{ $r['notes'] }}</td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>

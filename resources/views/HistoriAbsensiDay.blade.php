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
            <th style="background-color: #d8d8d8">Unit kerja</th>
            <th style="background-color: #d8d8d8">Check In</th>
            @if(!$products[0]['check_out'] == "-")
                <th style="background-color: #d8d8d8">Check Out</th>
            @endif
            <th style="background-color: #d8d8d8">Status</th>
            <th style="background-color: #d8d8d8">Notes</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $key => $r)
            @if ($key % 2 == 1)
                <tr>
                @else
                <tr>
            @endif
            <td style="text-align: center;">{{ $loop->iteration }}</td>
            <td>{{ $r['nm_pengguna'] }}</td>
            <td>{{ $r['unit_kerja']}}</td>
            <td>{{ $r['check_in'] }}</td>
            @if(!$r['check_out'] == "-")
            <td>{{ $r['check_out'] }}</td>
            @endif
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
        @endforeach
    </tbody>
</table>

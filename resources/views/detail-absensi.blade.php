<table class="table table-bordered">
    <thead>
        <tr>
            <th colspan="5">Nama: {{ $products[0]['nm_pengguna'] }}</th>
        </tr>
        <tr>
        </tr>
        <tr>
            <th style="text-align: center; background-color: #d8d8d8">No</th>
            <th style="background-color: #d8d8d8">Date</th>
            <th style="background-color: #d8d8d8">Shift</th>
            <th style="background-color: #d8d8d8">Start</th>
            <th style="background-color: #d8d8d8">End</th>
            <th style="background-color: #d8d8d8">Check In</th>
            <th style="background-color: #d8d8d8">Check Out</th>
            <th style="background-color: #d8d8d8">Status</th>
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
            <td>{{ $r['hari'] }}</td>
            <td>{{ $r['shift'] }}</td>
            <td>{{ $r['start'] }}</td>
            <td>{{ $r['end'] }}</td>
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
            </tr>
        @endforeach
    </tbody>
</table>

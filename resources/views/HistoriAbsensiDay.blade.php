<table class="table table-bordered">
    <thead style="background:#9C27B0;color:white">

        <tr>
<th>Tanggal: {{ $products[0]['date'] }}</th>

        </tr>

        <tr>
            <th style="text-align: center;">No</th>
          
            <th style="text-align: center;">Nama</th>
            <th>Role</th>
            <th>Check In</th>
            <th>Check Out</th>
            <th>Status</th>
            <th>Notes</th>
           
          
        </tr>
    </thead>
    <tbody>
        @foreach($products as $key => $r)
        @if($key%2==1)
        <tr style="background: #DDA0DD">
            @else
        <tr>
            @endif
            <td style="text-align: center;">{{$loop->iteration}}</td>
          
            <td style="text-align: center;">{{$r['nm_pengguna']}}</td>
            <td>{{$r['status_join_table'] == 1 ? 'Pegawai' : 'Guru' }}</td>
            <td>{{$r['check_in']}}</td>
            <td>{{$r['check_out']}}</td>
            <td>{{$r['status']}}</td>
            <td>{{$r['notes']}}</td>
                    </tr>
        @endforeach
    </tbody>
</table>
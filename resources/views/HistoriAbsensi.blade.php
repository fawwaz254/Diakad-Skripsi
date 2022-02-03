<table class="table table-bordered">
    <thead style="background:#9C27B0;color:white">
        <tr>
            <th style="text-align: center;">Tanggal</th>
            <th>Hari</th>
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
            <td style="text-align: center;">{{$r['tanggal']}}</td>
            <td>{{$r['hari']}}</td>
            <td>{{$r['check_in']}}</td>
            <td>{{$r['check_out']}}</td>
            <td>{{$r['status']}}</td>
            <td>{{$r['notes']}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
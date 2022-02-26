<table class="table table-bordered">
    <thead >

    <tr>




        <tr>
            <th style="text-align: center; background-color: #d8d8d8">No</th>
            <th style="background-color: #d8d8d8">Nama</th>
            <th style="background-color: #d8d8d8">Role</th>
            <th style="background-color: #d8d8d8">Tanggal</th>
        </tr>
        
{{-- <tr>
    <td></td>
    <td></td>
    <td></td>

</tr> --}}

    </thead>
    <tbody>
        @foreach ( $products as  $produk )
        @for ($i=0; $i>2; i++)
        
        {{-- @foreach($produk[1] as $r) --}}

        <tr>
            <td style="text-align: center;">{{$loop->iteration}}</td>
            <td>{{$produk['nm_pengguna']}}</td>
            <td>{{$produk['status_join_table'] == 1 ? 'Pegawai' : 'Guru' }}</td>
            {{-- <td>{{$r['check_in']}}</td>
            <td>{{$r['check_out']}}</td> --}}
            @foreach ( $produk as $p)
                
        
            @if($p['status'] == "sakit" || $produk[0]['status'] == "izin" )
            <td style="background-color: #fffc5e">{{$p['status']}}</td>
            @elseif($p['status'] == "Alpha")
            <td style="background-color: #ff5e79">{{$p['status']}}</td>
            @elseif($p['status'] == "masuk")
            <td style="background-color: #6cff5e">{{$p['status']}}</td>
            @else
            <td>{{$p['status']}}</td>
            @endif
            @endforeach
            {{-- <td>{{$r['notes']}}</td> --}}
                    </tr>
        {{-- @endforeach --}}
@endfor
        @endforeach
    </tbody>
</table>
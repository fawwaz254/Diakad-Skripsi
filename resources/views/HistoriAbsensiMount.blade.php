<table class="table table-bordered">
    <thead>

        <tr>
            <th>-</th>
            <th>-</th>
            <th>-</th>
            <th>Tanggal</th>
        </tr>
        <tr>
            <td style="text-align: center; background-color: #d8d8d8">No</td>
            <td style="background-color: #d8d8d8">Nama</td>
            <td style="background-color: #d8d8d8">{{ isset($products[0]['unit_kerja']) ? 'Unit Kerja' : 'Kelas' }}</td>
            {{-- @for ($i = 0; $i < ($total = count($products[0]) - 3); $i++)
                <td>{{ isseet($products[0][$i]['date']) ? $products[0][$i]['date'] : "" }}</td>
            @endfor --}}
            @foreach ($products as $item1)
                @foreach ($item1 as $item2)
                    <td>{{ isset($item2['date']) ? $item2['date'] : '' }}</td>
                    {{-- <td>{{ isseet($products[0][$i]['date']) ? $products[0][$i]['date'] : "" }}</td> --}}
                @endforeach
            @break
        @endforeach

    </tr>

</thead>
<tbody>
    @foreach ($products as $produk)
        <tr>
            <td style="text-align: center;">{{ $loop->iteration }}</td>
            <td>{{ $produk['nm_pengguna'] }}</td>
            <td>{{ isset($produk['unit_kerja']) ? $produk['unit_kerja'] : $produk['kelas'] }}</td>
            @foreach ($produk as $item2)
            @if(isset($item2['status']))
            @if ($item2['status'] == 'sakit' || $item2['status'] == 'izin')
                <td style="background-color: #fffc5e">{{ $item2['status'] }}</td>
            @elseif($item2['status'] == 'Alpha')
                <td style="background-color: #ff5e79">{{ $item2['status'] }}</td>
            @elseif($item2['status'] == 'masuk')
                <td style="background-color: #6cff5e">{{ $item2['status'] }}</td>
            @elseif($item2['status'] == 'Masuk')
                <td style="background-color: #6cff5e">{{ $item2['status'] }}</td>
            @elseif($item2['status'] == 'Telat')
                <td style="background-color: #ff8e1d">{{ $item2['status'] }}</td>
            @elseif($item2['status'] == 'Pulang lebih awal')
                <td style="background-color: #ff8e1d">{{ $item2['status'] }}</td>
            @elseif($item2['status'] == 'Telat dan Pulang lebih awal')
                <td style="background-color: #ff8e1d">{{ $item2['status'] }}</td>
            @elseif($item2['status'] == 'Tidak Checkout')
                <td style="background-color: #ff8e1d">{{ $item2['status'] }}</td>
            @elseif($item2['status'] == 'Telat & Tidak Checkout')
                <td style="background-color: #ff8e1d">{{ $item2['status'] }}</td>
            @else
                <td>{{ $item2['status'] }}</td>
            @endif
            @endif
           <td>{{ isset($item2['status']) ?  $item2['status'] : ''}}</td>
     
                {{-- @if ($item2['status'] == 'sakit' || $item2['status'] == 'izin')
                <td style="background-color: #fffc5e">{{ $item2['status'] }}</td>
            @elseif($item2['status'] == 'Alpha')
                <td style="background-color: #ff5e79">{{ $item2['status'] }}</td>
            @elseif($item2['status'] == 'masuk')
                <td style="background-color: #6cff5e">{{ $item2['status'] }}</td>
            @elseif($item2['status'] == 'Masuk')
                <td style="background-color: #6cff5e">{{ $item2['status'] }}</td>
            @elseif($item2['status'] == 'Telat')
                <td style="background-color: #ff8e1d">{{ $item2['status'] }}</td>
            @elseif($item2['status'] == 'Pulang lebih awal')
                <td style="background-color: #ff8e1d">{{ $item2['status'] }}</td>
            @elseif($item2['status'] == 'Telat dan Pulang lebih awal')
                <td style="background-color: #ff8e1d">{{ $item2['status'] }}</td>
            @elseif($item2['status'] == 'Tidak Checkout')
                <td style="background-color: #ff8e1d">{{ $item2['status'] }}</td>
            @elseif($item2['status'] == 'Telat & Tidak Checkout')
                <td style="background-color: #ff8e1d">{{ $item2['status'] }}</td>
            @else
                <td>{{ $item2['status'] }}</td>
            @endif --}}
            {{-- @endforeach
              @break --}}
             @endforeach



            {{-- @for ($i = 0; $i < ($total = count($produk) - 3); $i++)
                @if ($produk[$i]['status'] == 'sakit' || $produk[$i]['status'] == 'izin')
                    <td style="background-color: #fffc5e">{{ $produk[$i]['status'] }}</td>
                @elseif($produk[$i]['status'] == 'Alpha')
                    <td style="background-color: #ff5e79">{{ $produk[$i]['status'] }}</td>
                @elseif($produk[$i]['status'] == 'masuk')
                    <td style="background-color: #6cff5e">{{ $produk[$i]['status'] }}</td>
                @elseif($produk[$i]['status'] == 'Masuk')
                    <td style="background-color: #6cff5e">{{ $produk[$i]['status'] }}</td>
                @elseif($produk[$i]['status'] == 'Telat')
                    <td style="background-color: #ff8e1d">{{ $produk[$i]['status'] }}</td>
                @elseif($produk[$i]['status'] == 'Pulang lebih awal')
                    <td style="background-color: #ff8e1d">{{ $produk[$i]['status'] }}</td>
                @elseif($produk[$i]['status'] == 'Telat dan Pulang lebih awal')
                    <td style="background-color: #ff8e1d">{{ $produk[$i]['status'] }}</td>
                @elseif($produk[$i]['status'] == 'Tidak Checkout')
                    <td style="background-color: #ff8e1d">{{ $produk[$i]['status'] }}</td>
                @elseif($produk[$i]['status'] == 'Telat & Tidak Checkout')
                    <td style="background-color: #ff8e1d">{{ $produk[$i]['status'] }}</td>
                @else
                    <td>{{ $produk[$i]['status'] }}</td>
                @endif
            @endfor
 --}}


            {{-- <td>{{$r['notes']}}</td> --}}
        </tr>
    @endforeach
</tbody>
</table>

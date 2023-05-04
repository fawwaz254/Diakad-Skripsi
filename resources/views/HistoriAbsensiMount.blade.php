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
            @foreach ($products as $item1)
                @foreach ($item1 as $item2)
                    @if (isset($item2['date']))
                        <td> {{ $item2['date'] }} </td>
                        <td>Status</td>
                    @endif
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
                @if (isset($item2['check_in']))
                    <td>{{ substr($item2['check_in'], 0, 5) }}
                        @if (isset($item2['check_out']) && $item2['check_out'] != ' ')
                            - {{ substr($item2['check_out'], 0, 5) }}
                        @endif
                    </td>
                @endif


                @if (isset($item2['status']))
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
            @endforeach
        </tr>
    @endforeach
</tbody>
</table>

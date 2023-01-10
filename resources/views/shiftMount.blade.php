<table class="table table-bordered">
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th>Tanggal</th>
        </tr>
        <tr>
            <td style="text-align: center; background-color: #d8d8d8">No</td>
            <td style="background-color: #d8d8d8">Nama</td>
            @if (isset($products[0]['unit_kerja']))
                <td style="background-color: #d8d8d8">Unit Kerja</td>
            @else
                <td style="background-color: #d8d8d8">Kelas</td>
            @endif
            @foreach ($products as $item1)
                @foreach ($item1 as $item2)
                    @if (isset($item2['date']))
                        <td style="background-color: #d8d8d8"> {{ $item2['date'] }} </td>
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
            @if (isset($produk['unit_kerja']))
                <td>{{ $produk['unit_kerja'] }}</td>
            @else
                <td>{{ $produk['kelas'] }}</td>
            @endif
            @foreach ($produk as $item2)
                @if (isset($item2['time']))
                    @if ($item2['time'] == '-' && $item2['id_shift_master'] == '-')
                        <td style="background-color: #f2ff00"> </td>
                    @else
                        <td style="background-color: #ffffff">{{ $item2['id_shift_master'] }} ({{ $item2['time'] }})
                        </td>
                    @endif
                @endif
            @endforeach
        </tr>
    @endforeach
</tbody>
</table>

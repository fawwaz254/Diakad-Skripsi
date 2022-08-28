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
            <td style="background-color: #d8d8d8">'Unit Kerja</td>
            @foreach ($products as $item1)
                @foreach ($item1 as $item2)
                    @if (isset($item2['date']))
                        <td> {{ $item2['date'] }} </td>
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
            <td>{{ $produk['unit_kerja'] }}</td>
            @foreach ($produk as $item2)
                @if (isset($item2['time']))
                        <td style="background-color: #fffc5e">{{ $item2['id_shift_master'] }} ({{ $item2['status'] }})</td>
                @endif
                @endforeach
        </tr>
    @endforeach
</tbody>
</table>

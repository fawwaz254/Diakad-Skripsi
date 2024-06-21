<table>
    <thead>
        <tr>
            @foreach ($headers as $header)
            <td style="text-align: center;font-weight: bold;">{{$header}}</td>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($bodies as $body)
        <tr>
            @foreach ($body as $row)
            <td>{{ $row }}</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>

</table>
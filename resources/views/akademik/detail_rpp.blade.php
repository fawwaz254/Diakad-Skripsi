<h5>{{ $nama_guru }}</h5>
<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Mapel</th>
                <th>Mata Pelajaran</th>
                <th>Tanggal Upload</th>
            </tr>
        </thead>
        <tbody>
            @if ($detail_rpp->count() > 0)
                @foreach ($detail_rpp as $key => $rpp)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $rpp->mata_pelajaran->kd_mata_pelajaran }}</td>
                        <td>{{ $rpp->mata_pelajaran->nm_mata_pelajaran }}</td>
                        <td>{{ $rpp->created_at }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3" style="text-align: center;">Data tidak ditemukan</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

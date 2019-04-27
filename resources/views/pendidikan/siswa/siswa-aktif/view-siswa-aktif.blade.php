<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-green">
                    <h2>
                        SISWA AKTIF
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                            <tr>
                                <th rowspan="3" style="text-align:center">Jurusan</th>
                                <th colspan="4" style="text-align: center;">Kelas</th>
                            </tr>
                            <tr>
                                <td colspan="2" style="text-align:center">Kelas 3</td>
                                <td colspan="2" style="text-align:center">Kelas 2</td>
                            </tr>
                            <tr>
                                <td style="text-align:center">Laki-Laki</td>
                                <td style="text-align:center">Perempuan</td>
                                <td style="text-align:center">Laki-Laki</td>
                                <td style="text-align:center">Perempuan</td>
                            </tr>
                            @foreach($jurusan as $jurusan)
                                <tr>
                                    <td>{{$jurusan->nm_jurusan}}</td>
                                </tr>
                            @endforeach
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
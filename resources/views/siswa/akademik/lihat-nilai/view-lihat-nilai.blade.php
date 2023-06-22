<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        NILAI MATA PELAJARAN
                    </h2>
                </div>
                <div class="body">
                    <table class="table table-bordered table-striped table-hover dataTable display responsive wrap">
                        <thead>
                            <tr>
                                <th>No. </th>
                                <th>Mapel</th>
                                <th>Guru Pengampu</th>
                                <th>Kelas</th>
                                <th>Nilai Angka</th>
                                <th>Nilai Huruf</th>
                            </tr>
                        </thead>
                        <tbody>
                                @php
                                    $nomer = 1; 
                                @endphp
                                @foreach ($nilaiKBM as $item)
                                <tr>
                                    <td>{{$nomer}}</td>
                                    <td>{{$item->nm_mata_pelajaran}}</td>
                                    <td>{{$item->nm_pengguna}}</td>
                                    <td>{{$item->nm_kelas}}</td>
                                    <td>{{$item->nilai_angka}}</td>
                                    <td>{{$item->nilai_huruf}}</td>
                                </tr>
                                @php
                                    $nomer++;
                                @endphp
                                @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
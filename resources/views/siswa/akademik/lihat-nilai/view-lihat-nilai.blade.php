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
                                <th>Mapel</th>
                                <th>Nilai Angka</th>
                                <th>Nilai Huruf</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach ($pengambilanmp as $item)
                                    @php
                                        $kelas = App\Models\KelasMP::where('id_kelas_mp',$item->id_kelas_mp)->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            {{$kelas->nm_kelas_mp}}
                                        </td>
                                        <td>
                                            {{isset($item->nilai_angka) ? $siswa->nilai_angka : 0}}
                                        </td>
                                        <td>
                                            {{$item->nilai_huruf}}
                                        </td>
                                    </tr>
                                @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@extends('app')
@section('meta')
    <!-- Meta -->
@endsection

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400&display=swap" rel="stylesheet">

@section('content')

    <body>
        <section class="section">
            <div class="container-fluid">

                <div class="container">

                    <br>

                    <!-- Widgets -->
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="body">

                                    <center>
                                        <img src="https://diakad.sgp1.digitaloceanspaces.com/{{ $sekolah->nm_singkat_sekolah }}/global/logo-sekolah"
                                            alt="Logo Sekolah" style="height:90px;" />
                                        <br>
                                        <h3 style="font-family: 'Nunito', sans-serif;">Data Penggunaan
                                            {{ strtoupper(env('APP_NAME', 'EDUMATE')) }} Untuk Setiap
                                            Guru {{ $semester_aktif->tahun_ajaran }}
                                            {{ $semester_aktif->nm_semester }}</h3>
                                        <h4 style="font-family: 'Nunito', sans-serif;">Per Tanggal :
                                            {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}</h4>
                                    </center>

                                    <hr>

                                    <div class="table-responsive">
                                        <table
                                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                            id="primary_table">
                                            <thead style="background: #009688 !important;color:white !important;">
                                                <tr>
                                                    <th style="text-align:center;width: 5%;">No</th>
                                                    <th style="width:15%;">Nama</th>
                                                    <th style="width:15%;">Menu</th>
                                                    <th style="text-align:center;width: 25%;">Jumlah Data</th>
                                                    <th style="text-align:center;">Progress</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $sortedData = collect($data)->sortByDesc('progres');
                                                @endphp
                                                @foreach ($sortedData as $key => $r)
                                                    <tr>
                                                        <td style="text-align:center;">{{ $loop->iteration }}</td>
                                                        <td>{{ $r['nama'] }}</td>
                                                        <td>
                                                            @foreach ($r['data'] as $data)
                                                                {{ $data }} <br>
                                                            @endforeach
                                                        </td>
                                                        <td style="text-align:center;">
                                                            @foreach ($r['status'] as $data)
                                                                {{ $data }} <br>
                                                            @endforeach

                                                        </td>
                                                        <td style="text-align:center;">

                                                            <div class="progress">
                                                                <div class="progress-bar progress-bar-red progress-bar-striped active"
                                                                    role="progressbar" aria-valuenow="{{ $r['progres'] }}"
                                                                    aria-valuemin="0" aria-valuemax="100"
                                                                    style="width: {{ $r['progres'] }}%;">
                                                                    {{ round($r['progres'], 0) }} %</div>
                                                            </div>

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
                </div>
                <!-- #END# Widgets -->

                <br>

            </div>

            </div>
        </section>
    </body>

    <!-- Modal Catatan -->
    <div class="modal fade" id="modalCatatan" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalCatatanHeader"></h4>
                </div>
                <div class="modal-body" id="modalCatatanBody">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Catatan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="isi_tabel">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Catatan -->
@endsection

<script>
    window.print();
</script>

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
                                    <img src="https://diakad.sgp1.digitaloceanspaces.com/{{ $sekolah->nm_singkat_sekolah }}/global/logo-sekolah" alt="Logo Sekolah" style="height:90px;" />
                                    <br>
                                    <h3 style="font-family: 'Nunito', sans-serif;">Data Penggunaan
                                        {{ strtoupper(env('APP_NAME', 'EDUMATE')) }} Untuk Setiap Role Semester
                                        {{ $semester_aktif->tahun_ajaran }} {{ $semester_aktif->nm_semester }}
                                    </h3>
                                    <h4 style="font-family: 'Nunito', sans-serif;">Per Tanggal :
                                        {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}
                                    </h4>
                                </center>

                                <hr>

                                <a onclick="window.print()" style="cursor:pointer;margin-bottom: 5px;" target="_blank" class="btn bg-red waves-effect">
                                    <i class="material-icons">print</i>
                                    <span>Print Laporan</span>
                                </a>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                        <thead style="background: #009688 !important;color:white !important;">
                                            <tr>
                                                <th style="text-align:center;width: 10%;">No</th>
                                                <th style="width:15%;">Role</th>
                                                <th style="text-align:center;width: 25%;">Status Penggunaan</th>
                                                <th style="text-align:center;">Progress</th>
                                                <th style="text-align:center;width: 15%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $key => $r)
                                            <tr>
                                                <td style="text-align:center;">{{ $loop->iteration }}</td>
                                                <td>{{ $r['role'] }}</td>
                                                <td style="text-align:center;">
                                                    @if ($r['status'] == 'Belum Digunakan')
                                                    @php $color ='danger'; @endphp
                                                    <span class="label bg-red detail-catatan">{{ $r['status'] }}</span>
                                                </td>
                                                @elseif($r['status'] == 'Sudah digunakan namun belum maksimal')
                                                @php $color ='warning'; @endphp
                                                <span class="label bg-orange detail-catatan">{{ $r['status'] }}</span>
                                                </td>
                                                @else
                                                @php $color ='success'; @endphp
                                                <span class="label bg-green detail-catatan">{{ $r['status'] }}</span>
                                                </td>
                                                @endif
                                                <td style="text-align:center;vertical-align: middle;">
                                                    @if ($r['status'])
                                                    <div class="progress">
                                                        <div class="progress-bar progress-bar-{{ $color }} progress-bar-striped active" role="progressbar" aria-valuenow="{{ $r['progress'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $r['progress'] }}%;vertical-align: middle;">
                                                            {{ round($r['progress'], 0) }} %
                                                        </div>
                                                    </div>
                                                    @endif
                                                </td>
                                                <td style="text-align:center;">
                                                    <button type="button" style="cursor:pointer;" onclick="detail_catatan({{ $r['id_role'] }})" class="btn bg-indigo waves-effect">
                                                        <i class="material-icons">remove_red_eye</i>
                                                        <span>Lihat Detail</span>
                                                    </button>
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

@section('js')
<script type="text/javascript">
    function detail_catatan(id_role) {

        $.ajax({
            url: base_url + '/reporting-dashboard/all-diakad/' + id_role,
            type: 'get',
            dataType: 'json',
            success: function(response) {
                $('#modalCatatanHeader').html('Catatan Untuk Role ' + response.nm_role);
                $('#isi_tabel').empty();
                $.each(response.catatan, function(i, value) {
                    if (value.status == 1) {
                        $('#isi_tabel').append(`
                            <tr>
                            <td>` + (i + 1) + `</td>
                            <td>` + value.catatan + `</td>
                            <td><i class="material-icons" style="color:green">check</i></td>
                            </tr>
                        `)
                    } else {
                        $('#isi_tabel').append(`
                            <tr>
                            <td>` + (i + 1) + `</td>
                            <td>` + value.catatan + `</td>
                            <td><i class="material-icons" style="color:red">clear</i></td>
                            </tr>
                        `)
                    }
                })
                $('#modalCatatan').modal('show');
            },
            error: function() {
                alert('mohon maaf terjadi kesalahan, silahkan hubungi admin');
            }
        })

    }
</script>
@endsection
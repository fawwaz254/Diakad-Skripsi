<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            @if ($cek_libur)
                <div class="alert alert-danger">
                    <strong>Tanggal {{ $date }} merupakan hari libur yaitu {{ $cek_libur->explanation }}
                </div>
            @endif
            <button type="button" class="btn btn-primary">
                Data Histori Absensi Guru dan Pegawai
            </button>
            <button type="button" onclick="viewSiswa()" class="btn btn-default">
                Data Histori Absensi Siswa
            </button>
            <div class="card" style="margin-top: 10px">
                <div class="header">
                    <h2>Filter Data</h2>
                </div>

                <div class="body">

                    <div class="row clearfix">

                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Unit Kerja
                            </h2>
                            <select class="form-control show-tick" name="unit_kerja">
                                <option @if($unit_kerja == "0" || $unit_kerja == null)  selected  @endif  value="0">-- Semua --</option>
                                <option @if($unit_kerja == "1" )  selected  @endif value="1">Pegawai</option>
                                @foreach ($list_unit_kerja as $uk)
                                    <option @if($unit_kerja == $uk->id_unit_kerja )  selected  @endif value="{{ $uk->id_unit_kerja }}">{{ $uk->nm_unit_kerja }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                            Date
                            </h2>
                            <input type="date" class="form-control" value="{{ $date }}" name="date"
                                aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div>
                                <button class="btn btn-block bg-red waves-effect" type="submit"   onclick="filterAction()"><i
                                    class="material-icons">save</i><span>Tampilkan</span></button>
                                {{-- <button type="button" class="btn bg-purple waves-effect"
                                    onclick="filterAction()">Change Date</button> --}}
                            </div>
                            
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <br>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="card">
              
                    <div class="header"><h2>Laporan Harian</h2>
                    </div>
                    <br>
                    <div class="body" >
                    <div class="table-responsive ">
                        <table class="table table-bordered" width="600px">
                            <thead style="background:#9C27B0;color:white">
                    {{-- <table id="example" class="table table-striped table-bordered" style="width:100%; 
                    padding: 10px; " > --}}
                       
                        <tr>
                          <th style="text-align: center;">Hadir</th>
                          <th style="text-align: center;">Hadir Terlambat</th>
                          <th style="text-align: center;">Belum Hadir</th>
                          <th style="text-align: center;">Izin</th>
                          <th style="text-align: center;">Sakit</th>
                          <th style="text-align: center;">Alpha</th>
                          <th style="text-align: center;">Tidak Checkout</th>
                          <th style="text-align: center;">Hadir Pulang Lebih Awal</th>
                        </tr>
                        </thead>
                        <tr>
                          <td style="text-align: center;">{{ $jumlah_hadir }}</td>
                          <td style="text-align: center;">{{ $jumlah_telat }}</td>
                       <td style="text-align: center;">{{ $belum_absent }}</td>
                       <td style="text-align: center;">{{ $jumlah_izin }}</td>
                       <td style="text-align: center;">{{ $jumlah_sakit }}</td>
                       <td style="text-align: center;">{{ $jumlah_alpha }}</td>
                       <td style="text-align: center;">{{ $tidak_checkout }}</td>
                       <td style="text-align: center;">{{ $jumlah_pulangcepat }}</td>
                      
                        </tr>
                      </table>
                </div>
            </div>
            </div>
        </div>
    </div>
    {{-- </div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="body bg-teal">
                    <div class="font-bold m-b--35">SUMMARY</div>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="dashboard-stat-list">
                                <li>
                                    Hadir
                                    <span class="pull-right"><b>{{ $jumlah_hadir }}</b></span>
                                </li>
                                <li>
                                    Hadir Terlambat
                                    <span class="pull-right"><b>{{ $jumlah_telat }}</b></span>
                                </li>
                                <li>
                                    Hadir Pulang Lebih Awal
                                    <span class="pull-right"><b>{{ $jumlah_pulangcepat }}</b></span>
                                </li>
                                <li>
                                    Tidak Checkout
                                    <span class="pull-right"><b>{{ $tidak_checkout }}</b></span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="dashboard-stat-list">
                                <li>
                                    Izin
                                    <span class="pull-right"><b>{{ $jumlah_izin }}</b></span>
                                </li>
                                <li>
                                    Sakit
                                    <span class="pull-right"><b>{{ $jumlah_sakit }}</b></span>
                                </li>
                                <li>
                                    Alpha
                                    <span class="pull-right"><b>{{ $jumlah_alpha }}</b></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <br>
        <a href="humas/absensi/histori-absensi/export-laravel/{{ $date }}"
            target="_blank" class="btn bg-purple waves-effect">
            <i class="material-icons" style="font-size: 15px;">print</i> Print Hari ini</a>
        <a href="humas/absensi/histori-absensi/export-laravel-mount/{{ $date }}"
            target="_blank" class="btn bg-purple waves-effect">
            <i class="material-icons" style="font-size: 15px;">print</i> Print Bulan ini</a>
    <br>
    <div class="row clearfix" style="margin-top: 10px">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Histori Absensi </h2>
                </div>

                <div class="body">
                    <div class="table-responsive ">
                        <table class="table table-bordered" width="600px">
                            <thead style="background:#9C27B0;color:white">
                                <tr>
                                    <th style="text-align: center;">#</th>
                                    <th style="text-align: center;">Nama</th>
                                    <th>Unit Kerja</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status</th>
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                             
                                @foreach ($hasil as $key => $r)
                                    @if ($key % 2 == 1)
                                        <tr style="background: #DDA0DD">
                                        @else
                                        <tr>
                                    @endif
                                    @if ($r['shift'])
                                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                                        <td style="text-align: center;">{{ $r['nm_pengguna'] }}</td>
                                        <td>{{ $r['unit_kerja']}}</td>
                                        <td>{{ $r['check_in'] }}</td>
                                        <td>{{ $r['check_out'] }}</td>
                                        <td>{{ $r['status'] }}</td>

                                        <td style="text-align: center;display:flex;justify-content:center">
                                            @if ($r['id_presensi_pengguna'] == '')
                                                <button type="button" class="btn bg-teal waves-effect"
                                                    onclick="addAbsensi('{{ $r['id_pengguna'] }}')">
                                                    <i class="material-icons">edit</i>
                                                </button>
                                            @else
                                                <button type="button" class="btn bg-teal waves-effect"
                                                    onclick="editAbsensi('{{ $r['id_presensi_pengguna'] }}')">
                                                    <i class="material-icons">edit</i>
                                                </button>
                                                <button data-id="{{ $r['id_presensi_pengguna'] }}"
                                                    style="margin-left:3px;"
                                                    class="btn bg-red waves-effect delete-record">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            @endif
                                        </td>
                                        </tr>
                                    @else
                                    @endif
                                @endforeach
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">


function viewSiswa(){
        window.location='/humas#absensi/histori-absensi-siswa'
    }


    function filterAction() {
        loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/' + $('input[name=date]').val() + '/' + $('select[name=unit_kerja]').val());
    }

    function addAbsensi(id_pengguna) {
        window.location = '/humas#absensi/histori-absensi/' + id_pengguna + '/' + $('input[name=date]').val() + '/add'
    }

    function editAbsensi(currUser) {
        window.location = '/humas#absensi/histori-absensi/' + currUser + '/' + $('input[name=date]').val() + '/edit'
    }


    $(".delete-record").click(function() {
        var token = $("meta[name='csrf-token']").attr("content");
        var id = $(this).data("id");
        swal({
                title: "Are you sure?",
                showCancelButton: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    $('.delete-record').attr("disabled", true);
                    //swall
                    $.ajax({
                        url: ` /humas/absensi/histori-absensi/${id}/delete`,
                        type: "post",

                        data: {
                            _token: token,
                        },

                        success: function() {
                            swal({
                                title: "Delete Success",
                                text: "data berhasil dihapus",
                                icon: "success",
                            });
                            loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/' +
                                $('input[name=date]').val());
                        },
                    });
                }
                return;
            }
        );
    });
</script>

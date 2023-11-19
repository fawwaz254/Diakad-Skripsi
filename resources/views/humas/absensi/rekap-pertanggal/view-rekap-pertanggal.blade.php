<style>
    input {
        position: relative;
        width: 150px;
        height: 20px;
        color: white;
    }

    input:before {
        position: absolute;
        top: 3px;
        left: 3px;
        content: attr(data-date);
        display: inline-block;
        color: black;
    }

    input::-webkit-datetime-edit,
    input::-webkit-inner-spin-button,
    input::-webkit-clear-button {
        display: none;
    }

    input::-webkit-calendar-picker-indicator {
        position: absolute;
        top: 3px;
        right: 0;
        color: black;
        opacity: 1;
    }
</style>

{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
<div class="container-fluid">

    {{-- <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            @if ($cek_libur)
                <div class="alert alert-danger">
                    <strong>Tanggal {{ $date }} merupakan hari libur yaitu {{ $cek_libur->explanation }}
                </div>
            @endif

            <div class="block-header">
                <h2><a class="btn bg-green waves-effect target-link "
                        href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/get-data') }}"><i
                            class="material-icons">library_add</i><span>Get Data Fingerprint</span></a></h2>
            </div>
            <button type="button" class="btn btn-primary">
                Data Histori Absensi Guru dan Pegawai
            </button>
            <button type="button" onclick="viewSiswa()" class="btn btn-default">
                Data Histori Absensi Siswa
            </button>


            @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'manbaulhikam')
                <button type="button" onclick="viewSiswaPondok()" class="btn btn-default">
                    Data Histori Absensi Siswa Pondok
                </button>
                <button type="button" onclick="viewSiswaSholat()" class="btn btn-default">
                    Data Histori Sholat
                </button>
            @endif
            <div class="card" style="margin-top: 10px">
                <div class="header">
                    <h2>Filter Data</h2>
                </div>

                <div class="body">

                    <div class="row clearfix">

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Unit Kerja
                            </h2>
                            <select class="form-control show-tick" name="unit_kerja">
                                <option @if ($unit_kerja == '0') selected @endif value="0">-- Semua --
                                </option>
                                <option @if ($unit_kerja == '1') selected @endif value="1">Pegawai
                                </option>
                                @foreach ($list_unit_kerja as $uk)
                                    <option @if ($unit_kerja == $uk->id_unit_kerja) selected @endif
                                        value="{{ $uk->id_unit_kerja }}">{{ $uk->nm_unit_kerja }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Status
                            </h2>
                            <select class="form-control show-tick" name="status">
                                <option @if ($status == '0') selected @endif value="0">-- Semua --
                                </option>
                                <option @if ($status == 'Masuk') selected @endif value="Masuk">Masuk</option>
                                <option @if ($status == 'izin') selected @endif value="izin">Izin</option>
                                <option @if ($status == 'sakit') selected @endif value="sakit">Sakit</option>
                                <option @if ($status == 'Masuk | Telat') selected @endif value="Masuk | Telat">Masuk |
                                    Telat</option>
                                <option @if ($status == 'Alpha') selected @endif value="Alpha">Alpha</option>
                                <option @if ($status == 'Masuk | Telat  | Tidak Checkout') selected @endif
                                    value="Masuk | Telat  | Tidak Checkout">Masuk | Telat | Tidak Checkout</option>
                                <option @if ($status == 'Masuk | Tidak Checkout') selected @endif
                                    value="Masuk | Tidak Checkout">Masuk | Tidak Checkout</option>
                                <option @if ($status == 'Masuk | Telat dan Pulang lebih awal') selected @endif
                                    value="Masuk | Telat dan Pulang lebih awal">Masuk | Telat dan Pulang lebih awal
                                </option>
                                <option @if ($status == 'Masuk | Pulang lebih awal') selected @endif
                                    value="Masuk | Pulang lebih awal">Masuk | Pulang lebih awal</option>




                                <option @if ($status == 'Belum Absent') selected @endif value="Belum Absent">Belum
                                    Absent</option>
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Date
                            </h2>
                            <input type="date" class="form-control" data-date="" data-date-format="DD/MM/YYYY"
                                value="{{ $date }}" name="date" aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div>
                                <button class="btn btn-block bg-red waves-effect" type="submit"
                                    onclick="filterAction()"><i
                                        class="material-icons">save</i><span>Tampilkan</span></button>
                               
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div> --}}

    {{-- <br> --}}
    {{-- <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="card">

                <div class="header">
                    <h2>Keterangan</h2>
                </div>
                <br>
                <div class="body">
                    <div class="table-responsive ">
                        <table class="table table-bordered" width="600px">
                            <thead style="background:#9C27B0;color:white">
                                <tr>
                                    <th style="text-align: center;">Masuk</th>
                                    <th style="text-align: center;">Alpha</th> --}}
    {{-- <th style="text-align: center;">Belum Hadir</th> --}}
    {{-- <th style="text-align: center;">Hadir Terlambat</th> --}}

    {{-- <th style="text-align: center;">Izin</th>
                                    <th style="text-align: center;">Sakit</th> --}}

    {{-- <th style="text-align: center;">Tidak Checkout</th> --}}
    {{-- <th style="text-align: center;">Hadir Pulang Lebih Awal</th> --}}
    {{-- </tr>
                            </thead>
                            <tr> --}}
    {{-- <td style="text-align: center;">{{ $jumlah_hadir }}</td>
                                <td style="text-align: center;">{{ $jumlah_alpha }}</td>
                                <td style="text-align: center;">{{ $belum_absent }}</td>
                                <td style="text-align: center;">{{ $jumlah_telat }}</td>
                                <td style="text-align: center;">{{ $jumlah_izin }}</td>
                                <td style="text-align: center;">{{ $jumlah_sakit }}</td>
                                <td style="text-align: center;">{{ $tidak_checkout }}</td>
                                <td style="text-align: center;">{{ $jumlah_pulangcepat }}</td> --}}

    {{-- </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    {{-- <br> --}}
    {{-- <a href="humas/absensi/histori-absensi/export-laravel/{{ $date }}/{{ $unit_kerja }}" target="_blank"
        class="btn bg-purple waves-effect">
        <i class="material-icons" style="font-size: 15px;">print</i> Print Hari ini</a>

    <a href="humas/absensi/histori-absensi/export-laravel-week/{{ $date }}/{{ $unit_kerja }}"
        target="_blank" class="btn bg-purple waves-effect">
        <i class="material-icons" style="font-size: 15px;">print</i> Print Minggu ini</a>


    <a href="humas/absensi/histori-absensi/export-laravel-mount/{{ $date }}/{{ $unit_kerja }}"
        target="_blank" class="btn bg-purple waves-effect">
        <i class="material-icons" style="font-size: 15px;">print</i> Print Bulan ini</a>
    <br> --}}
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Rekap Absensi </h2>
                </div>

                <div class="body">
                    <div class="table-responsive ">
                        <table class="table table-bordered table-striped table-hover dataTable display  nowrap">
                            <thead style="background:#4e4e4e;color:white">
                                <tr>
                                    <th rowspan="2" style="text-align: center;vertical-align:middle">#</th>
                                    <th rowspan="2" style="text-align: center;vertical-align:middle">Nama</th>
                                    {{-- <th rowspan="2" style="text-align: center;vertical-align:middle">Unit Kerja
                                    </th> --}}
                                    <th colspan="{{ $dates->count() }}" style="text-align: center;">Tanggal</th>

                                    {{-- <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status</th>
                                    <th style="text-align: center;">Action</th> --}}
                                </tr>
                                <tr>
                                    @foreach ($dates as $date)
                                        <th>
                                            {{ substr($date->format('l'), 0, 3) }}
                                            <br>
                                            {{ $date->format('d') }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                @endphp
                                @foreach ($pengguna as $p)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ isset($hasil[$p->id_pengguna]['nm_pengguna']) ? $hasil[$p->id_pengguna]['nm_pengguna'] : '' }}
                                        </td>
                                        @foreach ($dates as $date)
                                            @php
                                                $status = '';
                                                if (isset($hasil[$p->id_pengguna][$date->format('Y-m-d')])) {
                                                    $status = $hasil[$p->id_pengguna][$date->format('Y-m-d')];
                                                }
                                            @endphp
                                            <td
                                                @if ($status == 'M') style="text-align: center;background: #b5ffe0" @elseif($status == 'A') style="text-align: center;background: #ff9494" @else style="text-align: center;" @endif>
                                                {{ $status }}
                                            </td>
                                        @endforeach

                                    </tr>
                                @endforeach
                                {{-- @endif --}}

                                {{-- @foreach ($hasil as $r)
                                    @if ($no % 2 == 1)
                                        <tr style="background: #DDA0DD">
                                        @else
                                        <tr>
                                    @endif
                                    @if ($r['status'] == $status || $status == '0')
                                        <td style="text-align: center;">{{ $no++ }}</td>
                                        <td style="text-align: center;">{{ $r['nm_pengguna'] }}</td>

                                        <td>{{ $r['check_in'] }}</td>
                                        <td>{{ $r['check_out'] }}</td>
                                        <td
                                            @if ($r['status'] == 'Masuk') style="background: #b5ffe0" @elseif($r['status'] == 'Alpha') style="background: #ff9494"  @elseif($r['status'] == 'Belum Absent') style="background: #ffffff" @else style="background: #fffdb5" @endif>
                                            {{ $r['status'] }}</td>
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
                                @endforeach --}}

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script> --}}
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('.dataTable').DataTable({
            paging: false,
            lengthMenu: [
                [-1],
                ["All"]
            ]
        });
    });
    $("input").on("change", function() {
        this.setAttribute(
            "data-date",
            moment(this.value, "YYYY-MM-DD")
            .format(this.getAttribute("data-date-format"))
        )
    }).trigger("change")

    function viewSiswa() {
        window.location = '/humas#absensi/histori-absensi-siswa'
    }

    function viewSiswaPondok() {
        window.location = '/humas#absensi/histori-absensi-siswa-pondok'
    }

    function viewSiswaSholat() {
        window.location = '/humas#absensi/histori-absensi-siswa-sholat'

    }

    function filterAction() {
        loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/' + $('input[name=date]').val() + '/' + $(
            'select[name=unit_kerja]').val() + '/' + $('select[name=status]').val());
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
                                $('input[name=date]').val() + '/0/0');
                        },
                    });
                }
                return;
            }
        );
    });
</script>

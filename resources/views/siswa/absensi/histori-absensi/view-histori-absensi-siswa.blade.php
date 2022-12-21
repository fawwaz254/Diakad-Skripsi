<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Filter Data</h2>
                </div>

                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-5">
                            <label>Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" value="{{ $start_date }}"
                                name="start_date" aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-5">
                            <label>Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end_date" value="{{ $end_date }}"
                                name="end_date" aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-2">
                            <button type="button" class="btn waves-effect"
                                style="margin-top:27px; background:#009efa; color: white !important"
                                onclick="filterAction()">Tampilkan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-8">
            <div class="card" style="background: #009efa">
                <div class="body">
                    <div class="font-bold m-b--35" style="color: white">SUMMARY</div>

                    <div class="row">
                        <div class="col-md-6">
                            <ul class="dashboard-stat-list">
                                <li style="color: white">
                                    Hadir
                                    <span class="pull-right"><b>{{ $jumlah_hadir }}</b></span>
                                </li>
                                <li style="color: white">
                                    Hadir Terlambat
                                    <span class="pull-right"><b>{{ $jumlah_telat }}</b></span>
                                </li>
                                <li style="color: white">
                                    Hadir Pulang Lebih Awal
                                    <span class="pull-right"><b>{{ $jumlah_pulangcepat }}</b></span>
                                </li>
                                <li style="color: white">
                                    Tidak Checkout
                                    <span class="pull-right"><b>{{ $tidak_checkout }}</b></span>
                                </li>
                            </ul>
                        </div>

                        <div class="col-md-6">
                            <ul class="dashboard-stat-list">
                                <li style="color: white">
                                    Izin
                                    <span class="pull-right"><b>{{ $jumlah_izin }}</b></span>
                                </li>
                                <li style="color: white">
                                    Sakit
                                    <span class="pull-right"><b>{{ $jumlah_sakit }}</b></span>
                                </li>
                                <li style="color: white">
                                    Alpha
                                    <span class="pull-right"><b>{{ $jumlah_alpha }}</b></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Histori Absensi {{ Request::segment(1) == 'wali-murid' ? $siswa->nm_pengguna : '' }}</h2>
                </div>

                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead style="background:#009efa; color:white">
                                <tr>
                                    <th width="5%" style="text-align:center !important">No.</th>
                                    <th>Hari dan Tanggal</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hasil as $key => $r)
                                    @if ($r['status'] != 'Libur')
                                        <tr style="background:">
                                        @else
                                        <tr>
                                    @endif
                                    <td align="center">{{ $loop->iteration }}</td>
                                    <td>{{ \Carbon\Carbon::create($r['hari'])->isoFormat('dddd, D MMMM Y') }}</td>
                                    <td>{{ $r['check_in'] }}</td>
                                    <td>{{ $r['check_out'] }}</td>
                                    <td>{{ $r['status'] }}</td>
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

<script type="text/javascript">
    $("#end_date").change(function() {
        var startDate = document.getElementById("start_date").value;
        var endDate = document.getElementById("end_date").value;

        if ((Date.parse(endDate) < Date.parse(startDate))) {
            swal({
                title: "Tanggal Salah",
                text: "Tanggal akhir tidak boleh kurang dari tanggal mulai",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                timer: 2000,
            });
            document.getElementById("end_date").value = startDate;
        }
    });

    function filterAction() {
        loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/' + $('input[name=start_date]').val() + '/' + $(
            'input[name=end_date]').val());
    }
</script>

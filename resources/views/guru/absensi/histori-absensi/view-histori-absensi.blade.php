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
                            <label>Start Date</label>
                            <input type="date" class="form-control" value="{{ $start_date }}" name="start_date"
                                aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-5">
                            <label>End Date</label>
                            <input type="date" class="form-control" value="{{ $end_date }}" name="end_date"
                                aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-2">
                            <button type="button" class="btn bg-purple waves-effect" style="margin-top:27px;"
                                onclick="filterAction()">Change Date</button>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <br>

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
    </div>

    <br>

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">

                <div class="header">
                    <h2>Histori Absensi</h2>
                </div>

                <div class="body">

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead style="background:#9C27B0;color:white">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Hari</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Status</th>
                                    <th>Shift</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hasil as $key => $r)
                                    @if ($r['status'] == 'Libur' || $r['status'] == '')
                                        <tr>
                                        @elseif($r['status'] == 'Masuk')
                                        <tr style="background: #8ffbac">
                                        @elseif($r['status'] == 'Alpha')
                                        <tr style="background: #fb8f8f">
                                        @else
                                        <tr style="background: #fbf48f">
                                    @endif
                                    <td>{{ $r['tanggal'] }}</td>
                                    <td>{{ $r['hari'] }}</td>
                                    <td>{{ $r['check_in'] }}</td>
                                    <td>{{ $r['check_out'] }}</td>
                                    <td>{{ $r['status'] }}</td>
                                    @if ($r['shift'])
                                        <td>{{ $r['shift'] }} ({{ $r['start'] }} - {{ $r['end'] }})</td>
                                    @else
                                        <td></td>
                                    @endif
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
    function filterAction() {
        loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/' + $('input[name=start_date]').val() + '/' + $(
            'input[name=end_date]').val());
    }
</script>

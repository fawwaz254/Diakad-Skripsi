<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
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

                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <label>
                                Unit Kerja
                            </label>
                            <select class="form-control show-tick" name="unit_kerja" onchange="changeUnitKerja()">
                                <option>Pilih unit kerja</option>
                                <option value="1">Pegawai</option>
                                @foreach ($list_unit_kerja as $uk)
                                    <option value="{{ $uk->id_unit_kerja }}">{{ $uk->nm_unit_kerja }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <label>Nama Pengguna</label>
                            <select class="form-control show-tick" name="pengguna">
                                <option value="">Pilih unit kerja dahulu</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <label>Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" value="{{ $start_date }}"
                                name="start_date" aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <label>Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end_date" value="{{ $end_date }}"
                                name="end_date" aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <button type="button" class="btn bg-purple waves-effect" style="margin-top:27px;"
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

        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="margin-top: 15px">
            <a href="humas/absensi/detail-absensi/cetak/{{ $pengguna }}/{{ $start_date }}/{{ $end_date }}"
                target="_blank" class="btn btn-block bg-red waves-effect">
                <i class="material-icons">print</i><span> Print</span></a>
        </div>
    </div>

    <br>

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">

                <div class="header">
                    <h2>Histori Absensi ( {{ $nm_pengguna }} )</h2>
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
                                    @if ($r['status'] != 'Libur')
                                        <tr style="background: #FFCCF2">
                                        @else
                                        <tr>
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

    function changeUnitKerja(el) {
        $.ajax({
            url: '{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/post-get-penguna') }}',
            type: 'POST',
            data: {
                unit_kerja: $('select[name=unit_kerja]').val()
            },
            success: function(result) {
                $('select[name=pengguna]').html('');
                var html = '<option value="">-- Pilih Pengguna --</option>';
                $.each(result, function(key, item) {
                    html += '<option value="' + item.id_pengguna + '">' + item.nm_pengguna +
                        '</option>';
                });
                $('select[name=pengguna]').html(html);
            }
        });
    }

    function filterAction() {
        if ($('select[name=pengguna]').val() == '') {
            swal({
                title: "Nama Pengguna Belum Dipilih",
                text: "Dimohon pilih pengguna yang akan dicari terlebih dahulu",
                type: "warning",
                confirmButtonColor: "#DD6B55",
            })
            return
        } else {
            loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/' + $('select[name=pengguna]').val() + '/' +
                $('input[name=start_date]')
                .val() + '/' + $('input[name=end_date]').val());
        }
    }

    function viewSiswa() {
        window.location = '/humas#absensi/detail-absensi/siswa'
    }
</script>

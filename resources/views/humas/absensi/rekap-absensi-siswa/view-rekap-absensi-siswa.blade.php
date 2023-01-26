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

    /* input::-webkit-datetime-edit,
    input::-webkit-inner-spin-button,
    input::-webkit-clear-button {
        display: none;
    } */

    input::-webkit-calendar-picker-indicator {
        position: absolute;
        top: 3px;
        right: 0;
        color: black;
        opacity: 1;
    }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <button type="button" onclick="viewGuru()" class="btn btn-default">
                Data Histori Absensi Guru dan Pegawai
            </button>
            <button type="button" class="btn btn-primary">
                Data Histori Absensi Siswa
            </button>
            <div class="card" style="margin-top: 10px">
                <div class="header">
                    <h2>Filter Data</h2>
                </div>

                <div class="body">

                    <div class="row clearfix">

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <label>
                                Kelas
                            </label>
                            <select class="form-control show-tick" name="kelas">
                                <option selected disabled value="">Pilih Kelas</option>
                                {{-- <option @if ($id_kelas == '0') selected @endif value="0">-- Semua --
                                </option> --}}
                                {{-- <option @if ($unit_kerja == '1') selected @endif value="1">Pegawai
                                </option> --}}
                                {{-- @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'manbaulhikam')
                                    <option value="1">-- Madrasah Tsanawiyah (MTs) --</option>
                                    <option value="2">-- Madrasah Aliyah (MA) --</option>
                                @endif --}}
                                @foreach ($list_kelas as $lk)
                                    <option @if ($id_kelas == $lk->id_kelas) selected @endif
                                        value="{{ $lk->id_kelas }}">{{ $lk->nm_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <label>Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" value="{{ $start_date }}"
                                name="start_date" aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <label>Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end_date" value="{{ $end_date }}"
                                name="end_date" aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div>
                                <button class="btn btn-block bg-red waves-effect" type="submit"
                                    onclick="filterAction()"><i
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

                <div class="header">
                    <h2>Data Rekap Absensi Siswa Kelas {{ $nama_kelas->nm_kelas }}</h2>
                </div>
                <br>
                <div class="body">
                    <div class="table-responsive ">
                        <table class="table table-bordered" width="600px">
                            <thead style="background:#9C27B0;color:white">
                                <tr>
                                    <th style="text-align: center;">Hadir</th>
                                    <th style="text-align: center;">Hadir Terlambat</th>
                                    <th style="text-align: center;">Izin</th>
                                    <th style="text-align: center;">Sakit</th>
                                    <th style="text-align: center;">Alpha</th>
                                    @if ($setting == '1')
                                        <th style="text-align: center;">Tidak Checkout</th>
                                        <th style="text-align: center;">Pulang Lebih Awal</th>
                                    @endif
                                </tr>
                            </thead>
                            <tr>
                                <td style="text-align: center;">{{ $jumlah_hadir }}</td>
                                <td style="text-align: center;">{{ $jumlah_telat }}</td>
                                <td style="text-align: center;">{{ $jumlah_izin }}</td>
                                <td style="text-align: center;">{{ $jumlah_sakit }}</td>
                                <td style="text-align: center;">{{ $jumlah_alpha }}</td>
                                @if ($setting == '1')
                                    <td style="text-align: center;">{{ $tidak_checkout }}</td>
                                    <td style="text-align: center;">{{ $jumlah_pulangcepat }}</td>
                                @endif
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="card">

                <div class="header">
                    <h2>Print Rekap Absensi Siswa Minggu Ini</h2>
                </div>
                <div class="body">
                    @foreach ($groupKelas as $kelas)
                        <a href="humas/absensi/rekap-absensi/print-mingguan/siswa/{{ $kelas->tingkat }}/{{ $kelas->id_jurusan }}"
                            target="_blank" class="btn bg-purple waves-effect m-2" style=" margin: 5px !important">
                            <i class="material-icons" style="font-size: 15px;">print</i> Seluruh Kelas
                            {{ '( ' . $kelas->tingkat . ' ' . $kelas->nm_jurusan . ' )' }}</a>
                    @endforeach

                    {{-- <a href="humas/absensi/rekap-absensi/allDataChart/siswa/0/{{ $start_date }}/{{ $end_date }}" target="_blank"
                    class="btn bg-purple waves-effect">
                    <i class="material-icons" style="font-size: 15px;">print</i> Print Semua Kelas</a>
                    <br> --}}
                </div>
            </div>
        </div>
    </div>
    <br>

    <div class="row clearfix" style="margin-top: 10px">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Histori Absensi Siswa Kelas {{ $nama_kelas->nm_kelas }}</h2>

                    <a href="humas/absensi/rekap-absensi/allDataChart/siswa/0/{{ $id_kelas }}/{{ $start_date }}/{{ $end_date }}"
                        target="_blank" class="btn bg-blue waves-effect" style=" margin-top: 20px !important;">
                        <i class="material-icons" style="font-size: 15px;">print</i> Print Kelas
                        {{ $nama_kelas->nm_kelas }}</a>
                    {{-- @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'manbaulhikam')
                        <a href="humas/absensi/rekap-absensi/allDataChart/siswa/1/{{ $start_date }}/{{ $end_date }}"
                            target="_blank" class="btn bg-purple waves-effect">
                            <i class="material-icons" style="font-size: 15px;">print</i> Print Madrasah Tsanawiyah (MTs)</a>
                        <a href="humas/absensi/rekap-absensi/allDataChart/siswa/2/{{ $start_date }}/{{ $end_date }}"
                            target="_blank" class="btn bg-purple waves-effect">
                            <i class="material-icons" style="font-size: 15px;">print</i> Print Madrasah Aliyah (MA)</a>
                    @endif --}}
                </div>

                <div class="body">
                    <div class="table-responsive ">
                        <table class="table table-bordered" width="600px">
                            <thead style="background:#9C27B0;color:white">
                                <tr>
                                    <th style="text-align: center;vertical-align: middle;">#</th>
                                    <th style="text-align: center;vertical-align: middle;">Nama</th>
                                    <th style="text-align: center;vertical-align: middle;">Unit Kerja</th>
                                    <th style="text-align: center;vertical-align: middle;">Tepat Waktu</th>
                                    <th style="text-align: center;vertical-align: middle;">Terlambat</th>
                                    {{-- <th style="text-align: center;vertical-align: middle;">Belum Hadir</th> --}}
                                    <th style="text-align: center;vertical-align: middle;">Izin</th>
                                    <th style="text-align: center;vertical-align: middle;">Sakit</th>
                                    <th style="text-align: center;vertical-align: middle;">Alpha</th>
                                    <th style="text-align: center;vertical-align: middle;">Tidak <br> Checkout</th>
                                    <th style="text-align: center;vertical-align: middle;">Terlambat, <br> Tidak
                                        Checkout</th>
                                    <th style="text-align: center;vertical-align: middle;">Pulang <br> Lebih Awal</th>
                                    <th style="text-align: center;vertical-align: middle;">Terlambat, <br> Pulang Lebih
                                        Awal</th>
                                    {{-- <th style="text-align: center;vertical-align: middle;">Kosong</th>
                                    <th style="text-align: center;vertical-align: middle;">Libur</th> --}}
                                    <th style="text-align: center;vertical-align: middle;">Detail</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($data as $key => $r)
                                    @if ($key % 2 == 1)
                                        <tr style="background: #DDA0DD">
                                        @else
                                        <tr>
                                    @endif

                                    <td style="text-align: center;" style="text-align: center;">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td style="text-align: center;" style="text-align: center;">
                                        {{ $r['nm_pengguna'] }}
                                    </td>
                                    <td style="text-align: center;">{{ $r['kelas'] }}</td>
                                    <td style="text-align: center;">{{ $r['masuk'] }}</td>
                                    <td
                                        @if ($r['telat'] > 3) style="text-align: center;background-color : #ff8080" @else  style="text-align: center;" @endif>
                                        {{ $r['telat'] }}</td>
                                    <td
                                        @if ($r['izin'] > 3) style="text-align: center;background-color : #ff8080" @else  style="text-align: center;" @endif>
                                        {{ $r['izin'] }}</td>
                                    <td
                                        @if ($r['sakit'] > 3) style="text-align: center;background-color : #ff8080" @else  style="text-align: center;" @endif>
                                        {{ $r['sakit'] }}</td>
                                    <td
                                        @if ($r['alpha'] > 3) style="text-align: center;background-color : #ff8080" @else  style="text-align: center;" @endif>
                                        {{ $r['alpha'] }}</td>
                                    @if ($setting == '1')
                                        <td
                                            @if ($r['tidakCheckout'] > 3) style="text-align: center;background-color : #ff8080" @else  style="text-align: center;" @endif>
                                            {{ $r['tidakCheckout'] }}</td>
                                        <td
                                            @if ($r['Telat & Tidak Checkout'] > 3) style="text-align: center;background-color : #ff8080" @else  style="text-align: center;" @endif>
                                            {{ $r['Telat & Tidak Checkout'] }}</td>
                                        <td
                                            @if ($r['pulang'] > 3) style="text-align: center;background-color : #ff8080" @else  style="text-align: center;" @endif>
                                            {{ $r['pulang'] }}</td>
                                        <td
                                            @if ($r['telatDanPulangLebihAwal'] > 3) style="text-align: center;background-color : #ff8080" @else  style="text-align: center;" @endif>
                                            {{ $r['telatDanPulangLebihAwal'] }}</td>
                                        {{-- <td style="text-align: center;">{{ $r['kosong'] }}</td>
                                            <td style="text-align: center;">{{ $r['libur'] }}</td> --}}
                                    @endif
                                    <td><a class=" btn btn-success btn-circle waves-effect waves-circle waves-float"
                                            href=" {{ url(Request::segment(1) . '/' . Request::segment(2) . '/rekap-absensi/cetak/siswa/' . $r['id_pengguna'] . '/' . $start_date . '/' . $end_date) }} "
                                            target="_blank"><i class="material-icons">picture_as_pdf</i></a> <a
                                            class=" btn btn-success btn-circle waves-effect waves-circle waves-float"
                                            href=" {{ url(Request::segment(1) . '/' . Request::segment(2) . '/rekap-absensi/chart/siswa/' . $r['id_pengguna'] . '/' . $start_date . '/' . $end_date) }} "
                                            target="_blank"><i class="material-icons">insert_chart</i></a></td>
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
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script> --}}
<script type="text/javascript">
    // $("input").on("change", function() {
    //     this.setAttribute(
    //         "data-date",
    //         moment(this.value, "YYYY-MM-DD")
    //         .format(this.getAttribute("data-date-format"))
    //     )
    // }).trigger("change")

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

    function viewGuru() {
        window.location = '/humas#absensi/rekap-absensi'
    }

    function filterAction() {
        var id_kelas = $('select[name="kelas"]').val();
        if (!id_kelas) {
            swal({
                title: "Pilih Kelas dahulu",
                text: "Kelas tidak boleh kosong",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                timer: 2000,
            });
        } else {
            loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/detail/siswa/' + $('select[name=kelas]')
                .val() +
                '/' + $(
                    'input[name=start_date]').val() + '/' + $('input[name=end_date]').val());
        }
    }
</script>

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
<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @if (Request::segment(1) == 'humas')
                <button type="button" onclick="viewGuru()" class="btn btn-default">
                    Data Histori Absensi Guru dan Pegawai
                </button>
                <button type="button" class="btn btn-primary">
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
            @endif

            <div class="card" style="margin-top: 10px">
                <div class="header">
                    <h2>
                        DATA SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/histori-absensi-siswa') }}">
                        {{ csrf_field() }}
                        <div class="row clearfix">
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Kelas
                                </h2>
                                <select class="form-control show-tick" name="kelas">
                                    <option value="0">-- Semua --</option>
                                    @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'manbaulhikam')
                                        <option value="1">-- Madrasah Tsanawiyah (MTs) --</option>
                                        <option value="2">-- Madrasah Aliyah (MA) --</option>
                                    @endif
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id_kelas }}"
                                            @if ($wali_kelas->id_kelas ?? null == $k->id_kelas) SELECTED @endif>{{ $k->nm_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Status
                                </h2>
                                <select class="form-control show-tick" name="status">
                                    <option value="0">-- Semua --</option>
                                    <option value="Masuk">Masuk</option>
                                    <option value="izin">Izin</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="Masuk | Telat">Masuk | Telat</option>
                                    <option value="Alpha">Alpha</option>
                                    <option value="Belum Absent">Belum Absent</option>
                                </select>
                            </div>

                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Tanggal
                                </h2>
                                <input type="date" class="form-control" data-date="" data-date-format="DD/MM/YYYY"
                                    value="{{ $date }}" name="date" aria-required="true" aria-invalid="true">
                            </div>


                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Tampilkan</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
{{-- <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="body">
                <div class="font-bold ">Laporan Harian</div>
                <br>
                <table id="example" class="table table-striped table-bordered"
                    style="width:100%; 
                padding: 10px; ">
                    <thead>
                        <tr>
                            <th>Hadir</th>
                            <th>Hadir Terlambat</th>
                            <th>Belum Hadir</th>
                            <th>Izin</th>
                            <th>Sakit</th>
                            <th>Alpha</th>
                        </tr>
                    </thead>
                    <tr>
                        <td>{{ $jumlah_hadir }}</td>
                        <td>{{ $jumlah_telat }}</td>
                        <td>{{ $belum_absent }}</td>
                        <td>{{ $jumlah_izin }}</td>
                        <td>{{ $jumlah_sakit }}</td>
                        <td>{{ $jumlah_alpha }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div> --}}


@include('scriptjs')
<script type="text/javascript">
    $("input").on("change", function() {
        this.setAttribute(
            "data-date",
            moment(this.value, "YYYY-MM-DD")
            .format(this.getAttribute("data-date-format"))
        )
    }).trigger("change")

    function viewGuru() {
        window.location = '/humas#absensi/histori-absensi'
    }

    function viewSiswaPondok() {
        window.location = '/humas#absensi/histori-absensi-siswa-pondok'
    }

    function viewSiswaSholat() {
        window.location = '/humas#absensi/histori-absensi-siswa-sholat'

    }
</script>
{{-- <script type="text/javascript">
    $(document).ready(function() {
        $('select').select();
    });

    var modul_url = 'absensi';

    $('#jurusan').on('change', function(e) {
        console.log(e);
 
        var id_jurusan = e.target.value;
        $.get(base_url + '/' + role_url + '/' + modul_url + '/' + 'histori-absensi-siswa/get-kelas/' + id_jurusan,
            function(data) {
                console.log(data);
                $('#kelas').empty();


                $('#kelas').append($("<option>")
                    .attr("value", 0)
                    .text("-- Semua --")
                );
                $.each(data, function(index, kelasObj) {
                    $('#kelas').append($("<option>")
                        .attr("value", kelasObj.id_kelas)
                        .text(kelasObj.nm_kelas)
                    );
                })

                $('select').select();
            });
    });
</script> --}}

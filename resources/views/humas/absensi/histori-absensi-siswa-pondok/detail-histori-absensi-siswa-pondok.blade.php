<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        @if(Request::segment(1) == 'humas')
            <button type="button" onclick="viewGuru()" class="btn btn-default">
                Data Histori Absensi Guru dan Pegawai
            </button>
            <button type="button" class="btn btn-default">
                Data Histori Absensi Siswa
            </button>
            <button type="button" class="btn btn-primary">
                Data Histori Absensi Siswa Pondok
            </button>

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
                                <option @if ($id_kelas == "0") selected @endif value="0">-- Semua --</option>
                                @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'manbaulhikam')
                                <option @if ($id_kelas == "1") selected @endif value="1">-- Madrasah Tsanawiyah (MTs) --</option>
                                <option @if ($id_kelas == "2") selected @endif value="2">-- Madrasah Aliyah (MA) --</option>
                                @endif
                                @foreach ($kelas as $k)
                                    <option @if ($id_kelas == $k->id_kelas) selected @endif
                                        value="{{ $k->id_kelas }}">{{ $k->nm_kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Status
                            </h2>
                            <select class="form-control show-tick" name="status"> 
                                
                                <option @if ($status == "0") selected @endif value="0">-- Semua --</option>
                                <option @if ($status == "Masuk") selected @endif value="Masuk">Masuk</option>
                                <option @if ($status == "izin") selected @endif  value="izin">Izin</option>
                                <option @if ($status == "sakit") selected @endif  value="sakit">Sakit</option>
                                <option @if ($status == "Masuk | Telat") selected @endif  value="Masuk | Telat">Masuk | Telat</option>
                                <option @if ($status == "Alpha") selected @endif  value="Alpha">Alpha</option>
                                <option @if ($status == "Belum Absent") selected @endif  value="Belum Absent">Belum Absent</option>
                            </select>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Tanggal
                            </h2>
                            <input type="date" class="form-control" value="{{ $date }}" name="date"
                                aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
<div class="row">
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
</div>
<br>
<a href="{{url(Request::segment(1).'/absensi/histori-absensi-siswa/export-laravel/'.$id_kelas.'/'.$date)}}" target="_blank"
    class="btn bg-purple waves-effect">
    <i class="material-icons" style="font-size: 15px;">print</i> Print Hari ini</a>

    <a href="{{url(Request::segment(1).'/absensi/histori-absensi-siswa/export-laravel-week/'.$id_kelas.'/'.$date)}}"
    target="_blank" class="btn bg-purple waves-effect">
    <i class="material-icons" style="font-size: 15px;">print</i> Print Minggu ini</a>

<a href="{{url(Request::segment(1).'/absensi/histori-absensi-siswa/export-laravel-mount/'.$id_kelas.'/'.$date)}}"
    target="_blank" class="btn bg-purple waves-effect">
    <i class="material-icons" style="font-size: 15px;">print</i> Print Bulan ini</a>
<br>
<input type="hidden" value="{{ $id_kelas }}" name="id_kelas">
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
                                <th style="text-align: center;">Kelas</th>
                                <th style="text-align: center;">NIS</th>
                                <th style="text-align: center;">Nama</th>

                                <th>Check In</th>
                                {{-- <th>Check Out</th> --}}
                                <th>Status</th>
                                {{-- @if(Request::segment(1) == 'humas') --}}
                                <th style="text-align: center;">Action</th>
                                {{-- @endif --}}
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
                                @if($r['status'] == $status || $status == '0')
                                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                                    <td style="text-align: center;">{{ $r['kelas'] }}</td>
                                    <td style="text-align: center;">{{ $r['nis'] }}</td>
                                    <td style="text-align: center;">{{ $r['nm_pengguna'] }}</td>

                                    <td>{{ $r['check_in'] }}</td>
                                    {{-- <td>{{ $r['check_out'] }}</td> --}}
                                    <td
                                        @if ($r['status'] == 'Masuk') style="background: #b5ffe0" @elseif($r['status'] == 'Alpha') style="background: #ff9494" @else style="background: #fffdb5" @endif>
                                        {{ $r['status'] }}</td>

                                    {{-- @if(Request::segment(1) == 'humas') --}}
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
                                            <button data-id="{{ $r['id_presensi_pengguna'] }}" style="margin-left:3px;"
                                                class="btn bg-red waves-effect delete-record">
                                                <i class="material-icons">delete</i>
                                            </button>
                                        @endif
                                    </td>
                                    {{-- @endif --}}
                                    </tr>
                                    @endif
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

@include('scriptjs')
<script type="text/javascript">
    var role = window.location.pathname;

    function viewGuru() {
        window.location = '/humas#absensi/histori-absensi'
    }

    function addAbsensi(id_pengguna) {
        window.location = `${role}#absensi/histori-absensi-siswa/${id_pengguna}/${$('input[name=id_kelas]').val()}/${$('input[name=date]').val()}/add`
    }

    function editAbsensi(currUser) {
        window.location = `${role}#absensi/histori-absensi-siswa/${currUser}/${$('input[name=id_kelas]').val()}/${$('input[name=date]').val()}/edit`
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
                        url: `${role}/absensi/histori-absensi-siswa/${id}/delete`,
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
                            loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/{{ Request::segment(4) }}/' +
                                $('input[name=id_kelas]').val() + '/' +
                                $('input[name=date]').val() + '/0') ;
                        },
                    });
                }
                return;
            }
        );
    });
</script>
{{-- {{-- <script type="text/javascript">
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

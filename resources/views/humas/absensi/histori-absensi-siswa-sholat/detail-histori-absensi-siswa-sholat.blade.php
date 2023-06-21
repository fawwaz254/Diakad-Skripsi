<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        @if (Request::segment(1) == 'humas')
            <button type="button" onclick="viewGuru()" class="btn btn-default">
                Data Histori Absensi Guru dan Pegawai
            </button>
            <button type="button" onclick="viewSiswa()" class="btn btn-default">
                Data Histori Absensi Siswa
            </button>
            <button type="button" onclick="viewSiswaPondok()" class="btn btn-default">
                Data Histori Absensi Siswa Pondok
            </button>

            <button type="button" class="btn btn-primary">
                Data Histori Absensi Siswa Sholat
            </button>
        @endif
        <div class="card" style="margin-top: 10px">
            <div class="header">
                <h2>
                    DATA SISWA SHOLAT
                </h2>
            </div>
            <div class="body">
                <form id="form-validation" method="POST"
                    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/histori-absensi-siswa-sholat') }}">
                    {{ csrf_field() }}
                    <div class="row clearfix">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Kelas
                            </h2>
                            <select class="form-control show-tick" name="kelas">
                                <option @if ($id_kelas == '0') selected @endif value="0">-- Semua --
                                </option>
                                @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'manbaulhikam')
                                    <option @if ($id_kelas == '1') selected @endif value="1">-- Madrasah
                                        Tsanawiyah (MTs) --</option>
                                    <option @if ($id_kelas == '2') selected @endif value="2">-- Madrasah
                                        Aliyah (MA) --</option>
                                @endif
                                @foreach ($kelas as $k)
                                    <option @if ($id_kelas == $k->id_kelas) selected @endif
                                        value="{{ $k->id_kelas }}">{{ $k->nm_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
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
                            <th style="text-align: center;">Siswa</th>
                            <th style="text-align: center;">Subuh</th>
                            <th style="text-align: center;">Dzuhur</th>
                            <th style="text-align: center;">Magrib</th>
                            <th style="text-align: center;">Isya</th>
                        </tr>
                    </thead>
                    <tr>
                        <td style="text-align: center;">{{ $pengguna->count() }}</td>
                        <td style="text-align: center;">{{ $jumlah_subuh }}</td>
                        <td style="text-align: center;">{{ $jumlah_dzuhur }}</td>
                        <td style="text-align: center;">{{ $jumlah_magrib }}</td>
                        <td style="text-align: center;">{{ $jumlah_isya }}</td>

                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- <br>
<a href="{{ url(Request::segment(1) . '/absensi/histori-absensi-siswa/export-laravel/' . $id_kelas . '/' . $date) }}"
    target="_blank" class="btn bg-purple waves-effect">
    <i class="material-icons" style="font-size: 15px;">print</i> Print Hari ini</a>

<a href="{{ url(Request::segment(1) . '/absensi/histori-absensi-siswa/export-laravel-week/' . $id_kelas . '/' . $date) }}"
    target="_blank" class="btn bg-purple waves-effect">
    <i class="material-icons" style="font-size: 15px;">print</i> Print Minggu ini</a> --}}

{{-- <a href="{{url(Request::segment(1).'/absensi/histori-absensi-siswa/export-laravel-mount/'.$id_kelas.'/'.$date)}}"
    target="_blank" class="btn bg-purple waves-effect">
    <i class="material-icons" style="font-size: 15px;">print</i> Print Bulan ini</a> --}}
{{-- <br> --}}
<input type="hidden" value="{{ $id_kelas }}" name="id_kelas">
<div class="row clearfix" style="margin-top: 10px">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card">
            <div class="header">
                <h2>Histori Absensi </h2>
                <br>
                <a href="humas/absensi/histori-absensi-siswa-sholat/export-day/{{ $date }}/{{ $id_kelas }}"
                    target="_blank" class="btn bg-purple waves-effect">
                    <i class="material-icons" style="font-size: 15px;">print</i> Print Harian</a>
                <br>
            </div>
            <div class="body">
                <div class="table-responsive ">
                    <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                        <thead style="background:#9C27B0;color:white">
                            <tr>
                                <th style="text-align: center;">#</th>
                                <th style="text-align: center;">Kelas</th>
                                <th style="text-align: center;">NIS</th>
                                <th style="text-align: center;">Nama</th>
                                <th style="text-align: center;">Subuh</th>
                                <th style="text-align: center;">Dzuhur</th>
                                <th style="text-align: center;">Maghrib</th>
                                <th style="text-align: center;">Isya</th>
                                {{-- <th style="text-align: center;">Rekap 7 Hari Terakhir</th> --}}

                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $no = 1;
                            @endphp
                            @foreach ($hasil as $r)
                                @if ($no % 2 == 0)
                                    <tr style="background: #DDA0DD">
                                    @else
                                    <tr>
                                @endif
                                <td style="text-align: center;">{{ $no++ }}</td>
                                <td style="text-align: center;">{{ $r['kelas'] }}</td>
                                <td style="text-align: center;">{{ $r['nis'] }}</td>
                                <td style="text-align: center;">{{ $r['nm_pengguna'] }}</td>

                                <td style="text-align: center;">{{ $r['subuh'] }}</td>
                                <td style="text-align: center;">{{ $r['dzuhur'] }}</td>
                                <td style="text-align: center;">{{ $r['maghrib'] }}</td>
                                <td style="text-align: center;">{{ $r['isya'] }}</td>
                                {{-- <td style="text-align: center;">{{ $r['rekap'] }}</td> --}}
                                </tr>
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

    function viewSiswa() {
        window.location = '/humas#absensi/histori-absensi-siswa'
    }

    // function addAbsensi(id_pengguna) {
    //     window.location =
    //         `${role}#absensi/histori-absensi-siswa/${id_pengguna}/${$('input[name=id_kelas]').val()}/${$('input[name=date]').val()}/add`
    // }

    // function editAbsensi(currUser) {
    //     window.location =
    //         `${role}#absensi/histori-absensi-siswa/${currUser}/${$('input[name=id_kelas]').val()}/${$('input[name=date]').val()}/edit`
    // }

    function viewSiswaPondok() {
        window.location = '/humas#absensi/histori-absensi-siswa-pondok'
    }

    $(document).ready(function() {
        var table = $('.dataTable').DataTable({
            paging: false,
            lengthMenu: [
                [-1],
                ["All"]
            ]
        });
    });
    // $(".delete-record").click(function() {
    //     var token = $("meta[name='csrf-token']").attr("content");
    //     var id = $(this).data("id");

    //     swal({
    //             title: "Are you sure?",
    //             showCancelButton: true
    //         },
    //         function(isConfirm) {
    //             if (isConfirm) {
    //                 $('.delete-record').attr("disabled", true);
    //                 //swall
    //                 $.ajax({
    //                     url: `${role}/absensi/histori-absensi-siswa/${id}/delete`,
    //                     type: "post",

    //                     data: {
    //                         _token: token,
    //                     },

    //                     success: function() {
    //                         swal({
    //                             title: "Delete Success",
    //                             text: "data berhasil dihapus",
    //                             icon: "success",
    //                         });
    //                         loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/{{ Request::segment(4) }}/' +
    //                             $('input[name=id_kelas]').val() + '/' +
    //                             $('input[name=date]').val() + '/0');
    //                     },
    //                 });
    //             }
    //             return;
    //         }
    //     );
    // });
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

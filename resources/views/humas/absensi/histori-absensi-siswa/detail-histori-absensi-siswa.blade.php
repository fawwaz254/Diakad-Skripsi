
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <button type="button" onclick="viewGuru()" class="btn btn-default">
                Data Histori Absensi Guru dan Pegawai
            </button>
            <button type="button" class="btn btn-primary">
                Data Histori Absensi Siswa
            </button>
            <div class="card" style="margin-top: 10px">
                <div class="header">
                    <h2>
                        DATA SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-view-data-siswa') }}">
                        {{ csrf_field() }}
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Kelas
                                </h2>
                                <select class="form-control show-tick" name="id_jurusan" id="jurusan">
                                    <option value="0">-- Semua --</option>
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id_kelas }}">{{ $k->nm_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row clearfix">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <h2 class="card-inside-title">
                                        Tanggal
                                    </h2>
                                    <input type="date" class="form-control" value="{{ $date }}"
                                        name="date" aria-required="true" aria-invalid="true">
                                </div>
                            </div>
                            <div class="row clearfix">
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
            <div class="body" >
                <div class="font-bold ">Laporan Harian</div>
                <br>
                <table id="example" class="table table-striped table-bordered" style="width:100%; 
                padding: 10px; " >
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
                   <td>0</td>
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
<div class="row clearfix">
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
                                <th>Role</th>
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
                                    <td>{{ $r['status_join_table'] == 1 ? 'Pegawai' : 'Guru' }}</td>
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

@include('scriptjs')
<script  type="text/javascript">
function viewGuru(){
        window.location='/humas#absensi/histori-absensi'
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

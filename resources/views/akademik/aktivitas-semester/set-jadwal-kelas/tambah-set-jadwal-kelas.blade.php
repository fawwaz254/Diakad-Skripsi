<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/copy-jadwal-kelas/view-copy-jadwal-kelas') }}"><i
                    class="material-icons">content_copy</i><span>Copy Data Dari Semester Sebelumnya</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="margin-top: 10px">
                <div class="header">
                    <h2>
                        SET JADWAL KELAS
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3)) }}">
                        {{ csrf_field() }}
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Kelas
                                </h2>
                                <select class="form-control show-tick" name="id_kelas">
                                    <option selected="" disabled="">Pilih Kelas</option>
                                    @foreach ($data_kelas as $k)
                                        <option value="{{ $k->id_kelas }}"
                                            @if ($kelas->id_kelas == $k->id_kelas) selected @endif>{{ $k->nm_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Semester
                                </h2>
                                <select class="form-control show-tick" name="id_semester">
                                    @foreach ($data_semester as $s)
                                        @if ($s->is_aktif_semester == 1)
                                            <option value="{{ $s->id_semester }}"
                                                @if ($semester->id_semester == $s->id_semester) selected @endif>
                                                {{ $s->tahun_ajaran }} {{ $s->nm_semester }} (Aktif)
                                            </option>
                                        @else
                                            <option value="{{ $s->id_semester }}"
                                                @if ($semester->id_semester == $s->id_semester) selected @endif>
                                                {{ $s->tahun_ajaran }} {{ $s->nm_semester }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Tampilkan</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card" style="margin-top: 10px;padding: 10px">
                <div class="header">
                    <h2>
                        SET JADWAL KELAS {{ $kelas->nm_kelas }} - {{ $semester->tahun_ajaran }}
                        {{ $semester->nm_semester }}
                    </h2>
                </div>
                <div class="table-responsive ">
                    <table class="table table-bordered" width="600px">
                        <thead style="background:#54bebe;color:white">
                            <tr>
                                <th style="text-align: center;" width="5px">#</th>
                                @foreach ($jadwal_hari as $hari)
                                    <th style="text-align: center;" width="85px">{{ $hari->nm_jadwal_hari }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jam as $key => $r)
                                <tr
                                    @if ($key % 2 == 1) style="background: #98d1d1" @else style="background: #badbdb" @endif>
                                    <td style="text-align: center; vertical-align: middle;">
                                        @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'smkypm1taman')
                                            {{ $key }}
                                        @else
                                            {{ $key + 1 }}
                                        @endif
                                    </td>
                                    @foreach ($jadwal_hari as $hari)
                                        <td style="text-align: center; vertical-align: middle;">
                                            @if ($data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari] ?? false)
                                                <table align="center" class="table table-bordered" border="0"
                                                    cellspacing="0" cellpadding="0">
                                                    <tr>
                                                        <td width="85px"
                                                            style="padding:  0 10px 0 10px ; background-color:#{{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari . 'color'] }}; font-weight: bold;text-align:left;vertical-align: middle">
                                                            <span style="float:right;">
                                                                @if ($data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari . 'primary'] == '1')
                                                                    <button
                                                                        type="button"class="btn bg-blue waves-effect passingID2"
                                                                        data-toggle="modal"
                                                                        data-id-jadwal-kelas-mp='{{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['id_jadwal_kelas_mp'] }}'
                                                                        {{-- data-id-jadwal-hari = '{{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['id_jadwal_hari'] }} '  --}}
                                                                        data-id-jadwal-jam='{{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['id_jadwal_jam'] }}'
                                                                        data-id-jadwal-jam-selesai='{{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['id_jadwal_jam_selesai'] }}'
                                                                        data-id-jadwal-jam-selesai='{{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['id_jadwal_jam_selesai'] }}'
                                                                        data-id-jadwal-jam-selesai='{{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['id_jadwal_jam_selesai'] }}'
                                                                        data-id-guru='{{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['id_guru'] }}'
                                                                        data-hari=" {{ $hari->id_jadwal_hari }}"
                                                                        data-id-pengampu-mp='{{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['id_pengampu_mp'] }}'
                                                                        id="edit" style="padding: 0 4px 0 4px ">
                                                                        <i class="material-icons">edit</i>
                                                                    </button>
                                                            </span>
                                                        @else
                                            @endif

                                        </td>
                                        <td
                                            style="background-color:#{{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari . 'color'] }}">
                                            @if ($data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari . 'primary'] == '1')
                                                <button type="button"class="btn bg-red waves-effect delete-record"
                                                    data-id="{{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['id_kelas_mp'] }}"
                                                    style="padding: 0 4px 0 4px ">
                                                    <i class="material-icons">close</i></button>
                                            @else
                                                <br>
                                            @endif
                                        </td>
                                </tr>
                                <tr>
                                    <td style="text-align:right; background-color:white; font-size:9px">
                                        <span style="font-weight: bold; ">

                                            {{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['nm_mata_pelajaran'] }}
                                            ({{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['kd_mata_pelajaran'] }})
                                        </span><br>
                                        {{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['gelar_depan'] ?? '' }}
                                        {{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['nm_pengguna'] ?? '' }}
                                        {{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['gelar_belakang'] ?? '' }}

                                    </td>
                                    <td style="font-size:9px;background-color:white">
                                        @if (!empty($data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['path_foto_pengguna']))
                                            <img src="https://diakad.sgp1.digitaloceanspaces.com/{{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['path_foto_pengguna'] }}"
                                                alt="img" height="50" />
                                        @else
                                            <img src="https://ui-avatars.com/api/?size=100&name={{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['nm_pengguna'] ?? '' }}"
                                                height="50" />
                                        @endif
                                    </td>
                                </tr>
                    </table>
                @else
                    <button type="button" class="btn bg-green waves-effect passingID" data-toggle="modal"
                        data-jam="{{ $r->jam_ke }}" data-hari=" {{ $hari->id_jadwal_hari }}" id="open">
                        <i class="material-icons ">add</i>
                    </button>
                    @endif
                    </td>
                    @endforeach
                    </tr>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>



<input type="hidden" name="id_semester" value="{{ $semester->id_semester }}">
<input type="hidden" name="id_kelas" value="{{ $kelas->id_kelas }}">
<input type="hidden" name="id_hari" id="hari" value="">
<input type="hidden" name="id_jadwal_kelas_mp" id="id_jadwal_kelas_mp" value="">
<input type="hidden" name="id_pengampu_mp" id="id_pengampu_mp" value="">


<!-- Modal -->
<div class="modal" tabindex="-1" role="dialog" id="myModal">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">

                <h5 class="modal-title">Input Jadwal Kelas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="Name">Jam Masuk : <span style="color: red">(Otomatis)</span> </label>
                        <select class="form-control show-tick" name="jamMasuk" id="jamMasuk">

                            @foreach ($jadwal_jam as $j)
                                <option value="{{ $j->id_jadwal_jam }}" id="{{ $j->jam_ke }}">
                                    {{ $j->nm_jadwal_jam }}
                                    ({{ $j->jam_mulai }},{{ $j->menit_mulai }} -
                                    {{ $j->jam_selesai }},{{ $j->menit_selesai }})
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="row">

                    <div class="form-group col-md-4">
                        <label for="Club">Jam Selesai :</label>
                        <select class="form-control show-tick" name="jamSelesai">
                            <option value="" disabled selected>Pilih Jam
                            </option>
                            @foreach ($jadwal_jam as $j)
                                <option value="{{ $j->id_jadwal_jam }}">{{ $j->nm_jadwal_jam }}
                                    ({{ $j->jam_mulai }},{{ $j->menit_mulai }} -
                                    {{ $j->jam_selesai }},{{ $j->menit_selesai }})
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="test">Jurusan : <span style="color: red">(Otomatis jika ada)</span></label>
                        <select class="form-control show-tick" onchange="changeJurusan(this)" name="jurusan">
                            <option value="" selected>Semua</option>
                            @foreach ($list_jurusan as $jurusan)
                                <option @if ($jurusan->id_jurusan == $id_jurusan) selected @endif
                                    value="{{ $jurusan->id_jurusan }}">
                                    {{ $jurusan->nm_jurusan }}
                                    {{-- ({{ $mapel->kd_mata_pelajaran }}) --}}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="test">Jenis Mapel : <span style="color: red">(Opsional)</span></label>
                        <select class="form-control show-tick" onchange="changeJurusan(this)" name="jenismapel">
                            <option value="" selected>Semua</option>
                            @foreach ($list_jenis_mata_pelajaran as $jenis_mata_pelajaran)
                                <option value="{{ $jenis_mata_pelajaran->id_jenis_mata_pelajaran }}">
                                    {{ $jenis_mata_pelajaran->nm_jenis_mata_pelajaran }}
                                    ({{ $jenis_mata_pelajaran->kode_jenis_mata_pelajaran }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="test">Mapel :</label>
                        <select class="form-control show-tick" name="mapel">
                            <option value="" disabled selected>Pilih Mapel</option>
                            @foreach ($data_jenis_mata_pelajaran as $jenis_mata_pelajaran)
                                <optgroup label="{{ $jenis_mata_pelajaran->nm_jenis_mata_pelajaran }}"
                                    style="color: red">
                                    @foreach ($jenis_mata_pelajaran->mapel as $mapel)
                                        <option value="{{ $mapel->id_mata_pelajaran }}" style="color: black">
                                            {{ $mapel->nm_mata_pelajaran }}
                                            ({{ $mapel->kd_mata_pelajaran }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="Goal Score">Guru :</label>
                        <select class="form-control show-tick" name="guru">
                            <option value="" disabled selected>Pilih Guru
                            </option>
                            @foreach ($list_guru as $guru)
                                <option value="{{ $guru->id_guru }}">
                                    {{ $guru->pengguna->gelar_depan ?? '' }}
                                    {{ $guru->pengguna->nm_pengguna ?? '' }}
                                    {{ $guru->pengguna->gelar_belakang ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="Goal Score">Ruangan :</label>
                        <select class="form-control show-tick" name="ruangan">
                            @if ($ruangan = $allruangan->where('id_kelas', $kelas->id_kelas)->first())
                                <option value="{{ $ruangan->id_ruangan }}" selected>{{ $ruangan->nm_ruangan }}
                                </option>
                            @else
                                <option disabled selected>Otomatis terpilih jika sudah set ruang kelas di role Sarpras
                                </option>
                                @foreach ($allruangan as $ruangan)
                                    <option value="{{ $ruangan->id_ruangan }}">{{ $ruangan->nm_ruangan }} </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success  waves-effect" onclick="save()">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal 2 untuk edit --}}
<div class="modal" tabindex="-1" role="dialog" id="myModal2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">

                <h5 class="modal-title">Edit Jadwal Kelas </h5>
                {{-- <input type="text" name="id_semester" value="{{ $semester->id_semester }}" disabled> --}}
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- <input type="hidden" name="id_semester" value="{{ $semester->id_semester }}">
            <input type="hidden" name="id_kelas" value="{{ $kelas->id_kelas }}">
            <input type="hidden" name="id_hari" id="hari" value=""> --}}


            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="Name">Jam Masuk : <span style="color: red">(Otomatis)</span> </label>
                        <select class="form-control show-tick" name="jamMasukEdit" id="jamMasukEdit">

                            @foreach ($jadwal_jam as $j)
                                <option value="{{ $j->id_jadwal_jam }}" id="{{ $j->jam_ke }}">
                                    {{ $j->nm_jadwal_jam }}
                                    ({{ $j->jam_mulai }},{{ $j->menit_mulai }} -
                                    {{ $j->jam_selesai }},{{ $j->menit_selesai }})
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="row">

                    <div class="form-group col-md-4">
                        <label for="Club">Jam Selesai :</label>
                        <select class="form-control show-tick" name="jamSelesaiEdit" id="jamSelesaiEdit">
                            <option value="" disabled selected>Pilih Jam
                            </option>
                            @foreach ($jadwal_jam as $j)
                                <option value="{{ $j->id_jadwal_jam }}">{{ $j->nm_jadwal_jam }}
                                    ({{ $j->jam_mulai }},{{ $j->menit_mulai }} -
                                    {{ $j->jam_selesai }},{{ $j->menit_selesai }})
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="Goal Score">Guru :</label>
                        <select class="form-control show-tick" name="guruEdit" id="guruEdit">
                            <option value="" disabled selected>Pilih Guru
                            </option>
                            @foreach ($list_guru as $guru)
                                <option value="{{ $guru->id_guru }}">
                                    {{ $guru->pengguna->gelar_depan ?? '' }}
                                    {{ $guru->pengguna->nm_pengguna ?? '' }}
                                    {{ $guru->pengguna->gelar_belakang ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="Goal Score">Ruangan :</label>
                        <select class="form-control show-tick" name="ruanganEdit">
                            @if ($ruangan = $allruangan->where('id_kelas', $kelas->id_kelas)->first())
                                <option value="{{ $ruangan->id_ruangan }}" selected>{{ $ruangan->nm_ruangan }}
                                </option>
                            @else
                                <option disabled selected>Otomatis terpilih jika sudah set ruang kelas di role Sarpras
                                </option>
                                @foreach ($allruangan as $ruangan)
                                    <option value="{{ $ruangan->id_ruangan }}">{{ $ruangan->nm_ruangan }} </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success  waves-effect" onclick="saveEdit()">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>



{{-- Modal 3 untuk penangung jawab --}}


<div class="modal" tabindex="-1" role="dialog" id="myModal3">


    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">

                <h5 class="modal-title">Input Penangung Jawab</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- <input type="hidden" name="id_semester" value="{{ $semester->id_semester }}">
            <input type="hidden" name="id_kelas" value="{{ $kelas->id_kelas }}">
            <input type="hidden" name="id_hari" id="hari" value="{{ $kelas->id_kelas }}"> --}}

            <div class="modal-body">

                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="Goal Score">Penanggung jawab Mata Ajar : <span
                                style="color: red">(Opsional)</span>
                        </label>
                        <select class="form-control show-tick" name="penangungJawab">
                            <option value="" selected>Pilih Guru
                            </option>
                            @foreach ($list_guru as $guru)
                                <option value="{{ $guru->id_guru }}">{{ $guru->pengguna->nm_pengguna ?? '' }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="Goal Score">Tim PJMA 1 : <span style="color: red">(Opsional)</span></label>
                        <select class="form-control show-tick" name="pjma1">
                            <option value="" selected>Pilih Guru
                            </option>
                            @foreach ($list_guru as $guru)
                                <option value="{{ $guru->id_guru }}">{{ $guru->pengguna->nm_pengguna ?? '' }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="Goal Score">Tim PJMA 2 : <span style="color: red">(Opsional)</span></label>
                        <select class="form-control show-tick" name="pjma2">
                            <option value="" selected>Pilih Guru
                            </option>
                            @foreach ($list_guru as $guru)
                                <option value="{{ $guru->id_guru }}">{{ $guru->pengguna->nm_pengguna ?? '' }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success  waves-effect" onclick="save()">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>




@include('scriptjs')


<script>
    $(".passingID").click(function() {
        var jam = parseInt($(this).attr('data-jam'));
        var hari = parseInt($(this).attr('data-hari'));
        // alert(jam);
        const $select = document.querySelector('#jamMasuk');
        const $option = document.getElementById(jam);
        $select.value = $option.value;
        $("#hari").val(hari);

        $('#myModal').modal('show');
    });

    $(".passingID2").click(function() {
        var jadwal_kelas_kelas_mp = parseInt($(this).attr('data-id-jadwal-kelas-mp'));
        var jadwal_jam = $(this).attr('data-id-jadwal-jam');
        var jadwal_jam_selesai = $(this).attr('data-id-jadwal-jam-selesai');
        var guru = $(this).attr('data-id-guru');
        const $select1 = document.querySelector('#jamMasukEdit');
        $select1.value = jadwal_jam;

        const $select2 = document.querySelector('#jamSelesaiEdit');
        $select2.value = jadwal_jam_selesai;
        const $select3 = document.querySelector('#guruEdit');
        $select3.value = guru;
        var hari = parseInt($(this).attr('data-hari'));
        $("#hari").val(hari);

        $('#myModal2').modal('show');

        var id_jadwal_kelas_mp = $(this).attr('data-id-jadwal-kelas-mp');
        // alert(id_jadwal_kelas_mp)
        $("#id_jadwal_kelas_mp").val(id_jadwal_kelas_mp);


        var id_pengampu_mp = $(this).attr('data-id-pengampu-mp');
        $("#id_pengampu_mp").val(id_pengampu_mp);
    });

    $(".passingID3").click(function() {
        // var jam = parseInt($(this).attr('data-jam'));
        // var hari = parseInt($(this).attr('data-hari'));
        // alert(jam);
        // const $select = document.querySelector('#jamMasuk');
        // const $option = document.getElementById(jam);
        // $select.value = $option.value;
        // $("#hari").val(hari);

        $('#myModal3').modal('show');
    });

    function save() {
        $('#myModal').modal('hide');
        $('button').attr('disabled', 'disabled');
        // var pengguna = [];

        // $("input:checkbox[name=id_pengguna]:checked").each(function(){
        //     pengguna.push($(this).val());
        // });

        // untuk NON-AKTIFKAN set jadwal kelas //
        var isJadwalTutup = false;

        if (isJadwalTutup) {
            vex.dialog.alert('Set jadwal kelas sudah ditutup');
            $('button').removeAttr('disabled');
            return;
        }

        $.ajax({
            url: base_url +
                '/{{ Request::segment(1) }}/{{ Request::segment(2) }}/action-set-jadwal-kelas/add/0',
            type: 'POST',
            data: {

                jamMasuk: $('select[name=jamMasuk]').val(),
                jamSelesai: $('select[name=jamSelesai]').val(),
                mapel: $('select[name=mapel]').val(),
                guru: $('select[name=guru]').val(),
                ruangan: $('select[name=ruangan]').val(),
                // penangungJawab: $('select[name=penangungJawab]').val(),
                // pjma1: $('select[name=pjma1]').val(),
                // pjma2: $('select[name=pjma2]').val(),
                id_semester: $('input[name=id_semester]').val(),
                id_kelas: $('input[name=id_kelas]').val(),
                id_hari: $('input[name=id_hari]').val()
            },
            success: function(response) {
                if (response.status_code == 200) {
                    vex.dialog.alert(response.message);
                } else if (response.status_code == 201) {
                    vex.dialog.alert(response.message);
                    window.location.href = response.link;
                } else if (response.status_code == 202) {
                    vex.dialog.alert(response.message);
                    setTimeout(function() {
                        loadURI(response.path);
                    }, 2000);

                } else if (response.status_code == 203) {
                    vex.dialog.alert(response.message);
                    primary_table.ajax.reload(null, false);
                } else if (response.status_code == 204) {
                    loadURI(response.path);
                } else if (response.status_code == 300) {
                    vex.dialog.alert(response.message);
                }
            },
            complete: function() {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }

    function saveEdit() {
        $('#myModal2').modal('hide');
        $('button').attr('disabled', 'disabled');
        // var pengguna = [];
        // $id_jadwal_kelas_mp= $('input[name=id_jadwal_kelas_mp]').val();
        // alert($('input[name=id_jadwal_kelas_mp]').val())
        // $("input:checkbox[name=id_pengguna]:checked").each(function(){
        //     pengguna.push($(this).val());
        // });
        $.ajax({
            url: base_url +
                '/{{ Request::segment(1) }}/{{ Request::segment(2) }}/action-set-jadwal-kelas/edit/' + $(
                    'input[name=id_jadwal_kelas_mp]').val(),
            type: 'POST',
            data: {

                jamMasuk: $('select[name=jamMasukEdit]').val(),
                jamSelesai: $('select[name=jamSelesaiEdit]').val(),
                // mapel: $('select[name=mapelEdit]').val(),
                guru: $('select[name=guruEdit]').val(),
                ruangan: $('select[name=ruanganEdit]').val(),
                // penangungJawab: $('select[name=penangungJawab]').val(),
                // pjma1: $('select[name=pjma1]').val(),
                // pjma2: $('select[name=pjma2]').val(),
                id_pengampu_mp: $('input[name=id_pengampu_mp]').val(),
                id_semester: $('input[name=id_semester]').val(),
                id_kelas: $('input[name=id_kelas]').val(),
                id_hari: $('input[name=id_hari]').val()
            },
            success: function(response) {
                if (response.status_code == 200) {
                    vex.dialog.alert(response.message);
                } else if (response.status_code == 201) {
                    vex.dialog.alert(response.message);
                    window.location.href = response.link;
                } else if (response.status_code == 202) {
                    vex.dialog.alert(response.message);
                    setTimeout(function() {
                        loadURI(response.path);
                    }, 2000);

                } else if (response.status_code == 203) {
                    vex.dialog.alert(response.message);
                    primary_table.ajax.reload(null, false);
                } else if (response.status_code == 204) {
                    loadURI(response.path);
                } else if (response.status_code == 300) {
                    vex.dialog.alert(response.message);
                }
            },
            complete: function() {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }


    $(".delete-record").click(function() {
        var token = $("meta[name='csrf-token']").attr("content");
        var id = $(this).data("id");
        // alert(id);
        swal({
                title: "Are you sure?",
                showCancelButton: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    $('.delete-record').attr("disabled", true);
                    //swall
                    $.ajax({
                        // alert(id);
                        url: `{{ Request::segment(1) }}/{{ Request::segment(2) }}/action-set-jadwal-kelas/delete/${id}`,
                        type: "post",

                        data: {
                            _token: token,
                            id_semester: $('input[name=id_semester]').val(),
                            id_kelas: $('input[name=id_kelas]').val(),
                        },

                        success: function(response) {
                            if (response.status_code == 200) {
                                vex.dialog.alert(response.message);
                            } else if (response.status_code == 201) {
                                vex.dialog.alert(response.message);
                                window.location.href = response.link;
                            } else if (response.status_code == 202) {
                                vex.dialog.alert(response.message);
                                setTimeout(function() {
                                    loadURI(response.path);
                                }, 2000);

                            } else if (response.status_code == 203) {
                                vex.dialog.alert(response.message);
                                primary_table.ajax.reload(null, false);
                            } else if (response.status_code == 204) {
                                loadURI(response.path);
                            } else if (response.status_code == 300) {
                                vex.dialog.alert(response.message);
                            }
                        }
                    });
                    $('.delete-record').attr("disabled", false);
                }
            }
        );
    });


    function changeJurusan(el) {
        $('select[name=mapel]').empty();
        $.ajax({
            url: '{{ url(Request::segment(1) . '/' . Request::segment(2) . '/getMataPelajaran') }}',
            type: 'POST',
            data: {
                jurusan: $('select[name=jurusan]').val(),
                jenismapel: $('select[name=jenismapel]').val(),
            },
            success: function(result) {
                $('select[name=mapel]').html('');
                var html = '<option value="" >-- Pilih Mata Pelajaran --</option>';
                $.each(result['mapel'], function(key, item) {
                    html += '<option value="' + item.id_mata_pelajaran + '">' + item
                        .nm_mata_pelajaran + ' (' + item.kd_mata_pelajaran +
                        ')</option>'
                });
                $('select[name=mapel]').html(html);
            }
        });
    }
</script>

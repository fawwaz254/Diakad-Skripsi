<div class="container-fluid">
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
                                @if ($key % 2 == 1)
                                    <tr style="background: #98d1d1">
                                        <td style="text-align: center; vertical-align: middle;">{{ $key + 1 }}
                                        </td>
                                    @else
                                    <tr style="background: #badbdb">
                                        <td style="text-align: center; vertical-align: middle;">{{ $key + 1 }}
                                        </td>
                                @endif
                                @foreach ($jadwal_hari as $hari)
                                    <td style="text-align: center; vertical-align: middle;">
                                        @if ($data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari] ?? false)
                                            <table align="center" class="table table-bordered" border="0"
                                                cellspacing="0" cellpadding="0">
                                                <tr>

                                                    <td
                                                        style="padding:  0 10px 0 10px ; background-color:#{{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari . 'color'] }}; font-weight: bold;text-align:left;vertical-align: middle">
                                                        <span
                                                            style=" text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;">
                                                            {{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['kd_mata_pelajaran'] }}
                                                        </span>
                                                        {{-- {{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['jam_mulai'] }}.
                                                        {{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['menit_mulai'] }}
                                                        -
                                                        {{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['jam_selesai'] }}.
                                                        {{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['menit_selesai'] }} --}}
                                                        <span style="float:right;">
                                                            @if ($data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari . 'primary'] == '1')
                                                                <button type="button"class="btn bg-blue waves-effect"
                                                                    style="padding: 0 4px 0 4px ">
                                                                    <i class="material-icons">edit</i></button>
                                                        </span>
                                                    @else
                                        @endif

                                    </td>
                                    <td
                                        style="background-color:#{{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari . 'color'] }}">
                                        @if ($data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari . 'primary'] == '1')
                                            <button type="button"class="btn bg-red waves-effect"
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

                                                {{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['nm_mata_pelajaran'] }}</span><br>
                                            {{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['gelar_depan'] }}
                                            {{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['nm_pengguna'] }}
                                            {{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['gelar_belakang'] }}


                                            {{-- <br>
                                                        {{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['jam_mulai'] }}.
                                                        {{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['menit_mulai'] }}
                                                        -
                                                        {{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['jam_selesai'] }}.
                                                        {{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['menit_selesai'] }} --}}


                                        </td>
                                        <td style="font-size:9px;background-color:white">
                                            @if (!empty($data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['path_foto_pengguna']))
                                                <img src="https://diakad.sgp1.digitaloceanspaces.com/{{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['path_foto_pengguna'] }}"
                                                    alt="img" height="50" />
                                            @else
                                                <img src="https://ui-avatars.com/api/?size=100&name={{ $data_kelas_mp[$r->jam_ke . $hari->id_jadwal_hari]['nm_pengguna'] }}"
                                                    height="50" />
                                            @endif
                                        </td>
                                    </tr>
                    </table>
                @else
                    {{-- <button class="btn btn-warning btn-detail open_modal" value="test">Edit</button> --}}


                    {{-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" --}}
                    {{-- data-target="#myModal" id="open">Open Modal</button> --}}
                    <button type="button" class="btn bg-green waves-effect passingID" data-toggle="modal"
                        data-jam="{{ $r->jam_ke}}}" data-hari=" {{  $hari->id_jadwal_hari }}" id="open">
                        <i class="material-icons ">add</i>
                    </button>
                    {{-- {{ $r->jam_ke.','.$hari->id_jadwal_hari  }} --}}

                    {{-- {{ $r->id_jadwal_jam . $hari->id_jadwal_hari }} --}}
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
            <input type="hidden" name="id_semester" value="{{ $semester->id_semester }}">
            <input type="hidden" name="id_kelas" value="{{ $kelas->id_kelas }}">
            <input type="hidden" name="id_hari" id="hari" value="{{ $kelas->id_kelas }}">

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
                        <label for="test">Mapel :</label>
                        <select class="form-control show-tick" name="mapel">
                            <option value="" disabled selected>Pilih Mapel</option>
                            @foreach ($mapel as $m)
                                <option value="{{ $m->id_mata_pelajaran }}">{{ $m->nm_mata_pelajaran }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- <div class="row">
                        <div class="form-group col-md-4">
                            <label for="test">Ruangan : <span style="color:red">(Otomastis jika ruang sudah diatur dengan kelas)</span></label>
                            <select class="form-control show-tick" name="id">
                                <option value="" disabled selected>Pilih Ruangan</option>
                                @foreach ($ruangan as $r)
                                    <option value="{{ $r->id_ruangan }}" @if ($r->id_kelas == $kelas->id_kelas)  selected @endif>{{ $r->nm_ruangan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="Goal Score">Guru :</label>
                        <select class="form-control show-tick" name="guru">
                            <option value="" disabled selected>Pilih Guru
                            </option>
                            @foreach ($list_guru as $guru)
                                <option value="{{ $guru->id_guru }}">{{ $guru->pengguna->nm_pengguna }}
                                </option>
                            @endforeach
                            {{-- <input type="text" class="form-control" name="club" id="club"> --}}
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="Goal Score">Penanggung jawab Mata Ajar : <span
                                style="color: red">(Opsional)</span>
                        </label>
                        <select class="form-control show-tick" name="penangungJawab">
                            <option value="" disabled selected>Pilih Guru
                            </option>
                            @foreach ($list_guru as $guru)
                                <option value="{{ $guru->id_guru }}">{{ $guru->pengguna->nm_pengguna }}
                                </option>
                            @endforeach
                            {{-- <input type="text" class="form-control" name="club" id="club"> --}}
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="Goal Score">Tim PJMA 1 : <span style="color: red">(Opsional)</span></label>
                        <select class="form-control show-tick" name="pjma1">
                            <option value="" disabled selected>Pilih Guru
                            </option>
                            @foreach ($list_guru as $guru)
                                <option value="{{ $guru->id_guru }}">{{ $guru->pengguna->nm_pengguna }}
                                </option>
                            @endforeach
                            {{-- <input type="text" class="form-control" name="club" id="club"> --}}
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4">
                        <label for="Goal Score">Tim PJMA 2 : <span style="color: red">(Opsional)</span></label>
                        <select class="form-control show-tick" name="pjma2">
                            <option value="" disabled selected>Pilih Guru
                            </option>
                            @foreach ($list_guru as $guru)
                                <option value="{{ $guru->id_guru }}">{{ $guru->pengguna->nm_pengguna }}
                                </option>
                            @endforeach
                            {{-- <input type="text" class="form-control" name="club" id="club"> --}}
                        </select>
                    </div>
                </div>
                {{-- </div> --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    {{-- <button class="btn btn-block bg-success waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button> --}}
                    {{-- <button type="submit" class="btn btn-block waves-effect" onclick="save()">SAVE</button> --}}
                    <button type="submit" class="btn btn-success  waves-effect" onclick="save()">Submit</button>
                </div>
            </div>
        </div>
    </div>
    </form>


    @include('scriptjs')


    <script>
        $(".passingID").click(function() {
            var jam = parseInt($(this).attr('data-jam'));
            var hari = parseInt($(this).attr('data-hari'));
            // alert(jam);
            const $select = document.querySelector('#jamMasuk');
            const $option = document.getElementById(jam);
            $select.value = $option.value;
            $("#hari").val( hari );

            $('#myModal').modal('show');
        });

        function save() {
            $('#myModal').modal('hide');
            $('button').attr('disabled', 'disabled');
            // var pengguna = [];

            // $("input:checkbox[name=id_pengguna]:checked").each(function(){
            //     pengguna.push($(this).val());
            // });
            $.ajax({
                url: base_url +
                    '/{{ Request::segment(1) }}/{{ Request::segment(2) }}/action-set-jadwal-kelas/add/0',
                type: 'POST',
                data: {

                    jamMasuk: $('select[name=jamMasuk]').val(),
                    jamSelesai: $('select[name=jamSelesai]').val(),
                    mapel: $('select[name=mapel]').val(),
                    guru: $('select[name=guru]').val(),
                    penangungJawab: $('select[name=penangungJawab]').val(),
                    pjma1: $('select[name=pjma1]').val(),
                    pjma2: $('select[name=pjma2]').val(),
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
                        setTimeout(function(){
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

        // jQuery(document).ready(function() {
        //     jQuery('#ajaxSubmit').click(function(e) {
        //         e.preventDefault();
        //         $.ajaxSetup({
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        //             }
        //         });
        //         jQuery.ajax({
        //             url: "{{ url('/chempionleague') }}",
        //             method: 'post',
        //             data: {
        //                 name: jQuery('#name').val(),
        //                 club: jQuery('#club').val(),
        //                 country: jQuery('#country').val(),
        //                 score: jQuery('#score').val(),
        //             },
        //             success: function(result) {
        //                 if (result.errors) {
        //                     jQuery('.alert-danger').html('');

        //                     jQuery.each(result.errors, function(key, value) {
        //                         jQuery('.alert-danger').show();
        //                         jQuery('.alert-danger').append('<li>' + value +
        //                             '</li>');
        //                     });
        //                 } else {
        //                     jQuery('.alert-danger').hide();
        //                     $('#open').hide();
        //                     $('#myModal').modal('hide');
        //                 }
        //             }
        //         });
        //     });
        // });
    </script>


    {{-- <script type="text/javascript">

$(document).on('click','.open_modal',function(){
        // var url = "domain.com/yoururl";
        // var tour_id= $(this).val();
        // $.get(url + '/' + tour_id, function (data) {
        //     //success data
        //     console.log(data);
        //     $('#tour_id').val(data.id);
        //     $('#name').val(data.name);
        //     $('#details').val(data.details);
        //     $('#btn-save').val("update");
            $('#myModal').modal('show');
        }) 
    // });

</script>  --}}

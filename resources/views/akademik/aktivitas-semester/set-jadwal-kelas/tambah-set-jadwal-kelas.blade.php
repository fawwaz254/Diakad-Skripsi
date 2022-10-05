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
                                                        <span style=" text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;;">
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
                                                            <i class="material-icons">edit</i></button></span>
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
                                            <button type="button" class="btn bg-green waves-effect" data-toggle="modal"
                                                data-target="#myModal" id="open">
                                                <i class="material-icons">add</i>
                                            </button>
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





<form method="post" action="{{ url('chempionleague') }}" id="form">
    @csrf
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
                    {{-- <div class="row">
                        <div class="form-group col-md-4">
                            <label for="Name">Jam Masuk:</label>
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                    </div> --}}
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="Club">Jam Selesai:</label>
                            <select class="form-control show-tick" name="id_semester">
                                <option value="" disabled selected>Pilih Jam
                                </option>
                                @foreach ($jadwal_jam as $j)
                                    <option value="{{ $j->id_jadwal_jam }}">{{ $j->nm_jadwal_jam }}
                                        ({{ $j->jam_mulai }},{{ $j->menit_mulai }} -
                                        {{ $j->jam_selesai }},{{ $j->menit_selesai }})
                                    </option>
                                @endforeach
                                {{-- <input type="text" class="form-control" name="club" id="club"> --}}
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="test">Mapel:</label>
                            <select class="form-control show-tick" name="id">
                                <option value="" disabled selected>Pilih Mapel</option>
                                @foreach ($mapel as $m)
                                    <option value="{{ $m->id_mata_pelajaran }}">{{ $m->nm_mata_pelajaran }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="Goal Score">Guru:</label>
                            <select class="form-control show-tick" name="id_semester">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-success" id="ajaxSubmit">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</form>
{{-- 
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
       <div class="modal-content">
         <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title" id="myModalLabel">Tour</h4>
        </div>
        <div class="modal-body">
        <form id="frmProducts" name="frmProducts" class="form-horizontal" novalidate="">
            <div class="form-group error">
             <label for="inputName" class="col-sm-3 control-label">Jam Mulai</label>
               <div class="col-sm-9">
                <input type="text" class="form-control has-error" id="name" name="name" placeholder="Product Name" value="">
               </div>
               </div>
             <div class="form-group">
             <label for="inputDetail" class="col-sm-3 control-label">Jam Selesai</label>
                <div class="col-sm-9">
                <input type="text" class="form-control" id="details" name="details" placeholder="details" value="">
                </div>
                <div class="form-group">
            <label for="inputDetail" class="col-sm-3 control-label">Mata Pelajaran</label>
            <div class="col-sm-9">
            <input type="text" class="form-control" id="details" name="details" placeholder="details" value="">
            </div>
                </div>
                <div class="form-group">
            <label for="inputDetail" class="col-sm-3 control-label">Guru</label>
            <div class="col-sm-9">
            <input type="text" class="form-control" id="details" name="details" placeholder="details" value="">
            </div>
                </div>
        </div></div>
        </form>
        </div>
        <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="btn-save" value="add">Simpan</button>
        <input type="hidden" id="product_id" name="tour_id" value="0">
        </div>
    </div>
  </div>
</div>
</div> --}}
@include('scriptjs')

{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.3/css/bootstrap.css" rel="stylesheet">   --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta.3/js/bootstrap.min.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.0/jquery.js"></script>  --}}

{{-- <script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script> --}}
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
    integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
</script>
<script>
    jQuery(document).ready(function() {
        jQuery('#ajaxSubmit').click(function(e) {
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            jQuery.ajax({
                url: "{{ url('/chempionleague') }}",
                method: 'post',
                data: {
                    name: jQuery('#name').val(),
                    club: jQuery('#club').val(),
                    country: jQuery('#country').val(),
                    score: jQuery('#score').val(),
                },
                success: function(result) {
                    if (result.errors) {
                        jQuery('.alert-danger').html('');

                        jQuery.each(result.errors, function(key, value) {
                            jQuery('.alert-danger').show();
                            jQuery('.alert-danger').append('<li>' + value +
                                '</li>');
                        });
                    } else {
                        jQuery('.alert-danger').hide();
                        $('#open').hide();
                        $('#myModal').modal('hide');
                    }
                }
            });
        });
    });
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

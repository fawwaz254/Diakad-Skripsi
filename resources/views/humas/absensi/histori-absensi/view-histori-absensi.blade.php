<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">

                <div class="header">
                    <h2>Filter Data</h2>
                </div>

                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">Pilih Guru atau Tenaga Pendidik </h2>
                            <select class="form-control show-tick" id="guru-or-tendik" required="">
                                <option selected value="0">Pilih</option>
                                <option value="guru">Guru</option>
                                <option value="tendik">Tenaga Pendidik</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="id_pengguna">
                    
                    <div class="row clearfix" id="guru-container">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Pilih Guru
                            </h2>
                            <select class="form-control show-tick" id="id_guru" required="">
                                @foreach($guru as $r)
                                <option value="{{$r->id_pengguna}}" {{$r->id_pengguna == $id_pengguna ? 'selected' :
                                    ''}}>{{$r->nm_pengguna}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row clearfix" id="tendik-container">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Pilih Tenaga Pendidik 
                            </h2>
                            <select class="form-control show-tick" id="id_tendik" required="">
                                @foreach($tendik as $r)
                                <option value="{{$r->id_pengguna}}" {{$r->id_pengguna == $id_pengguna ? 'selected' :
                                    ''}}>{{$r->nm_pengguna}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> 
                    <div class="row clearfix">

                        <div class="col-md-5">
                            <label>Start Date</label>
                            <input type="date" class="form-control" value="{{$start_date}}" name="start_date"
                                aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-5">
                            <label>End Date</label>
                            <input type="date" class="form-control" value="{{$end_date}}" name="end_date"
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
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">

                <div class="header">
                    <h2>Histori Absensi</h2>
                </div>

                <div class="body">

                    <table class="table table-bordered">
                        <thead style="background:#9C27B0;color:white">
                            <tr>
                                <th style="text-align: center;">Tanggal</th>
                                <th>Hari</th>
                                <th>Check In</th>
                                <th>Check Out</th>
                                <th>Status</th>
                                <th>Notes</th>
                        
                                <th style="text-align: center;">Action</th>
                              
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hasil as $key => $r)
                            @if($key%2==1)
                            <tr style="background: #DDA0DD">
                                @else
                            <tr>
                                @endif
                                <td style="text-align: center;">{{$r['tanggal']}}</td>
                                <td>{{$r['hari']}}</td>
                                <td>{{$r['check_in']}}</td>
                                <td>{{$r['check_out']}}</td>
                                <td>{{$r['status']}}</td>
                                <td>{{$r['notes']}}</td>
                                <td style="text-align: center;display:flex;justify-content:center">
                                    @if ($r['id_presensi_pengguna'] =='')
                                    <button type="button" class="btn bg-teal waves-effect">
                                        <a
                                            href="/humas#absensi/histori-absensi/{{$id_pengguna}}/{{$r['date']}}/{{$start_date}}/{{$end_date}}/add">
                                            <i class="material-icons">edit</i>
                                        </a>
                                    </button>
                                    @else
                                    <button type="button" class="btn bg-teal waves-effect">
                                        <a
                                            href="/humas#absensi/histori-absensi/{{$r['id_presensi_pengguna']}}/{{$start_date}}/{{$end_date}}/edit">
                                            <i class="material-icons">edit</i>
                                        </a>
                                    </button>
                                        <button data-id="{{  $r['id_presensi_pengguna'] }}" class="btn bg-red waves-effect delete-record">
                                            <i class="material-icons">delete</i>
                                        </button>

                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>

<script type="text/javascript">
    $('#id_pengguna').val($('#id_guru').val())
    function filterAction(){
        loadURI('{{Request::segment(2)}}/{{Request::segment(3)}}/' + $('#id_pengguna').val()  + '/' + $('input[name=start_date]').val() + '/' + $('input[name=end_date]').val());
    };
    document.getElementById("tendik-container").style.display = "none";
    document.getElementById("guru-container").style.display = "none";
    $('#guru-or-tendik').change(function () {
        if ($(this).val()=="0") {
            document.getElementById("tendik-container").style.display = "none";
            document.getElementById("guru-container").style.display = "none";
        }
        if ($(this).val() == "guru") {
            document.getElementById("tendik-container").style.display = "none";
            document.getElementById("guru-container").style.display = "block";
        } 
        if($(this).val()=="tendik"){
            document.getElementById("guru-container").style.display = "none";
            document.getElementById("tendik-container").style.display = "block";
        }
    })
    $('#id_guru').change(function () {
        $('#id_pengguna').val($('#id_guru').val())
    })

    $('#id_tendik').change(function () {
        $('#id_pengguna').val($('#id_tendik').val())
    })

    $(".delete-record").click(function () {
        var token = $("meta[name='csrf-token']").attr("content");
        var id = $(this).data("id");
        swal(
        { title: "Are you sure?", showCancelButton: true},
        function (isConfirm) {
            if (isConfirm) {
                //swall
                $.ajax({
                    url: ` /humas/absensi/histori-absensi/${id}/delete`,
                    type: "post",

                    data: {
                        _token: token,
                    },

                    success: function () {
                        swal({
                            title: "Delete Succes",
                            text: "data berhasil dihapus",
                            icon: "success",
                        });
                        location.reload();
                    },
                });
            }
            return;
        }
    );
});

</script>
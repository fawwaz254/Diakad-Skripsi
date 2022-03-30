<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            @if($cek_libur)
            <div class="alert alert-danger">
                <strong>Tanggal {{$date}} merupakan hari libur yaitu {{$cek_libur->explanation}}
            </div>
            @endif

            <div class="card">
                <div class="header">
                    <h2>Filter Data</h2>
                </div>

                <div class="body">

                    <div class="row clearfix">

                        <div class="col-md-5">
                            <label>Date</label>
                            <input type="date" class="form-control" value="{{$date}}" name="date"
                                aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-2" style="display: flex; margin-top:27px;" >
                            <div>
                            <button type="button" class="btn bg-purple waves-effect" 
                                onclick="filterAction()">Change Date</button>
                            </div>
                            <div style="margin-left:10px; ">
                                <a href="humas/absensi/histori-absensi/export-laravel/{{ $date }}" target="_blank" class="btn bg-purple waves-effect" >
                                    <i class="material-icons" style="font-size: 15px;">print</i> Print Hari ini</a>
                            </div>
                            <div style="margin-left:10px; ">
                                <a href="humas/absensi/histori-absensi/export-laravel-mount/{{ $date }}" target="_blank" class="btn bg-purple waves-effect" >
                                    <i class="material-icons" style="font-size: 15px;">print</i> Print Bulan ini</a>
                            </div>
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
                                <span class="pull-right"><b>{{$jumlah_hadir}}</b></span>
                            </li>
                            <li>
                                Hadir Terlambat
                                <span class="pull-right"><b>{{$jumlah_telat}}</b></span>
                            </li>
                            <li>
                                Hadir Pulang Lebih Awal
                                <span class="pull-right"><b>{{$jumlah_pulangcepat}}</b></span>
                            </li>
                            <li>
                                Tidak Checkout
                                <span class="pull-right"><b>{{$tidak_checkout}}</b></span>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="dashboard-stat-list">
                            <li>
                                Izin
                                <span class="pull-right"><b>{{$jumlah_izin}}</b></span>
                            </li>
                            <li>
                                Sakit
                                <span class="pull-right"><b>{{$jumlah_sakit}}</b></span>
                            </li>
                            <li>
                                Alpha
                                <span class="pull-right"><b>{{$jumlah_alpha}}</b></span>
                            </li>
                        </ul>
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

              

                <div class="header" >
                    <h2>Histori Absensi   </h2>
                    
                   
                   
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
                            @foreach($hasil as $key => $r)
                            @if($key%2==1)
                            <tr style="background: #DDA0DD">
                                @else
                            <tr>
                                @endif

                                @if($r['shift'])
                                <td style="text-align: center;">{{$loop->iteration}}</td>
                                <td style="text-align: center;">{{$r['nm_pengguna']}}</td>
                                <td>{{$r['status_join_table'] == 1 ? 'Pegawai' : 'Guru' }}</td>
                                <td>{{$r['check_in']}}</td>
                                <td>{{$r['check_out']}}</td>
                                <td>{{$r['status']}}</td>
                          
                                <td style="text-align: center;display:flex;justify-content:center">
                                    @if ($r['id_presensi_pengguna'] =='')
                                    <button type="button" class="btn bg-teal waves-effect" onclick="addAbsensi('{{$r['id_pengguna']}}')">
                                        <i class="material-icons">edit</i>
                                    </button>
                                    @else
                                    <button type="button" class="btn bg-teal waves-effect" onclick="editAbsensi('{{$r['id_presensi_pengguna']}}')">
                                        <i class="material-icons">edit</i>
                                    </button>
                                        <button data-id="{{  $r['id_presensi_pengguna'] }}" style="margin-left:3px;" class="btn bg-red waves-effect delete-record">
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

</div>

<script type="text/javascript">

    function filterAction(){
        loadURI('{{Request::segment(2)}}/{{Request::segment(3)}}/' + $('input[name=date]').val());
    }

    function addAbsensi(id_pengguna){
        window.location='/humas#absensi/histori-absensi/' + id_pengguna + '/' + $('input[name=date]').val()+'/add'
    }

    function editAbsensi(currUser){
        window.location='/humas#absensi/histori-absensi/' + currUser + '/' + $('input[name=date]').val()+'/edit'
    }

  
    $(".delete-record").click(function () {
        var token = $("meta[name='csrf-token']").attr("content");
        var id = $(this).data("id");
        swal(
        { title: "Are you sure?", showCancelButton: true},
        function (isConfirm) {
            if (isConfirm) {
                $('.delete-record').attr("disabled", true);
                //swall
                $.ajax({
                    url: ` /humas/absensi/histori-absensi/${id}/delete`,
                    type: "post",

                    data: {
                        _token: token,
                    },

                    success: function () {
                        swal({
                            title: "Delete Success",
                            text: "data berhasil dihapus",
                            icon: "success",
                        });
                        loadURI('{{Request::segment(2)}}/{{Request::segment(3)}}/' + $('input[name=date]').val());
                    },
                });
            }
            return;
        }
    );
});

</script>
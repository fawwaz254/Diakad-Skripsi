@extends('app')
@section('meta')
<!-- Meta -->
@endsection

@section('content')

<body>
    <section class="section">
        <div class="container-fluid">
            <div class="block-header">
            </div>
            <div class="block-header">
                <h2><a class="btn bg-blue waves-effect" href="{{url('reporting-dashboard')}}"><i class="material-icons">arrow_back</i><span>Kembali</span></a></h2>
            </div>

            <!-- Widgets -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                               Data Penggunaan Diakad Untuk Setiap Role
                            </h2>
                        </div>
                        <div class="body">
                           
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead style="background: #009688 !important;color:white !important">
                                <tr>
                                    <th style="text-align:center;">No</th>
                                    <th>Role</th>
                                    <th style="text-align:center;">Pelatihan</th>
                                    <th style="text-align:center;">Penggunaan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $r)
                                <tr>
                                    <td style="text-align:center;">{{$loop->iteration}}</td>
                                    <td >{{$r['role']}}</td>
                                    <td style="text-align:center;"></td>
                                    <td style="text-align:center;">
                                        @if($r['status'] == 'Belum Digunakan')
                                        <span class="label bg-red detail-catatan" style="cursor:pointer;" onclick="detail_catatan({{$r['id_role']}})">{{$r['status']}}</span></td>
                                        @else
                                        <span class="label bg-green detail-catatan" style="cursor:pointer;" onclick="detail_catatan({{$r['id_role']}})">{{$r['status']}}</span></td>
                                        @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
          
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Widgets -->

        </div>
    </section>
</body>

<!-- Modal Catatan -->
<div class="modal fade" id="modalCatatan" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modalCatatanHeader"></h4>
            </div>
            <div class="modal-body" id="modalCatatanBody">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Catatan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="isi_tabel">
                        
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Catatan -->

@endsection

@section('js')

<script type="text/javascript">

    function detail_catatan(id_role){

        $.ajax({
            url : base_url+'/reporting-dashboard/all-diakad/'+id_role,
            type : 'get',
            dataType : 'json',
            success : function (response){
                $('#modalCatatanHeader').html('Catatan Untuk Role '+response.nm_role);
                $('#isi_tabel').empty();
                $.each(response.catatan,function(i,value){
                    if(value.status == 1){
                        $('#isi_tabel').append(`
                            <tr>
                            <td>`+(i+1)+`</td>
                            <td>`+value.catatan+`</td>
                            <td><i class="material-icons" style="color:green">check</i></td>
                            </tr>
                        `)
                    }
                    else{
                        $('#isi_tabel').append(`
                            <tr>
                            <td>`+(i+1)+`</td>
                            <td>`+value.catatan+`</td>
                            <td><i class="material-icons" style="color:red">clear</i></td>
                            </tr>
                        `)
                    }
                })
                $('#modalCatatan').modal('show');
            },   
            error:function(){
                alert('mohon maaf terjadi kesalahan, silahkan hubungi admin');
            }
        })

    }
</script>

@endsection
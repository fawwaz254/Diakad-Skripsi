@extends('app')
@section('meta')
<!-- Meta -->
@endsection

<style type="text/css">


.progress-bar-animated {
    -webkit-animation: progress-bar-stripes 1s linear infinite;
    animation: progress-bar-stripes 1s linear infinite;
}

.progress .progress-bar {
    line-height: 23px;
    background-color: #dc3545!important;
}

.progress-bar-striped {
    background-image: linear-gradient(
45deg
,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);
    background-size: 1rem 1rem;
    
}

</style>

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
                               Data Penggunaan Diakad Untuk Setiap Role Semester {{$semester_aktif->tahun_ajaran}} {{$semester_aktif->nm_semester}}
                            </h2>
                        </div>
                        <div class="body">
                           
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead style="background: #009688 !important;color:white !important">
                                <tr>
                                    <th style="text-align:center;width: 10%;">No</th>
                                    <th style="width:15%;">Role</th>
                                    <th style="text-align:center;width: 25%;">Status Penggunaan</th>
                                    <th style="text-align:center;">Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $key => $r)
                                <tr>
                                    <td style="text-align:center;">{{$loop->iteration}}</td>
                                    <td >{{$r['role']}}</td>
                                    <td style="text-align:center;">
                                        @if($r['status'] == 'Belum Digunakan')
                                        <span class="label bg-red detail-catatan" style="cursor:pointer;" onclick="detail_catatan({{$r['id_role']}})">{{$r['status']}}</span></td>
                                        @elseif($r['status'] == 'Sudah digunakan namun belum maksimal')
                                        <span class="label bg-orange detail-catatan" style="cursor:pointer;" onclick="detail_catatan({{$r['id_role']}})">{{$r['status']}}</span></td>
                                        @else
                                        <span class="label bg-green detail-catatan" style="cursor:pointer;" onclick="detail_catatan({{$r['id_role']}})">{{$r['status']}}</span></td>
                                        @endif
                                    <td style="text-align:center;vertical-align: middle;">
                                    @if($r['status'])
                                    <div class="progress">
                                      <div class="progress-bar progress-bar-striped bg-success progress-bar-animated " role="progressbar" aria-valuenow="{{$r['progress']}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$r['progress']}}%;vertical-align: middle;">{{round($r['progress'],0)}} %</div>
                                    </div>
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
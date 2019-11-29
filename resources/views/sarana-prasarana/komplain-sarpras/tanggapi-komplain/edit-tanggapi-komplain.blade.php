<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#komplain-sarpras/tanggapi-komplain')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        DETAIL KOMPLAIN INVENTARIS/SARPRAS
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                            <tr>
                                <th colspan="2" style="text-align: center;">KOMPLAIN INVENTARIS/SARPRAS</th>
                            </tr>
                            <tr>
                                <td style="width: 50%">Nama Ruangan</td>
                                <td style="width: 50%">{{$nm_ruangan}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Nama Inventaris</td>
                                <td style="width: 50%">{{$nm_inventaris_ruangan}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Nama Buku/Alat</td>
                                <td style="width: 50%">{{$nm_buku_alat}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">User Komplain</td>
                                <td style="width: 50%">{{$user_komplain}}</td>
                            </tr>
                            <tr>
                                <td style="width: 50%">Keterangan Komplain</td>
                                <td style="width: 50%">{{$data_komplain_sarpras->keterangan_komplain}}</td>
                            </tr><tr>
                                <td style="width: 50%">Status Urgent</td>
                                <td style="width: 50%">{{$is_urgent}}</td>
                            </tr>
                        </table>
                    </div>

                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-tanggapi-komplain/edit/'.$data_komplain_sarpras->id_komplain_sarpras)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Tanggal Perbaikan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_perbaikan" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Keterangan Perbaikan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="keterangan_perbaikan" required="" aria-required="true" aria-invalid="true" >
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
$(function(){    
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'DD MMMM YYYY HH:mm:00',
        //lang : 'id',
        clearButton: true,
        weekStart: 1,
        time: true
    });

});
</script>
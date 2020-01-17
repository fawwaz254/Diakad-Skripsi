<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#data-sarpras-ruangan/inventaris')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT INVENTARIS
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-inventaris/edit/'.$data_inventaris_ruangan->id_inventaris_ruangan)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Ruangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_ruangan">
                                    @foreach($data_ruangan as $data)
                                        @if($data->id_ruangan == $data_inventaris_ruangan->id_ruangan)
                                            @if($data->is_aktif == 1)
                                                <option value="{{$data->id_ruangan}}" selected >{{$data->nm_ruangan}} - {{$data->nm_gedung}} (Aktif)</option>
                                            @else
                                                <option value="{{$data->id_ruangan}}" selected >{{$data->nm_ruangan}} - {{$data->nm_gedung}} (Non-Aktif)</option>
                                            @endif
                                        @else
                                            @if($data->is_aktif == 1)
                                                <option value="{{$data->id_ruangan}}">{{$data->nm_ruangan}} - {{$data->nm_gedung}} (Aktif)</option>
                                            @else
                                                <option value="{{$data->id_ruangan}}">{{$data->nm_ruangan}} - {{$data->nm_gedung}} (Non-Aktif)</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Inventaris
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_inventaris_ruangan" required="" aria-required="true" aria-invalid="true" value="{{$data_inventaris_ruangan->nm_inventaris_ruangan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kode Inventaris
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="kode_inventaris_ruangan" required="" aria-required="true" aria-invalid="true" value="{{$data_inventaris_ruangan->kode_inventaris_ruangan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Pembelian
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_pembelian" required="" aria-required="true" aria-invalid="true" value="{{$tgl_pembelian}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jumlah Inventaris
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="jumlah_inventaris_ruangan" required="" aria-required="true" aria-invalid="true" value="{{$data_inventaris_ruangan->jumlah_inventaris_ruangan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kondisi Baik
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="jumlah_kondisi_baik" required="" aria-required="true" aria-invalid="true" value="{{$data_inventaris_ruangan->jumlah_kondisi_baik}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kondisi Rusak
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="jumlah_kondisi_rusak" required="" aria-required="true" aria-invalid="true" value="{{$data_inventaris_ruangan->jumlah_kondisi_rusak}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Spesifikasi
                            <small>*Detail Spesifikasi Sarana seperti: ukuran, bahan, dan merk</small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="spesifikasi_inventaris_ruangan" required="" aria-required="true" aria-invalid="true" value="{{$data_inventaris_ruangan->spesifikasi_inventaris_ruangan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Keterangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="keterangan_inventaris_ruangan" required="" aria-required="true" aria-invalid="true" value="{{$data_inventaris_ruangan->keterangan_inventaris_ruangan}}">
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
        format: 'DD MMMM YYYY',
        //lang : 'id',
        clearButton: true,
        weekStart: 1,
        time: false
    });

});
</script>
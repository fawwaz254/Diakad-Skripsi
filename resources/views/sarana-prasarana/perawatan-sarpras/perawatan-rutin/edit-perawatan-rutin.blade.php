<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#perawatan-sarpras/input-perawatan-rutin')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT PERAWATAN RUTIN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-input-perawatan-rutin/edit/'.$data_perawatan_sarpras->id_perawatan_sarpras)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Ruangan
                            <small>*Wajib Diisi Minimal Salah Satu</small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_ruangan">
                                    @foreach($data_ruangan as $data)
                                        @if($data->id_ruangan == $data_perawatan_sarpras->id_ruangan)
                                            <option value="{{$data->id_ruangan}}" readonly >{{$data->nm_ruangan}} - {{$data->nm_jenis_ruangan}} ({{$data->nm_gedung}})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Inventaris Ruangan
                            <small>*Wajib Diisi Minimal Salah Satu</small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_inventaris_ruangan">
                                    @foreach($data_inventaris_ruangan as $data)
                                        @if($data->id_inventaris_ruangan == $data_perawatan_sarpras->id_inventaris_ruangan)
                                            <option value="{{$data->id_inventaris_ruangan}}" readonly >{{$data->nm_inventaris_ruangan}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Buku/Alat
                            <small>*Wajib Diisi Minimal Salah Satu</small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_buku_alat">
                                    @foreach($data_buku_alat as $data)
                                        @if($data->id_buku_alat == $data_perawatan_sarpras->id_buku_alat)
                                            <option value="{{$data->id_buku_alat}}" readonly>{{$data->nm_buku_alat}} - {{$data->jenis_buku_alat}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Perawatan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_perawatan" required="" aria-required="true" aria-invalid="true" value="{{$tgl_perawatan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Keterangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="keterangan_perawatan" required="" aria-required="true" aria-invalid="true" value="{{$data_perawatan_sarpras->keterangan_perawatan}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Sudah Dilakukan?
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="is_sudah_perawatan">
                                    @if($data_perawatan_sarpras->is_sudah_perawatan == 0)
                                        <option value="0" selected >Belum</option>
                                        <option value="1">Sudah</option>
                                    @else
                                        <option value="0">Belum</option>
                                        <option value="1" selected >Sudah</option>
                                    @endif
                                </select>
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
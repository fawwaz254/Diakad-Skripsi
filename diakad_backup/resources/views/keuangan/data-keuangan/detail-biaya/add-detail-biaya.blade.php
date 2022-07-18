<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-keuangan/detail-biaya')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH DETAIL BIAYA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-detail-biaya/add/'.$id_detail_biaya)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Biaya Sekolah
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_biaya_sekolah">
                                    <option value="" disabled selected >-- Pilih Biaya Sekolah --</option>
                                    @foreach($data_biaya_sekolah as $data)
                                        <option value="{{$data->id_biaya_sekolah}}">{{ ucwords($data->nm_kelompok_biaya) }} ({{$data->tahun_ajaran}} {{$data->nm_semester}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Biaya
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_biaya">
                                    <option value="" disabled selected >-- Pilih Nama Biaya --</option>
                                    @foreach($data_biaya as $data)
                                        <option value="{{$data->id_biaya}}">{{$data->nm_biaya}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Biaya Internal
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelompok_biaya_internal">
                                    <option value="" >-- Pilih Nama Biaya Internal --</option>
                                    @foreach($data_biaya_internal as $data)
                                        <option value="{{$data->id_kelompok_biaya_internal}}">{{$data->nm_kelompok_biaya_internal}} ({{$data->nm_biaya}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Validasi
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="validasi_biaya">
                                    <option value="0">Belum</option>
                                    <option value="1">Sudah</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Besar Biaya
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="besar_biaya" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Keterangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="keterangan_biaya" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jenis Biaya
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_jenis_detail_biaya" onchange="changeJenis(this)">
                                    <option value="" disabled selected >-- Pilih Jenis Biaya --</option>
                                    @foreach($data_jenis_detail_biaya as $data)
                                        <option value="{{$data->id_jenis_detail_biaya}}">{{$data->nm_jenis_detail_biaya}} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Bulan <small>* Khusus Jenis Biaya Pembayaran Per Bulan</small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div id="div_bulan">
                                </div>
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
function changeJenis(el){
    $.ajax({
        url: '{{url(Request::segment(1).'/'.Request::segment(2).'/bulan-byjenisbiaya')}}',
        type: 'POST',
        data: {
            id_jenis_detail_biaya: $('select[name=id_jenis_detail_biaya]').val()
        },
        success: function(result) {
            // $('select[name=id_bulan]').html('');
            // var html = '<option value="">-- Pilih Bulan --</option>';
            // $.each(result, function( key, item ) {
            //     html += '<option value="'+item.id_bulan+'">'+item.nm_bulan+'</option>'
            // });
            // $('select[name=id_bulan]').html(html);

            $('#div_bulan').html('');
            var html = '';
            $.each(result, function( key, item ) {
                html += '<div class="row clearfix" style="margin-left:0;">'+
                    '<input type="checkbox" id="checkbox-'+item.id_bulan+'" name="id_bulan[]" class="filled-in" value="'+item.id_bulan+'">'+
                    '<label for="checkbox-'+item.id_bulan+'">'+item.nm_bulan+'</label>'+
                '</div>';
            });
            $('#div_bulan').html(html);
        }
    });
}
</script>
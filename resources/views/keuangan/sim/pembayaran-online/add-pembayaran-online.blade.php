<div class="container-fluid">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#'.Request::segment(2).'/'.Request::segment(3))}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            REQUST KODE BAYAR
                        </h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/save')}}">
                            {{csrf_field()}}
                            <h2 class="card-inside-title">
                                Kelas
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="kelas" onchange="changeKelas(this)">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($data_kelas as $data)
                                            <option value="{{$data->id_kelas}}">{{$data->nm_kelas}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Nama Siswa
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_siswa" onchange="changeSiswa(this)">
                                        <option value="">-- Pilih Siswa --</option>
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Tagihan yang akan dibayar
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div id="div_tagihan">
                                    </div>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Dibayar melalui
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="payment_channel" required="">
                                        <option value="" disabled selected >-- Pilih Metode Pembayaran --</option>
                                        @foreach($grup_payment_channel as $name => $data_payment_channel)
                                        <optgroup label="{{$name}}">
                                            @foreach($data_payment_channel as $data)
                                                <option value="{{$data->payment_code}}">{{$data->payment_name}} ({{$data->payment_description}})</option>
                                            @endforeach
                                        </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Request kode bayar</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
function changeKelas(el){
    $.ajax({
        url: "{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/siswa-bykelas')}}",
        type: 'POST',
        data: {
            kelas: $('select[name=kelas]').val()
        },
        success: function(result) {
            $('select[name=id_siswa]').html('');
            var html = '<option value="">-- Pilih Siswa --</option>';
            $.each(result, function( key, item ) {
                html += '<option value="'+item.id_siswa+'" data-nis="'+item.nis_siswa+'" data-id="'+item.id_pengguna+'">'+item.nm_pengguna+' ('+item.nis_siswa+')</option>'
            });
            $('select[name=id_siswa]').html(html);
        }
    });
}
function changeSiswa(el){
    var nis_nama_siswa = $(el).children("option:selected").data('nis');
    var id_pengguna = $(el).children("option:selected").data('id');
    
    $.ajax({
        url: "{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/tagihan/datatables')}}/" + id_pengguna,
        type: 'POST',
        success: function(result) {
            $('#div_tagihan').html('');
            var html = '';
            $.each(result.data, function( key, item ) {
                html += '<div class="row clearfix" style="margin-left:0;">'+
                    '<input type="checkbox" id="checkbox-'+item.id_tagihan_biaya+'" name="id_tagihan_biaya[]" class="filled-in" value="'+item.id_tagihan_biaya+'">'+
                    '<label for="checkbox-'+item.id_tagihan_biaya+'">'+item.nm_biaya + ' ' + item.jenis_biaya +' ('+item.besar_biaya+')</label>'+
                '</div>';
            });
            $('#div_tagihan').html(html);
        }
    });
}
</script>
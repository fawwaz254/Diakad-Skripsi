<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#perawatan-sarpras/pengadaan-sarpras')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH PENGADAAN BARANG/SARPRAS
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-pengadaan-sarpras/add/'.$id_rpb_sarpras)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Semester
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester">
                                    <option value="" disabled selected >-- Pilih Semester --</option>
                                    @foreach($data_semester as $data)
                                        @if($data->is_aktif_semester == 1)
                                            <option value="{{$data->id_semester}}" selected>{{$data->tahun_ajaran}} {{$data->nm_semester}} (Aktif)</option>
                                        @else
                                            <option value="{{$data->id_semester}}">{{$data->tahun_ajaran}} {{$data->nm_semester}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Unit Kerja
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_unit_kerja">
                                  <option value="" disabled selected >-- Pilih Unit Kerja --</option>
                                    @foreach($data_unit_kerja as $data)
                                        <option value="{{$data->id_unit_kerja}}">{{$data->nm_singkatan_unit}} - {{$data->nm_unit_kerja}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Ruangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_ruangan" onchange="changeRuangan(this)">
                                    <option value="" disabled selected >-- Pilih Ruangan --</option>
                                    @foreach($data_ruangan as $data)
                                        <option value="{{$data->id_ruangan}}">{{$data->nm_ruangan}} - {{$data->nm_jenis_ruangan}} ({{$data->nm_gedung}})</option>
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
                                    <option value="" disabled selected >-- Pilih Inventaris Ruangan --</option>
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
                                    <option value="" disabled selected >-- Pilih Buku/Alat --</option>
                                    @foreach($data_buku_alat as $data)
                                        <option value="{{$data->id_buku_alat}}">{{$data->nm_buku_alat}} - {{$data->nm_jenis_buku_alat}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Harga Satuan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="harga_satuan_rpb_sarpras" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Qty
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="qty_rpb_sarpras" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Pengadaan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_rpb_sarpras" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Prioritas Pengadaan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="prioritas_rpb_sarpras">
                                    <option value="" disabled selected >-- Pilih Prioritas --</option>
                                    <option value="1">Rendah</option>
                                    <option value="2">Sedang</option>
                                    <option value="3">Tinggi</option>
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

function changeRuangan(el){
    $.ajax({
        url: '{{url(Request::segment(1).'/'.Request::segment(2).'/inventaris-byruangan')}}',
        type: 'POST',
        data: {
            id_ruangan: $('select[name=id_ruangan]').val()
        },
        success: function(result) {
            $('select[name=id_inventaris_ruangan]').html('');
            var html = '<option value="" disabled selected >-- Pilih Inventaris Ruangan --</option>';
            $.each(result, function( key, item ) {
                html += '<option value="'+item.id_inventaris_ruangan+'">'+item.nm_inventaris_ruangan+'</option>'
            });
            $('select[name=id_inventaris_ruangan]').html(html);
        }
    });
}
</script>
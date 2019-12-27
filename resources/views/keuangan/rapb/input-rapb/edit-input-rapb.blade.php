<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#rapb/input-rapb/view-detail-input-rapb/'.$semester_mulai->id_semester.'/'.$semester_selesai->id_semester)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        INPUT RAPB
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-input-rapb/edit/'.$data_rapb->id_rapb)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Semester Mulai
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester_mulai">
                                    <option value="{{$semester_mulai->id_semester}}">{{$semester_mulai->tahun_ajaran}} {{$semester_mulai->nm_semester}}</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Semester Selesai
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester_selesai">
                                    <option value="{{$semester_selesai->id_semester}}">{{$semester_selesai->tahun_ajaran}} {{$semester_selesai->nm_semester}}</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jenis RAPB
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="jenis" onchange="changeJenis(this)">
                                    <option value="">-- Pilih Jenis --</option>
                                    @if($jenis_kategori == 1)
                                        <option value="1" selected >Penerimaan</option>
                                        <option value="2">Pengeluaran</option>
                                    @elseif($jenis_kategori == 2)
                                        <option value="1">Penerimaan</option>
                                        <option value="2" selected >Pengeluaran</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kategori RAPB
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kategori_rapb" onchange="changeKategori(this)">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($data_kategori as $data)
                                        @if($data->id_kategori_rapb == $id_kategori_rapb)
                                            <option value="{{$data->id_kategori_rapb}}" selected>{{$data->kode_kategori_rapb}} - {{$data->nm_kategori_rapb}}</option>
                                        @else
                                            <option value="{{$data->id_kategori_rapb}}">{{$data->kode_kategori_rapb}} - {{$data->nm_kategori_rapb}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Sub-Kategori RAPB
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_subkategori_rapb">
                                    <option value="">-- Pilih Sub-Kategori --</option>
                                    @foreach($data_subkategori as $data)
                                        @if($data->id_subkategori_rapb == $data_rapb->id_subkategori_rapb)
                                            <option value="{{$data->id_subkategori_rapb}}" selected>{{$data->kode_subkategori_rapb}} - {{$data->nm_subkategori_rapb}}</option>
                                        @else
                                            <option value="{{$data->id_subkategori_rapb}}">{{$data->kode_subkategori_rapb}} - {{$data->nm_subkategori_rapb}}</option>
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
                                        @if($data->id_unit_kerja == $data_rapb->id_unit_kerja)
                                            <option value="{{$data->id_unit_kerja}}" selected >{{$data->nm_singkatan_unit}} - {{$data->nm_unit_kerja}}</option>
                                        @else
                                            <option value="{{$data->id_unit_kerja}}">{{$data->nm_singkatan_unit}} - {{$data->nm_unit_kerja}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Dana Perkiraan RAPB
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="dana_perkiraan_rapb" required="" aria-required="true" aria-invalid="true" value="{{$data_rapb->dana_perkiraan_rapb}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal RAPB
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_rapb" required="" aria-required="true" aria-invalid="true" value="{{$tgl_rapb}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Prioritas RAPB
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="prioritas_rapb">
                                    <option value="">-- Pilih Prioritas --</option>
                                    @if($data_rapb->prioritas_rapb == 1)
                                        <option value="1" selected >Rendah</option>
                                        <option value="2">Sedang</option>
                                        <option value="3">Tinggi</option>
                                    @elseif($data_rapb->prioritas_rapb == 2)
                                        <option value="1">Rendah</option>
                                        <option value="2" selected >Sedang</option>
                                        <option value="3">Tinggi</option>
                                    @elseif($data_rapb->prioritas_rapb == 3)
                                        <option value="1">Rendah</option>
                                        <option value="2">Sedang</option>
                                        <option value="3" selected >Tinggi</option>
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
function changeJenis(el){
    $.ajax({
        url: '{{url(Request::segment(1).'/'.Request::segment(2).'/kategori-byjenis')}}',
        type: 'POST',
        data: {
            jenis: $('select[name=jenis]').val()
        },
        success: function(result) {
            $('select[name=id_kategori_rapb]').html('');
            var html = '<option value="" disabled selected >-- Pilih Kategori --</option>';
            $.each(result, function( key, item ) {
                html += '<option value="'+item.id_kategori_rapb+'">'+item.kode_kategori_rapb+' - '+item.nm_kategori_rapb+'</option>'
            });
            $('select[name=id_kategori_rapb]').html(html);

            $('select[name=id_subkategori_rapb]').html('');
            var html = '<option value="">-- Pilih Sub-Kategori --</option>';
            $('select[name=id_subkategori_rapb]').html(html);
        }
    });
}

function changeKategori(el){
    $.ajax({
        url: '{{url(Request::segment(1).'/'.Request::segment(2).'/subkategori-bykategori')}}',
        type: 'POST',
        data: {
            id_kategori_rapb: $('select[name=id_kategori_rapb]').val()
        },
        success: function(result) {
            $('select[name=id_subkategori_rapb]').html('');
            var html = '<option value="" disabled selected >-- Pilih Sub-Kategori --</option>';
            $.each(result, function( key, item ) {
                html += '<option value="'+item.id_subkategori_rapb+'">'+item.kode_subkategori_rapb+' - '+item.nm_subkategori_rapb+'</option>'
            });
            $('select[name=id_subkategori_rapb]').html(html);
        }
    });
}

$(function(){    
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'dddd, DD MMMM YYYY',
        //lang : 'id',
        clearButton: true,
        weekStart: 1,
        time: false
    });
});
</script>
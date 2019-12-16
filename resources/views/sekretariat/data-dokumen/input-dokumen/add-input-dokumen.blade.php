<div class="container-fluid">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-dokumen/input-dokumen/')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            TAMBAH DATA DOKUMEN
                        </h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-input-dokumen/add/0')}}">
                            {{csrf_field()}}
                            <h2 class="card-inside-title">
                                Loker
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_arsip_loker">
                                        <option value="">-- Pilih Arsip Loker--</option>
                                        @foreach($loker as $data)
                                            <option value="{{$data->id_arsip_loker}}">
                                                {{$data->nm_arsip_loker}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Pemilik
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_arsip_pemilik">
                                        <option value="">-- Pilih Arsip Pemilik--</option>
                                        @foreach($pemilik as $data)
                                            <option value="{{$data->id_arsip_pemilik}}">
                                                {{$data->nm_arsip_pemilik}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Kategori
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="kategori" onchange="subKategori(this)">
                                        <option value="">-- Pilih Arsip Kategori--</option>
                                        @foreach($kategori as $data)
                                            <option value="{{$data->id_arsip_kategori}}">
                                                {{$data->nm_arsip_kategori}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                             <h2 class="card-inside-title">
                                Sub Kategori
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_arsip_subkategori">
                                        <option value="">-- Pilih Sub Kategori --</option>
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Unit Kerja <small>*Tidak wajib diisi</small>
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_unit_kerja">
                                        <option value="">-- Pilih Unit Kerja--</option>
                                        @foreach($unit as $data)
                                            <option value="{{$data->id_unit_kerja}}">
                                                {{$data->nm_unit_kerja}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Nama Arsip Dokumen
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_arsip_dokumen" required="" aria-required="true"
                                    aria-invalid="true" value="">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Kode Katalog
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="kode_katalog" required="" aria-required="true"
                                    aria-invalid="true" value="">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Nomor Arsip Dokumen
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nomor_arsip_dokumen" required="" aria-required="true"
                                    aria-invalid="true" value="">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jumlah Halaman
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="jumlah_halaman" required="" aria-required="true"
                                    aria-invalid="true" value="">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Tanggal Penyusunan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="datepicker form-control" name="tgl_penyusunan" required="" aria-required="true"
                                    aria-invalid="true" value="">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Contact Person <small>Nomor HP atau kontak dari penanggungjawab dokumen</small>
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="contact_person" required="" aria-required="true"
                                    aria-invalid="true" value="">
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
<script type="text/javascript">
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
<script>
function subKategori(el){
    $.ajax({
        url: '{{url(Request::segment(1).'/'.Request::segment(2).'/sub-kategori')}}',
        type: 'POST',
        data: {
            kategori: $('select[name=kategori]').val()
        },
        success: function(result) {
            $('select[name=id_arsip_subkategori]').html('');
            var html = '<option value="">-- Pilih SubKategori --</option>';
            $.each(result, function( key, item ) {
                html += '<option value="'+item.id_arsip_subkategori+'">'+item.nm_arsip_subkategori+'</option>'
            });
            $('select[name=id_arsip_subkategori]').html(html);
        }
    });
}
</script>

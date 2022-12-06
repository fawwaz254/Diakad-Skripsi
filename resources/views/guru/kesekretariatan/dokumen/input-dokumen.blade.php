<div class="container-fluid">
@if(empty($dokumen->id_arsip_dokumen)) 
    <form id="form-upload" method="POST"
        action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-upload-dokumen/add/0')}}" enctype="multipart/form-data">
@else 
    <form id="form-upload" method="POST"
        action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-upload-dokumen/edit/'.$dokumen->id_arsip_dokumen)}}" enctype="multipart/form-data">
@endif
        {{csrf_field()}}
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            {{$title}}
                        </h2>
                    </div>
                    <div class="body">
                        <h2 class="card-inside-title">
                            Loker
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_arsip_loker">
                                    <option value="">-- Pilih Arsip Loker--</option>
                                    @foreach($loker as $data)
                                    <option value="{{$data->id_arsip_loker}}" @if(!empty($dokumen->id_arsip_dokumen)) @if($data->id_arsip_loker == $dokumen->id_arsip_loker) selected @endif @endif>
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
                                    <option value="{{$data->id_arsip_pemilik}}" @if(!empty($dokumen->id_arsip_dokumen)) @if($data->id_arsip_pemilik == $dokumen->id_arsip_pemilik) selected @endif @endif>
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
                                <select class="form-control show-tick" name="kategori" onchange="subKategori()">
                                    <option value="">-- Pilih Arsip Kategori--</option>
                                    @foreach($kategori as $data)
                                    <option value="{{$data->id_arsip_kategori}}" @if(!empty($dokumen->id_arsip_dokumen)) @if($data->id_arsip_kategori == $dokumen->id_arsip_kategori) selected @endif @endif>
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
                                    <option value="{{$data->id_unit_kerja}}" @if(!empty($dokumen->id_arsip_dokumen)) @if($data->id_unit_kerja == $dokumen->id_unit_kerja) selected @endif @endif>
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
                                <input type="text" class="form-control" name="nm_arsip_dokumen" required=""
                                    aria-required="true" aria-invalid="true" value="@if(!empty($dokumen->id_arsip_dokumen)) {{$dokumen->nm_arsip_dokumen}} @endif">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kode Katalog
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="kode_katalog" required=""
                                    aria-required="true" aria-invalid="true" value="@if(!empty($dokumen->id_arsip_dokumen)) {{$dokumen->kode_katalog}} @endif">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nomor Arsip Dokumen
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nomor_arsip_dokumen" required=""
                                    aria-required="true" aria-invalid="true" value="@if(!empty($dokumen->id_arsip_dokumen)) {{$dokumen->nomor_arsip_dokumen}} @endif">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Jumlah Halaman
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="jumlah_halaman" required=""
                                    aria-required="true" aria-invalid="true" value="@if(!empty($dokumen->id_arsip_dokumen)) {{$dokumen->jumlah_halaman}} @endif">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Penyusunan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_penyusunan" required=""
                                    aria-required="true" aria-invalid="true" value="@if(!empty($dokumen->id_arsip_dokumen)) {{$dokumen->tgl_penyusunan}} @endif">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Contact Person <small>Nomor HP atau kontak dari penanggungjawab dokumen</small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="contact_person" required=""
                                    aria-required="true" aria-invalid="true" value="@if(!empty($dokumen->id_arsip_dokumen)) {{$dokumen->contact_person}} @endif">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Dokumen
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>
                                    <input type="file" name="file" />
                                </label>
                            </div>
                        </div>
                        @if($arsip_dokumen_file != null)
                            @foreach($arsip_dokumen_file as $file)
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <li>
                                        <a href="{{ Storage::disk('spaces')->url($file->nm_arsip_dokumen_file) }}" target="_blank">
                                        @if (in_array(pathinfo($file->nm_arsip_dokumen_file, PATHINFO_EXTENSION), ['jpg','jpeg','bmp','png']))
                                        <img style="height:7rem; width:auto;" src="{{ Storage::disk('spaces')->url($file->nm_arsip_dokumen_file) }}">
                                        @else
                                        {{ str_limit($file->nm_arsip_dokumen_file, $limit = 50, $end = '...').pathinfo($file->nm_arsip_dokumen_file, PATHINFO_EXTENSION) }}
                                        @endif
                                        </a>
                                        </li>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <em>No files to display.</em>
                                </div>
                            </div>
                        @endif
                        <h2 class="card-inside-title">
                            Status Akses Dokumen
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control" name="is_publik" id="is_publik">
                                    <option value="unselected">-- Pilih Status Akses --</option>
                                    <option value="1" @if(!empty($dokumen->id_arsip_dokumen)) @if($dokumen->is_publik == 1) selected @endif @endif>Publik</option>
                                    <option value="0" @if(!empty($dokumen->id_arsip_dokumen)) @if($dokumen->is_publik == 0) selected @endif @endif>Terbatas</option>
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix" id="save">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="hak_akses" @if(!empty($dokumen->id_arsip_dokumen)) @if($dokumen->is_publik == 1) style="display: none" @endif @else style="display: none" @endif>
                    <br>
                    <div class="card">
                        <div class="header">
                            <h2>
                                HAK AKSES ARSIP (ONLINE)
                            </h2>
                        </div>
                        <div class="body">
                            <h2 class="card-inside-title">
                                Status Pengguna
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1"
                                            name="status_pengguna[]" value="1" @if(!empty($dokumen->id_arsip_dokumen)) @if($dokumen_akses->firstWhere('status_join_table', 1)) checked @endif @endif>
                                        <label class="form-check-label" for="inlineCheckbox1">Tendik</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox2"
                                            name="status_pengguna[]" value="2" @if(!empty($dokumen->id_arsip_dokumen)) @if($dokumen_akses->firstWhere('status_join_table', 2)) checked @endif @endif>
                                        <label class="form-check-label" for="inlineCheckbox2">Guru</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox3"
                                            name="status_pengguna[]" value="3" @if(!empty($dokumen->id_arsip_dokumen)) @if($dokumen_akses->firstWhere('status_join_table', 3)) checked @endif @endif>
                                        <label class="form-check-label" for="inlineCheckbox3">Siswa</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox4"
                                            name="status_pengguna[]" value="4" @if(!empty($dokumen->id_arsip_dokumen)) @if($dokumen_akses->firstWhere('status_join_table', 4)) checked @endif @endif>
                                        <label class="form-check-label" for="inlineCheckbox4">Wali Murid</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox5"
                                            name="status_pengguna[]" value="5" @if(!empty($dokumen->id_arsip_dokumen)) @if($dokumen_akses->firstWhere('status_join_table', 5)) checked @endif @endif>
                                        <label class="form-check-label" for="inlineCheckbox5">Pelatih Ekskul</label>
                                    </div>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Unit Kerja
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    @php $end = 6; @endphp
                                    @foreach($unit as $unit_kerja)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="inlineCheckbox{{$end}}"
                                            name="unit_kerja[]" value="{{$unit_kerja->id_unit_kerja}}" @if(!empty($dokumen->id_arsip_dokumen)) @if($dokumen_akses->firstWhere('id_unit_kerja', $unit_kerja->id_unit_kerja)) checked @endif @endif>
                                        <label class="form-check-label"
                                            for="inlineCheckbox{{$end}}">{{$unit_kerja->nm_unit_kerja}}</label>
                                    </div>
                                    @php $end++; @endphp
                                    @endforeach
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                            class="material-icons">save</i><span>Save</span></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
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
$('#form-upload').submit(function(e) {
        e.preventDefault();
    }).validate({
        highlight: function (input) {
            $(input).addClass('is-danger');
        },
        unhighlight: function (input) {
            $(input).removeClass('is-danger');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.control').addClass('help').addClass('is-danger').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');

            var formData = new FormData(form);
            
            setTimeout(() => {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    enctype: 'multipart/form-data',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if(response.status == 200){
                            vex.dialog.alert(response.message);
                        }else if(response.status == 201){
                            vex.dialog.alert(response.message);
                            window.location.href = response.link;
                        }else if(response.status == 202){
                            vex.dialog.alert(response.message);
                            loadURI(response.path);
                        }else if(response.status == 203){
                            vex.dialog.alert(response.message);
                            primary_table.ajax.reload(null, false);
                        }else if(response.status == 204){
                            loadURI(response.path);
                        }else if(response.status == 300){
                            vex.dialog.alert(response.message);
                        }
                    },
                    complete: function() {
                        $('button').removeAttr('disabled');
                    }
                });
                
            }, 1000);
        }
    });

function subKategori(){
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
<script>
    $(document).ready(function () {
        $("#is_publik").change(function () {
            // view hak akses
            if($("#is_publik option:selected").val() == 0) {
                $('#hak_akses').show();
                $('#save').hide();
            } else {
                $('#hak_akses').hide();
                $('#save').show();
            }
        });

        @if(!empty($dokumen->id_arsip_dokumen)) 
        var id_subkategori = '{!! $dokumen->id_arsip_subkategori !!}';
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
                    if(id_subkategori == item.id_arsip_subkategori){
                    html += '<option value="'+item.id_arsip_subkategori+'" selected>'+item.nm_arsip_subkategori+'</option>'
                    }else{
                    html += '<option value="'+item.id_arsip_subkategori+'">'+item.nm_arsip_subkategori+'</option>'
                    }
                });
                $('select[name=id_arsip_subkategori]').html(html);
            }
        });
        @endif
    });
</script>

<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#mgmp/laporan-harian-mgmp')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH LAPORAN KERJA HARIAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-upload" method="POST"action="{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/action-kerja-harian/add/0')}}">
                        {{csrf_field()}}

                        <h2 class="card-inside-title">
                            Tanggal
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tanggal" required="" aria-required="true"
                                        aria-invalid="true" value="{{ $waktu }}">
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Mata Pelajaran
                         </h2>
                         <select class="form-control show-tick" name="mata_pelajaran">
                             <option  value="" selected>-- Pilih Mata Pelajaran--</option>
                             @foreach($mapel as $mata_pelajaran)
                             <option value="{{ $mata_pelajaran->categori_file_mgmp->category_file_mgmp_id}}">
                                 {{ $mata_pelajaran->categori_file_mgmp->category_file_name }}
                             </option>
                             @endforeach
                         </select>

                         <h2 class="card-inside-title">
                            Jenis Jurnal Harian
                         </h2>
                         <select class="form-control show-tick" name="jenis">
                             <option  value="" selected>-- Pilih Jenis Jurnal Harian--</option>
                             @foreach($jenis as $nama_jenis)
                             <option value="{{ $nama_jenis->jenis_MGMP}}">
                                 {{ $nama_jenis->jenis_MGMP}}
                             </option>
                             @endforeach
                         </select>
                         {{-- <h2 class="card-inside-title">
                            Jenis
                        </h2>
                        <div class="demo-radio-button">
                            <input name="jenis" type="radio" value="Silabus" id="radio_1"  />
                            <label for="radio_1">Silabus</label>
                            <input name="jenis" type="radio" value="RPP" id="radio_2" />
                            <label for="radio_2">RPP</label>
                            <input name="jenis" type="radio" value="Prota" id="radio_3" />
                            <label for="radio_3">Prota</label>
                            <input name="jenis" type="radio" value="Promes" id="radio_4" />
                            <label for="radio_4">Promes</label>
                        </div> --}}

                        <h2 class="card-inside-title">
                            Status
                        </h2>
                        <div class="demo-radio-button">
                            <input name="status" type="radio" value="1" id="target_1"   />
                            <label for="target_1">Selesai</label>
                            <input name="status" type="radio" value="0" id="target_2" />
                            <label for="target_2">Belum Selesai</label>
                        </div>

                        <h2 class="card-inside-title">
                            File Pendukung ( pdf , pptx , docx , doc , xlsx , png , jpg , jpeg | max 5 mb )
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>
                                    <input type="file" class="form-control" name="file" />
                                </label>
                            </div>
                        </div>


                        <h2 class="card-inside-title">
                            Keterangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea rows="4" cols="50" class="form-control" name="keterangan" required="" aria-required="true"
                                    aria-invalid="true"></textarea>
                            </div>
                        </div>


                        {{-- @foreach($mapel as $mata_pelajaran)
                        {{ $mata_pelajaran->categori_file_mgmp->category_file_name }}
                        @endforeach --}}
                        {{-- <div class="demo-radio-button">
                            <input name="lokasi" type="radio" value="kantor" id="radio_3" checked  />
                            <label for="radio_3">Kantor</label>
                            <input name="lokasi" type="radio" value="lapangan" id="radio_4" />
                            <label for="radio_4">Lapangan</label>
                        </div> --}}

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

<script>    
    $('#form-upload').validate({
        rules: {
            'checkbox': {
                required: true
            },
            'gender': {
                required: true
            }
        },
        highlight: function (input) {
            $(input).parents('.form-group').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-group').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');
            $.ajax({
                url: form.action,
                type: form.method,
                enctype: 'multipart/form-data',
                data: new FormData($('#form-upload')[0]),
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
                    $('input').removeAttr('readonly', 'readonly');
                    $('button').removeAttr('disabled');
                }
            });
        }
    });
</script>


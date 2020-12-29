    <div class="container-fluid">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-kesiswaan/prestasi-siswa')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            EDIT PRESTASI SISWA {{$prestasi->nm_prestasi_siswa}}
                        </h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-prestasi-siswa/edit/'.$prestasi->id_prestasi_siswa)}}">
                            {{csrf_field()}}
                            <h2 class="card-inside-title">
                                Nama Siswa
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_pengguna" readonly="" aria-required="true"
                                    aria-invalid="true" value="{{$prestasi->nm_pengguna}}">
                                    <input type="hidden" class="form-control" name="id_siswa" readonly="" aria-required="true"
                                    aria-invalid="true" value="{{$prestasi->id_siswa}}">
                                </div>
                            </div>

                            <h2 class="card-inside-title">
                                Semester
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_semester">
                                        <option value="">-- Pilih Semester --</option>
                                        @foreach($data_semester as $data)
                                            <option value="{{$data->id_semester}}" @if($data->id_semester == $prestasi->id_semester) selected @endif>
                                                {{$data->nm_semester}} ({{$data->tahun_ajaran}}) @if($data->is_aktif_semester == "1") (Aktif) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Nama Prestasi
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_prestasi_siswa" required="" aria-required="true"
                                    aria-invalid="true" value="{{$prestasi->nm_prestasi_siswa}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Tingkat Prestasi
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_tingkat_prestasi_siswa">
                                        <option value="">-- Pilih Tingkat Prestasi --</option>
                                        @foreach($data_tingkat_prestasi as $data)
                                            <option value="{{$data->id_tingkat_prestasi_siswa}}" @if($data->id_tingkat_prestasi_siswa == $prestasi->id_tingkat_prestasi_siswa) selected @endif>
                                               {{$data->nm_tingkat_prestasi_siswa}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jenis Prestasi
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="jenis_prestasi_siswa">
                                        <option value="">-- Pilih Jenis Prestasi --</option>
                                        <option value="1" @if($prestasi->jenis_prestasi_siswa == 1) selected @endif>Sains</option>
                                        <option value="2" @if($prestasi->jenis_prestasi_siswa == 2) selected @endif>Seni</option>
                                        <option value="3" @if($prestasi->jenis_prestasi_siswa == 3) selected @endif>Olahraga</option>
                                        <option value="4" @if($prestasi->jenis_prestasi_siswa == 4) selected @endif>Lain-Lain</option>
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Lokasi Prestasi
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="lokasi_prestasi_siswa" required="" aria-required="true"
                                    aria-invalid="true" value="{{$prestasi->lokasi_prestasi_siswa}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Penyelenggara Prestasi
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="penyelenggara_prestasi_siswa" required="" aria-required="true"
                                    aria-invalid="true" value="{{$prestasi->penyelenggara_prestasi_siswa}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Peringkat Prestasi <small><b>* Misal: Juara I maka diisi 1, Juara Harapan 1 maka diisi 4</b></small>
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="peringkat_prestasi_siswa" required="" aria-required="true"
                                    aria-invalid="true" value="{{$prestasi->peringkat_prestasi_siswa}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Tanggal Prestasi
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="datepicker form-control" name="tgl_prestasi_siswa" required="" aria-required="true" aria-invalid="true" value="{{$prestasi->tgl_prestasi_siswa}}">
                                </div>
                            </div>
                             <h2 class="card-inside-title">
                                Ekstrakurikuler <small><b>* Tidak Wajib Diisi. Hanya Diisi Bila Prestasi Berhubungan dengan Ekstrakurikuler</b></small>
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_ekskul">
                                        <option value="">-- Pilih Ekskul --</option>
                                        @foreach($data_ekskul as $data)
                                            <option value="{{$data->id_ekskul}}" @if($data->id_ekskul == $prestasi->id_ekskul) selected @endif>
                                               {{$data->nm_ekskul}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Guru Pendamping <small><b>* Tidak Wajib Diisi. Hanya Diisi Bila Ada Guru Pendamping</b></small>
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_guru_pendamping">
                                        <option value="">-- Pilih Guru Pendamping --</option>
                                        @foreach($data_guru as $data)
                                            <option value="{{$data->id_guru}}" @if($data->id_guru == $prestasi->id_guru_pendamping) selected @endif>
                                               {{$data->gelar_depan}}{{$data->nm_pengguna}},{{$data->gelar_belakang}}
                                            </option>
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

function changeKelas(el){
    $.ajax({
        url: '{{url(Request::segment(1).'/'.Request::segment(2).'/siswa-bykelas')}}',
        type: 'POST',
        data: {
            kelas: $('select[name=kelas]').val()
        },
        success: function(result) {
            $('select[name=id_siswa]').html('');
            var html = '<option value="">-- Pilih Siswa --</option>';
            $.each(result, function( key, item ) {
                html += '<option value="'+item.id_siswa+'">'+item.nm_pengguna+' ('+item.nis_siswa+')</option>'
            });
            $('select[name=id_siswa]').html(html);
        }
    });
}
</script>
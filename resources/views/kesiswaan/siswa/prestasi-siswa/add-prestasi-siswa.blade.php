    <div class="container-fluid">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-kesiswaan/prestasi-siswa')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            TAMBAH PRESTASI SISWA
                        </h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-prestasi-siswa/add/0')}}">
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
                                    <select class="form-control show-tick" name="id_siswa">
                                        <option value="">-- Pilih Siswa --</option>
                                    </select>
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
                                            <option value="{{$data->id_semester}}">
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
                                    aria-invalid="true">
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
                                            <option value="{{$data->id_tingkat_prestasi_siswa}}">
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
                                        <option value="1">Sains</option>
                                        <option value="2">Seni</option>
                                        <option value="3">Olahraga</option>
                                        <option value="4">Lain-Lain</option>
                                    </select>
                                </div>
                            </div>

                            <h2 class="card-inside-title">
                                Jenis Lomba
                            </h2>
                            <select class="form-control show-tick" name="jenis_lomba_siswa">
                                <option value="">Pilih Jenis Lomba</option>
                                <option value="Individu">Individu</option>
                                <option value="Kelompok">Kelompok</option>
                            </select>


                            <h2 class="card-inside-title">
                                Lokasi Prestasi
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="lokasi_prestasi_siswa" required="" aria-required="true"
                                    aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Penyelenggara Prestasi
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="penyelenggara_prestasi_siswa" required="" aria-required="true"
                                    aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Peringkat Prestasi <small><b>* Misal: Juara I maka diisi 1, Juara Harapan 1 maka diisi 4</b></small>
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="peringkat_prestasi_siswa" required="" aria-required="true"
                                    aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Tanggal Prestasi
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="datepicker form-control" name="tgl_prestasi_siswa" required="" aria-required="true" aria-invalid="true">
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h2 class="card-inside-title">
                                        Link Sertifikat
                                    </h2>
                                    <input type="text" class="form-control" name="link_sertifikat" required="" aria-required="true" aria-invalid="true">
                                </div>
                            </div>

                             {{-- <h2 class="card-inside-title">
                                Ekstrakurikuler <small><b>* Tidak Wajib Diisi. Hanya Diisi Bila Prestasi Berhubungan dengan Ekstrakurikuler</b></small>
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_ekskul">
                                        <option value="">-- Pilih Ekskul --</option>
                                        @foreach($data_ekskul as $data)
                                            <option value="{{$data->id_ekskul}}">
                                               {{$data->nm_ekskul}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> --}}
                            <h2 class="card-inside-title">
                                Guru Pendamping <small><b>* Tidak Wajib Diisi. Hanya Diisi Bila Ada Guru Pendamping</b></small>
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_guru_pendamping">
                                        <option value="">-- Pilih Guru Pendamping --</option>
                                        @foreach($data_guru as $data)
                                            <option value="{{$data->id_guru}}">
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
            format: 'DD MMMM YYYY',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
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
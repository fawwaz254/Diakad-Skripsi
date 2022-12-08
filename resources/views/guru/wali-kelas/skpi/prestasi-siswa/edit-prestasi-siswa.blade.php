<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/' . Request::segment(4) . '/' . $prestasi->id_siswa) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT PRESTASI SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="post"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/' . Request::segment(4) . '/action/edit/' . $prestasi->id_prestasi_siswa) }}">
                        {{-- {{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/' . Request::segment(4) . '/action/edit/' . $prestasi->id_prestasi_siswa) }} --}}
                        {{ csrf_field() }}
                        <input type="hidden" name="id_siswa" value="{{ $prestasi->id_siswa }}">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Nama Prestasi
                                </h2>
                                <input type="text" class="form-control" name="nm_prestasi_siswa" required=""
                                    aria-required="true" value="{{ $prestasi->nm_prestasi_siswa }}" aria-invalid="true">
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-4">
                                <h2 class="card-inside-title">
                                    Peringkat
                                </h2>
                                <select class="form-control show-tick" name="peringkat_prestasi_siswa">
                                    <option value="1"
                                        {{ $prestasi->peringkat_prestasi_siswa == '1' ? 'selected' : '' }}>
                                        Peringkat 1</option>
                                    <option value="2"
                                        {{ $prestasi->peringkat_prestasi_siswa == '2' ? 'selected' : '' }}>
                                        Peringkat 2</option>
                                    <option value="3"
                                        {{ $prestasi->peringkat_prestasi_siswa == '3' ? 'selected' : '' }}>
                                        Peringkat 3</option>
                                    <option value="4"
                                        {{ $prestasi->peringkat_prestasi_siswa == '4' ? 'selected' : '' }}>
                                        Juara Harapan 1</option>
                                    <option value="5"
                                        {{ $prestasi->peringkat_prestasi_siswa == '5' ? 'selected' : '' }}>
                                        Juara Harapan 2</option>
                                    <option value="6"
                                        {{ $prestasi->peringkat_prestasi_siswa == '6' ? 'selected' : '' }}>
                                        Juara Harapan 3</option>
                                    <option value="7"
                                        {{ $prestasi->peringkat_prestasi_siswa == '7' ? 'selected' : '' }}>
                                        Peserta</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <h2 class="card-inside-title">
                                    Lokasi
                                </h2>
                                <input type="text" class="form-control" name="lokasi_prestasi_siswa" required=""
                                    aria-required="true" value="{{ $prestasi->lokasi_prestasi_siswa }}"
                                    aria-invalid="true" maxlength="64">
                            </div>

                            <div class="col-md-4">
                                <h2 class="card-inside-title">
                                    Penyelenggara
                                </h2>
                                <input type="text" class="form-control" name="penyelenggara_prestasi_siswa"
                                    required="" value="{{ $prestasi->penyelenggara_prestasi_siswa }}"
                                    aria-required="true" aria-invalid="true">
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h2 class="card-inside-title">
                                    Tingkat Prestasi
                                </h2>
                                <select class="form-control show-tick" name="id_tingkat_prestasi_siswa" required="">
                                    @foreach ($tingkat as $r)
                                        <option value="{{ $r->id_tingkat_prestasi_siswa }}"
                                            {{ $prestasi->id_tingkat_prestasi_siswa == $r->id_tingkat_prestasi_siswa ? 'selected' : '' }}>
                                            {{ $r->nm_tingkat_prestasi_siswa }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <h2 class="card-inside-title">
                                    Tanggal Kegiatan
                                </h2>
                                <input type="text" class="datepicker form-control" name="tgl_prestasi_siswa"
                                    required="" aria-required="true"
                                    value="{{ date('d F Y', strtotime($prestasi->tgl_prestasi_siswa)) }}"
                                    aria-invalid="true">
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-6">
                                <h2 class="card-inside-title">
                                    Guru Pendamping
                                </h2>
                                <select class="form-control show-tick" name="id_guru_pendamping">
                                    <option value="">Pilih Guru Pendamping</option>
                                    @foreach ($guru as $r)
                                        <option value="{{ $r->id_guru }}"
                                            {{ $prestasi->id_guru_pendamping == $r->id_guru ? 'selected' : '' }}>
                                            {{ $r->gelar_depan ? $r->gelar_depan : '' }} {{ $r->nm_guru_pendamping }}
                                            {{ $r->gelar_belakang ? $r->gelar_belakang : '' }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- <div class="col-md-4">
                                <h2 class="card-inside-title">
                                    Jenis Ekskul
                                </h2>
                                <select class="form-control show-tick" name="id_ekskul">
                                    <option value="">Pilih Jenis Ekskul</option>
                                    @foreach ($ekskul as $r)
                                        <option value="{{$r->id_ekskul}}"
                            {{$prestasi->id_ekskul == $r->id_ekskul ? 'selected' : ''}}>{{$r->nm_ekskul}}</option>
                            @endforeach
                            </select>
                        </div> --}}

                            <div class="col-md-6">
                                <h2 class="card-inside-title">
                                    Jenis Lomba
                                </h2>
                                <select class="form-control show-tick" name="jenis_lomba_siswa">
                                    <option value="">Pilih Jenis Lomba</option>
                                    <option value="Individu"
                                        {{ $prestasi->jenis_lomba_siswa == 'Individu' ? 'selected' : '' }}>Individu
                                    </option>
                                    <option value="Kelompok"
                                        {{ $prestasi->jenis_lomba_siswa == 'Kelompok' ? 'selected' : '' }}>Kelompok
                                    </option>
                                </select>
                            </div>

                        </div>

                        <div class="alert alert-warning">
                            <strong>Catatan !</strong> Untuk link sertifikat pastikan anda mengupload di google drive
                            dengan
                            settingan publik, untuk tutorial menguplod dengan settingan publik bisa dilihat <span
                                style="text-decoration:underline;cursor:pointer" id="disini">disini</span>.
                        </div>

                        <div id="video" style="display:none;">
                            <iframe width="870" height="393" src="https://www.youtube.com/embed/ccXgIuT0Hjc"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Link Sertifikat
                                </h2>
                                <input type="text" class="form-control" name="link_sertifikat"
                                    value="{{ $prestasi->link_sertif_prestasi_siswa }}" required=""
                                    aria-required="true" aria-invalid="true">
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
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
    $('#disini').click(function() {
        $('#video').show();
    })
</script>

<script type="text/javascript">
    $(function() {
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'DD MMMM YYYY',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
</script>

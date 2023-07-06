<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#siswa/insert-update-siswa') }}"><i
                    class="material-icons">note_add</i><span>Insert / Update Siswa</span></a>
            <a class="btn bg-green waves-effect target-link"
                href="{{ url(Request::segment(1) . '#siswa/upload-data-siswa') }}"><i
                    class="material-icons">attach_file</i><span>Upload Data Siswa Dengan Excel</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        DATA SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-view-data-siswa') }}">
                        {{ csrf_field() }}
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Jurusan
                                </h2>
                                <select class="form-control show-tick" name="id_jurusan" id="jurusan">
                                    <option value="0">-- Semua --</option>
                                    @foreach ($jurusan as $jurusan)
                                        <option value="{{ $jurusan->id_jurusan }}">{{ $jurusan->nm_jurusan }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Kelas
                                </h2>
                                <select class="form-control show-tick" name="id_kelas" id="kelas">
                                    <option value="0">-- Semua --</option>
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Tahun Masuk
                                </h2>
                                <select class="form-control show-tick" name="thn_masuk_siswa" id="thn_masuk">
                                    <option value="0">-- Semua --</option>
                                    @foreach ($thn_masuk_siswa as $tahun)
                                        @if ($tahun->thn_masuk_siswa == null)
                                        @else
                                            <option value="{{ $tahun->thn_masuk_siswa }}">{{ $tahun->thn_masuk_siswa }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Jalur
                                </h2>
                                <select class="form-control show-tick" name="id_jalur" id="jalur">
                                    <option value="0">-- Semua --</option>
                                    @foreach ($jalur as $jalur)
                                        <option value="{{ $jalur->id_jalur }}">{{ $jalur->nm_jalur }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Status Siswa
                                </h2>
                                <select class="form-control show-tick" name="id_status_pengguna" id="status_siswa">
                                    <option value="0">-- Semua --</option>
                                    @foreach ($status_pengguna as $status_siswa)
                                        <option value="{{ $status_siswa->id_status_pengguna }}">
                                            {{ $status_siswa->nm_status_pengguna }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix" style="display: none">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Filter Berdasarkan Abjad
                                </h2>
                                <select class="form-control" name="filter_by" id="filter_by">
                                    <option value="0">Semua</option>
                                    @foreach (range('A','Z') as $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Tampilkan</span></button>
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
    $(document).ready(function() {
        $('select').select();
    });

    var modul_url = 'siswa';

    $('#jurusan').on('change', function(e) {
        console.log(e);
        var id_jurusan = e.target.value;
        $.get(base_url + '/' + role_url + '/' + modul_url + '/' + 'data-siswa/get-kelas/' + id_jurusan,
            function(data) {
                console.log(data);
                $('#kelas').empty();


                $('#kelas').append($("<option>")
                    .attr("value", 0)
                    .text("-- Semua --")
                );
                $.each(data, function(index, kelasObj) {
                    $('#kelas').append($("<option>")
                        .attr("value", kelasObj.id_kelas)
                        .text(kelasObj.nm_kelas)
                    );
                })

                $('select').select();
            });
    });
</script>

<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#jadwal/set-kbm-tanpa-jadwal/view-detail/' . $id_kelas . '/' . $id_semester) }}">
                <i class="material-icons">backspace</i>
                <span>Kembali</span>
            </a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Input Jadwal Mata Ajar
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-set-kbm-tanpa-jadwal/add/0') }}">
                        {{ csrf_field() }}
                        <div class="demo-color-box bg-success">
                            Informasi Kelas dan Mata Pelajaran
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <label for="test">Jurusan : <span style="color: red">(Otomatis jika
                                        ada)</span></label>
                                <select class="form-control show-tick" onchange="changeJurusan(this)" name="jurusan">
                                    <option value="" selected>Semua</option>
                                    @foreach ($list_jurusan as $jurusan)
                                        <option @if ($jurusan->id_jurusan == $id_jurusan) selected @endif
                                            value="{{ $jurusan->id_jurusan }}">
                                            {{ $jurusan->nm_jurusan }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <label for="test">Jenis Mapel : <span style="color: red">(Opsional)</span></label>
                                <select class="form-control show-tick" onchange="changeJurusan(this)" name="jenismapel">
                                    <option value="" selected>Semua</option>
                                    @foreach ($list_jenis_mata_pelajaran as $jenis_mata_pelajaran)
                                        <option value="{{ $jenis_mata_pelajaran->id_jenis_mata_pelajaran }}">
                                            {{ $jenis_mata_pelajaran->nm_jenis_mata_pelajaran }}
                                            ({{ $jenis_mata_pelajaran->kode_jenis_mata_pelajaran }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <label for="test">Mapel :</label>
                                <select class="form-control show-tick" name="id_mata_pelajaran" required>
                                    <option value="" disabled selected>Pilih Mapel</option>
                                    @foreach ($data_jenis_mata_pelajaran as $jenis_mata_pelajaran)
                                        <optgroup label="{{ $jenis_mata_pelajaran->nm_jenis_mata_pelajaran }}"
                                            style="color: red">
                                            @foreach ($jenis_mata_pelajaran->mapel as $mapel)
                                                <option value="{{ $mapel->id_mata_pelajaran }}" style="color: black">
                                                    {{ $mapel->nm_mata_pelajaran }}
                                                    ({{ $mapel->kd_mata_pelajaran }})
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-4">
                                <label>Kelas</label>
                                <input type="text" class="form-control" name="kelas" readonly=""
                                    aria-required="true" aria-invalid="true" value="{{ $kelas->nm_kelas }}">
                                <input type="hidden" name="id_kelas" value="{{ $kelas->id_kelas }}">
                            </div>

                            <div class="col-md-4">
                                <label>Semester</label>
                                <input type="text" class="form-control" name="semester" readonly=""
                                    aria-required="true" aria-invalid="true"
                                    value="{{ $semester->nm_semester }}  {{ $semester->tahun_ajaran }}">
                                <input type="hidden" name="id_semester" value="{{ $semester->id_semester }}">
                            </div>
                            {{-- guru --}}
                            <div class="col-md-4">
                                <label>Guru</label>
                                <select class="form-control show-tick" name="id_guru">
                                    <option value="" disabled selected>Pilih Guru
                                    </option>
                                    @foreach ($list_guru as $guru)
                                        <option value="{{ $guru->id_guru }}"
                                            @if ($id_guru == $guru->id_guru) selected @endif>
                                            {{ $guru->pengguna->gelar_depan }}{{ $guru->pengguna->nm_pengguna }}{{ $guru->pengguna->gelar_belakang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <hr>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-6 col-sm-12 col-xs-12">
                                    <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                            class="material-icons">save</i><span>Save</span></button>
                                </div>
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
    $('.select2').select2();

    function changeJurusan(el) {
        $('select[name=id_mata_pelajaran]').empty();
        $.ajax({
            url: '{{ url(Request::segment(1) . '/' . Request::segment(2) . '/getMapelKbmTanpaJadwal') }}',
            type: 'POST',
            data: {
                jurusan: $('select[name=jurusan]').val(),
                jenismapel: $('select[name=jenismapel]').val(),
            },
            success: function(result) {
                $('select[name=id_mata_pelajaran]').html('');
                var html = '<option value="" >-- Pilih Mata Pelajaran --</option>';
                $.each(result['mapel'], function(key, item) {
                    html += '<option value="' + item.id_mata_pelajaran + '">' + item
                        .nm_mata_pelajaran + ' (' + item.kd_mata_pelajaran +
                        ')</option>'
                });
                $('select[name=id_mata_pelajaran]').html(html);
            }
        });
    }
</script>

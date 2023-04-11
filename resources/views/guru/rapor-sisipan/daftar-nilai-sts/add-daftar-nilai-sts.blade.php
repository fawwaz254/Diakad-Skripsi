<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#rapor-sisipan/daftar-nilai-sts') }}"><i
                    class="material-icons">keyboard_backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>
                        TAMBAH NILAI
                    </h2>
                </div>
                <div class="body">

                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/daftar-nilai-sts/action-daftar-nilai-sts/add/0') }}">
                        {{ csrf_field() }}
                        <div class="row clearfix">
                            <div class="col-md-4">
                                <label>Jurusan</label>
                                <select class="form-control show-tick" name="id_jurusan" onchange="changeJurusan(this)"
                                    required>
                                    <option selected disabled>-- Pilih Jurusan --</option>
                                    @foreach ($list_jurusan as $jurusan)
                                        <option value="{{ $jurusan->id_jurusan }}">{{ $jurusan->nm_jurusan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Jenis Mata Pelajaran</label>
                                <select class="form-control show-tick" name="id_jenis_mata_pelajaran"
                                    onchange="changeJurusan(this)" required>
                                    <option selected disabled>-- Pilih Semua --</option>
                                    @foreach ($jenis_mapel as $jenis)
                                        <option value="{{ $jenis->id_jenis_mata_pelajaran }}">
                                            {{ $jenis->nm_jenis_mata_pelajaran }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-4">
                                <label>Mata Pelajaran</label>
                                <select class="form-control show-tick" name="id_mata_pelajaran" required>
                                    {{-- <option selected disabled>-- Pilih Mata Pelajaran --</option> --}}
                                    {{-- @foreach ($list_mapel as $r)
                                        <option value="{{ $r->id_mata_pelajaran }}">{{ $r->nm_mata_pelajaran }} ({{ $r->kd_mata_pelajaran }})
                                        </option>
                                    @endforeach --}}
                                </select>
                            </div>

                        </div>
                        <div class="row clearfix">

                            <div class="col-md-6">
                                <label>Kelas</label>
                                <select class="form-control show-tick" name="id_kelas" required>
                                    {{-- <option selected disabled>-- Pilih Kelas --</option> --}}
                                    {{-- @foreach ($list_kelas as $r)
                                    <option value="{{ $r->id_kelas }}">{{ $r->nm_kelas }}
                                    </option>
                                @endforeach --}}
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label>Semester</label>
                                <select class="form-control show-tick" name="id_semester" required="">
                                    @foreach ($data_semester as $data)
                                        <option value="{{ $data->id_semester }}"
                                            @if ($semester_aktif->id_semester == $data->id_semester) selected @endif>
                                            {{ $data->tahun_ajaran }}
                                            {{ $data->nm_semester }}
                                            @if ($data->is_aktif_semester == 1)
                                                (Aktif)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                {{-- <option selected disabled>-- Pilih Kelas --</option> --}}
                                {{-- @foreach ($list_kelas as $r)
                                    <option value="{{ $r->id_kelas }}">{{ $r->nm_kelas }}
                                    </option>
                                @endforeach --}}
                                </select>
                            </div>

                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-indigo waves-effect" type="submit"><i
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

<script>
    function changeJurusan(el) {
        $.ajax({
            url: '{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/getMataPelajaran') }}',
            type: 'POST',
            data: {
                jurusan: $('select[name=id_jurusan]').val(),
                jenis_mata_pelajaran: $('select[name=id_jenis_mata_pelajaran]').val(),
            },
            success: function(result) {
                $('select[name=id_mata_pelajaran]').html('');
                var html = '<option value="">-- Pilih Mata Pelajaran --</option>';
                $.each(result['mapel'], function(key, item) {
                    html += '<option value="' + item.id_mata_pelajaran + '">' + item
                        .nm_mata_pelajaran + ' (' + item.kd_mata_pelajaran +
                        ')</option>'
                });
                $('select[name=id_mata_pelajaran]').html(html);

                $('select[name=id_kelas]').html('');
                var html = '<option value="">-- Pilih Kelas --</option>';
                $.each(result['kelas'], function(key, item) {
                    html += '<option value="' + item.id_kelas + '">' + item.nm_kelas + '</option>'
                });
                $('select[name=id_kelas]').html(html);
            }
        });
    }
</script>

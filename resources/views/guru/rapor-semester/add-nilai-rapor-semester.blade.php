<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#rapor-semester/tambah-nilai-rapor-semester') }}"><i
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
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/tambah-nilai-rapor-semester/action/add/0') }}">
                        {{ csrf_field() }}
                        <div class="row clearfix">

                            <div class="col-md-12">
                                <label>Kelas</label>
                                <select class="form-control show-tick" name="id_kelas" onchange="changeKelas(this)"
                                    required>
                                    <option selected disabled>-- Pilih Kelas --</option>
                                    @foreach ($list_kelas as $r)
                                        <option value="{{ $r->id_kelas }}">{{ $r->nm_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label>Mata Pelajaran</label>
                                <select class="form-control show-tick" name="id_mata_pelajaran" required>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label>Semester (Otomatis)</label>
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
                                </select>
                            </div>
                        </div>

                        <div id="place">
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
    function changeKelas(el) {
        $('select[name=id_mata_pelajaran]').html('');
        $('#place').html('');
        $.ajax({
            url: '{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/getMataPelajaran') }}',
            type: 'POST',
            data: {
                id_kelas: $('select[name=id_kelas]').val(),

            },
            success: function(result) {
                $('select[name=id_mata_pelajaran]').html('');
                var html = '<option value="">-- Pilih Mata Pelajaran --</option>';
                $.each(result['mapel'], function(key, item) {
                    if (item.mata_pelajaran_rapor.mata_pelajaran)
                        html += '<option value="' + item.mata_pelajaran_rapor.id_mata_pelajaran +
                        '">' +
                        item
                        .mata_pelajaran_rapor.mata_pelajaran.nm_mata_pelajaran +
                        '</option>'
                });
                $('select[name=id_mata_pelajaran]').html(html);
                $('#place').html('');

                if (result['kelas'].type_rapor == '1') {
                    var html = '';
                    $.each(result['kelas'].jenis_rapor.komponen_jenis_rapor, function(key, item) {
                        html += '<div class="col-md-12">' +
                            '<label>Keterangan ' + item.nm_komponen_jenis_rapor +
                            '</label>' +
                            '</div>' +
                            '<div class="col-md-12">' +
                            '<pre>' +
                            ' nilai A</pre>' +
                            '<textarea rows="1" cols="50" class="form-control" name="keterangan_rapor[' +
                            item
                            .id_komponen_jenis_rapor +
                            '][keterangan_a]" aria-required="true" aria-invalid="true"></textarea>' +
                            '</div>' +
                            '<div class="col-md-12">' +
                            '<pre>' +
                            ' nilai B</pre>' +
                            '<textarea rows="1" cols="50" class="form-control" name="keterangan_rapor[' +
                            item
                            .id_komponen_jenis_rapor +
                            '][keterangan_b]" aria-required="true" aria-invalid="true "></textarea>' +
                            '</div>' +
                            '<div class="col-md-12">' +
                            '<pre>' +
                            ' nilai C</pre>' +
                            '<textarea rows="1" cols="50" class="form-control" name="keterangan_rapor[' +
                            item
                            .id_komponen_jenis_rapor +
                            '][keterangan_c]" aria-required="true" aria-invalid="true"></textarea>' +
                            '</div>' +
                            '<div class="col-md-12">' +
                            '<pre>' +
                            ' nilai D</pre>' +
                            '<textarea rows="1" cols="50" class="form-control" name="keterangan_rapor[' +
                            item
                            .id_komponen_jenis_rapor +
                            '][keterangan_d]" aria-required="true" aria-invalid="true"></textarea>' +
                            '</div>';
                    });
                    $('#place').html(html);
                } else if (result['kelas'].type_rapor == '2') {
                    $('#place').append(`<div class="col-md-12">
                            <label>Keterangan1</label>
                            <textarea rows="1" cols="50" class="form-control" name="keterangan" aria-required="true" aria-invalid="true"></textarea>
                        </div>
                        <div class="col-md-12">
                            <label>Keterangan2</label>
                            <textarea rows="1" cols="50" class="form-control" name="keterangan2" aria-required="true"
                                aria-invalid="true"></textarea>
                        </div>`);
                }


            }
        });
    }
</script>

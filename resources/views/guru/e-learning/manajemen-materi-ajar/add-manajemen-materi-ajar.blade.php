<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#e-learning/manajemen-materi-ajar') }}"><i
                    class="material-icons">keyboard_backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>
                        TAMBAH MATERI AJAR
                    </h2>
                </div>
                <div class="body">

                    <form id="form-upload" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-manajemen-materi-ajar/add/0') }}"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="row clearfix">

                            <div class="col-md-4">
                                <label>Jurusan</label>
                                <select class="form-control show-tick" name="id_jurusan" id="jurusan">
                                    <option selected disabled>-- Pilih Kelas --</option>
                                    @foreach ($list_jurusan as $r)
                                        <option value="{{ $r->id_jurusan }}">{{ $r->nm_jurusan }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Kelas</label>
                                <select class="form-control show-tick" name="kelas" id="kelas">
                                    <option selected disabled>-- Pilih Kelas --</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Status</label>
                                <select class="form-control show-tick" name="status">
                                    <option value="0">Tidak Aktif</option>
                                    <option value="1">Aktif</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label>Mata Pelajaran</label>
                                <select class="form-control show-tick" name="id_mata_pelajaran">
                                    @foreach ($list_mapel as $r)
                                        <option value="{{ $r->id_mata_pelajaran }}">{{ $r->nm_mata_pelajaran }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label>Judul Materi</label>
                                <input type="text" class="form-control" name="judul_materi" aria-invalid="true">
                            </div>

                        </div>

                        <hr>

                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Pilih Upload File Dari Mana</label>
                                <div class="demo-radio-button">
                                    <input name="file_from" onchange="change_file_from()" type="radio" id="radio_1"
                                        value="1" checked="" class="with-gap" />
                                    <label for="radio_1">File Dari Komputer</label>
                                    <input name="file_from" onchange="change_file_from()" type="radio" id="radio_2"
                                        value="2" class="with-gap" />
                                    <label for="radio_2">Dari Link</label>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">

                            <div class="col-md-5">
                                <label>Keterangan</label>
                                <input type="text" class="form-control" name="nm_file[]" required=""
                                    aria-required="true" aria-invalid="true">
                            </div>

                            <div class="col-md-5" style="display:none;" id="place_link">
                                <label>Link</label>
                                <input type="text" class="form-control" name="link[]" aria-required="true"
                                    required="" aria-invalid="true" />
                            </div>

                            <div class="col-md-5" id="place_file">
                                <label>File ( pdf, ppt, docx, xlsx | max 10 mb )</label>
                                <input type="file" class="form-control" name="file[]" required=""
                                    aria-required="true" aria-invalid="true" accept=".pdf, .doc, .docx, .ppt, .xlsx">
                            </div>

                            <div class="col-md-2" style="margin-top: 23px;" id="tambah_file">
                                <button class="btn btn-success btn-block" type="button"><i
                                        class="material-icons">add</i> Tambah</button>
                            </div>

                            <div class="col-md-2" style="display:none; margin-top: 23px;" id="tambah_link">
                                <button class="btn btn-success btn-block" type="button"><i
                                        class="material-icons">add</i> Tambah</button>
                            </div>

                        </div>

                        <div id="place">

                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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

<script type="text/javascript">
    $(document).ready(function() {
        $('select').select();
    });



    var modul_url = 'e-learning';

    $('#jurusan').on('change', function(e) {
        console.log(e);
        var id_jurusan = e.target.value;
        // alert(base_url + '/' + role_url + '/' + modul_url + '/' + 'manajemen-materi-ajar/get-kelas/' + id_jurusan);
        $.get(base_url + '/' + role_url + '/' + modul_url + '/' + 'manajemen-materi-ajar/get-kelas/' +
            id_jurusan,
            function(data) {
                console.log(data);
                $('#kelas').empty();

                $('#kelas').append($("<option>")
                    .text("-- Pilih Kelas --")
                );
                $.each(data, function(index, kelas) {
                    $('#kelas').append($("<option>")
                        .attr("value", kelas.id_kelas)
                        .text(kelas.nm_kelas)
                    );
                })

                $('select').select();
            });
    });

    function change_file_from() {

        var x = $("input[name='file_from']:checked").val()

        if (x == 1) {
            $('#place_file').show();
            $('#tambah_file').show();
            $('#tambah_link').hide();
            $('#place_link').hide();
            $('#form-upload')[0].reset();
        } else {
            $('#place_file').hide();
            $('#tambah_file').hide();
            $('#tambah_link').show();
            $('#place_link').show();
        }

    }

    $('#form-upload').validate({
        rules: {
            'checkbox': {
                required: true
            },
            'gender': {
                required: true
            }
        },
        highlight: function(input) {
            $(input).parents('.form-group').addClass('error');
        },
        unhighlight: function(input) {
            $(input).parents('.form-group').removeClass('error');
        },
        errorPlacement: function(error, element) {
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
                    if (response.status == 200) {
                        vex.dialog.alert(response.message);
                    } else if (response.status == 201) {
                        vex.dialog.alert(response.message);
                        window.location.href = response.link;
                    } else if (response.status == 202) {
                        vex.dialog.alert(response.message);
                        loadURI(response.path);
                        // } else if (response.status == 203) {
                        //     vex.dialog.alert(response.message);
                        //     primary_table.ajax.reload(null, false);
                    } else if (response.status == 204) {
                        loadURI(response.path);
                    } else if (response.status == 300) {
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

    $('#tambah_file').click(function() {

        $('#place').append(`

            <div class="row clearfix">

            <div class="col-md-5">
                <label>Keterangan</label>
                <input type="text" class="form-control" name="nm_file[]" required="" aria-required="true" aria-invalid="true">
            </div>

            <div class="col-md-5">
                <label>File</label>
                <input type="file" class="form-control" name="file[]" required="" aria-required="true" aria-invalid="true">
            </div>

            <div class="col-md-2" style="margin-top: 23px;" >
                <button class="btn btn-danger btn-block delete_file" type="button"><i class="material-icons">delete</i> Hapus</button>
            </div>

            </div>
        `);

    })

    $('#tambah_link').click(function() {

        $('#place').append(`

    <div class="row clearfix">

    <div class="col-md-5">
        <label>Keterangan</label>
        <input type="text" class="form-control" name="nm_file[]" required="" aria-required="true" aria-invalid="true">
    </div>

    <div class="col-md-5">
        <label>Link</label>
        <input type="text" class="form-control" name="link[]" aria-required="true" required=""
        aria-invalid="true" />
    </div>

    <div class="col-md-2" style="margin-top: 23px;">
        <button class="btn btn-danger btn-block delete_file" type="button"><i class="material-icons">delete</i> Hapus</button>
    </div>

    </div>
`);

    })

    $("#place").on("click", ".delete_file", function() {
        $(this).parent().parent().remove();
    })
</script>

<style>
    .block-header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>

<div class="container-fluid">
    <div class="block-header-container">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link"
                    href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i
                        class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="block-header">
            <button class="btn bg-blue waves-effect target-link" data-toggle="modal" data-target="#massUploadModal">
                <i class="material-icons">file_upload</i><span>Upload Excel</span>
            </button>
        </div>
    </div>

    {{-- Modal upload file --}}
    <div id="massUploadModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">Upload Excel</h4>
                </div>
                <div class="modal-body">
                    <p>Silakan unduh template dan unggah file yang sudah diisi:</p>
                    <a href="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/download-template') }}"
                        class="btn btn-success">
                        <i class="material-icons">cloud_download</i> Download Template
                    </a>
                    <hr>
                    <p>
                        Petunjuk pengisian:
                    </p>
                    <ul>
                        <li>Kolom Jenis Aktivitas diisi dengan: Harian, Mingguan, Bulanan atau Insidentil</li>
                        <li>Nilai Aktivitas diisi dengan angka</li>
                        <li>Nilai Karakter yang lebih dari satu dipisahkan dengan koma</li>
                        <li>Jika dinilai oleh Guru maka kolom guru diisi angka 1</li>
                        <li>Jika dinilai oleh Siswa maka kolom siswa diisi 1, jika tidak maka diisi 0
                        </li>
                    </ul>
                    <form id="form-simpan-excel"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="uploadFile">Pilih file untuk diunggah:</label>
                            <input type="file" id="uploadFile" name="file" class="form-control">
                        </div>
                        <button type="button" onclick="prosesUpload()" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- BODY --}}
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH AKTIFITAS REWARD SISWA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-upload" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/save-add-aktivitas-reward-siswa') }}">
                        @csrf

                        <h2 class="card-inside-title">
                            Jenis Aktivitas Reward Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control" id="jenis_aktivitas_reward" name="jenis_aktivitas_reward">
                                    <option value="" selected disabled>-- Pilih Jenis Aktivitas Reward Siswa --
                                    </option>
                                    @forelse ($data_jenis_aktivitas as $row)
                                        <option value="{{ $row->id_jenis_aktivitas_reward }}">
                                            {{ $row->nm_jenis_aktivitas_reward }}</option>
                                    @empty
                                        <option value="">Tidak ada data</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Penilai
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="checkbox" id="guru" name="guru" value="1">
                                <label for="guru">Guru</label>
                                <br>
                                <input type="checkbox" id="siswa" name="siswa" value="1">
                                <label for="siswa">Siswa</label>
                            </div>
                        </div>
                        <input type="hidden" id="is_guru" name="is_guru">
                        <input type="hidden" id="is_siswa" name="is_siswa">
                        <h2 class="card-inside-title">
                            Nama Aktivitas Reward Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_aktivitas_reward" required=""
                                    aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nilai Aktivitas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="nilai_aktivitas" required=""
                                    aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Karakter yang ditanamkan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="checkbox" id="disiplin" name="nilai_karakter" value="Disiplin">
                                <label for="disiplin">Disiplin</label>
                                <br>
                                <input type="checkbox" id="religius" name="nilai_karakter" value="Religius">
                                <label for="religius">Religius</label>
                                <br>
                                <input type="checkbox" id="tangguh" name="nilai_karakter"
                                    value="Tangguh dan Tanggung Jawab">
                                <label for="tangguh">Tangguh dan Tanggung Jawab</label>
                                <br>
                                <input type="checkbox" id="peduli" name="nilai_karakter" value="Peduli">
                                <label for="peduli">Peduli</label>
                                <br>
                                <input type="checkbox" id="komunikasi" name="nilai_karakter" value="Komunikasi">
                                <label for="komunikasi">Komunikasi</label>
                                <br>
                                <input type="checkbox" id="kolaborasi" name="nilai_karakter" value="Kolaborasi">
                                <label for="kolaborasi">Kolaborasi</label>
                                <br>
                                <input type="checkbox" id="kritis" name="nilai_karakter"
                                    value="Kritis dan Pemecahan Masalah">
                                <label for="kritis">Kritis dan Pemecahan Masalah</label>
                                <br>
                                <input type="checkbox" id="kreatif" name="nilai_karakter"
                                    value="Kreatif dan Inovatif">
                                <label for="kreatif">Kreatif dan Inovatif</label>
                                <br>
                                <input type="checkbox" id="jujur" name="nilai_karakter" value="Kejujuran">
                                <label for="jujur">Kejujuran</label>
                            </div>
                        </div>
                        <input type="hidden" id="nilai_karakter_checked" name="nilai_karakter" required=""
                            aria-required="true" aria-invalid="true">
                        <h2 class="card-inside-title">
                            Status
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control" id="status" name="status" required=""
                                    aria-required="true" aria-invalid="true">
                                    <option selected disabled>Pilih Status</option>
                                    <option value="1">Aktif</option>
                                    <option value="0">Non Aktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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

<script>
    $('#form-upload').submit(function(e) {
        e.preventDefault();
    }).validate({
        highlight: function(input) {
            $(input).addClass('is-danger');
        },
        unhighlight: function(input) {
            $(input).removeClass('is-danger');
        },
        errorPlacement: function(error, element) {
            $(element).parents('.control').addClass('help').addClass('is-danger').append(error);
        },
        submitHandler: function(form) {
            // handle input penilai
            if ($('#guru').is(':checked')) {
                $('#is_guru').val(1);
            } else {
                $('#is_guru').val(0);
            }

            if ($('#siswa').is(':checked')) {
                $('#is_siswa').val(1);
            } else {
                $('#is_siswa').val(0);
            }

            // handle input nilai karakter
            var karakter = [];

            $('input[name="nilai_karakter"]:checked').each(function() {
                karakter.push($(this).val());
            });

            $('#nilai_karakter_checked').val(karakter.join('#'));

            // Disable button and send the form via AJAX
            $('button').attr('disabled', 'disabled');

            setTimeout(() => {
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
                            console.log("Redirecting to:", response.path);
                            loadURI(response.path);
                        } else if (response.status == 203) {
                            vex.dialog.alert(response.message);
                            primary_table.ajax.reload(null, false);
                        } else if (response.status == 204) {
                            loadURI(response.path);
                        } else if (response.status == 300) {
                            vex.dialog.alert(response.message);
                        }
                        console.log('Server response:', response);
                    },
                    complete: function() {
                        $('button').removeAttr('disabled');
                    }
                });

            }, 1000);
        }
    });

    function prosesUpload() {
        let file = $('#uploadFile').val()
        let url = "{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/upload-file') }}"
        let data = new FormData($('#form-simpan-excel')[0]);
        
        $.ajax({
            url: url,
            type: 'POST',
            enctype: 'multipart/form-data',
            data: data,
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
                    console.log("Redirecting to:", response.path);
                    loadURI(response.path);
                    window.location.reload();
                } else if (response.status == 203) {
                    vex.dialog.alert(response.message);
                    primary_table.ajax.reload(null, false);
                } else if (response.status == 204) {
                    loadURI(response.path);
                } else if (response.status == 300) {
                    vex.dialog.alert(response.message);
                }
                console.log('Server response:', response);
            },
            complete: function() {
                $('button').removeAttr('disabled');
            }
        });
    }
</script>

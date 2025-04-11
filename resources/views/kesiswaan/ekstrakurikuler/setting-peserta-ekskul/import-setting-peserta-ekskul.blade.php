<div class="container-fluid">
    <div class="block-header mb-4">
        <h2>
            <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#ekstrakurikuler/setting-peserta-ekskul/view-ekskul/' . $id_semester . '/' . $id_ekskul) }}">
                <i class="material-icons">backspace</i>
                <span>Kembali</span>
            </a>
        </h2>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-4">
            <div class="card">
                <div class="header">
                    <h2>UPLOAD DATA SISWA EKSKUL</h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
                            <h2 class="card-inside-title">Upload File Excel</h2>
                            <form id="form-upload-excel" method="POST"
                                action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-setting-peserta-ekskul/add/' . $id_ekskul) }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <p>Pilih File Excel</p>
                                <input type="file" name="file-excel" id="file-excel"
                                    accept=".xls,.xlsx,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                <br>
                                <div class="col-xs-6 col-sm-6 col-md-6 mt-3">
                                    <button id="btn-upload" class="btn btn-block bg-blue waves-effect" type="button">
                                        <i class="material-icons">cloud_upload</i>
                                        <span>Upload File Excel</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
                            <h2 class="card-inside-title">Petunjuk</h2>
                            <h5>Format susunan file Excel sebagai berikut:</h5>
                            <ul>
                                <li><strong>NIS</strong>: Nomor Induk Siswa</li>
                                <li><strong>Nama Lengkap</strong>: Nama Lengkap Siswa</li>
                                <li><strong>Kelas</strong>: Kelas Siswa (pastikan penulisan sesuai Nama Kelas)</li>
                            </ul>
                            <br>
                            <a href="{{ route('ekstrakurikuler/download-file-excel') }}">
                                <button class="btn btn-block bg-green waves-effect" type="button">
                                    <i class="material-icons">file_download</i>
                                    <span>Download Template Excel</span>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="data-gagal" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-4" style="margin-top: 20px; display: none;">
            <div class="card">
                <div class="header">
                    <h2>DATA SISWA EKSKUL GAGAL IMPORT</h2>
                    <p style="margin-bottom: 0; color: red;">*Data siswa tidak terdaftar di sistem</p>
                    <p style="color: red;">*Silahkan periksa kembali NIS siswa</p>
                </div>
                <div class="body">
                    <div class="table-responsive mb-3">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script AJAX --}}
@include('scriptjs')

<script>
    $('#btn-upload').on('click', function () {
        var form = $('#form-upload-excel')[0];
        var data = new FormData(form);

        if (!$('#file-excel').val()) {
            vex.dialog.alert("Silakan pilih file Excel terlebih dahulu.");
            return;
        }

        $('button').attr('disabled', 'disabled');

        $.ajax({
            url: $('#form-upload-excel').attr('action'),
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.status == 200) {
                    vex.dialog.alert(response.message);
                } else if (response.status == 201) {
                    vex.dialog.alert(response.message);
                    window.location.href = response.link;
                } else if (response.status == 202) {
                    if (response.nis_gagal && response.nis_gagal.length > 0) {
                        vex.dialog.alert(response.message + '. \nNamun ada data siswa yang gagal');
                        $('#data-gagal').css('display', 'block');
                        let dataSet = [];
                        response.nis_gagal.forEach((nis, index) => {
                            dataSet.push({
                                no: index + 1,
                                nis: nis,
                            });
                        });
                        if ($.fn.DataTable.isDataTable('#primary_table')) {
                            $('#primary_table').DataTable().clear().destroy();
                        }

                        $('#primary_table').DataTable({
                            data: dataSet,
                            columns: [
                                { data: 'no' },
                                { data: 'nis' },
                            ]
                        });
                    } else {
                        vex.dialog.alert(response.message);
                    }
                } else if (response.status == 203) {
                    vex.dialog.alert(response.message);
                } else if (response.status == 204) {
                    loadURI(response.path);
                } else if (response.status == 300) {
                    vex.dialog.alert(response.message);
                } else {
                    vex.dialog.alert(response.message || "Terjadi kesalahan saat upload.");
                }
            },
            error: function (xhr) {
                vex.dialog.alert("Gagal upload file. Pastikan format file sesuai.");
            },
            complete: function () {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    });
</script>
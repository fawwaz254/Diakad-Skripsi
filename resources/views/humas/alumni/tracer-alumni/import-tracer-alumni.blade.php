<div class="container-fluid">
    <div class="block-header mb-4">
        <h2>
            <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/tracer-alumni') }}">
                <i class="material-icons">backspace</i>
                <span>Kembali</span>
            </a>
        </h2>
    </div>

    <!-- STATUS SELECTION -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-4">
            <div class="card">
                <div class="header">
                    <h2>PILIH STATUS ALUMNI</h2>
                    <small>Pilih status alumni yang akan diimport</small>
                </div>
                <div class="body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">Status Alumni</h2>
                            <div class="form-group">
                                <input class="with-gap radio-col-light-green form-control validate" type="radio"
                                    name="status_import" value="bekerja" id="status_bekerja">
                                <label for="status_bekerja">Alumni Bekerja</label>

                                <input class="with-gap radio-col-light-green form-control validate" type="radio"
                                    name="status_import" value="usaha" id="status_usaha">
                                <label for="status_usaha">Alumni Wirausaha</label>

                                <input class="with-gap radio-col-light-green form-control validate" type="radio"
                                    name="status_import" value="kuliah" id="status_kuliah">
                                <label for="status_kuliah">Alumni Kuliah</label>

                                <input class="with-gap radio-col-light-green form-control validate" type="radio"
                                    name="status_import" value="menunggu" id="status_menunggu">
                                <label for="status_menunggu">Alumni Menunggu</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM IMPORT ALUMNI BEKERJA -->
    <div class="form_import_layout" id="import_bekerja" style="display: none;">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-4">
                <div class="card">
                    <div class="header">
                        <h2>UPLOAD DATA ALUMNI BEKERJA</h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
                                <h2 class="card-inside-title">Upload File Excel Alumni Bekerja</h2>
                                <form id="form-upload-excel-bekerja" method="POST" action="{{ url('humas/alumni/tracer-alumni/handle-import') }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <p>Pilih File Excel</p>
                                    <input type="file" name="file-excel" id="file-excel-bekerja" accept=".xls,.xlsx" required>
                                    <input type="hidden" name="status" value="bekerja">
                                    <br>
                                    <div class="col-xs-6 col-sm-6 col-md-6 mt-3">
                                        <button id="btn-upload-bekerja" class="btn btn-block bg-blue waves-effect" type="button">
                                            <i class="material-icons">cloud_upload</i>
                                            <span>Upload File Excel</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
                                <h2 class="card-inside-title">Petunjuk</h2>
                                <h5>Format susunan file Excel ALUMNI BEKERJA sebagai berikut:</h5>
                                <ul>
                                    <li><strong>NIS</strong>: Nomor Induk Siswa</li>
                                    <li><strong>Nama Lengkap</strong>: Nama Lengkap Siswa</li>
                                    <li><strong>Kelas</strong>: Kelas Siswa (Penulisan sesuai Nama Kelas dan Gunakan huruf <strong>KAPITAL</strong>)</li>
                                    <li><strong>Jurusan</strong>: Jurusan Siswa (Gunakan huruf <strong>KAPITAL</strong>)</li>
                                    <li><strong>Tahun Lulus</strong>: Tahun Lulus Sekolah</li>
                                    <li><strong>Nomor Telepon/HP/WA</strong>: Nomor Telepon Siswa</li>
                                    <li><strong>Email</strong>: Email siswa</li>
                                    <li><strong>Alamat</strong>: Alamat Siswa</li>
                                    <li><strong>Alamat URL Instagram / Facebook</strong>: Link Profile Instagram / Facebook Siswa</li>
                                    <li><strong>Nama Instansi</strong>: Nama Instansi Tempat Bekerja</li>
                                    <li><strong>Alamat Instansi</strong>: Alamat lengkap Instansi</li>
                                    <li><strong>Kontak Instansi</strong>: Kontak/Nomor Instansi Yang Bisa Dihubungi</li>
                                    <li><strong>Bidang Usaha</strong>: Bidang Usaha Kerja</li>
                                    <li><strong>Tahun Masuk</strong>: Tahun Masuk Bekerja</li>
                                    <li><strong>Kapan Mulai Bekerja</strong>: Ketik : Sebelum Terima Ijazah / Sesudah Terima Ijazah</li>
                                    <li><strong>Lama Bekerja</strong>: Masukkan Dalam Hitungan Bulan</li>
                                </ul>
                                <br>
                                <a href="{{ route('alumnibekerja/download-file-excel') }}">
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
        </div>
    </div>

    <!-- FORM IMPORT ALUMNI WIRAUSAHA -->
    <div class="form_import_layout" id="import_usaha" style="display: none;">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-4">
                <div class="card">
                    <div class="header">
                        <h2>UPLOAD DATA ALUMNI WIRAUSAHA</h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
                                <h2 class="card-inside-title">Upload File Excel Alumni Wirausaha</h2>
                                <form id="form-upload-excel-usaha" method="POST" action="{{ url('humas/alumni/tracer-alumni/handle-import') }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <p>Pilih File Excel</p>
                                    <input type="file" name="file-excel" id="file-excel-usaha" accept=".xls,.xlsx" required>
                                    <input type="hidden" name="status" value="usaha">
                                    <br>
                                    <div class="col-xs-6 col-sm-6 col-md-6 mt-3">
                                        <button id="btn-upload-usaha" class="btn btn-block bg-blue waves-effect" type="button">
                                            <i class="material-icons">cloud_upload</i>
                                            <span>Upload File Excel</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
                                <h2 class="card-inside-title">Petunjuk</h2>
                                <h5>Format susunan file Excel ALUMNI WIRAUSAHA sebagai berikut:</h5>
                                <ul>
                                    <li><strong>NIS</strong>: Nomor Induk Siswa</li>
                                    <li><strong>Nama Lengkap</strong>: Nama Lengkap Siswa</li>
                                    <li><strong>Kelas</strong>: Kelas Siswa (Penulisan sesuai Nama Kelas dan Gunakan huruf <strong>KAPITAL</strong>)</li>
                                    <li><strong>Jurusan</strong>: Jurusan Siswa (Gunakan huruf <strong>KAPITAL</strong>)</li>
                                    <li><strong>Tahun Lulus</strong>: Tahun Lulus Sekolah</li>
                                    <li><strong>Nomor Telepon/HP/WA</strong>: Nomor Telepon Siswa</li>
                                    <li><strong>Email</strong>: Email siswa</li>
                                    <li><strong>Alamat</strong>: Alamat Siswa</li>
                                    <li><strong>Alamat URL Instagram / Facebook</strong>: Link Profile Instagram / Facebook Siswa</li>
                                    <li><strong>Nama Usaha</strong>: Nama Usaha/Bisnis</li>
                                    <li><strong>Alamat Usaha</strong>: Alamat lengkap Usaha</li>
                                    <li><strong>Kontak Usaha</strong>: Kontak/Nomor Usaha Yang Bisa Dihubungi</li>
                                    <li><strong>Bidang Usaha</strong>: Bidang Usaha yang dijalankan</li>
                                    <li><strong>Tahun Mulai Usaha</strong>: Tahun Mulai Berwirausaha</li>
                                    <li><strong>Kapan Mulai Usaha</strong>: Ketik : Sebelum Terima Ijazah / Sesudah Terima Ijazah</li>
                                    <li><strong>Lama Usaha</strong>: Masukkan Dalam Hitungan Bulan</li>
                                </ul>
                                <br>
                                <a href="{{ route('alumniwirausaha/download-file-excel') }}">
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
        </div>
    </div>

    <!-- FORM IMPORT ALUMNI KULIAH -->
    <div class="form_import_layout" id="import_kuliah" style="display: none;">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-4">
                <div class="card">
                    <div class="header">
                        <h2>UPLOAD DATA ALUMNI KULIAH</h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
                                <h2 class="card-inside-title">Upload File Excel Alumni Kuliah</h2>
                                <form id="form-upload-excel-kuliah" method="POST" action="{{ url('humas/alumni/tracer-alumni/handle-import') }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <p>Pilih File Excel</p>
                                    <input type="file" name="file-excel" id="file-excel-kuliah" accept=".xls,.xlsx" required>
                                    <input type="hidden" name="status" value="kuliah">
                                    <br>
                                    <div class="col-xs-6 col-sm-6 col-md-6 mt-3">
                                        <button id="btn-upload-kuliah" class="btn btn-block bg-blue waves-effect" type="button">
                                            <i class="material-icons">cloud_upload</i>
                                            <span>Upload File Excel</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
                                <h2 class="card-inside-title">Petunjuk</h2>
                                <h5>Format susunan file Excel ALUMNI KULIAH sebagai berikut:</h5>
                                <ul>
                                    <li><strong>NIS</strong>: Nomor Induk Siswa</li>
                                    <li><strong>Nama Lengkap</strong>: Nama Lengkap Siswa</li>
                                    <li><strong>Kelas</strong>: Kelas Siswa (Penulisan sesuai Nama Kelas dan Gunakan huruf <strong>KAPITAL</strong>)</li>
                                    <li><strong>Jurusan</strong>: Jurusan Siswa (Gunakan huruf <strong>KAPITAL</strong>)</li>
                                    <li><strong>Tahun Lulus</strong>: Tahun Lulus Sekolah</li>
                                    <li><strong>Nomor Telepon/HP/WA</strong>: Nomor Telepon Siswa</li>
                                    <li><strong>Email</strong>: Email siswa</li>
                                    <li><strong>Alamat</strong>: Alamat Siswa</li>
                                    <li><strong>Alamat URL Instagram / Facebook</strong>: Link Profile Instagram / Facebook Siswa</li>
                                    <li><strong>Nama Perguruan Tinggi</strong>: Nama Universitas/Perguruan Tinggi</li>
                                    <li><strong>Alamat Perguruan Tinggi</strong>: Alamat lengkap Perguruan Tinggi</li>
                                    <li><strong>Kontak Perguruan Tinggi</strong>: Kontak/Nomor Perguruan Tinggi</li>
                                    <li><strong>Fakultas</strong>: Nama Fakultas</li>
                                    <li><strong>Program Studi</strong>: Nama Program Studi/Jurusan</li>
                                    <li><strong>Jenjang</strong>: Jenjang Pendidikan (D1/D2/D3/D4/S1/S2)</li>
                                    <li><strong>Tahun Masuk</strong>: Tahun Masuk Kuliah</li>
                                    <li><strong>Kapan Mulai Kuliah</strong>: Ketik : Sebelum Terima Ijazah / Sesudah Terima Ijazah</li>
                                </ul>
                                <br>
                                <a href="{{ route('alumnikuliah/download-file-excel') }}">
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
        </div>
    </div>

    <!-- FORM IMPORT ALUMNI MENUNGGU -->
    <div class="form_import_layout" id="import_menunggu" style="display: none;">
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-4">
                <div class="card">
                    <div class="header">
                        <h2>UPLOAD DATA ALUMNI MENUNGGU</h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
                                <h2 class="card-inside-title">Upload File Excel Alumni Menunggu</h2>
                                <form id="form-upload-excel-menunggu" method="POST" action="{{ url('humas/alumni/tracer-alumni/handle-import') }}" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <p>Pilih File Excel</p>
                                    <input type="file" name="file-excel" id="file-excel-menunggu" accept=".xls,.xlsx" required>
                                    <input type="hidden" name="status" value="menunggu">
                                    <br>
                                    <div class="col-xs-6 col-sm-6 col-md-6 mt-3">
                                        <button id="btn-upload-menunggu" class="btn btn-block bg-blue waves-effect" type="button">
                                            <i class="material-icons">cloud_upload</i>
                                            <span>Upload File Excel</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12 mb-3">
                                <h2 class="card-inside-title">Petunjuk</h2>
                                <h5>Format susunan file Excel ALUMNI BELUM BEKERJA sebagai berikut:</h5>
                                <ul>
                                    <li><strong>NIS</strong>: Nomor Induk Siswa</li>
                                    <li><strong>Nama Lengkap</strong>: Nama Lengkap Siswa</li>
                                    <li><strong>Kelas</strong>: Kelas Siswa (Penulisan sesuai Nama Kelas dan Gunakan huruf <strong>KAPITAL</strong>)</li>
                                    <li><strong>Jurusan</strong>: Jurusan Siswa (Gunakan huruf <strong>KAPITAL</strong>)</li>
                                    <li><strong>Tahun Lulus</strong>: Tahun Lulus Sekolah</li>
                                    <li><strong>Nomor Telepon/HP/WA</strong>: Nomor Telepon Siswa</li>
                                    <li><strong>Email</strong>: Email siswa</li>
                                    <li><strong>Alamat</strong>: Alamat Siswa</li>
                                    <li><strong>Alamat URL Instagram / Facebook</strong>: Link Profile Instagram / Facebook Siswa</li>
                                    <li><strong>Status Menunggu</strong>: Ketik : Mencari Kerja / Mempersiapkan Diri Masuk Perguruan Tinggi</li>
                                </ul>
                                <br>
                                <a href="{{ route('alumnimenunggu/download-file-excel') }}">
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
        </div>
    </div>

    <!-- DATA BERHASIL IMPORT -->
    <div id="data-berhasil" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-4" style="margin-top: 20px; display: none;">
        <div class="card">
            <div class="header bg-light-green">
                <h2 style="color: white;">
                    <i class="material-icons">check_circle</i>
                    DATA ALUMNI BERHASIL IMPORT
                </h2>
                <p style="margin-bottom: 0; color: white;">✓ Data alumni berhasil ditambahkan ke sistem</p>
            </div>
            <div class="body">
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                        id="success_table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Lengkap</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- DATA GAGAL IMPORT -->
    <div id="data-gagal" class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mb-4" style="margin-top: 20px; display: none;">
        <div class="card">
            <div class="header bg-red">
                <h2 style="color: white;">
                    <i class="material-icons">error</i>
                    DATA ALUMNI GAGAL IMPORT
                </h2>
                <p style="margin-bottom: 0; color: white;">✗ Data alumni tidak terdaftar di sistem</p>
                <p style="color: white;">✗ Silahkan periksa kembali NIS alumni</p>
            </div>
            <div class="body">
                <div class="table-responsive mb-3">
                    <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                        id="failed_table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Lengkap</th>
                                <th>Alasan Gagal</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.32/sweetalert2.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.7.32/sweetalert2.all.min.js"></script>

{{-- Script AJAX --}}
@include('scriptjs')

<script>
    $(document).ready(function() {
        $('#data-berhasil').hide();
        $('#data-gagal').hide();
    });

    $("input[name='status_import']").change(function() {
        toggleImportForm(this.value);
    });

    function toggleImportForm(status) {
        $('.form_import_layout').hide();
        $('#data-berhasil').hide();
        $('#data-gagal').hide();

        switch (status) {
            case 'bekerja':
                $('#import_bekerja').show();
                break;
            case 'usaha':
                $('#import_usaha').show();
                break;
            case 'kuliah':
                $('#import_kuliah').show();
                break;
            case 'menunggu':
                $('#import_menunggu').show();
                break;
        }
    }

    $('#btn-upload-bekerja').on('click', function() {
        handleUpload('#form-upload-excel-bekerja', '#file-excel-bekerja');
    });

    $('#btn-upload-usaha').on('click', function() {
        handleUpload('#form-upload-excel-usaha', '#file-excel-usaha');
    });

    $('#btn-upload-kuliah').on('click', function() {
        handleUpload('#form-upload-excel-kuliah', '#file-excel-kuliah');
    });

    $('#btn-upload-menunggu').on('click', function() {
        handleUpload('#form-upload-excel-menunggu', '#file-excel-menunggu');
    });

    function handleUpload(formSelector, fileSelector) {
        var form = $(formSelector)[0];
        var data = new FormData(form);

        if (!$(fileSelector).val()) {
            Swal.fire({
                icon: 'warning',
                title: 'File Tidak Dipilih',
                text: 'Silakan pilih file Excel terlebih dahulu.',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        Swal.fire({
            title: 'Mengimpor Data...',
            text: 'Mohon tunggu, sedang memproses file Excel',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $('button').attr('disabled', 'disabled');

        $.ajax({
            url: $(formSelector).attr('action'),
            type: 'POST',
            data: data,
            processData: false,
            contentType: false,
            success: function(response) {
                Swal.close();
                
                if (response.status == 200) {
                    showSuccessAlert(response);
                    displaySuccessTable(response.nis_berhasil);
                    
                } else if (response.status == 202) {
                    showMixedAlert(response);
                    displaySuccessTable(response.nis_berhasil);
                    displayFailedTable(response.nis_gagal);
                    
                } else if (response.status == 300) {
                    showFailedAlert(response);
                    displayFailedTable(response.nis_gagal);
                    
                } else if (response.status == 201) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        if (response.link) {
                            window.location.href = response.link;
                        }
                    });
                    
                } else if (response.status == 204) {
                    loadURI(response.path);
                    
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: response.message || "Terjadi kesalahan saat upload.",
                        confirmButtonColor: '#dc3545'
                    });
                }
            },
            error: function(xhr) {
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Upload Gagal',
                    text: 'Gagal upload file. Pastikan format file sesuai.',
                    confirmButtonColor: '#dc3545'
                });
            },
            complete: function() {
                $('button').removeAttr('disabled');
            }
        });
    }

    function showSuccessAlert(response) {
        Swal.fire({
            icon: 'success',
            title: 'Import Berhasil!',
            html: `
                <div style="text-align: left; margin: 20px 0;">
                    <h4>📊 Ringkasan Import:</h4>
                    <p>✅ <b>Total Data:</b> ${response.summary.total_data}</p>
                    <p>🎉 <b>Berhasil:</b> ${response.summary.berhasil}</p>
                    <p>❌ <b>Gagal:</b> ${response.summary.gagal}</p>
                </div>
                <p><b>${response.message}</b></p>
            `,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'OK, Lanjutkan'
        }).then(() => {
            scrollToResults();
        });
    }

    function showMixedAlert(response) {
        Swal.fire({
            icon: 'warning',
            title: 'Import Selesai dengan Catatan',
            html: `
                <div style="text-align: left; margin: 20px 0;">
                    <h4>📊 Ringkasan Import:</h4>
                    <p>📈 <b>Total Data:</b> ${response.summary.total_data}</p>
                    <p>✅ <b>Berhasil:</b> ${response.summary.berhasil}</p>
                    <p>❌ <b>Gagal:</b> ${response.summary.gagal}</p>
                </div>
                <p><b>${response.message}</b></p>
                <p style="color: #856404;">⚠️ Silakan periksa tabel di bawah untuk detail data yang gagal diimpor.</p>
            `,
            confirmButtonColor: '#ffc107',
            confirmButtonText: 'OK, Periksa Detail'
        }).then(() => {
            scrollToResults();
        });
    }
    
    function showFailedAlert(response) {
        Swal.fire({
            icon: 'error',
            title: 'Import Gagal!',
            html: `
                <div style="text-align: left; margin: 20px 0;">
                    <h4>📊 Ringkasan Import:</h4>
                    <p>📈 <b>Total Data:</b> ${response.summary.total_data}</p>
                    <p>✅ <b>Berhasil:</b> ${response.summary.berhasil}</p>
                    <p>❌ <b>Gagal:</b> ${response.summary.gagal}</p>
                </div>
                <p><b>${response.message}</b></p>
                <p style="color: #721c24;">⚠️ Silakan periksa tabel di bawah untuk detail data yang gagal diimpor.</p>
            `,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'OK, Periksa Detail'
        }).then(() => {
            scrollToResults();
        });
    }

    function displaySuccessTable(data) {
        if (data && data.length > 0) {
            $('#data-berhasil').show();
            $('#success_table').DataTable().destroy();
            $('#success_table tbody').empty();
            data.forEach(function(item, index) {
                $('#success_table tbody').append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.nis}</td>
                        <td>${item.nama}</td>
                        <td><span class="badge bg-green">${item.status}</span></td>
                    </tr>
                `);
            });
            
            $('#success_table').DataTable({
                responsive: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]]
            });
        }
    }

    function displayFailedTable(data) {
        if (data && data.length > 0) {
            $('#data-gagal').show();

            $('#failed_table').DataTable().destroy();
            $('#failed_table tbody').empty();

            data.forEach(function(item, index) {
                $('#failed_table tbody').append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.nis}</td>
                        <td>${item.nama}</td>
                        <td><span class="badge bg-red">${item.alasan}</span></td>
                    </tr>
                `);
            });

            $('#failed_table').DataTable({
                responsive: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]]
            });
        }
    }

    function loadURI(path) {
        if (path) {
            window.location.href = path;
        }
    }

    function validateFile(fileInput) {
        const file = fileInput.files[0];
        const maxSize = 5 * 1024 * 1024; // MAX FILE SIZE 5MB
        const allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];
        
        if (!file) {
            return { valid: false, message: 'Silakan pilih file terlebih dahulu.' };
        }
        
        if (file.size > maxSize) {
            return { valid: false, message: 'Ukuran file terlalu besar. Maksimal 5MB.' };
        }
        
        if (!allowedTypes.includes(file.type)) {
            return { valid: false, message: 'Format file tidak didukung. Gunakan file .xlsx atau .xls' };
        }
        
        return { valid: true };
    }

    $('#file-excel-bekerja, #file-excel-usaha, #file-excel-kuliah, #file-excel-menunggu').on('change', function() {
        const validation = validateFile(this);
        const fileName = this.files[0] ? this.files[0].name : '';
        
        if (!validation.valid) {
            Swal.fire({
                icon: 'warning',
                title: 'File Tidak Valid',
                text: validation.message,
                confirmButtonColor: '#ffc107'
            });
            $(this).val('');
        } else {
            const label = $(this).next('label').length ? $(this).next('label') : $(this).parent().find('p');
            if (fileName) {
                label.html(`<i class="material-icons" style="color: green;">check_circle</i> File dipilih: <strong>${fileName}</strong>`);
            }
        }
    });

    $("input[name='status_import']").change(function() {
        $('#file-excel-bekerja, #file-excel-usaha, #file-excel-kuliah, #file-excel-menunggu').val('');
        
        $('#form-upload-excel-bekerja p, #form-upload-excel-usaha p, #form-upload-excel-kuliah p, #form-upload-excel-menunggu p').text('Pilih File Excel');
        
        $('#data-berhasil, #data-gagal').hide();
    });

    function updateUploadProgress(percent) {
        if (percent < 100) {
            Swal.update({
                html: `
                    <div style="margin: 20px 0;">
                        <p>Mengimpor Data Alumni...</p>
                        <div style="background: #f0f0f0; border-radius: 10px; padding: 3px;">
                            <div style="background: #007bff; height: 20px; border-radius: 8px; width: ${percent}%; transition: width 0.3s;"></div>
                        </div>
                        <p style="margin-top: 10px;">${percent}% selesai</p>
                    </div>
                `
            });
        }
    }

    function handleAjaxError(xhr, status, error) {
        let errorMessage = 'Terjadi kesalahan saat mengimpor data.';
        
        if (xhr.status === 413) {
            errorMessage = 'File terlalu besar. Silakan gunakan file yang lebih kecil.';
        } else if (xhr.status === 422) {
            const response = xhr.responseJSON;
            if (response && response.errors) {
                errorMessage = Object.values(response.errors).flat().join('\n');
            }
        } else if (xhr.status === 500) {
            errorMessage = 'Terjadi kesalahan server. Silakan coba lagi nanti.';
        } else if (xhr.status === 0) {
            errorMessage = 'Koneksi terputus. Periksa koneksi internet Anda.';
        }
        
        Swal.fire({
            icon: 'error',
            title: 'Upload Gagal',
            text: errorMessage,
            confirmButtonColor: '#dc3545'
        });
    }

    $(document).keydown(function(e) {
        if (e.ctrlKey && e.which === 85) {
            e.preventDefault();
            const activeStatus = $("input[name='status_import']:checked").val();
            if (activeStatus) {
                $(`#btn-upload-${activeStatus}`).click();
            }
        }
        
        if (e.which === 27) {
            Swal.close();
        }
    });

    function scrollToResults() {
        setTimeout(function() {
            if ($('#data-berhasil:visible, #data-gagal:visible').length > 0) {
                $('html, body').animate({
                    scrollTop: $('#data-berhasil:visible, #data-gagal:visible').first().offset().top - 20
                }, 500);
            }
        }, 500);
    }
</script>
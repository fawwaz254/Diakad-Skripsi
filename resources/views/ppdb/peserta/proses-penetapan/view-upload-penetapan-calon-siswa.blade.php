<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        UPLOAD DATA CALON SISWA
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            {{-- <h2 class="card-inside-title">
                              Cek File Excel
                          </h2>
                          <form id="form-upload"
                              action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/cek-file-excel') }}"
                              method="post" enctype="multipart/form-data">
                              {{ csrf_field() }}
                              Pilih File Excel
                              <input type="file" name="file-excel" id="file-excel"
                                  accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, .xls">
                              <br>
                              <div class="col-xs-6 col-sm-6 col-md-6">
                                  <button class="btn btn-block bg-green waves-effect" type="submit">
                                      <i class="material-icons">cloud_upload</i>
                                      <span>Cek File Excel</span>
                                  </button>
                              </div>
                          </form> --}}

                            <br><br><br>

                            <h2 class="card-inside-title">
                                Upload File Excel
                            </h2>
                            <form id="form-upload-calon-siswa"
                                action="{{ route('calon-siswa.post-file-excel', Request::segment(5)) }}" method="POST"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                Pilih File Excel
                                <input type="file" name="file-excel" id="file-excel"
                                    accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, .xls">
                                <br>
                                <div class="col-xs-6 col-sm-6 col-md-6">
                                    <button class="btn btn-block bg-red waves-effect" type="submit">
                                        <i class="material-icons">cloud_upload</i>
                                        <span>Upload File Excel</span>
                                    </button>
                                </div>
                            </form>

                            {{-- <br><br><br>
                          <h2 class="card-inside-title">
                              Upload Email Siswa
                          </h2>
                          <form id="form-upload3"
                              action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-file-excel-email') }}"
                              method="post" enctype="multipart/form-data">
                              {{ csrf_field() }}
                              Pilih File Excel
                              <input type="file" name="file-excel" id="file-excel"
                                  accept="application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, .xls">
                              <br>
                              <div class="col-xs-6 col-sm-6 col-md-6">
                                  <button class="btn btn-block bg-red waves-effect" type="submit">
                                      <i class="material-icons">cloud_upload</i>
                                      <span>Upload File Excel</span>
                                  </button>
                              </div>
                          </form> --}}



                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Petunjuk
                            </h2>
                            <h5>Format susunan file excel, sebagai berikut :</h5>
                            <ul>
                                <li>
                                    NISN : Nomor Induk Siswa Nasional
                                </li>
                                <li>
                                    Nama Lengkap : Nama Lengkap Siswa
                                </li>
                                <li>
                                    Jenis Kelamin : Jenis Kelamin Siswa.
                                    <br>Keterangan : Isi Dengan (<b>L</b> atau <b>P</b>)
                                </li>
                                <li>
                                    Status Siswa : Status Siswa, pastikan format penulisan benar sesuai dengan Nama
                                    Status. Contoh : Aktif. <br> <strong>Pastikan Sudah Dibuat</strong> data master
                                    melalui menu Data Akademik -> Data Status Siswa
                                </li>
                                <li>
                                    Kelas : Kelas Siswa, pastikan format penulisan benar sesuai dengan Nama Kelas.
                                    Contoh : 7-AK-1. <br> <strong>Pastikan Sudah Dibuat</strong> data master melalui
                                    menu Setting Kelas -> Data Kelas
                                </li>
                                <li>
                                    Tahun Masuk : tahun angkatan masuk 4 digit. Contoh : 2018. <br> <strong>Pastikan
                                        Sudah Dibuat</strong> Data Penerimaan dengan Jenis Penerimaan "Siswa Lama" pada
                                    Tahun tersebut melalui menu Pendaftaran -> Data Penerimaan.
                                </li>
                                <li>
                                    Semester Masuk : semester ketika siswa masuk, pastikan format penulisan benar sesuai
                                    dengan Kode Semester. Contoh : 20151. <br> <strong>Pastikan Sudah Dibuat</strong>
                                    data master melalui menu Data Akademik -> Data Nama Semester.
                                </li>
                                <li>
                                    Jalur : Jalur Masuk Siswa, pastikan format penulisan benar sesuai dengan Nama Jalur.
                                    Contoh : Reguler. <br> <strong>Pastikan Sudah Dibuat</strong> data master melalui
                                    menu Data Akademik -> Data Jalur
                                </li>
                            </ul>
                            <a href="{{ route('calon-siswa/download-file-excel') }}">
                                <button class="btn btn-block bg-blue waves-effect" type="submit">
                                    <i class="material-icons">cloud_upload</i>
                                    <span>Download Template Excel</span>
                                </button>
                            </a>
                            <br>
                            {{-- <br>
                          <a href="{{ route('siswa/download-file-excel-lite') }}">
                              <button class="btn btn-block bg-green waves-effect" type="submit">
                                  <i class="material-icons">cloud_upload</i>
                                  <span>Download Template Excel Lite</span>
                              </button>
                          </a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#form-upload-calon-siswa").on("submit", function(e) {
            e.preventDefault(); // Mencegah form submit secara default

            var formData = new FormData(this); // Ambil data dari form

            $.ajax({
                url: $(this).attr("action"), // Ambil URL dari form action
                type: "POST",
                data: formData,
                processData: false, // Mencegah jQuery memproses data
                contentType: false, // Mencegah jQuery mengatur Content-Type
                beforeSend: function() {
                    $("button[type=submit]").prop("disabled", true).text("Uploading...");
                },
                success: function(response) {
                    $("button[type=submit]").prop("disabled", false).text(
                        "Upload File Excel");

                    // Menampilkan pesan sukses
                    $(".body").prepend(
                        '<div class="alert alert-success">Upload berhasil!</div>');
                },
                error: function(xhr) {
                    $("button[type=submit]").prop("disabled", false).text(
                        "Upload File Excel");

                    // Menampilkan pesan error dari server
                    var errorMessage = xhr.responseJSON?.message || "Upload gagal!";
                    $(".body").prepend('<div class="alert alert-danger">' + errorMessage +
                        '</div>');
                }
            });
        });
    });
</script>

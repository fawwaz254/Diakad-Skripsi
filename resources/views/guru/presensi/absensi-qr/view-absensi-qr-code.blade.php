<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Qr Code Scanner</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">

</head>

<body class="bg-secondary">
    <div class="row m-5">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="container-fluid">

                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="text-center">Presensi Qr Code </h4>
                        <h5 class="text-center">{{ $data_kelas->nm_kelas }} |
                            {{ $data_kelas->nm_mata_pelajaran }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <video id="preview" style="width: 100%"></video>
                    </div>
                    {{ csrf_field() }}
                    <br>
                    <div class="card-footer">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col-lg-6">Last Absent : <div id="last_siswa_absent"
                                            style="display: inline">

                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th scope="col-lg-6">Belum Absent : <div id="jumlah_siswa_belum_absent"
                                            style="display: inline">
                                            ({{ count($list_data) }})
                                        </div>
                                    </th>
                                </tr>

                            </thead>
                            <tbody>
                                <div>
                                    @foreach ($list_data as $item)
                                        <tr id="{{ $item->nis_siswa }}">
                                            <td>{{ $item->nis_siswa . ' - ' . $item->pengguna->nm_pengguna }}</td>
                                        </tr>
                                    @endforeach
                                </div>
                                <tr>
                                    <td><label>Masuk</label>
                                        <input type="text" name="sudah_absent" id="sudah_absent" readonly
                                            class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <td><label>Sakit</label>
                                        <input type="text" name="sakit_absent" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <td> <label>Izin</label>
                                        <input type="text" name="izin_absent" class="form-control">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="card-inside-title">
                                            Pertemuan pekan ke
                                        </label>
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="number" class="form-control" name="pertemuan_ke"
                                                    aria-required="true" aria-invalid="true" value="{{ $pertemuan_ke }}"
                                                    readonly>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="card-inside-title">
                                            Uraian Materi
                                        </label>
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <textarea class="form-control" name="uraian_materi" rows="4" cols="100"></textarea>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                                {{-- <tr>
                                    <th>
                                        <label class="card-inside-title">
                                            Waktu Mulai
                                        </label>
                                        <input type="text" class="datepicker-time form-control" name="waktu_mulai"
                                            required="" aria-required="true" aria-invalid="true"
                                            value="{{ $data_kelas->waktu_mulai }}">
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <label class="card-inside-title">
                                            Waktu Selesai
                                        </label>
                                        <input type="text" class="datepicker-time form-control" name="waktu_selesai"
                                            required="" aria-required="true" aria-invalid="true"
                                            value="{{ $data_kelas->waktu_selesai }}">
                                    </th>
                                </tr> --}}
                            </tbody>
                            <tr>
                                <th>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button type="button" class="btn btn-success btn-block bg-red waves-effect"
                                            type="submit" onclick="takeAction()">Save</button>
                                    </div>
                                </th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <script>
        var jumlah = '{{ count($list_data) }}';
        var sudah_absent = [];
        var base_url = '{{ url(Request::segment(1) . '/' . Request::segment(2)) }}';
        var url_action = base_url + '/' +
            'post-kbm-absensi-siswa-barcode';
        let scanner = new Instascan.Scanner({
            video: document.getElementById('preview')
        });
        Instascan.Camera.getCameras().then(function(cameras) {
            if (cameras.length > 0) {
                // console.log(cameras);
                var selectedCam = cameras[0];
                $.each(cameras, (i, c) => {
                    selectedCam = cameras[i];
                    // if (c.name.indexOf('back') != -1) {
                    //     selectedCam = c;
                    //     return false;
                    // }
                });
                // scanner.start(cameras[0]);
                scanner.start(selectedCam);
            } else {
                alert('No cameras found');
            }

        }).catch(function(e) {
            console.error(e);
        });

        scanner.addListener('scan', function(c) {

            if (!sudah_absent.includes(c)) {
                sudah_absent.push(c);
                swal({
                    title: "QR Code : " + c,
                    buttons: false,
                    timer: 2000,
                    icon: "success",
                });
                jumlah--;
                document.getElementById(c).remove();
                document.getElementById('jumlah_siswa_belum_absent').textContent = jumlah;

                document.getElementById('sudah_absent').value = sudah_absent;
            } else {
                swal({
                    title: "QR Code Sudah Absent : " + c,
                    buttons: false,
                    timer: 2000,
                    icon: "success",
                });
            }
            document.getElementById('last_siswa_absent').textContent = c;
            // console.log(jumlah);
            // console.log(sudah_absent);
        });

        function takeAction() {
            $('button').attr('disabled', 'disabled');
            var csrfToken = $('input[name="_token"]').val();
            $.ajax({
                type: "POST",
                url: url_action,
                data: {
                    id_jadwal_kelas_mp: '{{ $id_jadwal_kelas_mp }}',
                    pertemuan_ke: '{{ $pertemuan_ke }}',
                    siswa_presensi: $('input[name=sudah_absent]').val(),
                    siswa_sakit: $('input[name=sakit_absent]').val(),
                    siswa_izin: $('input[name=izin_absent]').val(),
                    uraian_materi: $('textarea[name=uraian_materi]').val(),
                    _token: csrfToken,
                },
                success: function(response) {
                    // console.log(response);
                    if (response.status == 200) {
                        swal({
                            title: response.message,
                            buttons: false,
                            timer: 2000,
                            icon: "success",
                        });
                    } else if (response.status == 201) {
                        swal({
                            title: response.message,
                            buttons: false,
                            timer: 2000,
                            icon: "success",
                        });
                        window.location = '/guru#presensi/absensi-siswa';
                    } else if (response.status == 202) {
                        swal({
                            title: response.message,
                            buttons: false,
                            timer: 2000,
                            icon: "success",
                        });
                        setTimeout(() => {
                            window.location = '/guru#presensi/absensi-siswa';
                        }, 2300);
                    } else if (response.status == 203) {
                        swal({
                            title: response.message,
                            buttons: false,
                            timer: 2000,
                            icon: "success",
                        });
                        primary_table.ajax.reload(null, false);
                    } else if (response.status == 204) {
                        loadURI(response.path);
                    } else if (response.status == 205) {
                        $('#modalMaster').modal('hide');
                        swal({
                            title: response.message,
                            buttons: false,
                            timer: 2000,
                            icon: "success",
                        });
                        primary_table.ajax.reload(null, false);
                    } else if (response.status == 300) {
                        swal({
                            title: response.message,
                            buttons: false,
                            timer: 2000,
                            icon: "success",
                        });
                    }
                },
                complete: function() {
                    $('button').removeAttr('disabled');
                }
            });
        }
    </script>
</body>

</html>

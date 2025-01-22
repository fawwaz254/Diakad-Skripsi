<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#penanganan-siswa/input-pelanggaran/add') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        INPUT PELANGGARAN BERSAMA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-input-pelanggaran/add/' . $id_pelanggaran_siswa) }}">
                        {{ csrf_field() }}
                        @method('POST')
                        @csrf

                        <div class="row clearfix">
                            <div class="col-md-4">
                                <label>Semester</label>
                                <select class="form-control show-tick" name="id_semester" required="">
                                    <option value="" disabled selected>-- Pilih Semester --</option>
                                    @foreach ($data_semester as $data)
                                        <option value="{{ $data->id_semester }}" {{ $data->is_aktif_semester == 1 ? 'selected' : '' }}>
                                            {{ $data->tahun_ajaran }} {{ $data->nm_semester }}
                                            {{ $data->is_aktif_semester == 1 ? '(Aktif)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Kelas</label>
                                <select class="form-control show-tick" name="kelas" onchange="changeKelas(this)"
                                    required="">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($data_kelas as $data)
                                        <option value="{{ $data->id_kelas }}">{{ $data->nm_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Nama Siswa</label>
                                <select class="form-control show-tick" name="id_siswa" required
                                    onchange="changeName(this)">
                                    <option value="">-- Pilih Siswa --</option>
                                </select>
                            </div>
                        </div>

                        <h2 class="card-inside-title">Data Siswa</h2>
                        <table id="example"
                            class="display table table-bordered table-striped table-hover dataTable responsive nowrap"
                            style="width:100%">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" class="chk-col-blue select-all" id="checkbox1">
                                        <label for="checkbox1"></label>
                                    </th>
                                    <th>Nomor Induk Siswa</th>
                                    <th>Name</th>
                                    <th>Kelas</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tahun Masuk</th>
                                </tr>
                            </thead>
                            <tbody id="table-body"></tbody>
                        </table>

                        <button type="button" name="addStudents[]" class="btn btn-primary"
                            style="margin-top: 10px; padding: 10px;" id="add-students">Tambahkan Siswa
                        </button>

                        <h2 class="card-inside-title">Siswa yang Dipilih</h2>
                        <table id="selected-students-table" class="display table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nomor Induk Siswa</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tahun Masuk</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="selected-students-body"></tbody>
                        </table>

                        <h2 class="card-inside-title">Sub Kategori Pelanggaran</h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick select2" name="id_subkategori_pelanggaran"
                                    required>
                                    <option value="" disabled selected>-- Pilih --</option>
                                    @foreach ($data_kategori as $kategori)
                                        <optgroup label="{{ $kategori->nm_kategori_pelanggaran }}">
                                            @foreach ($kategori->subkategori_pelanggaran as $data)
                                                <option value="{{ $data->id_subkategori_pelanggaran }}">
                                                    {{ $kategori->tingkat_kategori_pelanggaran }}.{{ $data->tingkat_subkategori_pelanggaran }}
                                                    {!! $data->nm_subkategori_pelanggaran !!}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <h2 class="card-inside-title">Catatan Pelanggaran</h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="catatan_pelanggaran" required>
                            </div>
                        </div>

                        <h2 class="card-inside-title">Catatan Khusus <small><b>* Ditampilkan Khusus, Tidak Untuk Diakses
                                    User Lain</b></small></h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea name="catatan_pelanggaran_khusus" id="editor1" class="editor1" rows="10"
                                    cols="80"></textarea>
                            </div>
                        </div>

                        <h2 class="card-inside-title">Tanggal Pelanggaran</h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="datetime-local" class="form-control" name="tgl_pelanggaran" required>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
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

{{-- Modal histori pelanggaran --}}
<div class="modal" tabindex="-1" role="dialog" id="modal_history_pelanggaran">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <h5 class="modal-title">History Pelanggaran Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label for="Name">Nama :</label>
                        <input type="text" name="nama" value="" disabled class="col-lg-12">
                    </div>
                </div>
                <br>
                <div id="print"></div>
            </div>
        </div>
    </div>
</div>

@include('scriptjs')
<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>

<script>
    CKEDITOR.replace('editor1');

    setInterval(updateDiv, 100);

    function updateDiv() {
        var editorText = CKEDITOR.instances.editor1.getData();
        $('#editor1').val(editorText);
    }

    $(document).ready(function () {
        var selectedStudents = [];

        $('#add-students').click(function () {
            $('.select-item:checked').each(function () {
                var row = $(this).closest('tr');
                var studentData = {
                    id: $(this).val(),
                    nis: row.find('td:nth-child(2)').text(),
                    name: row.find('td:nth-child(3)').text(),
                    kelas: row.find('td:nth-child(4)').text(),
                    jenisKelamin: row.find('td:nth-child(5)').text(),
                    tahunMasuk: row.find('td:nth-child(6)').text()
                };

                if (!selectedStudents.some(student => student.nis === studentData.nis)) {
                    selectedStudents.push(studentData);
                    updateSelectedStudentsTable();
                }
            });
        });

        function updateSelectedStudentsTable() {
            var tbody = $('#selected-students-body');
            tbody.empty();

            selectedStudents.forEach(function (student, index) {
                var row = `
                <tr>
                    <td>${student.nis}</td>
                    <td>${student.name}</td>
                    <td>${student.kelas}</td>
                    <td>${student.jenisKelamin}</td>
                    <td>${student.tahunMasuk}</td>
                    <td>
                        <button type="button" class="btn btn-info btn-sm history-student" data-index="${index}">History</button>
                        <button class="btn btn-danger btn-sm delete-student" data-index="${index}">Delete</button>
                    </td>
                </tr>`;
                tbody.append(row);
            });

            $('.history-student').on('click', function () {
                var index = $(this).data('index');
                var student = selectedStudents[index];
                showHistoryModal(student);
            });
        }

        function showHistoryModal(student) {
            $("input[name='nama']").val(student.name);
            $("input[name='nis']").val(student.nis);
            $("input[name='kelas']").val(student.kelas);
            changeName(student.nis);
            $('#modal_history_pelanggaran').modal('show');
        }

        $(document).on('click', '.delete-student', function () {
            var index = $(this).data('index');
            selectedStudents.splice(index, 1);
            updateSelectedStudentsTable();
        });

        $('.select-all').change(function () {
            $('.select-item').prop('checked', $(this).prop('checked'));
        });

        $(document).on('change', '.select-item', function () {
            $('.select-all').prop('checked', $('.select-item:checked').length === $('.select-item').length);
        });

        $('#form-validation').on('submit', function (event) {
            event.preventDefault();

            if (selectedStudents.length === 0) {
                swal({
                    title: 'Pilih satu atau lebih siswa',
                });
                return;
            }

            var formData = {
                selected_students: selectedStudents,
                // id_semester: $('select[name=id_semester]').val(),
                id_subkategori_pelanggaran: $('select[name=id_subkategori_pelanggaran]').val(),
                catatan_pelanggaran: $('input[name=catatan_pelanggaran]').val(),
                catatan_pelanggaran_khusus: $('textarea[name=catatan_pelanggaran_khusus]').val(),
                tgl_pelanggaran: $('input[name=tgl_pelanggaran]').val(),
            };

            swal({
                title: 'Apakah Yakin Untuk Menyimpan Pelanggaran?',
                showCancelButton: true
            }, function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: '{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-input-pelanggaran/add-multiple') }}',
                        type: 'POST',
                        data: { 'selectedStudents': selectedStudents, 'formData': formData },
                        success: function (response) {
                            if (response.status == 202) {
                                vex.dialog.alert(response.message);
                                selectedStudents = [];
                                updateSelectedStudentsTable();
                            } else {
                                vex.dialog.alert(response.message);
                            }
                        },
                        error: function (xhr) {
                            var errors = xhr.responseJSON.errors;
                            var errorMessage = '';
                            for (var key in errors) {
                                errorMessage += errors[key].join(', ') + '\n';
                            }
                            vex.dialog.alert(errorMessage);
                        },
                        complete: function () {
                            $('button').removeAttr('disabled', 'disabled');
                        }
                    });
                } else {
                    selectedStudents = [];
                }
            });
        });
    });


    function changeName(el) {

        $.ajax({
            url: '{{ url(Request::segment(1) . '/' . Request::segment(2) . '/siswa-pelanggaran') }}',
            type: 'POST',
            data: {
                siswa: $('select[name=id_siswa]').val()
            },
            success: function (result) {
                console.log(result);
                // alert(result['siswa']);

                $("input[name='nama']").val(result['siswa']['nm_pengguna']);
                $("input[name='nik']").val(result['siswa']['nis_siswa']);
                $("input[name='kelas']").val(result['siswa']['nm_kelas']);

                var html = '<h5 style="text-align:left">Histori Siswa</h5>' +
                    '<table border="1" style="width:100%" cellspacing="0" cellpadding="10">' +
                    '<tr>' +
                    '<td align="center">No.</td>' +
                    '<td align="center">Jenis Pelanggaran</td>' +
                    '<td align="center">Pelanggaran Tingkat</td>' +
                    '<td align="center">Poin</td>' +
                    '<td align="center">Frekuensi</td>' +
                    '<td align="center">Jumlah</td>' +
                    '</tr>';
                var jumlah = 0;
                $.each(result['list_data'], function (key, item) {
                    html += '<tr><td align="center">' + (key + 1) + '</td>';
                    html += '<td align="center">' + item['nm_subkategori_pelanggaran'] + '</td>';
                    html += '<td align="center">' + item['nm_kategori_pelanggaran'] + '</td>';
                    html += '<td align="center">' + item['poin_subkategori_pelanggaran'] + '</td>';
                    html += '<td align="center">' + item['frekuensi'] + ' x' + '</td>';
                    html += '<td align="center">' + item['jumlah_poin'] + '</td></tr>';
                    jumlah += item['jumlah_poin'];
                });
                html += '<tr><td colspan="5" align="center">Total</td><td align="center">' + jumlah + '</td></tr>'
                html += '</table>';

                $('#print').html(html);
            }
        });
    }

    function changeKelas(el) {
        $.ajax({
            url: '{{ url(Request::segment(1) . '/' . Request::segment(2) . '/siswa-bykelas') }}',
            type: 'POST',
            data: {
                kelas: $('select[name=kelas]').val()
            },
            beforeSend: function () {
                $('select[name=id_siswa]').html('<option>Loading...</option>');
                $('#table-body').html('<tr><td colspan="6">Loading...</td></tr>');
            },
            success: function (result) {
                $('select[name=id_siswa]').html('');
                $('#table-body').html('');

                var html = '<option value="">-- Pilih Siswa --</option>';
                $.each(result, function (key, item) {
                    html += `<option value="${item.id_siswa}">${item.nm_pengguna} (${item.nis_siswa})</option>`;

                    var checkboxId = 'checkbox_' + item.id_siswa;

                    var row = `
                    <tr>
                        <td>
                            <input type="checkbox" class="chk-col-blue select-item" id="${checkboxId}" value="${item.id_siswa}">
                            <label for="${checkboxId}"></label>
                        </td>
                        <td>${item.nis_siswa}</td>
                        <td>${item.nm_pengguna}</td>
                        <td>${item.nm_kelas}</td>
                        <td>${item.jenis_kelamin == 1 ? 'Laki-laki' : 'Perempuan'}</td>
                        <td>${item.thn_masuk_siswa}</td>
                    </tr>`;

                    $('#table-body').append(row);
                });

                $('select[name=id_siswa]').html(html);
            },
            error: function (xhr, status, error) {
                alert('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
                $('select[name=id_siswa]').html('<option value="">-- Pilih Siswa --</option>');
                $('#table-body').html('<tr><td colspan="6">Data tidak tersedia.</td></tr>');
            }
        });
    }
</script>
<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#penanganan-siswa/input-pelanggaran') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        INPUT PELANGGARAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-input-pelanggaran/add/' . $id_pelanggaran_siswa) }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Semester
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_semester" required="">
                                    <option value="" disabled selected>-- Pilih Semester --</option>
                                    @foreach ($data_semester as $data)
                                        @if ($data->is_aktif_semester == 1)
                                            <option value="{{ $data->id_semester }}" selected>{{ $data->tahun_ajaran }}
                                                {{ $data->nm_semester }} (Aktif)</option>
                                        @else
                                            <option value="{{ $data->id_semester }}">{{ $data->tahun_ajaran }}
                                                {{ $data->nm_semester }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Kelas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="kelas" onchange="changeKelas(this)"
                                    required="">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($data_kelas as $data)
                                        <option value="{{ $data->id_kelas }}">{{ $data->nm_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_siswa" required=""
                                    onchange="changeName(this)">
                                    <option value="">--
                                        Pilih Siswa --</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Riwayat pelanggaran Siswa
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <a href="#" data-toggle="modal" data-target="#myModal2"
                                    class=" bg-blue waves-effect btn"><i class="material-icons">search</i><span>Lihat
                                        Histori</span></a>
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Kategori Pelanggaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="kategori" onchange="changeKategori(this)">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($data_kategori as $data)
                                        <option value="{{ $data->id_kategori_pelanggaran }}">
                                            {{ $data->tingkat_kategori_pelanggaran }} -
                                            {{ $data->nm_kategori_pelanggaran }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Sub-Kategori Pelanggaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_subkategori_pelanggaran">
                                    <option value="">-- Pilih Sub-Kategori --</option>
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Catatan Pelanggaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="catatan_pelanggaran" required=""
                                    aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Catatan Khusus <small><b>* Ditampilkan Khusus, Tidak Untuk Diakses User Lain</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea name="catatan_pelanggaran_khusus" id="editor1" class="editor1" rows="10" cols="80"></textarea>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tanggal Pelanggaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="datetime-local" class="form-control" name="tgl_pelanggaran" required=""
                                    aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
<div class="modal" tabindex="-1" role="dialog" id="myModal2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">

                <h5 class="modal-title">LAPORAN PRIBADI SISWA </h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="Name">Nama :</label>
                        <input type="text" name="nama" value="" disabled>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="Name">NIK :</label>
                        <input type="text" name="nik" value="" disabled>
                    </div>
                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                        <label for="Name">Kelas :</label>
                        <input type="text" name="kelas" value="" disabled>
                    </div>
                </div>
                <div id="print"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<!-- CKeditor Plugin Js -->
<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>

<script>
    CKEDITOR.replace('editor1');

    // custom code to key binding ckeditor
    timer = setInterval(updateDiv, 100);

    function updateDiv() {
        var editorText = CKEDITOR.instances.editor1.getData();
        $('#editor1').val(editorText);
    }
</script>

<script>
    $(function() {
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'DD MMMM YYYY HH:mm:00',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: true
        });

    });

    var modul_url = 'penanganan-siswa';
    var change_kelas_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'siswa-bykelas';
    var change_kategori_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'subkategori-bykategori';

    function changeKelas(el) {
        $.ajax({
            url: change_kelas_url,
            type: 'POST',
            data: {
                kelas: $('select[name=kelas]').val()
            },
            success: function(result) {
                $('select[name=id_siswa]').html('');
                var html = '<option value="">-- Pilih Siswa --</option>';
                $.each(result, function(key, item) {
                    html += '<option value="' + item.id_siswa + '">' + item.nm_pengguna + ' (' +
                        item.nis_siswa + ')</option>'
                });
                $('select[name=id_siswa]').html(html);
            }
        });
    }

    function changeKategori(el) {
        $.ajax({
            url: change_kategori_url,
            type: 'POST',
            data: {
                kategori: $('select[name=kategori]').val()
            },
            success: function(result) {
                $('select[name=id_subkategori_pelanggaran]').html('');
                var html = '<option value="">-- Pilih Sub-Kategori --</option>';
                $.each(result, function(key, item) {
                    html += '<option value="' + item.id_subkategori_pelanggaran + '">' + item
                        .tingkat_kategori_pelanggaran + '.' + item.tingkat_subkategori_pelanggaran +
                        ' ' + item.nm_subkategori_pelanggaran + '</option>'
                });
                $('select[name=id_subkategori_pelanggaran]').html(html);
            }
        });
    }



    function changeName(el) {
        $.ajax({
            url: '{{ url(Request::segment(1) . '/' . Request::segment(2) . '/siswa-pelanggaran') }}',
            type: 'POST',
            data: {
                siswa: $('select[name=id_siswa]').val()
            },
            success: function(result) {
                // console.log();
                // alert(result['siswa']);

                $("input[name='nama']").val(result['siswa']['nm_pengguna']);
                $("input[name='nik']").val(result['siswa']['nis_siswa']);
                $("input[name='kelas']").val(result['siswa']['nm_kelas']);

                var html = '<h5 style="text-align:left">Histori Siswa</h5>' +
                    '<table border="1" style="width:100%" cellspacing="0" cellpadding="10">' +
                    '<tr>' +
                    '<td align="center"  >No.</td>' +
                    '<td align="center" >Jenis Pelanggaran</td>' +
                    '<td align="center" >Pelanggaran Tingkat</td>' +
                    '<td align="center" >Poin</td>' +
                    '<td align="center" >Frekuensi</td>' +
                    '<td align="center" >Jumlah</td>' +
                    '</tr>';
                var jumlah = 0;
                $.each(result['list_data'], function(key, item) {
                    html += '<tr><td align="center">' + (key + 1) + '</td>';
                    html += '<td align="center">' + item['nm_subkategori_pelanggaran'] +
                        '</td>';
                    html += '<td align="center">' + item['nm_kategori_pelanggaran'] +
                        '</td>';
                    html += '<td align="center">' + item['poin_subkategori_pelanggaran'] +
                        '</td>';
                    html += '<td align="center">' + item['frekuensi'] + ' x' + '</td>';
                    html += '<td align="center">' + item['jumlah_poin'] + '</td></tr>';
                    jumlah += item['jumlah_poin'];
                });
                html += '<tr><td colspan="5" align="center">Total</td><td align="center">' +
                    jumlah +
                    '</td></tr>'
                html += '</table>';

                $('#print').html(html);
            }
        });
    }
</script>

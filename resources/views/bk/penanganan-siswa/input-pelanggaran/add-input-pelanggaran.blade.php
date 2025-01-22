<div class="container-fluid">
    <div class="block-header">
        <h2 style="display: flex; justify-content: space-between">
            <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#penanganan-siswa/tindakan-pelanggaran') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a>
            <a class="btn bg-blue waves-effect target-link"
                href="{{url(Request::segment(1) . '#penanganan-siswa/input-pelanggaran/multiple')}}"><i
                    class="material-icons">list_alt</i><span>Tambah Sekaligus</span></a>
        </h2>
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

                        <div class="row clearfix">
                            <div class="col-md-4">
                                <label>Semester</label>
                                <select class="form-control show-tick" name="id_semester" required="">
                                    <option value="" disabled selected>-- Pilih Semester --</option>
                                    @foreach ($data_semester as $data)
                                        @if ($data->is_aktif_semester == 1)
                                            <option value="{{ $data->id_semester }}" selected>{{ $data->tahun_ajaran }}
                                                {{ $data->nm_semester }} (Aktif)
                                            </option>
                                        @else
                                            <option value="{{ $data->id_semester }}">{{ $data->tahun_ajaran }}
                                                {{ $data->nm_semester }}
                                            </option>
                                        @endif
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
                                <select class="form-control show-tick" name="id_siswa" required=""
                                    onchange="changeName(this)">
                                    <option value="">-- Pilih Siswa --</option>
                                </select>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-md-4">
                                <label>Riwayat pelanggaran Siswa </label>
                                <br>
                                <a href="#" data-toggle="modal" data-target="#modal_history_pelanggaran"
                                    class=" bg-blue waves-effect btn"><i class="material-icons">search</i><span>Lihat
                                        Histori</span></a>
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Sub Kategori Pelanggaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick select2" name="id_subkategori_pelanggaran">
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
                                <textarea name="catatan_pelanggaran_khusus" id="editor1" class="editor1" rows="10"
                                    cols="80"></textarea>
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
<div class="modal" tabindex="-1" role="dialog" id="modal_history_pelanggaran">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">

                <h5 class="modal-title">History Pelanggaran Siswa</h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> --}}
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
        {{-- <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div> --}}
    </div>
</div>



@include('scriptjs')
<!-- CKeditor Plugin Js -->
<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>


<script>
    var name = this.id_siswa;
    var kelas = this.kelas;
    var semester = this.id_semester
    CKEDITOR.replace('editor1');

    // custom code to key binding ckeditor
    timer = setInterval(updateDiv, 100);

    function updateDiv() {
        var editorText = CKEDITOR.instances.editor1.getData();
        $('#editor1').val(editorText);
    }

    $(function () {
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'DD MMMM YYYY HH:mm:00',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: true
        });

    });

    function changeKelas(el) {
        $.ajax({
            url: '{{ url(Request::segment(1) . '/' . Request::segment(2) . '/siswa-bykelas') }}',
            type: 'POST',
            data: {
                kelas: $('select[name=kelas]').val()
            },
            success: function (result) {
                $('select[name=id_siswa]').html('');
                var html = '<option value="">-- Pilih Siswa --</option>';
                $.each(result, function (key, item) {
                    // html += '<option value="' + item.id_kelas + '/' + '' + item.id_siswa + '">  ' +
                    //     item.nm_pengguna + ' (' + item.nis_siswa + ')</option>'
                    html += '<option value="' + item.id_siswa + '">  ' +
                        item.nm_pengguna + ' (' + item.nis_siswa + ')</option>'
                });
                $('select[name=id_siswa]').html(html);
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
            success: function (result) {
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
                $.each(result['list_data'], function (key, item) {
                    html += '<tr><td align="center">' + (key + 1) + '</td>';
                    html += '<td align="center">' + item['nm_subkategori_pelanggaran'] + '</td>';
                    html += '<td align="center">' + item['nm_kategori_pelanggaran'] + '</td>';
                    html += '<td align="center">' + item['poin_subkategori_pelanggaran'] + '</td>';
                    html += '<td align="center">' + item['frekuensi'] + ' x' + '</td>';
                    html += '<td align="center">' + item['jumlah_poin'] + '</td></tr>';
                    jumlah += item['jumlah_poin'];
                });
                html += '<tr><td colspan="5" align="center">Total</td><td align="center">' + jumlah +
                    '</td></tr>'
                html += '</table>';

                $('#print').html(html);
            }
        });
    }
    // function changeName(el){
    //     var nilai = $(el).val() ;
    //     $('#print').html('@foreach ($data_semester as $data)'+
        //     '@if ($data->is_aktif_semester == 1)'+
            //     // '<a href="#" data-toggle="modal" data-target="#myModal2" class=" bg-blue waves-effect  "><i class="material-icons">search</i><span>Lihat Histori</span></a>'+
            //     // '<a href="#myModal2" class="btn bg-blue waves-effect  passingID2" data-bs-toggle="modal"><i class="material-icons">backspace</i><span>Kembali</span></a>'+
            //     // '<a href="bimbingan-konseling/penanganan-siswa/jurnal-tindakan/print/{{ $data->id_semester }}/'+nilai+'" id="print" target="_blank">Histori Pelanggaran</a>'+
        //     '@endif'+
    //     '@endforeach');

    // }


    // $(".passingID2").click(function() {
    // var jadwal_kelas_kelas_mp = parseInt($(this).attr('data-id-jadwal-kelas-mp'));
    // var jadwal_jam = $(this).attr('data-id-jadwal-jam');
    // var jadwal_jam_selesai = $(this).attr('data-id-jadwal-jam-selesai');
    // var guru = $(this).attr('data-id-guru');
    // const $select1 = document.querySelector('#jamMasukEdit');
    // $select1.value = jadwal_jam;

    // const $select2 = document.querySelector('#jamSelesaiEdit');
    // $select2.value = jadwal_jam_selesai;
    // const $select3 = document.querySelector('#guruEdit');
    // $select3.value = guru;
    // var hari = parseInt($(this).attr('data-hari'));
    // $("#hari").val(hari);

    // $('#myModal2').modal('show');

    // var id_jadwal_kelas_mp =  $(this).attr('data-id-jadwal-kelas-mp');
    // // alert(id_jadwal_kelas_mp)
    // $("#id_jadwal_kelas_mp").val(id_jadwal_kelas_mp);


    // var  id_pengampu_mp = $(this).attr('data-id-pengampu-mp');
    // $("#id_pengampu_mp").val(id_pengampu_mp);
    // });

    $('.select2').select2();
</script>
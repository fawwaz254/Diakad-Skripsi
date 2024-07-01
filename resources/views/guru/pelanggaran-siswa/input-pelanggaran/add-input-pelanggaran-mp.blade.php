<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#pelanggaran-siswa/input-pelanggaran-mp') }}"><i
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
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-input-pelanggaran-mp/add/' . $id_presensi_mp_pelanggaran) }}">
                        {{ csrf_field() }}
                        <div class="row clearfix">

                            <div class="col-md-4">
                                <label> Data Siswa</label>
                                <input type="hidden" name="id_presensi_mp" required=""
                                    value="{{ $presensi_mp_aktif->id_presensi_mp }}">
                                <input type="hidden" name="id_siswa" required=""
                                    value="{{ $data_siswa->id_siswa }}">
                                <input type="hidden" name="id_kelas" required=""
                                    value="{{ $data_siswa->id_kelas }}">
                                <input type="text" class="form-control" disabled=""
                                    value="{{ $data_siswa->nm_pengguna }} - {{ $data_siswa->nis_siswa }}">
                            </div>

                            <div class="col-md-4">
                                <label>Terjadi di</label>
                                <input type="text" class="form-control" disabled=""
                                    value="KELAS {{ $data_kelas->nm_kelas }} MAPEL {{ $data_kelas->nm_mata_pelajaran }}">
                            </div>

                            <div class="col-md-4">
                                <label>Pada pekan ke</label>
                                <input type="text" class="form-control" disabled=""
                                    value="{{ $presensi_mp_aktif->pertemuan_ke }}">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-md-4">
                                <label>Riwayat pelanggaran Siswa </label>
                                <br>
                                <a href="#" data-toggle="modal" data-target="#myModal2"
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
                                                    {!! $data->nm_subkategori_pelanggaran !!}</option>
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
                        <label for="Name">NIS :</label>
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
<script>
    $('.select2').select2();

    $(document).ready(function() {
        $.ajax({
            url: '{{ url(Request::segment(1) . '/' . Request::segment(2) . '/siswa-pelanggaran') }}',
            type: 'POST',
            data: {
                siswa: $('input[name=id_siswa]').val()
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
                    jumlah + '</td></tr>'
                html += '</table>';

                $('#print').html(html);
            }
        });
    });
</script>

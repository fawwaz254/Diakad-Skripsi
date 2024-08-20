<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#presensi/absensi-tanpa-jadwal') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <table>
                        <tr>
                            <td>KELAS </td>
                            <td> : </td>
                            <td> {{ $kelas_mp->kelas->nm_kelas }}</td>
                        </tr>
                        <tr>
                            <td>MAPEL </td>
                            <td> : </td>
                            <td> {{ $kelas_mp->mata_pelajaran->nm_mata_pelajaran }}</td>
                        </tr>
                        <tr>
                            <td>SEMESTER</td>
                            <td> : </td>
                            <td> {{ $semester_aktif->tahun_ajaran }} {{ $semester_aktif->nm_semester }}</td>
                        </tr>
                    </table>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-absensi-tanpa-jadwal/add-kbm/' . $id_jadwal_kelas_mp . '/' . $pertemuan_ke) }}">

                        <form action="">
                            {{ csrf_field() }}
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable display nowrap"
                                    id="primary_table" style="overflow-x: scroll;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIS</th>
                                            <th>Kehadiran siswa</th>
                                            <th>Nilai Karakter</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <h2 class="card-inside-title">
                                Pertemuan ke
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="pertemuan_ke" required=""
                                        aria-required="true" aria-invalid="true" value="{{ $pertemuan_ke }}" readonly>
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Tanggal
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input class="form-control" type="text" name="tgl_presensi" readonly
                                        value="{{ $tanggal }}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Uraian Materi
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    @if ($presensi_mp_aktif)
                                        <textarea class="form-control" name="uraian_materi" rows="4" cols="100">{{ $presensi_mp_aktif->uraian_materi }}</textarea>
                                    @else
                                        <textarea class="form-control" name="uraian_materi" rows="4" cols="100">{{ !empty($mapel_rpp_detail) ? $mapel_rpp_detail->deskripsi : '' }}</textarea>
                                    @endif
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Waktu Mulai
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="datepicker-time form-control" name="waktu_mulai"
                                        required="" aria-required="true" aria-invalid="true" value="">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Waktu Selesai
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="datepicker-time form-control" name="waktu_selesai"
                                        required="" aria-required="true" aria-invalid="true" value="">
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
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
<input type="hidden" id="data-id" value="{{ $id_jadwal_kelas_mp }}">
<input type="hidden" id="pertemuan-id" value="{{ $pertemuan_ke }}">

@include('scriptjs')
<script>
    var id_jadwal_kelas_mp = {!! json_encode($id_jadwal_kelas_mp) !!};
    var pertemuan_ke = {!! json_encode($pertemuan_ke) !!};

    var modul_url = 'presensi';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'absensi-tanpa-jadwal/datatables-kbm/' +
        id_jadwal_kelas_mp + '/' + pertemuan_ke;

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        // serverSide: true,
        pageLength: 100,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nis_siswa',
                name: 'siswa.nis_siswa',
                visible: false,
            },
            {
                data: 'alasan',
                name: 'pengguna.nm_pengguna',
                render: function(data, type, row) {
                    var data_siswa = `${row.nis_siswa.nis_siswa}<br/>
                    ${row.nm_pengguna}<br/>`;

                    if (data.status_pengguna.status == 1) {
                        var html = '';
                        $.each(data.options, function(index, item) {
                            if (data.kehadiran == item.id) {
                                html += '<option value="' + item.id + '" selected>' + item
                                    .text + '</option>';
                            } else {
                                html += '<option value="' + item.id + '">' + item.text +
                                    '</option>';
                            }
                        })
                        return data_siswa + '<br><input type="hidden" name="id_siswa[]" value="' + row
                            .nis_siswa.id_siswa +
                            '"/><select class="form-control show-tick" style="width:85px;" name="alasan[]">' +
                            html +
                            '</select>';
                    } else {
                        return data_siswa + '<p class="font-underline col-orange font-24">' + data
                            .status_pengguna
                            .nm_status + '</p>';
                    }
                }
            },
            {
                data: 'nilai_karakter',
                searchable: false,
                orderable: false,
                render: function(data, type, row) {
                    if (data.status_pengguna.status == 1) {
                        var html = '';
                        var i = 0;
                        $.each(data.options, function(index, item) {
                            html += `<span><input id="ck-${row.nis_siswa.id_siswa}-${i}" type="checkbox" name="id_karakter_siswa[${row.nis_siswa.id_siswa}][]" checked class="filled-in" value="${item}">
                                        <label for="ck-${row.nis_siswa.id_siswa}-${i}">${item}</label></span><br/>`;
                            i++;
                        })
                        return html;
                    } else {
                        return '';
                    }
                }
            },
        ],
        order: [
            [1, 'asc']
        ]
    });

    primary_table.on('draw', function() {
        primary_table.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        });
    }).draw();


    // primary_table.on('draw', function() {
    //     primary_table.column(0, {
    //         search: 'applied',
    //         order: 'applied'
    //     }).nodes().each(function(cell, i) {
    //         var start = this.page.info().page * this.page.info().length;
    //         cell.innerHTML = start + i + 1;
    //     });

    //     var opsi = {!! json_encode($opsi) !!};

    //     if (opsi == 1) {
    //         var rows = primary_table.rows([{
    //             'search': 'applied'
    //         }, ':nth-child(2n)']).nodes();

    //         $('select', rows).val(99);
    //     } else if (opsi == 2) {
    //         var rows = primary_table.rows([{
    //             'search': 'applied'
    //         }, ':nth-child(1)', ':nth-child(2n+1)']).nodes();

    //         $('select', rows).val(99);
    //     } else if (opsi == 3) {
    //         var count = primary_table.data().count();
    //         var array = getArrayForSettingTable(count, true);

    //         var rows = primary_table.rows(array).nodes();

    //         $('select', rows).val(99);
    //     } else if (opsi == 4) {
    //         var count = primary_table.data().count();
    //         var array = getArrayForSettingTable(count, false);

    //         var rows = primary_table.rows(array).nodes();

    //         $('select', rows).val(99);
    //     } else if (opsi == 5) { // Laki-laki
    //         var indexes = primary_table.rows().eq(0).filter(function(rowIdx) {
    //             return primary_table.cell(rowIdx, 3).data() === 'Perempuan' ? true : false;
    //         });

    //         var rows = primary_table.rows(indexes).nodes();

    //         $('select', rows).val(99);
    //     } else if (opsi == 6) { // Perempuan
    //         var indexes = primary_table.rows().eq(0).filter(function(rowIdx) {
    //             return primary_table.cell(rowIdx, 3).data() === 'Laki-laki' ? true : false;
    //         });

    //         var rows = primary_table.rows(indexes).nodes();

    //         $('select', rows).val(99);
    //     }
    // }).draw();

    function getArrayForSettingTable(count, is_last) {
        if (count % 2 == 0) {
            var half_count = count / 2;
            if (is_last) {
                var start = half_count + 1;
                var end = count;
            } else {
                var start = 1;
                var end = half_count;
            }
        } else {
            var half_count = Math.floor(count / 2);
            if (is_last) {
                var start = half_count + 1;
                var end = count;
            } else {
                var start = 1;
                var end = half_count;
            }
        }

        var array = [];
        for (var i = start; i <= end; i++) {
            array.push(':nth-child(' + i + ')');
        }

        return array;
    }

    $(function() {
        $('.datepicker-time').bootstrapMaterialDatePicker({
            format: 'HH:mm',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: true,
            date: false
        });

        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
</script>

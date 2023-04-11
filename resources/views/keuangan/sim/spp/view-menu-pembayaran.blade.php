<style>
    .tdbg-1 {
        background: #efee9d;
    }

    .tdbg-2 {
        background: #d1eaa3;
    }

    .tdbg-3 {
        background: #dbc6eb;
    }

    .tdbg-4 {
        background: #abc2e8;
    }

    .tdbg-5 {
        background: #ddf3f5;
    }

    .tdbg-6 {
        background: #f2aaaa;
    }

    .tdbg-7 {
        background: #f6def6;
    }

    .tdbg-8 {
        background: #f4ebc1;
    }

    .tdbg-9 {
        background: #a6dcef;
    }

    .tdbg-10 {
        background: #f2aaaa;
    }

    .tdbg-11 {
        background: #ddf3f5;
    }

    .tdbg-12 {
        background: #a0c1b8;
    }

    .tdbg-13 {
        background: #ffffff;

    }

    table.is-fixed td {
        height: 75px;
        max-height: 75px;
        min-height: 75px;
    }

    table.datatable.dataTable.no-footer.fixedHeader-floating {
        top: 0px;
        width: 100% !important;
        display: block;
        overflow-x: auto;
    }
</style>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        PEMBAYARAN SISWA
                    </h2>
                </div>
                @include('keuangan/sim/spp/partials/header-card-menu')
            </div>
            <div class="card is-gap">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Tahun Ajaran
                            </h2>
                            <select class="form-control show-tick" name="tahun_akademik_semester">
                                @foreach ($data_semester as $semester)
                                    <option value="{{ $semester->thn_akademik_semester }}"
                                        @if ($semester->thn_akademik_semester == $tahun_akademik_semester) selected @endif>
                                        {{ $semester->tahun_ajaran }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Kelas
                            </h2>
                            <select class="form-control show-tick" name="kelas">
                                <option value="">Pilih kelas</option>
                                @foreach ($data_kelas as $data)
                                    <option value="{{ $data->id_kelas }}"
                                        @if (!empty($id_kelas) && $id_kelas == $data->id_kelas) selected @endif>
                                        {{ $data->nm_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i
                                    class="material-icons">save</i><span>Ubah Tahun Ajaran/kelas</span></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="header">
                    <h2>
                        PEMBAYARAN SPP
                    </h2>
                </div>
                <div class="body">
                    <a href="/keuangan/utility/pembayaran-by-kelas/print/{{ $tahun_akademik_semester }}/{{ $id_kelas }}"
                        target="_blank" class="btn btn-success">Print Pembayaran Siswa</a>

                    <h2 class="card-inside-title">
                        Tanggal Pembayaran
                    </h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="text" class="datepicker form-control" name="tgl_pembayaran"
                                value="{{ $waktu }}">

                        </div>
                    </div>
                    <h2 class="card-inside-title">
                        Aksi yang dilakukan
                    </h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <input type="radio" name="action" id="lunas" class="filled-in with-gap"
                                    checked="" value="1">
                                <label for="lunas">Langsung Lunas</label>

                                <input type="radio" name="action" id="cicilan" class="filled-in with-gap"
                                    value="2">
                                <label for="cicilan" class="m-l-20">Cicilan</label>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table is-fixed table-bordered table-striped table-hover dataTable"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    @foreach ($data_bulan_tagihan as $bulan)
                                        @if (!empty($bulan->id_bulan))
                                            <th class="tdbg-{{ $bulan->id_bulan }}">{{ $bulan->nm_bulan }}</th>
                                        @else
                                            <th class="tdbg">{{ $bulan->nm_biaya }}</th>
                                        @endif
                                    @endforeach
                                    @foreach ($data_ket_tagihan as $ket)
                                        <td class="tdbg-13" style="vertical-align: bottom;">{{ $ket->keterangan }}
                                        </td>
                                    @endforeach
                                </tr>
                            </thead>
                            @php
                                $no = 1;
                            @endphp
                            <tbody>
                                @foreach ($data_siswa as $siswa)
                                    @if ($siswa->pengguna->status_pengguna->aktif_status_pengguna == 1)
                                        <tr>
                                        @else
                                        <tr style="background-color: #ffc109;">
                                    @endif
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $siswa->nis_siswa }}</td>
                                    @if ($siswa->pengguna->status_pengguna->aktif_status_pengguna == 1)
                                        <td>{{ $siswa->pengguna->nm_pengguna }}</td>
                                    @else
                                        <td>{{ $siswa->pengguna->nm_pengguna }}<br>(Mutasi/Keluar)</td>
                                    @endif
                                    @foreach ($data_bulan_tagihan as $bulan)
                                        @php
                                            $tagihan = $data_tagihan
                                                ->where('id_siswa', $siswa->id_siswa)
                                                ->where('id_bulan', $bulan->id_bulan)
                                                ->first();
                                        @endphp
                                        @if (!empty($tagihan) > 0)
                                            @if ($tagihan->is_tagih == 1)
                                                @php
                                                    $tagihan_bulanan = $tagihan->besar_biaya + $tagihan->denda_biaya - $tagihan->besar_pembayaran;
                                                @endphp
                                                <td>
                                                    @if ($tagihan->is_request == 0)
                                                        <button class="btn btn-block bg-black waves-effect"
                                                            onclick="takeAction(this)"
                                                            data-id="{{ $tagihan->id_tagihan_biaya }}"
                                                            data-nis="{{ $tagihan->nis_siswa }}">Rp{{ number_format($tagihan_bulanan) }}</button>
                                                    @else
                                                        Rp{{ number_format($tagihan_bulanan) }}<br><b>Online</b>
                                                    @endif
                                                </td>
                                            @elseif($tagihan->is_tagih == 0)
                                                <td
                                                    class="tdbg-{{ date_format(date_create($tagihan->tgl_pelunasan), 'n') }}">
                                                    <a target="_blank"
                                                        href="keuangan/sim/spp/print-pembayaran/{{ $tagihan->id_tagihan_biaya }}"><b
                                                            style="color: #4caf50;">Print
                                                            {{ date_format(date_create($tagihan->tgl_pelunasan), 'd/m') }}</b></a>
                                                    @if (
                                                        $tagihan->is_request == 0 &&
                                                            \Carbon\Carbon::now()->subDay()->format('Y-m-d H:i:s') < $tagihan->tgl_pelunasan)
                                                        <br>
                                                        <a style="margin-top: 2px; color: #e91e63; cursor: pointer;"
                                                            onclick="deleteActionKhusus(this)"
                                                            data-id="{{ $tagihan->id_tagihan_biaya }}">
                                                            Batal
                                                        </a>
                                                    @endif
                                                    @if ($tagihan->is_request == 1)
                                                        <br> <b>Online</b>
                                                    @endif
                                                </td>
                                            @endif
                                        @else
                                            <td></td>
                                        @endif
                                    @endforeach
                                    @foreach ($data_ket_tagihan as $ket)
                                        @php
                                            $tagihan = $data_tagihan_non_bulanan
                                                ->where('id_siswa', $siswa->id_siswa)
                                                ->where('title_biaya', $ket->title_biaya)
                                                ->first();
                                        @endphp
                                        @if (!empty($tagihan) > 0)
                                            @if ($tagihan->is_tagih == 1)
                                                @php
                                                    $tagihan_bulanan = $tagihan->besar_biaya + $tagihan->denda_biaya - $tagihan->besar_pembayaran;
                                                @endphp
                                                <td>
                                                    @if ($tagihan->is_request == 0)
                                                        <button class="btn btn-block bg-black waves-effect"
                                                            onclick="takeAction(this)"
                                                            data-id="{{ $tagihan->id_tagihan_biaya }}"
                                                            data-nis="{{ $tagihan->nis_siswa }}">Rp{{ number_format($tagihan_bulanan) }}</button>
                                                    @else
                                                        Rp{{ number_format($tagihan_bulanan) }}<br><b>Online</b>
                                                    @endif
                                                </td>
                                            @elseif($tagihan->is_tagih == 0)
                                                <td
                                                    class="tdbg-{{ date_format(date_create($tagihan->tgl_pembayaran), 'n') }}">
                                                    <a target="_blank"
                                                        href="keuangan/sim/spp/print-pembayaran/{{ $tagihan->id_tagihan_biaya }}"><b
                                                            style="color: #4caf50;">Print
                                                            {{ date_format(date_create($tagihan->tgl_pembayaran), 'd/m') }}</b></a>
                                                    @if ($tagihan->is_request == 0)
                                                        <br>
                                                        <a style="margin-top: 2px; color: #e91e63; cursor: pointer;"
                                                            onclick="deleteActionKhusus(this)"
                                                            data-id="{{ $tagihan->id_tagihan_biaya }}">
                                                            Batal
                                                        </a>
                                                    @endif
                                                    @if ($tagihan->is_request == 1)
                                                        <br> <b>Online</b>
                                                    @endif
                                                </td>
                                            @endif
                                        @else
                                            <td></td>
                                        @endif
                                    @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('scriptjs')
<script>
    var modul_url = 'utility';
    var lunas_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-pembayaran-siswa/lunas';
    var detail_tagihan_siswa_url = base_url + '/' + role_url + '#' + modul_url + '/' +
        'pembayaran-siswa/view-detail-tagihan-siswa';
    var delete_pembayaran_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'action-pembayaran-siswa/delete-by-tagihan';

    function takeAction(element) {
        var item = $(element);
        if ($('input[name=action]:checked', ).val() == 2) {
            window.open(detail_tagihan_siswa_url + '/' + item.attr('data-id') + '/' + item.attr('data-nis'));
        } else if ($('input[name=action]:checked', ).val() == 1) {
            $('button').attr('disabled', 'disabled');
            $.ajax({
                type: "POST",
                url: lunas_url + '/' + item.attr('data-id'),
                data: {
                    tgl_pembayaran: $('input[name=tgl_pembayaran]').val()
                },
                success: function(response) {
                    // vex.dialog.alert(response.message);
                    item.parent('td').replaceWith(
                        '<td class="tdbg-' + response.data.month + '">' + response.data.date +
                        '    <br>' +
                        '<a style="margin-top: 2px; color: #e91e63; cursor: pointer;" onclick="deleteActionKhusus(this)" data-id="' +
                        response.data.id + '">' +
                        '    Batal' +
                        '</a>' +
                        '</td>'
                    );
                },
                complete: function() {
                    $('button').removeAttr('disabled', 'disabled');
                }
            });
        }
    }

    function deleteActionKhusus(element) {
        var item = $(element);
        $('button').attr('disabled', 'disabled');
        $.ajax({
            type: "POST",
            url: delete_pembayaran_url + '/' + item.attr('data-id'),
            success: function(response) {
                item.parent('td').replaceWith(
                    '<td>' +
                    '<button class="btn btn-block bg-black waves-effect"' +
                    'onclick="takeAction(this)"' +
                    'data-id="' + response.data.id + '"' +
                    'data-nis="' + response.data.nis_siswa + '">Rp' + response.data.tagihan_bulanan +
                    '</button>' +
                    '</td>'
                );

                // loadContent(
                //     'sim/spp/pembayaran/{{ $tahun_akademik_semester }}/{{ $id_kelas }}/{{ $waktu }}'
                // );
            },
            complete: function() {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }

    function filterAction() {
        var kelas = $('select[name=kelas]').val();
        var tahun_akademik_semester = $('select[name=tahun_akademik_semester]').val();
        var waktu = $('input[name=tgl_pembayaran]').val()

        loadURI('sim/spp/pembayaran/' + tahun_akademik_semester + '/' + kelas + '/' + waktu);
    }
</script>
<script>
    $('.block').scroll(function() {
        var scrollAmt = $(this).scrollLeft();
        $('.fixedHeader-floating').css('left', 0 - parseInt(scrollAmt) + 'px');
    });



    $(function() {

        var start_date = "{{ \Carbon\Carbon::parse($waktu)->addMonth(2)->format('Y-m-d') }}";
        var end_date = "{{ \Carbon\Carbon::parse($waktu)->subMonth(2)->format('Y-m-d') }}";

        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false,
            minDate: end_date,
            maxDate: start_date,
        });
    });

    var primary_table = $('#primary_table').DataTable({
        ordering: false,
        scrollX: true,
        fixedColumns: {
            leftColumns: 3
        },
        fixedHeader: {
            header: true,
            footer: false,
            headerOffset: 65,
        },
        scrollCollapse: true,
        paging: false
    });
</script>

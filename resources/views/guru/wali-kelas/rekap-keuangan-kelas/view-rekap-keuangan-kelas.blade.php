<style>
    .tdbg-0 {
        background: white;
    }

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
</style>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            @if ($tahun_akademik_semester != null && $id_kelas != null)
                <div class="card">
                    <div class="header">
                        <h2>
                            PEMBAYARAN SISWA
                        </h2>
                    </div>
                    <div class="body">

                        <a href="/guru/wali-kelas/rekap-keuangan-kelas/print/{{ $tahun_akademik_semester }}/{{ $id_kelas }}"
                            target="_blank" class="btn btn-success">Print Pembayaran Siswa</a>

                        <h2 class="card-inside-title">
                            Tanggal Pembayaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_pembayaran"
                                    value="{{ \Carbon\Carbon::today()->format('Y-m-d') }}">
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
                            <table class="table table-bordered table-striped table-hover dataTable" id="primary_table">
                                <thead>
                                    <tr>
                                        <th rowspan="2">NIS</th>
                                        <th rowspan="2">Nama</th>
                                        <th class="text-center" colspan="{{ count($data_bulan_tagihan) }}">SPP</th>
                                        @if (count($data_ket_tagihan) > 0)
                                            <th class="text-center" colspan="{{ count($data_ket_tagihan) }}">
                                                {{ $data_ket_tagihan[0]->nm_biaya }}</th>
                                        @endif
                                    </tr>
                                    <tr>
                                        @foreach ($data_bulan_tagihan as $bulan)
                                            @if (!empty($bulan->id_bulan))
                                                <th class="tdbg-{{ $bulan->id_bulan }}">{{ $bulan->nm_bulan }}
                                                </th>
                                            @else
                                                <th class="tdbg">{{ $bulan->nm_biaya }}</th>
                                            @endif
                                        @endforeach
                                        @foreach ($data_ket_tagihan as $ket)
                                            <td class="tdbg-0" rowspan="2">{!! $ket->title_biaya !!}</td>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $no = 1;
                                    @endphp
                                    @foreach ($data_siswa as $siswa)
                                        @if ($siswa->pengguna->status_pengguna->aktif_status_pengguna == 1)
                                            <tr>
                                            @else
                                            <tr style="background-color: #ffc109;">
                                        @endif
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
                                                            <p>Belum Lunas</p>
                                                            {{-- <button class="btn btn-block bg-black waves-effect"
                                                                onclick="takeAction(this)"
                                                                data-id="{{ $tagihan->id_tagihan_biaya }}"
                                                                data-nis="{{ $tagihan->nis_siswa }}">Rp
                                                                {{ number_format($tagihan_bulanan) }}</button> --}}
                                                        @else
                                                            Rp{{ number_format($tagihan_bulanan) }}<br><b>Online</b>
                                                        @endif
                                                    </td>
                                                @elseif($tagihan->is_tagih == 0)
                                                    <td class="tdbg-{{ date_format(date_create($tagihan->tgl_pembayaran), 'n') }}"
                                                        style="vertical-align:middle;text-align: center;">
                                                        {{ date_format(date_create($tagihan->tgl_pembayaran), 'd/m') }}
                                                        @if ($tagihan->is_request == 0)
                                                            <br>
                                                            <p>Lunas</p>
                                                            {{-- <button
                                                                class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                                                style="width: 25px; height: 25px;"
                                                                onclick="deleteActionKhusus(this)"
                                                                data-id="{{ $tagihan->id_pembayaran_biaya }}">
                                                                <i class="material-icons"
                                                                    style="left: -7px; top: -7px;">close</i>
                                                            </button> --}}
                                                        @endif
                                                        @if ($tagihan->is_request == 1)
                                                            <br><b>Online</b>
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
                                                    ->where('id_detail_biaya', $ket->id_detail_biaya)
                                                    ->first();
                                            @endphp
                                            @if (!empty($tagihan) > 0)
                                                @if ($tagihan->is_tagih == 1)
                                                    @php
                                                        $tagihan_bulanan = $tagihan->besar_biaya + $tagihan->denda_biaya - $tagihan->besar_pembayaran;
                                                    @endphp
                                                    <td>
                                                        @if ($tagihan->is_request == 0)
                                                            <p>Belum Lunas</p>
                                                            {{-- <button class="btn btn-block bg-black waves-effect"
                                                                onclick="takeAction(this)"
                                                                data-id="{{ $tagihan->id_tagihan_biaya }}"
                                                                data-nis="{{ $tagihan->nis_siswa }}">Rp{{ number_format($tagihan_bulanan) }}</button> --}}
                                                        @else
                                                            Rp{{ number_format($tagihan_bulanan) }}<br><b>Online</b>
                                                        @endif
                                                    </td>
                                                @elseif($tagihan->is_tagih == 0)
                                                    <td class="tdbg-{{ date_format(date_create($tagihan->tgl_pembayaran), 'n') }}"
                                                        style="vertical-align:middle;text-align: center;">
                                                        {{ date_format(date_create($tagihan->tgl_pembayaran), 'd/m') }}
                                                        @if ($tagihan->is_request == 0)
                                                            <br>
                                                            <p>Lunas</p>
                                                            {{-- <button
                                                                class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                                                style="width: 25px; height: 25px;"
                                                                onclick="deleteActionKhusus(this)"
                                                                data-id="{{ $tagihan->id_pembayaran_biaya }}">
                                                                <i class="material-icons"
                                                                    style="left: -7px; top: -7px;">close</i>
                                                            </button> --}}
                                                        @endif
                                                        @if ($tagihan->is_request == 1)
                                                            <br><b>Online</b>
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
            @endif
        </div>
    </div>
</div>

@include('scriptjs')
<script>
    var modul_url = 'wali-kelas';
    var lunas_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-pembayaran-siswa/lunas';
    var detail_tagihan_siswa_url = base_url + '/' + role_url + '#' + modul_url + '/' +
        'rekap-keuangan-kelas';
    var delete_pembayaran_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-pembayaran-siswa/delete';
</script>
<script>
    $(function() {
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });

    var primary_table = $('#primary_table').DataTable({
        ordering: false,
        scrollX: true,
        fixedColumns: {
            leftColumns: 2
        },
        scrollCollapse: true,
        paging: false
    });
</script>

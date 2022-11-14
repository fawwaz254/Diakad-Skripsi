<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        FILTER BULAN
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <label>Bulan</label>
                                    <select class="form-control show-tick" name="id_bulan">
                                        @foreach ($data_bulan as $data)
                                            <option {{ $bulan->id_bulan == $data->id_bulan ? 'selected' : '' }} value="{{ $data->id_bulan }}">
                                                {{ $data->nm_bulan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <label>Tahun</label>
                                    <select class="form-control show-tick" name="tahun">
                                        @for ($i = 2015; $i <= 2025; $i++)
                                            <option {{ $tahun == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()">
                                <i class="material-icons">save</i><span>Filter</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Rekap Presensi Mengajar Guru Pada Bulan {{ $bulan->nm_bulan }}
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable" id="primary_table">
                            <thead>
                                <tr>
                                    <th rowspan="2">No.</th>
                                    <th rowspan="2">Nama</th>
                                    <th rowspan="2">Total Kehadiran</th>
                                    <th colspan="{{ $dates->count() }}">Tanggal</th>
                                </tr>
                                <tr>
                                    @foreach ($dates as $date)
                                        <th>
                                            {{ substr(\Carbon\Carbon::create($date)->isoFormat('dddd'), 0, 3) }}
                                            <br>
                                            {{ $date->format('d') }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_guru as $guru)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $guru->nm_pengguna }}</td>
                                        <td class="text-center">
                                            {{ $data_presensi->where('id_pengguna', $guru->id_pengguna)->count() }}</td>
                                        @foreach ($dates as $date)
                                            @php
                                                $all_presensi = $data_presensi
                                                    ->where('tgl_presensi', $date->format('Y-m-d'))
                                                    ->where('id_pengguna', $guru->id_pengguna)
                                                    ->all();
                                            @endphp
                                            @if ($auth_data->sekolah_data->nm_sekolah == 'SMK YPM 3 Taman')
                                                @if (count($all_presensi) > 0)
                                                    <td style="background: #91d18b; text-align:center;">
                                                        <b><a href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/monitoring-presensi-guru/' . $date->format('d') . '/' . $bulan->id_bulan . '/' . $tahun . '/' . $guru->id_pengguna) }}"
                                                                class="target-link">{{ count($all_presensi) }}x</a></b>
                                                    </td>
                                                @elseif($date->format('l') == 'Saturday' || $date->format('l') == 'Sunday')
                                                    <td style="background: #07689f; text-align:center;">
                                                        <b><a class="target-link">Libur</a></b>
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif
                                            @else
                                                @if (count($all_presensi) > 0)
                                                    <td style="background: #91d18b; text-align:center;">
                                                        <b><a class="target-link">{{ count($all_presensi) }}x</a></b>
                                                    </td>
                                                @elseif($date->format('l') == 'Sunday')
                                                    <td style="background: #07689f; text-align:center;">
                                                        <b><a class="target-link">Libur</a></b>
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif
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

<script>
    function filterAction() {
        loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/' + $('select[name=id_bulan]').val() + '/' + $('select[name=tahun]').val());
    }

    var primary_table = $('#primary_table').DataTable({
        ordering: false,
        scrollX: true,
        fixedColumns: {
            leftColumns: 3
        },
        scrollCollapse: true,
        paging: false
    });
</script>

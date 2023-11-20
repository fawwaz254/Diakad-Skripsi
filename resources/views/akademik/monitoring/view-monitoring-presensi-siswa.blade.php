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
                                            <option {{ $bulan->id_bulan == $data->id_bulan ? 'selected' : '' }}
                                                value="{{ $data->id_bulan }}">
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
                                            <option {{ $tahun == $i ? 'selected' : '' }} value="{{ $i }}">
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <label>Kelas</label>
                                    <select class="form-control show-tick" name="id_kelas">
                                        <option disabled>Pilih Kelas</option>
                                        @foreach ($data_kelas as $kelas)
                                            <option value="{{ $kelas->id_kelas }}"
                                                {{ $kelas->id_kelas == $id_kelas ? 'selected' : '' }}>
                                                {{ $kelas->nm_kelas }}</option>
                                        @endforeach

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
                        Rekap Presensi Siswa Pada Bulan {{ $bulan->nm_bulan }}
                        {{-- <a class="btn bg-blue waves-effect"
                            style="margin-left: 10px" target="_blank"
                            href="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/print/' . $bulan->id_bulan . '/' . $tahun) }}"><i
                                class="material-icons">print</i><span>Cetak</span></a> --}}
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable" id="primary_table">
                            <thead>
                                <tr>
                                    <th rowspan="2">No.</th>
                                    <th rowspan="2">Nama</th>

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
                                @foreach ($data_siswa as $siswa)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $siswa->pengguna->nm_pengguna }}</td>
                                        @foreach ($dates as $date)
                                            @php
                                                $presensi1 = null;
                                                $presensi2 = null;
                                                $presensi3 = null;
                                                $presensi4 = null;

                                                if (isset($data_array_presensi[$siswa->id_siswa . $date->format('Y-m-d') . '1'])) {
                                                    $presensi1 = $data_array_presensi[$siswa->id_siswa . $date->format('Y-m-d') . '1'];
                                                } elseif (isset($data_array_presensi[$siswa->id_siswa . $date->format('Y-m-d') . '2'])) {
                                                    $presensi2 = $data_array_presensi[$siswa->id_siswa . $date->format('Y-m-d') . '2'];
                                                } elseif (isset($data_array_presensi[$siswa->id_siswa . $date->format('Y-m-d') . '3'])) {
                                                    $presensi3 = $data_array_presensi[$siswa->id_siswa . $date->format('Y-m-d') . '3'];
                                                } elseif (isset($data_array_presensi[$siswa->id_siswa . $date->format('Y-m-d') . '4'])) {
                                                    $presensi4 = $data_array_presensi[$siswa->id_siswa . $date->format('Y-m-d') . '4'];
                                                }

                                                $maxValue = max($presensi1, $presensi2, $presensi3, $presensi4);

                                                $bg = 0;
                                                if (!empty($maxValue) && $maxValue != '0') {
                                                    if ($maxValue === $presensi1) {
                                                        $bg = 1;
                                                    } elseif ($maxValue === $presensi2) {
                                                        $bg = 2;
                                                    } elseif ($maxValue === $presensi3) {
                                                        $bg = 3;
                                                    } elseif ($maxValue === $presensi4) {
                                                        $bg = 4;
                                                    } else {
                                                        $bg = 0;
                                                    }
                                                }
                                            @endphp

                                            @if (
                                                $auth_data->sekolah_data->nm_singkat_sekolah == 'manu' ||
                                                    $auth_data->sekolah_data->nm_singkat_sekolah == 'minu' ||
                                                    $auth_data->sekolah_data->nm_singkat_sekolah == 'mtsnu' ||
                                                    $auth_data->sekolah_data->nm_singkat_sekolah == 'sdnu' ||
                                                    $auth_data->sekolah_data->nm_singkat_sekolah == 'smknu' ||
                                                    $auth_data->sekolah_data->nm_singkat_sekolah == 'smpnu' ||
                                                    $auth_data->sekolah_data->nm_singkat_sekolah == 'tkqnu')
                                                @if ($date->format('l') == 'Saturday' || $date->format('l') == 'Sunday')
                                                    <td style="background: #ffffff; text-align:center;">
                                                        <b><a class="target-link">Libur</a></b>
                                                    </td>
                                                @else
                                                    <td
                                                        @if ($bg == '1') style="text-align:center; background: #dbffd8;"  @elseif($bg == '4') style="text-align:center; background: #ffd8de;" @else style="text-align:center; background: #ffffff; " @endif>
                                                        @if (!empty($presensi1))
                                                            <b><a class="target-link;"
                                                                    style="color:#3ba331">{{ $presensi1 . 'X ' }}</a></b>
                                                        @endif
                                                        @if (!empty($presensi2))
                                                            <b><a class="target-link;"
                                                                    style="color:gold">{{ $presensi2 . 'X ' }}</a></b>
                                                        @endif
                                                        @if (!empty($presensi3))
                                                            <b><a class="target-link;"
                                                                    style="color:aqua">{{ $presensi3 . 'X ' }}</a></b>
                                                        @endif
                                                        @if (!empty($presensi4))
                                                            <b><a class="target-link;"
                                                                    style="color:crimson">{{ $presensi4 . 'X ' }}</a></b>
                                                        @endif

                                                    </td>
                                                @endif
                                            @else
                                                @if ($date->format('l') == 'Sunday')
                                                    <td style="background: #ffffff; text-align:center;">
                                                        <b><a class="target-link">Libur</a></b>
                                                    </td>
                                                @else
                                                    <td
                                                        @if ($bg == '1') style="text-align:center; background: #dbffd8;"  @elseif($bg == '4') style="text-align:center; background: #ffd8de;" @else style="text-align:center; background: #ffffff; " @endif>
                                                        @if (!empty($presensi1))
                                                            <b><a class="target-link;"
                                                                    style="color:#3ba331">{{ $presensi1 . 'X ' }}</a></b>
                                                        @endif
                                                        @if (!empty($presensi2))
                                                            <b><a class="target-link;"
                                                                    style="color:gold">{{ $presensi2 . 'X ' }}</a></b>
                                                        @endif
                                                        @if (!empty($presensi3))
                                                            <b><a class="target-link;"
                                                                    style="color:aqua">{{ $presensi3 . 'X ' }}</a></b>
                                                        @endif
                                                        @if (!empty($presensi4))
                                                            <b><a class="target-link;"
                                                                    style="color:crimson">{{ $presensi4 . 'X ' }}</a></b>
                                                        @endif

                                                    </td>
                                                    {{-- <td></td> --}}
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
        loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/' + $('select[name=id_kelas]').val() + '/' + $(
            'select[name=id_bulan]').val() + '/' + $(
            'select[name=tahun]').val());
    }

    var primary_table = $('#primary_table').DataTable({
        ordering: false,
        // scrollX: true,

        // scrollCollapse: true,
        paging: false
    });
</script>

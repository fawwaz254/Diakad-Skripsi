<style>
    .is-center {
        text-align: center;
        vertical-align: middle !important;
    }
</style>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        REKAP ABSENSI MAGANGG
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <form id="form-validation" method="post"
                            action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/rekap-absensi/action-pengajuan-magang') }}">
                            {{ csrf_field() }}
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Rekanan Magang</label>
                                <select class="form-control show-tick" name="id_rekanan_magang" id="id_rekanan_magang"
                                    required>
                                    <option value="0"> Semua Rekanan </option>
                                    @foreach ($data_rekanan_magang as $data)
                                        <option value="{{ $data->id_rekanan_magang }}"
                                            @if ($id_rekanan == $data->id_rekanan_magang) selected @endif>
                                            {{ $data->nm_rekanan_magang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>Periode Magang</label>
                                <select class="form-control show-tick" name="id_periode_magang" id="id_periode_magang">
                                    <option value="0"> Semua Periode </option>
                                    @foreach ($data_periode_magang as $data)
                                        <option value="{{ $data->id_periode_magang }}"
                                            @if ($id_periode == $data->id_periode_magang) selected @endif>{{ $data->nm_magang }} -
                                            {{ $data->nm_periode_magang }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <label> Date </label>
                                <input type="date" class="form-control" data-date="" data-date-format="DD/MM/YYYY"
                                    value="{{ $date }}" name="date" aria-required="true" aria-invalid="true">
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button class="btn btn-block bg-red waves-effect" onclick="filterData()"><i
                                            class="material-icons">save</i><span>Filter</span></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover  display">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">Hadir</th>
                                            <th style="text-align: center;">Sakit</th>
                                            <th style="text-align: center;">Izin</th>
                                            <th style="text-align: center;">Alpha</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @php
                                            $hadir = 0;
                                            $sakit = 0;
                                            $izin = 0;
                                            $alpha = 0;
                                            foreach ($list_pengambilan_magang as $pengambilan_magang) {
                                                if (isset($pengambilan_magang->presensiMagangSiswa)) {
                                                    $kehadiran = $pengambilan_magang->presensiMagangSiswa->kehadiran;
                                                    if ($kehadiran == '1') {
                                                        $hadir++;
                                                    } elseif ($kehadiran == '2') {
                                                        $sakit++;
                                                    } elseif ($kehadiran == '3') {
                                                        $izin++;
                                                    } elseif ($kehadiran == '0') {
                                                        $alpha++;
                                                    }
                                                }
                                            }
                                        @endphp
                                        <tr>
                                            <td style="text-align: center;">{{ $hadir }}</td>
                                            <td style="text-align: center;">{{ $sakit }}</td>
                                            <td style="text-align: center;">{{ $izin }}</td>
                                            <td style="text-align: center;">{{ $alpha }}</td>
                                        </tr>
                                        <tr>
                                            <th style="text-align: center;">Rekap Hadir</th>
                                            <th style="text-align: center;">Rekap Sakit</th>
                                            <th style="text-align: center;">Rekap Izin</th>
                                            <th style="text-align: center;">Rekap Alpha</th>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center;">
                                                {{ $presensi_magang_siswa->where('kehadiran', '1')->count() }}</td>
                                            <td style="text-align: center;">
                                                {{ $presensi_magang_siswa->where('kehadiran', '2')->count() }}</td>
                                            <td style="text-align: center;">
                                                {{ $presensi_magang_siswa->where('kehadiran', '3')->count() }}</td>
                                            <td style="text-align: center;">
                                                {{ $presensi_magang_siswa->where('kehadiran', '0')->count() }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <br>
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table
                                    class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                    id="primary_table">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">No. </th>
                                            <th style="text-align: center;">Kelas</th>
                                            <th style="text-align: center;">NIS</th>
                                            <th>Nama</th>
                                            <th>Rekanan</th>
                                            <th style="text-align: center;">@php
                                                $date = Carbon\Carbon::parse($date)->locale('id');
                                                $date->settings(['formatFunction' => 'translatedFormat']);
                                                echo $date->format('l, j F Y ');
                                            @endphp</th>
                                            <th style="text-align: center;">Hadir</th>
                                            <th style="text-align: center;">Izin/Sakit</th>
                                            <th style="text-align: center;">Alpha</th>
                                            <th style="text-align: center;">Action</th>
                                        </tr>

                                    </thead>
                                    <tbody>
                                        @php
                                            $no = 1;
                                        @endphp
                                        @foreach ($list_pengambilan_magang as $pengambilan_magang)
                                            <tr>
                                                <td style="text-align: center;">{{ $no++ }}</td>
                                                <th style="text-align: center;">
                                                    {{ $pengambilan_magang->kelas->nm_kelas }}</th>
                                                <td style="text-align: center;">
                                                    {{ $pengambilan_magang->siswa->nis_siswa }}</td>
                                                <td>{{ $pengambilan_magang->siswa->pengguna->nm_pengguna }}</td>
                                                <td>{{ $pengambilan_magang->rekanan->nm_rekanan_magang }}</td>

                                                @if (isset($pengambilan_magang->presensiMagangSiswa))
                                                    @if ($pengambilan_magang->presensiMagangSiswa->kehadiran == 1)
                                                        <td class="is-center bg-light-green"> &#10004;</td>
                                                    @elseif($pengambilan_magang->presensiMagangSiswa->kehadiran == 2)
                                                        <td class="is-center bg-amber">S</td>
                                                    @elseif($pengambilan_magang->presensiMagangSiswa->kehadiran == 3)
                                                        <td class="is-center bg-cyan">I</td>
                                                    @elseif($pengambilan_magang->presensiMagangSiswa->kehadiran == 0)
                                                        <td class="is-center bg-red">A</td>
                                                    @endif
                                                @else
                                                    <td></td>
                                                @endif

                                                <td class="is-center">
                                                    {{ $presensi_magang_siswa->where('id_siswa', $pengambilan_magang->id_siswa)->where('kehadiran', '1')->count() }}
                                                </td>
                                                <td class="is-center">
                                                    {{ $presensi_magang_siswa->where('id_siswa', $pengambilan_magang->id_siswa)->whereIn('kehadiran', [2, 3])->count() }}
                                                </td>
                                                <td class="is-center">
                                                    {{ $presensi_magang_siswa->where('id_siswa', $pengambilan_magang->id_siswa)->where('kehadiran', '0')->count() }}
                                                </td>
                                                <td class="is-center">
                                                    <a class=" btn btn-success btn-circle waves-effect waves-circle waves-float justify-content-center align-items-center"
                                                        href="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/print/' . $pengambilan_magang->id_siswa) }}"
                                                        target="_blank">
                                                        <i class="material-icons">picture_as_pdf</i>
                                                    </a>
                                                </td>
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
    </div>
</div>


@include('scriptjs')

<script type="text/javascript">
    function filterData() {
        loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}' + '/detail/' + $(
            'select[name=id_rekanan_magang]').val() + '/' + $('select[name=id_periode_magang]').val() + '/' + $(
            'input[name=date]').val());
    }

    $("input").on("change", function() {
        this.setAttribute(
            "data-date",
            moment(this.value, "YYYY-MM-DD")
            .format(this.getAttribute("data-date-format"))
        )
    }).trigger("change")

    $(document).ready(function() {
        var table = $('.dataTable').DataTable({
            paging: false,
            lengthMenu: [
                [-1],
                ["All"]
            ]
        });
    });
</script>

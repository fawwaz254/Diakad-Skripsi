<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        SISWA AKTIF
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                            <tr>
                                <th rowspan="2" style="text-align:center; vertical-align:middle;">Jurusan</th>
                                <th colspan="{{ $data_tingkat->count() }}" style="text-align: center;">Kelas</th>
                            </tr>
                            <tr>

                                @foreach ($data_tingkat as $tingkat)
                                    <td style="text-align:center">Kelas {{ $tingkat->tingkat }}</td>
                                @endforeach
                            </tr>
                            @php
                                $total = [];
                            @endphp
                            @foreach ($data_jurusan as $jurusan)
                                <tr>
                                    <td>{{ $jurusan->nm_jurusan }}</td>
                                    @foreach ($data_tingkat as $tingkat)
                                        @php
                                            $count_siswa_tingkat = \App\Models\Siswa::query()
                                                ->whereHas('kelas', function ($q) use ($tingkat, $jurusan) {
                                                    $q->where('tingkat', $tingkat->tingkat)->where('id_jurusan', $jurusan->id_jurusan);
                                                })
                                                ->whereHas('pengguna.status_pengguna', function ($q) {
                                                    $q->where('aktif_status_pengguna', 1)->where('nm_status_pengguna', 'AKTIF');
                                                    // $q->where('aktif_status_pengguna', 1)->where('kode_status_pengguna', 'AKTIF');
                                                    // $q->where('aktif_status_pengguna', 1)->where('kode_status_pengguna', '!=', 'CUTI');
                                                })
                                                ->whereNotNull('id_kelas')
                                                ->count();
                                        @endphp
                                        <td style="text-align:center">{{ $count_siswa_tingkat }} Siswa</td>
                                        @if (!empty($total[$tingkat->tingkat]))
                                            @php
                                                $total[$tingkat->tingkat] += $count_siswa_tingkat;
                                            @endphp
                                        @else
                                            @php
                                                $total[$tingkat->tingkat] = $count_siswa_tingkat;
                                            @endphp
                                        @endif
                                    @endforeach
                                </tr>
                            @endforeach
                            <tr style="background-color: #8bc34a;">
                                <td>TOTAL</td>
                                @foreach ($data_tingkat as $tingkat)
                                    <td style="text-align:center">{{ $total[$tingkat->tingkat] }} Siswa</td>
                                @endforeach
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')

<div class="container-fluid">
    <div class="block-header" style=" display: flex;
    justify-content: space-between;">
        <h1 style="font-size: 3rem; margin:0; padding:5px">LOG PENGGUNAAN DIAKAD</h1>
    </div>

    <div class="row clearfix" style="margin-bottom:3rem">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>1 Hari Terakhir</h2>

                </div>
                <div class="body">
                    <span style="font-size: 5rem;font-weight:bold">{{ $totalPengguna1HariTerakhir }}</span> Pengguna
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>7 Hari Terakhir</h2>

                </div>
                <div class="body">
                    <span style="font-size: 5rem;font-weight:bold">{{ $totalPengguna7HariTerakhir }}</span> Pengguna
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>30 Hari Terakhir</h2>

                </div>
                <div class="body">
                    <span style="font-size: 5rem;font-weight:bold">{{ $totalPengguna30HariTerakhir }}</span> Pengguna
                </div>
            </div>
        </div>
    </div>

    <div class="block-header" style=" display: flex;justify-content: space-between;">
        <h1 style="font-size: 2.5rem; margin-top:0; padding:5px"><span id="title-filter-date">1</span> HARI TERAKHIR
        </h1>

        <form id="form-validation" method="POST"
            action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3)) }}"
            style="display: inline;padding: 5px">
            {{ csrf_field() }}
            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <select class="form-control show-tick" name="filter_day">
                        <option selected disabled>-- Pilih Filter --</option>
                        <option value="1">1 HARI TERAKHIR</option>
                        <option value="7">7 HARI TERAKHIR</option>
                        <option value="30">30 HARI TERAKHIR</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <select class="form-control show-tick" name="filter_pengguna">
                        <option selected disabled>-- Pilih Filter --</option>
                        <option value="1-2">Guru & Tendik</option>
                        <option value="3">Siswa</option>
                        <option value="4">Wali Murid</option>
                        <option value="0">Semua</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <button class="btn btn-block form-control bg-blue waves-effect" type="submit">
                        Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="row clearfix" style="margin-bottom:3rem">
        <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
            <div class="card">
                <div class="body">
                    <canvas id="pengguna-chart" height="185"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <div class="card">
                <div class="header bg-cyan">
                    <h2 id="dynamic-title-card" style="float: left">Semua Pengguna</h2>

                    <span style="float: right">{{ $list_pengguna->count() }} Pengguna</span>

                    <div style="clear: both"></div>
                </div>
                <div class="body" style="overflow-y: scroll;max-height:500px">
                    <ul class="list-group">
                        @forelse ($list_pengguna as $pengguna)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                @if ($pengguna->pengguna->status_join_table == 1)
                                    {{ $pengguna->pengguna->fullname() }}
                                    <span class="badge badge-primary badge-pill">{{ $pengguna->total_count }}
                                        sesi</span><br>
                                    <small>TENDIK</small>
                                @elseif ($pengguna->pengguna->status_join_table == 2)
                                    {{ $pengguna->pengguna->fullname() }}
                                    <span class="badge badge-primary badge-pill">{{ $pengguna->total_count }}
                                        sesi</span><br>
                                    <small>GURU</small>
                                @elseif($pengguna->pengguna->status_join_table == 3)
                                    {{ $pengguna->pengguna->nm_pengguna }}
                                    <span class="badge badge-primary badge-pill">{{ $pengguna->total_count }}
                                        sesi</span><br>
                                    <small>Siswa kelas {{ $pengguna->pengguna->siswa->kelas->nm_kelas }}</small>
                                @elseif($pengguna->pengguna->status_join_table == 4)
                                    {{ $pengguna->pengguna->nm_pengguna }}
                                    <span class="badge badge-primary badge-pill">{{ $pengguna->total_count }}
                                        sesi</span><br>
                                    <small>Wali Murid</small>
                                @endif
                            </li>
                        @empty
                            TIDAK ADA DATA UNTUK SAAT INI
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@include('scriptjs')

<script>
    var label = @json($chart_label);
    var data = @json($chart_data);

    const ctx = document.getElementById('pengguna-chart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: label,
            datasets: [{
                label: '# Pengguna Login',
                data: data,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<script>
    var currentUrl = window.location.href;
    var parts = currentUrl.split('/');
    var filter_pengguna = parts[parts.length - 1];
    var filter_day = parts[parts.length - 2];

    if (filter_day == 1 || filter_day == 7 || filter_day == 30) {
        $('#title-filter-date').text(filter_day);
    }

    if (filter_pengguna == '1-2') {
        $('#dynamic-title-card').text('Guru & Tendik');
    } else if (filter_pengguna == 3) {
        $('#dynamic-title-card').text('Siswa');
    } else if (filter_pengguna == 4) {
        $('#dynamic-title-card').text('Wali Murid');
    }
</script>

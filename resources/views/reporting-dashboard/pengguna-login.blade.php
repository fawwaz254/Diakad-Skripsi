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
        <h1 style="font-size: 2.5rem; margin-top:0; padding:5px"><span class="title-filter-date">1</span> HARI TERAKHIR
        </h1>

        <form id="form-validation" method="POST"
            action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3)) }}"
            style="display: inline;padding: 5px">
            {{ csrf_field() }}
            <div class="row clearfix">
                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                    <select class="form-control show-tick" name="filter_day">
                        <option selected disabled>-- Pilih Filter --</option>
                        <option value="1">1 HARI TERAKHIR</option>
                        <option value="7">7 HARI TERAKHIR</option>
                        <option value="30">30 HARI TERAKHIR</option>
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
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="body">
                    <canvas id="pengguna-chart" height="70"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>Guru & Tendik</h2>
                </div>
                <div class="body">
                    <ul class="list-group">
                        @forelse ($list_guru as $guru)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $guru->pengguna->nm_pengguna }}
                                <span class="badge badge-primary badge-pill">{{ $guru->total_count }} sesi</span>
                            </li>
                        @empty
                            TIDAK ADA DATA UNTUK SAAT INI
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>Siswa</h2>
                </div>
                <div class="body">
                    <ul class="list-group">
                        @forelse ($list_siswa as $siswa)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $siswa->pengguna->nm_pengguna }}
                                <span class="badge badge-primary badge-pill">{{ $siswa->total_count }} sesi</span>
                            </li>
                        @empty
                            TIDAK ADA DATA UNTUK SAAT INI
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>Wali Murid</h2>
                </div>
                <div class="body">
                    <ul class="list-group">
                        @forelse ($list_wali_murid as $wali_murid)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $wali_murid->pengguna->nm_pengguna }}
                                <span class="badge badge-primary badge-pill">{{ $wali_murid->total_count }} sesi</span>
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


    console.log(label);
    console.log(data);
    const ctx = document.getElementById('pengguna-chart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: label,
            datasets: [{
                label: '# Grafik Pengguna Login',
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
    var filter_value = parts[parts.length - 1];

    if (filter_value == 1 || filter_value == 7 || filter_value == 30) {
        $('.title-filter-date').text(filter_value);
    }
</script>

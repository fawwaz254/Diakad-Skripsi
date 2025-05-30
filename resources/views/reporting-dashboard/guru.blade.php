@include('app')
@section('meta')
@endsection

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400&display=swap" rel="stylesheet">


<style type="text/css" media="print">
    @media print {
        .none {
            display: none !important;
        }
    }
</style>
<body>
    <section class="section">
        <div class="container-fluid">
            <div class="container">
                {{-- <br> --}}

                <!-- Widgets -->
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card">
                            <div class="body">
                                <center>
                                    <img src="https://diakad.sgp1.digitaloceanspaces.com/{{ $sekolah->nm_singkat_sekolah }}/global/logo-sekolah"
                                        alt="Logo Sekolah" style="height:90px;" />
                                    <br>
                                    <h3 style="font-family: 'Nunito', sans-serif;">Data Penggunaan
                                        {{ strtoupper(env('APP_NAME', 'EDUMATE')) }} Untuk Setiap
                                        Guru {{ $semester_aktif->tahun_ajaran }}
                                        {{ $semester_aktif->nm_semester }}</h3>
                                    <h4 style="font-family: 'Nunito', sans-serif;">Per Tanggal :
                                        {{ indonesiaDate(\Carbon\Carbon::now()->format('Y-m-d')) }}</h4>
                                </center>

                                <hr>
                                <form id="form-validation" method="POST"
                                    action="{{ url(Request::segment(1) . '/' . Request::segment(2)) }}"
                                    style="display: inline;padding: 5px">
                                    @csrf
                                    <div class="row clearfix">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <select class="form-control show-tick" name="filter_value">
                                                <option selected disabled>-- Pilih Filter --</option>
                                                <option value="1" {{ $filter_value == '1' ? 'selected' : '' }}>
                                                    Biodata</option>
                                                <option value="2" {{ $filter_value == '2' ? 'selected' : '' }}>
                                                    Kesekretariatan</option>
                                                <option value="3" {{ $filter_value == '3' ? 'selected' : '' }}>
                                                    Rapor Sisipan</option>
                                                <option value="4" {{ $filter_value == '4' ? 'selected' : '' }}>
                                                    E-Learning Materi</option>
                                                <option value="5" {{ $filter_value == '5' ? 'selected' : '' }}>
                                                    E-Learning Soal</option>
                                                <option value="6" {{ $filter_value == '6' ? 'selected' : '' }}>
                                                    Presensi</option>
                                                <option value="7" {{ $filter_value == '7' ? 'selected' : '' }}>
                                                    Jurnal Harian</option>
                                                <option value="8" {{ $filter_value == '8' ? 'selected' : '' }}>
                                                    Sarana Prasarana</option>
                                                <option value="9" {{ $filter_value == '9' ? 'selected' : '' }}>
                                                    Laporan</option>
                                                <option value="10" {{ $filter_value == '10' ? 'selected' : '' }}>
                                                    Reward Siswa</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-10 col-sm-4 col-xs-4">
                                            <div class="col-md-5">
                                                <label>Tanggal Mulai</label>
                                                <input type="date" class="form-control" id="start_date" value="{{ $start_date }}"
                                                    name="start_date" aria-required="true" aria-invalid="true">
                                            </div>

                                            <div class="col-md-5">
                                                <label>Tanggal Akhir</label>
                                                <input type="date" class="form-control" id="end_date" value="{{ $end_date }}"
                                                    name="end_date" aria-required="true" aria-invalid="true">
                                            </div>
                                        </div>
                                            {{-- <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            <select class="form-control show-tick" name="filter_tanggal">
                                                <option selected value="1"
                                                    {{ $filter_tanggal == '1' ? 'selected' : '' }}>Semua</option>
                                                <option value="7" {{ $filter_tanggal == '7' ? 'selected' : '' }}>
                                                    Satu Minggu</option>
                                                <option value="30" {{ $filter_tanggal == '30' ? 'selected' : '' }}>
                                                    Satu Bulan</option>
                                                <option value="365"
                                                    {{ $filter_tanggal == '365' ? 'selected' : '' }}>Satu Tahun
                                                </option>
                                            </select>
                                        </div> --}}
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 none">
                                        <button class="btn btn-block form-control bg-cyan waves-effect">
                                            Filter
                                        </button>
                                        <button onclick="window.print()"
                                            class="btn btn-block form-control bg-cyan waves-effect">
                                            Print
                                        </button>
                                    </div>
                                </form>

                                {{-- chart yang berubah di beberapa device bisa dikurangi dengan membuat height dan width yang tetap --}}
                                <div class="col-12">
                                    <canvas id="pengguna-chart" height="450"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Widgets -->

        </div>
    </section>
</body>

<script>
    $(document).ready(function() {
        var filterValue = $('select[name="filter_value"]').val();

        function getFilterValue(filter) {
            if (filter == '3' || filter == '6') {
                $('select[name="filter_tanggal"]').prop('disabled', true)
                    .css("cursor", "not-allowed").val(30);
            } else {
                $('select[name="filter_tanggal"]').prop('disabled', false)
                    .css("cursor", 'default').val(1);
            }
        }

        $('select[name="filter_value"]').on('change', function() {
            var selectedValue = $(this).val();
            getFilterValue(selectedValue);
        });

        getFilterValue(filterValue);
    });

    const chartData = @json($chartData);
    const label = chartData.labels;
    const data = chartData.data;

    // Render chart
    const ctx = document.getElementById('pengguna-chart');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: label,
            datasets: [{
                label: 'Data aktivitas pengguna',
                data: data,
                backgroundColor: 'rgba(0, 188, 212, 0.5)',
                borderColor: 'rgba(0, 188, 212, 1)',
                borderWidth: 1,
            }],
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        },
    });
</script>

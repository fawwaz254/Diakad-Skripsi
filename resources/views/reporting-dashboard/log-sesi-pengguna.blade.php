<style>
    .lds-ring {
        color: #00BCD4;
    }

    .lds-ring,
    .lds-ring div {
        box-sizing: border-box;
    }

    .lds-ring {
        display: inline-block;
        position: relative;
        width: 80px;
        height: 80px;
    }

    .lds-ring div {
        box-sizing: border-box;
        display: block;
        position: absolute;
        width: 64px;
        height: 64px;
        margin: 8px;
        border: 8px solid currentColor;
        border-radius: 50%;
        animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
        border-color: currentColor transparent transparent transparent;
    }

    .lds-ring div:nth-child(1) {
        animation-delay: -0.45s;
    }

    .lds-ring div:nth-child(2) {
        animation-delay: -0.3s;
    }

    .lds-ring div:nth-child(3) {
        animation-delay: -0.15s;
    }

    @keyframes lds-ring {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

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
            action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/filter') }}"
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
                    <button class="btn btn-block form-control bg-cyan waves-effect" type="submit">
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
                            <li style="cursor: pointer"
                                class="list-group-item d-flex justify-content-between align-items-center"
                                onclick="showModalDetail('{{ $pengguna->pengguna->id_pengguna }}')">
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

{{-- MODAL ACTION --}}
<div class="modal" tabindex="-1" role="dialog" id="modal-action">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <h1 style="font-size: 3rem" class="modal-title"></h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>

@include('scriptjs')

<script>
    var current_url = window.location.href;
    var parts = current_url.split('/');
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
            }],
            backgroundColor: '#00BCD4',
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
    var loading_element =
        `<div style="text-align:center"><div class="lds-ring"><div></div><div></div><div></div><div></div></div></div>`;

    function showModalDetail(id_pengguna) {
        $('.modal-title').empty();
        $('.modal-body').empty();
        $('.modal-body').html(loading_element);
        $('#modal-action').modal('show');

        $.ajax({
            type: "POST",
            url: '{{ url()->current() }}',
            data: {
                _token: $("meta[name='csrf-token']").attr("content"),
                id_pengguna,
            },
            success: function(response) {

                $('.modal-title').empty();

                var table_element = `
                <table class="table table-striped" style="width: 100%">
                    <tr>
                        <th>No.</th>
                        <th>Method</th>
                        <th>Route</th>
                        <th>Aktivitas</th>
                    </tr>
                `;

                let total_activity_time_in_seconds = 0;

                response.data.log.forEach((item, index) => {
                    if (index > 0) {
                        const current_created_at = new Date(item.created_at);
                        const previous_created_at = new Date(response.data.log[index - 1]
                            .created_at);

                        const diff_in_milliseconds = current_created_at - previous_created_at;
                        const diff_in_seconds = diff_in_milliseconds / 1000;
                        const diff_in_minutes = diff_in_seconds / 60;

                        if (diff_in_minutes <= 10) {
                            total_activity_time_in_seconds += diff_in_seconds;
                        }
                    }

                    var parts = item.route.split('/');

                    table_element += `
                        <tr>
                            <td style="text-align:center">${index + 1}</td>
                            <td style="text-align:center"><span class="badge" style="border-radius:15px;padding:.5rem 1rem;">${item.method}</span></td>
                            <td>${item.route}</td>
                            <td><span style="padding:.7rem" class="badge bg-cyan">${parts[4].replace(/-/g, ' ').replace(/\b\w/g, c => c.toUpperCase())}</span> <span style="padding:.7rem" class="badge bg-teal">${parts[5] ? parts[5].replace(/-/g, ' ').replace(/\b\w/g, c => c.toUpperCase()) : ''}</span></td>
                        </tr>
                        `;
                });

                table_element += `</table>`
                $('.modal-body').html(table_element);

                const total_activity_time = formatTime(total_activity_time_in_seconds);
                $('.modal-title').html(
                    `${response.data.nm_pengguna} <span style="text-align:center"><span class="badge" style="border-radius:15px;padding:.5rem 1rem;">${total_activity_time}</span>`
                );
            },
        });
    }

    function formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const remainingSeconds = Math.floor(seconds % 60);

        return `${hours} jam ${minutes} menit ${remainingSeconds} detik`;
    }
</script>

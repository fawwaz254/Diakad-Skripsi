<div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card is-gap">
            <div class="header">
                <h2>Filter Data</h2>
            </div>
            <div class="body">
                <div class="row clearfix">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <h4>
                            Kelas
                        </h4>
                        <select class="form-control show-tick" name="id_kelas" required="">
                            <option value="" selected disabled>-- Pilih Kelas --</option>
                            @foreach ($data_kelas as $data)
                                <option value="{{ $data->id_kelas }}"
                                    {{ !empty(Request::input('id_kelas')) && $data->id_kelas == Request::input('id_kelas') ? 'selected' : '' }}>
                                    {{ $data->nm_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <h4>
                            Jenis Aktivitas
                        </h4>
                        <select class="form-control show-tick" name="jenis_aktivitas_reward" required=""
                            onchange="changeJenis()">
                            @foreach ($data_jenis_aktivitas as $jenis_aktivitas)
                                <option value="{{ $jenis_aktivitas->id_jenis_aktivitas_reward }}"
                                    {{ !empty(Request::input('jenis')) && $jenis_aktivitas->id_jenis_aktivitas_reward == Request::input('jenis') ? 'selected' : '' }}>
                                    {{ $jenis_aktivitas->nm_jenis_aktivitas_reward }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <h4>
                            Aktivitas yang diinput
                        </h4>
                        <select class="form-control show-tick" name="aktivitas_reward" required=""
                            id="aktivitas_reward">
                            <option value="0" {{ Request::input('jenis') == '0' ? 'selected' : '' }}>
                                Semua
                            </option>
                            @foreach ($data_aktivitas_reward as $aktivitas_reward)
                                <option value="{{ $aktivitas_reward->id_aktivitas_reward_siswa }}"
                                    {{ !empty(Request::input('id_aktivitas_reward')) && $aktivitas_reward->id_aktivitas_reward_siswa == Request::input('id_aktivitas_reward') ? 'selected' : '' }}>
                                    {{ $aktivitas_reward->nm_aktivitas_reward_siswa }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row clearfix">
                    @php
                        $data_month = [
                            (object) ['id' => 1, 'name' => 'Januari'],
                            (object) ['id' => 2, 'name' => 'Februari'],
                            (object) ['id' => 3, 'name' => 'Maret'],
                            (object) ['id' => 4, 'name' => 'April'],
                            (object) ['id' => 5, 'name' => 'Mei'],
                            (object) ['id' => 6, 'name' => 'Juni'],
                            (object) ['id' => 7, 'name' => 'Juli'],
                            (object) ['id' => 8, 'name' => 'Agustus'],
                            (object) ['id' => 9, 'name' => 'September'],
                            (object) ['id' => 10, 'name' => 'Oktober'],
                            (object) ['id' => 11, 'name' => 'November'],
                            (object) ['id' => 12, 'name' => 'Desember'],
                        ];
                        $data_year = [2025, 2024, 2023];
                    @endphp
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <h4>
                            Bulan
                        </h4>
                        <select class="form-control show-tick" name="month" required="">
                            @foreach ($data_month as $month)
                                <option value="{{ $month->id }}" {{ $month->id == $now->month ? 'selected' : '' }}>
                                    {{ $month->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <h4>
                            Tahun
                        </h4>
                        <select class="form-control show-tick" name="year" required="">
                            @foreach ($data_year as $year)
                                <option value="{{ $year }}" {{ $year == $now->year ? 'selected' : '' }}>
                                    {{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                        <h4>
                            Action
                        </h4>
                        <button class="btn bg-red waves-effect" onclick="filterAction()">
                            <i class="material-icons">save</i>
                            <span>Filter</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (!empty(Request::input('jenis')))
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>Rekap Aktivitas Reward</h2>
                </div>
                <div class="body">
                    <h3>Bulan {{ $now->isoFormat('MMMM Y') }}</h3>
                    @if (!empty(Request::input('jenis')) && Request::input('jenis') == 1)
                        <h4>Rekap Aktivitas Harian</h4>
                        <div class="table-responsive">
                            <table id="primary_table"
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;" rowspan="2">Nama Siswa</th>
                                        <th style="text-align: center;" rowspan="2">NIS</th>
                                        <th style="text-align: center;" rowspan="2">Kelas</th>
                                        <th style="text-align: center;" colspan="{{ $dates->count() }}">Tanggal</th>
                                    </tr>
                                    <tr>
                                        @foreach ($dates as $d)
                                            <th>{{ $d->format('d') }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list_data as $siswa)
                                        <tr>
                                            <td>{{ $siswa->nm_pengguna }}</td>
                                            <td>{{ $siswa->nis_siswa }}</td>
                                            <td>{{ $siswa->nm_kelas }}</td>
                                            @foreach ($dates as $d)
                                                @php
                                                    $key = $siswa->id_siswa . '_' . $d->format('Y-m-d');
                                                @endphp
                                                @if (isset($data_reward_siswa[$key]))
                                                    <td style="background-color: #bffa85;">1x</td>
                                                @else
                                                    <td></td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if (!empty(Request::input('jenis')) && Request::input('jenis') == 2)
                        <h4>Rekap Aktivitas Mingguan</h4>
                        <div class="table-responsive">
                            <table id="primary_table"
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;" rowspan="2">Nama Siswa</th>
                                        <th style="text-align: center;" rowspan="2">NIS</th>
                                        <th style="text-align: center;" rowspan="2">Kelas</th>
                                        <th style="text-align: center;" colspan="{{ count($week_dates) }}">Minggu </th>
                                    </tr>
                                    <tr>
                                        @foreach ($week_dates as $d)
                                            <th>{{ $d['start']->isoFormat('dddd, DD MMM') }} -
                                                {{ $d['end']->isoFormat('dddd, DD MMM') }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list_data as $siswa)
                                        <tr>
                                            <td>{{ $siswa->nm_pengguna }}</td>
                                            <td>{{ $siswa->nis_siswa }}</td>
                                            <td>{{ $siswa->nm_kelas }}</td>
                                            @foreach ($week_dates as $d)
                                                @php
                                                    $key = $siswa->id_siswa . '_' . $d['start']->format('Y-m-d');
                                                @endphp
                                                @if (isset($data_reward_siswa[$key]))
                                                    <td style="background-color: #bffa85;">1x</td>
                                                @else
                                                    <td></td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    @if (!empty(Request::input('jenis')) && Request::input('jenis') == 3)
                        <h4>Rekap Aktivitas Bulanan</h4>
                        <div class="table-responsive">
                            <table id="primary_table"
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Nama Siswa</th>
                                        <th>NIS</th>
                                        <th>Kelas</th>
                                        <th>Bulan {{ $now->isoFormat('MMMM Y') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list_data as $siswa)
                                        <tr>
                                            <td>{{ $siswa->nm_pengguna }}</td>
                                            <td>{{ $siswa->nis_siswa }}</td>
                                            <td>{{ $siswa->nm_kelas }}</td>
                                            @if ($data_reward_siswa->where('id_siswa', $siswa->id_siswa)->first())
                                                <td style="background-color: #bffa85;">
                                                    1x
                                                </td>
                                            @else
                                                <td></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    // var name_kelas = $('select[name=id_kelas] option:selected').text();
    let modul_url = 'reward-siswa';
    let fetch_aktivitas_url = base_url + '/' + role_url + '/' + modul_url + '/ajax-get-aktivitas-reward';

    // var header_pdf = '{{ $auth_data->sekolah_data->nm_sekolah }}' + ' -' + name_kelas;
    var header_pdf = '{{ $auth_data->sekolah_data->nm_sekolah }}';

    var buttonConfigReward = {
        buttons: [{
            extend: "print",
            text: "PDF",
            title: header_pdf,
            className: "bg-pink waves-effect",
            orientation: "landscape",
            exportOptions: {
                columns: ":visible"
            },
            customize: function(e) {
                $(e.document.body).css("font-size", "10pt"), $(e.document.body).find("table").addClass(
                    "compact").css("font-size", "inherit")
            }
        }, {
            extend: "excelHtml5",
            className: "bg-green waves-effect",
            exportOptions: {
                columns: ":visible"
            }
        }],
        dom: {
            button: {
                className: "btn"
            }
        }
    };

    var primary_table = $('#primary_table').DataTable({
        paging: false,
        dom: 'Bfrtip',
        responsive: false,
        scrollX: true,
        // lengthMenu: dtLengButton,
        buttons: buttonConfigReward,
    })

    function changeJenis() {
        $.ajax({
            url: fetch_aktivitas_url,
            type: "GET",
            data: {
                jenis_aktivitas: $('select[name=jenis_aktivitas_reward]').val(),
                id_kelas: ''
            },
            success: function(response) {
                $('#aktivitas_reward').html(response);
            }
        });
    }

    function filterAction() {
        var id_kelas = $('select[name=id_kelas]').val();
        if (!id_kelas) {
            swal({
                title: 'Pilih Kelas',
            });
        } else {
            loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}?jenis=' + $(
                    'select[name=jenis_aktivitas_reward]').val() + '&id_aktivitas_reward=' + $(
                    'select[name=aktivitas_reward]').val() + '&id_kelas=' + $('select[name=id_kelas]').val() +
                '&month=' + $('select[name=month]').val() + '&year=' + $('select[name=year]').val());
        }
    }
</script>

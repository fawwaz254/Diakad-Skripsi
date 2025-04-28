<style>
    table thead th {
        text-align: center;
        vertical-align: middle !important;
    }
</style>

<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1)) }}#{{ Request::segment(2) }}/{{ Request::segment(3) }}/rekap-bulanan-form-harian/Fh2L4170978280965e937193feec"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h1>{{ $auth_data->sekolah_data->nm_sekolah }}</h1>
                    <h3>Bulan: {{ $bulan->nm_bulan }}</h3>
                    {{-- <a target="_blank"
                            href="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/' . $bulan->id_bulan . '/' . $tahun . '/download') }}"
                    class="btn btn-success waves-effect"><i class="material-icons">print</i><span>Download
                        Excel</span></a> --}}
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
                                        <div class="tanggal-header">
                                            {{ substr($date->format('l'), 0, 3) }}<br>
                                            {{ $date->format('d') }}
                                        </div>
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($data_pengguna as $pengguna)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $pengguna->nm_pengguna }}</td>
                                    @foreach ($dates as $date)
                                        @php
                                            $key = $pengguna->id_pengguna . $date->format('Y-m-d');
                                            $jawaban = $dataJawaban[$key] ?? [];
                                        @endphp
                                    <td>
                                            @foreach ($jawaban as $item)
                                                @if(trim($item) != '')
                                                {{ ' - ' . $item }}<br>
                                                @endif
                                            @endforeach
                                    </td>
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
    var header_pdf = '{{ $auth_data->sekolah_data->nm_sekolah }}';
    var nm_bulan = '{{ $bulan->nm_bulan }}';

    var buttonConfigHumas = {
        buttons: [{
                extend: "print",
                text: "PDF",
                title: header_pdf + '<br>' + 'Monitoring Kegiatan Harian' + '<br>' + 'Bulan : ' + nm_bulan,
                className: "bg-pink waves-effect",
                orientation: "landscape",
                exportOptions: {
                    columns: ":visible"
                },
                customize: function(e) {
                    $(e.document.body).css("font-size", "10pt");
                    $(e.document.body).find("table").addClass("compact").css("font-size", "inherit");
                }
            },
            {
                extend: "excelHtml5",
                className: "bg-green waves-effect",
                exportOptions: {
                    columns: ":visible"
                },
            },
        ],
        dom: {
            button: {
                className: "btn"
            }
        }
    }
    var primary_table = $('#primary_table').DataTable({
        dom: 'Bfrtip',
        lengthMenu: dtLengButton,
        buttons: buttonConfigHumas,
        lengthMenu: [
            [-1],
            ['All'],
        ],
    })
</script>
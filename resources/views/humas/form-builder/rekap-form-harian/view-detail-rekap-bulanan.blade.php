<style>
    table th,
    .is-center {
        text-align: center;
        vertical-align: middle !important;
    }
</style>
<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        Filter {{ $form->nm_form }}
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
                                        <option {{ $bulan->id_bulan == $data->id_bulan ? 'selected' : '' }} value="{{ $data->id_bulan }}">{{ $data->nm_bulan }}</option>
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
                                        @for ($i = 2015; $i <= 2025; $i++) <option {{ $tahun == $i ? 'selected' : '' }} value="{{ $i }}">
                                            {{ $i }}</option>
                                            @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        @if(isset($datas['allKelas']))
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <label>Kelas</label>
                                    <select class="form-control show-tick" name="id_kelas">
                                        @foreach ($datas['allKelas'] as $k)
                                        <option {{ $k->id_kelas == $datas['id_kelas'] ? 'selected' : '' }} value="{{ $k->id_kelas }}">{{ $k->nm_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <label>Pertanyaan</label>
                                    <select class="form-control show-tick" name="id_pertanyaan">
                                        <option value="0" {{ $id_pertanyaan == '0' ? 'selected' : '' }}>Semua
                                        </option>
                                        @foreach ($list_pertanyaan as $pertanyaan)
                                        <option {{ $pertanyaan->id_pertanyaan_form == $id_pertanyaan ? 'selected' : '' }} value="{{ $pertanyaan->id_pertanyaan_form }}">
                                            {{ $pertanyaan->nm_pertanyaan_form }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i class="material-icons">save</i><span>Filter</span></button>
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
                        Rekap Bulan {{ $bulan->nm_bulan }}
                        {{-- <a target="_blank"
                            href="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/' . $bulan->id_bulan . '/' . $tahun . '/download') }}"
                        class="btn btn-success waves-effect"><i class="material-icons">print</i><span>Download
                            Excel</span></a> --}}
                    </h2>

                    
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable" id="primary_table">
                            <thead>
                                <tr>
                                    <th rowspan="2">No. </th>
                                    <th rowspan="2">Nama</th>
                                    <th colspan="{{ $dates->count() }}">Tanggal</th>
                                </tr>
                                <tr>
                                    @foreach ($dates as $date)
                                    <th>
                                        {{ substr($date->format('l'), 0, 3) }}
                                        <br>
                                        {{ $date->format('d') }}
                                    </th>

                                    @php
                                    $total_pengisi[$date->format('d')] = 0;
                                    $total_normal[$date->format('d')] = 0;
                                    $total_warning[$date->format('d')] = 0;
                                    @endphp
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no = 1;
                                @endphp
                                @foreach ($data_pengguna as $pengguna)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $pengguna->fullname() }}</td>
                                    @foreach ($dates as $date)
                                    @if (isset($dataJawaban[$pengguna->id_pengguna . $date->format('Y-m-d')][0]))
                                    @if ($id_pertanyaan == '0')
                                    <td style="text-align:center; vertical-align:middle !important; ">
                                        <button onclick="cekJawaban(this)" class="btn btn-success btn-circle waves-effect waves-circle waves-float" data-id=" {{ $dataJawaban[$pengguna->id_pengguna . $date->format('Y-m-d')] }}"><i class="material-icons">remove_red_eye</i></button>
                                    </td>
                                    @else
                                    @if (is_array($dataJawaban[$pengguna->id_pengguna . $date->format('Y-m-d')][0]) &&
                                    count($dataJawaban[$pengguna->id_pengguna . $date->format('Y-m-d')][0]) > 0)
                                    <td @if(str_contains($dataJawaban[$pengguna->id_pengguna . $date->format('Y-m-d')][1],'#'))

                                        style="color:white; text-align:center; vertical-align:middle !important; background-color:{{$dataJawaban[$pengguna->id_pengguna . $date->format('Y-m-d')][1]}}"
                                        @endif

                                        >
                                        @foreach ($dataJawaban[$pengguna->id_pengguna . $date->format('Y-m-d')][0] as $item)
                                        {{ ' - ' . $item }}<br>
                                        @endforeach
                                    </td>
                                    @else
                                    <td @if(str_contains($dataJawaban[$pengguna->id_pengguna . $date->format('Y-m-d')][1],'#'))

                                        style="text-align:center; vertical-align:middle !important; background-color:{{$dataJawaban[$pengguna->id_pengguna . $date->format('Y-m-d')][1]}}"
                                        @else
                                        style="text-align:center; vertical-align:middle !important; background-color:#d4ffdf"


                                        @endif
                                        >
                                        {{ $dataJawaban[$pengguna->id_pengguna . $date->format('Y-m-d')][0] }}
                                    </td>
                                    @endif
                                    @endif
                                    @else
                                    <td></td>
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


<div class="modal" tabindex="-1" role="dialog" id="myModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <h4 class="modal-title" style="text-align: center">Detail Jawaban</h4>
            </div>
            <div id="place">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    function filterAction() {
        loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/{{ Request::segment(4) }}/' +
            '{{ $form->id_form }}' + '/' + $('select[name=id_bulan]').val() + '/' + $('select[name=tahun]')
            .val() + '/' + $('select[name=id_kelas]').val() + '/' + $('select[name=id_pertanyaan]').val());
    }

    var primary_table = $('#primary_table').DataTable({
        ordering: false,

        paging: false
    });

    function cekJawaban(el) {
        var item = $(el);

        $('button').attr('disabled', 'disabled');
        $.ajax({
            type: "POST",
            url: `{{ Request::segment(1) }}/{{ Request::segment(2) }}/{{ Request::segment(3) }}/get-detail-jawaban`,
            data: {
                id_jawaban_form: item.attr('data-id'),
            },
            success: function(response) {
                $('#place').html('');
                var html = '<table  class="table">';
                html += '<tr>';
                html += '<th>No</th>';
                html += '<th>Pertanyaan</th>';
                html += '<th>Jawaban</th>';
                html += '</tr>';
                $.each(response.detail_jawaban_form, function(key, item) {
                    html += '<tr >';
                    html += '<td>' + (key + 1) + '</td>';
                    html += '<td>' + item.pertanyaan_form.nm_pertanyaan_form + '</td>';
                    html += '<td>' + item.jawaban + '</td>';
                    html += '</tr>';
                });
                html += '</table>';
                $('#place').html(html);
            },
            complete: function() {
                $('button').removeAttr('disabled', 'disabled');
                $('#myModal').modal('show');
            }
        });

    };
</script>
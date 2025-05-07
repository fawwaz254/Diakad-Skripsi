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

    @if (isset($data['allKelas']))
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card is-gap">
                    <div class="header">
                        <h2>
                            Filter Form {{ $form->nm_form }}
                        </h2>
                    </div>

                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Kelas
                                </h2>
                                <select class="form-control show-tick" name="id_kelas">
                                    @foreach ($data['allKelas'] as $k)
                                        <option value="{{ $k->id_kelas }}"
                                            @if ($data['id_kelas'] == $k->id_kelas) SELECTED @endif>
                                            {{ $k->nm_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Tanggal
                                </h2>
                                <input type="date" class="form-control" data-date="" data-date-format="DD/MM/YYYY"
                                    value="{{ $date }}" name="date" aria-required="true" aria-invalid="true">
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i
                                        class="material-icons">save</i><span>Filter</span></button>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    @endif
</div>
</div>
</div>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Rekap Harian
                        {{-- {{ $bulan->nm_bulan }} --}}
                        {{-- <a target="_blank"
                            href="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/' . $bulan->id_bulan . '/' . $tahun . '/download') }}"
                        class="btn btn-success waves-effect"><i class="material-icons">print</i><span>Download
                            Excel</span></a> --}}
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive" style="padding: 10px">
                        <table class="table table-bordered table-striped table-hover dataTable" id="primary_table">
                            <thead>
                                <tr>
                                    <th rowspan="2">No. </th>
                                    <th rowspan="2">Nama</th>
                                    <th colspan="{{ $list_pertanyaan->count() }}">Pertanyaan</th>
                                </tr>
                                <tr>
                                    @foreach ($list_pertanyaan as $pertanyaan_form)
                                        <th>
                                            {{ $pertanyaan_form->nm_pertanyaan_form }}
                                        </th>
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
                                        @foreach ($list_pertanyaan as $pertanyaan_form)
                                            @if (isset($dataJawaban[$pengguna->id_pengguna . $pertanyaan_form->id_pertanyaan_form]))
                                                @if ($pertanyaan_form->jenis_pertanyaan == '4')
                                                    <td>
                                                        @foreach ($dataJawaban[$pengguna->id_pengguna . $pertanyaan_form->id_pertanyaan_form] as $item)
                                                            {{ ' - ' . $item }}<br>
                                                        @endforeach
                                                    </td>
                                                @elseif ($pertanyaan_form->jenis_pertanyaan == '2')
                                                    <td><a
                                                            href="{{ Storage::disk('spaces')->url($dataJawaban[$pengguna->id_pengguna . $pertanyaan_form->id_pertanyaan_form]) }}">
                                                            <img src="{{ Storage::disk('spaces')->url($dataJawaban[$pengguna->id_pengguna . $pertanyaan_form->id_pertanyaan_form]) }}"
                                                                alt="" style="width:300px; height:300px">
                                                        </a>
                                                    </td>
                                                @else
                                                    <td>{{ $dataJawaban[$pengguna->id_pengguna . $pertanyaan_form->id_pertanyaan_form] }}
                                                    </td>
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


{{-- <div class="modal" tabindex="-1" role="dialog" id="myModal">
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
</div> --}}


<script>
    var primary_table = $('#primary_table').DataTable({
        dom: 'Bfrtip',
        lengthMenu: dtLengButton,
        buttons: dtButtonConfig,
        lengthMenu: [
            [-1],
            ['All'],
        ],
    });

    function filterAction() {
        loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/{{ Request::segment(4) }}/' +
            '{{ $form->id_form }}' + '/' + $('input[name=date]').val() + '/');
    }

    

    $("input").on("change", function() {
        this.setAttribute(
            "data-date",
            moment(this.value, "YYYY-MM-DD")
            .format(this.getAttribute("data-date-format"))
        )
    }).trigger("change")
</script>
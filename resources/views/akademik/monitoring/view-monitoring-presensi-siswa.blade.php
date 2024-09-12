<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        FILTER BULAN
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
                                            <option {{ $bulan->id_bulan == $data->id_bulan ? 'selected' : '' }}
                                                value="{{ $data->id_bulan }}">
                                                {{ $data->nm_bulan }}
                                            </option>
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
                                        @for ($i = 2015; $i <= 2025; $i++)
                                            <option {{ $tahun == $i ? 'selected' : '' }} value="{{ $i }}">
                                                {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <label>Kelas</label>
                                    <select class="form-control show-tick" name="id_kelas">
                                        <option disabled>Pilih Kelas</option>
                                        @foreach ($data_kelas as $kelas)
                                            <option value="{{ $kelas->id_kelas }}"
                                                {{ $kelas->id_kelas == $id_kelas ? 'selected' : '' }}>
                                                {{ $kelas->nm_kelas }}
                                            </option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()">
                                <i class="material-icons">save</i><span>Filter</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function filterAction() {
        loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/' + $('select[name=id_kelas]').val() + '/' + $(
            'select[name=id_bulan]').val() + '/' + $(
            'select[name=tahun]').val());
    }
</script>

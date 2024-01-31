<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        REKAP ABSENSI MAGANG
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Periode Magang</label>
                            <select class="form-control show-tick" name="id_periode_magang" id="id_periode_magang">
                                <option value="0"> Semua Periode </option>
                                @foreach ($data_periode_magang as $data)
                                    <option value="{{ $data->id_periode_magang }}"
                                        @if ($semester_aktif->id_semester == $data->id_semester) selected @endif>{{ $data->nm_magang }} -
                                        {{ $data->nm_periode_magang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <label> Date </label>
                            <input type="date" class="form-control" data-date="" data-date-format="DD/MM/YYYY"
                                value="{{ $date }}" name="date" aria-required="true" aria-invalid="true">
                        </div>
                    </div>
                    <div class="">
                        <input type="hidden" name="id_rekanan_magang" value="0">
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-red waves-effect" onclick="filterData()"><i
                                    class="material-icons">save</i><span>Filter</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')

<script type="text/javascript">
    function filterData() {
        loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}' + '/detail/' + $(
            'input[name=id_rekanan_magang]').val() + '/' + $('select[name=id_periode_magang]').val() + '/' + $(
            'input[name=date]').val());
    }

    $("input").on("change", function() {
        this.setAttribute(
            "data-date",
            moment(this.value, "YYYY-MM-DD")
            .format(this.getAttribute("data-date-format"))
        )
    }).trigger("change")
</script>

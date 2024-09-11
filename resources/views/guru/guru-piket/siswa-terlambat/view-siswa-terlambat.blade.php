<style>
    input {
        position: relative;
        width: 150px;
        height: 20px;
        color: white;
    }

    input:before {
        position: absolute;
        top: 3px;
        left: 3px;
        content: attr(data-date);
        display: inline-block;
        color: black;
    }

    input::-webkit-datetime-edit,
    input::-webkit-inner-spin-button,
    input::-webkit-clear-button {
        display: none;
    }

    input::-webkit-calendar-picker-indicator {
        position: absolute;
        top: 3px;
        right: 0;
        color: black;
        opacity: 1;
    }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

            <div class="card" style="margin-top: 10px">
                <div class="header">
                    <h2>Filter Data</h2>
                </div>

                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/filter-siswa-terlambat') }}">
                        {{ csrf_field() }}
                        <div class="row clearfix">
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Kelas
                                </h2>
                                <select class="form-control show-tick" name="id_kelas">
                                    <option value="0">-- Semua --</option>
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id_kelas }}">{{ $k->nm_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-12 col-xs-12">
                                <h2 class="card-inside-title">
                                    Date
                                </h2>
                                <input type="date" class="form-control" data-date="" data-date-format="DD/MM/YYYY"
                                    value="{{ $date }}" name="date" aria-required="true" aria-invalid="true">
                            </div>

                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                <div>
                                    <h2 class="card-inside-title" style="visibility: hidden;">
                                        1
                                    </h2>
                                    <button class="btn btn-block bg-red waves-effect" type="submit">
                                        <i class="material-icons">save</i><span>Tampilkan</span>
                                    </button>
                                </div>

                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script type="text/javascript">
    // let modul_url = 'guru-piket';
    // let filter_url = base_url + '/' + role_url + '/filter-siswa-terlambat';
    // console.log(filter_url);

    $("input").on("change", function() {
        this.setAttribute(
            "data-date",
            moment(this.value, "YYYY-MM-DD")
            .format(this.getAttribute("data-date-format"))
        )
    }).trigger("change")


    // function filterAction() {
    //     loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/detail/' + $('input[name=kelas]').val() + '/' + $(
    //         'input[name=date]').val());
    // }
</script>

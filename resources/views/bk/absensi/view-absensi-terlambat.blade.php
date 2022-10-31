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

                    <div class="row clearfix">
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Date
                            </h2>
                            <input type="date" class="form-control" data-date="" data-date-format="DD/MM/YYYY"
                                value="{{ $now }}" name="date" aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <div>
                                <h2 class="card-inside-title" style="visibility: hidden;">
                                    1
                                </h2>
                                <button class="btn btn-block bg-red waves-effect" type="submit"
                                    onclick="filterAction()"><i
                                        class="material-icons">save</i><span>Tampilkan</span></button>
                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <br>
    <div class="row clearfix" style="margin-top: 10px">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>List Siswa Terlambat ({{ $now  }})</h2>
                </div>

                <div class="body">
                    <div class="table-responsive ">
                        <table class="table table-bordered" width="600px">
                            <thead style="background:#9C27B0;color:white">
                                <tr>
                                    <th style="text-align: center;">#</th>
                                    <th> <input id="checkbox_select_all_primary_table" type="checkbox" name="select_all" class="filled-in">
                                        <label for="checkbox_select_all_primary_table" style="margin-bottom: -10px;"></label></th>
                                    <th style="text-align: center;">Nama</th>
                                    <th>Kelas</th>
                                    <th>Check In</th>
                                    <th>Jarak Telat</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($terlambat as $key => $r)
                                    @if ($key % 2 == 1)
                                        <tr style="background: #DDA0DD">
                                        @else
                                        <tr>
                                    @endif

                                    <th style="text-align: center;">{{  $loop->iteration }}</th>
                                    <th><input id="checkbox-' + data.id + '" type="checkbox" name="id_tagihan_biaya[]" class="filled-in" value="' + data.id + '">
                                        <label for="checkbox-' + data.id + '"></label></th>
                                    <th>{{ $r->pengguna->nm_pengguna }}</th>
                                    <th>{{ $r->pengguna->siswa->kelas->nm_kelas }}</th>
                                    <th>{{ $r->check_in }}</th>
                                    @php
                                        $options = [
                                            'join' => ', ',
                                            'parts' => 2,
                                            'syntax' => \Carbon\CarbonInterface::DIFF_ABSOLUTE,
                                        ];
                                    @endphp
                                    <th>
                                        {{ \Carbon\carbon::parse($r->check_in)->diffForHumans(\Carbon\carbon::parse($absensi_siswa->start_time), $options) }}
                                    </th>
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
<script type="text/javascript">
//  alert('{{ Request::segment(2) }}/{{ Request::segment(3) }}/' + $('input[name=date]').val() + '');
    $("input").on("change", function() {
        this.setAttribute(
            "data-date",
            moment(this.value, "YYYY-MM-DD")
            .format(this.getAttribute("data-date-format"))
        )
    }).trigger("change")



    function filterAction() {
       
        loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/' + $('input[name=date]').val());
    }

    $(document).ready(function() {        
        /* Select All Checkbox */
        $('#checkbox_select_all_primary_table').change(function() {
            var select_all_checked = this.checked;
            var rows = primary_table.rows({ 'search': 'applied' }).nodes();

            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });
    });
</script>

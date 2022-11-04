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
                    <h2>List Siswa Terlambat ({{ $now }}) <br><br>
                        <button class="btn  bg-blue waves-effect" onclick="sendSiswaTerlambat()"><i
                                class="material-icons">add</i><span>Kirim ke Pelanggaran</span></button>
                    </h2>
                </div>

                <div class="body">
                    <div class="table-responsive ">
                        <table class="table table-bordered" width="600px">
                            <thead style="background:#9C27B0;color:white">
                                <tr>
                                    <th style="text-align: center;">#</th>
                                    <th style="text-align: center;"> <input id="checkbox_select_all_primary_table"
                                            type="checkbox" name="select_all" class="filled-in">
                                        <label for="checkbox_select_all_primary_table"
                                            style="margin-bottom: -10px;"></label>
                                    </th>
                                    <th style="text-align: center;">Nama</th>
                                    <th>Kelas</th>
                                    <th>Check In</th>
                                    <th>Jarak Telat</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($terlambat as $key => $r)
                                    @if ($sudah_terkirim->firstWhere('id_siswa', $r->pengguna->siswa->id_siswa))
                                        <tr style="background: #01ff4d">
                                        @else
                                            @if ($key % 2 == 1)
                                        <tr style="background: #DDA0DD">
                                        @else
                                        <tr>
                                    @endif
                                @endif


                                <th style="text-align: center;">{{ $loop->iteration }}</th>
                                <th style="text-align: center;">
                                    @if (!$sudah_terkirim->firstWhere('id_siswa', $r->pengguna->siswa->id_siswa))
                                        <input id="checkbox-{{ $r->id_pengguna }}" type="checkbox" name="id_pengguna"
                                            class="filled-in" value="{{ $r->id_pengguna }}">
                                        <label for="checkbox-{{ $r->id_pengguna }}"></label>
                                    @endif
                                </th>
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
                                <th>
                                    @if ($sudah_terkirim->firstWhere('id_siswa', $r->pengguna->siswa->id_siswa))
                                        Sudah Dilaporkan
                                    @else
                                        Belum Dilaporkan
                                    @endif
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
        $('#checkbox_select_all_primary_table').change(function() {
            var select_all_checked = this.checked;
            $('input[type="checkbox"]').prop('checked', this.checked);
        });
    });


    function sendSiswaTerlambat() {
        $('button').attr('disabled', 'disabled');
        var pengguna = [];
        $("input:checkbox[name=id_pengguna]:checked").each(function() {
            pengguna.push($(this).val());
        });

        // alert(base_url + '/{{ Request::segment(1) }}/{{ Request::segment(2) }}/{{ Request::segment(3) }}/reset-some-password');
        $.ajax({
            url: base_url +
                '/{{ Request::segment(1) }}/{{ Request::segment(2) }}/{{ Request::segment(3) }}/post-siswa-terlambat',
            type: 'POST',
            data: {
                data_siswa: pengguna,
                tanggal: '{{ $now }}'
            },
            success: function(response) {
                if (response.status_code == 200) {
                    vex.dialog.alert(response.message);
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else if (response.status_code == 201) {
                    vex.dialog.alert(response.message);
                    window.location.href = response.link;
                } else if (response.status_code == 202) {
                    vex.dialog.alert(response.message);
                    loadURI(response.path);
                } else if (response.status_code == 203) {
                    vex.dialog.alert(response.message);
                    primary_table.ajax.reload(null, false);
                } else if (response.status_code == 204) {
                    loadURI(response.path);
                } else if (response.status_code == 300) {
                    vex.dialog.alert(response.message);
                }
            },
            complete: function() {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }
</script>

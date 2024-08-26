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

    .dataTables_filter input[type="search"] {
        color: black;
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
                                        <option value="{{ $k->id_kelas }}"
                                            @if ($id_kelas == $k->id_kelas) SELECTED @endif>{{ $k->nm_kelas }}
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
                                    <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                            class="material-icons">save</i><span>Tampilkan</span></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <br>
    <div class="row clearfix" style="margin-top: 10px">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{-- <div class="header">
                    <h2>List Siswa Terlambat ({{ $date }}) <br><br>
                        <button class="btn  bg-blue waves-effect" onclick="sendSiswaTerlambat()"><i
                                class="material-icons">add</i><span>Kirim ke Pelanggaran</span></button>
                    </h2>
                </div> --}}

                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                            {{-- <table class="table table-bordered" width="600px"> --}}
                            <thead style="background:#9C27B0;color:white">
                                <tr>
                                    <th style="text-align: center;">#</th>
                                    {{-- <th style="text-align: center;"> <input id="checkbox_select_all_primary_table"
                                            type="checkbox" name="select_all" class="filled-in">
                                        <label for="checkbox_select_all_primary_table"
                                            style="margin-bottom: -10px;"></label>
                                    </th> --}}
                                    <th style="text-align: center;">Nama</th>
                                    <th>Kelas</th>
                                    <th>Check In</th>
                                    <th>Jarak Telat</th>
                                    <th>Note</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($terlambat))
                                    @foreach ($terlambat as $key => $r)
                                        @if ($r['pengguna']->siswa->pelanggaranTerlambat)
                                            <tr style="background: #01ff4d">
                                            @else
                                                @if ($loop->iteration % 2 != 1)
                                            <tr style="background: #DDA0DD">
                                            @else
                                            <tr>
                                        @endif
                                    @endif

                                    <th style="text-align: center;">{{ $loop->iteration }}</th>
                                    {{-- <th style="text-align: center;">
                                        @if (empty($r['pengguna']->siswa->pelanggaranTerlambat))
                                            <input id="checkbox-{{ $r['pengguna']->id_pengguna }}" type="checkbox"
                                                name="id_pengguna" class="filled-in"
                                                value="{{ $r['pengguna']->id_pengguna }}">
                                            <label for="checkbox-{{ $r['pengguna']->id_pengguna }}"></label>
                                        @endif
                                    </th> --}}
                                    <th>{{ $r['pengguna']->nm_pengguna }}</th>
                                    <th>{{ $r['pengguna']->siswa->kelas->nm_kelas }}</th>
                                    <th>{{ $r['pengguna']->presensi_pengguna ? $r['pengguna']->presensi_pengguna->check_in : 'Belum Absent' }}
                                    </th>
                                    <th>
                                        {{ $r['keterangan'] }}
                                    </th>
                                    <th>
                                        {{ $r['pengguna']->presensi_pengguna ? $r['pengguna']->presensi_pengguna->notes : '' }}
                                    </th>
                                    <th>
                                        @if ($r['pengguna']->siswa->pelanggaranTerlambat)
                                            Sudah Dilaporkan
                                        @else
                                            Belum Dilaporkan
                                        @endif
                                    </th>
                                    <th>
                                        @if ($r['pengguna']->presensi_pengguna)
                                            <button type="button" class="btn bg-teal waves-effect"
                                                onclick="editAbsensi('{{ $r['pengguna']->presensi_pengguna->id_presensi_pengguna }}')">
                                                <i class="material-icons">edit</i>
                                            </button>
                                        @else
                                            <button type="button" class="btn bg-blue waves-effect"
                                                onclick="addAbsensi('{{ $r['pengguna']->id_pengguna }}')">
                                                <i class="material-icons">add_box</i>
                                            </button>
                                        @endif
                                        @if ($r['pengguna']->presensi_pengguna)
                                            <a href="guru/guru-piket/catat-siswa-terlambat/print/{{ $r['pengguna']->presensi_pengguna ? $r['pengguna']->presensi_pengguna->id_presensi_pengguna : 0 }}"
                                                target="_blank" class="btn bg-red waves-effect">
                                                <i class="material-icons">print</i></a>
                                        @endif
                                    </th>
                                    </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    const id_kelas = '{{ $id_kelas }}';
    const date = '{{ $date }}';
    const modul_url = 'guru-piket';
    let datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' +
        'catat-siswa-terlambat/datatable';
        console.log(datatable_url);

    $("input").on("change", function() {
        this.setAttribute(
            "data-date",
            moment(this.value, "YYYY-MM-DD").format(this.getAttribute("data-date-format"))
        );
    }).trigger("change");

    $(document).ready(function() {
        $('.dataTable').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, "All"]
            ],
            reponsive: true,
            columnDefs: [{
                orderable: false,
                targets: 1
            }],
            order: [
                [0, 'asc']
            ]
        });

        // $('#checkbox_select_all_primary_table').change(function() {
        //     const select_all_checked = this.checked;
        //     $('input[type="checkbox"]').prop('checked', select_all_checked);
        // });
    });

    function editAbsensi(id_presensi_pengguna) {
        window.open(
            `${base_url}/{{ Request::segment(1) }}#{{ Request::segment(2) }}/{{ Request::segment(3) }}/editnotes/${id_presensi_pengguna}/${id_kelas}/${date}`,
            '_blank'
        );
    }

    function addAbsensi(id_pengguna) {
        window.open(
            `${base_url}/{{ Request::segment(1) }}#{{ Request::segment(2) }}/{{ Request::segment(3) }}/addnotes/${id_pengguna}/${id_kelas}/${date}`,
            '_blank'
        );
    }

    function filterAction() {
        loadURI(`{{ Request::segment(2) }}/{{ Request::segment(3) }}/` + $('input[name=date]').val());
    }

    // function sendSiswaTerlambat() {
    //     $('button').attr('disabled', 'disabled');

    //     const pengguna = $("input:checkbox[name=id_pengguna]:checked").map(function() {
    //         return $(this).val();
    //     }).get();

    //     if (!pengguna.length) {
    //         swal({
    //             title: "Warning",
    //             text: "Harap pilih siswa terlebih dahulu",
    //             type: "warning",
    //             confirmButtonColor: "#DD6B55",
    //             timer: 2000,
    //         });

    //         $('button').removeAttr('disabled');
    //         return;
    //     }

    //     $.ajax({
    //         url: `${base_url}/{{ Request::segment(1) }}/{{ Request::segment(2) }}/{{ Request::segment(3) }}/post-siswa-terlambat`,
    //         type: 'POST',
    //         data: {
    //             data_siswa: pengguna,
    //             tanggal: '{{ $date }}'
    //         },
    //         success: function(response) {
    //             vex.dialog.alert(response.message);

    //             switch (response.status_code) {
    //                 case 200:
    //                     setTimeout(() => location.reload(), 2000);
    //                     break;
    //                 case 201:
    //                     window.location.href = response.link;
    //                     break;
    //                 case 202:
    //                     loadURI(response.path);
    //                     break;
    //                 case 203:
    //                     primary_table.ajax.reload(null, false);
    //                     break;
    //                 case 204:
    //                     loadURI(response.path);
    //                     break;
    //                 case 300:
    //                     vex.dialog.alert(response.message);
    //                     break;
    //             }
    //         },
    //         complete: function() {
    //             $('button').removeAttr('disabled');
    //         }
    //     });
    // }
</script>

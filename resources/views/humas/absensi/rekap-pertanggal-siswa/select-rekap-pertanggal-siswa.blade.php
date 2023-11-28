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

{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <button type="button" class="btn btn-default" onclick="viewGuru()">
                Data Rekap Absensi Guru dan Pegawai
            </button>
            <button type="button" class="btn btn-primary">
                Data Rekap Absensi Siswa
            </button>
            <div class="card" style="margin-top: 10px">
                <div class="header">
                    <h2>Filter Data</h2>
                </div>
                <div class="body">
                    <div class="row clearfix">

                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <label>
                                Kelas
                            </label>
                            <select class="form-control show-tick" name="kelas">
                                <option selected disabled value="">Pilih Kelas</option>
                                {{-- <option value="0">--Semua--</option> --}}
                                {{-- @if ($auth_data->sekolah_data->nm_singkat_sekolah == 'manbaulhikam')
                                    <option value="1">-- Madrasah Tsanawiyah (MTs) --</option>
                                    <option value="2">-- Madrasah Aliyah (MA) --</option>
                                @endif --}}
                                @foreach ($list_kelas as $lk)
                                    <option value="{{ $lk->id_kelas }}">{{ $lk->nm_kelas }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <label>
                                Bulan
                            </label>
                            <select class="form-control show-tick" name="id_bulan">
                                @foreach ($data_bulan as $data)
                                    <option 
                                    {{ $bulan->id_bulan == $data->id_bulan ? 'selected' : '' }} 
                                    value="{{ $data->id_bulan }}">{{ $data->nm_bulan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <label>
                                Tahun
                            </label>
                            <select class="form-control show-tick" name="tahun">
                                @for ($i = 2015; $i <= 2025; $i++)
                                    <option {{ $tahun == $i ? 'selected' : '' }} value="{{ $i }}">
                                        {{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        {{-- <div class="col-md-3 col-sm-12 col-xs-12">
                            <label>Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" value="{{ $start_date }}"
                                name="start_date" aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <label>Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end_date" value="{{ $end_date }}"
                                name="end_date" aria-required="true" aria-invalid="true">
                        </div> --}}

                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <button type="button" class="btn bg-purple waves-effect" style="margin-top:27px;"
                                onclick="filterAction()">Tampilkan</button>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

</div>

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.3/moment.min.js"></script> --}}
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('.dataTable').DataTable({
            paging: false,
            lengthMenu: [
                [-1],
                ["All"]
            ]
        });
    });
    $("input").on("change", function() {
        this.setAttribute(
            "data-date",
            moment(this.value, "YYYY-MM-DD")
            .format(this.getAttribute("data-date-format"))
        )
    }).trigger("change")

    function viewSiswa() {
        window.location = '/humas#absensi/histori-absensi-siswa'
    }

    function viewSiswaPondok() {
        window.location = '/humas#absensi/histori-absensi-siswa-pondok'
    }

    function viewSiswaSholat() {
        window.location = '/humas#absensi/histori-absensi-siswa-sholat'

    }

    function viewGuru() {
        window.location = '/humas#absensi/rekap-pertanggal'
    }

    function filterAction() {
        var id_kelas = $('select[name="kelas"]').val();
        if(!id_kelas){
            swal({
                title: "Pilih Kelas dahulu",
                text: "Kelas tidak boleh kosong",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                timer: 2000,
            });
        } else {
            loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/detail/siswa/' + $('select[name=kelas]').val() + '/' + $('select[name=id_bulan]').val() + '/' + $('select[name=tahun]').val());
        }
    }

    function addAbsensi(id_pengguna) {
        window.location = '/humas#absensi/histori-absensi/' + id_pengguna + '/' + $('input[name=date]').val() + '/add'
    }

    function editAbsensi(currUser) {
        window.location = '/humas#absensi/histori-absensi/' + currUser + '/' + $('input[name=date]').val() + '/edit'
    }

    $(".delete-record").click(function() {
        var token = $("meta[name='csrf-token']").attr("content");
        var id = $(this).data("id");
        swal({
                title: "Are you sure?",
                showCancelButton: true
            },
            function(isConfirm) {
                if (isConfirm) {
                    $('.delete-record').attr("disabled", true);
                    //swall
                    $.ajax({
                        url: ` /humas/absensi/histori-absensi/${id}/delete`,
                        type: "post",

                        data: {
                            _token: token,
                        },

                        success: function() {
                            swal({
                                title: "Delete Success",
                                text: "data berhasil dihapus",
                                icon: "success",
                            });
                            loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/' +
                                $('input[name=date]').val() + '/0/0');
                        },
                    });
                }
                return;
            }
        );
    });
</script>

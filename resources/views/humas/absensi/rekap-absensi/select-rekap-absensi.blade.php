<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <button type="button" class="btn btn-primary">
                Data Rekap Absensi Guru dan Pegawai
            </button>
            <button type="button" onclick="viewSiswa()" class="btn btn-default">
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
                                Unit Kerja
                            </label>
                            <select class="form-control show-tick" name="unit_kerja">
                                {{-- <option>Pilih unit kerja</option> --}}
                                <option value="0">--Semua--</option>
                                <option value="1">Pegawai</option>
                                @foreach ($list_unit_kerja as $uk)
                                    <option value="{{ $uk->id_unit_kerja }}">{{ $uk->nm_unit_kerja }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <label>Tanggal Mulai</label>
                            <input type="date" class="form-control" id="start_date" value="{{ $start_date }}"
                                name="start_date" aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <label>Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end_date" value="{{ $end_date }}"
                                name="end_date" aria-required="true" aria-invalid="true">
                        </div>

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
@include('scriptjs')
<script>
    $("#end_date").change(function() {
        var startDate = document.getElementById("start_date").value;
        var endDate = document.getElementById("end_date").value;

        if ((Date.parse(endDate) < Date.parse(startDate))) {
            swal({
                title: "Tanggal Salah",
                text: "Tanggal akhir tidak boleh kurang dari tanggal mulai",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                timer: 2000,
            });
            document.getElementById("end_date").value = startDate;
        }
    });

    function filterAction() {
        loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/detail/' + $('select[name=unit_kerja]').val() +
            '/' + $(
                'input[name=start_date]').val() + '/' + $('input[name=end_date]').val());
    }

    function viewSiswa() {
        window.location = '/humas#absensi/rekap-absensi/siswa'
    }
</script>

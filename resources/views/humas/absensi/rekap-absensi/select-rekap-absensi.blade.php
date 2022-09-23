<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <button type="button" class="btn btn-primary">
                Data Histori Absensi Guru dan Pegawai
            </button>
            <button type="button" onclick="viewSiswa()" class="btn btn-default">
                Data Histori Absensi Siswa
            </button>
            <div class="card" style="margin-top: 10px">
                <div class="header" >
                    <h2>Filter Data</h2>
                </div>
                <div class="body">
                    <div class="row clearfix">

                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <label>
                                Unit Kerja
                            </label>
                            <select class="form-control show-tick" name="unit_kerja">
                                <option>Pilih unit kerja</option>
                                <option value="1">Pegawai</option>
                                @foreach ($list_unit_kerja as $uk)
                                    <option value="{{ $uk->id_unit_kerja }}">{{ $uk->nm_unit_kerja }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <label>Start Date</label>
                            <input type="date" class="form-control" value="{{$start_date}}" name="start_date" aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <label>End Date</label>
                            <input type="date" class="form-control" value="{{$end_date}}" name="end_date" aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <button type="button" class="btn bg-purple waves-effect" style="margin-top:27px;" onclick="filterAction()">Lihat </button>  
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

</div>
@include('scriptjs')
<script>




    function filterAction() {
        loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/detail/' + $('select[name=unit_kerja]').val() + '/' + $(
            'input[name=start_date]').val() + '/' +  $('input[name=end_date]').val());
    }

    function viewSiswa() {
        window.location = '/humas#absensi/detail-absensi/siswa'
    }
    </script>
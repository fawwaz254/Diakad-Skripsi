<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        CARI SISWA
                    </h2>
                </div>
                @include('keuangan/sim/spp/partials/header-card-menu')
                @php
                $semester_aktif = $data_semester->firstWhere('is_aktif_semester', 1);
                $start_semester = $data_semester->firstWhere('kode_semester', $semester_aktif->thn_akademik_semester.'1');
                $end_semester = $data_semester->firstWhere('kode_semester', $semester_aktif->thn_akademik_semester.'2');
                @endphp
            </div>
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Semester awal
                            </h2>
                            <select class="form-control show-tick" name="start_semester">
                            @foreach($data_semester as $semester)
                                <option value="{{$semester->id_semester}}" 
                                @if(!empty($start_semester))
                                    @if($start_semester->id_semester == $semester->id_semester)
                                        selected
                                    @endif
                                @else
                                    @if($semester->is_aktif_semester == 1)
                                        selected
                                    @endif
                                @endif>
                                {{$semester->tahun_ajaran}} ({{$semester->nm_semester}})</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Semester akhir
                            </h2>
                            <select class="form-control show-tick" name="end_semester">
                            @foreach($data_semester as $semester)
                                <option value="{{$semester->id_semester}}" 
                                @if(!empty($end_semester))
                                    @if($end_semester->id_semester == $semester->id_semester)
                                        selected
                                    @endif
                                @else
                                    @if($semester->is_aktif_semester == 1)
                                        selected
                                    @endif
                                @endif>
                                {{$semester->tahun_ajaran}} ({{$semester->nm_semester}})</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i class="material-icons">save</i><span>Ubah Semester</span></button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Tunggakan</th>
                                    <th>Bulan</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

var datatable_url   = base_url + '/' + role_url + '/sim/spp/cari/datatables';


var primary_table = $('#primary_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: datatable_url,
        type: 'POST',
        data: function(params){
            params.start_semester = $('select[name=start_semester]').val();
            params.end_semester = $('select[name=end_semester]').val();
        },
    },
    columns: [
        { data: null, searchable: false, orderable: false },
        { data: 'nis_siswa' },
        { data: 'pengguna.nm_pengguna' },
        { data: 'kelas.nm_kelas' },
        { data: 'total_tagihan_bulan', searchable: false, orderable: false },
        { data: 'tagihan_bulan', searchable: false, orderable: false },
    ]
});

primary_table.on( 'draw', function () {
    primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        var start = this.page.info().page * this.page.info().length;
        cell.innerHTML = start + i + 1;
    } );
} ).draw();

function filterAction(){
    primary_table.ajax.reload(null, false);
}
</script>
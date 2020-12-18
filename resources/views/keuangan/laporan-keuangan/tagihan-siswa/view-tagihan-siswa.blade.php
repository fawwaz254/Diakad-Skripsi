<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
       
            <div class="card">
                <div class="header">
                    <h2>
                        TAGIHAN SISWA
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Tahun Ajaran
                            </h2>
                            <select class="form-control show-tick" name="tahun_akademik_semester">
                            @foreach($data_semester as $semester)
                                <option value="{{$semester->thn_akademik_semester}}" 
                                    @if($semester->thn_akademik_semester == $tahun_akademik_semester)
                                        selected
                                    @endif>
                                {{$semester->tahun_ajaran}}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Kelas
                            </h2>
                            <select class="form-control show-tick" name="kelas">
                            <option value="">Semua kelas</option>
                            @foreach($data_kelas as $data)
                            <option value="{{$data->id_kelas}}">
                                {{$data->nm_kelas}}
                            </option>
                            @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i class="material-icons">save</i><span>Ubah Tahun Ajaran/kelas</span></button>
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
                                    <th>Tagihan</th>
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

var datatable_url   = base_url + '/' + role_url + '/laporan-keuangan/tagihan-siswa/datatables';

var primary_table = $('#primary_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: datatable_url,
        type: 'POST',
        data: function(params){
            params.kelas = $('select[name=kelas]').val();
            params.tahun_akademik_semester = $('select[name=tahun_akademik_semester]').val();
        },
    },
    columns: [
        { data: null, searchable: false, orderable: false },
        { data: 'nis_siswa' },
        { data: 'pengguna.nm_pengguna' },
        { data: 'kelas.nm_kelas' },
        { data: 'total_tagihan_bulan', searchable: false, orderable: false },
        { data: 'tagihan_bulan', searchable: false, orderable: false,
            render: function(data){
                var html = '<ul>';
                $.each(data,function(key, value){
                    html += '<li>'+value.judul+' ('+value.biaya+')</li>';
                });
                html += '</ul>';

                return html;
            }
        },
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
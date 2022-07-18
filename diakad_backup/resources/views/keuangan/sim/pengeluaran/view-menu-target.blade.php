<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        PENGISIAN TARGET
                    </h2>
                </div>
                @include('keuangan/sim/pengeluaran/partials/header-card-menu')
            </div>
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-12 col-sm-12 col-xs-12">
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
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i class="material-icons">save</i><span>Ubah Tahun Ajaran</span></button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Target</th>
                                    <th>Aksi</th>
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

var datatable_url   = base_url + '/' + role_url + '/sim/pengeluaran/target/datatables';
var edit_url        = role_url + '#sim/pengeluaran/target/edit/{{$tahun_akademik_semester}}' ;

var primary_table = $('#primary_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: datatable_url,
        type: 'POST',
        data: function(params){
            params.tahun = $('select[name=tahun_akademik_semester]').val();
        },
    },
    columns: [
        { data: null, searchable: false, orderable: false },
        { data: 'kode_subkategori_rapb' },
        { data: 'nm_subkategori_rapb' },
        { data: 'target_rapb', searchable: false, orderable: false },
        { data: 'action', searchable: false, orderable: false,
            render: function(data){
                return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" target="_blank" href="'+ edit_url + '/' + data.id +'">'+
                '    <i class="material-icons">edit</i>'+
                '</a> ';
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
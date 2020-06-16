<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                    SETTING
                    </h2>
                </div>
                @include('keuangan/sim/spp/partials/header-card-menu')
            </div>
            <style>
                table tr th{
                    text-align: center;
                }
            </style>
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <h2 class="card-inside-title">
                            Tahun Ajaran
                        </h2>
                        <div class="col-md-6 col-sm-12 col-xs-12">
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
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i class="material-icons">save</i><span>Ubah Tahun Ajaran</span></button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No </th>
                                    <th>Kelas</th>
                                    <th>SPP JULI</th>
                                    <th>SPP</th>
                                    <th>Tingkat</th>
                                    <th>Action</th>
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
var edit_setting_spp    = 'sim/spp/edit-setting/';

var datatable_url   = base_url + '/' + role_url + '/sim/spp/setting/datatables';
var primary_table = $('#primary_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: datatable_url,
        type: 'POST',
        data: function(params){
            params.tahun_akademik_semester = $('select[name=tahun_akademik_semester]').val();
        },
    },
    columns: [
        { data: null, searchable: false, orderable: false },
        { data: 'nm_kelas' },
        { data: 'nominal_spp_juli', searchable: false, orderable: false },
        { data: 'nominal_spp_non_juli', searchable: false, orderable: false },
        { data: 'tingkat' },
        { data: 'action', searchable: false, orderable: false, 
            render: function(data){
                if(data.status == 0){
                    return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="editAction(this)"  data-id="'+  data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</button>';
                }else{
                    return '';
                }
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

function editAction(el){
    var item = $(el);
    loadURI(edit_setting_spp + $('select[name=tahun_akademik_semester]').val() + '/' + item.attr('data-id'));
}
</script>
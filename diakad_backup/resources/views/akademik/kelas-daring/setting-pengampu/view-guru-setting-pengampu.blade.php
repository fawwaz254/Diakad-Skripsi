<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#'.Request::segment(2).'/setting-pengampu')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>SETTING MAPEL UNTUK GURU ({{$item->pengguna->fullname()}}) </h2>
                    <input name="id" value="{{$item->id_guru}}" type="hidden">
                </div>
                <div class="body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#sudah" data-toggle="tab">
                                <i class="material-icons">done_all</i> MAPEL DIAMPU
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#belum" data-toggle="tab">
                                <i class="material-icons">cancel_presentation</i> SEMUA MAPEL
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="sudah">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display" id="primary_table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Jurusan</th>
                                                <th>Kode</th>
                                                <th>Nama Mata Ajar</th>
                                                <th>Jenis Mapel</th>
                                                <th>Tingkat</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>    
                        </div>
                        <div role="tabpanel" class="tab-pane" id="belum">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display" id="secondary_table" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Jurusan</th>
                                                <th>Kode</th>
                                                <th>Nama Mata Ajar</th>
                                                <th>Jenis Mapel</th>
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
        </div>
    </div>
</div>

<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url       = 'kelas-daring';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'setting-pengampu/guru/datatables';
    var set_url         = base_url + '/' + role_url + '/' + modul_url + '/' + 'setting-pengampu/guru/action/set';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'setting-pengampu/guru/action/delete';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable_url,
            type: 'POST',
            data: function(params){
                params.status = 1;
                params.id = $('input[name=id]').val();
            },
        },
        columns: [
            { data: 'index_table', defaultContent: '', searchable: false, orderable: false },
            { data: 'jurusan.nm_jurusan' },
            { data: 'kd_mata_pelajaran' },
            { data: 'nm_mata_pelajaran' },
            { data: 'jenis_mata_pelajaran.nm_jenis_mata_pelajaran' },
            { data: 'tingkat_semester' },
            { data: 'action', searchable: false, orderable: false, 
                render: function(data){
                    return '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteMapelAction(this)" data-id="'+  data.id +'">'+
                    '    <i class="material-icons">delete_forever</i>'+
                    '</button>';
                } 
            },
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
            primary_table.cell(cell).invalidate('dom');
        } );
    } ).draw();


    var secondary_table = $('#secondary_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable_url,
            type: 'POST',
            data: function(params){
                params.status = 0;
                params.id = $('input[name=id]').val();
            },
        },
        columns: [
            { data: 'index_table', defaultContent: '', searchable: false, orderable: false },
            { data: 'jurusan.nm_jurusan' },
            { data: 'kd_mata_pelajaran' },
            { data: 'nm_mata_pelajaran' },
            { data: 'jenis_mata_pelajaran.nm_jenis_mata_pelajaran' },
            { data: 'tingkat_semester' },
            { data: 'action', searchable: false, orderable: false, 
                render: function(data){
                    return '<a class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="addMapelAction(this)" data-id="'+  data.id +'">'+
                    '    <i class="material-icons">note_add</i>'+
                    '</a> ';
                } 
            },
        ]
    });

    secondary_table.on( 'draw', function () {
        secondary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();

    function addMapelAction(element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        $.ajax({
            type: "POST",
            url: set_url,
            data: {
                id_mata_pelajaran: item.attr('data-id'),
                id: $('input[name=id]').val(),
            },
            success: function (response) {
                if(response.status == 200){
                    vex.dialog.alert(response.message);
                    primary_table.ajax.reload(null, false);
                    secondary_table.ajax.reload(null, false);
                }
            },
            complete: function() {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }

    function deleteMapelAction(element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        $.ajax({
            type: "POST",
            url: delete_url,
            data: {
                id_mata_pelajaran: item.attr('data-id'),
                id: $('input[name=id]').val(),
            },
            success: function (response) {
                if(response.status == 200){
                    vex.dialog.alert(response.message);
                    primary_table.ajax.reload(null, false);
                    secondary_table.ajax.reload(null, false);
                }
            },
            complete: function() {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }
</script>
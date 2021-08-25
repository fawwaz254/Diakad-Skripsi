<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>DATA JADWAL KELAS DARING </h2>
                </div>
                <div class="body">

                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#belum" data-toggle="tab">
                                <i class="material-icons">cancel_presentation</i> BELUM DIADAKAN
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#sudah" data-toggle="tab">
                                <i class="material-icons">done_all</i> SUDAH DIADAKAN
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="belum">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display" id="primary_table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal Pertemuan</th>
                                                <th>Nama Kelas Daring</th>
                                                <th>Pertemuan Ke</th>
                                                <th>Jenis Pertemuan</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>    
                        </div>
                        <div role="tabpanel" class="tab-pane" id="sudah">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display" id="secondary_table" style="width:100%;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Tanggal Pertemuan</th>
                                                <th>Nama Kelas Daring</th>
                                                <th>Pertemuan Ke</th>
                                                <th>Jenis Pertemuan</th>
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


<script type="text/javascript">
    
    var modul_url       = 'akademik';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'jadwal-kelas-daring/datatables';
    var edit_url   = role_url + '#' + modul_url + '/' + 'jadwal-kelas-daring';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable_url,
            type: 'POST',
            data: function(params){
                params.status = 0;
            },
        },
        columns: [
            { data: 'index_table', defaultContent: '', searchable: false, orderable: false },
            { data: 'tgl_presensi' },
            { data: 'nama_kelas_daring' },
            { data: 'pertemuan_ke' },
            { data: 'jenis_materi' },
            { data: 'action', searchable: false, orderable: false, 
                render: function(data){
                    if(data.open_class==0){
                        //  return  '<button  data-toggle="tooltip" data-placement="top" title="Kelas dapat dibuka 90 menit sebelum jadwal kelas tersebut" class="btn btn-success btn-circle waves-effect waves-circle waves-float">'+
                        // '    <i class="material-icons">remove_red_eye</i>'+
                        // '</button> ';
                        return `Kelas dapat dibuka H-60 Menit`;
                    }
                    else{
                        return  '<a class="target-link btn btn-success btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                        '    <i class="material-icons">remove_red_eye</i>'+
                        '</a> ';
                    }
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
                params.status = 1;
            },
        },
        columns: [
            { data: 'index_table', defaultContent: '', searchable: false, orderable: false },
            { data: 'tgl_presensi' },
            { data: 'nama_kelas_daring' },
            { data: 'pertemuan_ke' },
            { data: 'jenis_materi' },
            { data: 'action', searchable: false, orderable: false, 
                render: function(data){
                    return  '<a class="target-link btn btn-success btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                    '    <i class="material-icons">remove_red_eye</i>'+
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

</script>
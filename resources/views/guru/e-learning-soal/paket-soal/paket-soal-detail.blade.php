<div class="container-fluid">
    <div class="block-header">
        <h2><a type="button" class="btn bg-grey waves-effect" href="{{url(Request::segment(1).'#'.Request::segment(2).'/paket-soal')}}">
            <i class="material-icons">keyboard_backspace</i>
            <span>Kembali</span>
        </a> 
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-pink">
                    <h2>
                       Pilih Soal
                    </h2>
                </div>
                <div class="body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs tab-nav-right" role="tablist">
                        <li role="presentation" class="active"><a href="#not" data-toggle="tab" class="col-pink">List Belum Dipilih</a></li>
                        <li role="presentation"><a href="#selected" data-toggle="tab" class="col-green">List Sudah Dipilih</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="not">
                            <b>List Soal belum dipilih</b>
                            <div class="table-responsive">
                                <table id="primary_table" class="table table-bordered table-striped table-hover dataTable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Pembuat</th>
                                            <th>Soal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="selected">
                            <b>List Soal sudah dipilih</b>
                            <div class="table-responsive">
                                <table id="secondary_table" class="table table-bordered table-striped table-hover dataTable" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Pembuat</th>
                                            <th>Soal</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var test = "{{$question_package->id_paket_soal}}"; 

     var modul_url       = '{{Request::segment(2)}}';
     var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'paket-soal/detail/table/'+test+'/1';
     var datatable2_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'paket-soal/detail/table/'+test+'/2';
     var add_url        = base_url + '/' + role_url + '/' + modul_url + '/' + 'paket-soal/detail/add';
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="token"]').attr('content')
        }
    });

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable_url,
            type: 'POST'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'pengguna.nm_pengguna' },
            { data: 'text', name: 'text', orderable: false },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data) {
                    return '<button type="button" class="btn btn-info btn-circle waves-effect waves-circle waves-float" data-id="'+data.id+'" onclick="actionAdd(this)">'+
                        '    <i class="material-icons">library_add</i>'+
                        '</button>';
                }
            }
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = i + 1;
        } );
    } ).draw();

    var secondary_table = $('#secondary_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable2_url,
            type: 'POST'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'pengguna.nm_pengguna' },
            { data: 'text', name: 'text', orderable: false },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data) {
                    return '<a type="button" class="btn btn-info btn-circle waves-effect waves-circle waves-float" href="{{url('organizer/question/order')}}/'+ data.id +'?question_package_id={{$question_package->question_package_id}}">'+
                        '    <i class="material-icons">reorder</i>'+
                        '</a>'+
                        '<button type="button" class="btn btn-warning btn-circle waves-effect waves-circle waves-float" data-id="'+data.id+'" onclick="actionDelete(this)">'+
                        '    <i class="material-icons">delete</i>'+
                        '</button>';
                }
            }
        ]
    });

    secondary_table.on( 'draw', function () {
        secondary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = i + 1;
        } );
    } ).draw();

    function actionAdd(element){
        // var testt= item.attr('data-id');
        // alert(testt);
        var item = $(element);
        item.prop('disabled', true);
        var url = add_url;
        if(item.is(":disabled") ){
            $.ajax({
                type: "POST",
                url: url,
                data:{
                    id_paket_soal: '{{$question_package->id_paket_soal}}',
                    id_soal: item.attr('data-id')
                },
                success: function (result) {
                    primary_table.ajax.reload(null, false);
                    secondary_table.ajax.reload(null, false);
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
    }

    function actionDelete(element){
        var item = $(element);
        item.prop('disabled', true);
        var url = '{{url('organizer/question/package/detail/delete')}}';
        if(item.is(":disabled") ){
            $.ajax({
                type: "POST",
                url: url,
                data:{
                    id_paket_soal: '{{$question_package->id_paket_soal}}',
                    id_soal: item.attr('data-id')
                },
                success: function (result) {
                    primary_table.ajax.reload(null, false);
                    secondary_table.ajax.reload(null, false);
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
    }
</script>
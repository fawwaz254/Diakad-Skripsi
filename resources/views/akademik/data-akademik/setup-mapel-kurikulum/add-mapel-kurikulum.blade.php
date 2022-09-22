<div class="container-fluid">
    <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-akademik/setup-mp-kurikulum/view-mapel-kurikulum/'.$id_kurikulum)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
    <div class="row-clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Daftar Mata Pelajaran
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-setup-mp-kurikulum/tambah-mapel/'.$id_kurikulum)}}">
                    {{csrf_field()}}
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No. </th>
                                        <th>Jenis Mata Pelajaran</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var id_jurusan = {!! json_encode($id_kurikulum) !!};

    var modul_url       = 'data-akademik';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'setup-mp-kurikulum/datatables-mapel/' + id_jurusan ;

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'jenis_mata_pelajaran', name: 'jenis_mata_pelajaran' },
            { data: 'mata_pelajaran', name: 'mata_pelajaran' },
            { data: 'checkbox', name: 'checkbox', searchable: false, orderable: false,
                render: function (data, type, full, meta){
                    return '<input type="checkbox" name="id_mata_pelajaran[]" style="opacity: 1; visibility: visible; position: absolute; left: 90%" value="' + data.id + '">';
                }
            }
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>

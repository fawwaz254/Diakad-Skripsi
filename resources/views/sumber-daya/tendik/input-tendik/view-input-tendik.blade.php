<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#tendik/input-tendik/add')}}"><i class="material-icons">note_add</i><span>Input Tendik Baru</span></a></h2>
    </div>

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>FILTER STATUS</h2>
                </div>
                <div class="body">

                    <div class="row clearfix">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Status
                            </h2>
                            <select class="form-control show-tick" name="id_status_pengguna" id="id_status_pengguna">
                                <option value="0">-- Semua --</option>
                                @foreach($status as $r)
                                    <option value="{{$r->id_status_pengguna}}">{{$r->nm_status_pengguna}}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-red waves-effect" type="button" onclick="filterData()"><i class="material-icons">save</i><span>Tampilkan</span></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>DATA TENAGA PENDIDIK</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Tendik</th>
                                        <th>NIP</th>
                                        <!-- <th>Jabatan</th> -->
                                        <th>Unit Kerja</th>
                                        <th>Status</th>
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
<script>

    function filterData(){
        primary_table.draw();
    }

    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url       = 'tendik';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-tendik/datatables';
    var edit_url        = role_url + '#' + modul_url + '/' + 'input-tendik/edit';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-input-tendik/delete';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET',
            data : function(d){
                d.id_status_pengguna = $('select[name=id_status_pengguna]').val()
            }
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_pengguna', name: 'nm_pengguna' },
            { data: 'nip_staff', name: 'nip_staff' },
            /*{ data: 'nm_jabatan_pegawai', name: 'nm_jabatan_pegawai' },*/
            { data: 'nm_unit_kerja', name: 'nm_unit_kerja' },
            { data: 'nm_status_pengguna', name: 'nm_status_pengguna' },
            { data: 'action', name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/' + data.id +'">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a> '+
                    '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                    '    <i class="material-icons">delete_forever</i>'+
                    '</button>';
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
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        LAPORAN MAGANG
                    </h2>
                </div>
                <div class="body">

                        <div class="row clearfix">
 
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label>Periode Magang</label>
                                <select class="form-control show-tick" name="id_periode_magang" id="id_periode_magang">
                                    <option value="0"> Semua Periode </option>
                                    @foreach($data_periode_magang as $data)
                                      <option value="{{$data->id_periode_magang}}">{{$data->nm_magang}} - {{$data->nm_periode_magang}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" onclick="filterData()"><i class="material-icons">save</i><span>Filter</span></button>
                            </div>
                        </div>

                      <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>Rekanan Magang</th>
                                    <th>Periode Magang</th>
                                    <th></th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <br>

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    function filterData(){
        primary_table.draw();
    }

    var modul_url       = 'magang-siswa';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'laporan-magang/datatables';
    var print_url       = role_url + '/' + modul_url + '/' + 'laporan-magang/print';
    var input_url       = role_url + '#' + modul_url + '/' + 'laporan-magang/input';
    var open_url        = role_url + '/' + modul_url + '/' + 'laporan-magang/open';
    var edit_url        = role_url + '#' + modul_url + '/' + 'laporan-magang/edit';
    var delete_url      = role_url + '#' + modul_url + '/' + 'laporan-magang/delete';

        var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET',
            data : function(d){
                d.id_periode_magang = $('select[name=id_periode_magang]').val()
            }
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_rekanan_magang', name: 'nm_rekanan_magang' },
            { data: 'nm_periode_magang', name: 'nm_periode_magang' },
            {data: 'action',  name: 'action', searchable: false, orderable: false,
            render: function(data){
                    var html = '';
                    html += `<a target="_blank" href="`+print_url+`/`+data.id_rekanan_magang+`/`+data.id_periode_magang+`"><button class="btn btn-info btn-circle waves-effect waves-circle waves-float"><i class="material-icons">print</i></button></a>`
                    return html;
            } }
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();

</script>

@if($cek_kelas_mp==0)

<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#'.Request::segment(2).'/jadwal-kelas')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <p>Silahkan melakukan setting kelas terlebih dahulu sebelum setting jadwal</p>
</div>

@else

<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#'.Request::segment(2).'/jadwal-kelas')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#'.Request::segment(2).'/jadwal-kelas/data-jadwal/add/'.$item->id_kelas_mp_grup)}}"><i class="material-icons">note_add</i><span>Tambah Jadwal</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>SETTING JADWAL ({{$item->nm_kelas_mp_grup}}) </h2>
                    <input name="id" value="{{$item->id_kelas_mp_grup}}" type="hidden">
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pertemuan Ke</th>
                                    <th>Tanggal Pertemuan</th>
                                    <th>Jenis</th>
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

<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url       = 'kelas-daring';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'jadwal-kelas/data-jadwal/datatables';
    var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/' + 'jadwal-kelas/data-jadwal/action-delete';

    var edit_url        = role_url + '#' + modul_url + '/' + 'jadwal-kelas/data-jadwal/edit';

    var edit_materi_url        = role_url + '#' + modul_url + '/' + 'jadwal-kelas/materi/edit';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable_url,
            type: 'POST',
            data: function(params){
                params.id = $('input[name=id]').val();
            },
        },
        columns: [
            { data: 'index_table', defaultContent: '', searchable: false, orderable: false },
            { data: 'pertemuan_ke' },
            { data: 'tgl_presensi' },
            { data: 'jenis_materi', searchable: false, orderable: false },
            { data: 'status_jadwal', searchable: false, orderable: false },
            { data: 'action', searchable: false, class:'text-center', orderable: false, 
                render: function(data){
                    html = '';

                    if(data.sudah_diadakan==0){
                        html+= '<a class="btn btn-info btn-circle waves-effect waves-circle waves-float target-link" href="' +edit_url+ '/'+data.grup+'/'+data.id+'" data-id="'+  data.id +'">'+
                            '    <i class="material-icons">edit</i>'+
                            '</a> ';
                        html += '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\''+ delete_url +'\', this)" data-id="'+  data.id +'">'+
                    '    <i class="material-icons">delete_forever</i>'+
                    '</button>'
                    }

                    return html; 
                   
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

    function deleteKelasMpAction(element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        $.ajax({
            type: "POST",
            url: set_url,
            data: {
                id_presensi_mp: item.attr('data-id'),
                // id: $('input[name=id]').val(),
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

@endif
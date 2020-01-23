<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#'.$auth_data->modul_url.'/'.$auth_data->menu_url.'/'.$data_semester->id_semester.'/'.$data_kelas->id_kelas)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>KELAS {{$data_kelas->nm_kelas}}
                        <br>
                        SEMESTER {{$data_semester->tahun_ajaran}} {{$data_semester->nm_semester}}</h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.$auth_data->modul_url.'/'.$auth_data->menu_url.'/action/manage')}}">
                            {{csrf_field()}}
                            @if($presensi_harian)
                            <input type="hidden" name="id_presensi_harian" value="{{$presensi_harian->id_presensi_harian}}">
                            @else
                            <input type="hidden" name="id_presensi_harian">
                            @endif
                            <input type="hidden" name="id_semester" required="" value="{{$data_semester->id_semester}}">
                            <input type="hidden" name="id_kelas" required="" value="{{$data_kelas->id_kelas}}">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable display nowrap" id="primary_table" style="overflow-x: scroll;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>NIS</th>
                                            <th>Nama</th>
                                            <th>Alasan</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <labe>Tanggal dan jam presensi</label>
                                            @if($presensi_harian)
                                            <input type="text" class="datetimepicker form-control" name="tgl_entry" required="" aria-required="true" aria-invalid="true" value="{{$presensi_harian->tgl_entry}}">
                                            @else
                                            <input type="text" class="datetimepicker form-control" name="tgl_entry" required="" aria-required="true" aria-invalid="true" value="{{\Carbon\Carbon::today()->format('Y-m-d H:i')}}">
                                            @endif
                                        </div>
                                    </div>
                                </div>
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
</div>
@include('scriptjs')
<script>
    let modul_url       = '{{ $auth_data->modul_url }}';
    let menu_url        = '{{ $auth_data->menu_url }}';

    let id_kelas = '{{ $data_kelas->id_kelas }}',  id_semester = '{{ $data_semester->id_semester }}';

    @if($presensi_harian)
    let id_presensi_harian = '{{ $presensi_harian->id_presensi_harian }}';
    let datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + menu_url + '/datatables-detail/' + id_semester + '/' + id_kelas + '/' + id_presensi_harian;
    @else
    let datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + menu_url + '/datatables-detail/' + id_semester + '/' + id_kelas + '/0';
    @endif


    let primary_table = $('#primary_table').DataTable({
        processing: true,
        pageLength: 100,
        ajax: {
            url: datatable_url,
            type: 'POST'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nis_siswa', name: 'siswa.nis_siswa',
                render: function(data){
                    return data.nis_siswa+'<br><input type="hidden" name="id_siswa[]" value="'+ data.id_siswa + '" >';
                }
            },
            { data: 'nm_pengguna', name: 'pengguna.nm_pengguna' },
            { data: 'alasan', name: 'alasan', searchable: false, orderable: false,
                render: function(data){
                    if(data.status_pengguna.status == 1){
                        let html = '';
                        $.each(data.options, function(index, item){
                            if(data.kehadiran == item.id){
                                html += '<option value="'+item.id+'" selected>'+ item.text + '</option>';
                            }else{
                                html += '<option value="'+item.id+'">'+ item.text + '</option>';
                            }
                        })
                        return '<select class="form-control show-tick" style="width:85px;" name="alasan[]">'+
                        html +
                        '</select>';
                    }else{
                        return '<p class="font-underline col-orange font-24">' + data.status_pengguna.nm_status + '</p>';
                    }
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


$(function(){  
    $('.datepicker-time').bootstrapMaterialDatePicker({
        format: 'HH:mm',
        //lang : 'id',
        clearButton: true,
        weekStart: 1,
        time: true,
        date: false
    });

    $('.datetimepicker').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD HH:mm',
        clearButton: true,
        weekStart: 1,
        time: true,
        date: true
    });
});
</script>
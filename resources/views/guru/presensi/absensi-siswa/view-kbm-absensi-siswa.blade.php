<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#presensi/absensi-siswa')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-orange">
                        <h2>KELAS {{$data_kelas->nm_kelas}} <br>
                        MAPEL {{$data_kelas->nm_mata_pelajaran}}
                        <br>
                        SEMESTER {{$semester_aktif->tahun_ajaran}} {{$semester_aktif->nm_semester}}</h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-absensi-siswa/add-kbm/'.$id_jadwal_kelas_mp.'/'.$pertemuan_ke)}}">
                            {{csrf_field()}}
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
                            <h2 class="card-inside-title">
                                Pertemuan Ke
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="number" class="form-control" name="pertemuan_ke" required="" aria-required="true" aria-invalid="true" value="{{$pertemuan_ke}}" readonly >
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Tanggal
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    @if($presensi_mp_aktif)
                                    <input type="text" class="datepicker form-control" name="tgl_presensi" required="" aria-required="true" aria-invalid="true" value="{{$presensi_mp_aktif->tgl_presensi}}">
                                    @else
                                    <input type="text" class="datepicker form-control" name="tgl_presensi" required="" aria-required="true" aria-invalid="true" value="{{\Carbon\Carbon::today()->format('Y-m-d')}}">
                                    @endif
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Uraian Materi
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    @if($presensi_mp_aktif)
                                    <textarea class="form-control" name="uraian_materi" rows="4" cols="100">{{$presensi_mp_aktif->uraian_materi}}</textarea>
                                    @else
                                    <textarea class="form-control" name="uraian_materi" rows="4" cols="100">{{$data_kelas->uraian_materi}}</textarea>
                                    @endif
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Waktu Mulai
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    @if($presensi_mp_aktif)
                                    <input type="text" class="datepicker-time form-control" name="waktu_mulai" required="" aria-required="true" aria-invalid="true" value="{{$presensi_mp_aktif->waktu_mulai}}">
                                    @else
                                    <input type="text" class="datepicker-time form-control" name="waktu_mulai" required="" aria-required="true" aria-invalid="true" value="{{$data_kelas->waktu_mulai}}">
                                    @endif
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Waktu Selesai
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    @if($presensi_mp_aktif)
                                    <input type="text" class="datepicker-time form-control" name="waktu_selesai" required="" aria-required="true" aria-invalid="true" value="{{$presensi_mp_aktif->waktu_selesai}}">
                                    @else
                                    <input type="text" class="datepicker-time form-control" name="waktu_selesai" required="" aria-required="true" aria-invalid="true" value="{{$data_kelas->waktu_selesai}}">
                                    @endif
                                </div>
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
</div>
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var id_kelas_mp = {!! json_encode($data_kelas->id_kelas_mp) !!};
    var pertemuan_ke = {!! json_encode($pertemuan_ke) !!};

    var modul_url       = 'presensi';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'absensi-siswa/datatables-kbm/' + id_kelas_mp + '/' + pertemuan_ke;

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        // serverSide: true,
        pageLength: 100,
        ajax: {
            url: datatable_url,
            type: 'GET'
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
                    var html = '';
                    $.each(data.options, function(index, item){
                        if(data.kehadiran == item.id){
                            html += '<option value="'+item.id+'" selected>'+ item.text + '</option>';
                        }else{
                            html += '<option value="'+item.id+'">'+ item.text + '</option>';
                        }
                    })
                    return '<select class="form-control show-tick" name="alasan[]">'+
                    html +
                    '</select>';
                }
            }
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * 10;
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

    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'YYYY-MM-DD',
        clearButton: true,
        weekStart: 1,
        time: false
    });
});
</script>
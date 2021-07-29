<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#laporan/wali-kelas')}}"><i class="material-icons">arrow_back</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header">
                        <h2>Data Laporan Wali Kelas Semester {{$laporan_wali_kelas->semester->tahun_ajaran}} {{$laporan_wali_kelas->semester->nm_semester}} Bulan {{$laporan_wali_kelas->bulan->nm_bulan}}</h2>
                    </div>
                    <div class="body">
                       <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="vertical-align : middle;text-align:center;">No</th>
                                        <th rowspan="2" style="vertical-align : middle;text-align:center;">Nama</th>
                                        <th rowspan="2" style="vertical-align : middle;text-align:center;">Jabatan</th>
                                        <th colspan="2" style="vertical-align : middle;text-align:center;">Hasil Laporan</th>
                                        <th rowspan="2" style="vertical-align : middle;text-align:center;">Catatan</th>
                                        <th rowspan="2" style="vertical-align : middle;text-align:center;">Action</th>
                                    </tr>
                                    <tr>
                                        <th style="vertical-align : middle;text-align:center;">Tuntas</th>
                                        <th style="vertical-align : middle;text-align:center;">Belum Tuntas</th>
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

<input type="hidden" id="id" value="{{$laporan_wali_kelas->id_laporan_wali_kelas}}">

<form id="form-validation" method="post"  action="{{url(Request::segment(1).'/'.Request::segment(2).'/wali-kelas/action-detail-wali-kelas')}}">
{{csrf_field()}}
<!-- Modal Update Status -->
<div class="modal fade" id="modalMaster" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Form Update Status</h4>
            </div>
            <input type="hidden" name="id_laporan_wali_kelas_detail" id="id_laporan_wali_kelas_detail">
            <div class="modal-body" id="modal-body">

                <div class="row">
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" id="nama" />
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-line">
                                <input type="text" class="form-control" id="kelas" />
                            </div>
                        </div>
                    </div>


                </div>

                <p>Pilih Status</p>
                <div id="pilih_status">
                    
                </div>

                <br>

                <p>Catatan</p>
                <div class="row clearfix">
                    <div class="col-sm-12">
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="4" class="form-control no-resize" id="catatan" name="catatan"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
                <button type="submit" class="btn btn-link waves-effect">SAVE CHANGES</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Update Status -->
</form>

@include('scriptjs')

<script type="text/javascript">
    
    var id = $('#id').val();
    var modul_url       = 'laporan';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'wali-kelas/detail-datatable/'+id;
    var edit_url = '';

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
            { data: 'guru', name: 'guru' },
            { data: 'jabatan', name: 'jabatan' },
            { data: 'action',class:'text-center', name: 'action', searchable: false, orderable: false,
                render : function(data){
                    if(data.status == 1){
                        return '<i class="material-icons" style="color:green">done</i>';
                    }
                    else{
                        return '';
                    }
                }
            },
            { data: 'action',class:'text-center', name: 'action', searchable: false, orderable: false,
                render : function(data){
                    if(data.status == 0){
                        return '<i class="material-icons" style="color:red">clear</i>';
                    }
                    else{
                        return '';
                    }
                }
            },
            { data: 'catatan', name: 'catatan' },
            { data: 'action', class:'text-center' ,name: 'action', searchable: false, orderable: false,
                render: function(data){
                    return '<a class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="updateStatus(\''+ data.id +'\', \`pengajuan`\)">'+
                    '    <i class="material-icons">edit</i>'+
                    '</a> ';
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

    function updateStatus(id){

        $.ajax({
            url :  base_url + '/' + role_url + '/' + modul_url + '/' + 'wali-kelas/detail-ajax/'+id,
            type : 'get',
            dataType : 'json',
            success : function(response){
                $('#id_laporan_wali_kelas_detail').val(id);
                $('#catatan').html(response.catatan);
                var nama = response.guru.pengguna.gelar_depan+' '+response.guru.pengguna.nm_pengguna+' '+response.guru.pengguna.gelar_belakang;
                $('#nama').val(nama);
                $('#kelas').val(response.kelas.nm_kelas);

                if(response.status == 1){

                    $('#pilih_status').html(`
                        <div class="demo-radio-button">
                            <input name="status" type="radio" value="1" id="radio_1" checked />
                            <label for="radio_1">Tuntas</label>
                            <input name="status" type="radio" value="0" id="radio_2" />
                            <label for="radio_2">Belum Tuntas</label>
                        </div>
                    `);

                }

                else{

                    $('#pilih_status').html(`
                        <div class="demo-radio-button">
                            <input name="status" type="radio" value="1" id="radio_1" />
                            <label for="radio_1">Tuntas</label>
                            <input name="status" type="radio" value="0" id="radio_2" checked  />
                            <label for="radio_2">Belum Tuntas</label>
                        </div>
                    `);

                }

                $('#modalMaster').modal('show');
            },
            error : function(){
                alert('mohon maaf terjadi kesalahan, silahkan hubungi admin');
            }
        });

    }

</script>

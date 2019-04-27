<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#pendaftaran/penawaran-jurusan')}}"><i class="material-icons">keyboard_backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-lime">
                        <h2>PENAWARAN JURUSAN - DAFTAR JURUSAN</h2>
                    </div>

                    <div class="body" style="padding-bottom:50px;">
                        <table class="" style="margin-bottom:20px;">
                            <tr>
                                <td>Nama Penerimaan</td>
                                <td style="padding-left:5px">: <strong>{{$penerimaan->nm_penerimaan}}</strong></td>
                            </tr>
                            <tr>
                                <td>Semester</td>
                                <td style="padding-left:5px">: <strong>{{$penerimaan->nm_semester_penerimaan . ", " . $penerimaan->tahun_penerimaan}}</strong></td>
                            </tr>
                            <tr>
                                <td>Gelombang</td>
                                <td style="padding-left:5px">: <strong>{{$penerimaan->gelombang_penerimaan}}</strong></td>
                            </tr>
                        </table>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jurusan</th>
                                        <th>Status Aktif</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @forelse($penerimaan_jurusan as $i => $value)
                                    <tr>
                                        <td>{{($i+1)}}</td>
                                        <td>{{$value->nm_jurusan}}</td>
                                        <td>
                                            <div>
                                                <input type="checkbox" class="mycheckbox" id="check-{{$value->id_penerimaan_jurusan}}" data-id="{{$value->id_penerimaan_jurusan}}" {{($value->is_aktif == 1? 'checked':'')}}>
                                                <label for="check-{{$value->id_penerimaan_jurusan}}">{{($value->is_aktif == 1? 'Aktif':'Tidak Aktif')}}</label>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction('ppdb/pendaftaran/penawaran-jurusan/edit-penawaran-jurusan/delete', this)" data-id="{{$value->id_penerimaan_jurusan}}">
                                                <i class="material-icons">delete_forever</i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">no data</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                            <h2><a class="btn bg-green waves-effect target-link" href="{{url(Request::segment(1).'#pendaftaran/penawaran-jurusan/edit-penawaran-jurusan/'.$penerimaan->id_penerimaan.'/add')}}"><i class="material-icons">note_add</i><span>Tambah Jurusan</span></a></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(".mycheckbox").click(function(e) { 
        var checkbox = $(this);
        $.ajax({
            type: "POST",
            url: 'ppdb/pendaftaran/penawaran-jurusan/edit-penawaran-jurusan/activate/' + $(this).attr('data-id'),
            success: function (response) {
                if(response.status == 200){
                    vex.dialog.alert(response.message);
                }else if(response.status == 201){
                    vex.dialog.alert(response.message);
                    window.location.href = response.link;
                }else if(response.status == 202){
                    vex.dialog.alert(response.message);
                    loadURI(response.path);
                }else if(response.status == 203){
                    vex.dialog.alert(response.message);
                    primary_table.ajax.reload(null, false);
                }else if(response.status == 300){
                    vex.dialog.alert(response.message);
                }
            }
        });
    });
</script>
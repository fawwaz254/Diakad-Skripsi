<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>
                        DETAIL MATERI AJAR
                    </h2>
                </div>
                <div class="body">

                    <div class="row clearfix">

                        <div class="col-md-4">
                            <label>Jurusan</label>
                             <select class="form-control show-tick" name="id_jurusan">
                                @foreach($list_jurusan as $r)
                                <option value="{{$r->id_jurusan}}" {{$materi_ajar->id_jurusan == $r->id_jurusan ? 'selected' : ''}}>{{$r->nm_jurusan}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Tingkat</label>
                            <select class="form-control show-tick" name="tingkat">
                            @foreach($list_tingkat as $r)
                                <option value="{{$r->tingkat}}" {{$materi_ajar->tingkat == $r->tingkat ? 'selected' : ''}}>{{$r->tingkat}}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label>Status</label>
                            <select class="form-control show-tick" name="status">
                                <option value="0" {{$materi_ajar->status == 0 ? 'selected' : ''}}>Tidak Aktif</option>
                                <option value="1" {{$materi_ajar->status == 1 ? 'selected' : ''}}>Aktif</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Mata Pelajaran</label>
                            <select class="form-control show-tick" name="id_mata_pelajaran">
                                @foreach($list_mapel as $r)
                                <option value="{{$r->id_mata_pelajaran}}" {{$materi_ajar->id_mata_pelajaran == $r->id_mata_pelajaran ? 'selected' : ''}}>{{$r->nm_mata_pelajaran}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Judul Materi</label>
                            <input type="text" class="form-control" name="judul_materi" value="{{$materi_ajar->judul_materi}}" required="" aria-required="true" aria-invalid="true">
                        </div>

                    </div>

                    <div class="row clearfix">
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama File</th>
                                        <th>Tipe File</th>
                                        <th style="text-align:center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materi_ajar->materi_ajar_file as $r)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$r->nm_file}}</td>
                                        <td>{{$r->type_file}}</td>
                                        <td style="text-align:center;">
                                            <a href="{{Storage::disk('spaces')->url($r->link_file)}}" target="_blank"><button class="btn btn-success">Download</button></a>
                                            <button class="btn btn-warning lihat" data-type="{{$r->type_file}}" data-link="{{Storage::disk('spaces')->url($r->link_file)}}">Lihat Disini</button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                         </div>
                    </div>                    

                </div>
            </div>

            <br>

            <div class="card" id="card-materi" style="display:none;">
                <div class="body" id="frame">
                  
                </div>    
            </div>

            <br>

        </div>
    </div>
</div>

<script type="text/javascript">
    
    $('.lihat').click(function(){

        $('#card-materi').show();

        var link = $(this).data('link');
        var type = $(this).data('type');

        if(type=='pdf'){
            $('#frame').empty();
            $('#frame').append(`
                <iframe src="`+link+`" style="width:100%; height:535px;" frameborder="0"></iframe>
            `);
        }

        else{
            $('#frame').empty();
            $('#frame').append(`
                  <iframe  src='https://view.officeapps.live.com/op/embed.aspx?src=`+link+`' style="width:100%;" height='535px' frameborder='0'></iframe>
            `);
        }

        
    })

</script>

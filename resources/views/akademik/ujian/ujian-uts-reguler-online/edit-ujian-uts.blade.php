    <div class="container-fluid">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#ujian/ujian-uts-reguler-online')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            EDIT DATA {{$ujian->nm_ujian_mp}} @if($ujian->is_online == 1) Online @else Reguler @endif 
                        </h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-ujian-uts/edit/'.$ujian->id_ujian_mp)}}">
                            {{csrf_field()}}
                            <h2 class="card-inside-title">
                                Jenis Ujian
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_kegiatan" required=""  readonly aria-required="true"
                                        aria-invalid="true" value="{{$ujian->nm_kegiatan}}">
                                    <input type="hidden" name="id_kegiatan" value="{{$ujian->id_kegiatan}}">
                                    <input type="hidden" name="is_online" value="{{$ujian->is_online}}">
                                    <input type="hidden" name="id_kelas_mp" value="{{$ujian->id_kelas_mp}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Kelas 
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_kelas" required="" readonly="" aria-required="true"
                                        aria-invalid="true" value="{{$ujian->nm_kelas}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Mata Pelajaran
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_mata_pelajaran" required="" readonly="" aria-required="true"
                                        aria-invalid="true" value="{{$ujian->nm_mata_pelajaran}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Nama Ujian
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_ujian_mp" required="" aria-required="true"
                                        aria-invalid="true" value="{{$ujian->nm_ujian_mp}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Tanggal Ujian
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="datepicker form-control" name="tgl_ujian_mp" required="" aria-required="true"
                                        aria-invalid="true" value="{{$tgl_ujian_mp}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jam Mulai Ujian <small><strong>Misalnya : 10.00</strong></small>
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="datepicker-time form-control" name="jam_mulai" required="" aria-required="true"
                                        aria-invalid="true" value="{{$ujian->jam_mulai}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jam Selesai Ujian <small><strong>Misalnya : 12.00</strong></small>
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="datepicker-time form-control" name="jam_selesai" required="" aria-required="true"
                                        aria-invalid="true" value="{{$ujian->jam_selesai}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Keterangan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="keterangan" required="" aria-required="true"
                                        aria-invalid="true" value="{{$ujian->keterangan}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Ruangan Ujian
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="id_ruangan">
                                        <option value="">-- Pilih Ruangan Ujian --</option>
                                       @foreach($ruangan as $data)
                                            <option value="{{$data->id_ruangan}}" @if($data->id_ruangan == $ujian->id_ruangan) selected @endif>{{$data->nm_ruangan}}({{$data->nm_gedung}})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
    $(function(){    
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'DD MMMM YYYY',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });

    $(function(){  
    $('.datepicker-time').bootstrapMaterialDatePicker({
        format: 'HH:mm',
        //lang : 'id',
        clearButton: true,
        weekStart: 1,
        time: true,
        date: false
    });

});
    </script>

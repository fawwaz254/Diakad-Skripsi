    <div class="container-fluid">
        <div class="block-header">
            <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#ujian/ujian-uas-reguler-online/add/'.$online)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            Tambah Data {{$kegiatan->nm_kegiatan}}  @if($online == 1) Online @else Reguler @endif
                        </h2>
                    </div>
                    <div class="body">
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-ujian-uas/add/0')}}">
                            {{csrf_field()}}
                            <h2 class="card-inside-title">
                                Jenis Ujian
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_kegiatan" required=""  readonly aria-required="true"
                                    aria-invalid="true" value="{{$kegiatan->nm_kegiatan}}">
                                    <input type="hidden" class="form-control" name="id_kegiatan" required=""  readonly aria-required="true" aria-invalid="true" value="{{$kegiatan->id_kegiatan}}">
                                    <input type="hidden" class="form-control" name="is_online" required=""  readonly aria-required="true" aria-invalid="true" value="{{$online}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Kelas 
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_kelas" required="" readonly="" aria-required="true"
                                        aria-invalid="true" value="{{$kelas_mp->nm_kelas}}">
                                    <input type="hidden" class="form-control" name="id_kelas_mp" required="" readonly="" aria-required="true"
                                        aria-invalid="true" value="{{$id}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Mata Pelajaran
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_mata_pelajaran" required="" readonly="" aria-required="true"
                                        aria-invalid="true" value="{{$kelas_mp->nm_mata_pelajaran}}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Nama Ujian
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="nm_ujian_mp" required="" aria-required="true"
                                        aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Tanggal Ujian
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="datepicker form-control" name="tgl_ujian_mp" required="" aria-required="true"
                                        aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jam Mulai Ujian <small><strong>Misalnya : 10.00</strong></small>
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="datepicker-time form-control" name="jam_mulai" required="" aria-required="true"
                                        aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Jam Selesai Ujian <small><strong>Misalnya : 12.00</strong></small>
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="datepicker-time form-control" name="jam_selesai" required="" aria-required="true"
                                        aria-invalid="true">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Keterangan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="keterangan" required="" aria-required="true"
                                        aria-invalid="true">
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
                                            <option value="{{$data->id_ruangan}}">{{$data->nm_ruangan}}({{$data->nm_gedung}})</option>
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

<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#penetapan/data-penetapan/view-penetapan-penerimaan/'.$penetapan->id_penetapan)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-lime">
                    <h2>
                        TAMBAH {{$penetapan->nm_penetapan}}
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-penetapan-penerimaan/add/'.$penetapan->id_penetapan)}}">
                        {{csrf_field()}}
                        <!-- Mulai Isian untuk Tabel Penetapan -->
                        
                        
                        <h2 class="card-inside-title">
                            Penambahan Penerimaan pada sidang penetapan {{$penetapan->id_penetapan}}
                        </h2>
                        <input type="hidden" name="id_penetapan" value="{{$penetapan->id_penetapan}}">

                        <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control" name="id_penerimaan" id="select-penerimaan">
                                        <option value="">- Pilih Penerimaan -</option>
                                        @foreach($grup_penerimaan_tahun as $tahun => $grup_penerimaan)
                                            @foreach($grup_penerimaan as $semester => $datapergrup)
                                                <optgroup label="{{$tahun}} {{$semester}}">
                                                    @foreach($datapergrup as $data)
                                                    <option value="{{$data->id_penerimaan}}">{{"Gelombang " . $data->gelombang_penerimaan . " " . $data->nm_penerimaan}}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        @endforeach                                    
                                    </select>
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

    $('.datepicker-time').bootstrapMaterialDatePicker({
        format: 'DD MMMM YYYY HH:mm:00',
        //lang : 'id',
        clearButton: true,
        weekStart: 1,
        time: true
    });

});
</script>
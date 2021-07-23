<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    LAPORAN WISUDA
                </h2>
            </div>
            <div class="body">
                
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <select class="form-control show-tick" name="id_periode" id="id_periode">
                            <option value="">-- Pilih Periode Wisuda --</option>
                            @foreach($data_periode_wisuda as $data)
                                <option value="{{$data->id_periode_wisuda}}">{{$data->nm_periode_wisuda}} ({{$data->tahun_ajaran}} {{$data->nm_semester}})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <button class="btn btn-block bg-red waves-effect" id="print" type="submit"><i class="material-icons">print</i><span>Print</span></button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $('#print').click(function(){

        var id_periode = $('#id_periode').val();
        if(!id_periode){
            alert('Mohon diisi periode terlebih dahulu');
            return false;
        }

        window.location.href=base_url+'/kesiswaan/wisuda/laporan-wisuda/print-laporan-wisuda/'+id_periode;

    })

</script>
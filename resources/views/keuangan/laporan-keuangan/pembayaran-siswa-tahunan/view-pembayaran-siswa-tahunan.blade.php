<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#laporan-keuangan/pembayaran-siswa')}}"><span>Pembayaran by tanggal</span></a>
        <a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#laporan-keuangan/pembayaran-siswa-bulanan')}}"><span>Pembayaran bulanan</span></a>
        <a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#laporan-keuangan/pembayaran-siswa-tahunan')}}"><span>Pembayaran Tahunan</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        FILTER PEMBAYARAN SISWA TAHUNAN
                    </h2>
                </div>
                @php 
                $now = date('Y'); 
                $year = (range($now, $now - 10));
                @endphp
                <div class="body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <labe>Tahun</label>
                                    <select name="year" id="year" class="form-control">
                                        <option value="" selected disabled>Pilih tahun</option>
                                    @foreach($year as $y)
                                        <option value="{{$y}}">{{$y}}</option>
                                    @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i class="material-icons">save</i><span>Filter</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>LAPORAN PEMBAYARAN</h2>
                </div>
                <div class="body">
                    <div id="laporan-tahunan">
                        <ul>
                            <li class="list-group-item list-pembayaran"></li>
                            <li class="list-group-item">Total: <span class="pull-right">Rp. <span id="total_pembayaran" ></span></span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){    
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false,
        });
    });
</script>
<script>
    var modul_url               = 'laporan-keuangan';
    var query_url     = base_url + '/' + role_url + '/' + modul_url + '/' + 'pembayaran-siswa-tahunan/data';

    function filterAction(){
        var yearParams = $('#year').val();
        $.ajax({
            url: query_url + '/' + yearParams,
            method: "GET",
            success: function(data){
                var data = data;
                
                $('.list-pembayaran').empty();

                $.each(data.listData, function(key,value){
                    $('.list-pembayaran').append(`
                        <li class="list-group-item"> Kelas `+key+` <span class="pull-right"> Rp. `+value+`</span></li>
                    `);
                });

                $('#total_pembayaran').html(data.total);
            }
        });
    }
</script>
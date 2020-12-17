<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#laporan-keuangan/pembayaran-siswa')}}"><span>Pembayaran by tanggal</span></a>
        <a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#laporan-keuangan/pembayaran-siswa/bulanan')}}"><span>Pembayaran bulanan</span></a>
        <a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#laporan-keuangan/pembayaran-siswa/tahunan')}}"><span>Pembayaran Tahunan</span></a></h2>
    </div>

    <div class="row clearfix">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="card is-gap">
            <div class="header">
                <h2>FILTER PEMBAYARAN SISWA</h2>
            </div>
            <div class="body">

            	 <div class="row clearfix">

                     @php 
                     $curYear = date('Y'); 
                     $year = (range($curYear, $curYear - 10));
                     @endphp

            	 	 <div class="col-md-6 col-sm-6 col-xs-12">
                        <h2 class="card-inside-title">
                            Pilih Tahun
                        </h2>
                        <select class="form-control show-tick" name="tahun" id="tahun">
                             @foreach($year as $year)
                                <option value='{{$year}}'>{{$year}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <h2 class="card-inside-title">
                            Pilih Bulan
                        </h2>
                        <select class="form-control show-tick" name="bulan" id="bulan">
                            @foreach($bulan as $bulan)
                                <option value="{{$bulan->id_bulan}}" {{$bulan->id_bulan == $id_bulan ? 'selected' : ''}} >{{$bulan->nm_bulan}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <button class="btn btn-block bg-btn-submit waves-effect" id="button_filter" onclick="filterAction()"><i class="material-icons">save</i><span>Filter</span></button>
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
                    
                    <ul class="list-group">
                        <div id="kotak">
                        @foreach($grup as $key => $r)
                        <li class="list-group-item">{{$key}} <span class="badge bg-teal">{{$r}}</span></li>
                        @endforeach
                        </div>
                        <li class="list-group-item" style="border:2px solid black;">Total Pembayaran <span class="badge bg-purple" id="total_pembayaran">{{$total}}</span></li>
                    </ul>

                </div>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript">

    var modul_url               = 'laporan-keuangan';
    var data_url     = base_url + '/' + role_url + '/' + modul_url + '/' + 'pembayaran-siswa/bulanan/dataPembayaranSiswaBulanan';

    function filterAction(){

        var bulan = $('#bulan').val();
        var tahun = $('#tahun').val();

        $('#button_filter').prop("disabled", true);

        $.ajax({
        url: data_url,
        type: 'GET',
        data: {bulan:bulan,tahun:tahun},
        success:function(response){

            $('#kotak').empty();

            $.each(response.grup,function(key,value){
                $('#kotak').append(`
                    <li class="list-group-item">`+key+` <span class="badge bg-teal">`+value+`</span></li>
                `);
            });

            $('#total_pembayaran').html(response.total);
            $('#button_filter').prop("disabled", false);
        },
        error:function(){
            $('#button_filter').prop("disabled", false);
            alert('terjadi error')
        }
        });


    }

</script>


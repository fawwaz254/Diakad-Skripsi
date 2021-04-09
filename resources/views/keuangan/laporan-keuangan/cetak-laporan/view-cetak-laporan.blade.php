<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>DAFTAR LAPORAN KEUANGAN</h2>
                </div>
                <div class="body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <labe>Tanggal Awal</label>
                                    <input type="text" class="datepicker form-control" name="start_date" value="{{\Carbon\Carbon::today(env('APP_TIMEZONE', 'Asia/jakarta'))->format('Y-m-d')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <div class="form-line">
                                    <labe>Tanggal Akhir</label>
                                    <input type="text" class="datepicker form-control" name="end_date" value="{{\Carbon\Carbon::today(env('APP_TIMEZONE', 'Asia/jakarta'))->format('Y-m-d')}}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                PEMBAYARAN SISWA
                            </h2>
                            <ul>
                                <li><a onclick="printPembayaranSiswa('siswa')" >Rekap per Siswa</a></li>
                                <li><a onclick="printPembayaranSiswa('tanggal')">Rekap per Tanggal</a></li>
                                <li><a onclick="printPembayaranSiswa('bulan')">Rekap per Bulan</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                KELUAR MASUK KAS
                            </h2>
                            <ul>
                                <li><a onclick="printKas()" >Rekap Detail</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){    
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
</script>
<script>
    var modul_url        = 'laporan-keuangan';
    var datatable_url    = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-laporan/datatables';
    var print_laporan_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-laporan';

</script>

<script>
    function printPembayaranSiswa(jenis){
        $('button').attr('disabled', 'disabled');
        var start_date = $('input[name=start_date]').val();
        var end_date = $('input[name=end_date]').val();
        console.log(start_date, end_date, jenis);
        
        if(start_date == null || end_date == null || start_date == '' || end_date == ''){
            vex.dialog.alert("Tanggal Awal atau Tanggal Akhir yang dipilih tidak valid");
            $('button').removeAttr('disabled', 'disabled');
        } else {
            if(end_date < start_date){
                vex.dialog.alert("Tanggal Akhir harus sama dengan atau lebih dari Tanggal Awal");
                $('button').removeAttr('disabled', 'disabled');
            } else {
                window.open(print_laporan_url + '/' + 'print-pembayaran-siswa' + '/' + jenis + '/' + start_date + '/' + end_date, "_blank");
                $('button').removeAttr('disabled', 'disabled');
            }
        }
    }

    function printKas(){
        $('button').attr('disabled', 'disabled');
        var start_date = $('input[name=start_date]').val();
        var end_date = $('input[name=end_date]').val();
        console.log(start_date, end_date);

        if(start_date == null || end_date == null || start_date == '' || end_date == ''){
            vex.dialog.alert("Tanggal Awal atau Tanggal Akhir yang dipilih tidak valid");
            $('button').removeAttr('disabled', 'disabled');
        } else {
            if(end_date < start_date){
                vex.dialog.alert("Tanggal Akhir harus sama dengan atau lebih dari Tanggal Awal");
                $('button').removeAttr('disabled', 'disabled');
            } else {
                window.open(print_laporan_url + '/' + 'print-arus-kas' + '/' + start_date + '/' + end_date, "_blank");
                $('button').removeAttr('disabled', 'disabled');
            }
        }
    }
</script>
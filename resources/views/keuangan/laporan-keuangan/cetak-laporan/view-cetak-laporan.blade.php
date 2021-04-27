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
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">Setting cetak</h2>
                            <input class="with-gap radio-col-light-green form-control validate" type="radio" name="print_setting" value="all" id="all" onchange="changeSettingSession()"
                                {{ !empty(session('setting_print_keuangan')) && session('setting_print_keuangan') == 'all' ? 'checked' : ''  }} />
                            <label for="all"> Input dari semua staff keuangan </label>
                            <input class="with-gap radio-col-light-green form-control validate" type="radio" name="print_setting" value="self" id="self" onchange="changeSettingSession()"
                                {{ !empty(session('setting_print_keuangan')) && session('setting_print_keuangan') == 'self' ? 'checked' : ''  }} />
                            <label for="self"> Input dari pengguna sendiri </label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                PEMBAYARAN SISWA
                            </h2>
                            <ul>
                                <li><a style="cursor: pointer;" onclick="printPembayaranSiswa('siswa')" >Rekap per Siswa</a></li>
                                <li><a style="cursor: pointer;" onclick="printPembayaranSiswa('kelas')" >Rekap per Kelas</a></li>
                                <li><a style="cursor: pointer;" onclick="printPembayaranSiswa('tingkat')" >Rekap per Tingkat</a></li>
                                <li><a style="cursor: pointer;" onclick="printPembayaranSiswa('tanggal')">Rekap per Tanggal</a></li>
                                <li><a style="cursor: pointer;" onclick="printPembayaranSiswa('bulan')">Rekap per Bulan (Laporan Tahunan)</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                KAS KELUAR
                            </h2>
                            <ul>
                                <li><a style="cursor: pointer;" onclick="printKeluar('kategori')">Rekap per Kategori</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                KAS KELUAR & MASUK
                            </h2>
                            <ul>
                                <li><a style="cursor: pointer;" onclick="printKas('detail-reguler')">Rekap Detail Reguler</a></li>
                                <li><a style="cursor: pointer;" onclick="printKas('detail-internal')">Rekap Detail (Sesuai biaya internal)</a></li>
                                <li><a style="cursor: pointer;" onclick="printBulanan('full')">Laporan Bulanan dengan Tunggakan</a></li>
                                <li><a style="cursor: pointer;" onclick="printBulanan('harian')">Laporan Bulanan per Hari per Kategori</a></li>
                            </ul>
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
    var setting_url    = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-laporan/setting';
    var print_laporan_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-laporan';

</script>

<script>
    function printPembayaranSiswa(jenis){
        $('button').attr('disabled', 'disabled');
        var start_date = $('input[name=start_date]').val();
        var end_date = $('input[name=end_date]').val();
        
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

    function printKeluar(jenis){
        $('button').attr('disabled', 'disabled');
        var start_date = $('input[name=start_date]').val();
        var end_date = $('input[name=end_date]').val();

        if(start_date == null || end_date == null || start_date == '' || end_date == ''){
            vex.dialog.alert("Tanggal Awal atau Tanggal Akhir yang dipilih tidak valid");
            $('button').removeAttr('disabled', 'disabled');
        } else {
            if(end_date < start_date){
                vex.dialog.alert("Tanggal Akhir harus sama dengan atau lebih dari Tanggal Awal");
                $('button').removeAttr('disabled', 'disabled');
            } else {
                window.open(print_laporan_url + '/' + 'print-pengeluaran' + '/' + jenis + '/' + start_date + '/' + end_date, "_blank");
                $('button').removeAttr('disabled', 'disabled');
            }
        }
    }

    function printKas(jenis){
        $('button').attr('disabled', 'disabled');
        var start_date = $('input[name=start_date]').val();
        var end_date = $('input[name=end_date]').val();

        if(start_date == null || end_date == null || start_date == '' || end_date == ''){
            vex.dialog.alert("Tanggal Awal atau Tanggal Akhir yang dipilih tidak valid");
            $('button').removeAttr('disabled', 'disabled');
        } else {
            if(end_date < start_date){
                vex.dialog.alert("Tanggal Akhir harus sama dengan atau lebih dari Tanggal Awal");
                $('button').removeAttr('disabled', 'disabled');
            } else {
                window.open(print_laporan_url + '/' + 'print-arus-kas' + '/' + jenis + '/' + start_date + '/' + end_date, "_blank");
                $('button').removeAttr('disabled', 'disabled');
            }
        }
    }

    function printBulanan(jenis){
        $('button').attr('disabled', 'disabled');
        var start_date = $('input[name=start_date]').val();
        var end_date = $('input[name=end_date]').val();

        if(start_date == null || end_date == null || start_date == '' || end_date == ''){
            vex.dialog.alert("Tanggal Awal atau Tanggal Akhir yang dipilih tidak valid");
            $('button').removeAttr('disabled', 'disabled');
        } else {
            if(end_date < start_date){
                vex.dialog.alert("Tanggal Akhir harus sama dengan atau lebih dari Tanggal Awal");
                $('button').removeAttr('disabled', 'disabled');
            } else {
                var s = new Date(start_date);
                var e = new Date(end_date);
                
                if(s.getMonth() == e.getMonth() && s.getFullYear() == e.getFullYear()){
                    window.open(print_laporan_url + '/' + 'print-laporan-bulanan' + '/' + jenis + '/' + start_date + '/' + end_date, "_blank");
                }else{
                    vex.dialog.alert("Laporan ini hanya dapat dicetak dalam rentang waktu 1 bulan");
                }
                $('button').removeAttr('disabled', 'disabled');
            }
        }
    }

    function changeSettingSession(){
        $.ajax({
            type: "POST",
            data: {
                print_setting: $('input[name=print_setting]:checked').val()
            },
            url: setting_url,
            success: function (response) {
                console.log('Success ' + response);
            },
        });
    }
</script>
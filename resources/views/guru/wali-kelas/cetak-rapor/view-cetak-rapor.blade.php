<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>Cetak Rapor Siswa</h2>
                </div>
                <div class="body">
                    <div style="float:left;">
                        <span style="font-size: 20px;">Cetak Rapor Seluruh Siswa Kelas <strong>{{$data_wali_kelas->nm_kelas}}</strong></span><br>
                        Wali Kelas {{$data_wali_kelas->nm_wali_kelas}}
                    </div>
                    <a style="float:right;" class="btn btn-success btn-circle waves-effect waves-circle float-right" href="{{ url(Request::segment(1).'/'.Request::segment(2).'/cetak-rapor-siswa/print/'. $wali_kelas->id_kelas) }}" target="_blank">
                        <i class="material-icons">picture_as_pdf</i>
                    </a>

                    <div class="spacer" style="clear: both;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

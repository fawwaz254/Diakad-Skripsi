<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>Rapor Sisipan Siswa</h2>
                </div>
                <div class="body">
                    <div style="float:left;">
                        <span style="font-size: 20px;">Cetak Rapor Sisipan STS
                            {{-- <strong>{{ $data_wali_kelas->nm_kelas }} </strong>
                            Tahun Ajaran {{ $semester_aktif->tahun_ajaran }}</span><br>
                        Wali Kelas {{ $data_wali_kelas->nm_wali_kelas }} --}}

                    </div>
                    <a style="float:right;" class="btn btn-success btn-circle waves-effect waves-circle float-right"
                        href="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/cetak/0') }}"
                        target="_blank">
                        <i class="material-icons">picture_as_pdf</i>
                    </a>

                    <div class="spacer" style="clear: both;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

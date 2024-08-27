<div class="container-fluid">
    <div class="block-header">
        <h2>
            <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/add') }}"><i
                    class="material-icons">add</i><span>Tambah Aktivitas Reward Siswa</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Data Aktivitas Reward Siswa</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th style="vertical-align : middle;text-align:center;">No</th>
                                    <th style="vertical-align : middle;text-align:center;">Tanggal</th>
                                    <th style="vertical-align : middle;text-align:center;">Jenis</th>
                                    <th style="vertical-align : middle;text-align:center;">Uraian
                                        Kegiatan</th>
                                    <th style="vertical-align : middle;text-align:center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="5" style="text-align: center">Tidak ada data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

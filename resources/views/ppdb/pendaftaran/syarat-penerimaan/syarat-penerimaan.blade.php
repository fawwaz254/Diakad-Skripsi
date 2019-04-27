<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#pendaftaran/syarat-penerimaan')}}"><i class="material-icons">keyboard_backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    {{csrf_field()}}
                    <div class="header bg-lime">
                        <h2>SYARAT PENERIMAAN - DAFTAR SYARAT</h2>
                    </div>

                    <div class="body" style="padding-bottom:50px;">
                        <table class="" style="margin-bottom:20px;">
                            <tr>
                                <td>Nama Penerimaan</td>
                                <td style="padding-left:5px">: <strong>{{$penerimaan->nm_penerimaan}}</strong></td>
                            </tr>
                            <tr>
                                <td>Semester</td>
                                <td style="padding-left:5px">: <strong>{{$penerimaan->nm_semester_penerimaan . ", " . $penerimaan->tahun_penerimaan}}</strong></td>
                            </tr>
                            <tr>
                                <td>Gelombang</td>
                                <td style="padding-left:5px">: <strong>{{$penerimaan->gelombang_penerimaan}}</strong></td>
                            </tr>
                        </table>

                        <br><h4>Syarat Umum</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Syarat</th>
                                        <th>Keterangan</th>
                                        <th>Status</th>
                                        <th>Upload File</th>
                                        <th>Urutan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $n = 1;
                                    @endphp
                                    @forelse($penerimaan_syarat_umum as $i => $syarat_umum)
                                    <tr>
                                        <td>{{$n++}}</td>
                                        <td>{{$syarat_umum->nm_penerimaan_syarat}}</td>
                                        <td>{{$syarat_umum->keterangan_penerimaan_syarat}}</td>
                                        <td>{{($syarat_umum->is_wajib=="1"?"Wajib":"Tidak Wajib")}}</td>
                                        <td>{{($syarat_umum->is_upload_file=="1"?"Ya":"Tidak")}}</td>
                                        <td>{{$syarat_umum->urutan}}</td>
                                        <td>
                                            <a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="{{url(Request::segment(1).'#pendaftaran/syarat-penerimaan/'.$syarat_umum->id_penerimaan.'/edit/'.$syarat_umum->id_penerimaan_syarat)}}"><i class="material-icons">edit</i></a>
                                            
                                            <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction('ppdb/pendaftaran/syarat-penerimaan/{{$syarat_umum->id_penerimaan}}/delete', this)" data-id="{{$syarat_umum->id_penerimaan_syarat}}">
                                                <i class="material-icons">delete_forever</i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7">no data</td>
                                    </tr>
                                    @endforelse
                                <tbody>
                            </table>

                            <h2><a class="btn bg-green waves-effect target-link" href="{{url(Request::segment(1).'#pendaftaran/syarat-penerimaan/'.$penerimaan->id_penerimaan.'/add')}}"><i class="material-icons">note_add</i><span>Tambah Syarat Umum</span></a></h2>
                        </div>

                        <br><br><h4>Syarat Khusus</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jurusan</th>
                                        <th>Syarat</th>
                                        <th>Keterangan</th>
                                        <th>Status</th>
                                        <th>Upload</th>
                                        <th>Urutan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $m = 1;
                                    @endphp
                                    @forelse($penerimaan_syarat_khusus as $k => $khusus)
                                    <tr>
                                        <td>{{$m++}}</td>
                                        <td>{{$khusus->nm_jurusan}}</td>
                                        <td>{{$khusus->nm_penerimaan_syarat}}</td>
                                        <td>{{$khusus->keterangan_penerimaan_syarat}}</td>
                                        <td>{{($khusus->is_wajib=="1"?"Wajib":"Tidak Wajib")}}</td>
                                        <td>{{($khusus->is_upload_file=="1"?"Ya":"Tidak")}}</td>
                                        <td>{{$khusus->urutan}}</td>
                                        <td>
                                            <a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="{{url(Request::segment(1).'#pendaftaran/syarat-penerimaan/'.$khusus->id_penerimaan.'/edit/'.$khusus->id_penerimaan_syarat.'?type=khusus')}}"><i class="material-icons">edit</i></a>
                                            
                                            <button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction('ppdb/pendaftaran/syarat-penerimaan/{{$khusus->id_penerimaan}}/delete', this)" data-id="{{$khusus->id_penerimaan_syarat}}">
                                                <i class="material-icons">delete_forever</i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8">no data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            
                            <h2><a class="btn bg-green waves-effect target-link" href="{{url(Request::segment(1).'#pendaftaran/syarat-penerimaan/'.$penerimaan->id_penerimaan.'/add?type=khusus')}}"><i class="material-icons">note_add</i><span>Tambah Syarat Khusus</span></a></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
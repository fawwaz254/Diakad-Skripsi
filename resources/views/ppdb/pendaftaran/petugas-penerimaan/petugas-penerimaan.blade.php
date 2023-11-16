<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#pendaftaran/petugas-penerimaan') }}"><i
                    class="material-icons">keyboard_backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>PETUGAS PENERIMAAN - DAFTAR PETUGAS</h2>
                </div>

                <div class="body" style="padding-bottom:50px;">
                    <table class="" style="margin-bottom:20px;">
                        <tr>
                            <td>Nama Penerimaan</td>
                            <td style="padding-left:5px">: <strong>{{ $penerimaan->nm_penerimaan }}</strong></td>
                        </tr>
                        <tr>
                            <td>Semester</td>
                            <td style="padding-left:5px">:
                                <strong>{{ $penerimaan->nm_semester_penerimaan . ', ' . $penerimaan->tahun_penerimaan }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>Gelombang</td>
                            <td style="padding-left:5px">: <strong>
                                    {{ $penerimaan->gelombang_penerimaan == '0' ? 'Inden' : $penerimaan->gelombang_penerimaan }}
                                </strong></td>
                        </tr>
                    </table>

                    <!-- <br><h4>Syarat Umum</h4> -->
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIK/NIP</th>
                                    <th>Nama</th>
                                    <th>Jabatan / Status Pegawai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $ct = 1;
                                @endphp
                                @forelse($penerimaan_petugas as $petugas)
                                    <tr>
                                        <td>{{ $ct++ }}</td>
                                        <td>{{ !empty($petugas->nip_guru) ? $petugas->nip_guru : (!empty($petugas->nip_staff) ? $petugas->nip_staff : '') }}
                                        </td>
                                        <td>{{ $petugas->nm_pengguna }}</td>
                                        <td>{{ $petugas->jabatan_petugas == '1' ? 'Admin' : 'Verifikator' }} /
                                            {{ $petugas->status_join_table == '1' ? 'Tendik' : 'Guru' }}</td>
                                        <td>
                                            <button
                                                class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                                onclick="deleteAction('ppdb/pendaftaran/petugas-penerimaan/{{ $penerimaan->id_penerimaan }}/delete', this)"
                                                data-id="{{ $petugas->id_penerimaan_petugas }}">
                                                <i class="material-icons">delete_forever</i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">no data</td>
                                    </tr>
                                @endforelse
                            <tbody>
                        </table>

                        <h2><a class="btn bg-green waves-effect target-link"
                                href="{{ url(Request::segment(1) . '#pendaftaran/petugas-penerimaan/' . $penerimaan->id_penerimaan . '/add') }}"><i
                                    class="material-icons">note_add</i><span>Tambah Petugas</span></a></h2>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@include('scriptjs')

{{-- @dd($kunjungan_magang); --}}
<div class="container-fluid">
    <div class="block-header">
        <h2>
            {{-- <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#magang-siswa/rekanan-magang/add') }}">
                <i class="material-icons">note_add</i>
                <span>Tambah Rekanan Magang</span>
            </a>
            <a class="btn bg-green waves-effect"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3) . '/import-excel') }}">
                <i class="material-icons">attach_file</i>
                <span>Import Rekanan Magang</span>
            </a> --}}
            <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . 'add-kunjungan-magang') }}">
                <i class="material-icons">note_add</i>
                <span>Tambah Kunjungan</span>
            </a>
            <div>

            </div>
            {{-- <a class="btn bg-green waves-effect" href="#">
                <i class="material-icons">attach_file</i>
                <span>Import Rekanan Magang</span>
            </a> --}}
        </h2>
    </div>

    @foreach ($kunjungan_magang as $km)
        <div class="modal fade" id="deleteModal{{ $km->id_kunjungan_magang }}" tabindex="-1" role="dialog"
            aria-labelledby="myModalLabel{{ $km->id_kunjungan_magang }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h2 class="modal-title" style="color:red; font-size: 23px; text-align: center;"
                            id="myModalLabel{{ $km->id_kunjungan_magang }}">
                            Peringatan!</h2>
                    </div>
                    <div class="modal-body text-center">
                        <p style="font-size: 20px;  color: black;">Anda yakin ingin menghapus kunjungan magang ini?
                        </p>
                        <p style="font-style: italic;">Data yang sudah
                            dihapus tidak dapat dipulihkan kembali!</p>
                    </div>
                    <div class="modal-footer"
                        style="display: flex; gap: 10px; align-items: center; justify-content: center;">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                        <form id="deleteForm{{ $km->id_kunjungan_magang }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#deleteForm{{ $km->id_kunjungan_magang }}').submit(function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('guru.destroy.kunjungan-magang', $km->id_kunjungan_magang) }}",
                        type: 'DELETE',
                        success: function(result) {
                            vex.dialog.alert(result.message);
                            $('#deleteModal{{ $km->id_kunjungan_magang }}').modal('hide');
                            $('#primary_table').DataTable().ajax.reload();
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr);
                        }
                    });
                })
            })
        </script>
    @endforeach

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{-- {{ csrf_field() }} --}}
                <div class="header">
                    <h2>DATA KUNJUNGAN MAGANG</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap "
                            style="width: 100%" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    {{-- <th>Foto Kunjungan</th> --}}
                                    <th>Nama Periode Magang</th>
                                    <th>Nama Rekanan Magang</th>
                                    <th>Keterangan Kunjungan</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            {{-- <tbody>
                                @forelse ($kunjungan_magang as $km)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>-</td>
                                        <td>{{ $km->periode_magang->nm_periode_magang }}</td>
                                        <td>{{ $km->rekanan_magang->nm_rekanan_magang }}</td>
                                        <td>{{ $km->keterangan_kunjungan }}</td>
                                        <td>Action</td>
                                    </tr>
                                @empty
                                    <p>Tidak ada data</p>
                                @endforelse
                            </tbody> --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // // var modul_url = location.hash.replace('#','').split('/')[0];
    // var modul_url = 'magang-siswa';
    // var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'rekanan-magang/datatables';
    // var edit_url = role_url + '#' + modul_url + '/' + 'rekanan-magang/edit';
    // var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-rekanan-magang/delete';

    $(document).ready(function() {
        let table = $('#primary_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('index.dataKunjunganMagang') }}',
            columns: [{
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                searchable: false,
                orderable: false
            }, {
                data: 'periode_magang',
                name: 'periode_magang'
            }, {
                data: 'rekanan_magang',
                name: 'rekanan_magang'
            }, {
                data: 'keterangan_kunjungan',
                name: 'keterangan_kunjungan'
            }, {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false
            }]
        });
    })

    // var primary_table = $('#primary_table').DataTable({
    //     processing: true,
    //     serverSide: true,
    //     responsive: false,
    //     ajax: {
    //         url: datatable_url,
    //         type: 'GET'
    //     },
    //     columns: [{
    //             data: null,
    //             searchable: false,
    //             orderable: false
    //         },
    //         {
    //             data: 'nm_rekanan_magang',
    //             name: 'nm_rekanan_magang'
    //         },
    //         {
    //             data: 'nomor_telp_rekanan_magang',
    //             name: 'nomor_telp_rekanan_magang'
    //         },
    //         {
    //             data: 'nomor_hp_rekanan_magang',
    //             name: 'nomor_hp_rekanan_magang'
    //         },
    //         {
    //             data: 'alamat_rekanan_magang',
    //             name: 'alamat_rekanan_magang'
    //         },
    //         {
    //             data: 'tgl_mulai',
    //             name: 'tgl_mulai'
    //         },
    //         {
    //             data: 'tgl_selesai',
    //             name: 'tgl_selesai'
    //         },
    //         {
    //             data: 'kuota_rekanan_magang',
    //             name: 'kuota_rekanan_magang'
    //         },
    //         {
    //             data: 'contact_person_rekanan_magang',
    //             name: 'contact_person_rekanan_magang'
    //         },
    //         {
    //             data: 'action',
    //             name: 'action',
    //             searchable: false,
    //             orderable: false,
    //             render: function(data) {
    //                 return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
    //                     edit_url + '/' + data.id + '">' +
    //                     '    <i class="material-icons">edit</i>' +
    //                     '</a> ' +
    //                     '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteAction(\'' +
    //                     delete_url + '\', this)" data-id="' + data.id + '">' +
    //                     '    <i class="material-icons">delete_forever</i>' +
    //                     '</button>';
    //             }
    //         }
    //     ]
    // });

    // primary_table.on('draw', function() {
    //     primary_table.column(0, {
    //         search: 'applied',
    //         order: 'applied'
    //     }).nodes().each(function(cell, i) {
    //         var start = this.page.info().page * this.page.info().length;
    //         cell.innerHTML = start + i + 1;
    //     });
    // }).draw();
</script>

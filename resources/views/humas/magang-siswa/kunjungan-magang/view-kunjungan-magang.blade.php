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
            {{-- <a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . 'add-kunjungan-magang') }}">
                <i class="material-icons">note_add</i>
                <span>Tambah Kunjungan</span>
            </a>
            <div>

            </div> --}}
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
                        <form id="deleteForm{{ $km->id_kunjungan_magang }}" method="POST" action="{{ route('destroy.kunjungan-magang', $km->id_kunjungan_magang) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger waves-effect">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{-- {{ csrf_field() }} --}}
                <div class="header" style="display: flex; align-items: center; justify-content: space-between;">
                    <h2>DATA KUNJUNGAN MAGANG</h2>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <form action="" method="GET">
                            <select name="periode_magang" id="periode_magang" class="form-control">
                                <option value="" selected>Pilih Periode Magang</option>
                                @foreach ($periode_magang as $periode)
                                    <option value="{{ $periode->id_periode_magang }}">
                                        {{ $periode->nm_periode_magang . ' ' . $periode->nomor_sk_periode_magang }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                        <button type="button" id="btn-reset" class="btn btn-primary waves-effect">Reset Filter</button>
                    </div>

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
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function () {
        let table = $('#primary_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('humas.dataKunjunganMagang') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, orderable: false },
                { data: 'periode_magang', name: 'periode_magang' },
                { data: 'rekanan_magang', name: 'rekanan_magang' },
                { data: 'keterangan_kunjungan', name: 'keterangan_kunjungan' },
                { data: 'action', name: 'action', searchable: false, orderable: false }
            ]
        });

        // Filter by Periode
        $('#periode_magang').select2();
        $('#periode_magang').on('change', function () {
            var id_periode = $(this).val();
            table.ajax.url('{{ route('humas.dataKunjunganMagang') }}?id_periode_magang=' + id_periode).load();
        });

        // Reset Filter
        $('#btn-reset').on('click', function () {
            $('#periode_magang').val('').trigger('change');
            table.ajax.url('{{ route('humas.dataKunjunganMagang') }}').load();
        });

        // Submit Delete Modal
    $(document).on('submit', 'form[id^="deleteForm"]', function (e) {
    e.preventDefault();
    let form = $(this);
    let url = form.attr('action');

    $.ajax({
    url: url,
    type: 'POST',
    data: form.serialize(), //  otomatis membawa _token dan _method
    success: function (result) {
        $('.modal').modal('hide');
        $('#primary_table').DataTable().ajax.reload();
        alert(result.message || 'Data berhasil dihapus.');
    },
    error: function (xhr) {
        console.error(xhr.responseText);
        alert('Gagal menghapus data.');
    }
});

});

    });
</script>

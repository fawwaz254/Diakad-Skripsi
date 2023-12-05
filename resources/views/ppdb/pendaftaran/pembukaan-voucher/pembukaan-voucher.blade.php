<style>
    .tarif {
        color: #000;
        text-align: right;
    }
</style>
<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#pendaftaran/pembukaan-voucher') }}"><i
                    class="material-icons">keyboard_backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>PEMBUKAAN NOMOR PENDAFTARAN - DAFTAR TARIF & NOMOR PENDAFTARAN</h2>
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
                            <td style="padding-left:5px">:
                                <strong>{{ $penerimaan->gelombang_penerimaan == 0 ? 'Inden' : $penerimaan->gelombang_penerimaan }}</strong>
                            </td>
                        </tr>
                    </table>

                    <br>
                    <h4>Tarif</h4>
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Semester</th>
                                    <th>Tahun ajaran</th>
                                    <th>Jurusan</th>
                                    <th>Tarif Nominal</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $ct = 1;
                                @endphp
                                @foreach ($voucher_tarif as $tarif)
                                    <tr>
                                        <td>{{ $ct++ }}</td>
                                        <td>{{ $tarif->nm_semester }}</td>
                                        <td>{{ $tarif->tahun_ajaran }}</td>
                                        <td>{{ $tarif->nm_jurusan == '' ? 'Semua Jurusan' : $tarif->nm_jurusan }}</td>
                                        <td class="tarif">{{ $tarif->tarif }}</td>
                                        <td>{{ $tarif->deskripsi }}</td>
                                        <td>
                                            <button
                                                class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                                onclick="deleteAction('ppdb/pendaftaran/pembukaan-voucher/{{ $penerimaan->id_penerimaan }}/delete', this)"
                                                data-id="{{ $tarif->id_voucher_tarif }}">
                                                <i class="material-icons">delete_forever</i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            <tbody>
                        </table>
                    </div>

                    <h2><a class="btn bg-green waves-effect target-link"
                            href="{{ url(Request::segment(1) . '#pendaftaran/pembukaan-voucher/' . $penerimaan->id_penerimaan . '/add') }}"><i
                                class="material-icons">note_add</i><span>Tambah Tarif</span></a></h2>

                    <!-- separator -->
                    <br><br>
                    <h4>Nomor Pendaftaran</h4>
                    <div class="table-responsive">
                        <table
                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="tabel-voucher">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nominal</th>
                                    <th>Nomor Pendaftaran</th>
                                    <th>Pin Password</th>
                                    <th>Tanggal Ambil</th>
                                    <th>Tanggal Bayar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $ct2 = 1;
                                @endphp
                                @foreach ($vouchers as $voucher)
                                    <tr>
                                        <td>{{ $ct2++ }}</td>
                                        <td class="tarif">{{ $voucher->tarif }}</td>
                                        <td>{{ $voucher->kode_voucher }}</td>
                                        <td>{{ $voucher->pin_password }}</td>
                                        <td>{{ !empty($voucher->tgl_ambil) ? $voucher->tgl_ambil : '' }}</td>
                                        <td>{{ !empty($voucher->tgl_bayar) ? $voucher->tgl_bayar : '' }}</td>
                                        <td>
                                            <button
                                                class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                                onclick="deleteAction('ppdb/pendaftaran/pembukaan-voucher/{{ $penerimaan->id_penerimaan }}/delete-voucher', this)"
                                                data-id="{{ $voucher->id_voucher }}">
                                                <i class="material-icons">delete_forever</i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            <tbody>
                        </table>
                    </div>

                    <h2><a class="btn bg-green waves-effect target-link"
                            href="{{ url(Request::segment(1) . '#pendaftaran/pembukaan-voucher/' . $penerimaan->id_penerimaan . '/generate-voucher') }}"><i
                                class="material-icons">note_add</i><span>Tambah Nomor Pendaftaran</span></a></h2>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@include('scriptjs')
<script src="https://cdn.datatables.net/plug-ins/1.13.7/sorting/currency.js"></script>
<script>
    (function() {
        // run datatables
        $('#tabel-voucher').DataTable({
            "pageLength": 50
        });

        var tarif = document.querySelectorAll('.tarif');
        Array.prototype.forEach.call(tarif, function(elements, index) {
            // conditional here.. access elements
            elements.innerHTML = formatRupiah(elements.innerHTML, "Rp. ");
        });
    })();

    /* Fungsi formatRupiah */
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }
</script>

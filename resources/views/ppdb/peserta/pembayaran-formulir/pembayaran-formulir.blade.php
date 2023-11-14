<style>
    input[type="text"]:disabled {
        background: #dddddd;
    }

    select[id="metode_pembayaran"]:disabled {
        background: #dddddd;
    }

    select[id="id_bank"]:disabled {
        background: #dddddd;
    }

    select[id="id_bank_via"]:disabled {
        background: #dddddd;
    }
</style>
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Pembayaran Formulir</h2>
                </div>

                <div class="body" style="padding-bottom:50px;">

                    <!-- form cari voucher -->
                    <form id="form-validation" method="POST" enctype="multipart/form-data"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/pembayaran-formulir') }}">
                        {{ csrf_field() }}
                        <div class="card-inside-title">
                            No. Formulir / Nomor Pendaftaran
                            <small class="form-text text-muted">
                                *masukkan nomor pendaftaran yang ingin dibayar
                            </small>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-bottom:0px !important;">
                                <input type="text" class="form-control simulateplz" id="kode_voucher"
                                    name="kode_voucher" aria-required="true" aria-invalid="true"
                                    value="{{ $voucher == null ? '' : $voucher->kode_voucher }}" autofocus>
                            </div>
                        </div>

                        <h2><button class="btn bg-green waves-effect" type="submit"><i
                                    class="material-icons">search</i><span>Cari Voucher</span></button></h2>
                    </form>

                    <!-- detail voucher -->
                    @if ($voucher != null)
                        <div class="{{ $voucher == null ? 'hidden' : '' }}" style="margin-top:40px;">
                            @if ($voucher->tgl_ambil == null)
                                <div class="alert alert-info">
                                    <p>Formulir belum dapat dibayar karena nomor pendaftaran belum diambil oleh calon
                                        siswa</p>
                                </div>
                            @endif
                            @if ($voucher->tgl_bayar != null)
                                <div class="alert alert-info">
                                    <p>Formulir sudah dibayar</p>
                                </div>
                            @endif

                            <div class="row clearfix">
                                <div class="col-lg-7" style="margin-bottom:0px !important;">
                                    <div class="table-responsive">
                                        <table
                                            class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                            id="primary_table">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">Detail Pendaftaran</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Nomor Pendaftaran</td>
                                                    <td>{{ $voucher->kode_voucher }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Pin Pendaftaran</td>
                                                    <td>{{ $voucher->pin_password }}</td>
                                                </tr>
                                                <tr>
                                                    <td>UANG MUKA (DP)</td>
                                                    <td class="tarif">{{ $voucher->tarif }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Gelombang Penerimaan</td>
                                                    @if (!empty($voucher->nm_penerimaan))
                                                        <td>{{ $voucher->nm_penerimaan }} Gelombang
                                                            {{ $voucher->gelombang_penerimaan }}</td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                </tr>
                                                <tr>
                                                    <td>Tanggal Ambil</td>
                                                    <td>{{ !empty($voucher->tgl_ambil) ? $voucher->tgl_ambil : '' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Tanggal Bayar</td>
                                                    <td>{{ !empty($voucher->tgl_bayar) ? $voucher->tgl_bayar : '' }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Nama Calon Siswa</td>
                                                    <td>{{ $voucher->nm_c_siswa }}</td>
                                                </tr>
                                            <tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- start form -->
                            <!-- <h3>Bayar Formulir</h3> -->
                            <br>

                            @if ($voucher->tgl_ambil != null)
                                <form id="form-validation1" method="POST"
                                    action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/pembayaran-formulir/' . $voucher->kode_voucher . '/bayar-voucher') }}">

                                    {{ csrf_field() }}
                                    <input type="hidden" name="code_voucher" value="{{ $voucher->kode_voucher }}">

                                    <div class="card-inside-title">
                                        Besar Biaya (Rp.)
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                            style="margin-bottom:0px !important;">
                                            @if ($voucher->besar_biaya != null)
                                                <input type="text" class="form-control simulateplz targetdisabled"
                                                    id="besar_biaya" name="besar_biaya" aria-required="true"
                                                    aria-invalid="true" value="{{ $voucher->besar_biaya }}">
                                            @else
                                                <input type="text" class="form-control simulateplz targetdisabled"
                                                    id="besar_biaya" name="besar_biaya" aria-required="true"
                                                    aria-invalid="true" value="{{ $voucher->tarif }}">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="card-inside-title">
                                        Metode Pembayaran
                                        <small class="form-text text-muted">
                                            *pembayaran voucher dilakukan dengan metode cash/transfer
                                        </small>
                                    </div>
                                    <div class="row clearfix" style="margin-bottom:28px">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                            style="margin-bottom:0px !important;">
                                            @if ($voucher->tgl_bayar != null)
                                                <select class="form-control show-tick targetdisabled"
                                                    name="metode_pembayaran" id="metode_pembayaran">
                                                    <option value="">-</option>
                                                    <option value="0"
                                                        {{ $voucher->is_tagih_bank != '1' ? 'selected' : '' }}>Manual
                                                        (Cash)</option>
                                                    <option value="1"
                                                        {{ $voucher->is_tagih_bank == '1' ? 'selected' : '' }}>Via Bank
                                                        (Transfer)</option>
                                                </select>
                                            @else
                                                <select class="form-control show-tick targetdisabled"
                                                    name="metode_pembayaran" id="metode_pembayaran">
                                                    <option value="">-</option>
                                                    <option value="0">Manual (Cash)</option>
                                                    <option value="1">Via Bank (Transfer)</option>
                                                </select>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="{{ $voucher->is_tagih_bank == '1' ? '' : 'hidden' }}"
                                        id="transfer-section">
                                        <div class="card-inside-title">
                                            Nomor Transaksi
                                            <small class="form-text text-muted">
                                                *nomor transaksi transfer
                                            </small>
                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                                style="margin-bottom:0px !important;">
                                                <input type="text" class="form-control simulateplz targetdisabled"
                                                    id="nomor_transaksi" name="nomor_transaksi" aria-required="true"
                                                    aria-invalid="true" value="{{ $voucher->nomor_transaksi }}">
                                            </div>
                                        </div>

                                        <div class="card-inside-title">
                                            Transfer Via Bank
                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 via"
                                                style="margin-bottom:0px !important;">
                                                <select class="form-control show-tick targetdisabled" name="id_bank"
                                                    id="id_bank">
                                                    <option value="">-</option>
                                                    @foreach ($bank as $bnk)
                                                        <option value="{{ $bnk->id_bank }}">{{ $bnk->nm_bank }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @if ($voucher->id_bank != null)
                                            <script>
                                                var $option_id_bank = $('#id_bank option[value={{ $voucher->id_bank }}]');
                                                $option_id_bank.attr('selected', 'selected');
                                            </script>
                                        @endif

                                        <div class="card-inside-title">
                                            Transfer Metode
                                        </div>
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                                style="margin-bottom:0px !important;">
                                                <select class="form-control show-tick targetdisabled"
                                                    name="id_bank_via" id="id_bank_via">
                                                    <option value="">-</option>
                                                    @foreach ($bank_via as $via)
                                                        <option value="{{ $via->id_bank_via }}">
                                                            {{ $via->nm_bank_via }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @if ($voucher->id_bank_via != null)
                                            <script>
                                                var $opt = $('#id_bank_via option[value={{ $voucher->id_bank_via }}]');
                                                $opt.attr('selected', 'selected');
                                            </script>
                                        @endif
                                    </div>

                                    <h2>
                                        @if ($voucher->tgl_bayar != null)
                                            <button class="btn bg-red waves-effect"
                                                onclick="deleteAction('ppdb/peserta/pembayaran-formulir/reset-voucher', this)"
                                                data-id="{{ $voucher->kode_voucher }}">
                                                <i class="material-icons">delete_forever</i><span>Reset Voucher</span>
                                            </button>
                                        @endif
                                        <button class="btn bg-green waves-effect" type="submit"
                                            {{ $voucher->tgl_bayar != null ? 'disabled' : '' }}><i
                                                class="material-icons">note_add</i><span>Bayar Voucher</span></button>
                                    </h2>
                                </form>
                                <!-- end form -->


                            @endif
                        </div>
                        <!-- end of detail voucher -->
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
</div>
@include('scriptjs')
<script>
    var primary_table = null;
    $('#form-validation1').validate({
        rules: {
            'checkbox': {
                required: true
            },
            'gender': {
                required: true
            }
        },
        highlight: function(input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function(input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function(error, element) {
            $(element).parents('.form-group').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status == 200) {
                        vex.dialog.alert(response.message);
                    } else if (response.status == 201) {
                        vex.dialog.alert(response.message);
                        window.location.href = response.link;
                    } else if (response.status == 202) {
                        vex.dialog.alert(response.message);
                        loadURI(response.path);
                    } else if (response.status == 203) {
                        vex.dialog.alert(response.message);
                        primary_table.ajax.reload(null, false);
                    } else if (response.status == 204) {
                        loadURI(response.path);
                    } else if (response.status == 300) {
                        vex.dialog.alert(response.message);
                    }
                },
                complete: function() {
                    $('button').removeAttr('disabled', 'disabled');
                }
            });
        }
    });
</script>
<script>
    (function() {
        var tarif = document.querySelectorAll('.tarif');
        Array.prototype.forEach.call(tarif, function(elements, index) {
            // conditional here.. access elements
            elements.innerHTML = formatRupiah(elements.innerHTML, "Rp. ");
        });
        @if ($voucher != null)
            @if ($voucher->tgl_bayar != null)
                $(".targetdisabled").prop("disabled", true);
            @endif
        @endif
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

    $('#metode_pembayaran').on('change', function(e) {
        var optionSelected = $(this).find("option:selected");
        if ($(this).val() == "1") {
            $('#transfer-section').removeClass('hidden');
        } else {
            $('#transfer-section').addClass('hidden');
        }
    });
</script>

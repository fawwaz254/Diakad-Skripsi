<input type="hidden" id="nama" value="{{ $auth_data->pengguna->nm_pengguna }}">
<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>KODE BAYAR AKTIF</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nomor Transaksi</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Jumlah Bayar</th>
                                    <th>Bayar melalui</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pembayaran_aktif as $pembayaran_trs)
                                <tr>
                                    <td>{{ $pembayaran_trs->nomor_transaksi }}</td>
                                    <td>{{ $pembayaran_trs->keterangan }}</td>
                                    <td>{{ $pembayaran_trs->status_pembayaran_to_text() }}</td>
                                    <td>Rp{{ number_format($pembayaran_trs->besar_pembayaran) }}</td>
                                    <td>{{ $pembayaran_trs->payment_code }}</td>
                                    <td>
                                        <button class="btn btn-warning button_open_modal"
                                            data-link="{{ url('payment/detail/' . $pembayaran_trs->id_pembayaran_trs) }}"
                                            data-keterangan="{{ $pembayaran_trs->keterangan }}" type="button"
                                            waves-effect>
                                            <i class="material-icons">share</i>
                                            <span>Share Link Pembayaran</span>
                                        </button>
                                        <a target="_blank" href="{{ url('payment/detail/' . $pembayaran_trs->id_pembayaran_trs) }}">
                                            <button class="btn btn-success waves-effect">
                                                <i class="material-icons">attach_money</i>
                                                <span>Bayar Sekarang</span>
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <form id="form-validation" method="POST" action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/generate') }}">
                    {{ csrf_field() }}
                    <div class="header">
                        <h2>TAGIHAN SISWA</h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>
                                            <input id="checkbox_select_all_primary_table" type="checkbox" name="select_all" class="filled-in">
                                            <label for="checkbox_select_all_primary_table" style="margin-bottom: -10px;"></label>
                                        </th>
                                        <th>Biaya</th>
                                        <th>-</th>
                                        <th>Semester</th>
                                        <th>Besar Tagihan</th>
                                        <th>Denda Tagihan</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <button class="btn bg-blue waves-effect" type="submit">
                            <span>Bayar yang dicentang</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_share_link" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="defaultModalLabel">Share Link Pembayaran</h4>
            </div>
            <div class="modal-body">
                <div>
                    <a id="button_wa" href="#" target="_blank">
                        <button type="button" class="btn bg-green btn-block waves-effect">
                            <i class="material-icons">whatsapp</i> <span>Share Lewat Whatsapp</span>
                        </button>
                    </a>
                </div>
                <div>
                    <a id="button_telegram" href="#" target="_blank">
                        <button type="button" style="margin-top: 10px;" class="btn bg-primary btn-block waves-effect">
                            <i class="material-icons">telegram</i> <span>Share Lewat Telegram</span>
                        </button>
                    </a>
                </div>
                <div id="copy"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

@include('scriptjs')

<script>
    $('.button_open_modal').click(function() {

        var keterangan = $(this).data('keterangan');
        var link = $(this).data('link');
        var nama = $('#nama').val();
        $('#modal_share_link').modal('show');

        link = `berikut ini merupakan link untuk melakukan pembayaran ` + keterangan + ` atas nama ` + nama +
            ` ` + link + ` `;

        $('#copy').html(`
            <button type="button" onclick="copyToClipboard('` + link + `')" style="margin-top: 10px;" class="btn bg-blue-grey btn-block waves-effect">
                <i class="material-icons">content_copy</i> <span> Just Copy Link Pembayaran</span>
            </button>
        `);

        $("#button_wa").attr("href", "https://wa.me/?text=" + link);
        $("#button_telegram").attr("href", "https://telegram.me/share/url?url=" + link);

    })

    function copyToClipboard(link) {
        var $input = $("<input>");
        $input.val(link).appendTo('body').select();
        document.execCommand('copy');
        $input.remove();

        vex.dialog.alert('Link copied!');
    }

    var modul_url = 'keuangan';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'tagihan/datatables';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: false,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data, type, full, meta) {
                    return '<input id="checkbox-' + data.id +
                        '" type="checkbox" name="id_tagihan_biaya[]" class="filled-in" value="' +
                        data.id + '">' +
                        '<label for="checkbox-' + data.id + '"></label>';

                }
            },
            {
                data: 'nm_biaya',
                name: 'nm_biaya'
            },
            {
                data: 'jenis_biaya',
                name: 'jenis_biaya'
            },
            {
                data: 'semester',
                name: 'semester',
                searchable: false,
                orderable: false
            },
            {
                data: 'besar_biaya',
                name: 'besar_biaya'
            },
            {
                data: 'denda_biaya',
                name: 'denda_biaya'
            },
        ]
    });

    primary_table.on('draw', function() {
        primary_table.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        });
    }).draw();
</script>
<script type="text/javascript">
    $(document).ready(function() {
        /* Select All Checkbox */
        $('#checkbox_select_all_primary_table').change(function() {
            var select_all_checked = this.checked;
            var rows = primary_table.rows({
                'search': 'applied'
            }).nodes();

            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });
    });
</script>

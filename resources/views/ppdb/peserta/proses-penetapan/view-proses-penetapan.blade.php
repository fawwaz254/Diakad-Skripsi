<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>PROSES PENETAPAN</h2>
                </div>
                <div class="body">
                    <form id="form-validation1" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/' . Request::segment(3) . '/post-view-proses-penetapan') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="form-line">
                                <select class="form-control" name="id_penerimaan" id="select-penerimaan">
                                    <option value="">- Pilih Penerimaan -</option>
                                    @foreach ($grup_penerimaan_tahun as $tahun => $grup_penerimaan)
                                        @foreach ($grup_penerimaan as $semester => $datapergrup)
                                            <optgroup label="{{ $tahun }} {{ $semester }}">
                                                @foreach ($datapergrup as $data)
                                                    @if ($mode == 'show')
                                                        @if ($data->id_penerimaan == $penerimaan->id_penerimaan)
                                                            <option value="{{ $data->id_penerimaan }}" selected>
                                                                {{ 'Gelombang ' . $data->gelombang_penerimaan . ' ' . $data->nm_penerimaan . ' (' . $tahun . ')' }}
                                                            </option>
                                                        @else
                                                            <option value="{{ $data->id_penerimaan }}">
                                                                {{ 'Gelombang ' . $data->gelombang_penerimaan . ' ' . $data->nm_penerimaan . ' (' . $tahun . ')' }}
                                                            </option>
                                                        @endif
                                                    @else
                                                        <option value="{{ $data->id_penerimaan }}">
                                                            {{ 'Gelombang ' . $data->gelombang_penerimaan . ' ' . $data->nm_penerimaan . ' (' . $tahun . ')' }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary waves-effect"><i
                                class="material-icons">save</i><span>View Proses Penetapan</span></button>
                    </form>
                </div>
            </div>

            @if ($mode == 'show')
                <div class="card">
                    <div class="body">
                        <form id="form-validation" method="POST"
                            action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/proses-penetapan/penetapan') }}">
                            {{ csrf_field() }}
                            <div class="table-responsive">
                                <table
                                    class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                    id="primary_table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            {{-- <th>
                                                <input id="checkbox_select_all" type="checkbox" name="select_all"
                                                    class="filled-in">
                                                <label for="checkbox_select_all" style="margin-bottom: -10px;"></label>
                                            </th> --}}
                                            <th>Nomor Pendaftaran</th>
                                            <th>Nama</th>
                                            <th>No HP</th>
                                            <th>Asal Sekolah</th>
                                            {{-- <th>Pilihan 1</th> --}}
                                        </tr>
                                    </thead>
                                </table>
                                <div class="row clearfix">
                                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                        <input type="hidden" name="id_penerimaan"
                                            value="{{ $penerimaan->id_penerimaan }}"></input>
                                        <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                                class="material-icons">save</i><span>Save</span></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
</div>
@include('scriptjs')

@if ($mode == 'show')
    <script>
        var id_penerimaan = {!! json_encode($penerimaan->id_penerimaan) !!};

        var modul_url = 'peserta';
        var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'proses-penetapan/datatables/' +
            id_penerimaan;

        var primary_table = $('#primary_table').DataTable({
            processing: true,
            // serverSide: true,
            pageLength: 100,
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
                    data: 'checkbox',
                    name: 'checkbox',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, full, meta) {
                        return '<input id="checkbox-' + data.id +
                            '" type="checkbox" name="id_c_siswa[]" class="filled-in" value="' + data.id +
                            '">' +
                            '<label for="checkbox-' + data.id + '"></label>';

                    }
                },
                {
                    data: 'kode_voucher',
                    name: 'calon_siswa_baru.kode_voucher'
                },
                {
                    data: 'nm_c_siswa',
                    name: 'calon_siswa_baru.nm_c_siswa'
                },
                {
                    data: 'nomor_hp',
                    name: 'calon_siswa_baru.nomor_hp'
                },
                {
                    data: 'nm_sekolah_asal',
                    name: 'calon_siswa_sekolah.nm_sekolah_asal'
                },
                // {
                //     data: 'nm_jurusan',
                //     name: 'jurusan.nm_jurusan'
                // }
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
            $('input[name="select_all"]').change(function() {
                var select_all_checked = this.checked;
                var rows = primary_table.rows({
                    'search': 'applied'
                }).nodes();

                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });
        });
    </script>
@endif

<script>
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
            $(input).parents('.form-group').addClass('error');
        },
        unhighlight: function(input) {
            $(input).parents('.form-group').removeClass('error');
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
                    $('button').removeAttr('disabled');
                }
            });
        }
    });
</script>

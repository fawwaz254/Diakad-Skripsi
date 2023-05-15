<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#ijazah/pengambilan-ijazah') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        @if (isset($ijazah))
                            EDIT PENGAMBILAN IJAZAH
                        @else
                            TAMBAH PENGAMBILAN IJAZAH
                        @endif
                    </h2>
                </div>
                <div class="body">
                    @if (isset($ijazah))
                        <form id="form-validation" method="POST"
                            action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-pengambilan-ijazah/edit/' . $ijazah->id_ijazah) }}">
                        @else
                            <form id="form-validation" method="POST"
                                action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-pengambilan-ijazah/add/0/') }}">
                    @endif
                    {{ csrf_field() }}

                    <h2 class="card-inside-title">
                        @if (isset($ijazah))
                            Nama Siswa
                    </h2>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                            <select class="form-control show-tick" name="id_siswa" required id="id_siswa">
                                <option value="" selected disabled>-- Pilih Siswa --</option>
                                @foreach ($siswa as $s)
                                    <option value="{{ $s->id_siswa }}"
                                        @if (isset($ijazah) && $s->id_siswa == $ijazah->id_siswa) {{ 'selected' }} @endif
                                        data-nomorijazah="{{ $s->nomor_ijasah }}">
                                        {{ $s->nis_siswa . ' - ' . $s->nm_siswa . ' | ' . $s->nomor_ijasah }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            List NIS Siswa<br>
                            <small>*NIS Siswa dipisahkan dengan enter</small>
                            </h2>
                            {{-- <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <textarea rows="10" class="form-control" name="id_siswa" required></textarea> --}}
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                                        <thead>
                                            <tr>
                                                <th class="col-2">
                                                    <input id="checkbox_select_all" type="checkbox" name="select_all" class="filled-in">
                                                    <label for="checkbox_select_all" style="margin-bottom: -10px;"></label>
                                                </th>
                                                <th>NIS</th>
                                                <th>Nama Siswa</th>
                                            </tr>
                                        </thead>
                                    </table>
                                    @endif

                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Tanggal Pengambilan Ijazah
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control datetimepicker"
                                        name="tgl_pengambilan_ijazah"
                                        value="{{ isset($ijazah) ? Carbon\Carbon::createFromTimeString($ijazah->tgl_pengambilan_ijazah)->format('H:i - d F Y') : null }}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Penerima Ijazah <span><input type="checkbox" class="form-control" name="is_diwakilkan"
                                        id="is_diwakilkan" value="1"
                                        @if (isset($ijazah) && $ijazah->penerima_ijazah != null) {{ 'checked' }} @endif>
                                    <label for="is_diwakilkan">Diwakilkan?</label></span>
                            </h2>
                            <div class="row clearfix" id="penerima-ijazah"
                                style="display: @if (isset($ijazah) && $ijazah->penerima_ijazah != null) {{ 'block' }} @else {{ 'none' }} @endif;">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="text" class="form-control" name="penerima_ijazah"
                                        value="{{ $ijazah->penerima_ijazah ?? '' }}">
                                </div>
                            </div>
                            <h2 class="card-inside-title">
                                Catatan
                            </h2>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <textarea class="form-control" name="catatan_ijazah">{{ $ijazah->catatan_ijazah ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                            class="material-icons">save</i><span>Save</span></button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('scriptjs')
        <script>
            
            var modul_url       = 'ijazah';
            var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'pengambilan-ijazah/datatables-siswa';

            var primary_table = $('#primary_table').DataTable({
                processing: true,
                // serverSide: true,
                responsive: true,
                ajax: {
                    url: datatable_url,
                    type: 'GET'
                },
                columns: [
                { data: 'checkbox', name: 'checkbox', searchable: false, orderable: false,
                render: function (data, type, full, meta){
                    return '<input id="checkbox-' + data.nis_siswa + '" type="checkbox" name="nis_siswa[]" class="filled-in" value="' + data.nis_siswa + '">'+
                    '<label for="checkbox-' + data.nis_siswa + '"></label>'; 

                }
                },
                { data: 'nis_siswa', name: 'nis_siswa' },
                { data: 'nm_pengguna', name: 'pengguna.nm_pengguna' }

            ]
            });



            $('#is_diwakilkan').change(function() {
                if ($('#is_diwakilkan').is(':checked')) {
                    console.log('checked');
                    $('#penerima-ijazah').css('display', 'block');
                } else {
                    console.log('un-checked');
                    $('#penerima-ijazah').css('display', 'none');
                }
            });

            $('.datetimepicker').bootstrapMaterialDatePicker({
                format: 'HH:mm - DD MMMM YYYY',
                // lang : 'id',
                clearButton: true,
                weekStart: 1,
            });

            $(document).ready(function() {        
            /* Select All Checkbox */
            $('input[name="select_all"]').change(function() {
                var select_all_checked = this.checked;
                var rows = primary_table.rows({ 'search': 'applied' }).nodes();

                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });
    });
        </script>

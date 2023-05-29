<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/tracer-alumni') }}">
                <i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Cetak Alumni
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" class="row"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2)) }}/tracer-alumni/cetak2">
                        {{ csrf_field() }}

                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title"> Kelas </h2>
                            <div class="form-group">
                                <div class="form-line">
                                    <select class="form-control show-tick" name="id_kelas">
                                        <option value="" @if(empty($id_kelas)) selected  @endif disabled> Pilih Kelas </option>
                                        @foreach ($data_kelas as $kelas)
                                            <option value="{{ $kelas->id_kelas }}" {{ $id_kelas == $kelas->id_kelas ? 'selected' : '' }}>
                                                {{ $kelas->nm_kelas }}
                                            </option>
                                        @endforeach
                                        <option value="all" {{ $id_kelas == 'all' ? 'selected' : '' }} >Semua Kelas</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title"> Tahun Lulus </h2>
                            <input type="number" class="form-control" name="tahun_lulus" required=""
                                aria-required="true" aria-invalid="true" 
                                value="{{ !empty($tahun_lulus) ? $tahun_lulus : '' }}">
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            <button id="submit" class="btn btn-block bg-blue waves-effect" type="submit"
                                style="margin-top: 30px">
                                <i class="material-icons">search</i><span> Lihat
                                </span>
                            </button>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
                            @if (!empty($id_kelas) && !empty($tahun_lulus))
                                <a href="humas/alumni/tracer-alumni/export-alumni2/{{ $id_kelas }}/{{ $tahun_lulus }}"
                                    target="_blank" style="margin-top: 30px" class="btn bg-green waves-effect ">
                                    <i class="material-icons">local_printshop</i> Cetak</a>
                            @else
                                <a href=""
                                    target="_blank" style="margin-top: 30px; pointer-events: none;  "
                                    class="btn bg-grey waves-effect ">
                                    <i class="material-icons">local_printshop</i> Cetak</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if (!empty($id_kelas) && !empty($tahun_lulus))
            <div class="container-fluid">
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="card" style="margin-top: 30px">
                            <div class="body">
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                        id="primary_table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Kelas</th>
                                                <th>Tahun Lulus</th>
                                                <th>status</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
</div>
</div>
@include('scriptjs')
<script>
    var modul_url       = '{{Request::segment(2)}}';
    var menu_url       = '{{Request::segment(3)}}';
var id_kelas = '{{ $id_kelas }}';
var tahun_lulus = '{{ $tahun_lulus }}';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/tracer-alumni/cetak/datatables/' + id_kelas + '/' + tahun_lulus;
    // var edit_url        = role_url + '#' + modul_url + '/tracer-alumni/edit';
    // var detail_url      = role_url + '#' + modul_url + '/kategori-pertanyaan/detail';
    // var delete_url      = base_url + '/' + role_url + '/' + modul_url + '/tracer-alumni/action/delete';
// alert(datatable_url);
    var primary_table = $('#primary_table').DataTable({
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, 'All'],
        ],
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [
            { data: null, searchable: false, orderable: false },
            { data: 'nm_c_siswa' },
            { data: 'nm_kelas' },
            { data: 'tahun_lulus' },
            { data: 'status' },
        ]
    });

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>


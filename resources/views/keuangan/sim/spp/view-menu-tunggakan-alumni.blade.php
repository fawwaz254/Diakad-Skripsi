<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        TUNGGAKAN Alumni
                    </h2>
                </div>
                @include('keuangan/sim/spp/partials/header-card-menu')
            </div>
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Tahun Masuk Siswa
                            </h2>
                            <select class="form-control show-tick" name="tahun_akademik_semester">
                                @foreach ($data_semester as $semester)
                                    <option value="{{ $semester->thn_akademik_semester }}"
                                        @if ($semester->thn_akademik_semester == $tahun_akademik_semester) selected @endif>
                                        {{ $semester->tahun_ajaran }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i
                                    class="material-icons">save</i><span>Ubah Tahun Ajaran</span></button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No </th>
                                    <th>Nis</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Tahun Masuk</th>
                                    <th>Total</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    var datatable_url = base_url + '/' + role_url + '/sim/spp/tunggakanAlumni/datatables';
    var primary_table = $('#primary_table').DataTable({
        processing: true,
        // serverSide: true,
        aLengthMenu: [
            [25, 50, 100, 200, -1],
            [25, 50, 100, 200, "All"]
        ],
        iDisplayLength: -1,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'POST',
            data: function(params) {
                params.tahun_akademik_semester = $('select[name=tahun_akademik_semester]').val();
            },
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nis_siswa',
                name: 'nis_siswa',
            },
            {
                data: 'nm_pengguna',
                name: 'nm_pengguna'
            },
            {
                data: 'nm_kelas',
                name: 'nm_kelas'
            },
            {
                data: 'thn_masuk_siswa',
                name: 'thn_masuk_siswa'
            },
            {
                data: 'total_biaya',
                name: 'total_biaya'
            },
            {
                data: 'id_siswa'
            },

            // { data: 'nominal_spp_juli', searchable: false, orderable: false },
            // { data: 'nominal_spp_non_juli', searchable: false, orderable: false },
            // { data: 'tingkat' },
            // { data: 'action', searchable: false, orderable: false, 
            //     render: function(data){
            //         if(data.status == 0){
            //             return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="editAction(this)"  data-id="'+  data.id +'">'+
            //             '    <i class="material-icons">edit</i>'+
            //             '</button>';
            //         }else{
            //             return '';
            //         }
            //     }
            // },
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


    function filterAction() {
        primary_table.ajax.reload(null, false);
    }















    // function filterAction() {
    //     var tahun_akademik_semester = $('select[name=tahun_akademik_semester]').val();
    //     loadURI('sim/spp/tunggakan/' + tahun_akademik_semester);
    // }
</script>


{{-- <script>
    $(function() {
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });
</script> --}}

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        TAMPILKAN PENGELUARAN
                    </h2>
                </div>
                @include('keuangan/sim/pengeluaran/partials/header-card-menu')
            </div>
            <div class="card">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Tahun Ajaran
                            </h2>
                            <select class="form-control show-tick" name="tahun_akademik_semester">
                            @foreach($data_semester as $semester)
                                <option value="{{$semester->thn_akademik_semester}}" 
                                    @if($semester->thn_akademik_semester == $tahun_akademik_semester)
                                        selected
                                    @endif>
                                {{$semester->tahun_ajaran}}</option>
                            @endforeach
                            </select>
                        </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_awal" required="" 
                                            value="{{ $tgl_awal }}">
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" class="datepicker form-control" name="tgl_akhir" required="" 
                                            value="{{ $tgl_akhir }}">
                            </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-btn-submit waves-effect" onclick="filterAction()"><i class="material-icons">save</i><span>Cari Pengeluaran</span></button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No. </th>
                                    <th>Tanggal</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Uraian</th>
                                    <th>Dari Dana</th>
                                    <th>Pengeluaran</th>
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

$(function(){    
        $('.datepicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD',
            //lang : 'id',
            clearButton: true,
            weekStart: 1,
            time: false
        });
    });

var datatable_url   = base_url + '/' + role_url + '/sim/pengeluaran/tampilkan/datatables';
var edit_url = role_url + '#' + 'sim/pengeluaran/edit';
var delete_url = base_url + '/' + role_url + '/sim/pengeluaran/delete';
var print_url = base_url + '/' + role_url + '/sim/pengeluaran/print';

var primary_table = $('#primary_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: datatable_url,
        type: 'POST',
        data: function(params){
            params.tahun = $('select[name=tahun_akademik_semester]').val();
            params.tgl_awal = $('input[name=tgl_awal]').val();
            params.tgl_akhir = $('input[name=tgl_akhir]').val();
        },
    },
    columns: [
        { data: null, searchable: false, orderable: false },
        { data: 'tgl_realisasi', searchable: false },
        { data: 'rapb.subkategori.kode_subkategori_rapb' },
        { data: 'rapb.subkategori.nm_subkategori_rapb' },
        { data: 'nm_realisasi' },
        { data: 'sumber_dana', searchable: false, orderable: false },
        { data: 'dana_realisasi', searchable: false, orderable: false },
        { data: 'action', name: 'action', searchable: false, orderable: false,
            render: function(data){
                return '<a class="btn btn-success btn-circle waves-effect waves-float" target="_blank" href="'
                        + print_url + '/' + data.id +'">' + '<i class="material-icons">print</i>' + '</a>' 
                        + '<a class="target-link btn btn-info btn-circle waves-effect waves-float" href="'+ edit_url 
                        + '/' + data.id + '">' + '<i class="material-icons">edit</i>' + '</a> ' 
                        + '<button class="btn btn-danger btn-circle waves-effect waves-circle waves-float" onclick="deleteActionKhusus(\'' + delete_url + '\', this)" data-id="'+  data.id + '">'
                        + '<i class="material-icons">close</i>' + '</button>';
            }
        }
    ],
    order: [[1, 'dsc']],
});

primary_table.on( 'draw', function () {
    primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        var start = this.page.info().page * this.page.info().length;
        cell.innerHTML = start + i + 1;
    } );
} ).draw();

function filterAction(){
    primary_table.ajax.reload(null, false);
}

function deleteActionKhusus(delete_url, element){
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Are you sure?",
            text: "You won't be able to delete this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function (result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: delete_url + '/' + item.attr('data-id'),
                    success: function (response) {
                        if(response.status == 200){
                            vex.dialog.alert(response.message);
                        }else if(response.status == 201){
                            vex.dialog.alert(response.message);
                            window.location.href = response.link;
                        }else if(response.status == 202){
                            vex.dialog.alert(response.message);
                            loadURI(response.path);
                        }else if(response.status == 203){
                            vex.dialog.alert(response.message);
                            primary_table.ajax.reload(null, false);
                        }else if(response.status == 300){
                            vex.dialog.alert(response.message);
                        }
                    },
                    complete: function() {
                        $('button').removeAttr('disabled', 'disabled');
                    }
                });
            } else {
                $('button').removeAttr('disabled', 'disabled');
            }
        });
    }
</script>
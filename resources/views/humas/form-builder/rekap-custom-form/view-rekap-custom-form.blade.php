<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <input type="hidden" value="{{$form->id_custom_form}}" id="id_form">
            <h2>
                <a class="btn bg-blue waves-effect target-link" href="{{ url(Request::segment(1) . '#' . Request::segment(2) . '/' . Request::segment(3)) }}"><i class="material-icons">backspace</i><span>Kembali</span></a>
            </h2>
            <div class="card" style="margin-bottom: 10px;">
                <div class="header">
                    <h2>Filter Rekap Form {{$form->role->nm_role ?? 'PUBLIC' }}</h2>
                </div>
                <div class="body">
                    @if($form->id_role == 3)
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <h2 class="card-inside-title">
                                Pilih Kelas
                            </h2>
                            <select class="form-control show-tick" name="select_kelas">
                                @foreach($kelas as $k)
                                <option value="{{$k->id_kelas}}">{{$k->nm_kelas}}</option>

                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xs-12">
                            @if($form->jenis_custom_form == 'harian')
                            <h2 class="card-inside-title">
                                Pilih Tanggal
                            </h2>
                            <div class="">
                                <input type="date" class="form-control" id="select_tanggal" value="{{date('d-m-Y')}}">
                            </div>
                            @elseif($form->jenis_custom_form == 'bulanan')
                            <h2 class="card-inside-title">
                                Pilih Bulan
                            </h2>
                            <div class="">
                                <input type="month" class="form-control" id="select_tanggal" value="{{date('d-m-Y')}}">
                            </div>
                            @elseif($form->jenis_custom_form == 'biasa')
                            @if($form->id_role != 99)
                            <h2 class="card-inside-title">
                                Pilih Tanggal
                            </h2>
                            <div class="">
                                <input type="date" class="form-control" id="select_tanggal" value="{{date('d-m-Y')}}">
                            </div>
                            @endif
                            <div>
                                <p>Form Dibuka Pada <strong>{{$form->start_time}}</strong> dan Ditutup Pada <strong>{{$form->end_time}}</strong></p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                @CSRF
                <div class="header">
                    <h2>Data Form {{$form->nm_custom_form}}</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>{{isset($form->role->nm_role) ? 'Nama' : 'IP' }} {{$form->role->nm_role ?? 'PUBLIC' }}</th>
                                    @foreach($form->form_komponen as $key => $value)

                                    <th>{{ $value->label_custom_form_komponen }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal" tabindex="-1" role="dialog" id="myModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">
                <h4 class="modal-title" style="text-align: center">Detail Jawaban</h4>
            </div>
            <div id="place">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('scriptjs')
<script>
    var modul_url = '{{ Request::segment(2) }}';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'form-rekap/datatables/filter';
    var rekap_url = role_url + '#' + modul_url + '/' + 'form-rekap';
    // var komponen_url = role_url + '#' + modul_url + '/' + 'custom-form';
    // var delete_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'form-rekap/datatables';

    var primary_table = $('#primary_table').DataTable({
        processing: true,

        serverSide: true,
        fixedColumns: {
            left: 2
        },
        paging: false,
        ajax: {
            url: datatable_url,
            type: 'POST',
            data: function(d) {
                d.id = $('#id_form').val();
                d.date = $('#select_tanggal').val();
                d.id_kelas = $('select[name*=select_kelas]').val();
            }
        },
        columns: @json($tabel_kolom),
        columnDefs: [{
            targets: [0, 1],
            render: function(data) {
                return data;
            }
        }, {
            targets: '_all',
            render: function(data, meta, row, targets) {
                if (data != null) {
                    if (data.jenis != null && data.jenis == 'biasa') {
                        return `<button type='button' onclick='openModal(${targets.row}, ${targets.col})'>DETAIL</button>`
                    } else if (data.jenis != null && data.jenis == 'image') {
                        
                        return `
                        <a href="${data.data}">
                                        <img src="${data.data}" alt="" style="width:300px; height:300px">
                                        </a>
                        `
                    }else if(data.jenis != null && data.jenis == 'file'){
                        let txt = ''
                        $.each(data.data, function(index,value){
                            txt +=(`
                        <a href="${data.data}">
                        File-${index+1}
                        </a>`)
                        })

                        return txt;
                    }
                }
                return data;
            }

        }, ]
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

    $('select[name*=select_kelas]').on('change', function() {
        primary_table.ajax.reload()

    })
    $('#select_tanggal').on('change', function() {
        primary_table.ajax.reload()

    })
</script>
<script>
    function openModal(row, col) {
        let detail = primary_table.cells(row, col).data()[0]
        $('#place').html('');
        var html = '<table  class="table">';
        html += '<tr>';
        html += '<th>No</th>';
        html += '<th>Waktu</th>';
        html += '<th>Jawaban</th>';
        html += '</tr>';
        $.each(detail.data, function(key, item) {
            html += '<tr >';
            html += '<td>' + (key + 1) + '</td>';
            html += '<td>' + item.created_at + '</td>';
            html += '<td>' + item.respon + '</td>';
            html += '</tr>';
        });
        html += '</table>';
        $('#place').html(html);
        $('.modal').modal();
    }
</script>
<script>
    // $(document).ready(function() {
    //     $('.modal').modal();
    // });
    document.querySelector("#select_tanggal").valueAsDate = new Date();
</script>
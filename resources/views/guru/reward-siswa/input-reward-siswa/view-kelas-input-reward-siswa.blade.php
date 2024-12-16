<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link "
                href="{{ url(Request::segment(1) . '#reward-siswa/'.Request::segment(3)) }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{-- {{ csrf_field() }} --}}
                <div class="header">
                    <h2>KELAS {{ $data_kelas->nm_kelas }}<br>
                        SEMESTER {{ $semester_aktif->tahun_ajaran }} {{ $semester_aktif->nm_semester }}<br/>
                        AKTIVITAS {{ $data_aktivitas_reward->nm_aktivitas_reward_siswa }}
                    </h2>
                </div>
                <div class="body">
                    <h4 class="title" style="color:red;">Inputan {{$date_input}}</h4>
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/save-reward-siswa') }}">
                        @csrf
                        <input name="id_kelas" type="hidden" value="{{ $data_kelas->id_kelas }}" />
                        <input name="jenis_aktivitas_reward" type="hidden" value="{{ $data_aktivitas_reward->id_jenis_aktivitas_reward }}" />
                        <div class="table-responsive">
                            <table
                                class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                                style="width:100%;" id="primary_table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>Nama</th>
                                        <th>Mengikut Aktivitas</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"
                                style="margin-bottom: 30px; margin-left: 20">
                                <button class="btn btn-block bg-green waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    var id_kelas = {!! json_encode($data_kelas->id_kelas) !!};

    var modul_url = 'reward-siswa';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'input-reward-siswa/datatables';
    var add_url = role_url + '#' + modul_url + '/' + 'input-reward-siswa/add';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 100,
        responsive: false,
        ajax: {
            url: datatable_url,
            data: function(d) {
                d.id_kelas = id_kelas;
                d.id_aktivitas_reward_siswa = '{{$aktivitas_reward}}';
            },
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nis_siswa',
                name: 'nis_siswa'
            },
            {
                data: 'nm_pengguna',
                name: 'nm_pengguna'
            },
            {
                data: 'aktivitas_reward',
                name: 'aktivitas_reward',
                searchable: false,
                orderable: false,
                render: function(data, type, row) {
                    var html = '';
                    var i = 0;
                    for(index in data.list) {
                        var item = data.list[index];
                        html += `<span><input id="ck-${row.id_siswa}-${i}" type="checkbox" name="id_aktivitas_reward_siswa[${row.id_siswa}][${item.id_aktivitas_reward_siswa}]" checked class="filled-in" value="${item.id_aktivitas_reward_siswa}">
                                    <label for="ck-${row.id_siswa}-${i}">Ya</label></span><br/>`;
                        i++;
                    };
                    return html;
                }
            }
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

    $('#form-validation').validate({
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
                    } else if (response.status == 205) {
                        vex.dialog.alert(response.message);
                        primary_table.ajax.reload(null, false);
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

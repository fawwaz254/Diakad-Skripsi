<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Data Home Visit
                    </h2>
                    <br>
                    <h4>Kelas</h4>
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <select name="id-kelas" id="id-kelas" class="form-control" onchange="filterKelas()">
                                <option value="">-- Pilih Kelas --</option>
                                @forelse ($data_kelas as $row)
                                    <option value="{{ $row->id_kelas }}">{{ $row->nm_kelas }}</option>
                                @empty
                                    <option value="">Tidak ada data</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                </div>
                <div class="body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#belum_lengkap" data-toggle="tab" aria-expanded="true">
                                <i class="material-icons">done</i> BERKAS BELUM LENGKAP
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#sudah_lengkap" data-toggle="tab">
                                <i class="material-icons">done_all</i> BERKAS LENGKAP
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="belum_lengkap">
                            <div class="body">
                                <form id="form-validation" method="POST">
                                    @csrf
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover dataTable display"
                                            style="width:100% !important;" id="primary_table_belum_lengkap">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>
                                                        <input id="checkbox_select_all" type="checkbox"
                                                            name="select_all" class="filled-in">
                                                        <label for="checkbox_select_all"
                                                            style="margin-bottom: -10px;"></label>
                                                    </th>
                                                    <th>Nama Siswa</th>
                                                    <th>NIS</th>
                                                    <th>NISN</th>
                                                    <th>Guru Wali Kelas</th>
                                                    <th>Kelas</th>
                                                    <th>Semester</th>
                                                    <th>Nomor HP Wali Murid</th>
                                                    <th>Alamat Wali Murid</th>
                                                    <th>Rangkuman Home Visit</th>
                                                    <th>Tanggal Dibuat</th>
                                                    <th>Guru Kesiswaan</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <button id="btnSubmit" class="btn btn-block btn-success waves-effect"
                                            type="submit">
                                            {{-- onclick="if (confirm('Sure?')) return true; else return false;" --}}
                                            <i class="material-icons">check_box</i>
                                            <span>Approve</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="sudah_lengkap">
                            <div class="body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover dataTable display"
                                        style="width:100% !important;" id="primary_table_sudah_lengkap">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Siswa</th>
                                                <th>NIS</th>
                                                <th>NISN</th>
                                                <th>Guru Wali Kelas</th>
                                                <th>Kelas</th>
                                                <th>Semester</th>
                                                <th>Nomor HP Wali Murid</th>
                                                <th>Alamat Wali Murid</th>
                                                <th>Rangkuman Home Visit</th>
                                                <th>Tanggal Dibuat</th>
                                                <th>Guru Kesiswaan</th>
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
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    var modul_url = 'data-kesiswaan';
    var datatable_belum_lengkap = base_url + '/' + role_url + '/' + modul_url + '/' + 'home-visit/datatables/0';
    var datatable_sudah_lengkap = base_url + '/' + role_url + '/' + modul_url + '/' + 'home-visit/datatables/1';
    var detail_url = role_url + '#' + modul_url + '/' + 'home-visit/edit';

    // datatable jadwal UTS
    var primary_table_belum_lengkap = $('#primary_table_belum_lengkap').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable_belum_lengkap,
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'check',
                name: 'check',
                searchable: false,
                orderable: false,
                render: function(data, type, full, meta) {
                    return `<input id="checkbox-${data.id}"
                            type="checkbox"
                            name="selected_ids[]"
                            class="filled-in"
                            value="${data.id}">
                            <label for="checkbox-${data.id}"></label>`;
                }
            },
            {
                data: 'nm_siswa',
                name: 'p1.nm_pengguna'
            },
            {
                data: 'nis_siswa',
                name: 'siswa.nis_siswa'
            },
            {
                data: 'nisn_siswa',
                name: 'siswa.nisn_siswa'
            },
            {
                data: 'nm_wali_kelas',
                name: 'p2.nm_pengguna'
            },
            {
                data: 'nm_kelas',
                name: 'kelas.nm_kelas'
            },
            {
                data: 'semester',
                name: 'semester.tahun_ajaran'
            },
            {
                data: 'nomor_hp_wali_murid',
                name: 'home_visit.nomor_hp_wali_murid'
            },
            {
                data: 'alamat_wali_murid',
                name: 'home_visit.alamat_wali_murid'
            },
            {
                data: 'rangkuman_home_visit',
                name: 'home_visit.rangkuman_home_visit'
            },
            {
                data: 'tgl_home_visit',
                name: 'home_visit.created_at'
            },
            {
                data: 'nm_guru_kesiswaan',
                name: 'p3.nm_pengguna'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        detail_url + '/' + data.id + '">' +
                        '    <i class="material-icons">edit</i>' +
                        '</a>';
                }
            }
        ]
    });

    primary_table_belum_lengkap.on('draw', function() {
        primary_table_belum_lengkap.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        });
    }).draw();

    // datatable jadwal UTS
    var primary_table_sudah_lengkap = $('#primary_table_sudah_lengkap').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: datatable_sudah_lengkap,
            type: 'GET'
        },
        columns: [{
                data: null,
                searchable: false,
                orderable: false
            },
            {
                data: 'nm_siswa',
                name: 'p1.nm_pengguna'
            },
            {
                data: 'nis_siswa',
                name: 'siswa.nis_siswa'
            },
            {
                data: 'nisn_siswa',
                name: 'siswa.nisn_siswa'
            },
            {
                data: 'nm_wali_kelas',
                name: 'p2.nm_pengguna'
            },
            {
                data: 'nm_kelas',
                name: 'kelas.nm_kelas'
            },
            {
                data: 'semester',
                name: 'semester.tahun_ajaran'
            },
            {
                data: 'nomor_hp_wali_murid',
                name: 'home_visit.nomor_hp_wali_murid'
            },
            {
                data: 'alamat_wali_murid',
                name: 'home_visit.alamat_wali_murid'
            },
            {
                data: 'rangkuman_home_visit',
                name: 'home_visit.rangkuman_home_visit'
            },
            {
                data: 'tgl_home_visit',
                name: 'home_visit.created_at'
            },
            {
                data: 'nm_guru_kesiswaan',
                name: 'p3.nm_pengguna'
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        detail_url + '/' + data.id + '">' +
                        '    <i class="material-icons">edit</i>' +
                        '</a>';
                }
            }
        ]
    });

    primary_table_sudah_lengkap.on('draw', function() {
        primary_table_sudah_lengkap.column(0, {
            search: 'applied',
            order: 'applied'
        }).nodes().each(function(cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        });
    }).draw();

    function filterKelas() {
        let id_kelas = $('#id-kelas').val();

        var primary_table_belum_lengkap = $('#primary_table_belum_lengkap').DataTable();
        primary_table_belum_lengkap.settings()[0].ajax.data = function(d) {
            d.id_kelas = id_kelas;
        };
        primary_table_belum_lengkap.ajax.reload(null, false);

        var primary_table_sudah_lengkap = $('#primary_table_sudah_lengkap').DataTable();
        primary_table_sudah_lengkap.settings()[0].ajax.data = function(d) {
            d.id_kelas = id_kelas;
        };
        primary_table_sudah_lengkap.ajax.reload(null, false);
    }
</script>
{{-- Select All Checkbox --}}
<script type="text/javascript">
    $(document).ready(function() {
        $('input[id="checkbox_select_all"]').change(function() {
            var select_all_checked = this.checked;
            var rows = primary_table_belum_lengkap.rows({
                'search': 'applied'
            }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
        });
    });
</script>
{{-- form --}}
<script>
    var selectedIds = [];
    $('#form-validation')
        .submit(
            function(form) {
                event.preventDefault();
                $('input[type="checkbox"]:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (!selectedIds.length) {
                    swal({
                        title: 'Pilih satu atau lebih siswa',
                    })
                } else {
                    swal({
                        title: 'Apakah Yakin Untuk Approve Home Visit?',
                        showCancelButton: true
                    }, function(isConfirm) {
                        if (isConfirm) {
                            $.ajax({
                                url: "{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-home-visit/checkapprove') }}",
                                type: "POST",
                                data: {
                                    'selected_ids': selectedIds,
                                },
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
                                        primary_table_belum_lengkap.ajax.reload(null, false);
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
                        } else {
                            selectedIds = [];
                        }
                    });
                }
            }
        )
</script>

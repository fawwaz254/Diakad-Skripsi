<style>
    th {
        text-align: center;
    }

    table.dataTable tbody tr td:nth-child(4),
    table.dataTable tbody tr td:nth-child(5) {
        text-align: center;
    }
</style>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>List Kelas</h2>
                </div>
                <div class="container-fluid">
                    <div style="margin-top: 1.5rem">
                        <label for="tanggal_cetak">Set Tanggal Cetak KPI</label>
                        <input type="date" class="form-control" id="set_tanggal_cetak" name="set_tanggal_cetak"
                            aria-required="true" aria-invalid="true" value="{{ $tanggal_cetak }}">
                        <button class="btn btn-block btn-primary waves-effect align-items-start" type="submit"
                            onclick="setTanggalCetak()" style="margin-top: 1rem">
                            <i class="material-icons">save</i>Simpan
                        </button>
                    </div>
                </div>
                <div class="body" style="margin-top: 1rem">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kelas</th>
                                    <th>Wali Kelas</th>
                                    <th>Terisi</th>
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
<br>

@include('scriptjs')
<script>
    var modul_url = 'kpi';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'cetak-kpi/datatables';
    var detail_url = role_url + '#' + modul_url + '/cetak-kpi/detail';
    var saveTanggalCetakSemester_url = base_url + '/' + role_url + '/' + 'rapor-semester/cetak-rapor/save-tanggal-cetak-semester';

    $(document).ready(function() {
        var primary_table = $('#primary_table').DataTable({
            processing: true,
            serverSide: true,
            responsive: false,
            ajax: {
                url: datatable_url,
                type: 'GET',
            },
            columns: [{
                    data: null,
                    searchable: false,
                    orderable: false
                },

                {
                    data: 'kelas.nm_kelas',
                    name: 'kelas.nm_kelas'
                },
                {
                    data: 'guru.pengguna.nm_pengguna',
                    name: 'guru.pengguna.nm_pengguna'
                },
                {
                    data: 'terisi',
                    name: 'terisi'
                },
                {
                    data: 'action',
                    name: 'action',
                    searchable: false,
                    orderable: false,
                    render: function(data) {
                        return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                            detail_url + '/' + data.id + '">' +
                            '    <i class="material-icons">group</i>' +
                            '</a> ';
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
    });

    function setTanggalCetak() {
        let inputTanggal = $('#set_tanggal_cetak').val();

        $.ajax({
            url: saveTanggalCetakSemester_url,
            type: 'POST',
            data: {
                tanggal: inputTanggal
            },
            dataType: 'JSON',
            success: function(response) {
                console.log(response)
                if (response.code == 200) {
                    vex.dialog.alert(response.message);
                } else if (response.code == 300) {
                    vex.dialog.alert(response.message);
                } else if (response.code == 400) {
                    vex.dialog.alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                vex.dialog.alert('Terjadi kesalahan pada server: ' + error);
            }
        });
    }
</script>

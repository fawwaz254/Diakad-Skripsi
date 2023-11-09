<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>DATA MATERI AJAR</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru</th>
                                    <th>Judul Materi</th>
                                    <th>File Materi</th>
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

<script type="text/javascript">
    var modul_url = 'e-learning';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'materi-ajar/datatables';
    var edit_url = role_url + '#' + modul_url + '/' + 'materi-ajar/detail';
    // var view_url        =  role_url + '#' + modul_url + '/' + 'materi-ajar/view';

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
                orderable: false,
                className: 'align-center'
            },
            {
                data: 'mapel',
                name: 'mapel',
                className: 'align-center'
            },
            {
                data: 'guru',
                name: 'guru',
                className: 'align-center'
            },
            {
                data: 'judul_materi',
                name: 'judul_materi',
                className: 'align-center'
            },
            {
                data: 'action',
                name: 'action',
                render: function(data) {
                    console.log(data.materi_ajar_file);
                    let html = '';
                    html += '<ul>';
                    $.each(data.materi_ajar_file, function(i, value) {
                        html += `<li><p >` + value.nm_file + ` ( ` + value.type_file +
                            ` ) </p></li>`;
                    })
                    html += '</ul>';

                    return html;

                }
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                className: 'align-center',
                render: function(data) {
                    return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                        edit_url + '/' + data.id + '">' +
                        '    <i class="material-icons">remove_red_eye</i>' +
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

    //     window.addEventListener("load", function () {
    //   // do things after the DOM loads fully
    //   alert(base_url + '/' + role_url + '/' + modul_url + '/' + 'manajemen-materi-ajar/get-kelas/' + id_jurusan);
    // });

    //     var modul_url = 'e-learning';

    // $('#materi').on('click', function(e) {
    //     alert(base_url + '/' + role_url + '/' + modul_url + '/' + 'manajemen-materi-ajar/get-kelas/' + id_jurusan);
    //     console.log(e);
    //     var id_jurusan = e.target.value;
    //     // alert(base_url + '/' + role_url + '/' + modul_url + '/' + 'manajemen-materi-ajar/get-kelas/' + id_jurusan);
    //     $.get(base_url + '/' + role_url + '/' + modul_url + '/' + 'manajemen-materi-ajar/get-kelas/' + id_jurusan,
    //         function(data) {
    //             console.log(data);
    //             $('#kelas').empty();

    //             $('#kelas').append($("<option>")
    //                 .text("-- Pilih Kelas --")
    //             );
    //             $.each(data, function(index, kelas) {
    //                 $('#kelas').append($("<option>")
    //                     .attr("value", kelas.id_kelas)
    //                     .text(kelas.nm_kelas)
    //                 );
    //             })

    //             $('select').select();
    //         });
    // });
</script>

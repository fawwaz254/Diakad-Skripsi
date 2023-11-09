<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                {{ csrf_field() }}
                <div class="header">
                    <h2>Approve Dokumen Tanda Tangan Digital</h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                            id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Perihal Dokumen</th>
                                    <th>Dokumen</th>
                                    <th>Status</th>
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
@include('scriptjs')
<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var modul_url = 'manajemen-tanda-tangan';
    var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'approve-tanda-tangan-digital/datatables';
    var approve_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'approve-tanda-tangan-digital/approve';

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
                orderable: false
            },
            {
                data: 'perihal_dokumen',
                name: 'perihal_dokumen',
            },
            {
                data: 'action',
                name: 'action',
                render: function(data) {
                    return `<a href="/guru/manajemen-tanda-tangan/approve-tanda-tangan-digital/preview/${data.id}" target="_blank" rel="noopener noreferrer">Lihat Dokumen</a>`
                }
            },
            {
                data: 'is_approve',
                name: 'is_approve',
                render: function(data) {
                    // return `<a href="${data.id}" target="_blank" rel="noopener noreferrer">Lihat Dokumen</a>`
                    if (data) {
                        return `<p style="color:green">Sudah di approve</p>`
                    }
                    return `<p style="color:red">Belum di approve</p>`
                }
            },
            {
                data: 'action',
                name: 'action',
                searchable: false,
                orderable: false,
                render: function(data) {
                    if (!data.is_approve) {
                        return '<button class="btn btn-circle btn-success waves-effect waves-circle waves-float" onclick="approveDocumentAction(\'' +
                            approve_url + '\', this)" data-id="' + data.id + '">' +
                            '    <i class="material-icons">done</i>' +
                            '</button>';
                    }
                    return '';
                    // return '<a class="target-link btn btn-success btn-circle waves-effect waves-circle waves-float" href="' +
                    //     edit_url + '/' + data.id + '">' +
                    //     '    <i class="material-icons">done</i>' +
                    //     '</a> ';
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

    function approveDocumentAction(approve_url, element) {
        var item = $(element);
        $('button').attr('disabled', 'disabled');

        swal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, approve it!",
            cancelButtonText: "No, cancel!",
            closeOnConfirm: true,
            closeOnCancel: true
        }, function(result) {
            if (result) {
                $.ajax({
                    type: "POST",
                    url: approve_url + '/' + item.attr('data-id'),
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
                        } else if (response.status == 300) {
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

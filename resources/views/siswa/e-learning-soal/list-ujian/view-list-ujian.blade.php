<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        List Paket Soal
                    </h2>
                   
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table id="primary_table" class="table table-bordered table-striped table-hover dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th>Jumlah Soal</th>
                                    <th>Nilai Tiap Soal</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Akhir</th>
                                    <th>Durasi Pengerjaan</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->
</div>
@include('scriptjs')
<script>
    var modul_url       = '{{Request::segment(2)}}';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'list-ujian/table';
    var detail_url     =  role_url + '#' + modul_url + '/' + 'list-ujian/cek/';
    var delete_url     = role_url + '/' + modul_url + '/' + 'paket-soal/delete';

 
       var primary_table = $('#primary_table').DataTable({
           processing: true,
           // serverSide: true,
           ajax: {
               url: datatable_url,
               type: 'POST'
           },
           columns: [
               { data: null, searchable: false, orderable: false },
               { data: 'text', name: 'text'},
               { data: 'kelas.nm_kelas' },
               { data: 'total_question', name: 'total_question', searchable: false, orderable: false },
               { data: 'nilai'},{ data: 'waktu_mulai'},{ data: 'waktu_selesai'},{ data: 'waktu_pengerjaan'},{ data: 'status'},
               { data: 'action', searchable: false, orderable: false,
                   render: function(data) {
                       if(data.status == 1){
                        return '<a type="button" style="pointer-events: none" class="btn btn-primary btn-circle waves-effect waves-circle waves-float" href="">' +
                           '    <i class="material-icons">done</i>'+
                           '</a>';
                       }else if(data.status == 2){
                        return '<a type="button" class="btn btn-danger btn-circle waves-effect waves-circle waves-float" href="' + detail_url  + data.id +'">' +
                           '    <i class="material-icons">play_arrow</i>'+
                           '</a>';
                       }else if(data.status == 99){
                        return '<a type="button" style="pointer-events: none" class="btn btn-primary btn-circle waves-effect waves-circle waves-float" href="">' +
                           '    <i class="material-icons">access_time</i>'+
                           '</a>';
                       }
                       else{
                        return '<a type="button" class="btn btn-success btn-circle waves-effect waves-circle waves-float" href="' + detail_url  + data.id +'">' +
                           '    <i class="material-icons">play_arrow</i>'+
                           '</a>';
                       }
                   }
               }
           ],
           order: [[2, 'asc'], [1, 'asc']]
       });

       primary_table.on( 'draw', function () {
           primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
               var start = this.page.info().page * this.page.info().length;
               cell.innerHTML = i + 1;
           } );
       } ).draw();


   function actionDelete(element){
       var item = $(element);
       item.prop('disabled', true);
       var url = delete_url;
       vex.dialog.confirm({
           message: 'Are you sure to delete this item?',
           callback: function (value) {
               if(value){
                   $.ajax({
                       type: "POST",
                       url: url,
                       data:{
                           question_package_id: item.attr('data-id')
                       },
                       success: function (data) {
                           vex.dialog.alert(data.message);
                       setTimeout(() => {
                           // $('.primary_table').DataTable().ajax.reload(null, false);
                           // primary_table.ajax.reload(null, false);
                           primary_table.ajax.reload(null, false);
                           //    location.reload();
                       }, 2000);
                       
                       },
                       error: function (xhr, status, error) {
                           console.log(xhr.responseText);
                       }
                   });
               }else{
                   item.prop('disabled', false);
               }
           }
       })
   }
</script>
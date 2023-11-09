 <div class="container-fluid">
     <div class="block-header">
         <h2><a class="btn bg-blue waves-effect target-link"
                 href="{{ url(Request::segment(1) . '#perawatan-sarpras/pengadaan-sarpras') }}"><i
                     class="material-icons">backspace</i><span>Kembali</span></a></h2>
     </div>
     <div class="row clearfix">
         <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
             <div class="card">
                 {{ csrf_field() }}
                 <div class="header">
                     <h2>DETAIL SUPPLIER</h2>
                 </div>
                 <div class="body">
                     {{ csrf_field() }}
                     <div class="row clearfix">
                         <div class="col-md-12 col-sm-12 col-xs-12">
                             <h2 class="card-inside-title">
                                 INFO PENGADAAN BARANG/SARPRAS
                             </h2>
                             <hr style="border: 3px solid black;">
                         </div>
                     </div>
                     <div class="row clearfix">
                         <div class="col-md-6 col-sm-12 col-xs-12">
                             <h2 class="card-inside-title">
                                 Semester
                             </h2>
                             <div class="row clearfix">
                                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                     <input type="text" class="form-control" name="nm_mata_pelajaran" disabled=""
                                         aria-required="true" aria-invalid="true"
                                         value="{{ $data_rpb_sarpras->tahun_ajaran }} {{ $data_rpb_sarpras->nm_semester }}">
                                 </div>
                             </div>
                         </div>
                         <div class="col-md-6 col-sm-12 col-xs-12">
                             <h2 class="card-inside-title">
                                 Unit Kerja
                             </h2>
                             <div class="row clearfix">
                                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                     <input type="text" class="form-control" name="nm_mata_pelajaran" disabled=""
                                         aria-required="true" aria-invalid="true"
                                         value="{{ $data_rpb_sarpras->nm_unit_kerja }}">
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="row clearfix">
                         <div class="col-md-6 col-sm-12 col-xs-12">
                             <h2 class="card-inside-title">
                                 Jenis Buku/Alat
                             </h2>
                             <div class="row clearfix">
                                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                     <input type="text" class="form-control" name="nm_mata_pelajaran" disabled=""
                                         aria-required="true" aria-invalid="true"
                                         value="{{ $data_rpb_sarpras->nm_jenis_buku_alat }}">
                                 </div>
                             </div>
                         </div>
                         <div class="col-md-6 col-sm-12 col-xs-12">
                             <h2 class="card-inside-title">
                                 Buku/Alat
                             </h2>
                             <div class="row clearfix">
                                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                     <input type="text" class="form-control" name="nm_mata_pelajaran" disabled=""
                                         aria-required="true" aria-invalid="true"
                                         value="{{ $data_rpb_sarpras->nm_buku_alat }}">
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="row clearfix">
                         <div class="col-md-6 col-sm-12 col-xs-12">
                             <h2 class="card-inside-title">
                                 Ruangan
                             </h2>
                             <div class="row clearfix">
                                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                     <input type="text" class="form-control" name="nm_mata_pelajaran" disabled=""
                                         aria-required="true" aria-invalid="true"
                                         value="{{ $data_rpb_sarpras->nm_ruangan }}">
                                 </div>
                             </div>
                         </div>
                         <div class="col-md-6 col-sm-12 col-xs-12">
                             <h2 class="card-inside-title">
                                 Inventaris
                             </h2>
                             <div class="row clearfix">
                                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                     <input type="text" class="form-control" name="nm_mata_pelajaran" disabled=""
                                         aria-required="true" aria-invalid="true"
                                         value="{{ $data_rpb_sarpras->nm_inventaris_ruangan }}">
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="row clearfix">
                         <div class="col-md-6 col-sm-12 col-xs-12">
                             <h2 class="card-inside-title">
                                 Harga Satuan
                             </h2>
                             <div class="row clearfix">
                                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                     <input type="text" class="form-control" name="nm_mata_pelajaran" disabled=""
                                         aria-required="true" aria-invalid="true"
                                         value="Rp{{ number_format($data_rpb_sarpras->harga_satuan_rpb_sarpras, 0) }}">
                                 </div>
                             </div>
                         </div>
                         <div class="col-md-6 col-sm-12 col-xs-12">
                             <h2 class="card-inside-title">
                                 Qty
                             </h2>
                             <div class="row clearfix">
                                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                     <input type="text" class="form-control" name="nm_mata_pelajaran"
                                         disabled="" aria-required="true" aria-invalid="true"
                                         value="{{ $data_rpb_sarpras->qty_rpb_sarpras }}">
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="row clearfix">
                         <div class="col-md-6 col-sm-12 col-xs-12">
                             <h2 class="card-inside-title">
                                 Tanggal Pengadaan
                             </h2>
                             <div class="row clearfix">
                                 <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                     <input type="text" class="form-control" name="nm_mata_pelajaran"
                                         disabled="" aria-required="true" aria-invalid="true"
                                         value="{{ strftime('%A, %d %B %Y', strtotime($data_rpb_sarpras->tgl_rpb_sarpras)) }}">
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="row clearfix">
                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                             <hr style="border: 3px solid black;">
                         </div>
                     </div>
                     <div class="block-header">
                         <h2>
                             <a class="btn bg-blue waves-effect target-link"
                                 href="{{ url(Request::segment(1) . '#perawatan-sarpras/pengadaan-sarpras/add-supplier/' . $data_rpb_sarpras->id_rpb_sarpras) }}"><i
                                     class="material-icons">note_add</i><span>TAMBAH PENGADAAN SUPPLIER</span></a>
                         </h2>
                     </div>
                     <div class="table-responsive">
                         <table
                             class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                             id="primary_table">
                             <thead>
                                 <tr>
                                     <th>No. </th>
                                     <th>Supplier</th>
                                     <th>Harga Supplier</th>
                                     <th>Harga Penawaran</th>
                                     <th>Qty Penawaran</th>
                                     <th>Termin Penawaran</th>
                                     <th>Harga Apv Supplier</th>
                                     <th>Qty Apv Supplier</th>
                                     <th>Termin Apv Supplier</th>
                                     <th>Edit Apv Supplier</th>
                                     <th>Apv Supplier</th>
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
     var id_rpb_sarpras = {!! json_encode($data_rpb_sarpras->id_rpb_sarpras) !!};

     var modul_url = 'perawatan-sarpras';
     var datatable_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'pengadaan-sarpras/datatables/' +
         id_rpb_sarpras;
     var apv_supplier_url = base_url + '/' + role_url + '/' + modul_url + '/' + 'action-apv-pengadaan/approve-supplier';
     var apv_edit_supplier_url = role_url + '#' + modul_url + '/' + 'pengadaan-sarpras/edit-apv-supplier/' +
         id_rpb_sarpras;

     var primary_table = $('#primary_table').DataTable({
         processing: true,
         // serverSide: true,
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
                 data: 'supplier',
                 name: 'supplier'
             },
             {
                 data: 'harga_supplier',
                 name: 'harga_supplier'
             },
             {
                 data: 'harga_penawaran',
                 name: 'harga_penawaran'
             },
             {
                 data: 'qty_penawaran',
                 name: 'rpb_sarpras_supplier.qty_penawaran'
             },
             {
                 data: 'termin_penawaran',
                 name: 'rpb_sarpras_supplier.termin_penawaran'
             },
             {
                 data: 'harga_approve_supplier',
                 name: 'harga_approve_supplier'
             },
             {
                 data: 'qty_approve_supplier',
                 name: 'rpb_sarpras_supplier.qty_approve_supplier'
             },
             {
                 data: 'termin_approve_supplier',
                 name: 'rpb_sarpras_supplier.termin_approve_supplier'
             },
             {
                 data: 'edit_apv_supplier',
                 name: 'edit_apv_supplier',
                 searchable: false,
                 orderable: false,
                 render: function(data) {
                     return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                         apv_edit_supplier_url + '/' + data.id + '">' +
                         '    <i class="material-icons">edit</i>' +
                         '</a> ';
                 }
             },
             {
                 data: 'nm_approve',
                 name: 'nm_approve',
                 searchable: false,
                 orderable: false,
                 render: function(data) {
                     if (data.is_approve == 0) {
                         return '<button class="btn btn-info btn-circle waves-effect waves-circle waves-float" onclick="cekSupplierAction(\'' +
                             apv_supplier_url + '\', this)" data-idrpbsupplier="' + data
                             .id_rpb_sarpras_supplier + '">' +
                             '    <i class="material-icons">verified_user</i>' +
                             '</button>';
                     } else {
                         return '<a>' + data.nm_approve + ' (' + data.tgl_approve + ')</a>';
                     }
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


     function cekSupplierAction(apv_supplier_url, element) {
         var item = $(element);
         $('button').attr('disabled', 'disabled');

         swal({
             title: "Apakah Anda Yakin?",
             text: "Aksi Ini Akan Otomatis Melakukan Approve Supplier Dan Menghapus Approval Supplier Lain!",
             type: "warning",
             showCancelButton: true,
             confirmButtonColor: "#DD6B55",
             confirmButtonText: "Ya, Saya Yakin!",
             cancelButtonText: "Tidak, Batalkan!",
             closeOnConfirm: true,
             closeOnCancel: true
         }, function(result) {
             if (result) {
                 $.ajax({
                     type: "POST",
                     url: apv_supplier_url + '/' + item.attr('data-idrpbsupplier') + '/1',
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

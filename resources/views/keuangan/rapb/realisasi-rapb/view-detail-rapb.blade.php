 <div class="container-fluid">
     <div class="row clearfix">
         <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
             <div class="card">
                 {{ csrf_field() }}
                 <div class="header">
                     <h2>REALISASI RAPB</h2>
                 </div>
                 <div class="body">
                     <form id="form-validation" method="POST"
                         action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/post-view-rapb') }}">
                         {{ csrf_field() }}
                         <div class="row clearfix">
                             <div class="col-md-6 col-sm-12 col-xs-12">
                                 <h2 class="card-inside-title">
                                     Semester Mulai
                                 </h2>
                                 <select class="form-control show-tick" name="id_semester_mulai">
                                     <option value="" disabled selected>-- Pilih Semester Mulai --</option>
                                     @foreach ($data_semester as $data)
                                         @if ($data->id_semester == $id_semester_mulai)
                                             @if ($data->is_aktif_semester == 1)
                                                 <option value="{{ $data->id_semester }}" selected>
                                                     {{ $data->tahun_ajaran }} {{ $data->nm_semester }} (Aktif)</option>
                                             @else
                                                 <option value="{{ $data->id_semester }}" selected>
                                                     {{ $data->tahun_ajaran }} {{ $data->nm_semester }}</option>
                                             @endif
                                         @else
                                             @if ($data->is_aktif_semester == 1)
                                                 <option value="{{ $data->id_semester }}">{{ $data->tahun_ajaran }}
                                                     {{ $data->nm_semester }} (Aktif)</option>
                                             @else
                                                 <option value="{{ $data->id_semester }}">{{ $data->tahun_ajaran }}
                                                     {{ $data->nm_semester }}</option>
                                             @endif
                                         @endif
                                     @endforeach
                                 </select>
                             </div>
                             <div class="col-md-6 col-sm-12 col-xs-12">
                                 <h2 class="card-inside-title">
                                     Semester Selesai
                                 </h2>
                                 <select class="form-control show-tick" name="id_semester_selesai">
                                     <option value="" disabled selected>-- Pilih Semester Selesai --</option>
                                     @foreach ($data_semester as $data)
                                         @if ($data->id_semester == $id_semester_selesai)
                                             @if ($data->is_aktif_semester == 1)
                                                 <option value="{{ $data->id_semester }}" selected>
                                                     {{ $data->tahun_ajaran }} {{ $data->nm_semester }} (Aktif)
                                                 </option>
                                             @else
                                                 <option value="{{ $data->id_semester }}" selected>
                                                     {{ $data->tahun_ajaran }} {{ $data->nm_semester }}</option>
                                             @endif
                                         @else
                                             @if ($data->is_aktif_semester == 1)
                                                 <option value="{{ $data->id_semester }}">{{ $data->tahun_ajaran }}
                                                     {{ $data->nm_semester }} (Aktif)</option>
                                             @else
                                                 <option value="{{ $data->id_semester }}">{{ $data->tahun_ajaran }}
                                                     {{ $data->nm_semester }}</option>
                                             @endif
                                         @endif
                                     @endforeach
                                 </select>
                             </div>
                         </div>
                         <div class="row clearfix">
                             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                             </div>
                         </div>
                         <div class="row clearfix">
                             <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                 <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                         class="material-icons">save</i><span>Tampilkan</span></button>
                             </div>
                         </div>
                     </form>
                     <div class="row clearfix">
                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                         </div>
                     </div>
                     <ul class="nav nav-tabs" role="tablist">
                         <li role="prioritas" class="active">
                             <a href="#tinggi" data-toggle="tab" aria-expanded="true">
                                 <i class="material-icons">priority_high</i> TINGGI
                             </a>
                         </li>
                         <li role="prioritas">
                             <a href="#sedang" data-toggle="tab">
                                 <i class="material-icons">vertical_align_center</i> SEDANG
                             </a>
                         </li>
                         <li role="prioritas">
                             <a href="#rendah" data-toggle="tab">
                                 <i class="material-icons">low_priority</i> RENDAH
                             </a>
                         </li>
                     </ul>
                     <div class="tab-content">
                         <div role="tabpanel" class="tab-pane fade active in" id="tinggi">
                             <div class="body">
                                 <div class="table-responsive">
                                     <table class="table table-bordered table-striped table-hover dataTable display"
                                         id="primary_table_tinggi">
                                         <thead>
                                             <tr>
                                                 <th>No. </th>
                                                 <th>Mulai - Selesai</th>
                                                 <th>Jenis Kategori</th>
                                                 <th>Kode Sub-Kategori</th>
                                                 <th>Nama Sub-Kategori</th>
                                                 <th>Unit Kerja</th>
                                                 <th>Target Perkiraan</th>
                                                 <th>Realisasi</th>
                                                 <th>Belum Tercapai</th>
                                                 <th>Tanggal</th>
                                                 <th>Kepala Unit</th>
                                                 <th>Kepala Keuangan</th>
                                                 <th>Detail</th>
                                                 <th>Action</th>
                                             </tr>
                                         </thead>
                                     </table>
                                 </div>
                             </div>
                         </div>
                         <div role="tabpanel" class="tab-pane fade" id="sedang">
                             <div class="body">
                                 <div class="table-responsive">
                                     <table class="table table-bordered table-striped table-hover dataTable display"
                                         id="primary_table_sedang">
                                         <thead>
                                             <tr>
                                                 <th>No. </th>
                                                 <th>Mulai - Selesai</th>
                                                 <th>Jenis Kategori</th>
                                                 <th>Kode Sub-Kategori</th>
                                                 <th>Nama Sub-Kategori</th>
                                                 <th>Unit Kerja</th>
                                                 <th>Target Perkiraan</th>
                                                 <th>Realisasi</th>
                                                 <th>Belum Tercapai</th>
                                                 <th>Tanggal</th>
                                                 <th>Kepala Unit</th>
                                                 <th>Kepala Keuangan</th>
                                                 <th>Detail</th>
                                                 <th>Action</th>
                                             </tr>
                                         </thead>
                                     </table>
                                 </div>
                             </div>
                         </div>
                         <div role="tabpanel" class="tab-pane fade" id="rendah">
                             <div class="body">
                                 <div class="table-responsive">
                                     <table class="table table-bordered table-striped table-hover dataTable display"
                                         id="primary_table_rendah">
                                         <thead>
                                             <tr>
                                                 <th>No. </th>
                                                 <th>Mulai - Selesai</th>
                                                 <th>Jenis Kategori</th>
                                                 <th>Kode Sub-Kategori</th>
                                                 <th>Nama Sub-Kategori</th>
                                                 <th>Unit Kerja</th>
                                                 <th>Target Perkiraan</th>
                                                 <th>Realisasi</th>
                                                 <th>Belum Tercapai</th>
                                                 <th>Tanggal</th>
                                                 <th>Kepala Unit</th>
                                                 <th>Kepala Keuangan</th>
                                                 <th>Detail</th>
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
     // var modul_url = location.hash.replace('#','').split('/')[0];

     var id_semester_mulai = {!! json_encode($id_semester_mulai) !!};
     var id_semester_selesai = {!! json_encode($id_semester_selesai) !!};

     var modul_url = 'rapb';
     var datatable_url_tinggi = base_url + '/' + role_url + '/' + modul_url + '/' +
         'realisasi-rapb/datatables/rapb-tinggi/' + id_semester_mulai + '/' + id_semester_selesai;
     var datatable_url_sedang = base_url + '/' + role_url + '/' + modul_url + '/' +
         'realisasi-rapb/datatables/rapb-sedang/' + id_semester_mulai + '/' + id_semester_selesai;
     var datatable_url_rendah = base_url + '/' + role_url + '/' + modul_url + '/' +
         'realisasi-rapb/datatables/rapb-rendah/' + id_semester_mulai + '/' + id_semester_selesai;
     var add_url = role_url + '#' + modul_url + '/' + 'realisasi-rapb/add/' + id_semester_mulai + '/' +
         id_semester_selesai;
     var sarpras_url = role_url + '#' + modul_url + '/' + 'realisasi-rapb/view-detail-realisasi-sarpras/' +
         id_semester_mulai + '/' + id_semester_selesai;
     var view_detail_url = role_url + '#' + modul_url + '/' + 'realisasi-rapb/view-detail-realisasi/' +
         id_semester_mulai + '/' + id_semester_selesai;

     // TINGGI
     var primary_table_tinggi = $('#primary_table_tinggi').DataTable({
         processing: true,
         serverSide: true,
         ajax: {
             url: datatable_url_tinggi,
             type: 'GET'
         },
         columns: [{
                 data: null,
                 searchable: false,
                 orderable: false
             },
             {
                 data: 'semester',
                 name: 'semester'
             },
             {
                 data: 'tipe_kategori_rapb',
                 name: 'tipe_kategori_rapb'
             },
             {
                 data: 'kode_subkategori_rapb',
                 name: 'subkategori_rapb.kode_subkategori_rapb'
             },
             {
                 data: 'nm_subkategori_rapb',
                 name: 'subkategori_rapb.nm_subkategori_rapb'
             },
             {
                 data: 'nm_unit_kerja',
                 name: 'unit_kerja.nm_unit_kerja'
             },
             {
                 data: 'dana_perkiraan_rapb',
                 name: 'dana_perkiraan_rapb'
             },
             {
                 data: 'jml_realisasi',
                 name: 'jml_realisasi'
             },
             {
                 data: 'sisa_realisasi',
                 name: 'sisa_realisasi'
             },
             {
                 data: 'tgl_rapb',
                 name: 'tgl_rapb'
             },
             {
                 data: 'nm_kepala_unit',
                 name: 'nm_kepala_unit',
                 searchable: false,
                 orderable: false,
                 render: function(data) {
                     return '<a>' + data.nm_kepala_unit + '</a>';
                 }
             },
             {
                 data: 'nm_kepala_keuangan',
                 name: 'nm_kepala_keuangan',
                 searchable: false,
                 orderable: false,
                 render: function(data) {
                     return '<a>' + data.nm_kepala_keuangan + '</a>';
                 }
             },
             {
                 data: 'detail',
                 name: 'detail',
                 searchable: false,
                 orderable: false,
                 render: function(data) {
                     return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                         view_detail_url + '/' + data.id + '">' +
                         '    <i class="material-icons">visibility</i>' +
                         '</a> ';
                 }
             },
             {
                 data: 'action',
                 name: 'action',
                 searchable: false,
                 orderable: false,
                 render: function(data) {
                     return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                         add_url + '/' + data.id + '">' +
                         '    <i class="material-icons">post_add</i>' +
                         '</a> &nbsp;' +
                         '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                         sarpras_url + '/' + data.id + '">' +
                         '    <i class="material-icons">build</i>' +
                         '</a> ';
                 }
             }
         ]
     });

     primary_table_tinggi.on('draw', function() {
         primary_table_tinggi.column(0, {
             search: 'applied',
             order: 'applied'
         }).nodes().each(function(cell, i) {
             var start = this.page.info().page * this.page.info().length;
             cell.innerHTML = start + i + 1;
         });
     }).draw();

     // SEDANG
     var primary_table_sedang = $('#primary_table_sedang').DataTable({
         processing: true,
         serverSide: true,
         ajax: {
             url: datatable_url_sedang,
             type: 'GET'
         },
         columns: [{
                 data: null,
                 searchable: false,
                 orderable: false
             },
             {
                 data: 'semester',
                 name: 'semester'
             },
             {
                 data: 'tipe_kategori_rapb',
                 name: 'tipe_kategori_rapb'
             },
             {
                 data: 'kode_subkategori_rapb',
                 name: 'subkategori_rapb.kode_subkategori_rapb'
             },
             {
                 data: 'nm_subkategori_rapb',
                 name: 'subkategori_rapb.nm_subkategori_rapb'
             },
             {
                 data: 'nm_unit_kerja',
                 name: 'unit_kerja.nm_unit_kerja'
             },
             {
                 data: 'dana_perkiraan_rapb',
                 name: 'dana_perkiraan_rapb'
             },
             {
                 data: 'jml_realisasi',
                 name: 'jml_realisasi'
             },
             {
                 data: 'sisa_realisasi',
                 name: 'sisa_realisasi'
             },
             {
                 data: 'tgl_rapb',
                 name: 'tgl_rapb'
             },
             {
                 data: 'nm_kepala_unit',
                 name: 'nm_kepala_unit',
                 searchable: false,
                 orderable: false,
                 render: function(data) {
                     return '<a>' + data.nm_kepala_unit + '</a>';
                 }
             },
             {
                 data: 'nm_kepala_keuangan',
                 name: 'nm_kepala_keuangan',
                 searchable: false,
                 orderable: false,
                 render: function(data) {
                     return '<a>' + data.nm_kepala_keuangan + '</a>';
                 }
             },
             {
                 data: 'detail',
                 name: 'detail',
                 searchable: false,
                 orderable: false,
                 render: function(data) {
                     return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                         view_detail_url + '/' + data.id + '">' +
                         '    <i class="material-icons">visibility</i>' +
                         '</a> ';
                 }
             },
             {
                 data: 'action',
                 name: 'action',
                 searchable: false,
                 orderable: false,
                 render: function(data) {
                     return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                         add_url + '/' + data.id + '">' +
                         '    <i class="material-icons">post_add</i>' +
                         '</a> ' +
                         '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                         sarpras_url + '/' + data.id + '">' +
                         '    <i class="material-icons">build</i>' +
                         '</a> ';
                 }
             }
         ]
     });

     primary_table_sedang.on('draw', function() {
         primary_table_sedang.column(0, {
             search: 'applied',
             order: 'applied'
         }).nodes().each(function(cell, i) {
             var start = this.page.info().page * this.page.info().length;
             cell.innerHTML = start + i + 1;
         });
     }).draw();

     // RENDAH
     var primary_table_rendah = $('#primary_table_rendah').DataTable({
         processing: true,
         serverSide: true,
         responsive: false,
         ajax: {
             url: datatable_url_rendah,
             type: 'GET'
         },
         columns: [{
                 data: null,
                 searchable: false,
                 orderable: false
             },
             {
                 data: 'semester',
                 name: 'semester'
             },
             {
                 data: 'tipe_kategori_rapb',
                 name: 'tipe_kategori_rapb'
             },
             {
                 data: 'kode_subkategori_rapb',
                 name: 'subkategori_rapb.kode_subkategori_rapb'
             },
             {
                 data: 'nm_subkategori_rapb',
                 name: 'subkategori_rapb.nm_subkategori_rapb'
             },
             {
                 data: 'nm_unit_kerja',
                 name: 'unit_kerja.nm_unit_kerja'
             },
             {
                 data: 'dana_perkiraan_rapb',
                 name: 'dana_perkiraan_rapb'
             },
             {
                 data: 'jml_realisasi',
                 name: 'jml_realisasi'
             },
             {
                 data: 'sisa_realisasi',
                 name: 'sisa_realisasi'
             },
             {
                 data: 'tgl_rapb',
                 name: 'tgl_rapb'
             },
             {
                 data: 'nm_kepala_unit',
                 name: 'nm_kepala_unit',
                 searchable: false,
                 orderable: false,
                 render: function(data) {
                     return '<a>' + data.nm_kepala_unit + '</a>';
                 }
             },
             {
                 data: 'nm_kepala_keuangan',
                 name: 'nm_kepala_keuangan',
                 searchable: false,
                 orderable: false,
                 render: function(data) {
                     return '<a>' + data.nm_kepala_keuangan + '</a>';
                 }
             },
             {
                 data: 'detail',
                 name: 'detail',
                 searchable: false,
                 orderable: false,
                 render: function(data) {
                     return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                         view_detail_url + '/' + data.id + '">' +
                         '    <i class="material-icons">visibility</i>' +
                         '</a> ';
                 }
             },
             {
                 data: 'action',
                 name: 'action',
                 searchable: false,
                 orderable: false,
                 render: function(data) {
                     return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                         add_url + '/' + data.id + '">' +
                         '    <i class="material-icons">post_add</i>' +
                         '</a> ' +
                         '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="' +
                         sarpras_url + '/' + data.id + '">' +
                         '    <i class="material-icons">build</i>' +
                         '</a> ';
                 }
             }
         ]
     });

     primary_table_rendah.on('draw', function() {
         primary_table_rendah.column(0, {
             search: 'applied',
             order: 'applied'
         }).nodes().each(function(cell, i) {
             var start = this.page.info().page * this.page.info().length;
             cell.innerHTML = start + i + 1;
         });
     }).draw();
 </script>

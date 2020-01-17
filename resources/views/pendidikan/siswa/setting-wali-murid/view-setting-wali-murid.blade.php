<div class="container-fluid">
	<div class="block-header">
		<h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#siswa/setting-wali-murid')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
	</div>
    <div class="block-header">
		<h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#siswa/setting-wali-murid/upload-setting-wali-murid/'.$id_kelas)}}"><i class="material-icons">cloud_upload</i><span>Upload Wali Murid</span></a></h2>
	</div>
	<div class="row clearfix">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="card">
				{{csrf_field()}}
				<div class="header">
					<h2>DATA WALI MURID KELAS {{$kelas->nm_kelas}}</h2>
				</div>
				<div class="body">
					<form id="form-validation1" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/post-view-setting-wali-murid')}}">
						{{csrf_field()}}
						<h2 class="card-inside-title">
							Kelas
						</h2>
						<div class="row clearfix">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kelas">
								@foreach($data_kelas as $data)
                                    @if($data->id_kelas == $id_kelas)
                                        <option value="{{$data->id_kelas}}" selected="">{{$data->nm_kelas}}</option>
                                    @else
                                        <option value="{{$data->id_kelas}}">{{$data->nm_kelas}}</option>
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
                         <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                  <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                     <thead>
                        <tr>
                           <th>No. </th>
                           <th>NIS</th>
                           <th>NISN</th>
                           <th>Nama Siswa</th>
                           <th>Nama Wali Murid</th>
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

<script>
    // var modul_url = location.hash.replace('#','').split('/')[0];
    var id_kelas = {!! json_encode($kelas->id_kelas) !!};

    var modul_url       = 'siswa';
    var datatable_url   = base_url + '/' + role_url + '/' + modul_url + '/' + 'setting-wali-murid/datatables/' + id_kelas;
    var edit_url        = role_url + '#' + modul_url + '/' + 'setting-wali-murid/edit';

    var primary_table = $('#primary_table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: datatable_url,
            type: 'GET'
        },
        columns: [
        { data: null, searchable: false, orderable: false },
        { data: 'nis_siswa', name: 'nis_siswa' },
        { data: 'nisn_siswa', name: 'nisn_siswa'},
        { data: 'nm_siswa', name: 'nm_pengguna' },
        { data: 'nm_wali_murid', name: 'nm_wali_murid'},
        { data: 'action', name: 'action', searchable: false, orderable: false,
        render: function(data){
            return '<a class="target-link btn btn-info btn-circle waves-effect waves-circle waves-float" href="'+ edit_url + '/'  + data.id +'">'+
            '    <i class="material-icons">edit</i>'+
            '</a>';
        }
    }

    ]
});

    primary_table.on( 'draw', function () {
        primary_table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            var start = this.page.info().page * this.page.info().length;
            cell.innerHTML = start + i + 1;
        } );
    } ).draw();
</script>
<script>    
    $('#form-validation1').validate({
        highlight: function (input) {
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        },
        submitHandler: function(form) {
            $('button').attr('disabled', 'disabled');
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
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
                    }else if(response.status == 204){
                        loadURI(response.path);
                    }else if(response.status == 300){
                        vex.dialog.alert(response.message);
                    }
                },
                complete: function() {
                    $('button').removeAttr('disabled', 'disabled');
                }
            });
        }
    });
</script>
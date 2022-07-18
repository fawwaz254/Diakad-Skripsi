<style>
	.status-header {
		margin: 0 1.5rem;
		font-size: 18px;
		font-weight: normal;
		color: #111;
	}
</style>

<div class="container-fluid">
	<div class="block-header">
		<h2><a class="btn bg-blue waves-effect target-link"
				href="{{url(Request::segment(1).'#'.Request::segment(2).'/'.Request::segment(3))}}">
				<i class="material-icons">backspace</i><span>Kembali</span></a></h2>
	</div>
	<div class="row clearfix">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="card">
				<div class="header">
					<h2>
						{{!empty($jenisKerjasama)? 'EDIT' : 'TAMBAH'}} INSTANSI
					</h2>
				</div>
				<div class="body">
					<form id="form-validation" method="POST" class="row"
						action="{{url(Request::segment(1).'/'.Request::segment(2) . '/' . Request::segment(3) )}}/{{!empty($jenisKerjasama)? 'update/'.$jenisKerjasama->id_jenis_kerjasama  : 'store'}}">
						{{csrf_field()}}
						<input type="hidden" name="id_instansi"
							value="{{ !empty($jenisKerjasama) ? $jenisKerjasama->id_jenisKerjasama : ''}}">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<h2 class="card-inside-title"> Jenis Kerjasama </h2>
							<input type="text" class="form-control" name="nm_jenis_kerjasama" aria-required="true"
								aria-invalid="true"
								value="{{(!empty($jenisKerjasama))? $jenisKerjasama->nm_jenis_kerjasama : ''}}">
						</div>
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<button id="submit" class="btn btn-block bg-red waves-effect" type="submit">
								<i class="material-icons">save</i><span>
									{{!empty($jenisKerjasama)? 'Update' : 'Save'}} </span>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@include('scriptjs')
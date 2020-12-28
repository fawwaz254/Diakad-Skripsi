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
						{{!empty($instansi)? 'EDIT' : 'TAMBAH'}} INSTANSI
					</h2>
				</div>
				<div class="body">
					<form id="form-validation" method="POST" class="row"
						action="{{url(Request::segment(1).'/'.Request::segment(2) . '/' . Request::segment(3) )}}/{{!empty($instansi)? 'update/'.$instansi->id_instansi  : 'store'}}">
						{{csrf_field()}}
						<input type="hidden" name="id_instansi"
							value="{{ !empty($instansi) ? $instansi->id_instansi : ''}}">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<h2 class="card-inside-title"> Nama Instansi </h2>
							<input type="text" class="form-control" name="nm_instansi" aria-required="true"
								aria-invalid="true" value="{{(!empty($instansi))? $instansi->nm_instansi : ''}}">
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<h2 class="card-inside-title"> Bidang Usaha </h2>
							<input type="text" class="form-control" name="bidang_usaha" required="" aria-required="true"
								aria-invalid="true" value="{{(!empty($instansi))? $instansi->bidang_usaha : ''}}">
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<h2 class="card-inside-title"> Kontak </h2>
							<input type="text" class="form-control" name="kontak" required="" aria-required="true"
								aria-invalid="true" value="{{(!empty($instansi))? $instansi->kontak : ''}}">
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<h2 class="card-inside-title"> website </h2>
							<input type="text" class="form-control" name="website" aria-required="true"
								aria-invalid="true" value="{{(!empty($instansi))? $instansi->website : ''}}">
						</div>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<h2 class="card-inside-title"> Alamat </h2>
							<textarea class="form-control" name="alamat" required="" aria-required="true"
								aria-invalid="true"> {{(!empty($instansi))? $instansi->alamat : ''}} </textarea>
						</div>

						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<button id="submit" class="btn btn-block bg-red waves-effect" type="submit">
								<i class="material-icons">save</i><span> {{!empty($instansi)? 'Update' : 'Save'}}
								</span>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
  @include('scriptjs')
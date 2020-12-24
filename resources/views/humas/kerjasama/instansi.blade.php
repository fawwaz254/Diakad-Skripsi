<div class="container-fluid">
  <div class="block-header">
      <h2><a class="btn bg-blue waves-effect target-link"
              href="{{url(Request::segment(1).'#'.Request::segment(2).'/'.Request::segment(3).'/add')}}">
              <i class="material-icons">note_add</i><span>Tambah Instansi</span></a></h2>
  </div>
  <div class="row clearfix">
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
          <div class="card">
              <div class="header">
                  <h2>DATA INSTANSI</h2>
              </div>
              <div class="body">
                  <div class="table-responsive">
                      <table
                          class="table table-bordered table-striped table-hover dataTable display responsive nowrap"
                          id="primary_table">
                          <thead>
                            <tr>
                              <th>No</th>
                              <th>Nama Instansi</th>
                              <th>Bidang Usaha</th>
                              <th>Alamat</th>
                              <th>Kontak</th>
                              <th>Website</th>
                            </tr>
                          </thead>
                      </table>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
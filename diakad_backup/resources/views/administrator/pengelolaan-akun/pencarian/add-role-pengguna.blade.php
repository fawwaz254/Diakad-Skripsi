<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link " href="{{url(Request::segment(1).'#pengelolaan-akun/pencarian/view-detail-pengguna/'.$id_pengguna.'/'.$username_nama_cari)}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH ROLE USERNAME : {{$pengguna->username}}
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                NAMA PENGGUNA : {{strtoupper($pengguna->nm_pengguna)}}
                            </div>
                        </div>
                        <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-pencarian/add-role-pengguna/1')}}">
                            <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap">
                                <thead>
                                    <tr>
                                        <th>Pilih</th>
                                        <th>Nama Role</th>
                                        <th>Deskripsi</th>
                                        <th>Path</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($role_set as $data)
                                        <tr>
                                            <td class="center">
                                                <input id="checkbox-{{$data->id_role}}" type="checkbox" name="id_role[]" class="filled-in" value="{{$data->id_role}}">
                                                <label for="checkbox-{{$data->id_role}}"></label>
                                            </td>
                                            <td><strong>{{$data->nm_role}}</strong></td>
                                            <td>{{$data->deskripsi_role}}</td>
                                            <td>{{$data->path}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="5">
                                            <input type="hidden" class="form-control" name="id_pengguna" required="" aria-required="true" aria-invalid="true" value="{{$id_pengguna}}">
                                            <input type="hidden" class="form-control" name="username_nama_cari" required="" aria-required="true" aria-invalid="true" value="{{$username_nama_cari}}">
                                            <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<div class="container-fluid">
    <div class="block-header">
        <!-- <h2>PROFILE</h2> -->
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/profile')}}">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>
                            EDIT PROFILE 
                        </h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Nama pengguna</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="name" required="" aria-required="true" aria-invalid="true" value="{{$auth_data->pengguna->nm_pengguna}}">
                                    </div>
                                </div>
                                <label>Aktif role</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        @php
                                            $roles_pengguna = new Illuminate\Support\Collection;
                                        @endphp
                                            @foreach($auth_data->roles_pengguna as $role_pengguna)
                                            @php
                                                $roles_pengguna->push((object)[
                                                    'is_aktif' => $role_pengguna->is_aktif,
                                                    'id_role' => $role_pengguna->id_role,
                                                    'nm_role' => $roles->where('id_role', $role_pengguna->id_role)->first()->nm_role
                                                ]);
                                            @endphp
                                            @endforeach
                                        <select class="form-control show-tick" name="role">
                                            @foreach($roles_pengguna->sortBy('nm_role') as $role_pengguna)
                                            @if($role_pengguna->is_aktif == 1)
                                            <option value="{{$role_pengguna->id_role}}" selected>{{$role_pengguna->nm_role}}</option>
                                            @else
                                            <option value="{{$role_pengguna->id_role}}">{{$role_pengguna->nm_role}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-btn-submit waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
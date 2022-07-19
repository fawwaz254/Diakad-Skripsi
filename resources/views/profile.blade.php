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
                            {{$auth_data->pengguna->status_join_table == 3? 'MY PROFILE' : 'EDIT PROFILE'}} 
                        </h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Nama pengguna</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" class="form-control" name="name" required="" {{$auth_data->pengguna->status_join_table == 3? 'disabled' : ''}} aria-required="true" aria-invalid="true" value="{{$auth_data->pengguna->nm_pengguna}}">
                                    </div>
                                </div>
                                <label>Aktif role</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="role">
                                        @php
                                            $no = 1;
                                        @endphp
                                            @foreach($roles as $role_pengguna)
                                            @if($role_pengguna->is_aktif == 1)
                                            <option value="{{$role_pengguna->id_role}}" selected>{{$no++}}. {{$role_pengguna->nm_role}}</option>
                                            @else
                                            <option value="{{$role_pengguna->id_role}}">{{$no++}}. {{$role_pengguna->nm_role}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @if ($auth_data->pengguna->status_join_table == 3)
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-btn-submit waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                            </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<script>
    $('select:not(.ms)').selectpicker();
</script>
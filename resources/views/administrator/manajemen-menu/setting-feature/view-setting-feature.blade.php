<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        SETTING FEATURE MENU GURU
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-setting-feature-guru')}}">
                        {{csrf_field()}}
                        @foreach ($menu as $menu)
                            @php
                                $modul1= $modul->where('id_modul',$menu->id_modul)->first();
                            @endphp    
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                    <h4>{{$modul1->nm_modul}}</h4>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                                    <select class="form-control show-tick" name="{{$menu->id_modul}}">
                                        <option value="1" {{ ( $menu->is_aktif == 1) ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ ( $menu->is_aktif == 0) ? 'selected' : '' }}>Non-Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                </div>
                            </div>
                        @endforeach
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
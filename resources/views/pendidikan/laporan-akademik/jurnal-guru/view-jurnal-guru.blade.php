<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card is-gap">
                <div class="header">
                    <h2>
                        FILTER DATA
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Semester</label>
                                        <select class="form-control show-tick" name="id_semester" required="">
                                            @foreach($data_semester as $data)
                                            <option value="{{$data->id_semester}}" {{((!empty($selected_semester) && $selected_semester->id_semester == $data->id_semester)? 'selected' : ($data->is_aktif_semester == 1))? 'selected' : ''}}>
                                                {{$data->tahun_ajaran}}
                                                {{$data->nm_semester}} 
                                                @if($data->is_aktif_semester == 1)
                                                    (Aktif)
                                                @endif
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-line">
                                        <label>Guru</label>
                                        <select class="form-control show-tick" name="id_guru" required="">
                                            @foreach($data_guru as $r)
                                            <option value="{{$data->id_guru}}" {{!empty($selected_guru) && $selected_guru->id_guru == $r->id_guru ? 'selected' : '' }}>
                                                {{$r->gelar_depan ? $r->gelar_depan : ''}} {{$r->nm_pengguna}} {{$r->gelar_belakang ? $r->gelar_belakang : ''}}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                               
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-btn-submit waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@if(!empty($selected_guru) && !empty($selected_semester))



@endif

<script type="text/javascript">
    $('select:not(.ms)').selectpicker();
</script>
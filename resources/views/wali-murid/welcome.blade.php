@php
    $today = Carbon\Carbon::today('Asia/Jakarta');
@endphp
<div class="container-fluid">
    <div class="block-header">
        <h2>DASHBOARD | {{$today->format('d M Y')}}</h2>
    </div>
    <div class="row clearfix">
        @if($data_anak_murid->count() > 1)
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/wali-murid/save-wali-murid')}}">
                    {{csrf_field()}}
                    <div class="header">
                        <h2>
                            PILIH ANAK WALI ANDA
                        </h2>
                    </div>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label>Anak murid Anda</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <select class="form-control show-tick" name="id_siswa">
                                            @foreach($data_anak_murid->sortBy('nm_pengguna') as $anak_murid)
                                            <option value="{{$anak_murid->id_siswa}}">{{$anak_murid->nm_pengguna}}</option>
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
        @endif
    </div>
</div>
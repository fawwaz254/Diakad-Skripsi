<div class="container-fluid">
    <div class="block-header">
        {{-- "{{url(Request::segment(1).'#'.Request::segment(2).'/'.Request::segment(3).'/'.$presences->id_presensi_pengguna.'/edit')}}" --}}
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                </div>
                <div class="body">
                    <form  method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/'.$presences->id_presensi_pengguna.'/'.$start_date.'/'.$end_date.'/edit')}}">
                    {{csrf_field()}}
                    <label>Status</label>
                    <label for="">Status</label>
                    <select class="selectpicker" name="status">
                        @switch($presences['status'])
                            @case('masuk')
                        <option value="{{$presences['status']}}">{{$presences['status']}}</option>
                        <option value="tidak masuk">tidak masuk</option>
                        <option value="ijin">ijin</option>
                                @break
                            @case('tidak masuk')
                        <option value="{{$presences['status']}}">{{$presences['status']}}</option>
                        <option value="masuk">masuk</option>
                        <option value="ijin">ijin</option>
                                @break
                            @case('ijin')
                        <option value="{{$presences['status']}}">{{$presences['status']}}</option>
                        <option value="masuk">masuk</option>
                        <option value="tidak masuk">tidak masuk</option>
                                @break
                            @default
                            <h1>error</h1>
                        @endswitch
                    </select>
                    <label for="">Notes</label>
                    <input type="text" name="notes" value="{{$presences["notes"]}}">
                    <button type="submit">Submit</button>
                </form>
                <a href="/humas#absensi/histori-absensi/{{$presences["id_pengguna"]}}/{{$start_date}}/{{$end_date}}">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
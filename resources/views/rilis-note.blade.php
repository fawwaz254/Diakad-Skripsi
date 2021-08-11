@php

$data = json_decode(file_get_contents('rilis.json'),true);
$data = $data['rilis-terbaru'];

$mine = array_filter($data, function ($var) {
    return ($var['role'] == Request::segment(1));
});

@endphp

@if(count($mine)>0)

<br>

<div class="container-fluid">
    <div class="row">

    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header bg-cyan">
                    <h2>
                        RELEASE NOTES
                    </h2>
                </div>
                <div class="body">

                	    <div class="row clearfix">
                            <div class="col-xs-12 ol-sm-12 col-md-12 col-lg-12">
                                <div class="panel-group" id="accordion_9" role="tablist" aria-multiselectable="true">

                                	@foreach($mine[0]['update'] as $key => $r)

                                    @if($key <= 2)

                                	<div class="panel panel-col-cyan">
                                		<div class="panel-heading" role="tab" id="headingOne_{{$loop->iteration}}">
                                            <h4 class="panel-title">
                                                <a role="button" data-toggle="collapse" data-parent="#accordion_{{$loop->iteration}}" href="#collapseOne_{{$loop->iteration}}" aria-expanded="true" aria-controls="collapseOne_{{$loop->iteration}}">
                                                    {{date('d M Y',strtotime($r['tanggal-update']))}}
                                                </a>
                                            </h4>
                                        </div>
                                	</div>

                                	<div id="collapseOne_{{$loop->iteration}}" class="panel-collapse collapse {{$key == 0 ? 'in' : ''}}" role="tabpanel" aria-labelledby="headingOne_{{$loop->iteration}}">
                                        <div class="panel-body">

                                        	@foreach($r['list-update'] as $s)

                                        		<h4>{{$s['modul']}}</h4>

                                        		<ul>
                                        			@foreach($s['list-fitur'] as $t)
                                        			<li>{{$t['fitur']}} : {{$t['deskripsi']}}</li>
                                        			@endforeach
                                        		</ul>

                                        	@endforeach
                                          	
                                        </div>
                                    </div>

                                    @endif

                                    <br>

                                    @endforeach

                                </div>
                            </div>
                        </div>

                </div>
            </div>
        </div>

    </div>
</div>

<br>

@endif
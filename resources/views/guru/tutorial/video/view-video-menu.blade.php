<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header">
                        <h2>Video Tutorial Menu {{$menu->nm_menu}}</h2>
                    </div>
                    <div class="body">

                    	@if($menu->link_youtube)
                    	<iframe width="870" height="393" src="{{$menu->link_youtube}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    	@else
                    	<p>Mohon maaf video tutorial masih belum ada
                    	@endif

                    </div>
            </div>
        </div>
    </div>
</div>
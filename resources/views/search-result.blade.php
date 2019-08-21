<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header bg-green">
                    <h2>Hasil Search</h2>
                </div>
                <div class="body">
                    <div class="list-group">
                        @foreach($auth_data->menus->filter(function ($item) use ($search) {
                            return false !== stristr($item->nm_menu, $search);
                        }) as $menu)
                        @if(!empty($menu->page))
                        <a href="{{url(Request::segment(1).'#'.$menu->modul->route.'/'.$menu->page)}}" class="target-link list-group-item">
                        @else
                        <a href="javascript:void(0);" class="list-group-item">
                        @endif
                            <h4 class="list-group-item-heading">{{$menu->modul->nm_modul}}</h4>
                            <p class="list-group-item-text">
                                {{$menu->nm_menu}}
                            </p>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
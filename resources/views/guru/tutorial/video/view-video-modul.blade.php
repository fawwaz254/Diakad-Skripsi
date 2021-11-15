<style type="text/css">
    .folder:hover{
        transform: scale(1.2);
    }
    .folder{
        cursor: pointer;
    }
</style>

<div class="container-fluid">
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header">
                        <h2>Video Tutorial Modul {{$modul->nm_modul}}</h2>
                    </div>
                    <div class="body">

                        <div class="row">
                                
                            @foreach($menu as $r)
                            <a href="{{url(Request::segment(1).'#tutorial/video/menu/'.$r->id_menu)}}" style="color: inherit;text-decoration: inherit; ">
                            <div class="col-md-3 folder">
                                <center>
                                   <i class="material-icons" style="color:red;font-size: 45px;">video_library</i>
                                </center>
                                <center>
                                    <span>{{$r->nm_menu}}</span>                 
                                </center>
                            </div>
                            </a>
                            @endforeach

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
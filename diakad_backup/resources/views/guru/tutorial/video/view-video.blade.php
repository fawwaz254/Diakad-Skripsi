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
                        <h2>Video Tutorial Role {{$role_name}}</h2>
                    </div>
                    <div class="body">

                        <div class="row">
                                
                            @foreach($modul as $r)
                            <a href="{{url(Request::segment(1).'#tutorial/video/modul/'.$r->id_modul)}}" style="color: inherit;text-decoration: inherit; ">
                            <div class="col-md-3 folder">
                                <center>
                                   <i class="material-icons" style="color:#DAA520;font-size: 45px;">folder</i>
                                </center>
                                <center>
                                    <span>{{$r->nm_modul}}</span>                 
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
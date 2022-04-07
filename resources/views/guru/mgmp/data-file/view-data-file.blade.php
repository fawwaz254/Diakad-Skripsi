<style type="text/css">
    .folder:hover{
        transform: scale(1.2);
    }
    .folder{
        cursor: pointer;
    }
</style>

<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#mgmp/data-file-mapel/add')}}"><i class="material-icons">note_add</i><span>Tambah File</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                    <div class="header">
                        <h2>Data File</h2>
                    </div>
                    <div class="body">

                        <div class="row">
                                
                            @foreach($category as $r)
                            <a href="{{url(Request::segment(1).'#mgmp/data-file-mapel/category/'.$r->category_file_mgmp_id)}}" style="color: inherit;text-decoration: inherit; ">
                            <div class="col-md-3 folder">
                                <center>
                                   <i class="material-icons" style="color:#DAA520;font-size: 45px;">folder</i>
                                </center>
                                <center>
                                    <span>{{$r->category_file_name}}</span>                 
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
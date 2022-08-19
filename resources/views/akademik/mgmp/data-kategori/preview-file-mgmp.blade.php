<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#mpmp/laporan-mgmp')}}"><i class="material-icons">backspace</i><span>Kembali</span></a>
        </h2>
    </div>
    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header">
                    <h2>Preview File</h2>
                </div>
                <div class="body">
                    @if($ext=='pdf')
                        <iframe src="{{$link}}" style="width:100%; height:535px;" frameborder="0"></iframe>
                    @elseif($ext=='pptx' || $ext=='docx' || $ext=='xlsx')
                        <iframe  src='https://view.officeapps.live.com/op/embed.aspx?src={{$link}}' style="width:100%;" height='535px' frameborder='0'></iframe>
                    @else
                    <img src="{{ $link }}" />
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
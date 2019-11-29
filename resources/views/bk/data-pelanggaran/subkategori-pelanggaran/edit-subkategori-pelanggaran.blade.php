<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#data-pelanggaran/subkategori-pelanggaran')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        EDIT SUB-KATEGORI PELANGGARAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-subkategori-pelanggaran/edit/'.$data_subkategori_pelanggaran->id_subkategori_pelanggaran)}}">
                        {{csrf_field()}}
                        <h2 class="card-inside-title">
                            Nama Kategori
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick" name="id_kategori_pelanggaran">
                                    <option value="" disabled selected >-- Pilih Kategori --</option>
                                    @foreach($data_kategori_pelanggaran as $data)
                                        @if($data->id_kategori_pelanggaran == $data_subkategori_pelanggaran->id_kategori_pelanggaran)
                                            <option value="{{$data->id_kategori_pelanggaran}}" selected >{{$data->tingkat_kategori_pelanggaran}} - {{$data->nm_kategori_pelanggaran}}</option>
                                        @else
                                            <option value="{{$data->id_kategori_pelanggaran}}">{{$data->tingkat_kategori_pelanggaran}} - {{$data->nm_kategori_pelanggaran}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Nama Sub-Kategori
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea name="nm_subkategori_pelanggaran" id="editor1" class="editor1" rows="10" cols="80">{{ $data_subkategori_pelanggaran->nm_subkategori_pelanggaran }}</textarea>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Tingkat
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="tingkat_subkategori_pelanggaran" required="" aria-required="true" aria-invalid="true" value="{{$data_subkategori_pelanggaran->tingkat_subkategori_pelanggaran}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Poin
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="poin_subkategori_pelanggaran" required="" aria-required="true" aria-invalid="true" value="{{$data_subkategori_pelanggaran->poin_subkategori_pelanggaran}}">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Keterangan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="keterangan_subkategori_pelanggaran" required="" aria-required="true" aria-invalid="true" value="{{$data_subkategori_pelanggaran->keterangan_subkategori_pelanggaran}}">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i class="material-icons">save</i><span>Save</span></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@include('scriptjs')
<!-- CKeditor Plugin Js -->
<script src="{{asset('plugins/ckeditor/ckeditor.js')}}"></script>

<script>
CKEDITOR.replace( 'editor1' );

// custom code to key binding ckeditor
timer = setInterval(updateDiv,100);
function updateDiv(){
    var editorText = CKEDITOR.instances.editor1.getData();
    $('#editor1').val(editorText);
}
</script>
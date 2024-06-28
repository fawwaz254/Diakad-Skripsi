<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link"
                href="{{ url(Request::segment(1) . '#data-pelanggaran/kesimpulan-pelanggaran') }}"><i
                    class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        TAMBAH KESIMPULAN PELANGGARAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST"
                        action="{{ url(Request::segment(1) . '/' . Request::segment(2) . '/action-kesimpulan-pelanggaran/add/' . $id_kesimpulan_pelanggaran) }}">
                        {{ csrf_field() }}
                        <h2 class="card-inside-title">
                            Nama Kesimpulan
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="nm_kesimpulan_pelanggaran"
                                    required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Poin Bawah
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="poin_bawah_kesimpulan_pelanggaran"
                                    required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Poin Atas
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="number" class="form-control" name="poin_atas_kesimpulan_pelanggaran"
                                    required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Deskripsi 1
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea name="deskripsi_kesimpulan_pelanggaran_1" id="editor1" class="editor1" rows="10" cols="80"></textarea>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Deskripsi 2
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea name="deskripsi_kesimpulan_pelanggaran_2" id="editor2" class="editor2" rows="10" cols="80"></textarea>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Deskripsi 3
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea name="deskripsi_kesimpulan_pelanggaran_3" id="editor3" class="editor3" rows="10" cols="80"></textarea>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <button class="btn btn-block bg-red waves-effect" type="submit"><i
                                        class="material-icons">save</i><span>Save</span></button>
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
<script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>

<script>
    CKEDITOR.replace('editor1');

    // custom code to key binding ckeditor
    timer = setInterval(updateDiv, 100);

    function updateDiv() {
        var editorText = CKEDITOR.instances.editor1.getData();
        $('#editor1').val(editorText);
    }
</script>

<script>
    CKEDITOR.replace('editor2');

    // custom code to key binding ckeditor
    timer = setInterval(updateDiv, 100);

    function updateDiv() {
        var editorText = CKEDITOR.instances.editor2.getData();
        $('#editor2').val(editorText);
    }
</script>

<script>
    CKEDITOR.replace('editor3');

    // custom code to key binding ckeditor
    timer = setInterval(updateDiv, 100);

    function updateDiv() {
        var editorText = CKEDITOR.instances.editor3.getData();
        $('#editor3').val(editorText);
    }
</script>

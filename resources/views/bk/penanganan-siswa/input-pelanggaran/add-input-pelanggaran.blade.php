<div class="container-fluid">
    <div class="block-header">
        <h2><a class="btn bg-blue waves-effect target-link" href="{{url(Request::segment(1).'#penanganan-siswa/input-pelanggaran')}}"><i class="material-icons">backspace</i><span>Kembali</span></a></h2>
    </div>
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        INPUT PELANGGARAN
                    </h2>
                </div>
                <div class="body">
                    <form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/action-input-pelanggaran/add/'.$id_pelanggaran_siswa)}}">
                        {{csrf_field()}}

                        <div class="row clearfix">

                            <div class="col-md-4">
                                <label>Semester</label>
                                <select class="form-control show-tick" name="id_semester"required="" >
                                  <option value="" disabled selected >-- Pilih Semester --</option>
                                    @foreach($data_semester as $data)
                                        @if($data->is_aktif_semester == 1)
                                            <option value="{{$data->id_semester}}" selected>{{$data->tahun_ajaran}} {{$data->nm_semester}} (Aktif)</option>
                                        @else
                                            <option value="{{$data->id_semester}}">{{$data->tahun_ajaran}} {{$data->nm_semester}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                             <div class="col-md-4">
                                <label>Kelas</label>
                                <select class="form-control show-tick" name="kelas" onchange="changeKelas(this)" required="">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($data_kelas as $data)
                                        <option value="{{$data->id_kelas}}">{{$data->nm_kelas}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label>Nama Siswa</label>
                                <select class="form-control show-tick" name="id_siswa" required="" onchange="changeName(this)">
                                    <option value="">-- Pilih Siswa --</option>
                                </select>
                            </div>

                        </div>
                      

                        <div class="row clearfix">
                        <div class="col-md-4">
                            <label>Riwayat pelanggaran Siswa</label>
                        <br>
                            {{-- <a href="" id="print">PRint</a> --}}
                           
                            <a href="" id="print" ></a>
                     
                            
                            {{-- <select class="form-control show-tick" name="print" required="" onchange="changeName(this)">
                                {{-- <option value="">-- Pilih Siswa --</option> --}}
                            {{-- </select> --}} 
                        </div>
                        </div>

                        {{-- <a href="" name="print">Print</a> --}}



                        <h2 class="card-inside-title">
                            Sub Kategori Pelanggaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <select class="form-control show-tick select2" name="id_subkategori_pelanggaran">
                                    <option value="" disabled selected >-- Pilih --</option>
                                    @foreach($data_kategori as $kategori)
                                    <optgroup label="{{$kategori->nm_kategori_pelanggaran}}">
                                        @foreach($kategori->subkategori_pelanggaran as $data)
                                            <option value="{{$data->id_subkategori_pelanggaran}}">{{$kategori->tingkat_kategori_pelanggaran}}.{{$data->tingkat_subkategori_pelanggaran}} {!!$data->nm_subkategori_pelanggaran!!}</option>
                                        @endforeach
                                    </optgroup>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Catatan Pelanggaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="text" class="form-control" name="catatan_pelanggaran" required="" aria-required="true" aria-invalid="true">
                            </div>
                        </div>
                        <h2 class="card-inside-title">
                            Catatan Khusus <small><b>* Ditampilkan Khusus, Tidak Untuk Diakses User Lain</b></small>
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <textarea name="catatan_pelanggaran_khusus" id="editor1" class="editor1" rows="10" cols="80"></textarea>
                            </div>
                        </div>

                        <h2 class="card-inside-title">
                            Tanggal Pelanggaran
                        </h2>
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="datetime-local" class="form-control" name="tgl_pelanggaran" required="" aria-required="true" aria-invalid="true">
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


var name = this.id_siswa;
var kelas = this.kelas;
var semester = this.id_semester

</script>


<script>
CKEDITOR.replace( 'editor1' );

// custom code to key binding ckeditor
timer = setInterval(updateDiv,100);
function updateDiv(){
    var editorText = CKEDITOR.instances.editor1.getData();
    $('#editor1').val(editorText);
}
</script>

<script>
$(function(){    
    $('.datepicker').bootstrapMaterialDatePicker({
        format: 'DD MMMM YYYY HH:mm:00',
        //lang : 'id',
        clearButton: true,
        weekStart: 1,
        time: true
    });

});


function changeKelas(el){
    $.ajax({
        url: '{{url(Request::segment(1).'/'.Request::segment(2).'/siswa-bykelas')}}',
        type: 'POST',
        data: {
            kelas: $('select[name=kelas]').val()
        },
        success: function(result) {
            $('select[name=id_siswa]').html('');
            var html = '<option value="">-- Pilih Siswa --</option>';
            $.each(result, function( key, item ) {
                html += '<option value="'+item.id_kelas+'/'+''+item.id_siswa+'">  '+item.nm_pengguna+' ('+item.nis_siswa+')</option>'
            });
            $('select[name=id_siswa]').html(html);
        }
    });
}




function changeName(el){
    
    var nilai = $(el).val() ;
    $('#print').html('@foreach($data_semester as $data)'+
    '@if($data->is_aktif_semester == 1)'+
    '<a href="bimbingan-konseling/penanganan-siswa/jurnal-tindakan/print/{{$data->id_semester}}/'+nilai+'" id="print" target="_blank">Histori Pelanggaran</a>'+
    '@endif'+
    '@endforeach');
    
  
   

}
//     $.ajax({
//         url: '{{url(Request::segment(1).'/'.Request::segment(2).'/siswa-bySiswa')}}',
//         type: 'POST',
//         data: {
//             siswa: $('select[name=id_siswa]').val()
//         },
       
//         success: function(result) {
//             $('select[name=print]').html('');
//                      var html = 'List pelanggaran';
//             $.each(result, function( key, item ) {
//             html +=     '<a href='item.catatan_pelanggaran'>'+item.catatan_pelanggaran+'</a>'
//                             // html += '<option value="'+item.id_siswa+'">'+item.nm_pengguna+' ('+item.nis_siswa+')</option>'
//             });
//     //         $('select[name=print]').html(html);
        
           
//         }
//     });

// function semester(el){
//     var semester = $(el).val() ;
//     $("#print").html("id siswa"+semester );
// }





    // $.ajax({
    //     url: '{{url(Request::segment(1).'/'.Request::segment(2).'/siswa-bySiswa')}}',
    //     type: 'POST',
    //     data: {
    //         siswa: $('select[name=siswa]').val()
    //     },
    //     success: function(result) {
    //         // $('select[name=print]').html('');
    //         $("#print").html("hasilnya adalah"+siswa)

    //     }
    // });
//}


</script>
<script>
    $('.select2').select2();
</script>
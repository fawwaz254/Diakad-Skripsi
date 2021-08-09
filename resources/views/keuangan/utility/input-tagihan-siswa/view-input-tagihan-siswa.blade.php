<form id="form-validation" method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/input-tagihan-siswa/add-tagihan')}}">
{{csrf_field()}}
<div class="container-fluid">
    
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        INPUT TAGIHAN SISWA
                    </h2>
                </div>
                <div class="body">

                    <div class="row clearfix">

                        <div class="col-md-3">
                            <label>Semester</label>
                            <select class="form-control" name="semester" >
                            @foreach($data_semester as $semester)
                                <option value="{{$semester->id_semester}}">{{$semester->tahun_ajaran}} {{$semester->nm_semester}}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Nama Biaya</label>
                            <select class="form-control" name="nama_biaya" >
                            @foreach($data_biaya as $data)
                                <option value="{{$data->id_biaya}}">{{$data->nm_biaya}} </option>
                            @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Jenis Biaya</label>
                            <select class="form-control" name="jenis_biaya" disabled="">
                            @foreach($data_jenis_detail_biaya as $data)
                                <option value="{{$data->id_jenis_detail_biaya}}" {{$data->id_jenis_detail_biaya == 3 ? 'selected' : ''}}>{{$data->nm_jenis_detail_biaya}} </option>
                            @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Besar Biaya</label>
                            <input type="number" class="form-control" name="besar_biaya" required="" aria-required="true" aria-invalid="true">
                        </div>

                    </div>

                    <div class="row clearfix">
                        <div class="col-md-12">
                            <label>Keterangan</label>
                            <input type="text" class="form-control" name="keterangan" required="" aria-required="true" aria-invalid="true">
                        </div>
                    </div>

                    <p>Siswa Terpilih : <span id="siswa_terpilih">0</span></p>

                    <button type="submit" class="btn bg-indigo waves-effect" id="button_submit" disabled="">SUBMIT</button>

                </div>
            </div>
        </div>
    </div>

    <br>

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        PILIH SISWA
                    </h2>
                </div>
                <div class="body">

                    <div>
                        <label>Filter Kelompok Biaya</label>
                        <select class="form-control" id="kelompok_biaya" onchange="tampilkan_data(1)" name="kelompok_biaya">
                        @foreach($data_kelompok_biaya as $data)
                            <option value="{{$data->id_kelompok_biaya}}">{{$data->nm_kelompok_biaya}} </option>
                        @endforeach
                        </select>
                    </div>

                    <br>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable display responsive nowrap" id="primary_table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="isi_tabel">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</form>

@include('scriptjs')

<script type="text/javascript">

    tampilkan_data(0);

    $(document).on('change', '.filled-in', function() {

        var numberOfChecked = $('input:checkbox:checked').length;

        if(numberOfChecked > 0){
            $('#siswa_terpilih').html(numberOfChecked);
            $('#button_submit').removeAttr('disabled', 'disabled');
        } else {
            $('#button_submit').attr('disabled', 'disabled');
            $('#siswa_terpilih').html('0');
        }
    })

    function tampilkan_data(mode){

        var id = $('#kelompok_biaya').val();
        $('#siswa_terpilih').html('0');

        $.ajax({
            url : base_url+'/keuangan/utility/input-tagihan-siswa/filter-siswa/'+id,
            type : 'get',
            dataType : 'json',
            success : function (response){

                if(mode == 1) $('#primary_table').DataTable().clear().destroy();

                $('#isi_tabel').empty();

                $.each(response,function(i,value){
                    $('#isi_tabel').append(`
                        <tr>
                            <td>`+(i+1)+`</td>
                            <td>`+value.nis_siswa+`</td>
                            <td>`+value.nm_pengguna+`</td>
                            <td>`+value.nm_kelas+`</td>
                            <td>
                              <input type="checkbox" id="basic_checkbox_`+value.id_siswa+`" name="id_siswa[]" value="`+value.id_siswa+`" class="filled-in" />
                              <label for="basic_checkbox_`+value.id_siswa+`"></label>
                            </td>
                        </tr>
                    `);
                })
                
                $('#primary_table').DataTable();
            },  
            error: function(){
                alert('mohon maaf terjadi error, silahkan hubungi admin');
            }
        })

    }

</script>
<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <button type="button" onclick="viewGuru()" class="btn btn-default" >
                Data Histori Absensi Guru dan Pegawai
            </button>
            <button type="button"  class="btn btn-primary">
                Data Histori Absensi Siswa
            </button>
            <div class="card" style="margin-top: 10px">
                <div class="header" >
                    <h2>Filter Data</h2>
                </div>
                <div class="body">
                    <div class="row clearfix">

                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <label>
                                Kelas
                            </label>
                            <select class="form-control show-tick" name="kelas" onchange="changeKelas()">
                                <option>Pilih Kelas</option>
                                @foreach ($list_kelas as $lk)
                                    <option value="{{ $lk->id_kelas }}">{{ $lk->nm_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <label>Nama Pengguna</label>
                            <select class="form-control show-tick" name="pengguna">
                                <option>Pilih kelas dahulu</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <label>Start Date</label>
                            <input type="date" class="form-control" value="{{$start_date}}" name="start_date" aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <label>End Date</label>
                            <input type="date" class="form-control" value="{{$end_date}}" name="end_date" aria-required="true" aria-invalid="true">
                        </div>

                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <button type="button" class="btn bg-purple waves-effect" style="margin-top:27px;" onclick="filterAction()">Lihat </button>  
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

</div>
@include('scriptjs')
<script>


    function changeKelas(el){
        // alert('{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/post-get-penguna')}}');
        $.ajax({
            url: '{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/siswa/post-get-penguna')}}',
            type: 'POST',
            data: {
                kelas: $('select[name=kelas]').val()
            },
            success: function(result) {
                $('select[name=pengguna]').html('');
                var html = '<option value="">-- Pilih Pengguna --</option>';
                $.each(result, function( key, item ) {
                    html += '<option value="'+item.id_pengguna+'">'+item.nm_pengguna+ '</option>';
                });
                $('select[name=pengguna]').html(html);
            }
        });
    }

    function filterAction() {
        loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/siswa/' + $('select[name=pengguna]').val() + '/' + $(
            'input[name=start_date]').val() + '/' +  $('input[name=end_date]').val());
    }

    function viewGuru() {
        window.location = '/humas#absensi/detail-absensi'
    }
    </script>
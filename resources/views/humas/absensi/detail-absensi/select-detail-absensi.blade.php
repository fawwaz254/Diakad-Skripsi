<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">

                <div class="header">
                    <h2>Filter Data</h2>
                </div>

                <div class="body">

                    <div class="row clearfix">

                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <label>
                                Unit Kerja
                            </label>
                            <select class="form-control show-tick" name="unit_kerja" onchange="changeUnitKerja()">
                                <option>Pilih unit kerja</option>
                                <option value="1">Pegawai</option>
                                @foreach ($list_unit_kerja as $uk)
                                    <option value="{{ $uk->id_unit_kerja }}">{{ $uk->nm_unit_kerja }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <label>Nama Pengguna</label>
                            <select class="form-control show-tick" name="pengguna">
                                <option>Pilih unit kerja dahulu</option>
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

    function changeUnitKerja(el){
        $.ajax({
            url: '{{url(Request::segment(1).'/'.Request::segment(2).'/'.Request::segment(3).'/post-get-penguna')}}',
            type: 'POST',
            data: {
                unit_kerja: $('select[name=unit_kerja]').val()
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
        loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/' + $('select[name=pengguna]').val() + '/' + $(
            'input[name=start_date]').val() + '/' +  $('input[name=end_date]').val());
    }
    </script>
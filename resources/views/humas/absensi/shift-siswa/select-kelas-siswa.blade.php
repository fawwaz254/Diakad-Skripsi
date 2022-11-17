<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="header" >
                    <h2>Filter Data</h2>
                </div>
                <div class="body">
                    <div class="row clearfix">

                        <div class="col-md-5 col-sm-12 col-xs-12">
                            <label>
                                Kelas
                            </label>
                            <select class="form-control show-tick" name="kelas" >
                                <option>Pilih kelas</option>
                                <option value="0">Semua</option>
                                @foreach ($kelas as $lk)
                                    <option value="{{ $lk->id_kelas }}">{{ $lk->nm_kelas }}</option>
                                @endforeach
                            </select>
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
    function filterAction() {
        loadURI('{{ Request::segment(2) }}/{{ Request::segment(3) }}/' + 
            $('select[name=kelas]').val());
    }
    </script>
<style>
    input {
        position: relative;
        width: 150px;
        height: 20px;
        color: white;
    }

    input:before {
        position: absolute;
        top: 3px;
        left: 3px;
        content: attr(data-date);
        display: inline-block;
        color: black;
    }

    input::-webkit-datetime-edit,
    input::-webkit-inner-spin-button,
    input::-webkit-clear-button {
        display: none;
    }

    input::-webkit-calendar-picker-indicator {
        position: absolute;
        top: 3px;
        right: 0;
        color: black;
        opacity: 1;
    }
</style>
<div class="container-fluid">

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card" style="margin-top: 10px">
                <div class="header">
                    <h2>
                        SISWA
                    </h2>
                </div>
                <div class="body">
                    @foreach ($kpi as $item)
                    <h4>{{$item->nm_kategori}}</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kecakapan</th>
                                <th>Predikat</th>
                                <th>Deskripsi Kecakapan Penerapan Ibadah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($item->subkategorikpi as $key1=>$subkategorikpis)
                            @php
                                if($subkategorikpis->nm_subkategori == null){
                                    $rowspan = $subkategorikpis->komponenkpi->count();
                                }else {
                                    $rowspan = $subkategorikpis->komponenkpi->count() + 1;    
                                }
                                // dd($rowspan);
                            @endphp
                                @if ($subkategorikpis->nm_subkategori)
                                    <tr>
                                        <td rowspan="{{$rowspan}}">{{$key1+1}}</td>
                                        <td>{{$subkategorikpis->nm_subkategori}}</td>
                                        <td></td>                                
                                        <td rowspan="{{$rowspan}}">
                                            @foreach ($subkategorikpis->komponenkpi as $key2=>$komponenkpis1)
                                            @if ($komponenkpis1->nilaikomponenkpi->where('id_siswa',$siswa->id_siswa)->first())    
                                            {{$komponenkpis1->nilaikomponenkpi->where('id_siswa',$siswa->id_siswa)->first()->deskripsi_nilai}} 
                                            {{$komponenkpis1->deskripsi_komponen}}, 
                                            @endif
                                            @endforeach
                                        </td>                                
                                    </tr>
                                    @foreach ($subkategorikpis->komponenkpi as $key=>$komponenkpis)
                                        <tr>
                                            <td>{{$komponenkpis->nm_komponen}}</td>
                                            <td>
                                                @if ($komponenkpis->nilaikomponenkpi->where('id_siswa',$siswa->id_siswa)->first())    
                                                {{$komponenkpis->nilaikomponenkpi->where('id_siswa',$siswa->id_siswa)->first()->nilai_komponen}}
                                                @endif
                                            </td>                                                               
                                        </tr>
                                    @endforeach    
                                @else
                                    @foreach ($subkategorikpis->komponenkpi as $key=>$komponenkpis)
                                        @if ($key == 0)
                                        <tr>
                                            <td rowspan="{{$rowspan}}">{{$key1+1}}</td>
                                            <td>{{$komponenkpis->nm_komponen}}</td>
                                            <td>
                                                @if ($komponenkpis->nilaikomponenkpi->where('id_siswa',$siswa->id_siswa)->first())    
                                                {{$komponenkpis->nilaikomponenkpi->where('id_siswa',$siswa->id_siswa)->first()->nilai_komponen}}
                                                @endif
                                            </td>                                
                                            <td rowspan="{{$rowspan}}">
                                                @foreach ($subkategorikpis->komponenkpi as $key2=>$komponenkpis1)
                                                @if ($komponenkpis1->nilaikomponenkpi->where('id_siswa',$siswa->id_siswa)->first())    
                                                {{$komponenkpis1->nilaikomponenkpi->where('id_siswa',$siswa->id_siswa)->first()->deskripsi_nilai}} 
                                                {{$komponenkpis1->deskripsi_komponen}}, 
                                                @endif
                                                @endforeach
                                            </td>                                
                                        </tr>
                                        @else
                                        <tr>
                                            <td>{{$komponenkpis->nm_komponen}}</td>
                                            <td>
                                                @if ($komponenkpis->nilaikomponenkpi->where('id_siswa',$siswa->id_siswa)->first())    
                                                {{$komponenkpis->nilaikomponenkpi->where('id_siswa',$siswa->id_siswa)->first()->nilai_komponen}}
                                                @endif
                                            </td>                                                               
                                        </tr>
                                        @endif
                                    @endforeach 
                                @endif                                
                            @endforeach
                        </tbody>
                    </table>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<br>
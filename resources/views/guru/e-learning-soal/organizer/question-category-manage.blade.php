{{-- @extends('app')
@section('meta') --}}
<!-- Meta -->
{{-- @endsection --}}
{{-- @section('content')
{{-- <body class="theme-red"> --}}
    <!-- Page Loader -->
    {{-- <div class="page-loader-wrapper">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <div class="overlay"></div>
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    @include('organizer/partials/topbar')
    @include('organizer/partials/sidebar', ['active_menu' => 'question-category']) --}}
    {{-- <section class="content"> --}}
        <div class="container-fluid">
            <div class="block-header">
                <h2><a type="button" class="btn bg-grey waves-effect" href="{{url('guru/e-learning-soal/kategori-soal')}}">
                    <i class="material-icons">keyboard_backspace</i>
                    <span>Back</span>
                </a> 
                &nbsp; &nbsp; &nbsp;
                MANAGE KATEGORI
                </h2>
            </div> 
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header bg-pink">
                            <h2>
                                KATEGORI SOAL
                            </h2>
                        </div>
                        <div class="body">
                            <div class="row clearfix">
                                <form class="form-validation" id="form-validation"  method="POST" action="{{url(Request::segment(1).'/'.Request::segment(2).'/kategori-soal/manage')}}">
                                    {{csrf_field()}}
                                    <input type="hidden" name="id_kategori_soal" @if($item) value="{{$item->id_kategori_soal}}" @endif>
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">title</i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <p>Nama Kategori</p>
                                                <input type="text" class="form-control" name="nama" required="" aria-required="true" aria-invalid="true" @if($item) value="{{$item->nama}}" @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">label</i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <p>Nilai benar</p>
                                                <input type="text" class="form-control" name="nilai_benar" required="" aria-required="true" aria-invalid="true" @if($item) value="{{$item->nilai_benar}}" @else value="0" @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">label</i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <p>Nilai salah</p>
                                                <input type="text" class="form-control" name="nilai_salah" required="" aria-required="true" aria-invalid="true" @if($item) value="{{$item->nilai_salah}}" @else value="0" @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="material-icons">label</i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-11">
                                        <div class="form-group form-float">
                                            <div class="form-line">
                                                <p>Nilai kosong<p>
                                                <input type="text" class="form-control" name="nilai_kosong" required="" aria-required="true" aria-invalid="true" @if($item) value="{{$item->nilai_kosong}}" @else value="0" @endif>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button class="btn btn-block bg-pink waves-effect"  id="btn-submit">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@include('scriptjs')
{{-- @endsection --}}
{{-- @section('js')
<!-- Javascript -->
@endsection --}}

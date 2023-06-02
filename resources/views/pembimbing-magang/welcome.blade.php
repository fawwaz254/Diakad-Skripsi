@php
    $today = Carbon\Carbon::today('Asia/Jakarta');
@endphp
<div class="container-fluid">
    <div class="block-header">
        <h2>DASHBOARD | {{ $today->format('d M Y') }}</h2>
    </div>
    <div class="row clearfix">
    </div>
</div>

@include('rilis-note')

@extends('layout._main',["title"=>"Barangay Map"])
@section('content')
@include('layout._breadcrumb',["title_page"=>"Barangay Map"])
<section class="section">
    <div class="card p-3">
        <div class="card-body">
            <h5 class="card-title">CSJDM</h5>
            <iframe src="{{env('QGIS_URL')}}" width="100%" height="500" frameborder="0"></iframe>
        </div>
    </div>
</section>
@endsection
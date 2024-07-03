@extends('layout._main',["title"=>"Barangay Map"])
@section('content')
@include('layout._breadcrumb',["title_page"=>"Barangay Map"])
<section class="section">
    <div class="card p-3">
        <div class="card-body">
            <div class="d-flex justify-content-end align-items-center mb-4">
            {{-- <h5 class="card-title">CSJDM</h5> --}}
            <button class="btn btn-success btn" data-bs-target="#upload-map-modal" data-bs-toggle="modal">Upload map</button>
            </div>
            @php
                $path = public_path()."/qgis";
                $qgis = "";
                $scandir = array_diff(scandir($path), array('..', '.'));
                foreach($scandir as $file){
                    $qgis = $file;
                    break;
                }
            @endphp
            <iframe src="{{ asset('qgis/'.$qgis) }}" width="100%" height="700" frameborder="0"></iframe>
        </div>
    </div>


    <div class="modal" id="upload-map-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Map</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post" action="/barangay-map/upload" enctype="multipart/form-data" >
                    @csrf
                    <div class="modal-body">
                        <input required type="file" class="form-control" name="files" id="upload-map">
                    </div>
                    <div class="modal-footer d-flex justify-content-between align-items-center">
                        <a href="javascript:void(0)" class="text-secondary" data-bs-dismiss="modal">cancel</a>
                        <button class="btn btn-sm btn-primary">Upload map</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
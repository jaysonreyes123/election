<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
      
        <title>{{$title}}</title>
        <meta content="" name="description">
        <meta content="" name="keywords">
        <meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
       @include('layout.style')
      </head>
      <style>
        body{
            font-size: 13px;
        }
        #main{
            min-height: calc(100vh - 130px);
        }
        .dt-layout-table{
            overflow-y: auto;
        }
        #loader{
            display: none;
            margin: 0px;
            padding: 0px;
            position: absolute;
            right: 0px;
            top: 0px;
            width: 100%;
            height: 100%;
            background-color: rgb(255, 255, 255);
            z-index: 99999;
            opacity: 0.5;
        }
        #loading{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%)
        }
        .font-weight-bold{
            font-weight: bold !important;
        }
      </style>

<body>
    <div id="loader">
        <div id="loading">
            <div class="spinner-grow" role="status"></div>
            <div class="spinner-grow" role="status"></div>
            <div class="spinner-grow" role="status"></div>
            <div class="spinner-grow" role="status"></div>
            <div class="spinner-grow" role="status"></div>
        </div>   
    </div>
    @include('layout._header')
    @include('layout._sidemenu',["menu" => $title])
    <main id="main" class="main">
        @yield('content')
    </main>
    @include('layout.script')
    @include('layout._footer')
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var swal = Swal.mixin({
			toast: true,
			position: 'top-end',
			showConfirmButton: false,
			timer: 2500,
		});
    </script>
</body>
</html>

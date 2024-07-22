   @extends('layout._main',["title"=>"Dashboard"])
   @section('content')
   <style>
   
    .bg-green{
      color : #2eca6a;
      background: #e0f8e9;
    }
    .bg-green{
      color : #2eca6a;
      background: #e0f8e9;
    }
    .bg-red{
      color: #ff4c51 !important;
      background: #ffe4e5 !important;
    }
    .bg-yellow{
      color: #ffb400 !important;
      background: #fff4d9 !important;
    }
    /* .card-title > span{
      font-size: 10px;
    } */
    /* .card-icon{
      height: 40px !important;
      width: 40px !important;
    } */
    .card-icon > i{
      font-size: 17px;
    }
    .ps-3 > h6{
      font-size: 17px !important;
    }
    .widget-title{
      padding: 0px;
      padding-top: 10px;
    }
    </style>
    <link href=" {{ asset('assets/node_modules/gridstack/dist/gridstack.min.css') }} " rel="stylesheet"/>
    <script src=" {{ asset('assets/node_modules/gridstack/dist/gridstack-all.js') }} "></script>
    {{-- <link href="node_modules/gridstack/dist/gridstack.min.css" rel="stylesheet"/>
    <script src="node_modules/gridstack/dist/es5/gridstack-poly.js"></script>
    <script src="node_modules/gridstack/dist/es5/gridstack-all.js"></script> --}}
    <section class="section dashboard">
        @include('layout._breadcrumb',["title_page"=>"Dashboard"])
        <div class="d-flex justify-content-end" >
          <button class="btn btn-success" id="btn-layout" style="display: none" onclick="save()">Save layout</button>
        </div>
      <div class="row">
        <div class="col-lg-12" style="margin: 0px;padding: 0px">
          <div class="grid-stack">
            @foreach ($output as $widget )
            <div class="grid-stack-item" data-id={{$widget["id"]}} 
            gs-w="{{$widget["position"]["w"]}}"
            gs-h="{{$widget["position"]["h"]}}"
            {{isset($widget["position"]["x"]) ? "gs-x=".$widget["position"]["x"] : "" }} 
            {{isset($widget["position"]["y"]) ? "gs-y=".$widget["position"]["y"] : "" }}  
            >
                <div class="grid-stack-item-content card rounded">
                  <div class="card-body">
                    <h2 class="card-title">{{$widget["name"]}}</h2>
                    <h3 class="card-text">{{$widget["widget"]}}</h3>
                  </div>
                </div>
            </div>
            @endforeach
          </div>
        </div>
      
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              {{-- <h5 class="card-title">CSJDM</h5> --}}
              @php
                  $path = public_path()."/qgis";
                  $qgis = "";
                  $scandir = array_diff(scandir($path), array('..', '.'));
                  foreach($scandir as $file){
                      $qgis = $file;
                      break;
                  }
              @endphp
              <iframe src="{{ asset('qgis/'.$qgis) }}" width="100%" height="600" style="margin-top: 20px" frameborder="0"></iframe>
            </div>
          </div>
        </div>
      </div>
    </section>  
<script>

</script>
@push('script')
  <script>
      
  
    var precinct_barchart;
    var precinct_piechart;
    $(function(){
      // barangay_chart();
      // load_precinct();
      // load_precinct_pie();
    })
    let grid = GridStack.init();

    grid.on("change",function(item,event){
      $("#btn-layout").show();
    })

    grid.cellHeight(grid.cellWidth() * 1.2);
    function save(){
      var widget_id = [];
      var widget_position = [];
      var grids = grid.engine.nodes;
      grids.map((item)=>{
        let id = $(item.el).data("id");
        widget_id.push(id);
        widget_position.push(item._orig)
      })
      $("#loader").show();
      $.ajax({
        url:"/dashboard/update_grid",
        method:"post",
        data:{widget_id:widget_id,widget_position:widget_position},
        success:function(data){
          $("#loader").hide();
          location.reload();
        }
      })
    }

    function barangay_chart(){
      $.ajax({
        url:"/dashboard/barangay",
        method:"get",
        success:function(data){
          var chart = new ApexCharts(document.querySelector("#barangay-barchart"),data);
          chart.render();
        }
      })
    }
    

    function load_precinct(){
      let barangay = $("#precinct-barchart-select").val();
      $.ajax({
        url:"/dashboard/precinct/"+barangay,
        method:"get",
        success:function(data){
          precinct_barchart = new ApexCharts(document.querySelector("#precinct-barchart"),data);
          precinct_barchart.render();
        }
      })
    }

    function load_precinct_pie(){
      let barangay = $("#precinct-piechart-select").val();
      $.ajax({
        url:"/dashboard/precinct-pie/"+barangay,
        method:"get",
        success:function(data){
          precinct_piechart = new ApexCharts(document.querySelector("#precinct-piechart"),data);
          precinct_piechart.render();
        }
      })
    }

    function update_precinct(){
      let barangay = $("#precinct-barchart-select").val();
      $.ajax({
        url:"/dashboard/precinct/"+barangay,
        method:"get",
        success:function(data){
          precinct_barchart.updateOptions(data);
        }
      })
    }

    function update_precinct_piechart(){
      let barangay = $("#precinct-piechart-select").val();
      $.ajax({
        url:"/dashboard/precinct-pie/"+barangay,
        method:"get",
        success:function(data){
          console.log(data)
          precinct_piechart.updateOptions(data);
        }
      })
    }

    $("#precinct-barchart-select").change(function(){
      update_precinct();
    })
    $("#precinct-piechart-select").change(function(){
      update_precinct_piechart();
    })
  </script>
@endpush
@endsection
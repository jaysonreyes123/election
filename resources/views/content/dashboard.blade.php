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
    .card-title > span{
      font-size: 10px;
    }
    .card-icon{
      height: 40px !important;
      width: 40px !important;
    }
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
    <section class="section dashboard">
        @include('layout._breadcrumb',["title_page"=>"Dashboard"])
      <div class="row">
        <div class="col-lg-12">
          <div class="row">

            <div class="col-xl-2 col-lg-4">
              <div class="card info-card">
                <div class="card-body">
                  <h5 class="card-title widget-title"><span>Yes Votes</span></h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon bg-green rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-person-check-fill"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{\App\Models\Voters::where('vote',1)->count()}}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-2 col-lg-4">
              <div class="card info-card">
                <div class="card-body">
                  <h5 class="card-title widget-title"><span>No Votes</span></h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon bg-red rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-person-x-fill"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{\App\Models\Voters::where('vote',0)->count()}}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-2 col-lg-4">
              <div class="card info-card">
                <div class="card-body">
                  <h5 class="card-title widget-title"><span>Total Votes</span></h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon bg-yellow rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{\App\Models\Voters::count()}}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-4">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title widget-title"><span># of Precinct</span></h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-building"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{$precinct_model->count()}}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-lg-4">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title widget-title"><span># of Barangay</span></h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-house-fill"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{$barangay_model->count()}}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>

              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">CSJDM</h5>
                    <iframe src="{{ env('QGIS_URL') }}" width="100%" height="500" frameborder="0"></iframe>
                  </div>
                </div>
              </div>

           
            <div class="col-6">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Votes per Barangay</h5>
                  <div id="barangay-barchart"></div>
                </div>
              </div>
            </div>

            <div class="col-6">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Barangay Bar Chart</h5>
                      <div class="filter" style="right: 10px !important">
                        <div class="form-floating mt-2">
                          <select class="form-select" name="" id="precinct-barchart-select">
                            @foreach ($barangay_model as $barangay )
                              <option value="{{$barangay->name}}">{{$barangay->name}}</option>
                            @endforeach
                          </select>
                          <label for="">Barangay</label>
                        </div>
                      </div>
                      <div class="mt-4" id="precinct-barchart"></div>
                    </div>
                  </div>
                </div>
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                      <h5 class="card-title">Barangay Win/Loss</h5>
                      <div class="filter" style="right: 10px !important">
                        <div class="form-floating mt-2">
                          <select class="form-select" name="" id="precinct-piechart-select">
                            @foreach ($barangay_model as $barangay )
                              <option value="{{$barangay->name}}">{{$barangay->name}}</option>
                            @endforeach
                          </select>
                          <label for="">Barangay</label>
                        </div>
                      </div>
                      <div class="mt-4" id="precinct-piechart"></div>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div><!-- End Left side columns -->

      </div>
    </section>  

@push('script')
  <script>
    var precinct_barchart;
    var precinct_piechart;
    $(function(){
      barangay_chart();
      load_precinct();
      load_precinct_pie();
    })

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
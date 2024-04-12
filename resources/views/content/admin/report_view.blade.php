@extends('layout._main',["title"=>"Report"])
@section('content')
    <section class="section">
        <div class="row">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4>{{ucfirst($report_details->name)}}</h4>
                <div>
                    <a class="btn btn-sm btn-primary px-4" href="/report/export/{{$module}}/pdf/{{$id}}">PDF</a>
                    <a class="btn btn-sm btn-success px-4" href="/report/export/{{$module}}/csv/{{$id}}">CSV</a>
                    <a class="btn btn-sm btn-success px-4" href="/report/export/{{$module}}/xlsx/{{$id}}">EXCEL</a>
                </div>
            </div>
            <div class="col-lg-12 grid-margin col-xl-12 stretch-card">
                <div class="card p-2">
                    <div class="card-body">
                        <div class="table-reponsive">
                            <table class="table hover pointer" id="report-view-table">
                                <thead>
                                    <tr>
                                        @foreach ($headers as $header )
                                            @php
                                                $header_label =  \App\Helper\FieldHelper::getSingleFieldModule($moduleid,$header);
                                            @endphp
                                            <th>{{$header_label->label}}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($report_model as $data )
                                        <tr>
                                        @foreach ($headers as $header )
                                            <td>{{$data->$header}}</td>
                                        @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
       </div>
    </section>
@push('script')
    <script>
        var table;
        $(function(){
            table = $("#report-view-table").DataTable({
                order:[],
                pageLength:100
            });
        })
    </script>
@endpush
@endsection
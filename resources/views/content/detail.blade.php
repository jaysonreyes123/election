@extends('layout._main',["title"=>$module])
@section('content')
<style>
    tbody, td, tfoot, th, thead, tr{
        border-style: none !important;
    }
</style>
    @include('content.breadcrumb',["module"=>$module,"view" => "All", "id" => $id,"action" => "detail"])
    @include('content.import',["module" => $module])
    <section class="section mt-4">
        @foreach ($getBlock as $block )
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        {{$block->name}}
                    </div>
                    <div class="card-body">
                        @php
                            $blockid = $block->id;
                            $moduleid = $getModuleID;
                            $fields = \App\Helper\FieldHelper::getField($moduleid,$blockid);
                            $ctr = 0;
                        @endphp
                        <table class="table mt-4">
                            @foreach ($fields  as $field )
                                @php
                                    $columnname = $field->columnname;
                                    $data = $field_data->$columnname;
                                    $ctr++;
                                    $per_column = 2;
                                    $filepath = asset('attachment');
                                    $type = $field->type;
                                @endphp
                                @if ($ctr == 1)
                                    <tr>
                                @endif
                                    <td style="font-weight: bold;width: 10%">{{$field->label}}</td>
                                    <td style="width: 40%">
                                        @if ($type == "file")
                                            <a href="{{$filepath}}/{{$data}}">{{$data}}</a>
                                        @else
                                            {{$data}}
                                        @endif
                                    </td>
                                @if ($fields->count() == 1)
                                    <td style="width: 10%"></td>
                                    <td style="width: 40%"></td>
                                @endif
                                @if ($ctr == $per_column)
                                    @php
                                        $ctr = 0;
                                    @endphp
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </section>
@include('compontent.print')
@push('script')
<script>
    $("#print-barangay").click(function(){
        const barangay_name = $(this).data('value');
        $("#barangay-print-name").text(barangay_name);
        document.getElementById('viewer').src="/leaders/"+barangay_name;
        $("#barangay-print-modal").modal('show');
    })
</script>
@endpush
@endsection
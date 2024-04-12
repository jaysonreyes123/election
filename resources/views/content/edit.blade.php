@extends('layout._main',["title"=>$module])
@section('content')
<style>
    tbody, td, tfoot, th, thead, tr{
        border-style: none !important;
    }
    td{
        vertical-align: middle;
    }
    tr td:last-child {
    white-space: nowrap;
}
</style>
@include('content.breadcrumb',["module"=>$module,"view" => "All", "id" => $id,"action" => "edit"])
<section class="section mt-4">
    <form id="edit-form">
        @csrf
        <input type="hidden" name="id" value="{{$id}}">
        <input type="hidden" name="module" value="{{$module}}">
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
                            $count = 0;
                        @endphp
                        <table class="table mt-4" style="width: 100%">
                            @foreach ($fields  as $field )
                                @php
                                    $columnname = $field->columnname;
                                    $value = "";
                                    if($id == ""){
                                        if($field->default != ""){
                                            $value = $field->default;
                                        }
                                    }
                                    else{
                                        $value = $field_data->$columnname;
                                    }
                                    $ctr++;
                                    $per_column = 2;
                                @endphp
                                @if ($ctr == 1)
                                    <tr>
                                @endif
                                    <td  style="font-weight: bold;width: 10%">{{$field->label}} @if($field->mandatory == 1) <span title="required" class="text-danger">*</span> @endif </td>
                                    <td style="width: 40%">
                                        @if ($field->type == "picklist")
                                            <select class="form-control" style="width: 250px" name="{{$columnname}}">
                                                <option value="">Select an Option</option>
                                                @foreach (json_decode($field->picklist_value) as $picklist)
                                                    @if ($id == "")
                                                        <option value="{{$picklist}}" {{$picklist == $field->default ? "selected" : "" }}>{{$picklist}}</option> 
                                                    @else
                                                        <option value="{{$picklist}}" {{$picklist == $value ? "selected" : "" }}>{{$picklist}}</option> 
                                                    @endif
                                                    
                                                @endforeach
                                            </select>
                                        
                                        @elseif ($field->type == "text")
                                            <input style="width: 250px"  type="text" name="{{$columnname}}" class="form-control" value="{{$value}}">
                                        @elseif ($field->type == "integer")
                                            <input style="width: 250px"  type="number" name="{{$columnname}}" class="form-control" value="{{$value}}">
                                        @elseif ($field->type == "date")
                                            <input style="width: 250px"  type="date" name="{{$columnname}}" class="form-control" value="{{$value}}">
                                        @elseif ($field->type == "textarea")
                                            <textarea name="{{$columnname}}" class="form-control" cols="30" rows="2">{{$value}}</textarea>
                                        @endif
                                        
                                        <div  class="form-text text-danger validation" id="{{$columnname}}_validation"></div>
                                    </td>

                                    @if ($fields->count() == 1)
                                        <td style="width: 10%"></td>
                                        <td style="width: 50%"></td>
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
        @endforeach
        <div class="row fixed-bottom">
            <div class="col-12">
                <div class="card d-flex justify-content-center align-items-center py-2" style="margin-bottom: 0px">
                    <div class="card-body p-0">
                        <a class="text-danger" style="font-weight: bold; margin-right: 20px" href="/view/module/{{$module}}">Cancel</a>
                        <button class="btn btn-success shadow shadow-sm">save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
@push('script')
<script>
    $("#edit-form").on('submit',function(e){
        $("#loader").show();
        $(".validation").text("");
        e.preventDefault();
        $.ajax({
            url:"/save/module",
            method:"post",
            data:$(this).serialize(),
            success:function(data){
                location.href= data.redirect;
                $("#loader").hide();
            },
            error:function(err){
                console.log(err)
                $("#loader").hide();
                const keys = Object.keys(err.responseJSON.errors);
                keys.map((item)=>{
                    $("#"+item+"_validation").html(err.responseJSON.errors[item])
                })
            }
        })
    })
</script>
@endpush
@endsection
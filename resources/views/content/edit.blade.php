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
                                        @include('compontent.edit_field',["fieldtype" => $field->type,"columnname" => $columnname,"value" => $value,"id" => $id])
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
    var files_upload = [];
    $(".files").change(function(e){
        $("#loader").show();
        const files = e.target.files[0];
        const form = new FormData();
        form.append("files",files);
        const columnname = $(e.target).data('file');
        $("input[name='"+columnname+"']").val(files.name);
        $("#"+columnname+"_file_name").text(files.name);
        const files_ = [];
        files_[columnname] = files;
        files_upload.map((item,index)=>{
            if(item[columnname]){
                files_upload.splice(index, 1)
            }
            else{
                console.log("false");
            }
        })
        files_upload.push(files_)
        console.log(files_upload);
        $.ajax({
            type:"post",
            url:"/file/upload",
            processData: false,
            contentType: false,
            data:form,
            success:function(data){
                console.log(data)
                $("#loader").hide();
            }
        })
    })
</script>
@endpush
@endsection
@extends('layout._main',["title"=>"Fields"])
@section('content')
<style>
   input:read-only {
  background-color: rgb(245, 245, 245);
}
.nav-tabs{
    border: none;
}
.nav-tabs .nav-link.active{
    border-color: transparent;
    border-bottom: 2px solid black;
}
</style>
<section class="section">
    <div class="row">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>Field</h4>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-4">
            <div class="form-floating">
                
                <select class="form-select shadow shadow-sm" id="block-select">
                    @php
                        $models = \App\Models\Tab::all();
                    @endphp
                    @foreach ($models as $model )
                        <option value="{{$model->id}}">{{$model->name}}</option>
                    @endforeach
                </select>
                <label for="">Select Module</label>
            </div>
        </div>
    </div>
    <div class="card p-3">
        <div class="card-body">
            <ul class="nav nav-tabs" role="tablist" style="padding: 0px !important">
                <li class="nav-item" role="presentation">
                    <a href="#showViewLayout" class="nav-link"  data-bs-toggle="tab" data-bs-target="#showViewLayout" type="button" role="tab" >Detail View Layout</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a href="#showDuplicatePrevention" class="nav-link" data-bs-toggle="tab" data-bs-target="#showDuplicatePrevention" type="button" role="tab">Duplicate Prevention</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane" id="showViewLayout" role="tabpanel">
                    <div class="d-flex justify-content-between mt-3 mb-3">
                        <button class="btn btn-primary btn-sm shadow" data-bs-toggle="modal" data-bs-target="#block-modal">Add Block</button>
                        <button class="btn btn-success btn-sm shadow" id="save-layout-btn" style="margin-right: 10px;display: none" >Save Layout</button>
                    </div>
                    <div id="block-content"></div>
                </div>
                <div class="tab-pane " id="showDuplicatePrevention" role="tabpanel" aria-labelledby="profile-tab">...</div>
            </div>
        </div>
    </div>
    
</section>
<div class="modal" id="block-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Add Block</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="block-form">
                @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Block Name <span class="text-danger">*</span></label>
                    <input type="text" id="block-name" required class="form-control">
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <a class="btn btn-default" data-bs-dismiss="modal">Cancel</a>
                <button class="btn btn-primary btn-sm">Save</button>
            </div>
        </form>
        </div>
    </div>
</div>

<div class="modal" id="field-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Create Custom Field</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="field-form">
                @csrf
            <div class="modal-body">
                <div id="error-message"></div>
                <div class="form-group mb-2">
                    <label for="">Data Type</label>
                    <select class="form-select" id="data-type-select" name="data_type">
                        <option value="text">text</option>
                        <option value="integer">integer</option>
                        <option value="decimal">decimal</option>
                        <option value="date">date</option>
                        <option value="textarea">text area</option>
                        <option value="picklist">picklist</option>
                    </select>
                </div>
                <div class="form-group mb-2">
                    <label for="">Label Name <span class="text-danger">*</span> </label>
                    <input type="text" class="form-control" required name="label">
                </div>
                {{-- <div class="form-group mb-2" id="form-length">
                    <label for="">Length <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="length">
                </div> --}}
                <div id="data-type-option"></div>
                <div class="form-group mb-2">
                    <label for="">Default Value (<i>optional</i>)</label>
                    <div id="field-default-value">
                        <input type="text" class="form-control" id="default_value" name="default_value">
                    </div>
                </div>
                <h6 class="mt-4">Properties</h6>
                <div class="form-check mb-2">
                    <label for="mandatory" class="form-check-label">Required</label>
                    <input type="checkbox" id="mandatory" name="mandatory" class="form-check-input">
                </div>
                <div class="form-check mb-2">
                    <label for="column" class="form-check-label">Column</label>
                    <input type="checkbox" id="column" name="column" class="form-check-input">
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <a class="btn btn-default" data-bs-dismiss="modal">Cancel</a>
                <button class="btn btn-primary btn-sm">Save</button>
                <input type="hidden" id="block_id" name="block_id">
                <input type="hidden" id="field_id" value="">
            </div>
        </form>
        </div>
    </div>
</div>
@push('script')
    <script>
        
        $(function(){
            var mode = window.location.hash;
            $('.nav-tabs a[href="' + mode + '"]').tab('show');
        })
        
        $('.nav-tabs').on('shown.bs.tab', function(event){
            const href = $(event.target).attr('href').substr(1);
            if(href == "showViewLayout"){
                showViewLayout();
            }
            location.hash = href;
        });
        
       $("#block-content").sortable({
            cursor:"move"
       });
        
        function showViewLayout(){
                var module_ = $("#block-select").val();
                $.ajax({
                    url:"/block/list/"+module_,
                    success:function(data){
                        var content = "";
                        data.map((item,index)=>{
                            var fields = "";
                            
                            item.fields.map((item2,index)=>{

                                fields+=`
                                <div class='col-6 field'>
                                    <div class='card'>
                                        <div class='card-body'>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="card-title">${item2.label}</h6>
                                                ${item2.editable == 1 ? `<span class='bi bi-pen-fill edit' style="cursor:pointer" data-id="${item2.table}_${item2.columnname}"></span>` : `` }
                                            </div>
                                            <h6 class="card-subtitle mb-2 text-muted">Datatype: ${item2.type}</h6>
                                            <h6 class="card-subtitle mb-2 text-muted">Default: ${item2.default == null ? "none" : item2.default }</h6>

                                            <h6 class="mb-2 mt-4">Properties</h6>
                                            <h6 class="card-subtitle mb-2 text-muted">Required: ${item2.mandatory == 1 ? "<span class='badge bg-success'>on</span>" : "<span class='badge bg-danger'>off</span>" }</h6>
                                            <h6 class="card-subtitle mb-2 text-muted">Column: ${item2.column == 1 ? "<span class='badge bg-success'>on</span>" : "<span class='badge bg-danger'>off</span>" }</h6>
                                            <input type="hidden" id="${item2.table}_${item2.columnname}_label" value="${item2.label}" >
                                            <input type="hidden" id="${item2.table}_${item2.columnname}_datatype" value="${item2.type}" >
                                            <input type="hidden" id="${item2.table}_${item2.columnname}_default" value="${item2.default}" >
                                            <input type="hidden" id="${item2.table}_${item2.columnname}_id" value="${item2.id}" >
                                            <input type="hidden" id="${item2.table}_${item2.columnname}_decimals" value="${item2.decimals}" >
                                            <input type="hidden" id="${item2.table}_${item2.columnname}_mandatory" value="${item2.mandatory}" >
                                            <input type="hidden" id="${item2.table}_${item2.columnname}_column" value="${item2.column}" >
                                            <input type="hidden" id="${item2.table}_${item2.columnname}_picklist" value="${item2.type == "picklist" ? JSON.parse(item2.picklist_value) : "" }" >
                                        </div>
                                    </div>
                                </div>
                                `;
                            })

                            fields+=`
                            <div class='col-6 field'>
                            <div class="card">
                                    <div class="card-body  p-2 ">
                                        <div data-bs-toggle="modal" data-block-id="${item.id}" data-bs-target="#field-modal" class="btn-field border p-5 rounded-2 text-center align-items-center" style="font-size:15px;font-weight:bold;cursor:pointer">
                                            <span class="bi bi-plus"></span>Add Field
                                        </div>
                                    </div>
                                </div>
                                </div>
                                `;

                            content+=`
                                    <div class="card">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between">
                                                <h6 >${item.name}</h6>
                                                <button class="btn btn-default btn-sm border shadow shadow-sm btn-field" data-block-id="${item.id}">Add Field</button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="field-content row mt-4">
                                                ${fields}
                                            </div>
                                        </div>
                                    </div>
                            `;
                        })
                        if(content == ""){
                            content=`
                                <div class="card">
                                    <div class="card-body  p-2 ">
                                        <div data-bs-toggle="modal" data-bs-target="#block-modal" class="border p-5 rounded-2 text-center align-items-center" style="font-size:15px;font-weight:bold;cursor:pointer">
                                            <span class="bi bi-plus"></span>Add Block
                                        </div>
                                    </div>
                                </div>
                            `;
                        }
                        $("#block-content").html(content);

                        $(".btn-field").click(function(){
                            var block_id = $(this).data('block-id');
                            $("#block_id").val(block_id);
                            $("#field-modal").modal('show');
                        })
                        $(".edit").click(function(){
                            let id = $(this).data('id');
                            $("input[name='label']").val($("#"+id+"_label").val());
                            $("select[name='data_type']").val($("#"+id+"_datatype").val()).trigger('change');
                            $("input[name='default_value']").val($("#"+id+"_default_value").val());
                            if($("#"+id+"_datatype").val() == "decimal"){
                                $("input[name='decimals']").val($("#"+id+"_decimals").val());
                            }

                            var picklist_array = $("#"+id+"_picklist").val().split(",");
                            $(".select2").select2({
                                data:picklist_array,
                                tags:true

                            })
                            $('.select2').val(picklist_array).trigger('change')
                            
                            
                            var mandatory_value = $("#"+id+"_mandatory").val() == 1 ? true : false;
                            var column_value = $("#"+id+"_column").val() == 1 ? true : false;
                            $("input[name='mandatory']").prop('checked',mandatory_value);
                            $("input[name='column']").prop('checked',column_value);
                            $("#field_id").val($("#"+id+"_id").val())

                            $("input[name='label']").prop('readonly',true);
                            $("input[name='label']").prop('readonly',true);
                            $("select[name='data_type']").prop('disabled',true);
                            // $("input[name='default_value']").prop('readonly',true);
                            $("input[name='decimals']").prop('readonly',true);

                            $("#field-modal").modal('show');
                        })
                        $(".field-content").sortable(
                            {
                                connectWith: ".field-content",
                                revert: true,
                                cursor:"move",
                                tolerance: "pointer",
                                change:function(event,ui){
                                    $("#save-layout-btn").show();
                                }
                 
                            }
                        );
                    }
                })
               
        }
        $("#block-form").on('submit',function(e){
            e.preventDefault();
            $.ajax({
                url:"/field/save-block",
                method:"post",
                data:{
                    module:$("#block-select").val(),
                    name:$("#block-name").val()
                },
                success:function(data){
                    showViewLayout();
                    $("#block-modal").modal('hide');
                }
            })
            
        })
        $("#field-form").on('submit',function(e){
            e.preventDefault();
            $.ajax({
                url:"/field/save-field",
                method:"post",
                data:{
                    block_id:$("#block_id").val(),
                    datatype:$("select[name='data_type']").val(),
                    label:$("input[name='label']").val(),
                    default_value:$("#default_value").val(),
                    mandatory:$("input[name='mandatory']").is(":checked") ? 1 :0,
                    column:$("input[name='column']").is(":checked") ? 1 :0,
                    tabid:$("#block-select").val(),
                    decimals:$("input[name='decimals']").val(),
                    picklist:$("#picklist-select").val(),
                    id:$("#field_id").val()
                },
                success:function(data){
                    showViewLayout();
                    $("#field-modal").modal('hide');
                 
                    
                },
                error:function(err){
                    $("#error-message").html(`
                        <div class='alert alert-danger'>
                            ${err.responseJSON.error}
                        </div>
                    `);
                }

            })
            
        })

        $("#block-select").change(function(){
            showViewLayout();
        })
        $("#field-modal").on('show.bs.modal',function(){
            $("#error-message").html("");
        })
        $("#field-modal").on('hidden.bs.modal',function(){
            $("#field-form")[0].reset();
            $("select[name='data_type']").val('text').trigger('change');
            $("select[name='data_type']").prop('disabled',false);
            $("input[name='label']").prop('readonly',false);
            $("input[name='data_type']").prop('readonly',false);
            $("input[name='default_value']").prop('readonly',false);
            $("input[name='decimals']").prop('readonly',false);
            $("#field_id").val("");
        })
        $("#block-modal").on('hidden.bs.modal',function(){
            $("#block-form")[0].reset();
        })
        $("#data-type-select").change(function(){
            $("#data-type-option").html("");
            if($(this).val() == "decimal"){
                $("#for-decimal").show();
                $("#data-type-option").html(`
                    <div class="form-group mb-2">
                        <label for="">Decimal Places <span class="text-danger">*</span> </label>
                        <input type="text" class="form-control" required name="decimals">
                    </div>
                `);
                $("#field-default-value").html("<input type='text' class='form-control' id='default_value' name='default_value'>");
            }
            else if($(this).val() == "date"){
                $("#field-default-value").html("<input type='date' class='form-control' id='default_value' name='default_value'>");
            }
            else if($(this).val() == "textarea"){
                $("#field-default-value").html("<textarea name='default_value' id='default_value' class='form-control'></textarea>");
            }
            else if($(this).val() == "picklist"){
                $("#data-type-option").html(`
                    <div class="form-group mb-2">
                        <label for="">Picklist Value <span class="text-danger">*</span> </label>
                        <select required class="select2" name="picklist-select" id="picklist-select" multiple style="width:100%">
                            <option value=""></option>
                        </select>
                    </div>
                `);
                $("#field-default-value").html("<select id='default_value' class='form-control' name='default_value'><option value=''>Select an Option</option></select>");
            }
            else{
                $("#field-default-value").html("<input type='text' class='form-control' id='default_value' name='default_value'>");
            }

            $("#picklist-select").select2({
                dropdownParent: $("#field-modal"),
                tags:true
            });
            $("#picklist-select").on("change",function(e){
                const data = $(this).val();
                $("#default_value").html("<option value=''>Select an Option</option>");
                data.map((item)=>{
                    console.log(item)
                    $("#default_value").append("<option value='"+item+"'>"+item+"</option>");
                })
            })

        })

        $("#save-layout-btn").click(function(){
            console.log($(".field"));
        })


        
       
    </script>
@endpush
@endsection
@extends('layout._main',["title"=>"Dashboard Management"])
@section('content')
<section class="section">
   <div class="row">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>Dashboard Management</h4>
            <button data-bs-target="#dashboard-modal" data-bs-toggle="modal" class="btn btn-sm btn-primary">Add Widget</button>
        </div>
        <div class="col-lg-12 grid-margin col-xl-12 stretch-card">
            <div class="card p-2">
                <div class="card-body">
                    <div class="table-reponsive">
                        <table class="table hover pointer" id="dashboard-table">
                            <thead>
                                <tr>
                                    <th>Module</th>
                                    <th>Dashboard Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
   </div>

   <div class="modal" id="dashboard-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> <span id="action-label"></span>Widget</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="dashboard-form" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label for="">Module</label>
                        <select class="form-select" id="module-select" name="module">
                            <option value="">Select an Option</option>
                            @foreach (\App\Models\Tab::all() as $module)
                                <option value="{{$module->id}}">{{$module->name}}</option>
                            @endforeach
                        </select>
                        <div class="form-text text-danger" id="module_validation"></div>
                    </div>

                    <div class="form-group mb-2">
                        <label for="">dashboard Name</label>
                        <input type="text" name="name" id="" class="form-control">
                        <div class="form-text text-danger" id="name_validation"></div>
                    </div>

                    <div class="form-group mb-2">
                        <label for="">Select Option</label>
                        <select class="form-select select2" name="type" id="type" style="width: 100%" >
                        </select>
                        <div class="form-text text-danger" id="type_validation"></div>
                    </div>

                    <div class="form-group mb-2">
                        <label for="">All Conditions (<i>All conditions must be met</i>)</label>
                        <div id="and_content"></div>
                        <a  id="and_add_condition_btn" class="btn btn-sm btn-secondary mt-2" >Add Condition</a>
                    </div>

                    <div class="form-group mb-2">
                        <label for="">Atleast one Conditions (<i>Atleast one conditions must be met</i>)</label>
                        <div id="or_content"></div>
                        <a  id="or_add_condition_btn" class="btn btn-sm btn-secondary mt-2" >Add Condition</a>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <a class="btn btn-default" data-bs-dismiss="modal">Cancel</a>
                    <button class="btn btn-primary btn-sm">Save</button>
                    <input type="hidden" name="id" id="id">
                </div>
            </form>
        </div>
    </div>
   </div>
</section>
@push('script')
<script>
    var table;
    var select_fields=[];
    $(function(){
        
        $(".select2").select2({
            dropdownParent:$("#dashboard-modal"),
            placeholder:"Select an option",
        });
        table = $("#dashboard-table").DataTable({
                ajax:"/dashboard/maintenance/list",
                processing:true,
                serverSide:true,
                autoWitdth:false,
                order:[],
                columns:[
                    {data:'module_.name',name:'module_.name'},
                    {data:'name',name:'name'},
                    {data:'action',name:'action'}
                ]
            }) 
        
    })

    $("#dashboard-form").on('submit',function(e){
        e.preventDefault();
        $("#loader").show();
        $(".form-text").text("");
        $.ajax({
            url:"/dashboard/maintenance",
            method:"post",
            data:$(this).serialize(),
            success:function(data){
                var module_name = $("#module-select option:selected").text();
                table.ajax.reload();
                $("#dashboard-modal").modal("hide");
                $("#loader").hide();
            },
            error:function(err){
                const key = Object.keys(err.responseJSON.errors);
                key.map((item,index)=>{
                    $("#"+item+"_validation").text(err.responseJSON.errors[item][0])
                })
                $("#loader").hide();
            }
        })
    })
    $("#dashboard-table").on('click','.show',function(){
        const tr = $(this).closest('tr');
        const data = table.row(tr).data();
        $("#loader").show();
        $.ajax({
            url:"/dashboard/maintenance/"+data.id,
            data:{pin:1},
            method:"put",
            success:function(data){
                $("#dashboard-table").DataTable().ajax.reload();
                $("#loader").hide();
            }
        })

    })
    $("#dashboard-table").on('click','.hide',function(){
        const tr = $(this).closest('tr');
        const data = table.row(tr).data();
        $("#loader").show();
        $.ajax({
            url:"/dashboard/maintenance/"+data.id,
            data:{pin:0},
            method:"put",
            success:function(data){
                $("#dashboard-table").DataTable().ajax.reload();
                $("#loader").hide();
            }
        })

    })

    $("#dashboard-table").on('click','.delete',function(){

        Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
        }).then((result) => {
        if (result.isConfirmed) {
            const tr = $(this).closest('tr');
            const data = table.row(tr).data();
            $("#loader").show();
            $.ajax({
                url:"/dashboard/maintenance/"+data.id,
                method:"delete",
                success:function(data){
                    $("#dashboard-table").DataTable().ajax.reload();
                    $("#loader").hide();
                }
            })
        }
        });
    })
    $("#dashboard-table").on('click','.edit',function(){
        const tr = $(this).closest('tr');
        const id = table.row(tr).data().id;
        const tabid = table.row(tr).data().tabid;
        const name = table.row(tr).data().name;
        const filters = table.row(tr).data().report_filters_;
        const column = table.row(tr).data().dashboard_.columnname;
        const type = table.row(tr).data().dashboard_.dashboard_type;
        $("input[name='name']").val(name);
        $("#module-select").val(tabid).trigger('change');
        setTimeout(() => {
            // $("#type").val(column).trigger('change');

            // column.map((item)=>{
            //     $("#type option[value='"+item+"']").remove();
            //     var option = new Option(item,item,true,true);
            //     $("#type").append(option);
            //     $("#type").trigger('change');
            // })
        if(type == "count"){
            $("#type").val(type);
            $("#type").trigger('change');
        }
        else{
            $("#type").val(column+"-"+type);
            $("#type").trigger('change');
        }
        $("#and_content").html("");
        $("#or_content").html("");
        filters.map((item,index)=>{

            const operator = item.operator;
            const and_select = item.operator+"-select";
            const and_operator = item.operator+"_operator";
            const and_field = item.operator+"_field[]";
            var select_type_;
            var picklist_value;
            for(var a = 0;a < select_fields.length; a++){
                if(select_fields[a].columnname == item.columnname){
                    select_type_ = select_fields[a].type;
                    picklist_value = select_fields[a].picklist_value;
                    break;
                }
            }

            $("#"+operator+"_content").append(`
            <div class="row mt-2">
                <div class="col-3">
                    <select id="${and_select}" class="form-select ${and_select}" name="${and_field}">
                        ${set_select_fields(item.columnname)}
                    </select>
                </div>
                <div class="col-3">
                    <select class="form-select operator-select" id="" name="${and_operator}[]">
                        ${set_eperator(item.filter_type)}
                    </select>
                </div>
                <div class="col-5">
                    ${set_type_fields(select_type_,operator,JSON.parse(picklist_value),item.filter_value,item.filter_type)}
                </div>
                <div class="col-1">
                    <a style="cursor:pointer" class="text-danger remove_and_condition_btn"><span class="bi bi-trash"></span></a>
                </div>
            </div>
            `);
        })
        and_onchange_and_select();
        or_onchange_and_select();
        }, 500);
        
        $("#id").val(id);
        $("#dashboard-modal").modal('show');

    })

    // On select, place the selected item in order
    // $("#type").on("select2:select", function (evt) {
    //     var element = evt.params.data.element;
    //     var $element = $(element);
    //     $element.detach();
    //     $(this).append($element);
    //     $(this).trigger("change");
    // });

    $("#module-select").change(function(){
        get_fields();
        clear_orand_div();
      
    })
    function clear_orand_div(){
        $("#and_content").html(`
        <div class="row">
            <div class="col-3">
                <select id="and-select" class="form-select and-select" name="and_field[]">
                    ${set_select_fields()}
                </select>
            </div>
            <div class="col-3">
                <select class="form-select operator-select" id="" name="and_operator[]">
                    <option value=''></option>
                </select>
            </div>
            <div class="col-5">
                <input class="form-control" type="text" name='and_value[]' id="">
            </div>
            <div class="col-1"></div>
        </div>
        `);

        $("#or_content").html(`
        <div class="row">
            <div class="col-3">
                <select id="or-select" class="form-select or-select" name="or_field[]">
                    ${set_select_fields()}
                </select>
            </div>
            <div class="col-3">
                <select class="form-select operator-select" id="" name="or_operator[]">
                    <option value=''></option>    
                </select>
            </div>
            <div class="col-5">
                <input class="form-control" name='or_value[]' type="text"  id="">
            </div>
            <div class="col-1"></div>
        </div>
        `);
    }
    var type = ["min","max","sum","avg"];
    function get_fields(){
        $("#type").empty();
        $("#and-select").empty();
        $("#or-select").empty();
        select_fields = [];
        var id = $("#module-select").val();
        if(id != ""){
            $("#loader").show();
            $.get("/report/get_fields/"+id).done(function(data){
                $("#loader").hide();
                const option = new Option("Record Count","count");
                $("#type").append(option)
                data.map((item,index)=>{
                    if(item.type == "integer" || item.type == "decimal"){
                        type.map((item_)=>{
                            const option = new Option(item.label+" ( "+item_+" )",item.columnname+"-"+item_);
                            $("#type").append(option)
                        })
                    }
                    select_fields.push(item);
                    
                })
            $("#and-select").html(set_select_fields());
            $("#or-select").html(set_select_fields());
            })
           
        }
    }
    function set_select_fields(value = ""){
        let sel = "<option value=''>Select an Option</option>";
        select_fields.map((item,index)=>{
            var picklist_value = item.type == "picklist" ? "data-picklist="+item.picklist_value : "";
            var selected = value == item.columnname ? "selected" : "";
            sel+="<option "+selected+" "+picklist_value+" data-type="+item.type+" value="+item.columnname+">"+item.label+"</option>";
        })

        and_onchange_and_select()
        or_onchange_and_select()
        return sel;
    }

    function and_onchange_and_select(){
        $(".and-select").change(function(e){
            let select = $(this).find(":selected");   
            get_operator(e,select,"and");
        }) 

        $(".remove_and_condition_btn").click(function(){
            $(this).closest('.row').remove();
        })
    }

    function or_onchange_and_select(){
        $(".or-select").change(function(e){
            let select = $(this).find(":selected");   
            get_operator(e,select,"or");
        }) 

        $(".remove_or_condition_btn").click(function(){
            $(this).closest('.row').remove();
        })
    }
    $("#dashboard-modal").on('hidden.bs.modal',function(){
        $(".form-text").text("");
        $("#dashboard-form")[0].reset();
        $("#module-select").val("").trigger('change');
        $("#id").val("");
    })

    $("#and_add_condition_btn").click(function(){
        $("#and_content").append(`
            <div class="row mt-2">
                <div class="col-3">
                   <select class="form-select and-select" name="and_field[]">
                        ${set_select_fields()}
                    </select>
                </div>
                <div class="col-3">
                    <select class="form-select operator-select" name="and_operator[]">
                        <option value=''></option>
                    </select>
                </div>
                <div class="col-5">
                    <input type = "text" name="and_value[]" class="form-control">
                </div>
                <div class="col-1 d-flex align-items-center">
                    <a style="cursor:pointer" class="text-danger remove_and_condition_btn"><span class="bi bi-trash"></span></a>
                </div>
            </div>
        `);   
        and_onchange_and_select();
    })

    $("#or_add_condition_btn").click(function(){
        $("#or_content").append(`
            <div class="row mt-2">
                <div class="col-3">
                   <select class="form-select or-select" name="or_field[]">
                        ${set_select_fields()}
                    </select>
                </div>
                <div class="col-3">
                    <select class="form-select operator-select" name="or_operator[]">
                        <option value=''></option>
                    </select>
                </div>
                <div class="col-5">
                    <input type = "text" name="or_value[]" class="form-control">
                </div>
                <div class="col-1 d-flex align-items-center">
                    <a style="cursor:pointer" class="text-danger remove_or_condition_btn"><span class="bi bi-trash"></span></a>
                </div>
            </div>
        `);   
        or_onchange_and_select();
    })

    

    function set_eperator(value){
        var operation = ["equals","not equal to","contains","is empty","is not empty"];
        var option = "";
        operation.map((item)=>{
                let selected = value == item ? "selected" : "";
                option+="<option "+selected+" value='"+item+"'>"+item+"</option>";
        })
        return option;
    }
    
    function get_operator(e,select,operator_){
       let type = select.data("type");
       let row = $(e.target).closest('.row');
       row.find(".operator-select").html(set_eperator());
       if(type == "picklist"){
        let picklist = select.data("picklist");
        row.find(".col-5:nth-child(3)").html(set_type_fields(type,operator_,picklist))
       }
       else{
        row.find(".col-5:nth-child(3)").html(set_type_fields(type,operator_))
       }

       $(".operator-select").change(function(e){
            let val = $(this).val();
            if(val == "is empty" || val == "is not empty"){
                $(e.target).closest('.row').find('.and_value').hide()
                $(e.target).closest('.row').find('.and_value').val("");
            }
            else{
                $(e.target).closest('.row').find('.and_value').show()
            }
            
       })
    }

    function set_type_fields(type,operator_,picklist = "",value = "",is_empty = ""){
        let fields = "";
        var hide = "";
        let name = operator_+"_value[]";
        if(is_empty == "is empty" || is_empty == "is not empty"){
            hide = " style='display:none'";
        }
        if(type == "integer"){
            field = "<input "+hide+" class='form-control and_value' value='"+value+"' type='number' name='"+name+"'>";
        }
        else if(type == "date"){
            field = "<input "+hide+" class='form-control and_value' value='"+value+"' type='date' name='"+name+"'>"; 
        }
        else if(type == "picklist"){
            let option = "<option value=''>Select an Option</option>";
            picklist.map((item)=>{
                let selected = value == item ? "selected" : "";
                option+=`<option ${selected} value=${item}>${item}</option>`;
            })
            field = `
                <select ${hide} class='form-select and_value' name='"+name+"'>
                    ${option}
                </select>
            `;
        }
        else{
            field = "<input "+hide+" value='"+value+"' class='form-control and_value' type='text' name='"+name+"'>"; 
        }
        return field;
    }
</script>
@endpush
@endsection
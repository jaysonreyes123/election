@extends('layout._main',["title"=>$module])
@section('content')
@include('content.breadcrumb',["module"=>$module,"view" => "All", "id" => "","action" => "view"])
@include('content.import',["module" => $module])
<style>
    input[type='checkbox']{
        transform: scale(1.3);
        border: 1px solid #ccc !important;
    }
</style>
<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card p-2">
                <div class="card-body">
                    <div class=" mb-4 mt-2 d-flex justify-content-end align-items-center">
                        <div>
                            <button onclick="clearFilter()" id="clear-filter" class="btn btn-danger btn-sm" style="display:none"><span class="bi bi-x"></span> Clear Filter</button>

                            <button data-bs-toggle="modal" data-bs-target="#filter-modal" class="btn btn-outline-dark btn-sm"><span class="bi bi-funnel"></span> Filter</button>
                         
                            <button onclick="deleteSelectedRow()" id="delete-selected-row" disabled class="btn btn-outline-danger btn-sm"><span class="bi bi-trash"></span></button>
                        </div>
                    </div>
                    <table class="table" id="table" style="width:100%">
                        <thead>
                            <th><center><input type="checkbox" id="filter-select-all" style="cursor: pointer" class="form-check-input"></center></th>
                            @foreach ($field_column as $column)
                                <th>{{$column->label}}</th>
                            @endforeach
                            <th>Action</th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal" id="filter-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Filter</h4>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="filter-form">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-lg-5">
                            <select name="filter_column[]" class="form-select filter-select">
                                <option value="" selected disabled>Select an option</option>
                                @foreach ($field_column as $item)
                                    <option value="{{$item->columnname}}" data-type="{{$item->type}}" data-picklist="{{$item->picklist_value}}">{{$item->label}}</option>
                                @endforeach
                            </select>
                            
                        </div>
                        <div class="col-lg-5">
                            <div class="filter-field-type">
                                <input type="text" name="filter_value[]" class="form-control filter-field">
                            </div>
                        </div>
                        <div class="col-lg-2"></div>
                    </div>
                    <div id="filter-content"></div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <a class="btn btn-secondary btn-sm" onclick="addFilter()">Add Filter</a>
                    <button class="btn btn-primary btn-sm">Save filter</button>
                    <input type="hidden" value="{{$module}}" name="module">
                </div>
            </form>
        </div>
    </div>
</div>
@include('compontent.print')
@push('script')
    <script>
        var fields = @json($field_column);
        var module_ = @json($module);
        columns = [];
        columns.push({data:"id",name:"id",orderable:false})
        fields.map((item,index)=>{
            let columnname = item.columnname;
            columns.push({data:columnname,name:columnname})
        })
        var row_selected = [];
        var table;
        var filter_column = [];
        var filter_value = [];
        columns.push({data:"action",name:"action",orderable:false})
        function load_table(module_,filter_column,filter_value){
            row_selected = [];
            table =  $("#table").DataTable({
                autoWidth:false,
                reponsive:true,
                lengthChage:false,
                bLengthChange : false,
                pageLength:20,
                ajax:{
                    url:"/view/module/list",
                    type:"POST",
                    data:{
                        module:module_,
                        filter_column:filter_column,
                        filter_value:filter_value
                    }
                },
                processing:true,
                serverSide:true,
                columns:columns,
                order:[],
                columnDefs: [
                    {
                        'targets': 0,
                        'searchable': false,
                        'orderable': false,
                        'className': 'dt-body-center',
                        'render': function (data, type, full, meta){
                            return '<input type="checkbox" style="cursor:pointer" class="checkbox form-check-input" name="id[]" value="' + $('<div/>').text(data).html() + '">';
                        }
                    }
                ]
            });
            
        }
        $(function(){
           load_table(module_);
           table.on('draw.dt', function () {
                const total_record = $("#table").DataTable().page.info().recordsTotal;
                $(".checkbox").click(function(){
                    const checkbox_value = parseInt($(this).val());
                        if($(this).is(":checked")){
                            row_selected.push(checkbox_value);
                        }
                        else{
                            const checkbox_index = row_selected.indexOf(checkbox_value);
                            if (checkbox_index > -1) {
                                row_selected.splice(checkbox_index, 1);
                            }
                        }
                        if(total_record == row_selected.length){
                            $("#filter-select-all").prop("checked",true);
                        }
                        else{
                            $("#filter-select-all").prop("checked",false);
                        }
                        if(row_selected.length == 0){
                            $("#delete-selected-row").prop("disabled",true)
                        }
                        else{
                            $("#delete-selected-row").prop("disabled",false)
                        }
                    })
                table.rows().data().map((item)=>{ 
                    if(row_selected.includes(item.id)){
                        $(".checkbox[value="+item.id+"]").prop("checked",true);
                    }   

                });
            });
        })

       
        
        $("#table").on('click','.delete',function(){
            const redirect = $(this).data('redirect');
            let con = confirm("Are you sure you want to delete this data");
            if(con){
                location.href = redirect;
            }
            
        })

        $("#table").on('click','.print',function(){
            const barangay_name = $(this).data('barangay');
            // $.ajax({
            //     url:"/leaders/"+barangay_name,
            //     method:"get",
            //     success:function(data){
            //         document.getElementById('viewer').scr=data
            //         $("#barangay-print-modal").modal('show');
            //     }
            // })
            $("#barangay-print-name").text(barangay_name);
            document.getElementById('viewer').src="/leaders/"+barangay_name;
            $("#barangay-print-modal").modal('show');
        })

        $(".filter-select").change(function(){
            const data = $(this);
            const picklist_value = $(this).find(':selected').data('picklist');
            const type = $(this).find(":selected").data("type");
            data.closest(".row").find(".filter-field-type").html(setField(type,picklist_value))
        })

        function setField(field_type,picklist){
            if(field_type == "text" || field_type == "textarea"){
                return `<input type="text" name="filter_value[]" class="form-control  filter-field">`;
            }
            else if(field_type == "integer"){
                return `<input type="number" name="filter_value[]" class="form-control  filter-field">`;
            }
            else if(field_type == "date"){
                return `<input type="date" name="filter_value[]" class="form-control  filter-field">`;
            }
            else if(field_type == "picklist"){
                let option = "";
                picklist.map((item)=>{
                    option+=`<option>${item}</option>`;
                })
                return `<select name="filter_value[]"  class="form-select  filter-field">${option}</select>`;
            }
        }
        var filter_count = 1;
        function addFilter(){
            if(filter_count >= 3){
                swal.fire({
                    title:"Maximum filter",
                    icon:"error"
                })
                return false;
            }
            $("#filter-content").append(`
                <div class="row mb-3">
                    <div class="col-lg-5">
                        <select name="filter_column[]" class="form-select filter-select">
                            <option value="" selected disabled>Select an option</option>
                            @foreach ($field_column as $item)
                                <option value="{{$item->columnname}}" data-type="{{$item->type}}" data-picklist="{{$item->picklist_value}}">{{$item->label}}</option>
                            @endforeach
                        </select>
                        
                    </div>
                    <div class="col-lg-5">
                        <div class="filter-field-type">
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-2 d-flex align-items-center">
                        <button class="btn btn-danger btn-sm remove-filter"><span class="bi bi-trash"></span></button>
                    </div>
                </div>
            `)

            $(".remove-filter").click(function(){
                $(this).closest(".row").remove();
            })
            $(".filter-select").change(function(){
            const data = $(this);
            const picklist_value = $(this).find(':selected').data('picklist');
            const type = $(this).find(":selected").data("type");
            data.closest(".row").find(".filter-field-type").html(setField(type,picklist_value))
        })
        filter_count++;
        }

        $("#filter-form").on('submit',function(e){
            e.preventDefault();
            clear();
            $()
            $(".filter-select").map((index,item)=>{
                if($(item).find(":selected").val() != ""){
                    filter_column.push($(item).find(":selected").val());
                }   
            })
            $(".filter-field").map((index,item)=>{
                filter_value.push($(item).val());
            })
            $("#table").DataTable().destroy();
            load_table(module_,filter_column,filter_value)
            $("#clear-filter").show();
            $("#filter-modal").modal('hide');
        })
        function clear(){
            filter_column = [];
            filter_value  = [];
            $("#filter-select-all").prop("checked",false);
            $("#delete-selected-row").prop("disabled",true)
        }
        function clearFilter(){
            clear();
            $("#table").DataTable().destroy();
            load_table(module_);
            $("#filter-content").html("");
            $(".filter-select").val("");
            $(".filter-field").val("");
            $("#clear-filter").hide();
        }

        function deleteSelectedRow(){
            const con = confirm("Are you sure do you want to delete this data?");
            if(!con){
                return false;
            }
            $("#loader").show();
            $.ajax({
                url:"/view/multiple_delete",
                method:"post",
                data:{
                    module:module_,
                    row_selected:row_selected
                },
                success:function(data){
                    console.log(data)
                    $("#table").DataTable().ajax.reload();
                    $("#filter-select-all").prop("checked",false);
                    row_selected = [];
                    $("#loader").hide();

                }
                
            })
        }

        $("#filter-select-all").click(function(){
            $("#loader").show();
            if($(this).is(":checked")){
                $.ajax({
                    url:"/filter/selectall",
                    method:"post",
                    data:{
                        module:module_,
                        filter_column:filter_column,
                        filter_value:filter_value
                    },
                    success:function(data){
                        row_selected = data;
                        $(".checkbox").prop("checked",true);
                        $("#delete-selected-row").prop("disabled",false)
                        $("#loader").hide();
                    }
                })
            }
            else{
                setTimeout(() => {
                    row_selected = [];
                    $(".checkbox").prop("checked",false);
                    $("#delete-selected-row").prop("disabled",true)
                    $("#loader").hide();   
                }, 500);
            }
        })
        
    </script>
@endpush
@endsection
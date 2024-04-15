@extends('layout._main',["title"=>"Report"])
@section('content')
<section class="section">
   <div class="row">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>Report</h4>
            <button data-bs-target="#report-modal" data-bs-toggle="modal" class="btn btn-sm btn-primary">Add Report</button>
        </div>
        <div class="col-lg-12 grid-margin col-xl-12 stretch-card">
            <div class="card p-2">
                <div class="card-body">
                    <div class="table-reponsive">
                        <table class="table hover pointer" id="report-table">
                            <thead>
                                <tr>
                                    <th>Report Name</th>
                                    <th>Module</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
   </div>

   <div class="modal" id="report-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> <span id="action-label"></span> Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="report-form" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label for="">Module</label>
                        <select class="form-select" id="module-select" name="module">
                            <option value="">Select an otion</option>
                            @foreach (\App\Models\Tab::all() as $module)
                                <option value="{{$module->id}}">{{$module->name}}</option>
                            @endforeach
                        </select>
                        <div class="form-text text-danger" id="module_validation"></div>
                    </div>

                    <div class="form-group mb-2">
                        <label for="">Report Name</label>
                        <input type="text" name="name" id="" class="form-control">
                        <div class="form-text text-danger" id="name_validation"></div>
                    </div>

                    <div class="form-group mb-2">
                        <label for="">Select Column ( <i>max 15</i> )</label>
                        <select class="form-select select2" id="column-select" style="width: 100%" multiple name="column[]"></select>
                        <div class="form-text text-danger" id="column_validation"></div>
                    </div>

                    <div class="form-group mb-2">
                        <label for="">All condition met</label>
                        <div class="row">
                            <div class="col-3">
                                <select name="" id="and-select" class="form-select and-select">
                                    <option value="">Select an Option</option>
                                </select>
                            </div>
                            <div class="col-3">
                                <input class="form-control" type="text" name="" id="">
                            </div>
                            <div class="col-3">
                                <input class="form-control" type="text" name="" id="">
                            </div>
                            <div class="col-3"></div>
                        </div>
                        <div id="and_content"></div>
                        <a  id="and_add_condition_btn" class="btn btn-sm btn-secondary mt-2" >Add Condition</a>
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
        var select_fields;
        $(function(){
            
            $(".select2").select2({
                dropdownParent:$("#report-modal"),
                placeholder:"Select an option",
                maximumSelectionLength:15,
                closeOnSelect: false,
                tags:true
            });
            table = $("#report-table").DataTable({
                ajax:"/report/list",
                processing:true,
                serverSide:true,
                autoWitdth:false,
                order:[],
                columns:[
                    {data:'name',name:'name'},
                    {data:'module_.name',name:'module_.name'},
                    {data:'action',name:'action'}
                ]
            })
        })

        $("#report-form").on('submit',function(e){
            e.preventDefault();
            $("#loader").show();
            $(".form-text").text("");
            $.ajax({
                url:"/report/save",
                method:"post",
                data:$(this).serialize(),
                success:function(data){
                    var module_name = $("#module-select option:selected").text();
                    table.ajax.reload();
                    $("#loader").hide();
                    $("#report-modal").modal('hide');
                    location.href="/report/"+module_name+"/"+data.id;
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

        $("#report-table").on('click','.edit',function(){
            const tr = $(this).closest('tr');
            const id = table.row(tr).data().id;
            const tabid = table.row(tr).data().tabid;
            const name = table.row(tr).data().name;
            const columns = table.row(tr).data().column;
            const column = columns.split(",");
            $("input[name='name']").val(name);
            $("#module-select").val(tabid).trigger('change');
            setTimeout(() => {
                // $("#column-select").val(column).trigger('change');

                column.map((item)=>{
                    $("#column-select option[value='"+item+"']").remove();
                    var option = new Option(item,item,true,true);
                    $("#column-select").append(option);
                    $("#column-select").trigger('change');
                })
            }, 500);
            
            $("#id").val(id);
            $("#report-modal").modal('show');

        })

        // On select, place the selected item in order
        $("#column-select").on("select2:select", function (evt) {
            var element = evt.params.data.element;
            console.log(element)
            var $element = $(element);
            $element.detach();
            $(this).append($element);
            $(this).trigger("change");
        });

        $("#module-select").change(function(){
            get_fields();
            $("#and_content").html("")
            
        })
        function get_fields(){
            $("#column-select").empty();
            $("#and-select").empty();
            select_fields = "<option value=''>Select an Option</option>";
            var id = $("#module-select").val();
            if(id != ""){
                $("#loader").show();
                $.get("/report/get_fields/"+id).done(function(data){
                    $("#loader").hide();
                    data.map((item,index)=>{
                        const option = new Option(item.label,item.columnname);
                        select_fields+="<option value="+item.columnname+">"+item.label+"</option>";
                        $("#column-select").append(option)
                    })
                    $("#and-select").html(select_fields);
                })
            }
        }
        $("#report-modal").on('hidden.bs.modal',function(){
            $(".form-text").text("");
            $("#report-form")[0].reset();
            $("#module-select").val("").trigger('change');
            $("#id").val("");
        })

        $("#and_add_condition_btn").click(function(){
            $("#and_content").append(`
                <div class="row mt-2">
                    <div class="col">
                       <select class="form-select and-select">
                            <option value="">Select an Option</option>
                            ${select_fields}
                        </select>
                    </div>
                    <div class="col">
                        <input type = "text" class="form-control">
                    </div>
                    <div class="col">
                        <input type = "text" class="form-control">
                    </div>
                    <div class="col d-flex align-items-center">
                        <a style="cursor:pointer" class="text-danger remove_and_condition_btn"><span class="bi bi-trash"></span></a>
                    </div>
                </div>
            `);

            $(".remove_and_condition_btn").click(function(){
                $(this).closest('.row').remove();
            })
        })
        
    </script>
@endpush
@endsection
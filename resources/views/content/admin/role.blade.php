@extends('layout._main',["title"=>"Role"])
@section('content')
<section class="section">
   <div class="row">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>Role</h4>
            <button data-bs-target="#roles-modal" data-bs-toggle="modal" class="btn btn-sm btn-primary">Add roles</button>
        </div>
        <div class="col-lg-12 grid-margin col-xl-12 stretch-card">
            <div class="card p-2">
                <div class="card-body">
                    <div class="table-reponsive">
                        <table class="table hover pointer" id="roles-table">
                            <thead>
                                <tr>
                                    <th>Role Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
   </div>

   <div class="modal" id="roles-modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> <span id="action-label"></span> Roles</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="roles-form" method="post">
                @csrf
                <div class="modal-body">
                    <div id="error-div"></div>
                    <div class="form-group mb-2">
                        <label for="">Role Name</label>
                        <input type="text" name="role" id="rolename" class="form-control">
                        <div  class="form-text text-danger validation"  id="role_validation"></div>
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
        $(function(){
           table = $("#roles-table").DataTable({
                ajax:"/roles/list",
                autoWidth:false,
                serverSide:true,
                processing:true,
                order:[],
                columns:[
                    {data:"name",name:"name"},
                    {data:"action",name:"action",orderable:false}
                ],
            });  
        })

        $("#roles-form").on('submit',function(e){
            e.preventDefault();
            $(".validation").text("");
            $.ajax({
                url:"/roles/save",
                method:"post",
                data:{
                    role:$("#rolename").val(),
                    id:$("#id").val()
                },
                success:function(){
                    table.ajax.reload();
                    $("#roles-modal").modal('hide');
                },
                error:function(err){
                    const key = Object.keys(err.responseJSON.errors);
                    key.map((item,index)=>{
                        $("#"+item+"_validation").text(err.responseJSON.errors[item][0])
                    })
                }
            })
        })
        $("#roles-table").on('click','.edit',function(){
            const tr = $(this).closest('tr');
            const id = table.row(tr).data().id;
            const rolename = table.row(tr).data().name;

            $("#rolename").val(rolename);
            $("#id").val(id);
            $("#roles-modal").modal('show');
            
        })
        $("#roles-modal").on('hidden.bs.modal',function(){
            $(".validation").text("");
            $("#rolename").val("");
            $("#id").val("");
        })
     </script>
@endpush

@endsection
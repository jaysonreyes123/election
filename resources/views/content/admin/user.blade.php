@extends('layout._main',["title"=>"User"])
@section('content')
<section class="section">
   <div class="row">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>User</h4>
            <button data-bs-target="#users-modal" data-bs-toggle="modal" class="btn btn-sm btn-primary">Add Users</button>
        </div>
        <div class="col-lg-12 grid-margin col-xl-12 stretch-card">
            <div class="card p-2">
                <div class="card-body">
                    <div class="table-reponsive">
                        <table class="table hover pointer" id="users-table">
                            <thead>
                                <tr>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
   </div>

   <div class="modal" id="users-modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> <span id="action-label"></span> Users</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="users-form" method="post">
                @csrf
                <div class="modal-body">
                    <div id="error-div"></div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="">Firstname</label>
                                <input type="text" name="firstname" id="" class="form-control">
                                <div  class="form-text text-danger validation" id="firstname_validation"></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="">Lastname</label>
                                <input type="text" name="lastname" id="" class="form-control">
                                <div  class="form-text text-danger validation" id="lastname_validation"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="">Username</label>
                                <input type="text" name="username" id="" class="form-control">
                                <div  class="form-text text-danger validation" id="username_validation"></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-2">
                                <label for="">Email</label>
                                <input type="text" name="email" id="" class="form-control">
                                <div  class="form-text text-danger validation"  id="email_validation"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-2">
                        <label>Password</label>
                        <input type="password" name="password" id="" class="form-control">
                        <div  class="form-text text-danger validation"  id="password_validation"></div>
                    </div>
                    <div class="form-group mb-2">
                        <label>Role</label>
                        <select name="role" id="" class="form-select">
                            @foreach (\App\Models\Role::all() as $model )
                                <option value="{{$model->id}}">{{$model->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label>Status</label>
                        <select name="status" id="" class="form-select">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
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
           table = $("#users-table").DataTable({
                ajax:"/users/list",
                autoWidth:false,
                serverSide:true,
                processing:true,
                order:[],
                columns:[
                    {data:"firstname",name:"firstname"},
                    {data:"lastname",name:"lastname"},
                    {data:"username",name:"username"},
                    {data:"email",name:"email"},
                    {data:"roles_.name",name:"roles_.name"},
                    {data:"status_",name:"status_",orderable:false},
                    {data:"action",name:"action",orderable:false}
                ],
            });  
        })

        $("#users-form").on('submit',function(e){
            e.preventDefault();
            $(".validation").text("");
            $.ajax({
                url:"/users/save",
                method:"post",
                data:$(this).serialize(),
                success:function(data){
                    $("#users-form")[0].reset();
                    table.ajax.reload();
                    $("#users-modal").modal('hide');
                },
                error:function(err){
                    const key = Object.keys(err.responseJSON.errors);
                    key.map((item,index)=>{
                        $("#"+item+"_validation").text(err.responseJSON.errors[item][0])
                    })
                }
            }) 
        })
        $("#users-modal").on('hidden.bs.modal',function(){
            $("#users-form")[0].reset();
            $("#id").val("");
            $(".validation").text("");
        })
        $("#users-table").on('click','.edit',function(){
            const tr = $(this).closest('tr');
            const id = table.row(tr).data().id;
            const firstname = table.row(tr).data().firstname;
            const lastname = table.row(tr).data().lastname;
            const username = table.row(tr).data().username;
            const email = table.row(tr).data().email;
            const status = table.row(tr).data().status;
            const role = table.row(tr).data().role;
            $("#id").val(id);
            $("input[name='firstname']").val(firstname);
            $("input[name='lastname']").val(lastname);
            $("input[name='username']").val(username);
            $("input[name='email']").val(email);
            $("input[name='lastname']").val(lastname);
            $("select[name='role']").val(role);
            $("select[name='status']").val(status);
            $("#users-modal").modal('show');
        })
        
     </script>
@endpush

@endsection
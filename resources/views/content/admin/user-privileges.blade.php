@extends('layout._main',["title"=>"User Privileges"])
@section('content')
<section class="section">
   <div class="row">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>User Privileges</h4>
        </div>
        <div class="col-lg-12 grid-margin col-xl-12 stretch-card">
            <div class="card p-2">
                <div class="card-body">
                    <div class="card-title">
                        <div class="col-6 col-lg-3">
                            <div class="form-floating shadow shadow-sm">
                                <select name="" class="form-select" id="role-select">
                                    @foreach (\App\Models\Role::all() as $roles )
                                        <option value="{{$roles->id}}">{{$roles->name}}</option>
                                    @endforeach
                                </select>
                                <label for="">Select Role</label>
                            </div>
                        </div>
                        
                    </div>
                    <div class="table-reponsive">
                        <table class="table hover pointer" id="user-privileges-table">
                            <thead>
                                <tr>
                                    <th>Module</th>
                                    <th>Create</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                    <th>Import</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
   </div>

   <div class="modal" id="user-privileges-modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> <span id="action-label" style="text-transform: uppercase"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="user-privileges-form" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="create" >
                        <label class="form-check-label" for="create">
                          Create
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="edit" >
                        <label class="form-check-label" for="edit">
                          Edit
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="delete" >
                        <label class="form-check-label" for="delete">
                          Delete
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="import" >
                        <label class="form-check-label" for="import">
                          Import
                        </label>
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
           var role = $("#role-select").val();
           table = $("#user-privileges-table").DataTable({
                ajax:"/user-privileges/list/"+role,
                autoWidth:false,
                serverSide:true,
                processing:true,
                order:[],
                columns:[
                    {data:"tabs_.name",name:"tabs_.name"},
                    {data:"create_",name:"create_"},
                    {data:"edit_",name:"edit_"},
                    {data:"delete_",name:"delete_"},
                    {data:"import_",name:"import_"},
                    {data:"action",name:"action",orderable:false}
                ],
            });  
        })

        $("#user-privileges-form").on('submit',function(e){
            e.preventDefault();
            $.ajax({
                url:"/user-privileges/save",
                method:"post",
                data:{
                    create:$("#create").is(":checked") ? 1 :0,
                    edit:$("#edit").is(":checked") ? 1 :0,
                    delete_:$("#delete").is(":checked") ? 1 :0,
                    import_:$("#import").is(":checked") ? 1 :0,
                    id:$("#id").val()

                },
                success:function(){
                    table.ajax.reload();
                    $("#user-privileges-modal").modal('hide');
                }
            })
        })

        $("#user-privileges-table").on('click','.edit',function(){
            const tr = $(this).closest('tr');
            const id = table.row(tr).data().id;
            const create = table.row(tr).data().create == 0 ? false : true;
            const edit = table.row(tr).data().edit == 0 ? false : true;
            const delete_ = table.row(tr).data().delete == 0 ? false : true;
            const import_ = table.row(tr).data().import == 0 ? false : true;
            const name = table.row(tr).data().tabs_.name == 0 ? false : true;

            $("#create").prop("checked",create);
            $("#edit").prop("checked",edit);
            $("#delete").prop("checked",delete_);
            $("#import").prop("checked",import_);
            $("#id").val(id);
            $("#action-label").text($("#role-select option:selected").text()+" - "+name);
            $("#user-privileges-modal").modal('show');
        });

        $("#user-privileges-modal").on('hidden.bs.modal',function(){
            $("#create").prop("checked",false);
            $("#edit").prop("checked",false);
            $("#delete").prop("checked",false);
            $("#import").prop("checked",false);
            $("#id").val("");
        })

        $("#role-select").change(function(){
            table.ajax.url('/user-privileges/list/'+$(this).val()).load(null);
        })
     </script>
@endpush

@endsection
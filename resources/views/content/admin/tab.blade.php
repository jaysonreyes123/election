@extends('layout._main',["title"=>"Module"])
@section('content')
<section class="section">
   <div class="row">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4>Module</h4>
            <div>
                <button data-bs-target="#sort-modal" data-bs-toggle="modal" class="btn btn-sm btn-primary">Sort Module</button>
                <button data-bs-target="#tab-modal" data-bs-toggle="modal" class="btn btn-sm btn-primary">Add Module</button>
            </div>
            
        </div>
        <div class="col-lg-12 grid-margin col-xl-12 stretch-card">
            <div class="card p-2">
                <div class="card-body">
                    <div class="table-reponsive">
                        <table class="table hover pointer" id="tab-table">
                            <thead>
                                <tr>
                                    <th>Module Name</th>
                                    {{-- <th>Status</th> --}}
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
   </div>

   <div class="modal" id="sort-modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> Sort Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sort-module-form">
            <div class="modal-body">
                <ul class="list-group">
                    @foreach (\App\Models\Tab::orderBy('sort','asc')->get() as $tab )
                        <li data-module-id="{{$tab->id}}" class="list-group-item sort-tab ">
                          <span class="bi bi-list"></span>  {{$tab->label}}
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <a class="btn btn-default" data-bs-dismiss="modal">Cancel</a>
                <button class="btn btn-primary btn-sm">Save</button>
            </div>
            </form>
        </div>
    </div>
   </div>

   <div class="modal" id="tab-modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> <span id="action-label"></span> Module</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="tab-form" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-2">
                        <label for="">Module Name</label>
                        <input type="text" name="name" id="" required class="form-control">
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
           table = $("#tab-table").DataTable({
                ajax:"/tab/list",
                autoWidth:false,
                serverSide:true,
                processing:true,
                order:[],
                columns:[
                    {data:"label",name:"label"},
                    {data:"status",name:"status",orderable:false},
                    {data:"action",name:"action",orderable:false}
                ],
            });  
            
            $("#tab-table tbody").on('click','.edit',function(e){
                const tr = $(this).closest('tr');
                const id = table.row(tr).data().id;
                const name = table.row(tr).data().label;
                const status_ = table.row(tr).data().status;
                const status = $(status_).text() == 'Active' ? 1 : 0;
                $("input[name='id']").val(id);
                $("input[name='name']").val(name);
                $("select[name='status']").val(status);
                $("#tab-modal").modal('show');
            })

            

            $("#tab-table tbody").on('click','.delete',function(e){
                const tr = $(this).closest('tr');
                const id = table.row(tr).data().id;
                let con = confirm("Are you sure you want to delete this data");
                if(con){
                    $.ajax({
                        url:"/tab/delete/"+id,
                        method:"get",
                        success:function(data){
                            table.ajax.reload();
                        },
                    })
                }
            })
            
        })

        $("#tab-form").submit(function(e){
            e.preventDefault();
            $.ajax({
                url:"/tab/save",
                method:"post",
                data:$(this).serialize(),
                success:function(data){
                    $("#tab-modal").modal('hide'); 
                    table.ajax.reload();
                },
                error:function(err){
                    console.log(err);
                }
            })
            
        })

       
        $("#tab-modal").on('show.bs.modal',e=>{
            if(e.relatedTarget == undefined){
                $("#action-label").text("Edit");
            }
            else{
                $("#action-label").text("Add");
            }
        })

        $("#tab-modal").on('hidden.bs.modal',function(){
            $("input[name='name']").val("");
            $("select[name='status']").val(1);
        })

        $(".list-group").sortable({
            cursor:"move",
            scrollSpeed: 500,
            scrollSensitivity:20,
            change:function(event,ui){
                $("#save-layout-btn").show();
            }
       });
       $("#sort-module-form").on('submit',function(e){
        e.preventDefault();
        var sort_tab = [];
        $(".sort-tab").each(function(i,item){
            sort_tab.push($(item).data('module-id'))
        })
        $("#loader").show();
        $.ajax({
            url:"/tab/sort",
            method:"post",
            data:{sort_tab:sort_tab},
            success:function(data){
                $("#loader").hide();
                location.reload();
            }
        })
       })
     </script>
@endpush

@endsection
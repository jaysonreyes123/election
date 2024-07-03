@extends('layout._main',["title"=>$module])
@section('content')
@include('content.breadcrumb',["module"=>$module,"view" => "All", "id" => "","action" => "view"])
@include('content.import',["module" => $module])
<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card p-2">
                <div class="card-body">
                    <table class="table" id="table" style="width:100%">
                        <thead>
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
@include('compontent.print')
@push('script')
    <script>
        var fields = @json($field_column);
        var module_ = @json($module);
        console.log(module_)
        columns = [];
        fields.map((item,index)=>{
            let columnname = item.columnname;
            columns.push({data:columnname,name:columnname})
        })
        var table;
        columns.push({data:"action",name:"action",orderable:false})
        $(function(){
           table =  $("#table").DataTable({
                autoWidth:false,
                reponsive:true,
                ajax:"/view/module/list/"+module_,
                processing:true,
                serverSide:true,
                columns:columns,
                order:[],
                columnDefs: [{ width: '20%', targets: 0 }],
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


        
    </script>
@endpush
@endsection
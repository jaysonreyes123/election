<div class="modal" id="import-modal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import {{$module}}</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="bs-stepper" id="stepper1">
                    <div class="bs-stepper-header" id="import-step" role="tablist">
                      <!-- your steps here -->
                      <div class="step active" data-target="#upload-part">
                        <button type="button" disabled class="step-trigger" role="tab">
                          <span class="bs-stepper-circle">1</span>
                          <span class="bs-stepper-label">Upload Exce/Csv</span>
                        </button>
                      </div>
                      <div class="line"></div>
                      <div class="step" data-target="#fieldmapping-part">
                        <button type="button" disabled class="step-trigger" role="tab">
                          <span class="bs-stepper-circle">2</span>
                          <span class="bs-stepper-label">Field Mapping</span>
                        </button>
                      </div>
                    </div>
                    <div class="bs-stepper-content" id="import-content">
                      <!-- your steps content here -->
                      <div id="upload-part"  class="content active" role="tabpanel" >
                        <div class="row">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Import from Excel/Csv file</h6>
                                    <div class="row">
                                        <div class="form-group mb-3">
                                            <label for="" class="form-label">Select Exce/Csv file</label>
                                            <input type="file" class="form-control" id="import-file">
                                        </div>
                                        <div class="form-group mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" id="hasheader" >
                                                <label class="form-check-label" for="hasheader">
                                                  Has Header
                                                </label>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex justify-content-center align-items-baseline">
                                <a href="javascript:void(0)" data-bs-dismiss="modal" class="text-danger">Cancel</a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <button class="btn btn-sm btn-success px-4" onclick="nextStep()">Next</button>
                            </div> 
                        </div>
                      </div>
                      <div id="fieldmapping-part" class="content" role="tabpanel">
                        <div class="row">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="card-title">Map the Columns</h6>
                                    <div id="fieldmapping-content"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="d-flex justify-content-center align-items-baseline">
                                <a href="javascript:void(0)" data-bs-dismiss="modal" class="text-danger">Cancel</a>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <button class="btn btn-sm btn-success px-4" onclick="importBtn()">Import</button>
                            </div>
                        </div>
                        
                      </div>
                    </div>
                  </div>
            </div>
        </div>
    </div>
</div>
@push('script')

{{-- stepper --}}

<script>
    $("#import-modal").on('hidden.bs.modal',function(){
        $("#import-content .content").removeClass("active")
        $("#import-content .content").removeClass("dstepper-block")
        $("#import-step .step").removeClass("active")

        $("#import-step .step:first-child").addClass("active")
        $("#import-content .content:first-child").addClass("active")

        $("#import-file").val("");
        $("#hasheader").prop("checked",false);
    })

    $("#import-file").change(function(e){
        const files = $(this)[0].files;
        const ext = files[0].name.split(".").pop();
        if(ext != "xlsx" && ext != "csv"){
            swal.fire({
                icon:"error",
                title:"Xsls and Csv allowed"
            })
            $("#import-file").val("");
        }
    })

    function nextStep(){
        
        const file = $("#import-file")[0].files[0];
        if(file === undefined){
            swal.fire({
                icon:"error",
                title:"Select Exce/Csv file"
            })
            return false;
        }
        $("#loader").show();
        const modules = @json($module);
        const hasheader = $("#hasheader").is(":checked") ? 1 : 0;
        var form = new FormData();
        form.append("import_file",file);
        form.append("module",modules);
        form.append("hasheader",hasheader);
        $.ajax({
            url:"/import-step1",
            method:"post",
            contentType:false,
            processData:false,
            data:form,
            success:function(data){
                $("#fieldmapping-content").html(data);
                stepper1.next();
                $("#loader").hide();
            },
            error:function(err){
                swal.fire({
                icon:"error",
                title:"Something wrong please try again"
            })
            }
        })
    }

    function importBtn(){
        
        var select_value = [];
        var duplicate = [];
        var required_field = $("#required_field").val().split(",");
        $(".import-select :selected").map((index,item)=>{
            let val = $(item).val();
            require_field_position = required_field.indexOf(val);

            //check if selected value is more than 1
            //duplicate
            if($(item).val() != ""){
                if(select_value.includes($(item).val())){
                    let text = $(item).text();                    
                    duplicate.push(text);
                }
            }
            //check required field 
            if(require_field_position >= 0){
                required_field.splice(require_field_position,1);
            }
            select_value.push(val);
        })
        if(required_field.length !=0){
            var fields = required_field.join(",");
            swal.fire({
                icon:"error",
                title:"Field mapped required field "+fields
            })
            return false
        }
        if(duplicate.length !=0){
            var fields = duplicate.join(",");
            swal.fire({
                icon:"error",
                title:"Field mapped more than once "+fields
            })
            return false
        }

        var form = new FormData();
        const hasheader = $("#hasheader").is(":checked") ? 1 : 0;
        const file = $("#import-file")[0].files[0];
        var modules = @json($module);
        form.append("fields",select_value);
        form.append("module",modules);
        form.append("import_file",file);
        form.append("hasheader",hasheader)
        $("#loader").show();
        $.ajax({
            url:"/import/save",
            method:"post",
            data:form,
            processData:false,
            contentType:false,
            success:function(data){
                $("#table").DataTable().ajax.reload();
                $("#import-modal").modal('hide');
                $("#loader").hide();
            },
            error:function(err){
                swal.fire({
                icon:"error",
                title:"Something wrong please try again"
            })
                $("#loader").hide();
            }
        })
    }
    var stepper1Node = document.querySelector('#stepper1')
    var stepper1 = new Stepper(document.querySelector('#stepper1'))
    
</script>
@endpush

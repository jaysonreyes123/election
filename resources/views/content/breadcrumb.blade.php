<div class="pagetitle">
  
  <nav class="d-flex">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a  style="text-transform: capitalize" href="/view/module/{{$module}}">{{$module}}</a></li>
      @if ($action == "detail")
        <li class="breadcrumb-item"><a href="/view/module/{{$module}}">{{$view}}</a></li>
        <li class="breadcrumb-item active">{{$id}}</li>
      @elseif ($action == "edit")
      <li class="breadcrumb-item active">{{$id == ""? "Adding" : "Editing: ".$id}}</li>
      @else
      @endif
      
    </ol>
    @if ($action == "detail")
      <div class="ms-auto">
        @php
            $moduleid = \App\Helper\ModuleHelper::getModuleID($module);
            $user_privileges = \App\Helper\UserPrivilegesHelper::getUserPrivileges($moduleid);
        @endphp
        @if ($user_privileges->create == 1)
            <a href="/edit/module/{{$module}}/" class="btn btn-default border px-4 bg-white shadow shadow-sm"> <span class="bi bi-plus"></span> Add</a>
        @endif
        @if ($user_privileges->edit == 1)
            <a href="/edit/module/{{$module}}/{{$id}}"  class="btn btn-default border px-4 bg-white shadow shadow-sm"> <span class="bi bi-pen"></span> Edit</a>
        @endif
        
        @if ($moduleid == 1)
        @php
          $module_key = \App\Helper\ModuleHelper::getModuleKey($module);
          $barangay_model = \App\Models\Barangay::where($module_key,$id)->first();
        @endphp
        <a title='Print' href='javascript:void(0)'class="btn btn-default border px-4 bg-white shadow shadow-sm" data-value="{{$barangay_model->name}}" id="print-barangay" ><span class='bi bi-file-earmark text-success p-2'></span>Print report map</a>
        @endif
        
        {{-- @if ($user_privileges->import == 1)
            <button data-bs-target="#import-modal" data-bs-toggle="modal" class="btn btn-default border px-4 bg-white shadow shadow-sm"><span class="bi bi-upload"></span> Import</button>
        @endif --}}
      </div>
    @elseif ($action == "view")
    <div class="ms-auto">
        @if ($user_privileges->create == 1)
            <a href="/edit/module/{{$module}}/" class="btn btn-default border px-4 bg-white shadow shadow-sm"> <span class="bi bi-plus"></span> Add</a>
        @endif
        @if ($user_privileges->import == 1)
            <button data-bs-toggle="modal" data-bs-target="#import-modal" class="btn btn-default border px-4 bg-white shadow shadow-sm"><span class="bi bi-upload"></span> Import</button>
        @endif
    </div>
    @endif
  </nav>
</div> 
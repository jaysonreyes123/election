@if ($fieldtype == "picklist")
<select class="form-control" style="width: 250px" name="{{$columnname}}">
    <option value="">Select an Option</option>
    @foreach (json_decode($field->picklist_value) as $picklist)
        @if ($id == "")
            <option value="{{$picklist}}" {{$picklist == $field->default ? "selected" : "" }}>{{$picklist}}</option> 
        @else
            <option value="{{$picklist}}" {{$picklist == $value ? "selected" : "" }}>{{$picklist}}</option> 
        @endif
    @endforeach
</select>
@elseif ($columnname == "barangay_name")
<select class="form-control" style="width: 250px" name="{{$columnname}}">
    <option value="">Select an Option</option>
    @foreach (\App\Models\Barangay::where("is_delete",0)->orderBy('name','asc')->get() as   $barangay )
        @if ($id == "")
            <option value="{{$barangay->name}}" {{$barangay->name == $field->default ? "selected" : "" }}>{{$barangay->name}}</option> 
        @else
            <option value="{{$barangay->name}}" {{$barangay->name == $value ? "selected" : "" }}>{{$barangay->name}}</option> 
        @endif
    @endforeach
</select>
@elseif ($columnname == "barangay")
<select class="form-control" style="width: 250px" name="{{$columnname}}">
    <option value="">Select an Option</option>
    @foreach (\App\Models\Barangay::where("is_delete",0)->orderBy('name','asc')->get() as   $barangay )
        @if ($id == "")
            <option value="{{$barangay->name}}" {{$barangay->name == $field->default ? "selected" : "" }}>{{$barangay->name}}</option> 
        @else
            <option value="{{$barangay->name}}" {{$barangay->name == $value ? "selected" : "" }}>{{$barangay->name}}</option> 
        @endif
    @endforeach
</select>
@elseif ($fieldtype == "text")
<input style="width: 250px"  type="text" name="{{$columnname}}" class="form-control" value="{{$value}}">
@elseif ($fieldtype == "integer")
<input style="width: 250px"  type="number" name="{{$columnname}}" class="form-control" value="{{$value}}">
@elseif ($fieldtype == "date")
<input style="width: 250px"  type="date" name="{{$columnname}}" class="form-control" value="{{$value}}">
@elseif ($fieldtype == "file")
<label for="{{$columnname}}_file">
    <span class="form-control " style="min-width: 250px;cursor: pointer;" id="{{$columnname}}_file_name">{{$value == "" ? "Upload file" : $value}}</span>
</label>
<input style="width: 250px;display:none;" id="{{$columnname}}_file" type="file"  class="form-control files" data-file={{$columnname}} value="{{$value}}">
<input style="width: 250px"  type="hidden" name="{{$columnname}}" class="form-control" value="{{$value}}">
@elseif ($fieldtype == "textarea")
<textarea name="{{$columnname}}" class="form-control" cols="30" rows="2">{{$value}}</textarea>
@endif


<style>
    body{
            font-family: Calibri, sans-serif;       
            font-size: 12px;     
    }
    .page-break {
            page-break-after: always;
    }
    table{
        width: 50%;
        border-collapse: collapse;
    }
    table,td,tr,th{
        border: 1px solid #000000;
        padding: 10px;
    }
    th{
        text-align: left;
    }
    .barangay_map_image{
        width: 150px;
        height: 150px;
        margin-bottom: 20px;
    }
</style>
@if (!empty($table1))
    


<center><img class="barangay_map_image" src="{{public_path("attachment/".$barangay_map)}}" alt=""></center>
<table>
    <tr>
        <th>{{strtoupper($barangay)}}</th>
        <th></th>
    </tr>
@foreach ($table1 as $key => $val )
    <tr>
        <td style="text-transform: capitalize">{{$key}}</td>
        <td>{{$val}}</td>
    </tr>
@endforeach
</table>
@endif

@if (!empty($table2))
<table>
@foreach ($table2 as $key => $val )
    <tr>
        <td style="text-transform: capitalize">{{$key}}</td>
        <td>{{$val}}</td>
    </tr>
@endforeach
</table>
@endif
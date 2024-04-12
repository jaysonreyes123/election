<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<style>
    @page{
        width: 100%;
        font-size: 10px;
    }
    table,tr,td,th{
        width: 100% !important;
        border: 1px solid #ccc;
    }
    table{
        border-collapse: collapse;
    }
</style>
<body>
    <table style="width: 100%;table-layout: fixed">
        <tr>
            @foreach ($header_label as $header )
                <th style="font-weight: bold">{{$header}}</th>
            @endforeach
        </tr>
        <tbody>
            @foreach ($report_model as $data)
                <tr>
                @foreach ($headers as $header )
                    <td>{{$data->$header}}</td>
                @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
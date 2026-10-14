<!DOCTYPE html>
<html>

<head>
    <title>Stock Count Sheet</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <style>
        .RowHeader {
            display: table;
            width: 100%; /*Optional*/          
        }
        .Column1 {
            display: table-cell;
            width: 20%;
        }
        .Column2 {
            display: table-cell;
            width: 60%;
        }
        .Column3 {
            display: table-cell;
            width: 20%;
        }

        h1, h2, h3, h4, h5, h6 {
            margin-top:15;
            margin-bottom:15;
        }
    </style>
</head>

<body>
    <div class="RowHeader">
        <div class="Column1">
                @php
                    $path = public_path('/logo/'. getSettings('logo')) ;
                    $fileExists = File::exists($path);
                @endphp
                @if ($fileExists)
                    <img src="{{public_path('/logo/'. getSettings('logo'))}}"  height="40" width="80" >
                @endif
        </div>
        <div class="Column2">
            <h2 align="center" style="color:SteelBlue;" > {{ getSettings('business_name') }}  </h2>
        </div>
        <div class="Column3">
        </div>
    
    </div>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stock Opname</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th{
            font-weight: 600;
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            margin-top: 20px;
        }
        table td {
            text-align: center;
        }
        table,
        table td,
        table th {
            border: 1px solid black;
            padding: 5px;
        }

        .title {
            text-align: center;
            padding: 1rem 0rem;
        }

        /**
            Set the margins of the page to 0, so the footer and the header
            can be of the full height and width !
            **/
            @page {
            margin: 0cm 0cm;
        }

        /** Define now the real margins of every page in the PDF **/
        body {
            margin-top: 3cm;
            margin-left: 1cm;
            margin-right: 1cm;
            margin-bottom: 2cm;
        }

        /** Define the header rules **/
        header {
            position: fixed;
            top: 8px;
            left: 8px;
            right: 8px;
            height: 2cm;
        }

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 2cm;
        }
    </style>
</head>

<body>
    <header>
        <img src="{{ asset('storage/'.($header ?? '') ) }}" width="100%" height="100%"/>
    </header>

    <footer>
        <img src="{{ asset('storage/'.($footer ?? '') ) }}" width="100%" height="100%"/>
    </footer>
    <h3 class="title">{{ $title }}</h3>
    <table id="content">
        <tr>
            <th>#</th>
            <th width="10%">Nama Barang</th>
            <th>Stok</th>
            <th>Unit</th>
            <th>Brand</th>
            <th>Type</th>
        </tr>
        @foreach ($data as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ number_format(floatval($item->stock)) }}</td>
            <td>{{ $item->unit_name }}</td>
            <td>{{ $item->brand  ?? '-' }}</td>
            <td>{{ $item->type }}</td>
        </tr>
        @endforeach
    </table>
</body>

</html>

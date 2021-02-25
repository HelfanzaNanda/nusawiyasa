<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Used Inventory</title>
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
            /* text-align: center; */
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
        .content {
            margin-bottom: 1%;
        }
        .content .title-item td{
            text-align: center;
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


    @foreach ($datas as $data)
        <table class="content">
            <tr>
                <td style="width: 5%">No. Permintaan</td>
                <td colspan="6">{{ $data->number }}</td>
            </tr>
            <tr>
                <td>Kapling</td>
                <td colspan="6">{{ 'Blok '.$data->lot->block.' '.$data->lot->unit_number }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td colspan="6">{{ $data->date_translate_format() }}</td>
            </tr>
            <tr class="title-item">
                <td>No</td>
                <td>Nama Barang</td>
                <td>Unit</td>
                <td>Quantity</td>
                <td>Merk</td>
                <td>Harga</td>
                <td>Total</td>
            </tr>
            @foreach ($data->receiptOfGoodsRequestItems as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->inventory->name }}</td>
                    <td>{{ $item->inventory->unit->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->inventory->branch ?? '-' }}</td>
                    <td>{{ 'Rp '.number_format($item->inventory->purchase_price ?? '0'). ',-' }}</td>
                    <td>{{'Rp. '. number_format($item->inventory->purchase_price * $item->qty).',-' }}</td>
                </tr>
            @endforeach
        </table>
    @endforeach

</body>

</html>

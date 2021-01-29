<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Out Standing PO</title>
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
        /* table td::fir{
            text-align: center;
        } */
        table,
        table td,
        table th {
            border: 1px solid black;
            padding: 5px;
        }

        .title {
            text-align: center;
            text-transform: uppercase;

            /* padding: 1rem 0rem; */
        }
        .content {
            margin-bottom: 1%;
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
        <img src="{{ env("SITE_HEADER_PDF_URL") }}" width="100%" height="100%"/>
        {{-- <img src="https://i.ibb.co/QKFwyrZ/Picture-1-header.png" width="100%" height="100%"/> --}}
    </header>

    <footer>
        <img src="{{ env("SITE_FOOTER_PDF_URL") }}" width="100%" height="100%"/>
        {{-- <img src="https://i.ibb.co/mhvmQvt/Picture-1-footer.png" width="100%" height="100%"/> --}}
    </footer>
    <table>
        <tr>
            <td colspan="4" class="title"><strong>{{ $title }}</strong></td>
        </tr>
    </table>
    @foreach ($datas as $index => $data)
        <table class="content">
            <tr>
                <td style="width: 3%">{{ $loop->iteration }}</td>
                <td colspan="2">PO : {{ $data->number }}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2">Status : {{ $data->refGeneralStatuses->name }}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2">Tanggal : {{ $data->date_translate_format() }}</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2">Total : Rp. {{ number_format(floatval($data->total)) }},-</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2">OUTSTANDING ITEM</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td style="width: 3%">#</td>
                <td>Nama Barang</td>
                <td>Qty Belum Terkirim</td>
            </tr>
            @foreach ($data->purchaseOrderItems as $item)
                <tr>
                    <td></td>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->inventory->name }}</td>
                    <td>{{ $item->qty - $item->delivered_qty }}</td>
                </tr>
            @endforeach
        </table>
    @endforeach
</body>

</html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Jalan</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {{-- <link rel="stylesheet" href="{{ asset('template/assets/css/delivery-order-pdf.css') }}"> --}}
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> --}}

    <style>
        #table,
        #table td,
        #table th {
            border: 1px solid black;
            padding: 5px;
        }
        table {
            width: 100%;
        }

        #table {
            border-collapse: collapse;
        }

        #header td{
            padding: 5px;
        }

        #footer{
            position: absolute;
            top: 80%;
        }

        #footer td{
            padding-bottom: 10%;
        }

        .content {
            padding: 1ch
        }

        .text-center {
            text-align: center
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
    <div style="padding: 2rem">
        <div style="padding: 1rem; display: flex">
            <div><img src="{{ asset('storage/'.($company_logo ?? '') ) }}" style="width: 100px; height: auto;"></div>
            <h3 style="width: 100%; text-align: center;"><b> SURAT JALAN </b></h3>
        </div>
        <div class="content">
            <table id="header">
                <tr>
                    <td colspan="4">Kepada Yth : {{ $data['name'] }} </td>
                    <td style="width: 40%">Alamat : {{ $data['address'] }}</td>
                </tr>
                <tr>
                    <td colspan="4">No Surat Jalan : {{ $data['number'] }}</td>
                    <td style="width: 40%">Tanggal : {{ $data['date'] }} </td>
                </tr>
            </table>
            <table id="table">
                <tr>
                    <th>NO</th>
                    <th style="width: 10%">KODE BARANG</th>
                    <th>NAMA BARANG</th>
                    <th style="width: 10%">JUMLAH</th>
                    <th style="width: 10%">SATUAN</th>
                    <th>KETERANGAN</th>
                </tr>
                @foreach ($data['body'] as $val)
                <tr class="text-center">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $val['code'] }}</td>
                    <td>{{ $val['name'] }}</td>
                    <td>{{ $val['total'] }}</td>
                    <td>{{ $val['unit'] }}</td>
                    <td>{{ $val['note'] }}</td>
                </tr>
                @endforeach
            </table>
            <table id="footer">
                <tr>
                    <td style="width: 30%"></td>
                    <td style="width: 20%;">Diterima Oleh,</td>
                    <td style="width: 20%">Diketahui Oleh,</td>
                    <td style="width: 15%">Dibuat Oleh,</td>
                </tr>
                <tr>
                    <td style="width: 30%"></td>
                    <td>(&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    <td>(&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                    <td>(&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>

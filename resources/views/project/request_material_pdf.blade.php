<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Request Material</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

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

        #footer td{
            padding-bottom: 5%;
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
            margin-left: 2cm;
            margin-right: 2cm;
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
        #tfooter{
            position: absolute;
            top: 90%;
            left: 10%;
        }
        #tfooter td{
            padding-bottom: 70px;
        }
    </style>
</head>

<body>
    <header>
        <img src="{{ asset('storage/'.($header ?? '') ) }}"width="100%" height="100%"/>
    </header>

    <footer>
        <img src="{{ asset('storage/'.($footer ?? '') ) }}" width="100%" height="100%"/>
    </footer>

    <div style="margin-top: 20px;">
        <h3 style=" width: 100%; text-align: center;"><b> REQUESITION MATERIAL FORM (RMF) </b></h3>
        <div class="content">
            <table id="header">
                <tr>
                    <td style="width: 20%">Kpd Yth</td>
                    <td > : Purchasing Departement</td>
                </tr>
                <tr>
                    <td style="width: 20%">Tanggal</td>
                    <td > : {{ $data['date'] }}</td>
                </tr>
                <tr>
                    <td style="width: 20%">RMF Nomor</td>
                    <td > : {{ $data['rmf_number'] }}</td>
                </tr>
                <tr>
                    <td style="width: 20%">Perihal</td>
                    <td > : {{ $data['title'] }}</td>
                </tr>
            </table>
            <table id="table">
                <tr>
                    <th style="width: 10%">No</th>
                    <th>Nama Barang</th>
                    <th style="width: 10%">Merk</th>
                    <th style="width: 10%">Jumlah</th>
                </tr>
                @foreach ($data['body'] as $val)
                <tr class="text-center">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $val['inventory_name'] }}</td>
                    <td>{{ $val['inventory_brand'] }}</td>
                    <td>{{ $val['total'] }}</td>
                </tr>
                @endforeach
            </table>

            <table id="tfooter">
                <tr>
                    <td style="width: 20%;">Setujui Oleh,</td>
                    <td style="width: 15%">Dibuat Oleh,</td>
                </tr>
                <tr>
                    <td >(Project Manager)</td>
                    <td >(Project Implementers)</td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>

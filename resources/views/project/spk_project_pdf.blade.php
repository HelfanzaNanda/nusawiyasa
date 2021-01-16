<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Jalan</title>
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
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 3cm;
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
        #tfooter .ttd{
            padding-bottom: 70px;
        }

    </style>
</head>

<body>
    <header>
        <img src="{{ env("SITE_HEADER_PDF_URL") }}" width="100%" height="100%"/>
    </header>

    <footer>
        <img src="{{ env("SITE_FOOTER_PDF_URL") }}" width="100%" height="100%"/>
    </footer>

    <div>
        <div style="width: 100%; text-align: center; ">
            <h3 style="display: inline"><b> SURAT PERINTAH KERJA </b></h3>
            <p style="padding: 0px; margin: 0px;">{{ $data['number_project'] }}</p>

            <div></div>
        </div>
        <div class="content">
            <table id="header">
                <tr>
                    <td style="width: 20%">Kpd Yth</td>
                    <td> : Purchasing Departement</td>
                </tr>
                <tr>
                    <td style="width: 20%">Dari</td>
                    <td > : {{ $data['from'] }}</td>
                </tr>
                <tr>
                    <td style="width: 20%">Tanggal</td>
                    <td > : {{ $data['date'] }}</td>
                </tr>
                <tr>
                    <td style="width: 20%">Perihal</td>
                    <td > : {{ $data['title'] }}</td>
                </tr>
            </table>
            <table id="table">
                <tr>
                    <th style="width: 50%" >Uraian Pekerjaan</th>
                    <th>Keterangan</th>
                </tr>
                <tr>
                    <td>Mohon untuk segera dilaksanankan <br>
                        pembagunan untuk unit rumah untuk, <br>
                        <p>Nama Konsumen : {{ $data['customer_name'] }}<br></p>
                        <p>Nomor Kavling : {{ $data['lot_number'] }}<br></p>
                        <p>Type Rumah : {{ $data['building_area'] }}<br></p>
                        <p>Luas Tanah : {{ $data['surface_area'] }}<br></p>
                        <p>Status Perbankan : {{ $data['status'] }}<br></p>
                    </td>
                    <td style="vertical-align: top">{{ $data['note'] }}</td>
                </tr>
            </table>

            <table id="tfooter">
                <tr>
                    <td class="ttd" style="width: 20%;">Diterima Oleh,</td>
                    <td class="ttd" style="width: 20%;">Setujui Oleh,</td>
                    <td class="ttd" style="width: 15%">Dibuat Oleh,</td>
                </tr>
                <tr id="ttb">
                    <td >(Oki Setyawan)</td>
                    <td >(D Widi Nugroho)</td>
                    <td>(&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
                </tr>
                <tr >
                    <td >Project Manager</td>
                    <td >Direktur Utama</td>
                    <td >Marketing</td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>

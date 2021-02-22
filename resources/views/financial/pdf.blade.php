<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Pengajuan Keuangan</title>
    <style>
        html * {
        color: #000 !important;
        font-family: Arial !important;
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
            margin-top: 1cm;
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
        #tfooter .ttd{
            padding-bottom: 70px;
        }

        .right {
            float: right;
            max-width: 70%;
        }
        .underline {
            decoration: underline;
        }
        p {
            border-bottom-style: solid;
            border-bottom-color: black;
        }
        .column {
        float: left;/* Should be removed. Only for demonstration */
        }

        .left {
        width: 25%;
        }

        /* Clear floats after the columns */
        .row:after {
        content: "";
        display: table;
        clear: both;
        }
        .table {
    border-collapse: collapse;
}

        .table td, th {
            border: 1px solid black;
        }

    </style>
</head>
<body>
    <center><h2>DISPOSISI PENGAJUAN KEUANGAN</h2></center>
    <br>
    <h4>Nomor Pengajuan : {{$data['number']}}</h4>
    <h4>Perumahan : {{ $data['cluster']['name'] }}</h4>
    <h4>Tanggal   : {{$data['date']}}</h4>
    <br>
    <table class="table" style="width: 100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Uraian Penjelasan</th>
                <th>Jumlah</th>
                <th>Satuan</th>
                <th>Nilai(Rp)</th>
                <th>Total Nilai(Rp)</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        @php
            $no = 1;
        @endphp

        <tbody>
            @foreach ($data->detail as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item->value }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ $item->unit }}</td>
                <td>Rp. {{ number_format($item->price,2,'.',',') }}</td>
                <td>Rp. {{ number_format($item->total_price,2,'.',',') }}</td>
                <td>{{ $item->note }}</td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total</th>
                <td></td>
                <td></td>
                <td></td>
                <th>Rp. {{ number_format($data['total'],2,'.',',') }}</th>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <table  style="width: 100%">
        <tr style="margin-bottom: 100px">
            <td style="width: 33%"><center>Disetujui Oleh</center></td>
            <td style="width: 33%"><center>Diketahui Oleh</center></td>
            <td style="width: 34%"><center>Dibuat Oleh</center></td>
        </tr>
        <tr><td colspan="3">&nbsp; </td></tr>
        <tr><td colspan="3">&nbsp; </td></tr>
        <tr><td colspan="3">&nbsp; </td></tr>
        <tr><td colspan="3">&nbsp; </td></tr>
        <tr><td colspan="3">&nbsp; </td></tr>
        <tr>
            <td><center>(Project Lapangan)</center></td>
            <td><center>(Lapangan)</center></td>
            <td><center>(Administrasi)</center></td>
        </tr>
    </table>
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DIPOSISI PENGAJUAN UPAH BORONGAN</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>

        #table .table-no-borderred{
            border-style: none;
        }
        #table {
            border-collapse: collapse;
        }

        #table th{
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            margin-top: 20px;
        }
        #table,
        #table td,
        #table th {
            border: 1px solid black;
            padding: 5px;
        }
        table {
            width: 100%;
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

        #tfooter{
            position: absolute;
            top: 80%;
            left: 10%;
        }
        #tfooter .ttd{
            padding-bottom: 70px;
        } 
        #title {
            margin-bottom: 8px;
            font-weight: bold;
            font-family: Arial, Helvetica, sans-serif;
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
        {{-- <img src="https://images.unsplash.com/photo-1533167649158-6d508895b680?ixid=MXwxMjA3fDB8MHxzZWFyY2h8M3x8c3BsYXNofGVufDB8fDB8&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" width="100%" height="100%"/> --}}
    </header>

    <footer>
        <img src="{{ asset('storage/'.($footer ?? '') ) }}" width="100%" height="100%"/>
        {{-- <img src="https://images.unsplash.com/photo-1533167649158-6d508895b680?ixid=MXwxMjA3fDB8MHxzZWFyY2h8M3x8c3BsYXNofGVufDB8fDB8&ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" width="100%" height="100%"/> --}}
    </footer>
    <div>
        <table class="header">
            <tr id="title"><td>DIPOSISI PENGAJUAN UPAH BORONGAN</td></tr>
            <tr><td>Perumahan : {{ $data[2]['cluster_name'] }}</td></tr>
            <tr><td>Tanggal : {{ \Carbon\Carbon::parse($data[0]['date'])->format('d F Y') }}</td></tr>
        </table>

        <table id="table">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Uraian Penjelasan</th>
                    <th rowspan="2">Kavling</th>
                    <th rowspan="2">Nilai Kontrak SPK</th>
                    <th colspan="2">Progress Total</th>
                    <th rowspan="2">Keterangan</th>
                </tr>
                <tr>
                    <th>Persen(%)</th>
                    <th>Rupiah(Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data[1] as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->lot }}</td>
                        <td>{{ 'Rp. ' .number_format(floatval($item->spk_cost)) }}</td>
                        <td>{{ $item->weekly_percentage }}</td>
                        <td>{{ 'Rp. ' .number_format(floatval($item->weekly_cost)) }}</td>
                        <td>{{ $item->note }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table id="tfooter">
            <tr>
                <td class="ttd" style="width: 45%"></td>
                <td class="ttd">Setujui Oleh,</td>
                <td class="ttd">Diajukan Oleh,</td>
            </tr>
            <tr>
                <td></td>
                <td >(Project Manager)</td>
                <td >(Administrasi Lapangan)</td>
            </tr>
        </table>
    </div>
</body>

</html>

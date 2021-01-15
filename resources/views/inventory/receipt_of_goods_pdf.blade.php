<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bukti Penerimaan Barang</title>
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

        #footer td{
            padding-bottom: 5%;
        }

        .content {
            padding: 1ch
        }

        .text-center {
            text-align: center
        }
    </style>
</head>

<body>
    <div>
        <div style="display: flex">
            <div><img src="{{ env("SITE_LOGO_URL") }}" style="width: 100px; height: auto;"></div>
            <h3 style="width: 100%; text-align: center;"><b> BUKTI PENERIMAAN BARANG </b></h3>
        </div>
        <div class="content">
            <table id="header">
                <tr>
                    <td style="width: 20%">Nama Suplier</td>
                    <td colspan="5" style="width: 55%"> : {{ $data['supplier_name'] }}</td>
                    <td style="width: 10%"> No BPB</td>
                    <td > : {{ $data['bpb_number'] }}</td>
                </tr>
                <tr>
                    <td style="width: 20%">Alamat</td>
                    <td colspan="5" style="width: 55%"> : {{ $data['supplier_address'] }}</td>
                    <td style="width: 10%"> Tanggal</td>
                    <td > : {{ $data['date'] }}</td>
                </tr>
                <tr>
                    <td colspan="6" style="width: 65%"> </td>
                    <td style="width: 10%"> No PO</td>
                    <td > : {{ $data['po_number'] }}</td>
                </tr>
                <tr>
                    <td colspan="6" style="width: 65%"> </td>
                    <td style="width: 10%"> No Inv</td>
                    <td > : {{ $data['inv_number'] }}</td>
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
                    <td colspan="4"></td>
                    <td style="width: 20%">Diketahui Oleh,</td>
                    <td style="width: 15%">Dibuat Oleh,</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td >........................</td>
                    <td >........................</td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>

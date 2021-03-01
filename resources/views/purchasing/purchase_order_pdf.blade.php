<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Purchase Order</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>

        #table .table-no-borderred{
            border-style: none;
        }
        #table {
            border-collapse: collapse;
        }

        #table th{
            font-weight: 600;
            font-family: Arial, Helvetica, sans-serif;
            text-align: center;
            color: white;
            background: purple;
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
        #purchase-order {
            font-size: 20px;
            vertical-align: bottom;
            color: blueviolet;
            font-weight: 600;
            font-family: Arial, Helvetica, sans-serif;
        }
        .td-head {
            font-weight: 600;
            font-family: Arial, Helvetica, sans-serif;
            width: 40%;
            background: purple;
            color: white
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
    <div>
        <table class="header">
            <tr>
                <td style="width: 10%"><img src="{{ asset('storage/'.($company_logo ?? '') ) }}" width="80px"></td>
                <td style=" vertical-align: bottom;"><h3 style="display: inline"><b> {{ $company_name }}</b></h3></td>
                <td id="purchase-order">PURCHASE ORDER</td>
            </tr>
        </table>

        <table>
            <tr>
                <td style="width: 60%"></td>
                <td style="width: 5%">Tanggal</td>
                <td> : {{ $data['date'] }}</td>
            </tr>
            <tr>
                <td style="width: 60%">Jl. Bebedahan Wanamekar</td>
                <td style="width: 5%">No PO</td>
                <td>: {{ $data['po_number'] }}</td>
            </tr>
            <tr>
                <td style="width: 60%"> Wanareja Garut</td>
                <td style="width: 5%">No FPP</td>
                <td>; {{ $data['fpp_number'] }}</td>
            </tr>
        </table>
        <table>
            <tr>
                <td style="width: 5%"> Phone </td>
                <td style="width: 52%"> : 082198021663</td>
                <td style="width: 15%" rowspan="2">Jenis Permintaan</td>
                <td>
                    <input type="checkbox" {{ $data['isRap'] ? 'checked' : '' }}>
                    <label for="vehicle1"> Rap</label><br>
                </td>
            </tr>
            <tr>
                <td style="width: 5%"> Website </td>
                <td style="width: 52%;   text-decoration: underline; color: blue;"> : nusawiyasapropertindo.com</td>

                <td>
                    <input type="checkbox" {{ $data['isRap'] ? '' :'checked' }}>
                    <label for="vehicle1"> Non Rap</label><br>
                </td>
            </tr>
        </table>

        <table style="margin-top: 10px">
            <tr>
                <td class="td-head">SUPPLIER</td>
                <td style="width: 20%"></td>
                <td class="td-head">DI KIRIM KE</td>
            </tr>
        </table>

        <table>
            <tr>
                <td style="width: 18%">Nama Perusahaan</td>
                <td style="width: 42%">: {{ $data['supplier_name'] }}</td>
                <td style="width: 18%; vertical-align: top">Penerima</td>
                <td>: (&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            </tr>
            <tr>
                <td style="width: 18%">Address</td>
                <td style="width: 42%">: {{ $data['supplier_address'] }}</td>
                <td style="width: 18%; vertical-align: top">Nama Perusahaan</td>
                <td>: {{ $data['cluster_name'] }}</td>
            </tr>
            <tr>
                <td style="width: 18%">Phone</td>
                <td style="width: 42%">: {{ $data['supplier_phone'] }}</td>
                <td style="width: 18%; vertical-align: top">Alamat</td>
                <td>: {{ $data['cluster_address'] }}</td>
            </tr>
            <tr>
                <td style="width: 18%">No Telephone</td>
                <td style="width: 42%">: {{ $data['supplier_telephone'] }}</td>
                <td style="width: 18%; vertical-align: top">No Telephone</td>
                <td>: {{ $data['cluster_phone'] }}</td>
            </tr>
        </table>

        <table id="table">
            <tr>
                <th style="width: 5%">No</th>
                <th>URAIAN BARANG</th>
                <th style="width: 10%">JUMLAH</th>
                <th style="width: 20%">HARGA</th>
                <th>TOTAL HARGA</th>
            </tr>
            @foreach ($data['body'] as $val)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $val['inventory_name'] }}</td>
                <td>{{ $val['qty'] }}</td>
                <td>Rp. {{ number_format(floatval($val['price'])) }},-</td>
                <td>Rp. {{ number_format(floatval($val['total'])) }},-</td>
            </tr>
            @endforeach
            <tr>
                <td class="table-no-borderred" style="text-decoration: underline;" >Catatan Tambahan</td>
                <td class="table-no-borderred" style="border-right: 1px solid; vertical-align: top" colspan="2"> : {{ $data['note'] }}</td>
                <td class="table-no-borderred" >SUBTOTAL</td>
                <td class="table-no-borderred" >Rp. {{ number_format(floatval($data['sub_total'])) }},-</td>
            </tr>
            <tr>
                <td class="table-no-borderred" style="border-right: 1px solid;" colspan="3"></td>
                <td class="table-no-borderred" >PAJAK</td>
                <td class="table-no-borderred" >Rp. {{ number_format(floatval($data['tax'])) }},-</td>
            </tr>
            <tr>
                <td class="table-no-borderred" style="border-right: 1px solid;" colspan="3"></td>
                <td class="table-no-borderred" >PENGIRIMAN</td>
                <td class="table-no-borderred" >Rp. {{ number_format(floatval($data['delivery'])) }},-</td>
            </tr>
            <tr>
                <td class="table-no-borderred" style="border-right: 1px solid;" colspan="3"></td>
                <td class="table-no-borderred" style="border-bottom: double;"  >LAIN LAIN</td>
                <td class="table-no-borderred" style="border-bottom: double;">Rp. {{ number_format(floatval($data['other'])) }},-</td>
            </tr>
            <tr>
                <td class="table-no-borderred" colspan="3" style="border-right: 1px solid; border-bottom: 1px solid;"></td>
                <td class="table-no-borderred" style="border-bottom: 1px solid;">TOTAL</td>
                <td class="table-no-borderred" style="border-bottom: 1px solid;">Rp. {{ number_format(floatval($data['total'])) }},-</td>
            </tr>
        </table>

        <table id="tfooter">
            <tr>
                <td class="ttd" style="width: 20%;">Setujui Oleh,</td>
                <td class="ttd" style="width: 20%;">Diketahui Oleh,</td>
                <td class="ttd" style="width: 15%">Dibuat Oleh,</td>
            </tr>
            <tr id="ttb">
                <td >(D Widi Nugroho)</td>
                <td >(Oki Setyawan)</td>
                <td>(&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)</td>
            </tr>
            <tr >
                <td >Direktur Utama</td>
                <td >Project Manager</td>
                <td >Purchasing</td>
            </tr>
        </table>
    </div>
</body>

</html>

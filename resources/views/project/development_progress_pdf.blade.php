<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Development Progress</title>
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
            width: 300px;
            padding: 10px;
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
    <p style="text-align: right;line-height: 16px;">
        {{ $data['cluster']['address'] }}
        <br>
        Kelurahan {{ $data['cluster']['subdistrict'] }}
        <br>
        Kec. {{ $data['cluster']['district'] }}, {{ $data['cluster']['city'] }}
        <br>
        Telp. {{ $data['cluster']['phone'] }}
    </p>
    <div style="">
        <center>
            <h4 style="line-height: 20px">
                LAPORAN PER UNIT 
                <br>
                BLOK {{ $data['lot']['block'].'-'. $data['lot']['unit_number']}}
                <br>
                {{ $data['tanggal'] }}
                <br>
            </h4>
        </center>
    </div>
    <br>
    <div>
        <b><table>
            <tr>
                <td width="50px">PEKERJAAN </td>
                <Td>: Pembangunan Perumahan {{ $data['cluster']['name'] }} Blok {{ $data['lot']['block'].'-'. $data['lot']['unit_number']}}</Td>
            </tr>
            <tr>
                <td width="50px">Lokasi </td>
                <Td>: {{ $data['cluster']['address'].', Kp. '.$data['cluster']['subdistrict'].', Kec.'.$data['cluster']['district'].', '.$data['cluster']['city'].', '.$data['cluster']['province'] }}</Td>
            </tr>
            <td width="50px">Tanggal </td>
                <Td>: {{ $data['tanggal'] }}</Td>
        </table></b>
    </div>
    <center>
        <h4>Dokumentasi</h4>
    </center>
    <br>
    <div class="img">    
        @foreach ($data['file'] as $item)
        <?php
            $path = public_path($item['filepath'].'/'.$item['filename']);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $datafile = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($datafile);
        ?>
            <img src="{{ $base64 }}" width="250px" alt="">
        @endforeach
    </div>
    <br>
    <div>
        <table id="table">
            <tr>
                <th>NO</th>
                <th>PEKERJAAN YANG DILAKSANAKANA</th>
                <th>LOKASI</th>
                <th>VOLUME</th>
                <th>KETERANGAN</th>
            </tr>
                @php
                    $no = 1;
                @endphp
                @foreach ($data['job'] as $item)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $item['jobs'] }}</td>
                        <td>{{ $item['location'] }}</td>
                        <td>{{ $item['volume'] }}</td>
                        <td>{{ $item['note'] }}</td>
                    </tr>
                @endforeach
        </table>
        <br>
        <table id="table">
            <tr>
                <th>NO</th>
                <th>BAHAN YANG DIGUNAKAN</th>
                <th>JUMLAH</th>
                <th>ALAT YANG DIGUNAKAN</th>
                <th>JUMLAH</th>
                <th>TENAGA KERJA</th>
                <th>JUMLAH</th>
            </tr>
            @if(isset($data['eq']))
                @foreach ($data['eq'] as $item)
                    <tr>
                        <td>{{$item['no']}}</td>
                        <td>{{$item['material_name']}}</td>
                        <td>{{$item['material_qty']}}</td>
                        <td>{{$item['tool_name']}}</td>
                        <td>{{$item['tool_qty']}}</td>
                        <td>{{$item['service_name']}}</td>
                        <td>{{$item['service_qty']}}</td>
                    </tr>
                @endforeach
            @endif
        </table>
        <br>
        <table>
            <tr>
                <td>
                    <th>
                        <center>DISETUJUI OLEH:</center>
                    </th>
                    <th>
                        <center>DIPERIKSA OLEH:</center>
                    </th>
                    <th>
                        <center>DIBUAT OLEH:</center>
                    </th>
                </td>
            </tr>
            <tr>
                <td colspan="3">&nbsp</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp</td>
            </tr>
            <tr>
                <td>
                    <th><hr style="margin-left: 20px; margin-right: 20px"></th>
                    <th><hr style="margin-left: 20px; margin-right: 20px"></th>
                    <th><hr style="margin-left: 20px; margin-right: 20px"></th>
                    
                </td>
            </tr>
            <tr>
                <td>
                    <th><center>Pengawas</center></th>
                    <th><center>Inspector</center></th>
                    <th><center>Pelaksana</center></th>
                    
                </td>
            </tr>
        </table>
    </div>
</body>

</html>

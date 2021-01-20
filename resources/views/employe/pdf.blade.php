<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Jalan</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <style>
        html * {
        color: #000 !important;
        font-family: Arial !important;
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

    </style>
</head>

<body>
    <center><h2>Informasi Pegawai</h2></center>
    <center>
        <img src="{{ $data['avatar']}}" alt="" width="200px" height="200px">
    </center>
        <p>Nama Lengkap : <span style="float: right">{{ ($data['fullname']) ? $data['fullname'] : '-'}}</span></p>
        <p>Email : <span style="float: right">{{ ($data['email']) ? $data['email'] : '-'}}</span></p>
        <p>No. Handphone : <span style="float: right">{{ ($data['phone_number']) ? $data['phone_number'] : '-'}}</span></p>
        <p>
            Akun Bank :
            <span style="float: right">
                {{ ($data['bank_account']) ? $data['bank_account'] : '-'}}
                ({{ ($data['bank_name']) ? $data['bank_name'] : '-'}})
            </span>
        </p>
        <p>Tanggal Lahir : <span style="float: right">{{ ($data['date_birth']) ? $data['date_birth'] : '-'}}({{ $data['age'] }})</span></p>
        <p>Tempat Lahir : <span style="float: right">{{ ($data['place_birth']) ? $data['place_birth'] : '-'}}</span></p>
        <p>
            Awal Masuk Kerja : 
            <span style="float: right">
                {{ ($data['joined_at']) ? $data['joined_at'] : '-'}}
                {{ ($data['lama_kerja']) ? $data['lama_kerja'] : '-'}}
            </span>
        </p>
        <p>Keluar Kerja : <span style="float: right">{{ ($data['resign_at']) ? $data['resign_at'] : '-'}}</span></p>
        <p>Jenis Kelamin : <span style="float: right">{{ ($data['gender']) ? $data['gender'] : '-'}}</span></p>
        <p>Agama : <span style="float: right">{{ ($data['religion']) ? $data['religion'] : '-'}}</span></p>
        <p>Nama Ayah : <span style="float: right">{{ ($data['father_name']) ? $data['father_name'] : '-'}}</span></p>
        <p>Nama Ibu : <span style="float: right">{{ ($data['mother_name']) ? $data['mother_name'] : '-'}}</span></p>
        <p>
            Nomor Kartu Pengenal :
            <span style="float: right">
                {{ ($data['identity_card_number']) ? $data['identity_card_number'] : '-'}}
                ({{ ($data['identity_type']) ? $data['identity_type'] : '-'}})
            </span>
        </p>
        <div class="row" style="border-bottom-style: solid;
        border-bottom-color: black;">
            <div class="column left">Alamat : </div>
            <div class="column right" style="float: right;">
                {{ ($data['current_address_street']) ? $data['current_address_street'].', ' : ''}}
                {{ ($data['current_address_rt']) ? 'RT.'.$data['current_address_rt'].', ' : ''}}
                {{ ($data['current_address_rw']) ? 'RW.'.$data['current_address_rw'].', ' : ''}}
                {{ ($data['current_address_kelurahan']) ? 'Kel.'.$data['current_address_kelurahan'].', ' : ''}}
                {{ ($data['current_address_kecamatan']) ? 'Kec.'.$data['current_address_kecamatan'].', ' : ''}}
                {{ ($data['current_address_city']) ? $data['current_address_city'].', ' : ''}}
                {{ ($data['current_address_province']) ? $data['current_address_province'].', ' : ''}}
            </div>
          </div>
        <h5>Riwayat Pendidikan</h5>
        <table id="table">
            <tr>
                <th>Tingkat</th>
                <th>Sekolah</th>
                <th>Jurusan</th>
                <th>Tahun Lulus</th>
            </tr>
            @foreach ($data['educations'] as $item)
                <tr>
                    <td>{{ $item['grade'] }}</td>
                    <td>{{ $item['school'] }}</td>
                    <td>{{ $item['major'] }}</td>
                    <td>{{ $item['graduation_year'] }}</td>
                </tr>
            @endforeach
        </table>
</body>

</html>

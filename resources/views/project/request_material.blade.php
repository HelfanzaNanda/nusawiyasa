@extends('layouts.main')

@section('title', 'Pengajuan Bahan')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Pengajuan Bahan</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Pengajuan Bahan</a></li>
        <li class="breadcrumb-item active">Data Pengajuan Bahan</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
      <a href="{{url('/')}}/create-request-material" class="btn add-btn"><i class="fa fa-plus"></i> Tambah Pengajuan Bahan</a>
    </div>
  </div>
</div>
<!-- /Page Header -->

<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Pengajuan Bahan</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <div class="table-responsive">
          <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
            <thead>
              <tr>
                <th>#</th>
                <th width="15%">Judul</th>
                <th>Nomor FPP</th>
                <th>Nomor SPK</th>
                <th>Jenis Permintaan</th>
                <th>Tanggal</th>
                <th class="text-right" width="10%">Aksi</th>
              </tr>
            </thead>
            <tbody>
     
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('additionalScriptJS')
<script type="text/javascript">
  $("#main-table").DataTable({
      "pageLength": 10,
      "processing": true,
      "serverSide": true,
      "ajax":{
          "url": BASE_URL+"/request-material-datatables",
          "dataType": "json",
          "type": "POST",
          "data":function(d) { 
            d._token = "{{csrf_token()}}"
          },
      },
      "columns": [
          {data: 'id', name: 'id', width: '5%', "visible": false},
          {data: "title", name: 'title'},
          {data: "number", name: 'number'},
          {data: "spk_number", name: 'spk_number'},
          {data: "type", name: 'type'},
          {data: "date", name: 'date'},
          {data: 'action', name: 'action', className: 'text-right'},
      ],
  });
</script>
@endsection
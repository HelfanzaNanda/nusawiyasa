@extends('layouts.main')

@section('title', 'Jurnal Umum')

@section('style')
	
@endsection

@section('content')
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Jurnal Umum</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Jurnal Umum</a></li>
        <li class="breadcrumb-item active">Data Jurnal Umum</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
      <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah Jurnal Umum</a>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Data Jurnal Umum</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <div class="table-responsive">
          <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
            <thead>
              <tr>
                <th>#</th>
				<th>No. Ref</th>
				<th>Deskripsi</th>
				<th>Tipe</th>
				<th>Tanggal</th>
				<th>Total</th>
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

@section('additionalFileJS')

@endsection

@section('additionalScriptJS')
<script type="text/javascript">
  $("#main-table").DataTable({
      "pageLength": 10,
      "processing": true,
      "serverSide": true,
      // "searching": false,
      // "ordering": false,
      "ajax":{
          "url": BASE_URL+"/accounting-general-ledger-datatables",
          "dataType": "json",
          "type": "POST",
          "data":function(d) { 
            d._token = "{{csrf_token()}}"
          },
      },
      "columns": [
			{data: 'id', name: 'id', width: '5%', "visible": false},
			{data: 'ref', name: 'ref'},
			{data: 'description', name: 'description'},
			{data: 'type', name: 'type'},
			{data: 'date', name: 'date'},
			{data: 'total', name: 'total'},
			{data: 'action', name: 'action', className: 'text-right'},
      ],
  });
</script>
@endsection
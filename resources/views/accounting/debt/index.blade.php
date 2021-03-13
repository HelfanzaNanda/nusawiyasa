@extends('layouts.main')

@section('title', 'Hutang')

@section('style')
	
@endsection

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Hutang</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Hutang</a></li>
        <li class="breadcrumb-item active">Data Hutang</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
      <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah Hutang</a>
    </div>
  </div>
</div>
<!-- /Page Header -->

<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Data Hutang</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <div class="table-responsive">
          <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
            <thead>
              <tr>
                <th>#</th>
				<th>No.</th>
				<th>Nama Debitur</th>
				<th>No. PO</th>
				<th>Total</th>
				<th>Tanggal Hutang</th>
				<th>Rencana Pembayaran</th>
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

<!-- Add Salary Modal -->
<div id="add-modal" class="modal custom-modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Hutang</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-form" method="POST" action="#">
          {!! csrf_field() !!}
          <div class="row"> 
            <div class="col-sm-12">
              <div class="form-group">
                <label>No.</label>
                <input class="form-control" type="text" name="number" id="input-number">
              </div>
              <div class="form-group">
                <label>Debitur</label>
                <select class="form-control" id="input-debitur" name="supplier_id"
                required oninvalid="this.setCustomValidity('Harap Isikan Debitur.')" 
                onchange="this.setCustomValidity('')"> 
                	<option value=""> - Pilih Debitur - </option>
                	@foreach($suppliers as $supplier)
                	<option value="{{$supplier['id']}}">{{$supplier['name']}}</option>
                	@endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Tanggal</label>
                <input class="form-control" type="text" name="date" id="input-date"
                required oninvalid="this.setCustomValidity('Harap Isikan Tanggal.')" 
                onchange="this.setCustomValidity('')">
              </div>
              <div class="form-group">
                <label>Tanggal Bayar</label>
                <input class="form-control" type="text" name="payment_plan_date" id="input-payment-plan-date"
                required oninvalid="this.setCustomValidity('Harap Isikan Tanggal Bayar.')" 
                onchange="this.setCustomValidity('')">
              </div>
              <div class="form-group">
                <label>Total</label>
                <input class="form-control" type="number" name="total" id="input-total"
                required oninvalid="this.setCustomValidity('Harap Isikan Total.')" 
                onchange="this.setCustomValidity('')">
              </div>
              <div class="form-group">
                <label>Catatan</label>
                <textarea class="form-control" id="input-description" name="description"
                required oninvalid="this.setCustomValidity('Harap Isikan Catatan.')" 
                onchange="this.setCustomValidity('')"></textarea>
              </div>
            </div>
          </div>
          <div class="submit-section">
            <div class="col-auto float-right ml-auto pb-2">
              <button type="button" class="btn btn-close mr-2 btn-secondary" data-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-primary float-right loading" 
              data-loading-text='<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Loading...'>
                Submit
              </button>
            </div>
          </div>
        </form>
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
      "url": BASE_URL+"/debt-datatables",
      "dataType": "json",
      "type": "POST",
      "data":function(d) { 
        d._token = "{{csrf_token()}}"
      },
  },
  "columns": [
    	{data: 'id', name: 'id', width: '5%', "visible": false},
		{data: 'number', name: 'number'},
		{data: 'supplier_name', name: 'supplier_name'},
		{data: 'po_number', name: 'po_number'},
		{data: 'total', name: 'total'},
		{data: 'date', name: 'date'},
		{data: 'payment_plan_date', name: 'payment_plan_date'},
        {data: 'action', name: 'action', className: 'text-right'},
  ],
});

$("#show-add-modal").on('click',function() {
  $("form#add-form").trigger('reset');
  $('#input-debitur').val('').trigger('change');
	$('#add-modal').modal('show');
});

if($('#input-payment-plan-date').length > 0) {
	$('#input-payment-plan-date').datetimepicker({
	  format: 'YYYY-MM-DD',
	  icons: {
	    up: "fa fa-angle-up",
	    down: "fa fa-angle-down",
	    next: 'fa fa-angle-right',
	    previous: 'fa fa-angle-left'
	  }
	});
}

if($('#input-date').length > 0) {
	$('#input-date').datetimepicker({
	  format: 'YYYY-MM-DD',
	  icons: {
	    up: "fa fa-angle-up",
	    down: "fa fa-angle-down",
	    next: 'fa fa-angle-right',
	    previous: 'fa fa-angle-left'
	  }
	});
}

$('#input-debitur').select2({
	width: '100%'
});

$('form#add-form').submit( function( e ) {
e.preventDefault();
var loading_text = $('.loading').data('loading-text');
        $('.loading').html(loading_text).attr('disabled', true);
var form_data = new FormData( this );

$.ajax({
  type: 'post',
  url: BASE_URL+'/debt',
  data: form_data,
  cache: false,
  contentType: false,
  processData: false,
  dataType: 'json',
  beforeSend: function() {
    
  },
  success: function(msg) {
    
    if(msg.status == 'success'){
        setTimeout(function() {
            swal({
                title: "Sukses",
                text: msg.message,
                type:"success",
                html: true
            }, function() {
                $('#main-table').DataTable().ajax.reload(null, false);
                $('#add-modal').modal('hide');
                // window.location.replace(URL_LIST_PURCHASES);
            });
        }, 500);
    } else {
        swal({
            title: "Gagal",
            text: msg.message,
            showConfirmButton: true,
            confirmButtonColor: '#0760ef',
            type:"error",
            html: true
        });
    }
    $('.loading').html('Submit').attr('disabled', false)
  },
	error: function(params) {
		$('.loading').html('Submit').attr('disabled', false)
	}
})
});
</script>
@endsection
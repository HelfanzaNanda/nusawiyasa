@extends('layouts.main')

@section('title', 'Booking')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Booking</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Booking</a></li>
        <li class="breadcrumb-item active">Data Booking</li>
      </ul>
    </div>
{{--     <div class="col-auto float-right ml-auto">
      <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah Booking</a>
    </div> --}}
  </div>
</div>
<!-- /Page Header -->

<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Data Booking</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <div class="table-responsive">
          <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
            <thead>
              <tr>
                <th>#</th>
                <th width="15%">Nama Konsumen</th>
                <th>Nama Cluster</th>
                <th>Block</th>
                <th>No. Unit</th>
                <th>LT</th>
                <th>LB</th>
                <th>Status</th>
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

<div id="installment-modal" class="modal custom-modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cicilan Cash</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-form" method="POST" action="#">
          {!! csrf_field() !!}
          <div class="row"> 
            <div class="col-sm-12" id="installment-row">  
              <div class="form-group">
                <label>Cicilan 1</label>
                <input class="form-control" type="text" name="installment[]">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">  
              <center><button type="button" class="btn btn-primary btn-add-row"><i class="fa fa-plus"></i></button></center>
            </div>
          </div>
          <div class="submit-section">
            <button class="btn btn-primary submit-btn">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('additionalScriptJS')
<script type="text/javascript">

  $("#main-table").on('click', '#booking-installment', function() {
      let id = $(this).data('id');
      $('#installment-modal').modal('show');
  });

  $(document).on("click", '.btn-add-row', function () {
    var group = $('input[name="installment[]"]');

    if (group.length + 1 > 8) {
      swal({
          title: "Gagal",
          text: "Maksimal Cicilan Cash Adalah 8 kali",
          showConfirmButton: true,
          confirmButtonColor: '#0760ef',
          type:"error",
          html: true
      });
      return false;
    }

    let cols = '';

    cols += '<div class="form-group">';
    cols += '  <label>Cicilan '+(group.length + 1)+'</label>';
    cols += '  <input class="form-control" type="text" name="installment[]">';
    cols += '</div>';

    $("#installment-row").append(cols);
  });

  $("#main-table").DataTable({
      "pageLength": 10,
      "processing": true,
      "serverSide": true,
      // "searching": false,
      // "ordering": false,
      "ajax":{
          "url": BASE_URL+"/booking-datatables",
          "dataType": "json",
          "type": "POST",
          "data":function(d) { 
            d._token = "{{csrf_token()}}"
          },
      },
      "columns": [
          {data: 'id', name: 'id', width: '5%', "visible": false},
          {data: 'customer_name', name: 'customer_name'},
          {data: 'cluster_name', name: 'cluster_name'},
          {data: 'block', name: 'block'},
          {data: 'unit_number', name: 'unit_number'},
          {data: 'surface_area', name: 'surface_area'},
          {data: 'building_area', name: 'building_area'},
          {data: 'status', name: 'status'},
          {data: 'action', name: 'action', className: 'text-right'},
      ],
  });

  $('#input-province').select2({
    width: '100%'
  });

  $('#input-city').select2({
    width: '100%'
  });
</script>
@endsection
@extends('layouts.main')

@section('title', 'Pembayaran')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Pembayaran</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Pembayaran</a></li>
        <li class="breadcrumb-item active">Data Pembayaran</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
      <a href="#" class="btn btn-primary" id="show-filter-modal"><i class="fa fa-filter"></i> Filter</a>
    </div>
  </div>
</div>
<!-- /Page Header -->

<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Data Pembayaran</h3>
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

<div id="bank-status-modal" class="modal custom-modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Status Bank</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-form" method="POST" action="#">
          {!! csrf_field() !!}
          <div class="row"> 
            <input type="hidden" name="id" id="input-id">
            <div class="col-sm-12" id="installment-row">  
              <div class="form-group">
                <label>Status Bank</label>
                <select class="form-control" id="input-bank-status" name="bank_status">
                  <option> - Pilih Status - </option>
                  <option value="3">Ditolak</option>
                  <option value="2">Disetujui</option>
                </select>
              </div>
              <div class="form-group">
                <label>No Ref Bank</label>
                <input type="text" name="bank_status_number" class="form-control" 
                id="input-bank-status-number"
                required oninvalid="this.setCustomValidity('Harap Isikan No Ref Bank.')" 
                onchange="this.setCustomValidity('')">
              </div>
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

<div id="filter-modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="filter-form" method="POST" action="#">
                    {!! csrf_field() !!}
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Cluster/Perumahan</label>
                            <select id="filter-cluster">
                                <option value="0"> - Semua Cluster - </option>
                                @foreach($clusters as $cluster)
                                <option value="{{$cluster['id']}}">{{$cluster['name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select id="filter-status">
                                <option value="0"> - Semua Status - </option>
                                @foreach($statuses as $status)
                                <option value="{{$status['id']}}">{{$status['name']}}</option>
                                @endforeach
                            </select>
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

@section('additionalScriptJS')
<script type="text/javascript">
  $("#show-filter-modal").on('click',function() {
      $('#filter-modal').modal('show');
  });

  $('#filter-cluster').select2({
      width: '100%'
  });

  $('#filter-status').select2({
      width: '100%'
  });

  $('form#filter-form').submit( function( e ) {
      e.preventDefault();
      $('#main-table').DataTable().ajax.reload(null, false);
      $('#filter-modal').modal('hide');
  });

  $("#main-table").on('click', '#booking-installment', function() {
      let id = $(this).data('id');
      $('form#add-form').trigger('reset')
      $('#input-bank-status').val('').trigger('change')
      $('#installment-modal').modal('show');
  });

  $("#main-table").on('click', '#update-bank-status', function() {
      let id = $(this).data('id');
      $.ajax({
        url: BASE_URL+'/bookings/'+id,
        type: "GET",
        dataType: "json",
        beforeSend: function() {

        },
        success: function(res) {
          console.log(res);

          $('#input-id').val(id);
          $('#input-bank-status').val(res.bank_status).trigger('change');
          $('#input-bank-status-number').val(res.bank_status_number);
          $('#bank-status-modal').modal('show');
        }
      });
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
          "url": BASE_URL+"/customer-payment-datatables",
          "dataType": "json",
          "type": "POST",
          "data":function(d) { 
            d._token = "{{csrf_token()}}",
            d.filter = {
              "cluster_id" : $('#filter-cluster option:selected').val(),
              "status_id" : $('#filter-status option:selected').val()
            }
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

  $('#bank-status').select2({
    width: '100%'
  });

  $('form#add-form').submit( function( e ) {
    e.preventDefault();
    var form_data = new FormData( this );

    $.ajax({
      type: 'post',
      url: BASE_URL+'/bookings',
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
                    // $('#main-table').DataTable().ajax.reload(null, false);
                    $('#main-table').DataTable().ajax.reload(null, false);
                    $('#bank-status-modal').modal('hide');
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
    });
  });
</script>
@endsection
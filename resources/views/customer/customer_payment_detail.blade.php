@extends('layouts.main')

@section('title', 'Pembayaran')

@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Detail Pembayaran a/n {{$data['customer_name']}}</h4>
        <ul class="nav nav-tabs nav-tabs-solid">
          @foreach($customer_costs as $keyTitle => $customer_cost)
            <li class="nav-item"><a class="nav-link {{$keyTitle < 1 ? 'active' : ''}}" href="#solid-tab{{$keyTitle}}" data-toggle="tab">{{$customer_cost['key_name']}}</a></li>
          @endforeach
        </ul>
        <div class="tab-content">
          @foreach($customer_costs as $keyContent => $customer_cost_content)
            <div class="tab-pane {{$keyContent < 1 ? 'show active' : ''}}" id="solid-tab{{$keyContent}}">
              <div class="col-md-12">
                <h3>Nilai Kontrak : Rp {{number_format($customer_cost_content['value'])}}</h3>
              </div>
              <div class="col-md-12 d-flex">
                <div class="card card-table flex-fill">
                  <div class="card-header">
                    <div class="row align-items-center">
                      <div class="col">
                        <h3 class="card-title mb-0">Daftar Pembayaran {{$customer_cost_content['key_name']}}</h3>
                      </div>
                      <div class="col-auto float-right ml-auto">
                        <button type="button" class="btn add-btn add-payment-button" data-id="{{$customer_cost_content['id']}}"><i class="fa fa-plus"></i> {{$customer_cost_content['key_name']}}</a>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">  
                      <table class="table custom-table table-nowrap mb-0">
                        <thead>
                          <tr>
                            <th width="10%">Tanggal Pembayaran</th>
                            <th width="20%">Nominal Pembayaran</th>
                            <th width="10%">Jenis Pembayaran</th>
                            <th width="50%">Keterangan</th>
                            <th width="10%">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($customer_payments as $customer_payment)
                          @if($customer_payment['customer_cost_id'] == $customer_cost_content['id'])
                          <tr>
                            <td>{{date('d M Y', strtotime(($customer_payment['date'])))}}</td>
                            <td>Rp {{number_format($customer_payment['value'])}}</td>
                            <td>{{strtoupper($customer_payment['payment_type'])}}</td>
                            <td>{{$customer_payment['note']}}</td>
                            <td><button type="button" class="btn btn-success btn-sm">View</button>&nbsp;<button type="button" class="btn btn-primary btn-sm">Edit</button>&nbsp;<button type="button" class="btn btn-danger btn-sm">Delete</button></td>
                          </tr>
                          @endif
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

<div id="add-payment-modal" class="modal custom-modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Pembayaran</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-form" method="POST" action="#">
          {!! csrf_field() !!}
          <input type="hidden" name="customer_cost_id" id="input-customer-cost-id">
          <input type="hidden" name="customer_lot_id" id="input-customer-lot-id" value="{{$id}}">
          <div class="row"> 
            <div class="col-md-12"> 
              <div class="form-group">
                <label>Tanggal</label>
                <input class="form-control" type="text" name="date" id="input-date">
              </div>
              <div class="form-group">
                <label>Nominal</label>
                <input class="form-control" type="text" name="value" id="input-value">
              </div>
              <div class="form-group">
                <label>Tipe Pembayaran</label>
                <select class="form-control" id="input-payment-type" name="payment_type">
                  <option> - Pilih Tipe - </option>
                  <option value="transfer">Transfer</option>
                  <option value="cash">Cash</option>
                </select>
              </div>
              <div class="form-group">
                <label>Bukti Pembayaran</label>
                <input class="form-control" type="file" name="file" id="input-file">
              </div>
              <div class="form-group">
                <label>Catatan</label>
                <textarea class="form-control" name="note" id="input-note"></textarea>
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
@endsection

@section('additionalScriptJS')
<script type="text/javascript">
  $('#input-payment-type').select2({
    width: '100%'
  });

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

  $(".add-payment-button").on('click',function() {
      let customerCostId = $(this).data('id');
      $('#input-customer-cost-id').val(customerCostId);
      $('#add-payment-modal').modal('show');
  });

  $('form#add-form').submit( function( e ) {
    e.preventDefault();
    var form_data = new FormData( this );

    $.ajax({
      type: 'post',
      url: BASE_URL+'/bookings/{{$id}}/payments',
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
                    window.location.reload();
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
      }
    })
  });
</script>
@endsection
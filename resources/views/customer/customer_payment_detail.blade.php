@extends('layouts.main')

@section('title', 'Pembayaran')

@section('content')
<script type="text/javascript">
  let total_receivable = 0;
  let total_receivable_payment = 0;
</script>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Detail Pembayaran a/n {{$data['customer_name']}}</h4>
        <h4 class="card-title">Total Piutang : <span id="total-receivable"></span></h4>
        <h4 class="card-title">Sisa Piutang : <span id="total-receivable-payment" style="color: red;"></span></h4>
        <ul class="nav nav-tabs nav-tabs-solid">
          @foreach($customer_costs as $keyTitle => $customer_cost)
            @php
              $total[$keyTitle] = 0;
            @endphp
            <li class="nav-item"><a class="nav-link {{$keyTitle < 1 ? 'active' : ''}}" href="#solid-tab{{$keyTitle}}" data-toggle="tab">{{$customer_cost['key_name']}}</a></li>
          @endforeach
        </ul>
        <div class="tab-content">
          @foreach($customer_costs as $keyContent => $customer_cost_content)
            <script type="text/javascript">
              total_receivable += parseInt({{$customer_cost_content['value']}});
            </script>
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
                          @php
                            $total[$keyContent] += $customer_payment['value'];
                          @endphp
                          <tr>
                            <td>{{date('d M Y', strtotime(($customer_payment['date'])))}}</td>
                            <td>Rp {{number_format($customer_payment['value'])}}</td>
                            <td>{{strtoupper($customer_payment['payment_type'])}}</td>
                            <td>{{$customer_payment['note']}}</td>
                            {{-- <td>{{ pathinfo($customer_payment['filename'], PATHINFO_EXTENSION) }}</td> --}}
                            <td><button data-payment="{{ $customer_payment }}" data-id="{{ $customer_payment['id'] }}" data-ext="{{ pathinfo($customer_payment['filename'], PATHINFO_EXTENSION) }}" type="button" class="btn-view btn btn-success btn-sm">View</button>&nbsp;<button type="button" data-payment="{{ $customer_payment }}" class="btn edit-payment-button btn-primary btn-sm">Edit</button>&nbsp;<button id="delete" data-id={{ $customer_payment['id'] }} type="button" class="btn btn-danger btn-sm">Delete</button>
                              <a href="#" id="download-pdf"></a></td>

                          </tr>
                          <script type="text/javascript">
                            total_receivable_payment += parseInt({{$customer_payment['value']}});
                          </script>
                          @endif
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <h4 style="color: red; float: right;">Sisa Piutang {{$customer_cost_content['key_name']}} : Rp {{number_format($customer_cost_content['value'] - $total[$keyContent])}}</h4>
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
            <button type="submit" class="btn btn-primary submit-btn loading" 
            data-loading-text='<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Loading...'>
              Submit
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="edit-payment-modal" class="modal custom-modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Pembayaran</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="edit-form" method="POST" action="#">
          {!! csrf_field() !!}
          
          <input type="hidden" name="id" id="edit-customer-payment-id">
          <div class="row"> 
            <div class="col-md-12"> 
              <div class="form-group">
                <label>Tanggal</label>
                <input class="form-control" type="text" name="date" id="edit-input-date">
              </div>
              <div class="form-group">
                <label>Nominal</label>
                <input class="form-control" type="text" name="value" id="edit-input-value">
              </div>
              <div class="form-group">
                <label>Tipe Pembayaran</label>
                <select class="form-control" id="edit-input-payment-type" name="payment_type">
                  <option> - Pilih Tipe - </option>
                  <option value="transfer">Transfer</option>
                  <option value="cash">Cash</option>
                </select>
              </div>
              <div class="form-group">
                <label>Bukti Pembayaran</label>
                <input class="form-control" type="file" name="file" id="edit-input-file">
              </div>
              <div class="form-group">
                <label>Catatan</label>
                <textarea class="form-control" name="note" id="edit-input-note"></textarea>
              </div>
            </div>
          </div>
          <div class="submit-section">
            <button type="submit" class="btn btn-primary submit-btn loading" 
            data-loading-text='<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Loading...'>
              Submit
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div id="img-modal" class="modal custom-modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="img-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img src="" alt="" id="img-img">
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

  $('#edit-input-payment-type').select2({
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

  if($('#edit-input-date').length > 0) {
    $('#edit-input-date').datetimepicker({
      format: 'YYYY-MM-DD',
      icons: {
        up: "fa fa-angle-up",
        down: "fa fa-angle-down",
        next: 'fa fa-angle-right',
        previous: 'fa fa-angle-left'
      }
    });
  }

  $('.btn-view').on('click', function(){
      const id = $(this).data('id');
      const payment = $(this).data('payment');
      const ext = $(this).data('ext');
      if (ext === 'pdf') {
        window.open(BASE_URL+payment.filepath+'/'+payment.filename);
      }else{
          $('#img-modal').modal('show')
          $('#img-img').attr("src",BASE_URL+payment.filepath+'/'+payment.filename)
      }
  })

  $(".add-payment-button").on('click',function() {
      let customerCostId = $(this).data('id');
      $('#input-customer-cost-id').val(customerCostId);
      $('#add-payment-modal').modal('show');
  });

  var customer_payment_id = $('#edit-customer-payment-id');

  $(".edit-payment-button").on('click', function(){
    const customerpayment = $(this).data('payment');
    // $('#edit-customer-payment-id').val(customerpayment.id);
    customer_payment_id.val(customerpayment.id);
    $('#edit-input-date').val(customerpayment.date);
    $('#edit-input-value').val(parseInt(customerpayment.value));  
    $('#edit-input-payment-type').val(customerpayment.payment_type);
    $('#edit-input-note').val(customerpayment.note);  
    $('#edit-payment-modal').modal('show');
  })

  $('form#edit-form').submit( function( e ){
      e.preventDefault();
      var loading_text = $('.loading').data('loading-text');
        $('.loading').html(loading_text).attr('disabled', true);
      //console.log($('#edit-input-file')[0].files[0]);

      var form_data = new FormData( this );

    $.ajax({
      type: 'post',
      url: BASE_URL+`/bookings/payments/{${customer_payment_id.val()}}`,
      data: form_data,
      cache: false,
      contentType: false,
      processData: false,
      dataType: 'json',
      beforeSend: function() {
        
      },
      success: function(res) {
        $('.loading').html('Submit').attr('disabled', false)
        console.log(res);
        if(res.status == 'success'){
            setTimeout(function() {
                swal({
                    title: "Sukses",
                    text: res.message,
                    type:"success",
                    html: true
                }, function() {
                    window.location.reload();
                });
            }, 500);
        } else {
            swal({
                title: "Gagal",
                text: res.message,
                showConfirmButton: true,
                confirmButtonColor: '#0760ef',
                type:"error",
                html: true
            });
        }
      }
    })
  });

  $('form#add-form').submit( function( e ) {
    e.preventDefault();
    var loading_text = $('.loading').data('loading-text');
        $('.loading').html(loading_text).attr('disabled', true);
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
        $('.loading').html('Submit').attr('disabled', false)
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

  $(document).on('click', '#delete', function(e){
    event.preventDefault()
    var id = $(this).data("id")

    swal({
            title: 'Apakah kamu yakin untuk menghapus?',
            text: "Data ini tidak bisa dikebalikan lagi",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Hapus'
        }, function(){
          $.ajax({
            type: 'get',
            url: BASE_URL+`/bookings/payments/${id}/delete`,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function() {
              
            },
            success: function(res) {
              if(res.status == 'success'){
                  setTimeout(function() {
                    
                      swal({
                          title: "sukses",
                          text: res.message,
                          type:"success",
                          html: true
                      }, function() {
                          window.location.reload();
                      });
                  }, 500);
              } else {
                  swal({
                      title: "Gagal",
                      text: res.message,
                      showConfirmButton: true,
                      confirmButtonColor: '#0760ef',
                      type:"error",
                      html: true
                  });
              }
            }
          })
        })
  });

  $("#total-receivable").text(addSeparator(total_receivable, '.', '.', ','));
  $("#total-receivable-payment").text(addSeparator((total_receivable - total_receivable_payment), '.', '.', ','));
</script>
@endsection
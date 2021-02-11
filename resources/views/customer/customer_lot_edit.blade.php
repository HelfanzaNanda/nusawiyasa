@extends('layouts.main')

@section('title', 'Form Booking')

@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title mb-0">Form Booking</h4>
      </div>
      <form id="update-form" method="POST" action="#" enctype="multipart/form-data">
        <div class="card-body">
          {!! csrf_field() !!}
          <input type="hidden" value="{{ $data->payment_type }}" id="p-type">
          <input type="hidden" value="{{ $data->id }}" name="id" id="id">
          <div class="form-group row">
            <label class="col-form-label col-md-2">Konsumen</label>
            <div class="col-md-10">
              <select id="input-customer" name="customer_id"> 
                <option> - Pilih Konsumen - </option>
                @foreach($customers as $customer)
                  <option value="{{$customer['id']}}" {{ $customer['id'] == $data->customer_id ? 'selected' : '' }} >{{$customer['name']}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">Kapling</label>
            <div class="col-md-10">
              <select id="input-lot" name="lot_id"> 
                <option value="0"> - Pilih Kapling - </option>
                @foreach($lots as $lot)
                  {{-- @if(!$lot['booking_id'])                  
                    <option value="{{$lot['id']}}" {{ $lot['id'] == $data->lot_id ? 'selected' : '' }}>{{$lot['name']}} - {{$lot['block']}} / {{$lot['unit_number']}}</option>
                  @endif --}}
                  <option value="{{$lot['id']}}" {{ $lot['id'] == $data->lot_id ? 'selected' : '' }}>{{$lot['name']}} - {{$lot['block']}} / {{$lot['unit_number']}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">Tanggal Booking</label>
            <div class="col-md-10">
              <input class="form-control floating" type="text" value="{{ $data->booking_date }}" id="input-booking-date" name="booking_date">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">Metode Pembayaran</label>
            <div class="col-md-10">
              <select id="input-payment-type" name="payment_type"> 
                <option value="0"> - Pilih Metode Pembayaran - </option>
                <option value="cash" {{ $data->payment_type == 'cash' ? 'selected' : ''  }} >Cash Keras</option>
                <option value="cash_in_stages" {{ $data->payment_type == 'cash_in_stages' ? 'selected' : ''  }} >Cash Bertahap</option>
                <option value="credit" {{ $data->payment_type == 'credit' ? 'selected' : ''  }} >KPR</option>
              </select>
            </div>
          </div>
          <hr>
          <h4 class="text-primary">Biaya</h4>
          <div class="row" id="cost"> 

          </div>
          <hr>
          <h4 class="text-primary">Persyatan Dokumen</h4>
          <div class="row" id="term"> 

          </div>
        </div>
        <div class="card-footer">
          <div class="col-auto float-right ml-auto pb-2">
            <button type="submit" class="btn btn-primary submit-btn loading" 
            data-loading-text='<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Loading...'>
              Submit
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('additionalScriptJS')
<script type="text/javascript">
  $('#input-customer').select2({
    width: '100%'
  });

  $('#input-lot').select2({
    width: '100%'
  });

  $('#input-payment-type').select2({
    width: '100%'
  });

  if($('#input-booking-date').length > 0) {
    $('#input-booking-date').datetimepicker({
      format: 'YYYY-MM-DD',
      icons: {
        up: "fa fa-angle-up",
        down: "fa fa-angle-down",
        next: 'fa fa-angle-right',
        previous: 'fa fa-angle-left'
      }
    });
  }

  $(document).ready(function() {
    var payment_type = $('#input-payment-type').val();
    payment_type ? showFirstItem() : ''
  });

  function showFirstItem(){
      $("#cost").empty();
      $("#term").empty();
      let cost = '';
      let term = '';
      $.each({!! $customer_costs !!}, function(key, value) {
          cost += '<div class="col-md-6 mt-2">';
          cost += '<label>'+value.key_name+'</label>';
          cost += '<input class="form-control" type="number" name="customer_costs['+value.ref_term_purchasing_customer_id+']" value="'+parseFloat(value.value)+'">';
          cost += '</div>';
      });
      $.each({!! $customer_terms !!}, function(key, value) {
          term += '<div class="col-md-6 mt-2">';
          term += '<label >'+value.key_name+'</label>';
          term += '<input class="form-control" type="file" title="customer_terms['+value.filename+']" name="customer_terms['+value.ref_term_purchasing_customer_id+']" value="'+value.filepath+'/'+value.filename+'">';
          term += '</div>';
      });

      $("#cost").append(cost);
      $("#term").append(term); 
  }

  $('#input-payment-type').on('change', function() {
    
    var payment_type = $(this).val();
    var p_type = $('#p-type').val();
    if (payment_type == p_type) {
      showFirstItem() 
    }else if(payment_type) {
      $.ajax({
        url: BASE_URL+'/ref/term_purchasing_customers?all=true&payment_type='+payment_type+'&is_deleted=0',
        type: "GET",
        dataType: "json",
        beforeSend: function() {
          $("#cost").empty();
          $("#term").empty(); 
        },
        success: function(res) {
          let cost = '';
          let term = '';
          $.each(res.data, function(key, value) {
            if (value.terms_type == 'cost') {
                cost += '<div class="col-md-6 mt-2">';
                cost += '<label>'+value.name+'</label>';
                cost += '<input class="form-control" type="number" name="customer_costs['+value.id+']">';
                cost += '</div>';
            } else {
                term += '<div class="col-md-6 mt-2">';
                term += '<label>'+value.name+'</label>';
                term += '<input class="form-control" type="file" name="customer_terms['+value.id+']">';
                term += '</div>';
            }
          });

          $("#cost").append(cost);
          $("#term").append(term); 
        }
      });
    }
  });



  $('form#update-form').submit( function( e ) {
    e.preventDefault();
    var loading_text = $('.loading').data('loading-text');
        $('.loading').html(loading_text).attr('disabled', true);
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
        $('.loading').html('Submit').attr('disabled', false)
        if(msg.status == 'success'){
            setTimeout(function() {
                swal({
                    title: "Sukses",
                    text: msg.message,
                    type:"success",
                    html: true
                }, function() {
                    // $('#main-table').DataTable().ajax.reload(null, false);
                    $('#add-modal').modal('hide');
                    window.location.replace("{{url('/booking-page')}}");
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
      },
      error : function(jqXHR, textStatus, errorThrown){
        $('.loading').html('Submit').attr('disabled', false)
      }
    })
  });
</script>
@endsection
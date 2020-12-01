@extends('layouts.main')

@section('title', 'Form Booking')

@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title mb-0">Form Booking</h4>
      </div>
      <div class="card-body">
        <form id="add-form" method="POST" action="#">
          {!! csrf_field() !!}
          <div class="form-group row">
            <label class="col-form-label col-md-2">Konsumen</label>
            <div class="col-md-10">
              <select id="input-customer" name="customer_id"> 
                <option> - Pilih Konsumen - </option>
                @foreach($customers as $customer)
                  <option value="{{$customer['id']}}">{{$customer['name']}}</option>
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
                  <option value="{{$lot['id']}}">{{$lot['name']}} - {{$lot['block']}} / {{$lot['unit_number']}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">Tanggal Booking</label>
            <div class="col-md-10">
              <input class="form-control floating" type="text" value="{{date('Y-m-d')}}" id="input-booking-date" name="booking_date">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">Metode Pembayaran</label>
            <div class="col-md-10">
              <select id="input-payment-type" name="payment_type"> 
                <option value="0"> - Pilih Metode Pembayaran - </option>
                <option value="cash">Cash Keras</option>
                <option value="cash_in_stages">Cash Bertahap</option>
                <option value="credit">KPR</option>
              </select>
            </div>
          </div>

          <h4 class="text-primary">Biaya</h4>
          <div class="row" id="cost"> 

          </div>

          <h4 class="text-primary">Persyatan Dokumen</h4>
          <div class="row" id="term"> 

          </div>

          <div class="col-auto float-right ml-auto">
            <button class="btn btn-primary" type="submit">Submit</button>
          </div>
        </form>
      </div>
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

  $("#input-lot").val({{$lot_id}}).trigger("change");

  $('#input-payment-type').on('change', function() {
    var payment_type = $(this).val();

    console.log(payment_type);
    if(payment_type) {
      $.ajax({
        url: BASE_URL+'/ref/term_purchasing_customers?all=true&payment_type='+payment_type,
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
              cost += '<div class="col-md-6">';
              cost += '<label>'+value.name+'</label>';
              cost += '<input class="form-control" type="number" name="customer_costs['+value.id+']">';
              cost += '</div>';
            } else {
              term += '<div class="col-md-6">';
              term += '<label>'+value.name+'</label>';
              term += '<input class="form-control" type="file" name="customer_terms['+value.id+']">';
              term += '</div>';
            }
          });

          $("#cost").append(cost);
          $("#term").append(term); 
        }
      });
    } else {
        // $('#city').empty();
    }
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
      }
    })
  });
</script>
@endsection
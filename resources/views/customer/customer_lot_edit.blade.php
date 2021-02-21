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
              {{-- <input type="text" name="" id="" class="form-control" readonly value="{{ $data->payment_type }}"> --}}
              <input type="hidden" name="payment_type" value="{{ $data->payment_type }}">
              <select id="input-payment-type" disabled> 
                <option value="cash" {{ $data->payment_type == 'cash' ? 'selected' : ''  }} >Cash Keras</option>
                <option value="cash_in_stages" {{ $data->payment_type == 'cash_in_stages' ? 'selected' : ''  }} >Cash Bertahap</option>
                <option value="credit" {{ $data->payment_type == 'credit' ? 'selected' : ''  }} >KPR</option>
              </select>
            </div>
          </div>
          <hr>
          <h4 class="text-primary">Biaya</h4>
          <div class="row" id="cost">
            {{-- {{ dd($customer_costs) }} --}}
            {{-- @foreach ($customer_costs as $cost)
              <div class="col-md-6 mt-2">
                <label>{{ $cost->key_name }}</label>
                <input class="form-control" type="number" 
                name="customer_costs[{{ $cost->ref_term_purchasing_customer_id }}]" 
                value="{{ floatval($cost->value) }}">
              </div>
            @endforeach --}}
          </div>
          <hr>
          <h4 class="text-primary">Persyatan Dokumen</h4>
          <div class="row" id="term"> 
            {{-- @foreach ($customer_terms as $key => $term)
                <div class="col-md-6 mt-2">                      
                  <label >{{ $term->key_name }}</label>
                  <input type="hidden" name="term_ids[{{ $term->ref_term_purchasing_customer_id }}]" id="id_new_img-{{ $key }}">
                  <input class="form-control mb-2" data-id={{ $term->id }} onchange="readURL(this, {{ $key }});"
                   type="file" 
                  name="customer_terms[{{ $term->ref_term_purchasing_customer_id }}]" 
                  value="{{ $term->filepath.'/'.$term->filename }}">
                  <img id="preview-img-{{ $key }}" width="100" height="100"
                  src="{{ $term->filepath.'/'.$term->filename }}" >
                </div>                
            @endforeach --}}
          </div>
        </div>
        <div class="card-footer">
          <div class="col-auto float-right ml-auto pb-2">
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
@endsection

@section('additionalScriptJS')
<script type="text/javascript">


$(document).ready(function() {
    var cus_terms = {!! $customer_terms !!}
    var cus_costs = {!! $customer_costs !!}
    var payment_type = $('#input-payment-type').val();
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
            var filtered_costs = filterItems(cus_costs, value.id);
            if (value.terms_type == 'cost') {
              cost += '<div class="col-md-6 mt-2">';
              cost += '<label>'+value.name+'</label>';
              if (filtered_costs.length > 0) {
                cost += '<input class="form-control" type="number" name="customer_costs['+value.id+']" value="'+parseFloat(filtered_costs[0].value)+'">'; 
              }else{
                cost += '<input class="form-control" type="number" name="customer_costs['+value.id+']">';
              }
              cost += '</div>';
            } else {
              var filtered_terms = filterItems(cus_terms, value.id);
              term += '<div class="col-md-6 mt-2">';
              term += '<label>'+value.name+'</label>';
              term += '<input type="hidden" name="term_ids['+value.id+']" id="id_new_img-'+key+'">'
              if (filtered_terms.length > 0) {
                  term += '<input class="form-control mb-2" data-id='+filtered_terms[0].id+' onchange="readURL(this, '+key+');" type="file"'
                  term += 'name="customer_terms['+value.id+']" >'
                  term += '<img id="preview-img-'+key+'" width="100" height="100"'
                  term += 'src="'+filtered_terms[0].filepath+'/'+filtered_terms[0].filename+'" >'
                  // term += '<input class="form-control" type="file" name="customer_terms['+value.id+']">';
              }else{
                  term += '<input class="form-control mb-2"  onchange="readURL(this, '+key+');" type="file"'
                  term += 'name="customer_terms['+value.id+']" >'
                  term += '<img id="preview-img-'+key+'" width="100"  height="100" style="visibility: hidden;">'
              }
              term += '</div>';
            }
          });

          $("#cost").append(cost);
          $("#term").append(term); 
        }
      });
})

  function filterItems(items, searchVal) {
    return items.filter((item) => Object.values(item).includes(searchVal));
  }

  function readURL(input, key) {
      var termId = input.getAttribute('data-id');
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
              $('#preview-img-'+key).attr('src', e.target.result).attr('style', "visibility: ''");
              $('#id_new_img-'+key).val(termId);
          };
          reader.readAsDataURL(input.files[0]);
          
      }
  }

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
          //console.log(msg);
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
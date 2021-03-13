@extends('layouts.main')

@section('title', 'Form Booking')

@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title mb-0">Form Booking</h4>
      </div>
      <form id="add-form" method="POST" action="#">
        <div class="card-body">
          {!! csrf_field() !!}
          <div class="form-group row">
            <label class="col-form-label col-md-2">Konsumen</label>
            <div class="col-md-10">
              <select id="input-customer" name="customer_id"
              required oninvalid="this.setCustomValidity('Harap Isikan Konsumen.')" 
                onchange="this.setCustomValidity('')"> 
                <option value=""> - Pilih Konsumen - </option>
                @foreach($customers as $customer)
                  <option value="{{$customer['id']}}">{{$customer['name']}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">Kapling</label>
            <div class="col-md-10">
              <select id="input-lot" name="lot_id"
              required oninvalid="this.setCustomValidity('Harap Isikan Kapling.')" 
                onchange="this.setCustomValidity('')"> 
                <option value="0"> - Pilih Kapling - </option>
                @foreach($lots as $lot)
                  @if(!$lot['booking_id'])
                  <option value="{{$lot['id']}}">{{$lot['name']}} - {{$lot['block']}} / {{$lot['unit_number']}}</option>
                  @endif
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">Tanggal Booking</label>
            <div class="col-md-10">
              <input class="form-control floating" type="text" value="{{date('Y-m-d')}}" 
              id="input-booking-date" name="booking_date"
              required oninvalid="this.setCustomValidity('Harap Isikan Tanggal Booking.')" 
                onchange="this.setCustomValidity('')">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">Metode Pembayaran</label>
            <div class="col-md-10">
              <select id="input-payment-type" name="payment_type"
              required oninvalid="this.setCustomValidity('Harap Isikan Metode Pembayaran.')" 
                onchange="this.setCustomValidity('')"> 
                <option value=""> - Pilih Metode Pembayaran - </option>
                <option value="cash">Cash Keras</option>
                <option value="cash_in_stages">Cash Bertahap</option>
                <option value="credit">KPR</option>
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
            <button type="button" class="btn btn-close mr-2 btn-secondary" data-dismiss="modal">Kembali</button>
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
$('.btn-close').on('click', function(){
      window.location.replace('bookings')
  })
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
    if(payment_type) {
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
              term += '<input class="form-control" type="file" onchange="readURL(this, '+key+');"  name="customer_terms['+value.id+']">';
              // term += '<img id="preview-img-'+key+'" width="100"  height="100" style="visibility: hidden;">'
              // term += '<a class="mt-2" id="preview-pdf-'+key+'" target="_blank" style="display:none">lihat pdf</a>'
              term += '<a class="mt-2 preview-'+key+'" target="_blank" ></a>'
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

  function readURL(input, key) {
      var termId = input.getAttribute('data-id');
      if (input.files && input.files[0]) {
        $('.preview-'+key).attr("href", URL.createObjectURL(event.target.files[0]));  
        if (input.files[0].type == "application/pdf") {
          $('.preview-'+key).text('Lihat Pdf');
        }else{
          $('.preview-'+key).text('Lihat Image');
        }
      }
  }

  // function readURL(input, key) {
  //     var termId = input.getAttribute('data-id');
  //     if (input.files && input.files[0]) {
  //       if (input.files[0].type == "application/pdf") {
  //         $('#preview-pdf-'+key).show()
  //         $('#preview-pdf-'+key).attr("href", URL.createObjectURL(event.target.files[0]))
  //         $('#preview-img-'+key).hide()
  //       }else{
  //         var reader = new FileReader();
  //         reader.onload = function (e) {
  //             $('#preview-pdf-'+key).hide()
  //             $('#preview-img-'+key).attr('src', e.target.result).attr('style', "visibility: ''");
  //             $('#id_new_img-'+key).val(termId);
  //         };
  //         reader.readAsDataURL(input.files[0]);
  //       }
  //     }
  // }

  



  $('form#add-form').submit( function( e ) {
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
        $('.loading').html('Submit').attr('disabled', false)
      },
      error: function(params) {
          $('.loading').html('Submit').attr('disabled', false)
      }
    })
  });
</script>
@endsection


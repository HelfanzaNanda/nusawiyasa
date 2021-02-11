@extends('layouts.main')

@section('title', 'Detail Konfirmasi Konsumen')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Konfirmasi Konsumen</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Konfirmasi Konsumen</a></li>
        <li class="breadcrumb-item active">Data Konfirmasi Konsumen</li>
      </ul>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Detail Konfirmasi a/n {{$customer['customer']['user']['name']}}</h3>
      </div>
      <form id="add-form" method="POST" action="#">
        {!! csrf_field() !!}
        <div class="card-body ml-3 mt-3 mr-3 mb-3">
          <div class="row"> 
            {{-- @foreach($customer_costs as $customer_cost) --}}
              <div class="col-md-6">
                <label>Konfirmasi Konsumen</label>
                <input type="file" class="dropify" data-max-file-size="10M" data-default-file="{{(is_array($records) && count($records) > 0) ? url('/').$records[0]['filepath'].'/'.$records[0]['filename'] : ''}}" name="file" />
              </div>
            {{-- @endforeach --}}
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
  $('form#add-form').submit( function( e ) {
    e.preventDefault();
    var loading_text = $('.loading').data('loading-text');
        $('.loading').html(loading_text).attr('disabled', true);
    var form_data = new FormData( this );

    form_data.append('customer_id', '{{$customer['customer_id']}}');
    form_data.append('lot_id', '{{$customer['lot_id']}}');
    form_data.append('status', 1);

    $.ajax({
      type: 'post',
      url: BASE_URL+'/customer-confirmation',
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
              window.location.replace("{{url('/customer-confirmation')}}");
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
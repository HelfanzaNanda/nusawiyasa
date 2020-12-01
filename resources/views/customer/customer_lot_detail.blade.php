@extends('layouts.main')

@section('title', 'Booking')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Detail Booking</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Booking</a></li>
        <li class="breadcrumb-item active">Detail Booking</li>
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
        <h3 class="card-title mb-0">Detail Booking a/n {{$data['customer_name']}}</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <h4 class="text-primary">Biaya</h4>
        <div class="row"> 
          @foreach($customer_costs as $customer_cost)
            <div class="col-md-6">
              <label>{{$customer_cost['key_name']}}</label>
              <h5>Rp {{number_format($customer_cost['value'])}}</h5>
            </div>
          @endforeach
        </div>
        <hr/>
        <h4 class="text-primary">Persyatan Dokumen</h4>
        <div class="row"> 
          @foreach($customer_terms as $customer_term)
            <div class="col-md-6">
              <label>{{$customer_term['key_name']}}</label><br>
              <img src="{{env('APP_URL').$customer_term['filepath'].'/'.$customer_term['filename']}}" width="350px">
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('additionalScriptJS')
<script type="text/javascript">

  $('#input-province').select2({
    width: '100%'
  });

  $('#input-city').select2({
    width: '100%'
  });
</script>
@endsection
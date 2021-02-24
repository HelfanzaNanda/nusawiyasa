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
        <div id="term" class="row"></div>
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

  $(document).ready(function() {
    var cus_terms = {!! $customer_terms !!}
    var cus_costs = {!! $customer_costs !!}
    var data = {!! $data !!}
    var payment_type = data.payment_type
    $.ajax({
        url: BASE_URL+'/ref/term_purchasing_customers?all=true&payment_type='+payment_type+'&is_deleted=0',
        type: "GET",
        dataType: "json",
        beforeSend: function() {
          //$("#cost").empty();
          $("#term").empty(); 
        },
        success: function(res) {
          let cost = '';
          let term = '';
          $.each(res.data, function(key, value) {
            var filtered_costs = filterItems(cus_costs, value.id);
            if (value.terms_type == 'term') {
              var filtered_terms = filterItems(cus_terms, value.name);
              //console.log(filtered_terms);
              term += '<div class="col-md-6 mt-2">';
              term += '<label>'+value.name+'</label>'
              term += '<br/>';
              if (filtered_terms.length > 0) {
                  if (filtered_terms[0].filetype == 'pdf') {
                      term += '<a href="'+filtered_terms[0].filepath+'/'+filtered_terms[0].filename+'"'
                      term += 'class="mt-2 preview-'+key+'" target="_blank" >Lihat Pdf</a>'
                  }else{
                      term += '<a href="'+filtered_terms[0].filepath+'/'+filtered_terms[0].filename+'"'
                      term += 'class="mt-2 preview-'+key+'" target="_blank" >Lihat Image</a>'
                  }
              }else{
                  term += '<p style="font-size: small">Belum Upload Persyaratan</p>'
              }
              term += '</div>';
            }
          });

          //$("#cost").append(cost);
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
        $('.preview-'+key).attr("href", URL.createObjectURL(event.target.files[0]));  
        $('#id_new_img-'+key).val(termId);
        if (input.files[0].type == "application/pdf") {
          $('.preview-'+key).text('Lihat Pdf');
        }else{
          $('.preview-'+key).text('Lihat Image');
        }
      }
  }
</script>
@endsection
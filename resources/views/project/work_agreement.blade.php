@extends('layouts.main')

@section('title', 'Perjanjian Kerja')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Perjanjian Kerja</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Perjanjian Kerja</a></li>
        <li class="breadcrumb-item active">Data Perjanjian Kerja</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
      <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah Perjanjian Kerja</a>
    </div>
  </div>
</div>
<!-- /Page Header -->
@endsection

@section('additionalScriptJS')
<script type="text/javascript">
  $("#main-table").DataTable({
      "pageLength": 10,
      "processing": true,
      "serverSide": true,
      "ajax":{
          "url": BASE_URL+"/customer-datatables",
          "dataType": "json",
          "type": "POST",
          "data":function(d) { 
            d._token = "{{csrf_token()}}"
          },
      },
      "columns": [
          {data: 'id', name: 'id', width: '5%', "visible": false},
          {data: 'name', name: 'name', className: 'td-limit'},
          {data: 'email', name:'email', className: 'td-limit'},
          {data: 'phone', name: 'phone', className: 'td-limit', orderable: false},
          {data: 'province', name: 'province', className: 'td-limit'},
          {data: 'city', name: 'city', className: 'td-limit'},
          {data: 'action', name: 'action', className: 'text-right'},
      ],
  });

  $("#show-add-modal").on('click',function() {
      $('#add-modal').modal('show');
  });

  $('#input-province').select2({
    width: '100%'
  });

  $('#input-city').select2({
    width: '100%'
  });

  $('#input-province').on('change', function() {
    var province_id = $("option:selected", this).data('province-code');
    if(province_id) {
      $.ajax({
        url: BASE_URL+'/city_by_province/'+province_id,
        type: "GET",
        dataType: "json",
        beforeSend: function() {
            $('#input-city').empty();
        },
        success: function(data) {
          $.each(data, function(key, value) {
              $('#input-city').append('<option value="'+ value.name +'" data-city="'+ value.code+'">' + value.name + '</option>');
          });
        }
      });
    } else {

    }
  });

  $('form#add-form').submit( function( e ) {
    e.preventDefault();
    var form_data = new FormData( this );

    $.ajax({
      type: 'post',
      url: BASE_URL+'/customers',
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
                $('#main-table').DataTable().ajax.reload(null, false);
                $('#add-modal').modal('hide');
                // window.location.replace(URL_LIST_PURCHASES);
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
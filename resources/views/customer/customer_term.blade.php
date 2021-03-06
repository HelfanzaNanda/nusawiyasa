@extends('layouts.main')

@section('title', 'Customer')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Konsumen</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Konsumen</a></li>
        <li class="breadcrumb-item active">Data Konsumen</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
      {{-- <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah Konsumen</a> --}}
    </div>
  </div>
</div>
<!-- /Page Header -->

<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Data Konsumen</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <div class="table-responsive">
          <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No. HP</th>
                <th>Provinsi</th>
                <th>Kota</th>
                <th class="text-right" width="10%">Aksi</th>
              </tr>
            </thead>
            <tbody>
     
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Add Salary Modal -->
<div id="add-modal" class="modal custom-modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Konsumen</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-form" method="POST" action="#">
          {!! csrf_field() !!}
          <div class="row"> 
            <div class="col-sm-6"> 
              <div class="form-group">
                <label>Nama</label>
                <input class="form-control" type="text" name="name"
                required oninvalid="this.setCustomValidity('Harap Isikan Nama.')" 
                onchange="this.setCustomValidity('')">
              </div>
              <div class="form-group">
                <label>Email</label>
                <input class="form-control" type="text" name="email"
                required oninvalid="this.setCustomValidity('Harap Isikan Email.')" 
                onchange="this.setCustomValidity('')">
              </div>
              <div class="form-group">
                <label>No. HP</label>
                <input class="form-control" type="text" name="phone"
                required oninvalid="this.setCustomValidity('Harap Isikan No HP.')" 
                onchange="this.setCustomValidity('')">
              </div>
              <div class="form-group">
                <label>Tempat Lahir</label>
                <input class="form-control" type="text" name="place_of_birth"
                required oninvalid="this.setCustomValidity('Harap Isikan Tempat Lahir.')" 
                onchange="this.setCustomValidity('')">
              </div>
              <div class="form-group">
                <label>Tanggal Lahir</label>
                <input class="form-control" type="text" name="date_of_birth"
                required oninvalid="this.setCustomValidity('Harap Isikan Tanggal Lahir.')" 
                onchange="this.setCustomValidity('')">
              </div>
              <div class="form-group">
                <label>Pekerjaan</label>
                <input class="form-control" type="text" name="occupation"
                required oninvalid="this.setCustomValidity('Harap Isikan Pekerjaan.')" 
                onchange="this.setCustomValidity('')">
              </div>
            </div>
            <div class="col-sm-6">  
              <div class="form-group">
                <label>Kota</label>
                <select id="input-city" name="city"
                required oninvalid="this.setCustomValidity('Harap Isikan Kota.')" 
                onchange="this.setCustomValidity('')">
                  <option> - Pilih Kota - </option>
                </select>
              </div>
              <div class="form-group">
                <label>Kecamatan</label>
                <input class="form-control" type="text" name="district"
                required oninvalid="this.setCustomValidity('Harap Isikan Kecamatan.')" 
                onchange="this.setCustomValidity('')">
              </div>
              <div class="form-group">
                <label>Kelurahan</label>
                <input class="form-control" type="text" name="sub_district"
                required oninvalid="this.setCustomValidity('Harap Isikan Kelurahan.')" 
                onchange="this.setCustomValidity('')">
              </div>
              <div class="form-group">
                <label>Alamat</label>
                <textarea class="form-control" name="address" rows="5"
                required oninvalid="this.setCustomValidity('Harap Isikan Alamat.')" 
                onchange="this.setCustomValidity('')"></textarea>
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
<!-- /Add Salary Modal -->
@endsection

@section('additionalScriptJS')
<script type="text/javascript">
  $("#main-table").DataTable({
      "pageLength": 10,
      "processing": true,
      "serverSide": true,
      // "searching": false,
      // "ordering": false,
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
    $('form#add-form').trigger('reset')
    $('#input-city').val('').trigger('change')
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
        // $('#city').empty();
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
        $('.loading').html('Submit').attr('disabled', false)
      },
      error: function(params) {
          $('.loading').html('Submit').attr('disabled', false)
      }
    })
  });
</script>
@endsection
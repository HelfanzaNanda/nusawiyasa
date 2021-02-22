@extends('layouts.main')

@section('title', 'Supplier')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Supplier</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Supplier</a></li>
        <li class="breadcrumb-item active">Data Supplier</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
      <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah Supplier</a>
    </div>
  </div>
</div>
<!-- /Page Header -->

<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Supplier</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <div class="table-responsive">
          <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
            <thead>
              <tr>
                <th>#</th>
                <th width="15%">Nama Supplier</th>
                <th>Kota</th>
                <th>No. Telp</th>
                <th>Email</th>
                <th>Hutang</th>
                <th>Nama PIC</th>
                <th>No. Telp PIC</th>
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
        <h5 class="modal-title">Tambah Supplier</h5>
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
                <label>Nama Supplier</label>
                <input class="form-control" type="text" name="name">
              </div>
              <div class="form-group">
                <label>Email Supplier</label>
                <input class="form-control" type="text" name="email">
              </div>
              <div class="form-group">
                <label>No. HP Supplier</label>
                <input class="form-control" type="text" name="phone">
              </div>
              <div class="form-group">
                <label>Nama Penanggung Jawab (PIC)</label>
                <input class="form-control" type="text" name="pic_name">
              </div>
              <div class="form-group">
                <label>No. Telp Penanggung Jawab</label>
                <input class="form-control" type="text" name="pic_phone">
              </div>
            </div>
            <div class="col-sm-6">  
              <div class="form-group">
                <label>Provinsi</label>
                <select id="input-province" name="province"> 
                  <option> - Pilih Provinsi - </option>
                  @foreach($provinces as $province)
                    <option value="{{$province['name']}}" data-province-code="{{$province['code']}}">{{$province['name']}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Kota</label>
                <select id="input-city" name="city"> 
                  <option> - Pilih Kota - </option>
                </select>
              </div>
              <div class="form-group">
                <label>Kecamatan</label>
                <input class="form-control" type="text" name="district">
              </div>
              <div class="form-group">
                <label>Kelurahan</label>
                <input class="form-control" type="text" name="sub_district">
              </div>
              <div class="form-group">
                <label>Alamat</label>
                <textarea class="form-control" name="address" rows="5"></textarea>
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

<!-- Update Salary Modal -->
<div id="update-modal" class="modal custom-modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Supplier</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="update-form" method="POST" action="#">
          {!! csrf_field() !!}
          <div class="row"> 
            <div class="col-sm-6"> 
              <div class="form-group">
                <label>Nama Supplier</label>
                <input class="form-control" type="text" name="name" id="name">
                <input class="form-control" type="hidden" name="id" id="id-update">
              </div>
              <div class="form-group">
                <label>Email Supplier</label>
                <input class="form-control" type="text" name="email" id="email">
              </div>
              <div class="form-group">
                <label>No. HP Supplier</label>
                <input class="form-control" type="text" name="phone" id="phone">
              </div>
              <div class="form-group">
                <label>Nama Penanggung Jawab (PIC)</label>
                <input class="form-control" type="text" name="pic_name" id="pic_name">
              </div>
              <div class="form-group">
                <label>No. Telp Penanggung Jawab</label>
                <input class="form-control" type="text" name="pic_phone" id="pic_phone">
              </div>
            </div>
            <div class="col-sm-6">  
              <div class="form-group">
                <label>Provinsi</label>
                <select id="input-province-update" name="province"> 
                  <option> - Pilih Provinsi - </option>
                  @foreach($provinces as $province)
                    <option value="{{$province['name']}}" data-province-code="{{$province['code']}}">{{$province['name']}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Kota</label>
                <select id="input-city-update" name="city"> 
                  <option> - Pilih Kota - </option>
                </select>
              </div>
              <div class="form-group">
                <label>Kecamatan</label>
                <input class="form-control" type="text" name="district" id="district">
              </div>
              <div class="form-group">
                <label>Kelurahan</label>
                <input class="form-control" type="text" name="subdistrict" id="subdistrict">
              </div>
              <div class="form-group">
                <label>Alamat</label>
                <textarea class="form-control" name="address" rows="5" id="address"></textarea>
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
@endsection

@section('additionalScriptJS')
<script type="text/javascript">
  $("#main-table").DataTable({
      "pageLength": 10,
      "processing": true,
      "serverSide": true,
      "ajax":{
          "url": BASE_URL+"/supplier-datatables",
          "dataType": "json",
          "type": "POST",
          "data":function(d) { 
            d._token = "{{csrf_token()}}"
          },
      },
      "columns": [
          {data: 'id', name: 'id', width: '5%', "visible": false},
          {data: 'name', name: 'name'},
          {data: 'city', name: 'city'},
          {data: 'phone', name: 'phone'},
          {data: 'email', name: 'email'},
          {data: 'debt', name: 'debt'},
          {data: 'pic_name', name: 'pic_name'},
          {data: 'pic_phone', name: 'pic_phone'},
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
    var loading_text = $('.loading').data('loading-text');
        $('.loading').html(loading_text).attr('disabled', true);
    var form_data = new FormData( this );

    $.ajax({
      type: 'post',
      url: BASE_URL+'/supplier',
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

  $('#input-province-update').select2({
    width: '100%'
  });

  $('#input-city-update').select2({
    width: '100%'
  });

  $('#input-province-update').on('change', function() {
    var province_id = $("option:selected", this).data('province-code');
    if(province_id) {
      $.ajax({
        url: BASE_URL+'/city_by_province/'+province_id,
        type: "GET",
        dataType: "json",
        beforeSend: function() {
            $('#input-city-update').empty();
        },
        success: function(data) {
          $.each(data, function(key, value) {
              $('#input-city-update').append('<option value="'+ value.name +'" data-city="'+ value.code+'">' + value.name + '</option>');
          });
        }
      });
    } else {
        // $('#city').empty();
    }
  });

  $(document).on('click', '#edit', function(){
      var id = $(this).data("id")
      $('#update-modal').modal('show');

      $.ajax({
        url : BASE_URL+'/supplier/'+id,
        type : 'GET',
        dataType: "json",
        beforeSend: function() {
        
        },
        success: function(data) {
          $('#id-update').val(data.id)
          $('#name').val(data.name)
          $('#email').val(data.email)
          $('#phone').val(data.phone)
          $('#pic_name').val(data.pic_name)
          $('#pic_phone').val(data.pic_phone)
          $('#district').val(data.district)
          $('#subdistrict').val(data.subdistrict)
          $('#address').val(data.address)

          $('#input-province-update').select2()
          $('#input-province-update').val(data.province)
          $('#input-province-update').select2().trigger('change');
          $('#input-province-update').select2({
            width: '100%'
          });

          var province_id = $("option:selected", '#input-province-update').data('province-code');
          city = data.city;

          $.ajax({
            url: BASE_URL+'/city_by_province/'+province_id,
            type: "GET",
            dataType: "json",
            beforeSend: function() {
                $('#input-city-update').empty();
            },
            success: function(data) {
              $.each(data, function(key, value) {
                  tmp = '';
                  if(city == value.name){
                    tmp = 'selected';
                  }
                  $('#input-city-update').append('<option value="'+ value.name +'" data-city="'+ value.code+'"'+tmp+'>' + value.name + '</option>');
              });
            }
          });

          $('#input-city-update').select2()
          $('#input-city-update').val(data.province)
          $('#input-city-update').select2().trigger('change');
          $('#input-city-update').select2({
            width: '100%'
          });

        }
      })
  })

  $('form#update-form').submit( function( e ) {
    e.preventDefault();
    var loading_text = $('.loading').data('loading-text');
        $('.loading').html(loading_text).attr('disabled', true);
    var form_data = new FormData( this );

    $.ajax({
      type: 'post',
      url: BASE_URL+'/supplier',
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
                $('#main-table').DataTable().ajax.reload(null, false);
                $('#update-modal').modal('hide');
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

  $(document).on('click', '#delete', function(e){
    event.preventDefault()
    var id = $(this).data("id")

    swal({
            title: 'Apakah kamu yakin untuk menghapus?',
            text: "Data ini tidak bisa dikembalikan lagi",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Hapus'
        }, function(){
          $.ajax({
            type: 'get',
            url: BASE_URL+'/supplier/'+id+'/delete',
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
                          title: "sukses",
                          text: msg.message,
                          type:"success",
                          html: true
                      }, function() {
                          $('#main-table').DataTable().ajax.reload(null, false);
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
        })
  });
</script>
@endsection
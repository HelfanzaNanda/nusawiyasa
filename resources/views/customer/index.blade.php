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
      <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah Konsumen</a>
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
                <input class="form-control" type="text" name="name">
              </div>
              <div class="form-group">
                <label>Email</label>
                <input class="form-control" type="text" name="email">
              </div>
              <div class="form-group">
                <label>No. HP</label>
                <input class="form-control" type="text" name="phone">
              </div>
              <div class="form-group">
                <label>Tempat Lahir</label>
                <input class="form-control" type="text" name="place_of_birth">
              </div>
              <div class="form-group">
                <label>Tanggal Lahir</label>
                <input class="form-control" type="text" name="date_of_birth" id="input-dob">
              </div>
              <div class="form-group">
                <label>Pekerjaan</label>
                <input class="form-control" type="text" name="occupation">
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
                <input class="form-control" type="text" name="subdistrict">
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
<!-- /Add Salary Modal -->

<!-- Customer Update Modal -->
<div id="update-modal" class="modal custom-modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Konsumen</h5>
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
                <label>Nama</label>
                <input class="form-control" type="text" name="name" id="name-update">
                <input class="form-control" type="hidden" name="id" id="id-update">
              </div>
              <div class="form-group">
                <label>Email</label>
                <input class="form-control" type="text" name="email" id="email-update">
              </div>
              <div class="form-group">
                <label>No. HP</label>
                <input class="form-control" type="text" name="phone" id="phone-update">
              </div>
              <div class="form-group">
                <label>Tempat Lahir</label>
                <input class="form-control" type="text" name="place_of_birth" id="place-of-birth-update">
              </div>
              <div class="form-group">
                <label>Tanggal Lahir</label>
                <input class="form-control" type="text" name="date_of_birth" id="input-dob-update">
              </div>
              <div class="form-group">
                <label>Pekerjaan</label>
                <input class="form-control" type="text" name="occupation" id="occupation-update">
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
                <select id="input-city-update" name="city" id="city-update"> 
                  <option> - Pilih Kota - </option>
                </select>
              </div>
              <div class="form-group">
                <label>Kecamatan</label>
                <input class="form-control" type="text" name="district" id="ditrict-update">
              </div>
              <div class="form-group">
                <label>Kelurahan</label>
                <input class="form-control" type="text" name="subdistrict" id="sub-district-update" value="babi">
              </div>
              <div class="form-group">
                <label>Alamat</label>
                <textarea class="form-control" name="address" rows="5" id="address-update"></textarea>
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
<!-- /Update Customer Modal -->
@endsection

@section('additionalScriptJS')
<script type="text/javascript">
  if($('#input-dob').length > 0) {
    $('#input-dob').datetimepicker({
      format: 'YYYY-MM-DD',
      icons: {
        up: "fa fa-angle-up",
        down: "fa fa-angle-down",
        next: 'fa fa-angle-right',
        previous: 'fa fa-angle-left'
      }
    });
  }

  if($('#input-dob-update').length > 0) {
    $('#input-dob-update').datetimepicker({
      format: 'YYYY-MM-DD',
      icons: {
        up: "fa fa-angle-up",
        down: "fa fa-angle-down",
        next: 'fa fa-angle-right',
        previous: 'fa fa-angle-left'
      }
    });
  }

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

  $(document).on('click', '#delete', function(e){
    event.preventDefault()
    var id = $(this).data("id")

    swal({
            title: 'Apakah kamu yakin untuk menghapus?',
            text: "Data ini tidak bisa dikebalikan lagi",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Batal',
            confirmButtonText: 'Hapus'
        }, function(){
          console.log('ddd');
          $.ajax({
            type: 'get',
            url: BASE_URL+'/customers/'+id+'/delete',
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

  })

  $(document).on('click', '#edit', function(){
    event.preventDefault();
      var id = $(this).data("id")

      $('#update-modal').modal('show')
      
      $.ajax({
        url : BASE_URL+'/customers/'+id,
        type : 'GET',
        dataType: "json",
        beforeSend: function() {
        
        },
        success: function(data) {
          $('#id-update').val(data.id)
          $('#name-update').val(data.user.name)
          $('#email-update').val(data.user.email)
          $('#phone-update').val(data.user.phone)
          $('#place-of-birth-update').val(data.place_of_birth)
          $('#input-dob-update').val(data.date_of_birth)
          $('#occupation-update').val(data.occupation)
          $('#ditrict-update').val(data.district)
          $('#sub-district-update').val(data.subdistrict)
          $('#address-update').val(data.address)

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

          // $('#input-city-update').select2()
          //     $('#input-city-update').val(data.city)
          //     $('#input-city-update').select2().trigger('change');
          //     $('#input-city-update').select2({
          //       width: '100%'
          //     });
        }
      })
    })
  

  $("#show-add-modal").on('click',function() {
      $('#add-modal').modal('show');
  });

  $('#input-province').select2({
    width: '100%'
  });

  $('#input-city').select2({
    width: '100%'
  });

  $('#input-province-update').select2({
    width: '100%'
  });

  $('#input-city-update').select2({
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

  
  $('form#update-form').submit(function(e){
    e.preventDefault();
    var form_data = new FormData( this );
    var loading_text = $('.loading').data('loading-text');
      $('.loading').html(loading_text).attr('disabled', true);
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

  $('form#add-form').submit(function(e){
    e.preventDefault();
    var loading_text = $('.loading').data('loading-text');
    $('.loading').html(loading_text).attr('disabled', true);
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
</script>
@endsection
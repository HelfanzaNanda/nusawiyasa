@extends('layouts.main')

@section('title', 'Pengguna')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Pengguna</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Pengguna</a></li>
        <li class="breadcrumb-item active">Data Pengguna</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
      <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Update Pengguna</a>
    </div>
  </div>
</div>
<!-- /Page Header -->

<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Pengguna</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <div class="table-responsive">
          <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
            <thead>
              <tr>
                <th>#</th>
                <th width="15%">Nama Pengguna</th>
                <th>Cluster/Perumahan</th>
                <th>Role</th>
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
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Pengguna</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-form" method="POST" action="#">
          {!! csrf_field() !!}
          <div class="row"> 
            <div class="col-md-12"> 
              <div class="form-group">
                <label>Nama</label>
                <input class="form-control" type="text" name="name">
              </div>
              <div class="form-group">
                <label>Email</label>
                <input class="form-control" type="text" name="email">
              </div>
              <div class="form-group">
                <label>No HP</label>
                <input class="form-control" type="text" name="phone">
              </div>
              <div class="form-group">
                <label>Username</label>
                <input class="form-control" type="text" name="username">
              </div>
              <div class="form-group">
                <label>Password</label>
                <input class="form-control" type="password" name="password">
              </div>
              <div class="form-group">
                <label>Role</label>
                <select id="input-role" name="role_id"> 
                  <option> - Pilih Role - </option>
                  @foreach($roles as $role)
                    <option value="{{$role['id']}}">{{$role['name']}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Perumahan/Cluster</label>
                <select id="input-cluster" name="cluster_id"> 
                  <option> - Pilih Perumahan/Cluster - </option>
                  @foreach($clusters as $cluster)
                    <option value="{{$cluster['id']}}">{{$cluster['name']}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="submit-section">
            <div class="col-auto float-right ml-auto pb-2">
              <button type="button" class="btn btn-close mr-2 btn-secondary" data-dismiss="modal">Tutup</button>
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
</div>

<!-- Add Salary Modal -->
<div id="update-modal" class="modal custom-modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Pengguna</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="update-form" method="POST" action="#">
          {!! csrf_field() !!}
          <div class="row"> 
            <div class="col-md-12"> 
              <div class="form-group">
                <label>Nama</label>
                <input class="form-control" type="text" name="name" id="name">
                <input type="hidden" name="id" id="id">
              </div>
              <div class="form-group">
                <label>Email</label>
                <input class="form-control" type="text" name="email" id="email"
                required oninvalid="this.setCustomValidity('Harap Isikan Email.')" onchange="this.setCustomValidity('')">
              </div>
              <div class="form-group">
                <label>No HP</label>
                <input class="form-control" type="text" name="phone" id="phone"
                required oninvalid="this.setCustomValidity('Harap Isikan No HP.')" onchange="this.setCustomValidity('')">
              </div>
              <div class="form-group">
                <label>Username</label>
                <input class="form-control" type="text" name="username" id="username"
                required oninvalid="this.setCustomValidity('Harap Isikan Username.')" onchange="this.setCustomValidity('')">
              </div>
              <div class="form-group">
                <label>Password</label>
                <input class="form-control" type="password" name="password" id="password"
                required oninvalid="this.setCustomValidity('Harap Isikan Password.')" onchange="this.setCustomValidity('')">
              </div>
              <div class="form-group">
                <label>Role</label>
                <select id="role" name="role_id"
                required oninvalid="this.setCustomValidity('Harap Isikan Role.')" onchange="this.setCustomValidity('')"
                > 
                  <option value=""> - Pilih Role - </option>
                  @foreach($roles as $role)
                    <option value="{{$role['id']}}">{{$role['name']}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Perumahan/Cluster</label>
                <select id="cluster" name="cluster_id"
                required oninvalid="this.setCustomValidity('Harap Isikan Perumahan/Cluster.')" onchange="this.setCustomValidity('')"
                > 
                  <option value=""> - Pilih Perumahan/Cluster - </option>
                  @foreach($clusters as $cluster)
                    <option value="{{$cluster['id']}}">{{$cluster['name']}}</option>
                  @endforeach
                </select>
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
  $("#show-add-modal").on('click',function() {
    $('form#add-form').trigger('reset')
            $('select').val('').trigger('change')
      $('#add-modal').modal('show');
  });
  
  $('#input-role').select2({
    width: '100%'
  });

  $('#input-cluster').select2({
    width: '100%'
  });

  $("#main-table").DataTable({
      "pageLength": 10,
      "processing": true,
      "serverSide": true,
      "ajax":{
          "url": BASE_URL+"/user-datatables",
          "dataType": "json",
          "type": "POST",
          "data":function(d) { 
            d._token = "{{csrf_token()}}"
          },
      },
      "columns": [
          {data: 'id', name: 'id', width: '5%', "visible": false},
          {data: 'name', name: 'name'},
          {data: 'cluster_name', name: 'cluster_name'},
          {data: 'role_name', name: 'role_name'},
          {data: 'action', name: 'action', className: 'text-right'},
      ],
  });

  $('form#add-form').submit( function( e ) {
    e.preventDefault();
    var loading_text = $('.loading').data('loading-text');
        $('.loading').html(loading_text).attr('disabled', true);
    var form_data = new FormData( this );

    $.ajax({
      type: 'post',
      url: BASE_URL+'/user',
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

  $(document).on('click','#edit',function() {
    var id = $(this).data("id")
      $('#update-modal').modal('show');

      $.ajax({
        url : BASE_URL+'/user/'+id,
        type : 'GET',
        dataType: "json",
        beforeSend: function() {
        
        },
        success: function(data) {
          $('#id').val(data.id)

          $('#role').select2()
          $('#role').val(data.role_id)
          $('#role').select2().trigger('change');
          $('#role').select2({
            width: '100%'
          });

          $('#cluster').select2()
          $('#cluster').val(data.cluster_id)
          $('#cluster').select2().trigger('change');
          $('#cluster').select2({
            width: '100%'
          });

          $('#name').val(data.name)
          $('#email').val(data.email)
          $('#phone').val(data.phone)
          $('#username').val(data.username)
          $('#password').val('')
        }
      })
  });

  $('form#update-form').submit( function( e ) {
    e.preventDefault();
    var loading_text = $('.loading').data('loading-text');
        $('.loading').html(loading_text).attr('disabled', true);
    var form_data = new FormData( this );

    $.ajax({
      type: 'post',
      url: BASE_URL+'/user',
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
            url: BASE_URL+'/user/'+id+'/delete',
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
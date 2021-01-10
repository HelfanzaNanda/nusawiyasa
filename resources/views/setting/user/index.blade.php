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
      <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah Pengguna</a>
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
            <button class="btn btn-primary submit-btn">Submit</button>
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
      }
    })
  });
</script>
@endsection
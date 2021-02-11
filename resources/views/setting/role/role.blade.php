@extends('layouts.main')

@section('title', 'Hak Akses')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Hak Akses</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Hak Akses</a></li>
        <li class="breadcrumb-item active">Data Hak Akses</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
      <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah Hak Akses</a>
    </div>
  </div>
</div>
<!-- /Page Header -->

<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Hak Akses</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <div class="table-responsive">
          <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
            <thead>
              <tr>
                <th>#</th>
                <th>Hak Akses</th>
                <th>Guard Name</th>
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

<!-- Add Role Modal -->
<div id="add-modal" class="modal custom-modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Hak Akses</h5>
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
                <label>Hak Akses</label>
                <input class="form-control" type="text" name="role" id="role">
                <span class="text-danger error"></span>
              </div>
            </div>
          </div>
          <div class="submit-section">
            <button type="submit" class="btn btn-primary submit-btn">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Edit Role Modal -->
<div id="update-modal" class="modal custom-modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Hak Akses</h5>
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
                <label>Hak Akses</label>
                <input class="form-control" type="text" name="role" id="role-edit">
                <span class="text-danger error"></span>
                <input type="hidden" name="id" id="id">
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
$(document).ready(function(){
    $("#show-add-modal").on('click',function() {
        $('.error').text('');
        $('#role').val('');
        $('#add-modal').modal('show');
    });

    $("#main-table").DataTable({
        "pageLength": 10,
        "processing": true,
        "serverSide": true,
        "ajax":{
            "url": BASE_URL+"/roles-datatables",
            "dataType": "json",
            "type": "POST",
            "data":function(d) { 
                d._token = "{{csrf_token()}}"
            },
        },
        "columns": [
            {data: 'id', name: 'id', width: '5%', "visible": false},
            {data: 'name', name: 'name'},
            {data: 'guard_name', name: 'guard_name'},
            {data: 'action', name: 'action', className: 'text-right'},
        ],
    });

    $('form#add-form').submit(function(e) {
        
        $('.error').text('');
        e.preventDefault();
        var form_data = new FormData( this );

        $.ajax({
            type: 'post',
            url: BASE_URL+'/roles-add',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function() {
                
            },
            success: function(res) {
              
                if(res.status == 'success'){
                  swal({
                            title: "Sukses",
                            text: res.message,
                            type:"success",
                            html: true
                        }, function() {
                            $('#main-table').DataTable().ajax.reload(null, false);
                            $('#add-modal').modal('hide');
                            // window.location.replace(URL_LIST_PURCHASES);
                        });
                } else {
                    swal({
                        title: "Gagal",
                        text: res.message,
                        showConfirmButton: true,
                        confirmButtonColor: '#0760ef',
                        type:"error",
                        html: true
                    });
                }
            },
            error: function(jqXHR){
              
                if (jqXHR.status == 422) {
                    $('.error').text(jqXHR.responseJSON.errors.role)
                }
            }
        });
    });

  $(document).on('click','#edit',function() {
        $('.error').text('');
        var id = $(this).data("id")
        $('#update-modal').modal('show');

        $.ajax({
            url : BASE_URL+'/roles/'+id,
            type : 'GET',
            dataType: "json",
            beforeSend: function() {
            
            },
            success: function(data) {
                $('#id').val(data.id)
                $('#role-edit').val(data.name);
            }
        })
  });

  $('form#update-form').submit( function( e ) {
        $('.error').text('');
        e.preventDefault();
        var form_data = new FormData( this );

        $.ajax({
            type: 'post',
            url: BASE_URL+'/roles-update',
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
            },
            error: function(jqXHR) {
            if (jqXHR.status == 422) {
                $('.error').text(jqXHR.responseJSON.errors.role)
            }
        }
    })
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
            $.ajax({
                type: 'get',
                url: BASE_URL+'/roles-delete/'+id,
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
})
</script>
@endsection
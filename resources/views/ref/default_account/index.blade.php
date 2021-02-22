@extends('layouts.main')

@section('title', 'Default Akun')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Default Akun</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Default Akun</a></li>
        <li class="breadcrumb-item active">Data Default Akun</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
      
    </div>
  </div>
</div>
<!-- /Page Header -->

<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Data Default Akun</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <div class="table-responsive">
          <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Kode Akun</th>
                <th>Nama Akun</th>
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
        <h5 class="modal-title">Tambah Default Akun</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-form" method="POST" action="#">
          {!! csrf_field() !!}
          <input type="hidden" name="id" id="id">
          <div class="row"> 
            <div class="col-sm-12"> 
              <div class="form-group">
                <label>Nama : <span id="account-name"></span></label>
              </div>
              <div class="form-group">
                <label>Deskripsi : <span id="description"></span></label>
              </div>
              <div class="form-group">
                <label>Akun Berelasi</label>
                <select name="value" id="value" class="form-control">
                  <option value=""> - Pilih Akun Yang Berelasi - </option>
                  @foreach($coa as $key => $val)
                    <option value="{{$key}}">{{$key}} | {{$val}}</option>
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
<!-- /Add Salary Modal -->
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

  $("#main-table").DataTable({
      "pageLength": 10,
      "processing": true,
      "serverSide": true,
      // "searching": false,
      // "ordering": false,
      "ajax":{
          "url": BASE_URL+"/default-account-datatables",
          "dataType": "json",
          "type": "POST",
          "data":function(d) { 
            d._token = "{{csrf_token()}}",
            d.terms_type = "cost"
          },
      },
      "columns": [
          {data: 'id', name: 'id', width: '5%', "visible": false},
          {data: 'name', name: 'name'},
          {data: 'note', name: 'note'},
          {data: 'account_code', name: 'account_code'},
          {data: 'account_name', name: 'account_name'},
          {data: 'action', name: 'action', className: 'text-right'},
      ],
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
        type: 'delete',
        url: BASE_URL+'/default-account/'+id,
        data: {
            '_token' : "{{csrf_token()}}"
        },
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
        url : BASE_URL+'/default_accounts/'+id,
        type : 'GET',
        dataType: "json",
        beforeSend: function() {
        
        },
        success: function(data) {
          $('#id').val(data.id)
          $('#account-name').text(data.name);
          $('#description').text(data.note);
          $('#value').val(data.value).trigger('change');
          $('#add-modal').modal('show');
          // $('#input-province-update').select2()
          // $('#input-province-update').val(data.province)
          // $('#input-province-update').select2().trigger('change');
          // $('#input-province-update').select2({
          //   width: '100%'
          // });
        }
      })
    })
  

  $("#show-add-modal").on('click',function() {
      $('#id').val('')
      $('#add-modal').modal('show');
  });

  $('#value').select2({
    width: '100%'
  });

  $('form#add-form').submit(function(e){
    e.preventDefault();
    var loading_text = $('.loading').data('loading-text');
    $('.loading').html(loading_text).attr('disabled', true);
    var form_data = new FormData( this );
    
    $.ajax({
      type: 'post',
      url: BASE_URL+'/default-account',
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
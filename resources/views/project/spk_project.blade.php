@extends('layouts.main')

@section('title', 'SPK Project')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data SPK Project</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">SPK Project</a></li>
        <li class="breadcrumb-item active">Data SPK Project</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
      <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah SPK Project</a>
    </div>
  </div>
</div>
<!-- /Page Header -->
<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">SPK Project</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <div class="table-responsive">
          <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
            <thead>
              <tr>
                <th>#</th>
                <th width="10%">No. SPK</th>
                <th>Tanggal</th>
                <th>Cluster</th>
                <th>No. Unit</th>
                <th>Blok</th>
                <th>Customer</th>
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

<div id="add-modal" class="modal custom-modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah SPK Project</h5>
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
                <label>No. SPK</label>
                <input class="form-control number-input" type="text" name="number"
                required oninvalid="this.setCustomValidity('Harap Isikan No. SPK.')" onchange="this.setCustomValidity('')">
              </div>
              <div class="form-group">
                <label>Tanggal</label>
                <input class="form-control" type="text" name="date" id="input-date"
                required oninvalid="this.setCustomValidity('Harap Isikan Tanggal.')" onblur="this.setCustomValidity('')">
              </div>
              <div class="form-group">
                <label>Kapling</label>
                <select id="input-customer-lot-id" name="customer_lot_id"
                required oninvalid="this.setCustomValidity('Harap Isikan Kapling.')" onchange="this.setCustomValidity('')" >
                  
                  <option value=""> - Pilih Kapling - </option>
                    @foreach($lots as $lot)
                      <option value="{{$lot['id']}}">{{$lot['cluster_name']}} - {{$lot['unit_block']}} / {{$lot['unit_number']}} ({{$lot['customer_name']}})</option>
                    @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Catatan</label>
                <textarea class="form-control" name="note" rows="3"></textarea>
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

<div id="update-modal" class="modal custom-modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update SPK Project</h5>
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
                <label>No. SPK</label>
                <input class="form-control" type="text" name="number" id="number">
              </div>
              <div class="form-group">
                <label>Tanggal</label>
                <input class="form-control" type="text" name="date" id="input-date-update" id="date">
              </div>
              <div class="form-group">
                <label>Kapling</label>
                <select id="input-customer-lot-id-update" name="customer_lot_id"> 
                  <option> - Pilih Kapling - </option>
                    @foreach($lots as $lot)
                      <option value="{{$lot['id']}}">{{$lot['cluster_name']}} - {{$lot['unit_block']}} / {{$lot['unit_number']}} ({{$lot['customer_name']}})</option>
                    @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Catatan</label>
                <textarea class="form-control" name="note" rows="3" id="note"></textarea>
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
@endsection

@section('additionalScriptJS')
<script type="text/javascript">
  $("#main-table").DataTable({
      "pageLength": 10,
      "processing": true,
      "serverSide": true,
      "ajax":{
          "url": BASE_URL+"/spk-project-datatables",
          "dataType": "json",
          "type": "POST",
          "data":function(d) { 
            d._token = "{{csrf_token()}}"
          },
      },
      "columns": [
          {data: 'id', name: 'id', width: '5%', "visible": false},
          {data: 'number', name: 'number'},
          {data: 'date', name: 'date'},
          {data: 'cluster_name', name: 'cluster_name'},
          {data: 'unit_number', name: 'unit_number'},
          {data: 'unit_block', name: 'unit_block'},
          {data: 'customer_name', name: 'customer_name'},
          {data: 'action', name: 'action', className: 'text-right'}
      ],
  });

  $("#show-add-modal").on('click',function() {
    $('form#add-form').trigger('reset')
            $('select').val('').trigger('change')
    var url = '{{ asset('') }}'
      $.ajax({
        type: 'GET',
        url: url+'number/generate?prefix=SPK',
        success: function(data){
          $('.number-input').val(data.number)
        }
      })
      $('#add-modal').modal('show');
      
  });

  $('#input-customer-lot-id').select2({
    width: '100%'
  });

  if($('#input-date').length > 0) {
    $('#input-date').datetimepicker({
      format: 'YYYY-MM-DD',
      icons: {
        up: "fa fa-angle-up",
        down: "fa fa-angle-down",
        next: 'fa fa-angle-right',
        previous: 'fa fa-angle-left'
      }
    });
  }

  $('form#add-form').submit( function( e ) {
    e.preventDefault();
    var loading_text = $('.loading').data('loading-text');
        $('.loading').html(loading_text).attr('disabled', true);
    var form_data = new FormData( this );

      $.ajax({
      type: 'GET',
      url: '{{asset('')}}'+'number/validate?prefix=SPK&number='+$('.number-input').val(),
      success: function(data){
        
        if(data.status == 'error'){
          swal({
            title: "Gagal",
            text: "Maaf, Nomor pengajuan telah digunakan,",
            showConfirmButton: true,
            confirmButtonColor: '#0760ef',
            type:"error",
            html: true
          });
        }else{
          $.ajax({
            type: 'post',
            url: BASE_URL+'/spk-project',
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
        }
        $('.loading').html('Submit').attr('disabled', false)
      },
      error: function(params) {
          $('.loading').html('Submit').attr('disabled', false)
      }
    })
  });

  $(document).on('click', '#edit', function(){
      var id = $(this).data("id")
      $('#update-modal').modal('show');

      $.ajax({
        url : BASE_URL+'/spk-project/'+id,
        type : 'GET',
        dataType: "json",
        beforeSend: function() {
        
        },
        success: function(data) {
          var res = data.date.substring(0, 10);
          $('#id-update').val(data.id)
          $('#title').val(data.title)
          $('#number').val(data.number)
          $('#dest_name').val(data.dest_name)
          $('#input-date-update').val(res)
          $('#subject').val(data.subject)
          $('#note').val(data.note)

          $('#input-customer-lot-id-update').select2()
          $('#input-customer-lot-id-update').val(data.customer_lot_id)
          $('#input-customer-lot-id-update').select2().trigger('change');
          $('#input-customer-lot-id-update').select2({
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
      url: BASE_URL+'/spk-project',
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
            url: BASE_URL+'/spk-project/'+id+'/delete',
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
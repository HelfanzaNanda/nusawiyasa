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


<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Perjanjian Kerja</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <div class="table-responsive">
          <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
            <thead>
              <tr>
                <th>#</th>
                <th>Nomor</th>
                <th>Judul</th>
                <th>Tanggal</th>
                <th>Kavling</th>
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
        <h5 class="modal-title">Tambah Perjanjian Kerja</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-form" method="POST" action="#" enctype="multipart/form-data">
          {!! csrf_field() !!}
          <div class="row"> 
            <div class="col-md-12"> 
              <div class="form-group">
                <label>Nomor</label>
                <input class="form-control number" type="text" name="number" id="number">
                <span class="text-danger error"></span>
              </div>
              <div class="form-group">
                <label>Judul</label>
                <input class="form-control" type="text" name="title" id="title"
                required oninvalid="this.setCustomValidity('Harap Isikan Konfirmasi Judul.')" onblur="this.setCustomValidity('')">
                <span class="text-danger error"></span>
              </div>
              <div class="form-group">
                <label>Tanggal</label>
                <input class="form-control date" type="text" name="date" id="date"
                required oninvalid="this.setCustomValidity('Harap Isikan Konfirmasi Tanggal.')" onblur="this.setCustomValidity('')">
                <span class="text-danger error"></span>
              </div>
              <div class="form-group">
                <label>File</label>
                <div class="custom-file">  
                  <input type="file" class="custom-file-input" id="file" name="file" accept="application/pdf" onchange="readURL(this)"
                  required oninvalid="this.setCustomValidity('Harap Isikan File.')" onblur="this.setCustomValidity('')">
                  <label class="custom-file-label" id="label-img" for="file">Choose file...</label>
                  <div class="invalid-feedback">Example invalid custom file feedback</div>
                </div>
              </div>
              <div class="form-group">
                <label>Kapling</label>
                <select id="customer-lot-id" class="select" name="customer_lot_id"
                required oninvalid="this.setCustomValidity('Harap Isikan Kapling.')" onblur="this.setCustomValidity('')" >
                  <option value=""> - Pilih Kapling - </option>
                    @foreach($lots as $lot)
                      <option value="{{$lot['id']}}">{{$lot['cluster_name']}} - {{$lot['unit_block']}} / {{$lot['unit_number']}} ({{$lot['customer_name']}})</option>
                    @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Total Perjanjian Upah</label>
                <input class="form-control" type="text" name="wage" id="wage"
                required oninvalid="this.setCustomValidity('Harap Isikan Konfirmasi Total Perjanjian Upah.')" onblur="this.setCustomValidity('')">
                <span class="text-danger error"></span>
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


<!-- update Role Modal -->
<div id="update-modal" class="modal custom-modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Perjanjian Kerja</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="update-form" method="POST" action="#" enctype="multipart/form-data">
          {!! csrf_field() !!}
          <div class="row"> 
            <div class="col-md-12"> 
              <div class="form-group">
                <label>Nomor</label>
                <input class="form-control number" type="text" name="number" id="number-edit">
                <input type="hidden" name="id" id="id">
                <span class="text-danger error"></span>
              </div>
              <div class="form-group">
                <label>Judul</label>
                <input class="form-control" type="text" name="title" id="title-edit"
                required oninvalid="this.setCustomValidity('Harap Isikan Judul.')" onchange="this.setCustomValidity('')">
                <span class="text-danger error"></span>
              </div>
              <div class="form-group">
                <label>Tanggal</label>
                <input class="form-control date" type="text" name="date" id="date-edit"
                required oninvalid="this.setCustomValidity('Harap Isikan Tanggal.')" onblur="this.setCustomValidity('')">
                <span class="text-danger error"></span>
              </div>
              <div class="form-group">
                <label>File</label>
                <div class="custom-file">  
                  <input type="file" class="custom-file-input" id="file" name="file" accept="application/pdf" onchange="readURL(this)">
                  <label class="custom-file-label" id="label-img-edit" for="file">Choose file...</label>
                  <div class="invalid-feedback">Example invalid custom file feedback</div>
                </div>
              </div>
              <div class="form-group">
                <label>Kapling</label>
                <select id="customer-lot-id-edit" class="select" name="customer_lot_id"
                required oninvalid="this.setCustomValidity('Harap Isikan Kapling.')" onchange="this.setCustomValidity('')" >
                  
                  <option value=""> - Pilih Kapling - </option>
                    @foreach($lots as $lot)
                      <option value="{{$lot['id']}}">{{$lot['cluster_name']}} - {{$lot['unit_block']}} / {{$lot['unit_number']}} ({{$lot['customer_name']}})</option>
                    @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Total Perjanjian Upah</label>
                <input class="form-control" type="text" name="wage" id="wage-edit"
                required oninvalid="this.setCustomValidity('Harap Isikan Total Perjanjian Upah.')" onchange="this.setCustomValidity('')">
                <span class="text-danger error"></span>
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

$('.select').select2({
    width: '100%'
  });

  if($('.date').length > 0) {
    $('.date').datetimepicker({
      format: 'YYYY-MM-DD',
      icons: {
        up: "fa fa-angle-up",
        down: "fa fa-angle-down",
        next: 'fa fa-angle-right',
        previous: 'fa fa-angle-left'
      }
    });
  }

$(document).ready(function(){
    $("#show-add-modal").on('click',function() {
      $('form#add-form').trigger('reset')
            $('select').val('').trigger('change')
      $('.custom-file-label').text('Choose file...')
        $('.error').text('');
        var url = '{{ asset('') }}'
        $.ajax({
          type: 'GET',
          url: url+'number/generate?prefix=WA',
          success: function(data){
            $('.number').val(data.number)
          }
        })
        $('#add-modal').modal('show');
      
    });

    $("#main-table").DataTable({
        "pageLength": 10,
        "processing": true,
        "serverSide": true,
        "ajax":{
            "url": BASE_URL+"/work-agreement-datatables",
            "dataType": "json",
            "type": "POST",
            "data":function(d) { 
                d._token = "{{csrf_token()}}"
            },
        },
        "columns": [
            {data: 'id', name: 'id', width: '5%', "visible": false},
            {data: 'number', name: 'number'},
            {data: 'title', name: 'title'},
            {data: 'date', name: 'date'},
            {data: 'customer_name', name: 'customer_name'},
            {data: 'action', name: 'action', className: 'text-right'},
        ],
    });

    $('form#add-form').submit(function(e) {
        e.preventDefault();
        var loading_text = $('.loading').data('loading-text');
        $('.loading').html(loading_text).attr('disabled', true);
        $('.error').text('');
        var form_data = new FormData( this );
        $.ajax({
            type: 'post',
            url: BASE_URL+'/work-agreement',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function() {
                
            },
            success: function(res) {
              
                if(res.status == 'success'){
                    setTimeout(function() {
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
                    }, 500);
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
                $('.loading').html('Submit').attr('disabled', false)
            },
            error: function(jqXHR){
              $('.loading').html('Submit').attr('disabled', false)
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
            url : BASE_URL+'/work-agreement/'+id,
            type : 'GET',
            dataType: "json",
            beforeSend: function() {
            
            },
            success: function(data) {
                $('#id').val(data.id)
                $('#number-edit').val(data.number);
                $('#title-edit').val(data.title);
                $('#wage-edit').val(parseFloat(data.wage));
                $('#date-edit').val(formatDate(data.date));
                $('#customer-lot-id-edit').val(data.customer_lot_id).trigger('change');
                $('#label-img-edit').text(data.filename)

            }
        })
  });

  function formatDate(date) {
     var d = new Date(date),
         month = '' + (d.getMonth() + 1),
         day = '' + d.getDate(),
         year = d.getFullYear();
     if (month.length < 2) month = '0' + month;
     if (day.length < 2) day = '0' + day;

     return [year, month, day].join('-');
 }

  $('form#update-form').submit( function( e ) {
        $('.error').text('');
        e.preventDefault();
        var loading_text = $('.loading').data('loading-text');
        $('.loading').html(loading_text).attr('disabled', true);
        var form_data = new FormData( this );
        $.ajax({
            type: 'post',
            url: BASE_URL+'/work-agreement',
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
            },
            error: function(jqXHR) {
            $('.loading').html('Submit').attr('disabled', false)
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
                url: BASE_URL+'/work-agreement/'+id+'/delete/',
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

function readURL(input) {
    $('.custom-file-label').text(input.files[0].name);
}
</script>
@endsection
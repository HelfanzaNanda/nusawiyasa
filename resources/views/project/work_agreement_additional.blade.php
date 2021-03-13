@extends('layouts.main')

@section('title', 'Perjanjian Kerja Tambahan')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Perjanjian Kerja Tambahan</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Perjanjian Kerja Tambahan</a></li>
        <li class="breadcrumb-item active">Data Perjanjian Kerja Tambahan</li>
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
          <h3 class="card-title mb-0">Perjanjian Kerja Tambahan</h3>
        </div>
        <div class="card-body ml-3 mt-3 mr-3 mb-3">
            <form id="add-form" method="POST" action="#" enctype="multipart/form-data">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nomor</label>
                            <input class="form-control number" type="text" name="number" id="number">
                            <span class="text-danger error"></span>
                          </div>
                          <div class="form-group">
                            <label>Judul</label>
                            <input class="form-control" type="text" name="title" id="title"
                            required oninvalid="this.setCustomValidity('Harap Isikan Judul.')" onchange="this.setCustomValidity('')">
                            <span class="text-danger error"></span>
                          </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal</label>
                            <input class="form-control date" type="text" name="date" id="date"
                            required oninvalid="this.setCustomValidity('Harap Isikan Tanggal.')" onchange="this.setCustomValidity('')">
                            <span class="text-danger error"></span>
                          </div>
                          <div class="form-group">
                            <label>File</label>
                            <div class="custom-file">  
                              <input type="file" onchange="readURL(this)" class="custom-file-input" id="file" name="file" accept="application/pdf">
                              <label class="custom-file-label" for="file">Choose file...</label>
                              <div class="invalid-feedback">Example invalid custom file feedback</div>
                            </div>
                          </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-auto float-right ml-auto pb-2">
                            <button type="button" class="btn btn-close mr-2 btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary float-right loading" 
                            data-loading-text='<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Loading...'>
                              Submit
                            </button>
                          </div>
                    </div>
                </div>
              </form>
        </div>
      </div>
    </div>
</div>

<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
        <div class="card-body ml-3 mt-3 mr-3 mb-3">
            @foreach ($spk_worker_adtts as $val)
            <div class="row mb-2 align-items-center border border-bottom-10 mx-2">
                <div class="col-md-10">
                    <a href="{{ url($val->filepath.'/'.$val->filename) }}" target="_blank">{{ $val->title }}</a>
                </div>
                <div class="col-md-2">
                    <a class="dropdown-item" id="delete" href="#" data-toggle="modal" data-target="#delete_approve"  data-id="{{ $val->id }}"><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
  </div>
</div>

@endsection

@section('additionalScriptJS')
<script type="text/javascript">

    function readURL(input) {
        $('.custom-file-label').text(input.files[0].name);
    }

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
    var url = '{{ asset('') }}'
    $.ajax({
        type: 'GET',
        url: url+'number/generate?prefix=WAP',
        success: function(data){
            $('.number').val(data.number)
        }
    })
})

  
$(document).ready(function(){
    var spk_worker_id = "{{ $spk_worker_id }}";
    $('form#add-form').submit(function(e) {
        e.preventDefault();
        var loading_text = $('.loading').data('loading-text');
        $('.loading').html(loading_text).attr('disabled', true);
        $('.error').text('');
        var form_data = new FormData( this );
        $.ajax({
            type: 'post',
            url: BASE_URL+'/work-agreement/'+spk_worker_id+'/additional',
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
                            //$('#main-table').DataTable().ajax.reload(null, false);
                            $('#add-modal').modal('hide');
                             window.location.reload();
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
                    url: BASE_URL+'/work-agreement/'+spk_worker_id+'/additional/'+id+'/delete',
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
                                    window.location.reload();
                                    //$('#main-table').DataTable().ajax.reload(null, false);
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
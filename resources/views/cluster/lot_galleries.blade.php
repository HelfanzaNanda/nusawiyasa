@extends('layouts.main')

@section('title', 'Galeri Kavling '. $lot->block.' - '.$lot->unit_number)

@section('content')
<style type="text/css">
.image-row {
  margin: 10px;
  width:200px;
  height:150px;
  object-position: center;
  object-fit: cover;
}

.image {
  width:100%;
  height:150px;
  object-position: center;
  object-fit: cover;
}
</style>
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Galeri Kavling {{ $lot->block.' - '.$lot->unit_number }}</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Galeri Kavling {{ $lot->block.' - '.$lot->unit_number }}</a></li>
        <li class="breadcrumb-item active">Data Galeri Kavling {{ $lot->block.' - '.$lot->unit_number }}</li>
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
        <h3 class="card-title mb-0">Data Galeri Kavling {{ $lot->block.' - '.$lot->unit_number }}</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <form id="image-form" method="POST" action="#">
            {!! csrf_field() !!}
            <div class="row"> 
              <div class="col-md-4"> 
                <div class="form-group">
                  <label>Gambar</label>
                  <input class="form-control" type="hidden" name="lot_id" value="{{ $lot->id }}">
                  <input class="form-control" type="file" name="files[]" id="input-image" 
                  onchange="preview_image();" multiple>
                </div>
              </div>
              <div class="col-md-8">
                <div id="image_preview"></div>
              </div>
            </div>
            <div class="submit-section">
              <button type="submit" class="btn btn-primary float-right loading" 
              data-loading-text='<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Loading...'>
                Submit
              </button>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row justify-content-center">
                    @foreach ($galleries as $gallery)
                        <div class="col-md-3 mb-3">
                            <img class="image" src="{{ asset($gallery->filepath.'/'.$gallery->filename) }}" >
                            <a href="#" data-id="{{ $gallery->id }}" class="btn btn-delete btn-sm btn-danger btn-block rounded-0 shadow shadow-sm">Hapus</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('additionalScriptJS')
<script type="text/javascript">

  $('form#image-form').submit(function( e ) {
    e.preventDefault();
    var loading_text = $('.loading').data('loading-text');
    $('.loading').html(loading_text).attr('disabled', true);
    var form_data = new FormData( this );

    $.ajax({
      type: 'post',
      url: BASE_URL+'/lot-gallery',
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
                    //$('#main-table').DataTable().ajax.reload(null, false);
                     window.location.reload();
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

  $(document).on('click', '.btn-delete', function(e){
    e.preventDefault()
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
            url: BASE_URL+'/lot-gallery/'+id+'/delete',
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
                          //$('#main-table').DataTable().ajax.reload(null, false);
                          window.location.reload()
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

  function preview_image() {
   var total_file=document.getElementById("input-image").files.length;
   for(var i=0;i<total_file;i++) {
    $('#image_preview').append("<img class='image-row' src='"+URL.createObjectURL(event.target.files[i])+"'>");
   }
  }
</script>
@endsection
@extends('layouts.main')

@section('title', 'Pengaturan Umum')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Pengaturan Umum</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Pengaturan Umum</a></li>
        <li class="breadcrumb-item active">Data Pengaturan Umum</li>
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
        <h3 class="card-title mb-0">Pengaturan Umum</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <form action="" method="POST" id="edit-form">
            @csrf
            @foreach ($datas as $key => $data)
                <div class="row align-items-center form-group">
                    <div class="col-md-2"><label for="{{ $data->name }}">{{ $data->name }}</label></div>
                    <div class="col-md-10">
                        @if ($data->type == 'file')
                        <input type="file" onchange="readURL(this, {{ $key }})" class="form-control " id="{{ $data->name }}" name="{{ $data->key }}">
                        <img id="preview-img-{{ $key }}" width="50" height="50" class="mt-3" src="{{ asset('storage/'.$data->value) }}" alt="{{ $data->name }}">
                        @else
                        <input type="text" class="form-control" id="{{ $data->name }}" name="{{ $data->key }}" value="{{ $data->value ?? '-' }}">
                        @endif
                    </div>
                </div>
            @endforeach
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

@endsection

@section('additionalScriptJS')
<script>
     $('form#edit-form').submit( function( e ) {
        e.preventDefault();
        var loading_text = $('.loading').data('loading-text');
        $('.loading').html(loading_text).attr('disabled', true);
        var form_data = new FormData( this );

        $.ajax({
            type: 'post',
            url: BASE_URL+'/general-setting',
            data: form_data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            beforeSend: function() {
                
            },
            success: function(msg) {
                $('.loading').html('Submit').attr('disabled', false)
                //console.log(msg);
                if(msg.status == 'success'){
                    setTimeout(function() {
                        swal({
                            title: "Sukses",
                            text: msg.message,
                            type:"success",
                            html: true
                        }, function() {
                            location.reload()
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

      function readURL(input, key) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
            reader.onload = function (e) {
                $('#preview-img-'+key).attr('src', e.target.result).attr('style', "display: ''");
            };
            reader.readAsDataURL(input.files[0]);
        }
      }

</script>
@endsection

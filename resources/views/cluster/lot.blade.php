@extends('layouts.main')

@section('title', 'Kapling')

@section('content')
<style type="text/css">
.image-row {
  margin:10px;
  width:200px;
  height:150px;
}
</style>
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Kapling</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Kapling</a></li>
        <li class="breadcrumb-item active">Data Kapling</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
      <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah Kapling</a>
    </div>
  </div>
</div>
<!-- /Page Header -->

<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Data Kapling</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <div class="table-responsive">
          <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama Cluster</th>
                <th>Type</th>
                <th>Blok</th>
                <th>Nomor Unit</th>
                <th>Jumlah Lantai</th>
                <th>LT (m2)</th>
                <th>LB (m2)</th>
                <th>Status</th>
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
<div id="image-modal" class="modal custom-modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Gallery</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="image-form" method="POST" action="#">
          {!! csrf_field() !!}
          <input type="hidden" name="lot_id" id="input-image-lot-id">
          <div class="row"> 
            <div class="col-md-12"> 
              <div class="form-group">
                <label>Gambar</label>
                <input class="form-control" type="file" name="files[]" class="form-control" id="input-image" onchange="preview_image();" multiple>
              </div>
              <div id="image_preview"></div>
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

<!-- Add Salary Modal -->
<div id="add-modal" class="modal custom-modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Kapling</h5>
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
                <label>Cluster</label>
                <select id="input-cluster" name="cluster_id"> 
                  <option> - Pilih Cluster - </option>
                  @foreach($clusters as $cluster)
                    <option value="{{$cluster['id']}}">{{$cluster['name']}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Tipe Model</label>
                <select id="input-type" name="type">
                  <option value=""> - Pilih Tipe - </option>
                  <option value="lot">Kapling</option>
                  <option value="fasum">Fasum</option>
                  <option value="fasos">Fasos</option>
                </select>
              </div>
              <div class="form-group" id="type-name-group">
                <label>Nama Fasilitas</label>
                <input class="form-control" type="text" name="type_name" id="input-type-name">
              </div>
              <div class="form-group">
                <label>Blok</label>
                <input class="form-control" type="text" name="block">
              </div>
              <div class="form-group">
                <label>Nomor Unit</label>
                <input class="form-control" type="text" name="unit_number">
              </div>
              <div class="form-group">
                <label>Jumlah Lantai</label>
                <input class="form-control" type="number" name="total_floor">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label>Luas Tanah (m2)</label>
                <input class="form-control" type="number" name="surface_area">
              </div>
              <div class="form-group">
                <label>Luas Bangunan (m2)</label>
                <input class="form-control" type="number" name="building_area">
              </div>
              <div class="form-group">
                <label>Harga</label>
                <input class="form-control" type="number" name="price">
              </div>
              <div class="form-group">
                <label>Spesifikasi</label>
                <textarea id="input-description" name="description"></textarea>
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

<!-- update Salary Modal -->
<div id="update-modal" class="modal custom-modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Kapling</h5>
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
                <label>Cluster</label>
                <input type="hidden" name="id" id="id-update">
                <select id="input-cluster-update" name="cluster_id"> 
                  <option> - Pilih Cluster - </option>
                  @foreach($clusters as $cluster)
                    <option value="{{$cluster['id']}}">{{$cluster['name']}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Tipe Model</label>
                <select id="input-type-update" name="type">
                  <option value=""> - Pilih Tipe - </option>
                  <option value="lot">Kapling</option>
                  <option value="fasum">Fasum</option>
                  <option value="fasos">Fasos</option>
                </select>
              </div>
              <div class="form-group" id="type-name-group-update">
                <label>Nama Fasilitas</label>
                <input class="form-control" type="text" name="type_name" id="type-name-update">
              </div>
              <div class="form-group">
                <label>Blok</label>
                <input class="form-control" type="text" name="block" id="block-update">
              </div>
              <div class="form-group">
                <label>Nomor Unit</label>
                <input class="form-control" type="text" name="unit_number" id="unit-update">
              </div>
              <div class="form-group">
                <label>Jumlah Lantai</label>
                <input class="form-control" type="number" name="total_floor" id="total-floor-update">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label>Luas Tanah (m2)</label>
                <input class="form-control" type="number" name="surface_area" id="surface-area-update">
              </div>
              <div class="form-group">
                <label>Luas Bangunan (m2)</label>
                <input class="form-control" type="number" name="building_area" id="building-area-update">
              </div>
              <div class="form-group">
                <label>Harga</label>
                <input class="form-control" type="number" name="price" id="price-update">
              </div>
              <div class="form-group">
                <label>Spesifikasi</label>
                <textarea id="edit-description" name="description"></textarea>
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
<!-- /update Salary Modal -->
@endsection

@section('additionalScriptJS')
<script type="text/javascript">
  $('#type-name-group').hide();

  $("#main-table").DataTable({
      "pageLength": 10,
      "processing": true,
      "serverSide": true,
      // "searching": false,
      // "ordering": false,
      "ajax":{
          "url": BASE_URL+"/lot-datatables",
          "dataType": "json",
          "type": "POST",
          "data":function(d) { 
            d._token = "{{csrf_token()}}"
          },
      },
      "columns": [
          {data: 'id', name: 'id', width: '5%', "visible": false},
          {data: 'cluster_name', name: 'cluster_name'},
          {data: 'type', name: 'type'},
          {data: 'block', name: 'block'},
          {data: 'unit_number', name: 'unit_number'},
          {data: 'total_floor', name: 'total_floor'},
          {data: 'building_area', name: 'building_area'},
          {data: 'surface_area', name: 'surface_area'},
          {data: 'status', name: 'status'},
          {data: 'action', name: 'action', className: 'text-right'},
      ],
      "order": [[ 0, "desc" ]]
  });

  $("#show-add-modal").on('click',function() {
      $('#add-modal').modal('show');
  });

  $('#input-cluster').select2({
    width: '100%'
  });

  $('#input-type').select2({
    width: '100%'
  });

  $('form#add-form').submit( function( e ) {
    e.preventDefault();
    var loading_text = $('.loading').data('loading-text');
    $('.loading').html(loading_text).attr('disabled', true);
    var form_data = new FormData( this );

    $.ajax({
      type: 'post',
      url: BASE_URL+'/lots',
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

  $(document).on('click','#edit',function() {
    var id = $(this).data("id")
      $('#update-modal').modal('show');

      $.ajax({
        url : BASE_URL+'/lots/'+id,
        type : 'GET',
        dataType: "json",
        beforeSend: function() {
        
        },
        success: function(data) {
          $('#id-update').val(data.id)

          $('#input-cluster-update').select2()
          $('#input-cluster-update').val(data.cluster_id)
          $('#input-cluster-update').select2().trigger('change');
          $('#input-cluster-update').select2({
            width: '100%'
          });
          $('#input-type-update').select2({width: '100%'}).val(data.type).trigger('change');

          $('#block-update').val(data.block)
          $('#unit-update').val(data.unit_number)
          $('#total-floor-update').val(data.total_floor)
          $('#surface-area-update').val(data.surface_area)
          $('#building-area-update').val(data.building_area)
          $('#price-update').val(data.price)

          $('#edit-description').summernote("code", data.description);

          if (data.type_name) {
            $('#type-name-update').val(data.type_name)
            $('#type-name-group').show();
          }
        }
      })
  });

  $('form#update-form').submit(function( e ) {
    e.preventDefault();
    var loading_text = $('.loading').data('loading-text');
        $('.loading').html(loading_text).attr('disabled', true);
    console.log('da');
    var form_data = new FormData( this );

    $.ajax({
      type: 'post',
      url: BASE_URL+'/lots',
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

  $('form#image-form').submit(function( e ) {
    e.preventDefault();
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
                    $('#main-table').DataTable().ajax.reload(null, false);
                    $('#image-modal').modal('hide');
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
            url: BASE_URL+'/lots/'+id+'/delete',
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

  function preview_image() {
   var total_file=document.getElementById("input-image").files.length;
   for(var i=0;i<total_file;i++) {
    $('#image_preview').append("<img class='image-row' src='"+URL.createObjectURL(event.target.files[i])+"'>");
   }
  }

  $(document).on('click', '#lot-gallery', function(e){
    let lotId = $(this).data('id');
    $('#input-image-lot-id').val(lotId);
    $('#image-modal').modal('show');
  });

  $('#input-description').summernote({
    height: 200
  });

  $('#edit-description').summernote({
    height: 200
  });

  $('#input-type').on('change', function() {
    if (this.value == 'fasum' || this.value == 'fasos') {
      $('#type-name-group').show();
    } else {
      $('#type-name-group').hide();
    }

    $('#input-type-name').val('');
  });

  $('#input-type-update').on('change', function() {
    if (this.value == 'fasum' || this.value == 'fasos') {
      $('#type-name-group-update').show();
    } else {
      $('#type-name-group-update').hide();
    }

    $('#type-name-update').val('');
  });
</script>
@endsection
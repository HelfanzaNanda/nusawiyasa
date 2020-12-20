@extends('layouts.main')

@section('title', 'Barang')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Barang</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Barang</a></li>
        <li class="breadcrumb-item active">Data Barang</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
      <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah Barang</a>
    </div>
  </div>
</div>
<!-- /Page Header -->

<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Barang</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <div class="table-responsive">
          <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
            <thead>
              <tr>
                <th>#</th>
                <th width="15%">Nama Barang</th>
                <th>Stok</th>
                {{-- <th>Kategori</th> --}}
                <th>Perumahan/Cluster</th>
                <th>Unit</th>
                <th>Harga Beli</th>
                <th>Tipe</th>
                <th>Merk</th>
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
        <h5 class="modal-title">Tambah Barang</h5>
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
                <label>Nama Barang</label>
                <input class="form-control" type="text" name="name">
              </div>
              <div class="form-group">
                <label>Stok</label>
                <input class="form-control" type="text" name="stock">
              </div>
{{--               <div class="form-group">
                <label>Kategori</label>
                <select id="input-category" name="category_id"> 
                  <option> - Pilih Kategori - </option>
                  @foreach($categories as $category)
                    <option value="{{$category['id']}}">{{$category['name']}}</option>
                  @endforeach
                </select>
              </div> --}}
              <div class="form-group">
                <label>Unit</label>
                <select id="input-unit" name="unit_id"> 
                  <option> - Pilih Unit - </option>
                  @foreach($units as $unit)
                    <option value="{{$unit['id']}}">{{$unit['name']}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Cluster/Perumahan</label>
                <select id="input-cluster" name="cluster_id"> 
                  <option> - Pilih Cluster - </option>
                  @foreach($clusters as $cluster)
                    <option value="{{$cluster['id']}}">{{$cluster['name']}}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Harga Beli</label>
                <input class="form-control" type="text" name="purchase_price">
              </div>
              <div class="form-group">
                <label>Tipe</label>
                <select id="input-type" name="type"> 
                  <option> - Pilih Unit - </option>
                  <option value="service">Jasa</option>
                  <option value="tools">Alat</option>
                  <option value="materials">Material</option>
                </select>
              </div>
              <div class="form-group">
                <label>Merk</label>
                <input class="form-control" type="text" name="brand">
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
  
  $('#input-category').select2({
    width: '100%'
  });

  $('#input-unit').select2({
    width: '100%'
  });

  $('#input-type').select2({
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
          "url": BASE_URL+"/inventory-datatables",
          "dataType": "json",
          "type": "POST",
          "data":function(d) { 
            d._token = "{{csrf_token()}}"
          },
      },
      "columns": [
          {data: 'id', name: 'id', width: '5%', "visible": false},
          {data: 'name', name: 'name'},
          {data: 'stock', name: 'stock'},
          // {data: 'category_name', name: 'category_name'},
          {data: 'cluster_name', name: 'cluster_name'},
          {data: 'unit_name', name: 'unit_name'},
          {data: 'purchase_price', name: 'purchase_price'},
          {data: 'type', name: 'type'},
          {data: 'brand', name: 'brand'},
          {data: 'action', name: 'action', className: 'text-right'},
      ],
  });

  $('form#add-form').submit( function( e ) {
    e.preventDefault();
    var form_data = new FormData( this );

    $.ajax({
      type: 'post',
      url: BASE_URL+'/inventory',
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
@extends('layouts.main')

@section('title', 'Bon Permintaan Barang')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Bon Permintaan Barang</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Bon Permintaan Barang</a></li>
        <li class="breadcrumb-item active">Data Bon Permintaan Barang</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
      <a href="{{url('/create-receipt-of-goods-request')}}" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah Bon Permintaan Barang</a>
    </div>
  </div>
</div>
<!-- /Page Header -->

<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Bon Permintaan Barang</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <div class="table-responsive">
          <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
            <thead>
              <tr>
                <th>#</th>
                <th width="10%">No. Bon</th>
                <th>Tanggal</th>
                <th>Cluster/Perumahan</th>
                <th>No. Kapling</th>
                <th>Blok</th>
                <th>LT</th>
                <th>LB</th>
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
@endsection

@section('additionalScriptJS')
<script type="text/javascript">
  $("#main-table").DataTable({
      "pageLength": 10,
      "processing": true,
      "serverSide": true,
      "ajax":{
          "url": BASE_URL+"/receipt-of-goods-request-datatables",
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
          {data: 'block', name: 'block'},
          {data: 'surface_area', name: 'surface_area'},
          {data: 'building_area', name: 'building_area'},
          {data: 'action', name: 'action', className: 'text-right'},
      ],
  });

  $(document).on('click', '#delete', function(e){
    event.preventDefault()
    var id = $(this).data("id")
    console.log('gagal');
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
            url: BASE_URL+'/receipt-of-goods-request/'+id+'/delete',
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
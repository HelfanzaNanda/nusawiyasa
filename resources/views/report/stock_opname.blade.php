@extends('layouts.main')

@section('title', 'Report Outstanding PO')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Laporan Stock Opname</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Laporan Stock Opname</a></li>
        <li class="breadcrumb-item active">Laporan Stock Opname</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
        <a href="#" class="btn btn-primary" id="show-filter-modal"><i class="fa fa-filter"></i> Filter</a>
        <form action="{{ route('report.stock-opname.pdf') }}" target="_blank" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="cluster_pdf" id="cluster-pdf">
            <button type="submit" class="btn btn-secondary"><i class="fa fa-print"> Cetak</i></button>
        </form>
      </div>
  </div>
</div>
<!-- /Page Header -->

<div class="row">
    <div class="col-md-12 d-flex">
      <div class="card card-table flex-fill">
        <div class="card-header">
          <h3 class="card-title mb-0">Laporan Stock Opname</h3>
        </div>
        <div class="card-body ml-3 mt-3 mr-3 mb-3">
          <div class="table-responsive">
            <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
              <thead>
                <tr>
                    <th>#</th>
                    <th width="10%">Nama Barang</th>
                    <th>Stok</th>
                    <th>Unit</th>
                    <th>Brand</th>
                    <th>Type</th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- filter Modal -->
<div id="filter-modal" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Filter</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="filter-form" method="POST" action="#">
            {!! csrf_field() !!}
            <div class="col-md-12">
                <div class="form-group">
                    <label>Cluster/Perumahan</label>
                    <select id="input-cluster" name="cluster_id" required="">
                        <option value="0"> - Pilih Cluster - </option>
                        @foreach($clusters as $cluster)
                        <option value="{{$cluster['id']}}">{{$cluster['name']}}</option>
                        @endforeach
                    </select>
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

$( document ).ready(function() {
    fill_datatables()
});
  $('#input-cluster').select2({
    width: '100%'
  });

  $("#show-filter-modal").on('click',function() {
      $('#filter-modal').modal('show');
  });

  $('form#filter-form').submit( function( e ) {
    e.preventDefault();
    const cluster = $('#input-cluster').val();
    if(cluster != '0'){
        $('#filter-modal').modal('hide');
        $('#main-table').DataTable().destroy();
        $('#cluster-pdf').val(cluster);
        fill_datatables(cluster);
    }else{
        alertError('silahkan pilih dahulu');
    }

  });

  function fill_datatables(filter_cluster = ''){
      var datatable = $("#main-table").DataTable({
      "pageLength": 10,
      "processing": true,
      "serverSide": true,
      "ajax":{
          "url": BASE_URL+"/report-stock-opname-datatables",
          "dataType": "json",
          "type": "POST",
          "data":function(d) {
            d._token = "{{csrf_token()}}"
            d.filter_cluster = filter_cluster
          },
      },
      "columns": [
          {data: 'id', name: 'id', width: '5%', "visible": false},
          {data: 'name', name: 'name'},
          {data: 'stock', name: 'stock'},
          {data: 'unit_name', name: 'unit_name'},
          {data: 'brand', name: 'brand'},
          {data: 'type', name: 'type'},
        ],
    });
  }

  function alertError(message){
    swal({
        title: "Gagal",
        text: message,
        showConfirmButton: true,
        confirmButtonColor: '#0760ef',
        type:"error",
        html: true
    });
  }

</script>
@endsection

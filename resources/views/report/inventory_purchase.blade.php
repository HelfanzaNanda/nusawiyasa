@extends('layouts.main')

@section('title', 'Report Pembelian Inventory')
@section('style')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection
@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Report Pembelian Inventory</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Report Pembelian Inventory</a></li>
        <li class="breadcrumb-item active">Report Pembelian Inventory</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
        <a href="#" class="btn btn-primary" id="show-filter-modal"><i class="fa fa-filter"></i> Filter</a>
        <form action="{{ route('report.inventory-purchase.pdf') }}" target="_blank" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="cluster_pdf" id="cluster-pdf">
            <input type="hidden" name="daterange_pdf" id="daterange-pdf">
            <button type="submit" class="btn btn-secondary"><i class="fa fa-print"> Cetak</i></button>
        </form>
    </div>
  </div>
</div>
<div class="row">
    <div class="col-md-12 d-flex">
        <div class="card card-table flex-fill">
            <div class="card-header">
                <h3 class="card-title mb-0">Report Pembelian Inventory</h3>
            </div>
            <div class="card-body ml-3 mt-3 mr-3 mb-3">
                <div class="table-responsive">
                    <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th width="10%">No. PO</th>
                                <th width="10%">No. FPP</th>
                                <th>Jenis Permintaan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

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

                        <div class="form-group">
                            <label for="">Tanggal</label>
                            <input type="text" name="daterange" class="form-control" id="daterange" readonly style="background: white; cursor: pointer;" />
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
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script type="text/javascript">

    $(document).ready(function(){
        const cluster = $('#input-cluster').val();
        const daterange = $('#daterange').val();
        $('#cluster-pdf').val(cluster);
        $('#daterange-pdf').val(daterange);
        fill_datatables()
    })

    var date = new Date();
    var currentMonth = date.getMonth();
    var currentDate = date.getDate();
    var currentYear = date.getFullYear();
    
    $('#daterange').daterangepicker({
        startDate:  new Date(currentYear, currentMonth, '1'),
        endDate: new Date(currentYear, currentMonth, currentDate),
        "opens": "left",
        "drops": "up"
    });

    $("#show-filter-modal").on('click',function() {
        $('form#add-form').trigger('reset')
            $('select').val('').trigger('change')
        $('#filter-modal').modal('show');
    });

    var date = new Date();
    var currentMonth = date.getMonth();
    var currentDate = date.getDate();
    var currentYear = date.getFullYear();

    $('#daterange').daterangepicker({
        startDate:  new Date(currentYear, currentMonth, '1'),
        endDate: moment().startOf('day').toDate(),
        "opens": "left",
        "drops": "up"
    });

    $('#input-cluster').select2({
        width: '100%'
    });

    $('form#filter-form').submit( function( e ) {
    e.preventDefault();
    const cluster = $('#input-cluster').val();
    const daterange = $('#daterange').val();
    $('#filter-modal').modal('hide');
    $('#main-table').DataTable().destroy();
    $('#cluster-pdf').val(cluster);
    $('#daterange-pdf').val(daterange);
    fill_datatables(cluster, daterange);
  });

    function fill_datatables(cluster = '', daterange = ''){
        $("#main-table").DataTable({
            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            "ajax":{
                "url": BASE_URL+"/report-inventory-purchase-datatables",
                "dataType": "json",
                "type": "POST",
                "data":function(d) {
                    d._token = "{{csrf_token()}}"
                    d.inventory_purchase = true
                    d.cluster = cluster
                    d.daterange = daterange
                },
                // success: function(resp){
                //     trimResponse = $.trim(resp);
                //     console.log(resp); //works! I can see the item names in console log
                // },
            },
            "columns": [
                {data: 'id', name: 'id', width: '5%', "visible": false},
                {data: 'number', name: 'number'},
                {data: 'fpp_number', name: 'fpp_number'},
                {data: 'type', name: 'type'},
                {data: 'date', name: 'date'},
                {data: 'total', name: 'total'},
                {data: 'status', name: 'status'},
            ],

        });
    }
</script>
@endsection

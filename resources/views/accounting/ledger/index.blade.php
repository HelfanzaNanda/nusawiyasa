@extends('layouts.main')

@section('title', 'Buku Besar')

@section('style')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Buku Besar</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Buku Besar</a></li>
        <li class="breadcrumb-item active">Data Buku Besar</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
      {{-- <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah Buku Besar</a> --}}
      <button class="btn add-btn" id="show-filter-modal"><i class="fa fa-search"></i>Filter</button>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Data Buku Besar</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <div class="table-responsive">
          <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
            <thead>
              <tr>
                <th>#</th>
        				<th>Tanggal</th>
        				<th>Keterangan</th>
        				<th>Ref.</th>
        				<th>Debit</th>
        				<th>Kredit</th>
        				<th>Saldo</th>
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
                            <label>COA</label>
                            <select id="input-coa" name="coa_id" style="width: 100%;" class="form-control"> 

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Tanggal</label>
                            <input type="text" name="daterange" class="form-control" id="daterange" readonly
                                style="background: white; cursor: pointer;" />
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additionalFileJS')
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
@endsection

@section('additionalScriptJS')
<script type="text/javascript">
	var URL_SEARCH_COA = BASE_URL+'/accounting/get';

    $("#input-coa").select2({
      minimumInputLength: 2,
      dropdownParent: $("#filter-modal"),
      minimumResultsForSearch: '',
      ajax: {
        url: URL_SEARCH_COA,
        dataType: "json",
        type: "GET",
        data: function (params) {
          var queryParameters = {
            term: params.term
          }
          return queryParameters
        },
        processResults: function (data) {
          return {
            results: $.map(data, function (item) {
              return {
                text: item.name,
                id: item.coa
              }
            })
          }
        }
      }
    });

    $(document).on('click', '#show-filter-modal', function() {    
        $('.loading-area').hide();
        $('#filter-modal').modal('show'); 
    });

    $(document).ready(function(){
        const coa = $('#input-coa').val();
        const daterange = $('#daterange').val();
        $('#coa').val(coa);
        $('#daterange').val(daterange);
        fill_datatables()
    });

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

    $('form#filter-form').submit( function( e ) {
        e.preventDefault();
        const coa = $('#input-coa').val();
        const daterange = $('#daterange').val();
        $('#filter-modal').modal('hide');
        $('#main-table').DataTable().destroy();
        $('#coa').val(coa);
        $('#daterange').val(daterange);
        fill_datatables(coa, daterange);
    });

    function fill_datatables(coa = '', daterange = ''){
        $("#main-table").DataTable({
            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            "ajax":{
                "url": BASE_URL+"/accounting-ledger-datatables",
                "dataType": "json",
                "type": "POST",
                "data":function(d) {
                    d._token = "{{csrf_token()}}"
                    d.used_inventory = true
                    d.coa = coa
                    d.daterange = daterange
                },
                // success: function(resp) {
                //     trimResponse = $.trim(resp);
                //     console.log(resp); //works! I can see the item names in console log
                // },
            },
            "columns": [
		        { data: 'id', name: 'id', "visible": false },
		        { data: 'created_at', name: 'created_at' },
		        { data: 'description', name: 'description' },
		        { data: 'ref', name: 'ref' },
		        { data: 'debit', name: 'debit' },
		        { data: 'credit', name: 'credit' },
		        { data: 'saldo', name: 'saldo' }
            ],
        });
    }
</script>
@endsection
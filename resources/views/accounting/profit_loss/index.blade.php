@extends('layouts.main')

@section('title', 'Laba Rugi')

@section('style')
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')
<style type="text/css">
  .section-title {
    font-size: 25px;
    color: red;
  }

  #cover-spin {
      position:fixed;
      width:100%;
      left:0;right:0;top:0;bottom:0;
      background-color: rgba(255,255,255,0.7);
      z-index:9999;
      display: none;
  }

  @-webkit-keyframes spin {
    from {-webkit-transform:rotate(0deg);}
    to {-webkit-transform:rotate(360deg);}
  }

  @keyframes spin {
    from {transform:rotate(0deg);}
    to {transform:rotate(360deg);}
  }

  #cover-spin::after {
      content:'';
      display:block;
      position:absolute;
      left:48%;top:40%;
      border: 16px solid #f3f3f3;
      width: 120px;
      height: 120px;
      border-style:solid;
      border-radius: 50%;
      border-top: 16px solid blue;
      border-bottom: 16px solid blue;
      border-width: 4px;
      border-radius:50%;
      -webkit-animation: spin 2s linear infinite;
      animation: spin 2s linear infinite;
  }
</style>
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data COA</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">COA</a></li>
        <li class="breadcrumb-item active">Data COA</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
      {{-- <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah COA</a> --}}
      <button class="btn add-btn" id="show-filter-modal"><i class="fa fa-search"></i>Filter</button>
    </div>
  </div>
</div>
<!-- /Page Header -->

<div class="row">
	<div id="cover-spin"></div>
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Laba Rugi <span id="date-text">{{date('01-m-Y')}} s/d {{date('t-m-Y')}}</span></h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <div class="col-md-12">
		  <table class="table table-hover" id="results">
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
                {{-- <form id="filter-form" method="POST" action="#"> --}}
                    {!! csrf_field() !!}
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Tanggal</label>
                            <input type="text" name="daterange" class="form-control" 
                            id="daterange" readonly style="background: white; cursor: pointer;" 
                            />
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn" id="submitFilter">Filter</button>
                    </div>
                {{-- </form> --}}
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
var URL_GET = BASE_URL+'/accounting/profit_loss/get';

let daterange = $('#daterange').val();

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

$(document).on('click', '#show-filter-modal', function() {    
    $('.loading-area').hide();
    $("#daterange").val('');
    $('#filter-modal').modal('show'); 
});

$.ajax({
  "url": URL_GET,
  "cache": false,
  "dataType": "JSON",
  "type": "POST",
  "data": { 
    _token: "{{csrf_token()}}"
  },
  success: function(html){
    buildData(html);
  }
});

$("#submitFilter").click(function(){
	daterange = $('#daterange').val();
  $.ajax({
    "url": URL_GET,
    "cache": false,
    "dataType": "JSON",
    "type": "POST",
    "data": { 
      _token: "{{csrf_token()}}",
      date: daterange
    },
    beforeSend: function () {
      $("#results tbody").empty();
      $('#cover-spin').show();
      $('#date-text').empty();
    },
    success: function(html){
      buildData(html);
      $('#date-text').text(daterange);
      $('#cover-spin').hide();
      $('#filter-modal').modal('hide');
    }
  });
});

function buildData(params) {
  //Menghitung Pendapatan
  var revTotal = 0;
  $("#results tbody").append(
      "<tr>"+
        "<td>Pendapatan</td>"+
        "<td></td>"+
        "<td></td>"+
        "<td></td>"+
      "</tr>"
  );

  params.data.forEach(function(item) {
    if (item.sub_coa == 4) {
      revTotal += item.total;
      $("#results tbody").append(
          "<tr>"+
            "<td style='text-align:right;'>"+item.accounting_code+"</td>"+
            "<td>"+item.name+"</td>"+
            "<td>"+addSeparator(item.total, '.', '.', ',')+"</td>"+
            "<td></td>"+
          "</tr>"
      );
    }
  });

  $("#results tbody").append(
      "<tr>"+
        "<td></td>"+
        "<td style='text-align:right;'><b>Total Pendapatan</b></td>"+
        "<td></td>"+
        "<td><b>"+addSeparator(revTotal, '.', '.', ',')+"</b></td>"+
      "</tr>"
  );

  //Menghitung Pengeluaran
  var salTotal = 0;
  $("#results tbody").append(
      "<tr>"+
        "<td>Pengeluaran</td>"+
        "<td></td>"+
        "<td></td>"+
        "<td></td>"+
      "</tr>"
  );

  params.data.forEach(function(item) {
    if (item.sub_coa == 5) {
      salTotal += item.total;
      $("#results tbody").append(
          "<tr>"+
            "<td style='text-align:right;'>"+item.accounting_code+"</td>"+
            "<td>"+item.name+"</td>"+
            "<td>"+addSeparator(item.total, '.', '.', ',')+"</td>"+
            "<td></td>"+
          "</tr>"
      );
    }
  });

  $("#results tbody").append(
      "<tr>"+
        "<td></td>"+
        "<td style='text-align:right;'><b>Total Pengeluaran</b></td>"+
        "<td></td>"+
        "<td><b>"+addSeparator(salTotal, '.', '.', ',')+"</b></td>"+
      "</tr>"
  );

  //Menghitung Pendapatan Lain - Lain
  var othRevTotal = 0;
  $("#results tbody").append(
      "<tr>"+
        "<td>Pendapatan Lain - Lain</td>"+
        "<td></td>"+
        "<td></td>"+
        "<td></td>"+
      "</tr>"
  );

  params.data.forEach(function(item) {
    if (item.sub_coa == 6) {
      othRevTotal += item.total;
      $("#results tbody").append(
          "<tr>"+
            "<td style='text-align:right;'>"+item.accounting_code+"</td>"+
            "<td>"+item.name+"</td>"+
            "<td>"+addSeparator(item.total, '.', '.', ',')+"</td>"+
            "<td></td>"+
          "</tr>"
      );
    }
  });

  $("#results tbody").append(
      "<tr>"+
        "<td></td>"+
        "<td style='text-align:right;'><b>Total Pendapatan Lain - Lain</b></td>"+
        "<td></td>"+
        "<td><b>"+addSeparator(othRevTotal, '.', '.', ',')+"</b></td>"+
      "</tr>"
  );

  //Menghitung Pengeluaran Lain - Lain
  var othSalTotal = 0;
  $("#results tbody").append(
      "<tr>"+
        "<td>Pengeluaran Lain - Lain</td>"+
        "<td></td>"+
        "<td></td>"+
        "<td></td>"+
      "</tr>"
  );

  params.data.forEach(function(item) {
    if (item.sub_coa == 7) {
      othSalTotal += item.total;
      $("#results tbody").append(
          "<tr>"+
            "<td style='text-align:right;'>"+item.accounting_code+"</td>"+
            "<td>"+item.name+"</td>"+
            "<td>"+addSeparator(item.total, '.', '.', ',')+"</td>"+
            "<td></td>"+
          "</tr>"
      );
    }
  });

  $("#results tbody").append(
      "<tr>"+
        "<td></td>"+
        "<td style='text-align:right;'><b>Total Pengeluaran Lain - Lain</b></td>"+
        "<td></td>"+
        "<td><b>"+addSeparator(othSalTotal, '.', '.', ',')+"</b></td>"+
      "</tr>"
  );

  //Menghitung ikhtisar
  var profitLoss = ((othRevTotal + revTotal) - (othSalTotal + salTotal));
  $("#results tbody").append(
      "<tr>"+
        "<td></td>"+
        "<td></td>"+
        "<td></td>"+
        "<td></td>"+
      "</tr>"+
      "<tr>"+
        "<td></td>"+
        "<td style='text-align:right;'><b>Subtotal Pendapatan</b></td>"+
        "<td></td>"+
        "<td><b>"+addSeparator((othRevTotal + revTotal), '.', '.', ',')+"</b></td>"+
      "</tr>"+
      "<tr>"+
        "<td></td>"+
        "<td style='text-align:right;'><b>Subtotal Pengeluaran</b></td>"+
        "<td></td>"+
        "<td><b>"+addSeparator((othSalTotal + salTotal), '.', '.', ',')+"</b></td>"+
      "</tr>"+
      "<tr>"+
        "<td></td>"+
        "<td></td>"+
        "<td></td>"+
        "<td></td>"+
      "</tr>"+
      "<tr>"+
        "<td></td>"+
        "<td style='text-align:right;'><b>Laba/Rugi</b></td>"+
        "<td></td>"+
        "<td><b>"+addSeparator(profitLoss, '.', '.', ',')+' '+(profitLoss < 0 ? '(Rugi)' : '(Laba)')+"</b></td>"+
      "</tr>"
  );
}
</script>
@endsection
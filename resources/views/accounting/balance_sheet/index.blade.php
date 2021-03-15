@extends('layouts.main')

@section('title', 'Neraca')

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
      <h3 class="page-title">Data Neraca</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Neraca</a></li>
        <li class="breadcrumb-item active">Data Neraca</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
      {{-- <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah Neraca</a> --}}
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
        <h3 class="card-title mb-0">Neraca <span id="date-text">{{date('01-m-Y')}} s/d {{date('t-m-Y')}}</span></h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
      	<div class="row col-md-12">
		    <div class="col-md-6">
		      <div class="card card-table">
		        <div class="card-header">
		          <h6 class="slim-card-title">Assets</h6>
		        </div><!-- card-header -->
		        <div class="table-responsive">
		          <table class="table mg-b-0 tx-13" id="assets-table">
		            <tbody>

		            </tbody>
		          </table>
		        </div><!-- table-responsive -->
		      </div><!-- card -->
		    </div><!-- col-6 -->
		    <div class="col-md-6 mg-t-20 mg-lg-t-0">
		      <div class="card card-table">
		        <div class="card-header">
		          <h6 class="slim-card-title">Pasiva</h6>
		        </div><!-- card-header -->
		        <div class="table-responsive">
		          <table class="table mg-b-0 tx-13" id="liabilities-table">
		            <tbody>

		            </tbody>
		          </table>
		        </div><!-- table-responsive -->
		        <div class="card-header">
		          <h6 class="slim-card-title">Ekuitas</h6>
		        </div><!-- card-header -->
		        <div class="table-responsive">
		          <table class="table mg-b-0 tx-13" id="equity-table">
		            <tbody>

		            </tbody>
		          </table>
		        </div><!-- table-responsive -->
		      </div><!-- card -->
		    </div><!-- col-6 -->
      	</div>
		  <div class="row row-sm mg-t-20">
		    <div class="col-lg-6">
		      <div class="card card-table">
		        <div class="card-header">
		          <h4 class="slim-card-title" style="text-align: right;">Total Harta <span id="aktiva-total"></span></h4>
		        </div><!-- card-header -->
		      </div><!-- card -->
		    </div><!-- col-6 -->
		    <div class="col-lg-6 mg-t-20 mg-lg-t-0">
		      <div class="card card-table">
		        <div class="card-header">
		          <h4 class="slim-card-title" style="text-align: right;">Total Kewajiban dan Modal <span id="pasiva-total"></span></h4>
		        </div><!-- card-header -->
		      </div><!-- card -->
		    </div><!-- col-6 -->
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
		            <div class="input-group">
		              <input type="text" class="form-control" id="startDate" placeholder="Tanggal Awal">
		              <div class="input-group-prepend">
		                <span class="input-group-text">s/d</span>
		              </div>
		              <input type="text" class="form-control" id="endDate" placeholder="Tanggal Akhir">
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
$(document).on('click', '#show-filter-modal', function() {    
    $('.loading-area').hide();
    $('#filter-modal').modal('show'); 
});

var URL_GET = BASE_URL+'/accounting/balance_sheet';

if($('#startDate').length > 0) {
	$('#startDate').datetimepicker({
	  format: 'YYYY-MM-DD',
	  icons: {
	    up: "fa fa-angle-up",
	    down: "fa fa-angle-down",
	    next: 'fa fa-angle-right',
	    previous: 'fa fa-angle-left'
	  }
	});
}

if($('#endDate').length > 0) {
	$('#endDate').datetimepicker({
	  format: 'YYYY-MM-DD',
	  icons: {
	    up: "fa fa-angle-up",
	    down: "fa fa-angle-down",
	    next: 'fa fa-angle-right',
	    previous: 'fa fa-angle-left'
	  }
	});
}

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
  $.ajax({
    "url": URL_GET,
    "cache": false,
    "dataType": "JSON",
    "type": "POST",
    "data": { 
      _token: "{{csrf_token()}}",
      sDate: $("input#startDate").val(),
      eDate: $("input#endDate").val()
    },
    beforeSend: function () {
      $("#assets-table tbody").empty();
      $("#liabilities-table tbody").empty();
      $("#equity-table tbody").empty();
      $('#aktiva-total').empty();
      $('#pasiva-total').empty();
      $('#cover-spin').show();
      $('#date_text').empty();
    },
    success: function(html){
		buildData(html);
		$('#date-text').text($('#startDate').val()+' s/d '+$('#endDate').val());
		$('#cover-spin').hide();
		$('#filter-modal').modal('hide'); 
    },
	error: function(params) {
		$('.loading').html('Submit').attr('disabled', false)
	}
  });
});

function buildData(params) {
	//Menghitung Pendapatan
	var assetsTotal = 0;

	params.data.forEach(function(item) {
	  if (item.sub_coa == 1) {
	    assetsTotal += item.total;
	    $("#assets-table tbody").append(
	        "<tr>"+
	          "<td style='text-align:right;'>"+item.accounting_code+"</td>"+
	          "<td>"+item.name+"</td>"+
	          "<td>"+addSeparator(item.total, '.', '.', ',')+"</td>"+
	          "<td></td>"+
	        "</tr>"
	    );
	  }
	});

	$("#assets-table tbody").append(
	    "<tr>"+
	      "<td></td>"+
	      "<td style='text-align:right;'><b>Total Aktiva</b></td>"+
	      "<td></td>"+
	      "<td><b>"+addSeparator(assetsTotal, '.', '.', ',')+"</b></td>"+
	    "</tr>"
	);

	//Menghitung Pendapatan
	var liabiliesTotal = 0;

	params.data.forEach(function(item) {
	  if (item.sub_coa == 2) {
	    liabiliesTotal += item.total;
	    $("#liabilities-table tbody").append(
	        "<tr>"+
	          "<td style='text-align:right;'>"+item.accounting_code+"</td>"+
	          "<td>"+item.name+"</td>"+
	          "<td>"+addSeparator(item.total, '.', '.', ',')+"</td>"+
	          "<td></td>"+
	        "</tr>"
	    );
	  }
	});

	$("#liabilities-table tbody").append(
	    "<tr>"+
	      "<td></td>"+
	      "<td style='text-align:right;'><b>Total Pasiva</b></td>"+
	      "<td></td>"+
	      "<td><b>"+addSeparator(liabiliesTotal, '.', '.', ',')+"</b></td>"+
	    "</tr>"
	);

	//Menghitung Pendapatan
	var equityTotal = 0;

	params.data.forEach(function(item) {
	  if (item.sub_coa == 3) {
	    if (item.coa == params.default_profit_loss_account) {
	    	equityTotal += params.profitLoss;
	      $("#equity-table tbody").append(
	          "<tr>"+
	            "<td style='text-align:right;'>"+item.accounting_code+"</td>"+
	            "<td>"+item.name+"</td>"+
	            "<td>"+addSeparator(params.profitLoss, '.', '.', ',')+"</td>"+
	            "<td></td>"+
	          "</tr>"
	      );
	    } else {
	      equityTotal += item.total;
	      $("#equity-table tbody").append(
	          "<tr>"+
	            "<td style='text-align:right;'>"+item.accounting_code+"</td>"+
	            "<td>"+item.name+"</td>"+
	            "<td>"+addSeparator(item.total, '.', '.', ',')+"</td>"+
	            "<td></td>"+
	          "</tr>"
	      );
	    }
	  }
	});

	$("#equity-table tbody").append(
	    "<tr>"+
	      "<td></td>"+
	      "<td style='text-align:right;'><b>Total Ekuitas</b></td>"+
	      "<td></td>"+
	      "<td><b>"+addSeparator(equityTotal, '.', '.', ',')+"</b></td>"+
	    "</tr>"
	);
	assetsTotalFinal = parseInt(assetsTotal);
	pasivaEquityTotalFinal = parseInt(liabiliesTotal) + (parseInt(equityTotal));

	$('#aktiva-total').text(addSeparator(assetsTotalFinal, '.', '.', ','));
	$('#pasiva-total').text(addSeparator(pasivaEquityTotalFinal, '.', '.', ','));
}

</script>
@endsection
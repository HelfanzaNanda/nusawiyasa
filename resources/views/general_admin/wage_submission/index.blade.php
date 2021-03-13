@extends('layouts.main')

@section('title', 'Pengajuan Upah')

@section('style')
	
@endsection

@section('content')
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Pengajuan Upah</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Pengajuan Upah</a></li>
        <li class="breadcrumb-item active">Data Pengajuan Upah</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
      <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah Pengajuan Upah</a>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Data Pengajuan Upah</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <div class="table-responsive">
          <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
            <thead>
              <tr>
                <th>#</th>
				<th>No.</th>
				<th>Tanggal</th>
				<th>Perumahan</th>
				<th>Total</th>
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

<div id="main-modal" class="modal custom-modal fade" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 1200px !important;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Pengajuan Upah</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="add-form" method="POST" action="#">
          {!! csrf_field() !!}
          <div class="row"> 
            <div class="col-md-12"> 
				<label>No. Ref</label>
				<div class="form-group">
					<input type="hidden" name="id" id="id" value="0">
					<input type="text" class="form-control ref-number" placeholder="KD0001" name="number" id="ref">
				</div><!-- form-group -->
				<div class="form-group">
					<label>Cluster/Perumahan</label>
					<select id="input-cluster" name="cluster_id" 
					required oninvalid="this.setCustomValidity('Harap Isikan Cluster.')" onchange="this.setCustomValidity('')"> 
					  <option value=""> - Pilih Cluster - </option>
					  @foreach($clusters as $cluster)
					    <option value="{{$cluster['id']}}">{{$cluster['name']}}</option>
					  @endforeach
					</select>
				</div>
				<div class="form-group">
					<label>Tanggal</label>
					<input type="text" class="form-control" name="date" id="journal_date" value="{{date('Y-m-d')}}" autocomplete="off"
					required oninvalid="this.setCustomValidity('Harap Isikan Tanggal.')" onblur="this.setCustomValidity('')">
				</div>

				<div class="form-group">
					<table id="myTable" class="table display responsive nowrap">
						<thead>
							<tr>
								<th width="25%">Uraian</th>
								<th width="25%">Kapling</th>
								<th width="25%">Upah SPK</th>
								<th width="10%">%</th>
								<th width="15%">Upah Persentase</th>
								<th width="25%">Keterangan</th>
								<th width="5%"> 
								<button type="button" id="addrow" class="btn btn-info"><i class="fa fa-plus"></i></button>
								</th>
							</tr>
						</thead>
						<tbody id="journal_detail"></tbody>
					</table> 
				</div>
				<input type="hidden" id="typePost">
				<input type="hidden" id="id">
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

@section('additionalFileJS')

@endsection

@section('additionalScriptJS')
<script type="text/javascript">
	var URL_ADD = BASE_URL+'/salary-submission/store';
	var URL_EDIT = BASE_URL+'/salary-submission';
	var URL_UPDATE = BASE_URL+'/salary-submission/update';
	var URL_DELETE = BASE_URL+'/salary-submission/delete';
	var URL_SEARCH_CUSTOMER_LOT = BASE_URL+'/customer_lot_progress';

	$('#input-cluster').select2({
		width: '100%'
	});

	if($('#journal_date').length > 0) {
		$('#journal_date').datetimepicker({
		  format: 'YYYY-MM-DD',
		  icons: {
		    up: "fa fa-angle-up",
		    down: "fa fa-angle-down",
		    next: 'fa fa-angle-right',
		    previous: 'fa fa-angle-left'
		  }
		});
	}

	var jj=0;
	var counter = 0;
    $(document).on('click', '#show-add-modal', function() {
		jj = 0
		$('#ref').val('');
		$('#journal_date').val('');
		$('#description').val('');
		$('#myTable tbody tr').remove();
        $('.loading-area').hide();
        $('#typePost').val('add');
        $("#submit_button").prop('disabled', true);
        $('#main-modal').modal('show'); 

		$(document).ready(function(){
    		var url = '{{ asset('') }}'
			$.ajax({
			type: 'GET',
			url: url+'number/generate?prefix=PU',
			success: function(data){
				$('.ref-number').val(data.number)
			}
			})
		})
    });

	$("#main-table").DataTable({
	  "pageLength": 10,
	  "processing": true,
	  "serverSide": true,
	  // "searching": false,
	  // "ordering": false,
	  "ajax":{
	      "url": BASE_URL+"/salary-submission/datatables",
	      "dataType": "json",
	      "type": "POST",
	      "data":function(d) { 
	        d._token = "{{csrf_token()}}"
	      },
		//   success: function(res){
		// 	  console.log(res);
		//   }
	  },
	  "columns": [
			{data: 'id', name: 'id', width: '5%', "visible": false},
			{data: 'number', name: 'number'},
			{data: 'date', name: 'date'},
			{data: 'cluster_name', name: 'cluster_name'},
			{data: 'total', name: 'total'},
			{data: 'action', name: 'action', className: 'text-right'},
	  ],
	  "order": [[ 0, "desc" ]]
	});

	
	$( 'form#add-form' ).submit( function( e ) {
		e.preventDefault();
		var loading_text = $('.loading').data('loading-text');
		$('.loading').html(loading_text).attr('disabled', true);
		var form_data   = new FormData( this );
		$.ajax({
			type: 'GET',
			url: '{{asset('')}}'+'number/validate?prefix=PU&number='+$('.ref-number').val(),
			success: function(data){
				if (data.status == 'error' && $('#typePost').val() != 'edit') {
					swal({
						title: "Gagal",
						text: "Maaf, Nomor Pengajuan Upah telah digunakan,",
						showConfirmButton: true,
						confirmButtonColor: '#0760ef',
						type:"error",
						html: true
					});
				} else {
					$.ajax({
						type: 'post',
						url: URL_ADD,
						data: form_data,
						cache: false,
						contentType: false,
						processData: false,
						dataType: 'json',
						beforeSend: function() {
							$('.loading-area').show();
						},
						success: function(msg) {
							//console.log(msg);
							if(msg.status == 'success'){
								setTimeout(function() {
									swal({
										title: "Sukses",
										text: msg.message,
										type:"success",
										html: true
									}, function() {
										$('#main-modal').modal('hide');
										$("#main-table").DataTable().ajax.reload( null, false ); // user paging is not reset on reload
									});
								}, 200);
							} else {
								$('.loading-area').hide();
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
				}
				$('.loading').html('Submit').attr('disabled', false)
			},
			error: function(params) {
				$('.loading').html('Submit').attr('disabled', false)
			}
		});
	});

	$(document).on( 'click', '#delete', function( e ) {
		
	    e.preventDefault();
	    var id  = $( this ).attr( "data-id" );
	    swal( {
	        title:                "Apakah anda yakin?",
	        text:                 "Apakah anda yakin menghapus data ini?",
	        type:                 "warning",
	        showCancelButton:     true,
	        closeOnConfirm:       false,
	        showLoaderOnConfirm:  true,
	        confirmButtonText:    "Ya!",
	        cancelButtonText:     'Tidak',
	        confirmButtonColor:   "#ec6c62"
	    }, function() {
	        $.ajax({
	            url:    URL_DELETE + '/' + id,
	            type:   "GET",
				success: function(res) {
				if(res.status == 'success'){
					setTimeout(function() {       
						swal({
							title: "sukses",
							text: res.message,
							type:"success",
							html: true
						}, function() {
							//$('#main-modal').modal('hide');
                          	$("#main-table").DataTable().ajax.reload( null, false ); // user paging is not reset on reload
						});
					}, 500);
				} else {
					swal({
						title: "Gagal",
						text: res.message,
						showConfirmButton: true,
						confirmButtonColor: '#0760ef',
						type:"error",
						html: true
					});
				}
            }
	        })
	    } );
	});

	
	$("#addrow").on("click", function () {
		addRow()
		searchCustomerLot(jj)
		selectDk()
		jj++;
	});


	function selectDk(){
		//$(".select2").select2({minimumResultsForSearch: Infinity});
	    $("#dk_"+ jj).change(function () {
	        var id = $(this).attr('data-id');
	        if ($(this).val() === '1') {
	          $("input#debit_"+ id).val(0);
	          $("input#debit_"+ id).prop('readonly', true);
	          $("input#credit_"+ id).val(0);
	          $("input#credit_"+ id).prop('readonly', false);
	        } else {
	          $("input#credit_"+ id).val(0);
	          $("input#credit_"+ id).prop('readonly', true);
	          $("input#debit_"+ id).val(0);
	          $("input#debit_"+ id).prop('readonly', false);
	        }
	    });
	}

	function searchCustomerLot(number){
		$("#customer_lot_"+number).select2({
	      minimumInputLength: 2,
	      dropdownParent: $("#main-modal .modal-content"),
	      minimumResultsForSearch: '',
	      ajax: {
	        url: URL_SEARCH_CUSTOMER_LOT,
	        dataType: "json",
	        type: "GET",
	        data: function (params) {
	          var queryParameters = {
	            term: params.term,
	            cluster_id: $("select#input-cluster").val()
	          }
	          return queryParameters
	        },
			beforeSend : function()    {           
        		$('#customer_lot_'+number).empty()
    		},
	        processResults: function (data) {
				return {
					results: $.map(data, function (item) {
						return {
							text: item.block + '-' + item.unit_number,
							id: item.id,
							wage: item.wage,
							percentage: item.percentage
						}
					})
				}
			}
	      }
	    });

		$("#customer_lot_"+number).on('change', function(e) {
			console.log(parseFloat($(this).select2('data')[0]['wage']));
		    $('#spk_cost_'+number).val(parseFloat($(this).select2('data')[0]['wage']));
		    $('#weekly_percentage_'+number).val($(this).select2('data')[0]['percentage']);
		    $('#weekly_cost_'+number).val(parseFloat($(this).select2('data')[0]['wage']) * ($(this).select2('data')[0]['percentage']/100));
		    // $('#spk_cost_'+jj).val($(this).select2('data')[0]['wage']);
		});
	}

	function addRow() {
	    var newRow = $("<tr>");
	    var cols = "";
	    cols += '<td><input type="text" class="form-control" name="description[]" id="description_'+jj+'"></td>';
	    cols += '<td><input type="hidden" name="wage_submission[]" id="wage_submission_'+jj+'">';
	    cols += '<input type="hidden" name="wage_submission_detail[]" id="wage_submission_detail_'+jj+'">';
	    cols += '<select id="customer_lot_'+jj+'" class="form-control" name="customer_lot_id[]" style="width: 100%;">';
	    cols += '<option value"">Pilih</option>';
	    cols += '</select></td>';
	    cols += '<td><input type="text" class="form-control" id="spk_cost_'+jj+'"></td>';
	    cols += '<td><input type="text" class="form-control" name="weekly_percentage[]" id="weekly_percentage_'+jj+'"></td>';
	    cols += '<td><input type="text" class="form-control" name="weekly_cost[]" id="weekly_cost_'+jj+'"></td>';
	    cols += '<td><input type="text" class="form-control" name="note[]" id="note_'+jj+'"></td>';
	    cols += '<td><button type="button" id="ibtnDel" class="btn btn-danger"><i class="fa fa-trash"></i></button></td>';
	    newRow.append(cols);
	    $("table#myTable").append(newRow); 
		//selectDk()
		
	}

	$(document).on('click', '#edit', function() {    
		$('#typePost').val('edit');
		$('#myTable tbody tr').html('');
		var id = $(this).data('id');
		$('#id').val(id);
		$('.loading-area').hide();

		$.ajax({
			url: URL_EDIT+'/'+id,
			type: 'GET',
				"headers": {
					'Authorization': "{{ csrf_token() }}"
				},
			dataType: 'JSON',
			success: function(data, textStatus, jqXHR) {
					$('#ref').val(data[0].number);
					$('#id').val(data[0].id);
					$('#input-cluster').val(data[0].cluster_id).trigger('change');
					$('#journal_date').val(data[0].date);
					$.each( data[1], function( key, detail ) {
						addRow()
						searchCustomerLot(jj)
						selectDk()
						$('#description_'+jj).val(detail.description)
						var newOption = new Option(detail.lot, detail.customer_lot_id, true, true);
						$('#customer_lot_'+jj).append(newOption).change();
						$('#wage_submission_'+jj).val(detail.wage_submission_id)
						$('#wage_submission_detail_'+jj).val(detail.id)
						$('#spk_cost_'+jj).val(parseFloat(detail.spk_cost))
						$('#weekly_cost_'+jj).val(parseFloat(detail.weekly_cost))
						$('#weekly_percentage_'+jj).val(detail.weekly_percentage)
						$('#note_'+jj).val(detail.note)
						jj++
						
					});
					
					$('#main-modal').modal('show');
			},
		});    
	});

	$("#submitFilter").click(function(){
	    $("#table-general-ledger").DataTable().ajax.reload();
	});

	function sumDebit() {
	  var sum = 0;
	  $('.debit').each(function(){
	      sum += parseFloat(this.value);
	  });

	  return sum;
	}

	function sumCredit() {
	  var sum = 0;
	  $('.credit').each(function(){
	      sum += parseFloat(this.value);
	  });

	  return sum;
	}

	function checkBalance() {
	  if (sumDebit() === sumCredit()) {
	    $("#submit_button").prop('disabled', false);
	    $("span#balance").text("Balance")
	  } else {
	    $("#submit_button").prop('disabled', true);
	    $("span#balance").text("Tidak Balance")
	  }
	}

	$("table#myTable").on("click", "#ibtnDel", function (event) {
	  $(this).closest("tr").remove();
	  $('#addrow').attr('disabled', false).prop('value', "Add Row");
	});
</script>
@endsection
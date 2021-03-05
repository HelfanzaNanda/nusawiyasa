@extends('layouts.main')

@section('title', 'Jurnal Umum')

@section('style')
	
@endsection

@section('content')
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Data Jurnal Umum</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Jurnal Umum</a></li>
        <li class="breadcrumb-item active">Data Jurnal Umum</li>
      </ul>
    </div>
    <div class="col-auto float-right ml-auto">
      <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah Jurnal Umum</a>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Data Jurnal Umum</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <div class="table-responsive">
          <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
            <thead>
              <tr>
                <th>#</th>
				<th>No. Ref</th>
				<th>Deskripsi</th>
				<th>Tipe</th>
				<th>Tanggal</th>
				<th>Total</th>
				<th>Perumahan</th>
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
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Jurnal Umum</h5>
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
					<input type="text" class="form-control ref-number" placeholder="KD0001" name="ref" id="ref">
				</div><!-- form-group -->
				<div class="form-group">
				<label>Cluster/Perumahan</label>
					<select id="input-cluster" name="cluster_id" required=""> 
					  <option value="0"> - Pilih Cluster - </option>
					  @foreach($clusters as $cluster)
					    <option value="{{$cluster['id']}}">{{$cluster['name']}}</option>
					  @endforeach
					</select>
				</div>
				<label>Tanggal</label>
				<div class="form-group">
					<input type="text" class="form-control" name="date" id="journal_date" value="{{date('Y-m-d')}}" autocomplete="off">
				</div><!-- form-group -->
				<label>Keterangan</label>
				<div class="form-group">
					<textarea class="form-control" name="description" id="description"></textarea>
				</div><!-- form-group -->

				<div class="form-group">
				<table id="myTable" class="table display responsive nowrap">
				  <thead>
				    <tr>
				        <th width="25%">Nama Akun</th>
				        <th width="15%">D/K</th>
				        <th width="25%">Debit</th>
				        <th width="25%">Kredit</th>
				        <th width="10%"> 
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
            <button type="submit" class="btn btn-primary submit-btn loading" 
            data-loading-text='<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Loading...'>
              Submit
            </button>
          </div>
          {{-- <div class="submit-section">
          	<span id="balance"></span><br>
            <button class="btn btn-primary submit-btn" id="submit_button"><i class="fa fa-refresh fa-spin loading-area"></i>Submit</button>
          </div> --}}
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
	var URL_ADD = BASE_URL+'/accounting/general_ledger/store';
	var URL_EDIT = BASE_URL+'/accounting/general_ledger';
	var URL_UPDATE = BASE_URL+'/accounting/general_ledger/update';
	var URL_DELETE = BASE_URL+'/accounting/general_ledger/delete';
	var URL_SEARCH_COA = BASE_URL+'/accounting/get';

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
      url: url+'number/generate?prefix=JU',
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
	      "url": BASE_URL+"/accounting-general-ledger-datatables",
	      "dataType": "json",
	      "type": "POST",
	      "data":function(d) { 
			d.isPk = "no"
	        d._token = "{{csrf_token()}}"
	      },
		//   success: function(res){
		// 	  console.log(res);
		//   }
	  },
	  "columns": [
			{data: 'id', name: 'id', width: '5%', "visible": false},
			{data: 'ref', name: 'ref'},
			{data: 'description', name: 'description'},
			{data: 'type', name: 'type'},
			{data: 'date', name: 'date'},
			{data: 'total', name: 'total'},
			{data: 'cluster_name', name: 'cluster_name'},
			{data: 'action', name: 'action', className: 'text-right'},
	  ],
	  "order": [[ 0, "desc" ]]
	});

    

    // $( 'form#add-form' ).submit( function( e ) {
    //   e.preventDefault();
    //   var form_data   = new FormData( this );
    //   $.ajax({
    //         type: 'post',
    //         url: $('#typePost').val() === 'edit' ? URL_UPDATE + '/' + $('#id').val() : URL_ADD,
    //         data: form_data,
    //         cache: false,
    //         contentType: false,
    //         processData: false,
    //         dataType: 'json',
    //         beforeSend: function() {
    //           $('.loading-area').show();
			 //  console.log($('#typePost').val());
    //         },
    //         success: function(msg) {
				// //console.log(msg);
    //           if(msg.status == 'success'){
    //               setTimeout(function() {
    //                   swal({
    //                       title: "Sukses",
    //                       text: msg.message,
    //                       type:"success",
    //                       html: true
    //                   }, function() {
    //                       $('#main-modal').modal('hide');
    //                       $("#main-table").DataTable().ajax.reload( null, false ); // user paging is not reset on reload
    //                   });
    //               }, 200);
    //           } else {
    //               $('.loading-area').hide();
    //               swal({
    //                   title: "Gagal",
    //                   text: msg.message,
    //                   showConfirmButton: true,
    //                   confirmButtonColor: '#0760ef',
    //                   type:"error",
    //                   html: true
    //               });
    //           }
    //         }
    //   });
    // });

    $( 'form#add-form' ).submit( function( e ) {
      e.preventDefault();
	  var loading_text = $('.loading').data('loading-text');
		$('.loading').html(loading_text).attr('disabled', true);
      var form_data   = new FormData( this );
      $.ajax({
		type: 'GET',
		url: '{{asset('')}}'+'number/validate?prefix=JU&number='+$('.ref-number').val(),
		success: function(data){
		$('.loading').html('Submit').attr('disabled', false)
		if (data.status == 'error' && $('#typePost').val() != 'edit') {
			swal({
				title: "Gagal",
				text: "Maaf, Nomor jurnal umum telah digunakan,",
				showConfirmButton: true,
				confirmButtonColor: '#0760ef',
				type:"error",
				html: true
			});
		} else {
			$.ajax({
				type: 'post',
				url: $('#typePost').val() === 'edit' ? URL_UPDATE + '/' + $('#id').val() : URL_ADD,
				data: form_data,
				cache: false,
				contentType: false,
				processData: false,
				dataType: 'json',
				beforeSend: function() {
				$('.loading-area').show();
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
		console.log(jj);
	    counter = $('#myTable tbody tr').length;
	    var newRow = $("<tr>");
	    var cols = "";

	    cols += '<td><select id="coa_'+jj+'" class="form-control" name="coa[]" style="width: 100%;">';
	    cols += '<option value"">Pilih</option>';
	    cols += '</select></td>';
	    cols += '<td><select class="select2 form-control dk_type" name="type[]" id="dk_'+jj+'" data-id="'+jj+'">';
	    cols += '<option value="">K/D</option>';
	    cols += '<option value="1">K</option>';
	    cols += '<option value="2">D</option>';
	    cols += '</select></td>';
	    cols += '<td><input type="text" class="form-control debit" name="debit[]" id="debit_'+jj+'" onkeyup="checkBalance()"></td>';
	    cols += '<td><input type="text" class="form-control credit" name="credit[]" id="credit_'+jj+'" onkeyup="checkBalance()"></td>';
	    cols += '<td><button type="button" id="ibtnDel" class="btn btn-danger"><i class="fa fa-trash"></i></button></td>';

	    newRow.append(cols);
	    if (counter == 100) $('#addrow').attr('disabled', true).prop('value', "You've reached the limit");
	    $("table#myTable").append(newRow); 
	    //$(".select2").select2({minimumResultsForSearch: Infinity});
	    $("select#dk_"+ jj).change(function () {
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
	    $("#coa_"+jj).select2({
	      minimumInputLength: 2,
	      dropdownParent: $("#main-modal"),
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
	  counter++;
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

	function searchCoa(){
		$("#coa_"+jj).select2({
	      minimumInputLength: 2,
	      dropdownParent: $("#main-modal"),
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
	}

	function addRow() {
	    var newRow = $("<tr>");
	    var cols = "";
	    cols += '<td><input type="hidden" name="accounting_ledger[]" id="accounting_ledger_'+jj+'">';
	    cols += '<select id="coa_'+jj+'" class="form-control" name="coa[]" style="width: 100%;">';
	    cols += '<option value"">Pilih</option>';
	    cols += '</select></td>';
	    cols += '<td><select class="select2 form-control dk_type" name="type[]" id="dk_'+jj+'" data-id="'+jj+'">';
	    cols += '<option value="">K/D</option>';
	    cols += '<option value="1">K</option>';
	    cols += '<option value="2">D</option>';
	    cols += '</select></td>';
	    cols += '<td><input type="text" class="form-control debit" name="debit[]" id="debit_'+jj+'" onkeyup="checkBalance()"></td>';
	    cols += '<td><input type="text" class="form-control credit" name="credit[]" id="credit_'+jj+'" onkeyup="checkBalance()"></td>';
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
					$('#ref').val(data.ref);
					$('#journal_date').val(data.date);
					$('#description').val(data.description);
					$.each( data.accounting_ledgers, function( key, accounting_ledger ) {
						addRow()
						searchCoa()
						selectDk()
						var option = $("<option selected></option>").val(accounting_ledger.coa).text(`${accounting_ledger.accounting_master ? accounting_ledger.accounting_master.accounting_code : 0} | ${accounting_ledger.accounting_master ? accounting_ledger.accounting_master.name : 0}`);
						$(`#coa_${jj}`).append(option).change();
						if (parseInt(accounting_ledger.debit) === 0) {
							$(`#dk_${jj} option[value='1']`).attr('selected','selected');
							$(`#debit_${jj}`).prop('readonly', true);
							$(`#credit_${jj}`).prop('readonly', false);
						}else if(parseInt(accounting_ledger.credit) === 0){
							$(`#dk_${jj} option[value='2']`).attr('selected','selected');
							$(`#debit_${jj}`).prop('readonly', false);
							$(`#credit_${jj}`).prop('readonly', true);
						}
						$(`#debit_${jj}`).val(parseInt(accounting_ledger.debit))
						$(`#credit_${jj}`).val(parseInt(accounting_ledger.credit))
						$(`#accounting_ledger_${jj}`).val(parseInt(accounting_ledger.id))
						console.log(jj);
						jj++
						
					});
					
					$('#main-modal').modal('show');
			},
			error: function(jqXHR, textStatus, errorThrown){

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
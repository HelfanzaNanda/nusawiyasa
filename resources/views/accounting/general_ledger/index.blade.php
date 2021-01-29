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
        <h5 class="modal-title">COA</h5>
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
					<input type="text" class="form-control" placeholder="KD0001" name="ref" id="ref">
				</div><!-- form-group -->
				<label>Tanggal</label>
				<div class="form-group">
					<input type="text" class="form-control" name="date" id="journal_date" value="{{date('Y-m-d')}}">
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
				    
				   <tbody id="journal_detail">

				  </tbody>
				</table> 
				</div>
				<input type="hidden" id="typePost">
				<input type="hidden" id="id">
            </div>
          </div>
          <div class="submit-section">
          	<span id="balance"></span><br>
            <button class="btn btn-primary submit-btn" id="submit_button"><i class="fa fa-refresh fa-spin loading-area"></i>Submit</button>
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
  var URL_ADD = BASE_URL+'/accounting/general_ledger/store';
  var URL_SEARCH_COA = BASE_URL+'/accounting/get';

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

    $(document).on('click', '#show-add-modal', function() {    
        $('.loading-area').hide();
        $('#typePost').val('add');
        $("#submit_button").prop('disabled', true);
        $('#main-modal').modal('show'); 
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
	        d._token = "{{csrf_token()}}"
	      },
	  },
	  "columns": [
			{data: 'id', name: 'id', width: '5%', "visible": false},
			{data: 'ref', name: 'ref'},
			{data: 'description', name: 'description'},
			{data: 'type', name: 'type'},
			{data: 'date', name: 'date'},
			{data: 'total', name: 'total'},
			{data: 'action', name: 'action', className: 'text-right'},
	  ],
	});

    $(document).on('click', '#edit-general-ledger', function() {    

        var id = $(this).data('id');
        $('.loading-area').hide();
        $.ajax({
          url: URL_EDIT+'/'+id,
          type: 'GET',
            "headers": {
                'Authorization': _token
            },
          dataType: 'JSON',
          success: function(data, textStatus, jqXHR){ 
            $('#code').val(data.data.code);
            $('#name').val(data.data.name);
            $('#address').val(data.data.address);
            $('#phone').val(data.data.phone);
            $('#email').val(data.data.email);
            $('#general-ledger_receivable').val(data.data.general-ledger_receivable);
            $('#id').val(id);
            $('#typePost').val('edit');
            $('#main-modal').modal('show');
          },
          error: function(jqXHR, textStatus, errorThrown){

          },
        });    
    });

    $( 'form#add-form' ).submit( function( e ) {
      e.preventDefault();
      var form_data   = new FormData( this );
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
    });

	$( '#table-general-ledger' ).on( 'click', 'button#delete-general-ledger', function( e ) {
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
	            type:   "DELETE"
	        })
	        .done( function( data ) {
	            swal( "Dihapus!", "Data telah berhasil dihapus!", "success" );
	            $('#table-general-ledger').DataTable().ajax.reload();
	        } )
	        .error( function( data ) {
	            swal( "Oops", "We couldn't connect to the server!", "error" );
	        } );
	    } );
	});

	var jj=0;     
	var counter = 0;

	$("#addrow").on("click", function () {

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
	    // $(".select2").select2({minimumResultsForSearch: Infinity});
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
@extends('layouts.main')

@section('title', 'COA')

@section('style')
	<link rel="stylesheet" href="{{ asset('template/assets/plugins/treeview/dist/bootstrap-treeview.min.css') }}">
@endsection

@section('content')
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
    </div>
  </div>
</div>
<!-- /Page Header -->

<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Data COA</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <div class="col-md-12">
        	<div id="treeview2" class=""></div>
        </div>
      </div>
    </div>
  </div>
</div>

<div id="modal-accounting" class="modal custom-modal fade" role="dialog">
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
	            <label>COA</label>
	            <div class="input-group">
	              <div class="input-group-prepend">
	                <span class="input-group-text" id="disabled_coa"></span>
	              </div>
	              <input type="text" class="form-control" id="order_coa" name="order_coa" readonly>
	            </div><!-- input-group --><br>
	            <label>Nama Akun</label>
	            <div class="form-group">
	              <input type="text" class="form-control" name="name" id="name">
	            </div><!-- form-group -->
	            <label>Tipe</label>
	            <div class="form-group">
	              <select name="type" class="form-control" id="type">
	                  <option value="">D/K</option>
	                  <option value="1">D</option>
	                  <option value="2">K</option>
	              </select>
	            </div><!-- form-group -->
	            <input type="hidden" id="coa" name="coa">
	            <input type="hidden" id="id">
	            <input type="hidden" id="typePost">
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

@section('additionalFileJS')
	<script src="{{ asset('template/assets/plugins/treeview/dist/bootstrap-treeview.min.js') }}"></script>
@endsection

@section('additionalScriptJS')
	<script type="text/javascript">
      var treeData;

      $.ajax({
        type: "GET",  
        url: BASE_URL+'/accounting',
        dataType: "json",       
        success: function(response)  
        {
          initTree(response)
        }   
      });
      
      function initTree(treeData) {
        var dataArray = [];
          for (var key in treeData) {
              if (treeData.hasOwnProperty(key)) {
                  dataArray.push(treeData[key]);
              }
          };

          $('#treeview2').treeview({
            levels: 1,
            showBorder: false,
            enableLinks: true,
            selectedBackColor: "#03a9f3",
            onhoverColor: "rgba(0, 0, 0, 0.05)",
            expandIcon: 'fa fa-plus',
            collapseIcon: 'fa fa-minus',
            nodeIcon: 'fa fa-folder',
            data: dataArray,
			// onNodeSelected: function(event, data) {
			// 	console.log(data.nodeId)
			// 	$(this).treeview('expandNode', [ data.nodeId, { levels: 1, silent: true } ]);
			// },
			// onNodeUnselected: function(event, data) {
			// 	console.log(data.nodeId)
			// 	$(this).treeview('collapseNode', [ data.nodeId, { levels: 1, silent: true } ]);
			// },
          });
      }

	$(document).on('click', '#add-accounting', function() {    
	    var id = $(this).data('id');
	    $('#name').val('');
	    $('#type').val('');
	    $('.loading-area').hide();
	    $.ajax({
	      url: BASE_URL+'/accounting/add/'+id,
	      type: 'GET',
	      dataType: 'JSON',
	      success: function(data, textStatus, jqXHR){ 
	        $('#disabled_coa').html(data.accMaster.coa +' - '+ data.accMaster.name);
	        $('#order_coa').val(data.getMaxOrderCOA);
	        $('#coa').val(data.accMaster.coa+''+data.getMaxOrderCOA);
	        $('#id').val(id);
	        $('#typePost').val('add');
	        $('#modal-accounting').modal('show');
	      },
	      error: function(jqXHR, textStatus, errorThrown){

	      },
	    });
	});

	$(document).on('click', '#edit-accounting', function() {    
	    var id = $(this).data('id');
	    $('.loading-area').hide();
	    $.ajax({
	      url: BASE_URL+'/accounting/edit/'+id,
	      type: 'GET',
	      dataType: 'JSON',
	      success: function(data, textStatus, jqXHR){ 
	        $('#disabled_coa').html(data.accMaster.coa +' - '+ data.accMaster.name);
	        $('#order_coa').val(data.getMaxOrderCOA);
	        $('#coa').val(data.accMaster.coa+''+data.getMaxOrderCOA);
	        $('#name').val(data.accMaster.name);
	        $('#type').val(data.accMaster.type);
	        $('#id').val(id);
	        $('#typePost').val('edit');
	        $('#modal-accounting').modal('show');
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
	        url: BASE_URL + ($('#typePost').val() === 'edit' ? '/accounting/update/' + $('#id').val() : '/accounting/store/' + $('#id').val()),
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
	                      window.location.reload();
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
	</script>
@endsection
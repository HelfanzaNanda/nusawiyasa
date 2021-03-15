@extends('layouts.main')

@section('title', 'Penerimaan Barang')

@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title mb-0">Tambah Penerimaan Barang</h4>
      </div>
      <form id="add-form" method="POST" action="#">
        <div class="card-body">
          {!! csrf_field() !!}
          <div class="form-group row">
            <label class="col-form-label col-md-2">No. PO</label>
            <div class="col-md-10">
              <select id="input-po" name="purchase_order_id"
              required oninvalid="this.setCustomValidity('Harap Isikan No PO.')" onchange="this.setCustomValidity('')"  >
                
                <option value=""> - Pilih PO - </option>
                @foreach($purchase_orders as $purchase_order)
                  <option value="{{$purchase_order['id']}}">{{$purchase_order['number']}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">No. BPB</label>
            <div class="col-md-10">
              <input class="form-control floating" type="text" id="input-bpb-number" name="bpb_number"
              required oninvalid="this.setCustomValidity('Harap Isikan No. BPB')" onchange="this.setCustomValidity('')" >
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">Invoice Number</label>
            <div class="col-md-10">
              <input class="form-control floating" type="text" id="input-invoice-number" name="invoice_number"
              required oninvalid="this.setCustomValidity('Harap Isikan Invoice Number.')" onchange="this.setCustomValidity('')" >
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">Tanggal</label>
            <div class="col-md-10">
              <input class="form-control floating" type="text" id="input-date" name="date"
              required oninvalid="this.setCustomValidity('Harap Isikan Tanggal.')" onblur="this.setCustomValidity('')" >
            </div>
          </div>
          <section class="review-section">
            <div class="review-header text-center">
              <h3 class="review-title">Item Penerimaan Barang</h3>
              <p class="text-muted">Silahkan masukkan poin - poin penerimaan barang</p>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordered table-review review-table mb-0" id="general_comments">
                    <thead>
                      <tr>
                        <th style="width:40px;">#</th>
                        <th>Kode Barang</th>
                        <th>Item</th>
                        <th>Jumlah Diterima</th>
                        <th>Satuan</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    <tbody id="general_comments_tbody">

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </section>
        </div>
        <div class="card-footer">
          <div class="col-auto float-right ml-auto pb-2">
            <div class="col-auto float-right ml-auto pb-2">
              <button type="button" class="btn btn-close mr-2 btn-secondary" data-dismiss="modal">Kembali</button>
              <button type="submit" class="btn btn-primary float-right loading" 
              data-loading-text='<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Loading...'>
                Submit
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('additionalScriptJS')
<script type="text/javascript">
$('.btn-close').on('click', function(){
      window.location.replace('receipt-of-goods')
  })
  $(document).ready(function(){
    var url = '{{ asset('') }}'
    
    $.ajax({
      type: 'GET',
      url: url+'number/generate?prefix=BkPB',
      success: function(data){
        $('#input-bpb-number').val(data.number)
      }
    })
  })

  $('#input-po').select2({
    width: '100%'
  });

  if($('#input-date').length > 0) {
    $('#input-date').datetimepicker({
      format: 'YYYY-MM-DD',
      icons: {
        up: "fa fa-angle-up",
        down: "fa fa-angle-down",
        next: 'fa fa-angle-right',
        previous: 'fa fa-angle-left'
      }
    });
  }

  $("#input-po").on('change', function(e) {
    e.preventDefault();
    let id = $(this).val();
    $.ajax({
      url: BASE_URL+'/purchase_orders/'+id,
      type: "GET",
      dataType: "json",
      beforeSend: function() {
        $("tbody#general_comments_tbody").empty();
      },
      success: function(res) {
        let cols = '';
        $.each(res.items, function(key, value) {
          cols += '<tr>';
          cols += '<td>'+(key + 1)+'</td>';
          cols += '<td>'+value.inventory.code+'<input type="hidden" name="inventory_id[]" value="'+ value.inventory_id +'"></td>';
          cols += '<td>'+value.inventory.name+'</td>';
          cols += '<td><input type="text" class="form-control" name="delivered_qty[]" value="0"></td>';
          cols += '<td>'+value.inventory.unit.name+'</td>';
          cols += '<td><textarea class="form-control" name="note[]"></textarea></td>';
          cols += '</tr>';
        });

        $("tbody#general_comments_tbody").append(cols);
      }
    });
  });

  $('form#add-form').submit( function( e ) {
    e.preventDefault();
    var loading_text = $('.loading').data('loading-text');
        $('.loading').html(loading_text).attr('disabled', true);
    var form_data = new FormData( this );

    $.ajax({
    type: 'GET',
    url: '{{asset('')}}'+'number/validate?prefix=BkPB&number='+$('#input-bpb-number').val(),
    success: function(data){
      
      if(data.status == 'error'){
        swal({
          title: "Gagal",
          text: "Maaf, Nomor BPB telah digunakan,",
          showConfirmButton: true,
          confirmButtonColor: '#0760ef',
          type:"error",
          html: true
        });
      }else{
        $.ajax({
          type: 'post',
          url: BASE_URL+'/receipt-of-goods',
          data: form_data,
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
                  title: "Sukses",
                  text: msg.message,
                  type:"success",
                  html: true
                }, function() {
                  $('#add-modal').modal('hide');
                  window.location.replace("{{url('/receipt-of-goods')}}");
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
            $('.loading').html('Submit').attr('disabled', false)
          },
          error: function(params) {
              $('.loading').html('Submit').attr('disabled', false)
          }
        });
      }
      $('.loading').html('Submit').attr('disabled', false)
    },
      error: function(params) {
          $('.loading').html('Submit').attr('disabled', false)
      }
  })
  });
</script>
@endsection
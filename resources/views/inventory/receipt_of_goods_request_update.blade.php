@extends('layouts.main')

@section('title', 'Update Bon Permintaan Barang')

@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title mb-0">Update Bon Permintaan Barang</h4>
      </div>
      <form id="add-form" method="POST" action="#">
        <div class="card-body">
          {!! csrf_field() !!}
          <div class="form-group row">
            <label class="col-form-label col-md-2">No. Bon</label>
            <div class="col-md-10">
              <input class="form-control floating" type="text" id="input-number" name="number"value="{{ $receipt->number }}">
              <input type="hidden" name="id" value="{{ $receipt->id }}">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">Tanggal</label>
            <div class="col-md-10">
              <input class="form-control floating" type="text" id="input-date" name="date" value="{{ $receipt->date }}">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">Perumahan Yang Mengajukan</label>
            <div class="col-md-10">
              <select id="input-lot" name="lot_id"> 
                <option value="0"> - Pilih Kapling - </option>
                @foreach($lots as $lot)
                  <option value="{{$lot['id']}}" {{ ($lot['id'] == $receipt->lot_id) ? 'selected' : '' }}>{{$lot['name']}} - {{$lot['block']}} / {{$lot['unit_number']}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <section class="review-section">
            <div class="review-header text-center">
              <h3 class="review-title">Item Bon Permintaan Barang</h3>
              <p class="text-muted">Silahkan Masukkan Poin - Poin Bon Permintaan Barang</p>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordered table-review review-table mb-0" id="general_comments">
                    <thead>
                      <tr>
                        <th style="width:40px;">#</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Satuan</th>
                        <th>Keterangan</th>
                        <th style="width: 64px;"></th>
                      </tr>
                      <tr>
                        <th style="width:40px;">#</th>
                        <th colspan="2">
                          <input type="hidden" class="form-control add-item" id="input-item-code" placeholder="code">
                          <select id="item" class="form-control" style="z-index: 9999;">
                            <option value=""> Pilih </option>
                          </select>
                        </th>
                        <th><input type="text" class="form-control add-item" id="input-item-qty" placeholder="qty"></th>
                        <th><input type="text" class="form-control add-item" id="input-item-unit" placeholder="Satuan"></th>
                        <th><input type="text" class="form-control add-item" id="input-item-note" placeholder="Keterangan"></th>
                        <th style="width: 64px;"><button type="button" class="btn btn-primary btn-add-row"><i class="fa fa-plus"></i></button></th>
                      </tr>
                    </thead>
                    <tbody id="general_comments_tbody" >
                        @foreach ($receipt->items as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td></td>
                                <td>
                                    {{ $item->inventory->name }}
                                    <input type="hidden" name="inventory_id[]" value="{{ $item->inventory->id }}">
                                </td>
                                <td>
                                    {{ $item->qty }}
                                    <input type="hidden" name="qty[]" value="{{ $item->qty }}">
                                </td>
                                <td>
                                    {{ $item->inventory->unit->name }}
                                </td>
                                <td>
                                    {{ $item->note }}
                                    <input type="hidden" name="note[]" value="{{ $item->note }}">
                                </td>
                                <td>
                                    <button type="button" id="comments_remove" class="btn btn-danger">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </section>
        </div>
        <div class="card-footer">
          <div class="col-auto float-right ml-auto pb-2">
            <button type="button" class="btn btn-close mr-2 btn-secondary" data-dismiss="modal">Kembali</button>
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
@endsection

@section('additionalScriptJS')
<script type="text/javascript">
$('.btn-close').on('click', function(){
      window.location.replace('/receipt-of-goods-request')
  })
  $('#input-lot').select2({
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

  $("#item").select2({
    width: '100%',
    minimumInputLength: 2,
    minimumResultsForSearch: '',
    ajax: {
      url: BASE_URL+"/inventories",
      dataType: "json",
      type: "GET",
      data: function (params) {
        var queryParameters = {
          name: params.term
        }
        return queryParameters
      },
      processResults: function (data) {
        return {
          results: $.map(data.data, function (item) {
            return {
              text: item.name,
              id: item.id,
              price: item.purchase_price,
              unit: item.unit_name,
              code: item.code
            }
          })
        }
      }
    }
  });

  $("#item").on('change', function(e) {
      $("#input-item-unit").val($(this).select2('data')[0]['unit']);
      $("#input-item-code").val($(this).select2('data')[0]['code']);
  });

  $(document).on("click", '.btn-add-row', function () {
    var id = $(this).closest("table.table-review").attr('id');  // Id of particular table
    var div = $("<tr />");
    div.html(GetDynamicTextBox(id));
    $("#"+id+"_tbody").append(div);
    calculateTotal();
  });

  $(document).on("click", "#comments_remove", function () {
    $(this).closest("tr").prev().find('td:last-child').html('<button type="button" class="btn btn-danger" id="comments_remove"><i class="fa fa-trash-o"></i></button>');
    $(this).closest("tr").remove();
    calculateTotal();
  });

  function GetDynamicTextBox(table_id) {
    $('#comments_remove').remove();
    var rowsLength = document.getElementById(table_id).getElementsByTagName("tbody")[0].getElementsByTagName("tr").length+1;
    let cols = '';

    var inventoryId = $('#item :selected').val();
    var inventoryCode = $('#input-item-code').val();
    var inventoryName = $('#item :selected').text();
    var inventoryQty = $('#input-item-qty').val();
    var inventoryUnit = $('#input-item-unit').val();
    var inventoryNote = $('#input-item-note').val();

    cols += '<td>'+rowsLength+'</td>';
    cols += '<td>'+inventoryCode+'</td>';
    cols += '<td>'+inventoryName+'<input type="hidden" name="inventory_id[]" value="'+ inventoryId +'"></td>';
    cols += '<td>'+inventoryQty+'<input type="hidden" name="qty[]" value="'+ inventoryQty +'"></td>';
    cols += '<td>'+inventoryUnit+'</td>';
    cols += '<td>'+inventoryNote+'<input type="hidden" name="note[]" value="'+ inventoryNote +'"></td>';
    cols += '<td><button type="button" class="btn btn-danger" id="comments_remove"><i class="fa fa-trash-o"></i></button></td>';

    return cols;
  }

  $('.add-item').keypress(function (e) {
    if (e.which == 13) {
      var id = $(this).closest("table.table-review").attr('id');  // Id of particular table
      var div = $("<tr />");
      div.html(GetDynamicTextBox(id));
      $("#"+id+"_tbody").append(div);

      $("select#item").select2("open");

      $("#input-item-qty").val('');
      $("#input-item-unit").val('');
      $("#input-item-note").val('');

      return false;    //<---- Add this line
    }
  });

  $('form#add-form').submit( function( e ) {
    e.preventDefault();
    var loading_text = $('.loading').data('loading-text');
        $('.loading').html(loading_text).attr('disabled', true);
    var form_data = new FormData( this );

    $.ajax({
      type: 'post',
      url: BASE_URL+'/receipt-of-goods-request',
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
                    // $('#main-table').DataTable().ajax.reload(null, false);
                    $('#add-modal').modal('hide');
                    window.location.replace("{{url('/receipt-of-goods-request')}}");
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
  });
</script>
@endsection
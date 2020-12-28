@extends('layouts.main')

@section('title', 'Tambah Purchase Order')

@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title mb-0">Tambah Purchase Order</h4>
      </div>
      <form id="add-form" method="POST" action="#">
        <div class="card-body">
          {!! csrf_field() !!}
          <div class="form-group row">
            <label class="col-form-label col-md-2">No. PO</label>
            <div class="col-md-10">
              <input class="form-control floating" type="text" id="input-number" name="number">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">No. Pengajuan</label>
            <div class="col-md-10">
              <select id="input-fpp" name="fpp_number"> 
                <option value="0"> - Pilih No FPP - </option>
                @foreach($request_materials as $request_material)
                  <option value="{{$request_material['id']}}">{{$request_material['number']}}</option>
                @endforeach
              </select>
            </div>
          </div>
{{--           <div class="form-group row">
            <label class="col-form-label col-md-2">Perumahan Yang Mengajukan</label>
            <div class="col-md-10">
              <select id="input-lot" name="lot_id"> 
                <option value="0"> - Pilih Kapling - </option>
                @foreach($lots as $lot)
                  <option value="{{$lot['id']}}">{{$lot['name']}} - {{$lot['block']}} / {{$lot['unit_number']}}</option>
                @endforeach
              </select>
            </div>
          </div> --}}
          <div class="form-group row">
            <label class="col-form-label col-md-2">Yang Mengajukan</label>
            <div class="col-md-10">
              <select id="input-cluster" name="cluster_id"> 
                <option value="0"> - Pilih Perumahan/Cluster - </option>
                @foreach($clusters as $cluster)
                  <option value="{{$cluster['id']}}">{{$cluster['name']}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">Tanggal</label>
            <div class="col-md-10">
              <input class="form-control floating" type="text" id="input-date" name="date">
            </div>
          </div>
          
          <div class="form-group row">
            <label class="col-form-label col-md-2">Tipe Permintaan</label>
            <div class="col-md-10">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="type" id="rap" value="rap" checked="">
                <label class="form-check-label" for="rap">
                RAP
                </label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="type" id="non-rap" value="non_rap">
                <label class="form-check-label" for="non-rap">
                Non RAP
                </label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="type" id="disposition" value="disposition">
                <label class="form-check-label" for="disposition">
                Disposisi
                </label>
              </div>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-md-2">Keterangan</label>
            <div class="col-md-10">
              <textarea rows="4" class="form-control" placeholder="Keterangan" name="note"></textarea>
            </div>
          </div>
          <section class="review-section">
            <div class="review-header text-center">
              <h3 class="review-title">Item Purchase Order</h3>
              <p class="text-muted">Silahkan masukkan poin - poin purchase order</p>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordered table-review review-table mb-0" id="general_comments">
                    <thead>
                      <tr>
                        <th style="width:40px;">#</th>
                        <th>Item</th>
                        <th>Supplier</th>
                        <th>Qty</th>
                        <th>Satuan</th>
                        <th>Harga</th>
                        <th>Total</th>
                        <th style="width: 64px;"></th>
                      </tr>
                      <tr>
                        <th style="width:40px;">#</th>
                        <th>
                          <select id="item" class="form-control" style="z-index: 9999;">
                            <option value=""> Pilih </option>
                          </select>
                        </th>
                        <th>
                          <select id="input-supplier"> 
                            <option value="0"> - Pilih Supplier - </option>
                            @foreach($suppliers as $supplier)
                              <option value="{{$supplier['id']}}">{{$supplier['name']}}</option>
                            @endforeach
                          </select>
                        </th>
                        <th><input type="text" class="form-control add-item" id="input-item-qty" placeholder="qty" onkeyup="addItemCalc()"></th>
                        <th><input type="text" class="form-control add-item" id="input-item-unit" placeholder="Satuan"></th>
                        <th><input type="text" class="form-control add-item" id="input-item-price" placeholder="Harga" onkeyup="addItemCalc()"></th>
                        <th><input type="text" class="form-control add-item" id="input-item-total" placeholder="Total"></th>
                        <th style="width: 64px;"><button type="button" class="btn btn-primary btn-add-row"><i class="fa fa-plus"></i></button></th>
                      </tr>
                    </thead>
                    <tbody id="general_comments_tbody" >

                    </tbody>
                    <tfoot>
                      <tr>
                        <td colspan="6" style="text-align: right;">Subtotal</td>
                        <td><input type="text" name="subtotal" id="input-subtotal" class="form-control"></td>
                      </tr>
                      <tr>
                        <td colspan="6" style="text-align: right;">Pajak</td>
                        <td><input type="text" name="tax" id="input-tax" class="form-control" onkeyup="totalCalc()"></td>
                      </tr>
                      <tr>
                        <td colspan="6" style="text-align: right;">Pengiriman</td>
                        <td><input type="text" name="delivery" id="input-delivery" class="form-control" onkeyup="totalCalc()"></td>
                      </tr>
                      <tr>
                        <td colspan="6" style="text-align: right;">Lain - Lain</td>
                        <td><input type="text" name="other" id="input-other" class="form-control" onkeyup="totalCalc()"></td>
                      </tr>
                      <tr>
                        <td colspan="6" style="text-align: right;">Total</td>
                        <td><input type="text" name="total" id="input-total" class="form-control"></td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              </div>
            </div>
          </section>
        </div>
        <div class="card-footer">
          <div class="col-auto float-right ml-auto pb-2">
            <button class="btn btn-primary" type="submit">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('additionalScriptJS')
<script type="text/javascript">
  $('#input-supplier').select2({
    width: '100%'
  });

  $('#input-cluster').select2({
    width: '100%'
  });

  $('#input-fpp').select2({
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
            item.purchase_price = item.purchase_price ? item.purchase_price : 0;
            return {
              text: item.name,
              id: item.id,
              price: item.purchase_price,
              unit: item.unit_name
            }
          })
        }
      }
    }
  });

  $("#item").on('change', function(e) {
      // $("#input-qty").val(addSeparator($(this).select2('data')[0]['price']));
      $("#input-item-unit").val(addSeparator($(this).select2('data')[0]['unit'], '.', '.', ','));
      $("#input-item-price").val(addSeparator($(this).select2('data')[0]['price'], '.', '.', ','));
      // $("#input-total").val(addSeparator($(this).select2('data')[0]['price']));
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
    var inventoryName = $('#item :selected').text();
    var inventorySupplierId = $('#input-supplier :selected').val();
    var inventorySupplierName = $('#input-supplier :selected').text();
    var inventoryQty = $('#input-item-qty').val();
    var inventoryUnit = $('#input-item-unit').val();
    var inventoryPrice = addSeparator($('#input-item-price').val(), '.', '.', ',');
    var subTotal = addSeparator($('#input-item-total').val(), '.', '.', ',');

    cols += '<td>'+rowsLength+'</td>';
    cols += '<td>'+inventoryName+'<input type="hidden" name="item_inventory_id[]" value='+ inventoryId +'></td>';
    cols += '<td>'+inventorySupplierName+'<input type="hidden" name="item_supplier_id[]" value='+ inventorySupplierId +'></td>';
    cols += '<td>'+inventoryQty+'<input type="hidden" name="item_qty[]" value='+ inventoryQty +'></td>';
    cols += '<td>'+inventoryUnit+'</td>';
    cols += '<td>'+inventoryPrice+'<input type="hidden" name="item_price[]" value='+ inventoryPrice +'></td>';
    cols += '<td id="total">'+subTotal+'<input type="hidden" name="item_total[]" value='+ subTotal +'></td>';
    cols += '<td><button type="button" class="btn btn-danger" id="comments_remove"><i class="fa fa-trash-o"></i></button></td>';

    return cols;
  }

  function calculateTotal() {
    let total = 0;

    $("td#total").each(function() {
        var value = detectFloat($(this).text());
        // add only if the value is number
        if(!isNaN(value) && value.length != 0) {
          total += value;
        }
    });  

    $("#input-subtotal").val(addSeparator(total.toFixed(2), '.', '.', ','));
    totalCalc();
  }

  function addItemCalc() {
    var qty = parseFloat($("#input-item-qty").val());
    var price = parseFloat(($("#input-item-price").val()).split(',').join(''));

    var total = qty * price;

    // var disc_percentage = total * (parseFloat($("#item_disc_percent").val())/100);
    // var disc_value = parseFloat($("#item_disc_value").val());

    // var calculate = total - disc_percentage - disc_value;

    $("#input-item-total").val(addSeparator(total.toFixed(2), '.', '.', ','));
  }

  function totalCalc() {
    let tax = detectFloat($("#input-tax").val());
    let delivery = detectFloat($("#input-delivery").val());
    let other = detectFloat($("#input-other").val());
    let subtotal = detectFloat($("#input-subtotal").val());
    let total = 0;

    total = subtotal - tax - delivery - other;

    $("#input-total").val(addSeparator(total.toFixed(2), '.', '.', ','));

    return;
  }

  $('.add-item').keypress(function (e) {
    if (e.which == 13) {
      var id = $(this).closest("table.table-review").attr('id');  // Id of particular table
      var div = $("<tr />");
      div.html(GetDynamicTextBox(id));
      $("#"+id+"_tbody").append(div);

      calculateTotal();

      $("select#item").select2("open");

      $("#input-item-qty").val(0);
      $("#input-item-unit").val('');
      $("#input-item-price").val(0);
      $("#input-item-total").val(0);

      // $( ".tseparator" ).each( function () {
      //     $(this).on("blur", function (e) {
      //       var val = this.value;

      //       $(this).val(addSeparator(val));
      //     });

      //     $(this).on("focus", function (e) {
      //       var val = this.value;

      //       $(this).val(detectFloat(val));
      //     });
      // });

      return false;    //<---- Add this line
    }
  });

  $('form#add-form').submit( function( e ) {
    e.preventDefault();
    var form_data = new FormData( this );

    $.ajax({
      type: 'post',
      url: BASE_URL+'/purchase-order',
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
                    window.location.replace("{{url('/purchase-order')}}");
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
      }
    });
  });
</script>
@endsection
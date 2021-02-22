@extends('layouts.main')

@section('title', 'Tambah RAP')

@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title mb-0">Update RAP</h4>
      </div>
      <form id="add-form" method="POST" action="#">
        <div class="card-body">
          {!! csrf_field() !!}
          <div class="form-group row">
            <label class="col-form-label col-md-2">Judul</label>
            <div class="col-md-10">
              <input class="form-control floating" type="text" id="input-title" name="title" value="{{ $rap->title }}">
              <input type="hidden" name="id" value="{{ $rap->id }}">
            </div>
          </div>
{{--           <div class="form-group row">
            <label class="col-form-label col-md-2">Kapling</label>
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
            <label class="col-form-label col-md-2">Perumahan/Cluster</label>
            <div class="col-md-10">
              <select id="input-cluster" name="cluster_id">
                <option value="0"> - Pilih Perumahan/Cluster - </option>
                @foreach($clusters as $cluster)
                  <option value="{{$cluster['id']}}" @if ($cluster['id'] == $rap->cluster_id)
                      selected
                  @endif>{{$cluster['name']}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">Total</label>
            <div class="col-md-10">
              <input class="form-control floating" type="text" id="input-total" name="total" readonly="" value="{{ number_format((int)str_replace('.', '', $rap->total), 2, '.', ',') }}">
            </div>
          </div>
          <section class="review-section">
            <div class="review-header text-center">
              <h3 class="review-title">Item RAP</h3>
              <p class="text-muted">Silahkan masukkan poin - poin RAP</p>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordered table-review review-table mb-0" id="general_comments">
                    <thead>
                      <tr>
                        <th style="width:40px;">#</th>
                        <th>Item</th>
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
                        <th><input type="text" class="form-control add-item" id="input-item-qty" placeholder="qty" onkeyup="addItemCalc()"></th>
                        <th><input type="text" class="form-control add-item" id="input-item-unit" placeholder="Satuan"></th>
                        <th><input type="text" class="form-control add-item" id="input-item-price" placeholder="Harga" onkeyup="addItemCalc()"></th>
                        <th><input type="text" class="form-control add-item" id="input-item-total" placeholder="Total"></th>
                        <th style="width: 64px;"><button type="button" class="btn btn-primary btn-add-row"><i class="fa fa-plus"></i></button></th>
                      </tr>
                    </thead>
                    <tbody id="general_comments_tbody" >
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($rap->rapItem as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>
                                    {{ $item->inventory->name }}
                                    <input type="hidden" name="item_inventory_id[]" value="{{ $item->inventory->id }}">
                                </td>
                                <td>
                                    {{ $item->qty }}
                                    <input type="hidden" name="item_qty[]" value="{{ $item->qty }}">
                                </td>
                                <td>{{ $item->inventory->unit->name }}</td>
                                <td>
                                    {{ number_format((int)explode('.', $item->price)[0], 2, '.', ',') }}
                                    <input type="hidden" name="item_price[]" value="{{ number_format((int)explode('.', $item->price)[0], 2, '.', ',') }}">
                                </td>
                                <td id="total">
                                  {{ number_format((int)explode('.', $item->total)[0], 2, '.', ',') }}
                                    <input type="hidden" name="item_total[]" value="{{ number_format((int)explode('.', $item->total)[0], 2, '.', ',') }}">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger" id="comments_remove">
                                        <i class="fa fa-trash-o">
                                        </i>
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
  $('#input-cluster').select2({
    width: '100%'
  });

  var cluster_id = '';
  $(document).on('change', '#input-cluster', function(){
    cluster_id = $(this).val()
  });

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
          cluster_id: cluster_id,
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
    var inventoryQty = $('#input-item-qty').val();
    var inventoryUnit = $('#input-item-unit').val();
    var inventoryPrice = addSeparator($('#input-item-price').val(), '.', '.', ',');
    var subTotal = addSeparator($('#input-item-total').val(), '.', '.', ',');

    cols += '<td>'+rowsLength+'</td>';
    cols += '<td>'+inventoryName+'<input type="hidden" name="item_inventory_id[]" value='+ inventoryId +'></td>';
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

    $("#input-total").val(addSeparator(total.toFixed(2), '.', '.', ','));
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

  $('.add-item').keypress(function (e) {
    if (e.which == 13) {
      var id = $(this).closest("table.table-review").attr('id');  // Id of particular table
      var div = $("<tr />");
      div.html(GetDynamicTextBox(id));
      $("#"+id+"_tbody").append(div);

      calculateTotal();

      $("select#item").select2("open");

      $("#input-item-qty").val(0);
      $("#input-item-unit").val(0);
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
    var loading_text = $('.loading').data('loading-text');
    $('.loading').html(loading_text).attr('disabled', true);
    var form_data = new FormData( this );

    $.ajax({
      type: 'post',
      url: BASE_URL+'/rap',
      data: form_data,
      cache: false,
      contentType: false,
      processData: false,
      dataType: 'json',
      beforeSend: function() {
        
      },
      success: function(msg) {
        $('.loading').html('Submit').attr('disabled', false)
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
                    window.location.replace("{{url('/rap')}}");
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
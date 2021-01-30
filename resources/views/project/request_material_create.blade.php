@extends('layouts.main')

@section('title', 'Tambah Pengajuan Bahan')

@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title mb-0">Tambah Pengajuan Bahan</h4>
      </div>
      <form id="add-form" method="POST" action="#">
        <div class="card-body">
          {!! csrf_field() !!}
{{--           <div class="form-group row">
            <label class="col-form-label col-md-2">Judul</label>
            <div class="col-md-10">
              <input class="form-control floating" type="text" id="input-title" name="title">
            </div>
          </div> --}}
          <div class="form-group row">
            <label class="col-form-label col-md-2">Nomor Pengajuan</label>
            <div class="col-md-10">
              <input class="form-control floating" type="text" id="input-number" name="number">
            </div>
          </div>
{{--           <div class="form-group row">
            <label class="col-form-label col-md-2">Perihal</label>
            <div class="col-md-10">
              <input class="form-control floating" type="text" id="input-subject" name="subject">
            </div>
          </div> --}}
          <div class="form-group row">
            <label class="col-form-label col-md-2">SPK</label>
            <div class="col-md-10">
              <select id="input-spk" name="spk_id"> 
                <option value="0"> - Pilih SPK - </option>
                @foreach($spk as $row)
                  <option value="{{$row['id']}}">{{$row['number']}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">Perumahan/Cluster</label>
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
            <label class="col-form-label col-md-2">Kavling</label>
            <div class="col-md-10">
              <select id="input-lot" name="lot_id"> 
                <option value="0"> - Pilih Kavling - </option>
              </select>
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
{{--               <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="type" id="disposition" value="disposition">
                <label class="form-check-label" for="disposition">
                Disposisi
                </label>
              </div> --}}
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">Tanggal</label>
            <div class="col-md-10">
              <input class="form-control floating" type="text" id="input-date" name="date">
            </div>
          </div>
          <section class="review-section">
            <div class="review-header text-center">
              <h3 class="review-title">Item Pengajuan Bahan</h3>
              <p class="text-muted">Silahkan masukkan poin - poin Pengajuan Bahan</p>
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
                        <th>Merk</th>
                        <th style="width: 64px;"></th>
                      </tr>
                      <tr>
                        <th style="width:40px;">#</th>
                        <th>
                          <select id="item" class="form-control" style="z-index: 9999;">
                            <option value=""> Pilih </option>
                          </select>
                        </th>
                        <th><input type="text" class="form-control add-item" id="input-item-qty" placeholder="qty"></th>
                        <th><input type="text" class="form-control add-item" id="input-item-unit" placeholder="Satuan" readonly></th>
                        <th><input type="text" class="form-control add-item" id="input-item-brand" placeholder="Merk"></th>
                        <th style="width: 64px;"><button type="button" class="btn btn-primary btn-add-row"><i class="fa fa-plus"></i></button></th>
                      </tr>
                    </thead>
                    <tbody id="general_comments_tbody" >

                    </tbody>
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
  $(document).ready(function(){
    var url = '{{ asset('') }}'
    
    $.ajax({
      type: 'GET',
      url: url+'number/generate?prefix=PB',
      success: function(data){
        $('#input-number').val(data.number)
      }
    })
  })
  $('#input-spk').select2({
    width: '100%'
  });

  $('#input-cluster').select2({
    width: '100%'
  });

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

  $("#input-cluster").on('change', function(e) {
    e.preventDefault();
    let id = $(this).val();
    $.ajax({
      url: BASE_URL+'/get_lots?all=true&cluster_id='+id,
      type: "GET",
      dataType: "json",
      beforeSend: function(xhr) {
        $("select#input-lot").empty();
        $("select#input-lot").append('<option value="0"> - Pilih Kavling - </option>');
        
      },
      success: function(res) {
        let cols = '';
        $.each(res, function(key, value) {
          cols += '<option value="'+value.id+'">'+value.block+' - '+value.unit_number+'</option>';
        });
        $("select#input-lot").append(cols);
      }
    });
  });

  $("#item").select2({
    // tags: true,
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
              brand: item.brand,
              unit: item.unit_name
            }
          })
        }
      }
    },
    // createTag: function (params) {
    //   return {
    //     id: params.term,
    //     text: params.term,
    //     brand: "Isikan Merk...",
    //     unit: "Isikan Unit...",
    //     newOption: true
    //   }
    // },
    // templateResult: function (data) {
    //   var $result = $("<span></span>");

    //   $result.text(data.text);

    //   if (data.newOption) {
    //     $result.append(" <em>(new)</em>");
    //   }

    //   return $result;
    // }
  });

  $("#item").on('change', function(e) {
      // $("#input-qty").val(addSeparator($(this).select2('data')[0]['brand']));
      $("#input-item-unit").val(addSeparator($(this).select2('data')[0]['unit'], '.', '.', ','));
      $("#input-item-brand").val($(this).select2('data')[0]['brand']);
      // $("#input-total").val(addSeparator($(this).select2('data')[0]['price']));
  });

  $(document).on("click", '.btn-add-row', function () {
    var inventoryId = $('#item :selected').val();
    var inventoryName = $('#item :selected').text();
    var inventoryQty = $('#input-item-qty').val();
    var inventoryUnit = $('#input-item-unit').val();
    var inventoryBrand = $('#input-item-brand').val();

    if (inventoryId == '' || 
        inventoryQty == '' ||
        inventoryUnit == '') {
      swal({
          title: "Gagal",
          text: 'Harap Pilih Item',
          showConfirmButton: true,
          confirmButtonColor: '#0760ef',
          type:"error",
          html: true
      });

      return;
    }
    var id = $(this).closest("table.table-review").attr('id');  // Id of particular table
    var div = $("<tr />");
    div.html(GetDynamicTextBox(id));
    $("#"+id+"_tbody").append(div);

    $("select#item").select2("open");

  });

  $(document).on("click", "#comments_remove", function () {
    $(this).closest("tr").prev().find('td:last-child').html('<button type="button" class="btn btn-danger" id="comments_remove"><i class="fa fa-trash-o"></i></button>');
    $(this).closest("tr").remove();
  });

  function GetDynamicTextBox(table_id) {
    $('#comments_remove').remove();
    var rowsLength = document.getElementById(table_id).getElementsByTagName("tbody")[0].getElementsByTagName("tr").length+1;
    let cols = '';

    var inventoryId = $('#item :selected').val();
    var inventoryName = $('#item :selected').text();
    var inventoryQty = $('#input-item-qty').val();
    var inventoryUnit = $('#input-item-unit').val();
    var inventoryBrand = $('#input-item-brand').val();

    cols += '<td>'+rowsLength+'</td>';
    cols += '<td>'+inventoryName+'<input type="hidden" name="item_inventory_id[]" value='+ inventoryId +'> <input type="hidden" name="item_name[]" value='+ inventoryName +'></td>';
    cols += '<td>'+inventoryQty+'<input type="hidden" name="item_qty[]" value='+ inventoryQty +'></td>';
    cols += '<td>'+inventoryUnit+'<input type="hidden" name="item_unit[]" value='+ inventoryUnit +'></td>';
    cols += '<td>'+inventoryBrand+'<input type="hidden" name="item_brand[]" value='+ inventoryBrand +'></td>';
    cols += '<td><button type="button" class="btn btn-danger" id="comments_remove"><i class="fa fa-trash-o"></i></button></td>';

    return cols;
  }

  $('.add-item').keypress(function (e) {
    if (e.which == 13) {
      var inventoryId = $('#item :selected').val();
      var inventoryName = $('#item :selected').text();
      var inventoryQty = $('#input-item-qty').val();
      var inventoryUnit = $('#input-item-unit').val();
      var inventoryBrand = $('#input-item-brand').val();

      if (inventoryId == '' || 
          inventoryQty == '' ||
          inventoryUnit == '') {
        swal({
            title: "Gagal",
            text: 'Harap Pilih Item',
            showConfirmButton: true,
            confirmButtonColor: '#0760ef',
            type:"error",
            html: true
        });

        return;
      }

      var id = $(this).closest("table.table-review").attr('id');  // Id of particular table
      var div = $("<tr />");
      div.html(GetDynamicTextBox(id));
      $("#"+id+"_tbody").append(div);

      $("select#item").select2("open");

      return false;    //<---- Add this line
    }
  });

  $('form#add-form').submit( function( e ) {
    e.preventDefault();
    var form_data = new FormData( this );
    var rowsLength = document.getElementById("general_comments").getElementsByTagName("tbody")[0].getElementsByTagName("tr").length+1;
    if (rowsLength < 2) {
      swal({
          title: "Gagal",
          text: 'Harap Isikan Item',
          showConfirmButton: true,
          confirmButtonColor: '#0760ef',
          type:"error",
          html: true
      });

      return;
    }
    $.ajax({
      type: 'GET',
      url: '{{asset('')}}'+'number/validate?prefix=PB&number='+$('#input-number').val(),
      success: function(data){
        if(data.status == 'error'){
          swal({
            title: "Gagal",
            text: "Maaf, Nomor surat pengajuan bahan telah digunakan,",
            showConfirmButton: true,
            confirmButtonColor: '#0760ef',
            type:"error",
            html: true
          });
        }else{
          $.ajax({
            type: 'post',
            url: BASE_URL+'/request-material',
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
                          window.location.replace("{{url('/request-material')}}");
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
        }
      }
    })
  });

  $("#input-spk").on('change', function(e) {
    e.preventDefault();
    let id = $(this).val();
    $.ajax({
      url: BASE_URL+'/spk_projects/'+id,
      type: "GET",
      dataType: "json",
      beforeSend: function() {
        addLoadSpiner($('#input-lot')); 
        // $("select#input-lot").empty();
        // $("select#input-lot").append('<option value="0"> - Pilih Kavling - </option>');
      },
      success: function(res) {
        $('#input-cluster').val(res.customer_lot.lot.cluster_id).trigger('change');

        setTimeout(function(){ 
          $('#input-lot').val(res.customer_lot.lot_id).trigger('change');
          hideLoadSpinner($('#input-lot'));
        }, 1000);
      }
    });
  });
</script>
@endsection
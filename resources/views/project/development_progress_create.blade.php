@extends('layouts.main')

@section('title', 'Progress Pembangunan')

@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title mb-0">Tambah Progress Pembangunan</h4>
      </div>
      <form id="add-form" method="POST" action="#">
        <div class="card-body">
          {!! csrf_field() !!}
          <div class="form-group row">
            <label class="col-form-label col-md-2">Kapling</label>
            <div class="col-md-10">
              <select id="input-lot" name="lot_id"
              required oninvalid="this.setCustomValidity('Harap Isikan Kavling.')" onchange="this.setCustomValidity('')" > 
                <option value=""> - Pilih Kapling - </option>
                @foreach($lots as $lot)
                  <option value="{{$lot['id']}}" data-cluster-id="{{$lot['cluster_id']}}" data-customer-id="{{$lot['customer_id']}}">{{$lot['name']}} - {{$lot['block']}} / {{$lot['unit_number']}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">Tanggal</label>
            <div class="col-md-10">
              <input class="form-control floating" type="text" id="input-date" name="date"
              required oninvalid="this.setCustomValidity('Harap Isikan Tanggal.')" onchange="this.setCustomValidity('')" >
            </div>
          </div>
          <div class="form-group row">
            <label class="col-form-label col-md-2">Persentase</label>
            <div class="col-md-10">
              <input class="form-control floating" type="text" id="input-percentage" name="percentage"
              required oninvalid="this.setCustomValidity('Harap Isikan Persentase.')" onchange="this.setCustomValidity('')" >
            </div>
          </div>
          <section class="review-section">
            <div class="review-header text-center">
              <h3 class="review-title">DOKUMENTASI</h3>
              <p class="text-muted">Foto proses pembangunan</p>
              <button type="button" id="add-file-documentation" class="btn btn-primary mt-2"> Tambah Foto </button>
            </div>
            <div class="row" id="file-documentation">

            </div>
          </section>
          <section class="review-section">
            <div class="review-header text-center">
              <h3 class="review-title">Daftar Pekerjaan</h3>
              <p class="text-muted">Pekerjaan yang dilakukan di lapangan</p>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordered table-review review-table mb-0" id="table-jobs">
                    <thead>
                      <tr>
                        <th style="width:40px;">#</th>
                        <th>Pekerjaan Yang Dilaksanakan</th>
                        <th>Lokasi</th>
                        <th>Volume</th>
                        <th>Keterangan</th>
                        <th style="width: 64px;"></th>
                      </tr>
                      <tr>
                        <th style="width:40px;">#</th>
                        <th><input type="text" class="form-control add-item" id="input-job-work" placeholder="Pekerjaan"></th>
                        <th><input type="text" class="form-control add-item" id="input-job-location" placeholder="Lokasi"></th>
                        <th><input type="text" class="form-control add-item" id="input-job-volume" placeholder="Volume"></th>
                        <th><input type="text" class="form-control add-item" id="input-job-note" placeholder="Keterangan"></th>
                        <th style="width: 64px;"><button type="button" class="btn btn-primary btn-add-row"><i class="fa fa-plus"></i></button></th>
                      </tr>
                    </thead>
                    <tbody id="table-jobs-tbody" >

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </section>
          <section class="review-section">
            <div class="review-header text-center">
              <h3 class="review-title">Bahan Material</h3>
              <p class="text-muted">Bahan yang digunakan dalam mengerjakan pekerjaan</p>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordered table-review review-table mb-0" id="table-materials">
                    <thead>
                      <tr>
                        <th style="width:40px;">#</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Satuan</th>
                        <th style="width: 64px;"></th>
                      </tr>
                      <tr>
                        <th style="width:40px;">#</th>
                        <th>
                          <select id="item-material" class="form-control" style="z-index: 9999;">
                            <option value=""> Pilih </option>
                          </select>
                        </th>
                        <th><input type="text" class="form-control add-item" id="input-material-qty" placeholder="Qty"></th>
                        <th><input type="text" class="form-control add-item" id="input-material-unit" placeholder="Satuan"></th>
                        <th style="width: 64px;"><button type="button" class="btn btn-primary btn-add-row"><i class="fa fa-plus"></i></button></th>
                      </tr>
                    </thead>
                    <tbody id="table-materials-tbody" >

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </section>
          <section class="review-section">
            <div class="review-header text-center">
              <h3 class="review-title">Alat Material</h3>
              <p class="text-muted">Alat yang digunakan dalam mengerjakan pekerjaan</p>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordered table-review review-table mb-0" id="table-tools">
                    <thead>
                      <tr>
                        <th style="width:40px;">#</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Satuan</th>
                        <th style="width: 64px;"></th>
                      </tr>
                      <tr>
                        <th style="width:40px;">#</th>
                        <th>
                          <select id="item-tools" class="form-control" style="z-index: 9999;">
                            <option value=""> Pilih </option>
                          </select>
                        </th>
                        <th><input type="text" class="form-control add-item" id="input-tools-qty" placeholder="Qty"></th>
                        <th><input type="text" class="form-control add-item" id="input-tools-unit" placeholder="Satuan"></th>
                        <th style="width: 64px;"><button type="button" class="btn btn-primary btn-add-row"><i class="fa fa-plus"></i></button></th>
                      </tr>
                    </thead>
                    <tbody id="table-tools-tbody" >

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </section>
          <section class="review-section">
            <div class="review-header text-center">
              <h3 class="review-title">Tenaga Kerja</h3>
              <p class="text-muted">Tenaga Kerja yang digunakan dalam mengerjakan pekerjaan</p>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-bordered table-review review-table mb-0" id="table-service">
                    <thead>
                      <tr>
                        <th style="width:40px;">#</th>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Satuan</th>
                        <th style="width: 64px;"></th>
                      </tr>
                      <tr>
                        <th style="width:40px;">#</th>
                        <th>
                          <select id="item-service" class="form-control" style="z-index: 9999;">
                            <option value=""> Pilih </option>
                          </select>
                        </th>
                        <th><input type="text" class="form-control add-item" id="input-service-qty" placeholder="Qty"></th>
                        <th><input type="text" class="form-control add-item" id="input-service-unit" placeholder="Satuan"></th>
                        <th style="width: 64px;"><button type="button" class="btn btn-primary btn-add-row"><i class="fa fa-plus"></i></button></th>
                      </tr>
                    </thead>
                    <tbody id="table-service-tbody" >

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
      window.location.replace('development-progress')
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

  $("#item-material").select2({
    width: '100%',
    minimumInputLength: 2,
    minimumResultsForSearch: '',
    ajax: {
      url: BASE_URL+"/inventories",
      dataType: "json",
      type: "GET",
      data: function (params) {
        var queryParameters = {
          name: params.term,
          type: 'materials'
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
    }
  });

  $("#item-service").select2({
    width: '100%',
    minimumInputLength: 2,
    minimumResultsForSearch: '',
    ajax: {
      url: BASE_URL+"/inventories",
      dataType: "json",
      type: "GET",
      data: function (params) {
        var queryParameters = {
          name: params.term,
          type: 'service'
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
    }
  });

  $("#item-tools").select2({
    width: '100%',
    minimumInputLength: 2,
    minimumResultsForSearch: '',
    ajax: {
      url: BASE_URL+"/inventories",
      dataType: "json",
      type: "GET",
      data: function (params) {
        var queryParameters = {
          name: params.term,
          type: 'tools'
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
    }
  });

  $("#item-material").on('change', function(e) {
    $("#input-material-unit").val($(this).select2('data')[0]['unit']);
  });

  $("#item-service").on('change', function(e) {
    $("#input-service-unit").val($(this).select2('data')[0]['unit']);
  });

  $("#item-tools").on('change', function(e) {
    $("#input-tools-unit").val($(this).select2('data')[0]['unit']);
  });

  $(document).on("click", '#add-file-documentation', function () {
    let div = '';
    div += '<div class="col-md-3 pb-1 pt-1">';
    div += '<input type="file" class="dropify" data-max-file-size="10M" name="file[]" />';
    div += '</div>';

    $("#file-documentation").append(div);

    $('.dropify').dropify();
  });

  $(document).on("click", '.btn-add-row', function () {
    var id = $(this).closest("table.table-review").attr('id');  // Id of particular table
    var div = $("<tr />");
    div.html(GetDynamicTextBox(id));
    $("#"+id+"-tbody").append(div);
  });

  $(document).on("click", "#remove-jobs", function () {
    $(this).closest("tr").prev().find('td:last-child').html('<button type="button" class="btn btn-danger" id="remove-jobs"><i class="fa fa-trash-o"></i></button>');
    $(this).closest("tr").remove();
  });

  $(document).on("click", "#remove-materials", function () {
    $(this).closest("tr").prev().find('td:last-child').html('<button type="button" class="btn btn-danger" id="remove-materials"><i class="fa fa-trash-o"></i></button>');
    $(this).closest("tr").remove();
  });

  $(document).on("click", "#remove-service", function () {
    $(this).closest("tr").prev().find('td:last-child').html('<button type="button" class="btn btn-danger" id="remove-service"><i class="fa fa-trash-o"></i></button>');
    $(this).closest("tr").remove();
  });

  $(document).on("click", "#remove-tools", function () {
    $(this).closest("tr").prev().find('td:last-child').html('<button type="button" class="btn btn-danger" id="remove-tools"><i class="fa fa-trash-o"></i></button>');
    $(this).closest("tr").remove();
  });

  function GetDynamicTextBox(table_id) {
    let cols = '';
    var rowsLength = document.getElementById(table_id).getElementsByTagName("tbody")[0].getElementsByTagName("tr").length+1;

    if (table_id == 'table-jobs') {
      $('#remove-jobs').remove();

      var jobWork = $("#input-job-work").val();
      var jobLocation = $("#input-job-location").val();
      var jobVolume = $("#input-job-volume").val();
      var jobNote = $("#input-job-note").val();

      cols += '<td>'+rowsLength+'</td>';
      cols += '<td>'+jobWork+'<input type="hidden" name="job_work[]" value="'+ jobWork +'"></td>';
      cols += '<td>'+jobLocation+'<input type="hidden" name="job_location[]" value="'+ jobLocation +'"></td>';
      cols += '<td>'+jobVolume+'<input type="hidden" name="job_volume[]" value="'+ jobVolume +'"></td>';
      cols += '<td>'+jobNote+'<input type="hidden" name="job_note[]" value="'+ jobNote +'"></td>';
      cols += '<td><button type="button" class="btn btn-danger" id="remove-jobs"><i class="fa fa-trash-o"></i></button></td>';
    } else if (table_id == 'table-materials') {
      $('#remove-materials').remove();

      var materialId = $('#item-material :selected').val();
      var materialName = $('#item-material :selected').text();
      var materialQty = $('#input-material-qty').val();
      var materialUnit = $('#input-material-unit').val();

      cols += '<td>'+rowsLength+'</td>';
      cols += '<td>'+materialName+'<input type="hidden" name="material_inventory_id[]" value="'+ materialId +'"></td>';
      cols += '<td>'+materialQty+'<input type="hidden" name="material_qty[]" value="'+ materialQty +'"></td>';
      cols += '<td>'+materialUnit+'<input type="hidden" name="material_unit[]" value="'+ materialUnit +'"></td>';
      cols += '<td><button type="button" class="btn btn-danger" id="remove-jobs"><i class="fa fa-trash-o"></i></button></td>';
    } else if (table_id == 'table-service') {
      $('#remove-service').remove();

      var serviceId = $('#item-service :selected').val();
      var serviceName = $('#item-service :selected').text();
      var serviceQty = $('#input-service-qty').val();
      var serviceUnit = $('#input-service-unit').val();

      cols += '<td>'+rowsLength+'</td>';
      cols += '<td>'+serviceName+'<input type="hidden" name="service_inventory_id[]" value="'+ serviceId +'"></td>';
      cols += '<td>'+serviceQty+'<input type="hidden" name="service_qty[]" value="'+ serviceQty +'"></td>';
      cols += '<td>'+serviceUnit+'<input type="hidden" name="service_unit[]" value="'+ serviceUnit +'"></td>';
      cols += '<td><button type="button" class="btn btn-danger" id="remove-jobs"><i class="fa fa-trash-o"></i></button></td>';
    } else if (table_id == 'table-tools') {
      $('#remove-tools').remove();

      var toolsId = $('#item-tools :selected').val();
      var toolsName = $('#item-tools :selected').text();
      var toolsQty = $('#input-tools-qty').val();
      var toolsUnit = $('#input-tools-unit').val();

      cols += '<td>'+rowsLength+'</td>';
      cols += '<td>'+toolsName+'<input type="hidden" name="tools_inventory_id[]" value="'+ toolsId +'"></td>';
      cols += '<td>'+toolsQty+'<input type="hidden" name="tools_qty[]" value="'+ toolsQty +'"></td>';
      cols += '<td>'+toolsUnit+'<input type="hidden" name="tools_unit[]" value="'+ toolsUnit +'"></td>';
      cols += '<td><button type="button" class="btn btn-danger" id="remove-jobs"><i class="fa fa-trash-o"></i></button></td>';
    }

    $("#input-job-work").val('');
    $("#input-job-location").val('');
    $("#input-job-volume").val('');
    $("#input-job-note").val('');
    $('#input-material-qty').val('');
    $('#input-material-unit').val('');
    $('#input-service-qty').val('');
    $('#input-service-unit').val('');
    $('#input-tools-qty').val('');
    $('#input-tools-unit').val('');

    return cols;
  }

  $('.add-item').keypress(function (e) {
    if (e.which == 13) {
      var id = $(this).closest("table.table-review").attr('id');  // Id of particular table
      var div = $("<tr />");
      div.html(GetDynamicTextBox(id));
      $("#"+id+"-tbody").append(div);

      return false;
    }
  });
 
  $('form#add-form').submit( function( e ) {
    e.preventDefault();
    var loading_text = $('.loading').data('loading-text');
    $('.loading').html(loading_text).attr('disabled', true);
    var form_data = new FormData( this );

    $.ajax({
      type: 'post',
      url: BASE_URL+'/development-progress',
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
              window.location.replace("{{url('/development-progress')}}");
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
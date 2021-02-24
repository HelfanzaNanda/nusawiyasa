@extends('layouts.main')

@section('title', 'Ubah Pengajuan Keuangan')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Ubah Pengajuan Keuangan</h4>
                </div>
                <form id="add-form" action="#" method="post">
                    <div class="card-body">
                        @csrf
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">No. Pengajuan Keuangan</label>
                            <div class="col-md-10">
                                <input class="form-control floating" type="text" id="input-number" name="number" value="{{ $financial->number }}" readonly>
                                <input type="hidden" name="created_by_user_id" value="{{$id}}">
                                <input type="hidden" name="id" value="{{$financial->id}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Perumahan/Cluster</label>
                            <div class="col-md-10">
                              <select id="input-cluster" name="cluster_id">
                                <option value="0"> - Pilih Perumahan/Cluster - </option>
                                @foreach($clusters as $cluster)
                                  <option value="{{$cluster['id']}}" {{ ($cluster['id'] == $financial->cluster_id) ? 'selected' : '' }}>{{$cluster['name']}}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Tanggal</label>
                            <div class="col-md-10">
                                <input class="form-control floating" type="text" value="{{ $financial->date }}" id="input-date" name="date">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-2">Total</label>
                            <div class="col-md-10">
                                <input class="form-control floating" type="text" id="input-total" value="{{ number_format($financial->total,2,'.',',') }}" name="total" readonly="" value="0">
                            </div>
                        </div>
                        <section class="review-section">
                            <div class="review-header text-center">
                                <h3 class="review-title">Item Pengajuan Keuangan</h3>
                                <p class="text-muted">Silahkan masukan poin poin Pengajuan Keuangan</p>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-review review-table mb-0" id="general_comments">
                                            <thead>
                                                <tr>
                                                    <th style="width:40px;">#</th>
                                                    <th>Uraian</th>
                                                    <th>Jumlah</th>
                                                    <th>Satuan</th>
                                                    <th>Nilai</th>
                                                    <th>Nilai Total</th>
                                                    <th>Keterangan</th>
                                                    <th style="width: 64px;"></th>
                                                </tr>
                                                <tr>
                                                    <th style="width: 40px">#</th>
                                                    <th>
                                                        <input type="text" id="input-item-value" class="form-control add-item" placeholder="Uraian">
                                                    </th>
                                                    <th>
                                                        <input type="number" id="input-item-qty" class="form-control add-item" placeholder="Jumlah">
                                                    </th>
                                                    <th>
                                                        <select id="input-item-unit">
                                                            <option value="">Pilih Satuan</option>
                                                            @foreach ($units as $item)
                                                                <option value="{{$item->name}}">{{$item->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </th>
                                                    <th>
                                                        <input type="number" id="input-item-price" oninput="getTotalPrice()" class="form-control add-item" placeholder="Harga">
                                                    </th>
                                                    <th>
                                                        <input type="number" id="input-item-total-price" readonly class="form-control add-item" placeholder="Total Harga">
                                                    </th>
                                                    <th>
                                                        <input type="text" id="input-item-note" class="form-control add-item" placeholder="Keterangan">
                                                    </th>
                                                    <th style="width: 64px;"><button type="button" class="btn btn-primary btn-add-row"><i class="fa fa-plus"></i></button></th>
                                                </tr>
                                            </thead>
                                            <tbody id="general_comments_tbody">
                                                @foreach ($financial->detail as $item)
                                                    <tr>
                                                        <td>
                                                            {{ $no++ }}
                                                        </td>
                                                        <td>
                                                            {{$item->value}}
                                                            <input type="hidden" name="item_value[]" value="{{$item->value}}">
                                                        </td>
                                                        <td>
                                                            {{$item->qty}}
                                                            <input type="hidden" name="item_qty[]" value="{{ $item->qty }}">
                                                        </td>
                                                        <td>
                                                            {{$item->unit}}
                                                            <input type="hidden" name="item_unit[]" value="{{ $item->unit }}">
                                                        </td>
                                                        <td>
                                                            {{ number_format($item->price,2,'.',',') }}
                                                            <input type="hidden" name="item_price[]" value="{{ number_format($item->price,0,'.',',') }}">
                                                        </td>
                                                        <td>
                                                            {{ number_format($item->total_price,0,'.',',') }}
                                                            <input type="hidden" name="item_total_price[]" value="{{ number_format($item->total_price,0,'.',',') }}">
                                                        </td>
                                                        <td>
                                                            {{$item->note}}
                                                            <input type="hidden" name="item_note[]" value="{{$item->note}}">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger" id="comments_remove"><i class="fa fa-trash-o"></i></button>
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
        $('#input-item-unit').select2({
            width: '100%'
        });
        
        $('#input-cluster').select2({
            width: '100%'
        });

        $(document).on("click", '.btn-add-row', function () {
            var id = $(this).closest("table.table-review").attr('id');  // Id of particular table
            var div = $("<tr />");
            div.html(GetDynamicTextBox(id));
            $("#"+id+"_tbody").append(div);

            $('#input-item-value').val('')
            $('#input-item-qty').val('0')
            $('#input-item-unit').val('')
            $('#input-item-price').val('0')
            $('#input-item-total-price').val('0')
            calculateTotal();
        });

        function GetDynamicTextBox(table_id){
            $('#comments_remove').remove();
            var rowsLength = document.getElementById(table_id).getElementsByTagName("tbody")[0].getElementsByTagName("tr").length+1;
            let cols = '';

            var value = $('#input-item-value').val()
            var qty = $('#input-item-qty').val()
            var unit = $('#input-item-unit').val()
            var price = addSeparator($('#input-item-price').val(), '.', '.', ',');
            var totalPrice = addSeparator($('#input-item-total-price').val(), '.', '.', ',');
            var note = ($('#input-item-note').val() == "") ? '-' : $('#input-item-note').val()

            cols += '<td>'+rowsLength+'</td>';
            cols += '<td>'+value+'<input type="hidden" name="item_value[]" value='+ value +'></td>';
            cols += '<td>'+qty+'<input type="hidden" name="item_qty[]" value='+ qty +'></td>';
            cols += '<td>'+unit+'<input type="hidden" name="item_unit[]" value='+ unit +'></td>';
            cols += '<td>'+price+'<input type="hidden" name="item_price[]" value='+ price +'></td>';
            cols += '<td id="total">'+totalPrice+'<input type="hidden" name="item_total_price[]" value='+ totalPrice +'></td>';
            cols += '<td>'+note+'<input type="hidden" name="item_note[]" value='+ note +'></td>';
            cols += '<td><button type="button" class="btn btn-danger" id="comments_remove"><i class="fa fa-trash-o"></i></button></td>';
            return cols;

        }

        $(document).on("click", "#comments_remove", function () {
            $(this).closest("tr").prev().find('td:last-child').html('<button type="button" class="btn btn-danger" id="comments_remove"><i class="fa fa-trash-o"></i></button>');
            $(this).closest("tr").remove();
            calculateTotal();
        });

        function calculateTotal() {
            let total = {{ $financial->total }};

            $("td#total").each(function() {
                var value = detectFloat($(this).text());
                // add only if the value is number
                if(!isNaN(value) && value.length != 0) {
                total += value;
                }
            });  
            $("#input-total").val(addSeparator(total.toFixed(2), '.', '.', ','));
        }
        function getTotalPrice(){
            var qty = $('#input-item-qty').val()
            var price = $('#input-item-price').val()

            var totalPrice = qty * price

            $('#input-item-total-price').val(totalPrice);
        }

        $('form#add-form').submit(function(e){
            e.preventDefault();
            var loading_text = $('.loading').data('loading-text');
            $('.loading').html(loading_text).attr('disabled', true);

            var form_data = new FormData( this );
            $.ajax({
                type: 'post',
                url: BASE_URL+'/financial-submission/store',
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
                                window.location.replace("{{url('/financial-submission')}}");
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
        })
    </script>
@endsection
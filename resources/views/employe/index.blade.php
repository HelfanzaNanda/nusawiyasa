@extends('layouts.main')

@section('title', 'Pegawai')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title">Data Pegawai</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index-2.html">Pegawai</a></li>
                <li class="breadcrumb-item active">Data Pegawai</li>
            </ul>
        </div>
        <div class="col-auto float-right ml-auto">
            <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah Pegawai</a>
        </div>
    </div>
    </div>
    <!-- /Page Header -->
    <div class="row">
        <div class="col-md-12 d-flex">
            <div class="card card-table flex-fill">
                <div class="card-header">
                    <h3 class="card-title mb-0">Data Pegawai</h3>
                </div>
                <div class="card-body ml-3 mt-3 mr-3 mb-3">
                    <div class="table-responsive">
                        <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Jenis Kelamin</th>
                                <th>Bergabung Pada</th>
                                <th>Nomor Rekening</th>
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
    @include('employe.modal.create', ['provinces' => $provinces])    
    @include('employe.modal.update', ['provinces' => $provinces])
@endsection

@section('additionalScriptJS')
    <script type="text/javascript">
        if($('#input-dob').length > 0) {
            $('#input-dob').datetimepicker({
            format: 'YYYY-MM-DD',
            icons: {
                up: "fa fa-angle-up",
                down: "fa fa-angle-down",
                next: 'fa fa-angle-right',
                previous: 'fa fa-angle-left'
            }
            });
        }

        if($('#input-joined').length > 0) {
            $('#input-joined').datetimepicker({
            format: 'YYYY-MM-DD',
            icons: {
                up: "fa fa-angle-up",
                down: "fa fa-angle-down",
                next: 'fa fa-angle-right',
                previous: 'fa fa-angle-left'
            }
            });
        }
        if($('#input-resign').length > 0) {
            $('#input-resign').datetimepicker({
            format: 'YYYY-MM-DD',
            icons: {
                up: "fa fa-angle-up",
                down: "fa fa-angle-down",
                next: 'fa fa-angle-right',
                previous: 'fa fa-angle-left'
            }
            });
        }

        if($('#input-dob-update').length > 0) {
            $('#input-dob-update').datetimepicker({
            format: 'YYYY-MM-DD',
            icons: {
                up: "fa fa-angle-up",
                down: "fa fa-angle-down",
                next: 'fa fa-angle-right',
                previous: 'fa fa-angle-left'
            }
            });
        }

        if($('#input-joined-update').length > 0) {
            $('#input-joined-update').datetimepicker({
            format: 'YYYY-MM-DD',
            icons: {
                up: "fa fa-angle-up",
                down: "fa fa-angle-down",
                next: 'fa fa-angle-right',
                previous: 'fa fa-angle-left'
            }
            });
        }
        if($('#input-resign-update').length > 0) {
            $('#input-resign').datetimepicker({
            format: 'YYYY-MM-DD',
            icons: {
                up: "fa fa-angle-up",
                down: "fa fa-angle-down",
                next: 'fa fa-angle-right',
                previous: 'fa fa-angle-left'
            }
            });
        }

        $("#main-table").DataTable({
            "pageLength": 10,
            "processing": true,
            "serverSide": true,
            // "searching": false,
            // "ordering": false,
            "ajax":{
                "url": BASE_URL+"/employe-datatables",
                "dataType": "json",
                "type": "POST",
                "data":function(d) { 
                    d._token = "{{csrf_token()}}"
                },
            },
            "columns": [
                {data: 'id', name: 'id', width: '5%', "visible": false},
                {data: 'fullname', name: 'name', className: 'td-limit'},
                {data: 'email', name:'email', className: 'td-limit'},
                {data: 'gender', name: 'gender', className: 'td-limit'},
                {data: 'joined_at', name: 'joined_at', className: 'td-limit'},
                {data: 'bank_account', name: 'bank_account', className: 'td-limit'},
                {data: 'action', name: 'action', className: 'text-right'},
            ],
        });

        $('#input-gender').select2({
            width: '100%'
        });

        $('#input-religion').select2({
            width: '100%'
        });

        $('#input-province').select2({
            width: '100%'
        });

        $('#input-city').select2({
            width: '100%'
        });

        $('#input-relation').select2({
            width: '100%'
        });

        $('#input-blood').select2({
            width: '100%'
        });

        $('#input-mariage').select2({
            width: '100%'
        });
        $('#input-identity').select2({
            width: '100%'
        });

        $('#input-status').select2({
            width: '100%'
        });

        $('#input-status-update').select2({
            width: '100%'
        });

        $('#input-identity-update').select2({
            width: '100%'
        });

        $('#input-province').on('change', function() {
            var province_id = $("option:selected", this).data('province-code');
            if(province_id) {
                $.ajax({
                    url: BASE_URL+'/city_by_province/'+province_id,
                    type: "GET",
                    dataType: "json",
                    beforeSend: function() {
                        $('#input-city').empty();
                    },
                    success: function(data) {
                    $.each(data, function(key, value) {
                        $('#input-city').append('<option value="'+ value.name +'" data-city="'+ value.code+'">' + value.name + '</option>');
                    });
                    }
                });
            } else {
                // $('#city').empty();
            }
        });

        $('#input-gender-update').select2({
            width: '100%'
        });

        $('#input-religion-update').select2({
            width: '100%'
        });

        $('#input-province-update').select2({
            width: '100%'
        });

        $('#input-city-update').select2({
            width: '100%'
        });

        $('#input-relation-update').select2({
            width: '100%'
        });

        $('#input-blood-update').select2({
            width: '100%'
        });

        $('#input-mariage-update').select2({
            width: '100%'
        });

        $('#input-province-update').on('change', function() {
            var province_id = $("option:selected", this).data('province-code');
            if(province_id) {
                $.ajax({
                    url: BASE_URL+'/city_by_province/'+province_id,
                    type: "GET",
                    dataType: "json",
                    beforeSend: function() {
                        $('#input-city-update').empty();
                    },
                    success: function(data) {
                    $.each(data, function(key, value) {
                        $('#input-city-update').append('<option value="'+ value.name +'" data-city="'+ value.code+'">' + value.name + '</option>');
                    });
                    }
                });
            } else {
                // $('#city').empty();
            }
        });

        $("#show-add-modal").on('click',function() {
            $('#add-modal').modal('show');
        });

        $('form#add-form').submit(function(e){
            e.preventDefault();
            var loading_text = $('.loading').data('loading-text');
            $('.loading').html(loading_text).attr('disabled', true);
            var form_data = new FormData( this );
            
            $.ajax({
                type: 'post',
                url: BASE_URL+'/employe',
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
                                $('#main-table').DataTable().ajax.reload(null, false);
                                $('#add-modal').modal('hide');
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
            })  
        });

        $(document).on('click', '#edit', function(){
            var id = $(this).data("id")
            $('#update-modal').modal('show');

            $.ajax({
                url : BASE_URL+'/employe/'+id,
                type : 'GET',
                dataType: "json",
                beforeSend: function() {
                
                },
                success: function(data) {
                    $('#id').val(data.id)
                    $('#fullname').val(data.fullname)
                    $('#owner_bank_number').val(data.owner_bank_number)
                    $('#place_birth').val(data.place_birth)
                    $('#input-dob-update').val(data.date_birth)    
                    $('#email').val(data.email)
                    $('#phone_number').val(data.phone_number)
                    $('#current_address_kecamatan').val(data.current_address_kecamatan)
                    $('#current_address_kelurahan').val(data.current_address_kelurahan)
                    $('#current_address_rt').val(data.current_address_rt)
                    $('#current_address_rw').val(data.current_address_rw)
                    $('#current_address_street').val(data.current_address_street)
                    $('#bank_name').val(data.bank_name)
                    $('#bank_account').val(data.bank_account)
                    $('#identity_card_number').val(data.identity_card_number)
                    $('#father_name').val(data.father_name)
                    $('#mother_name').val(data.mother_name)
                    $('#emergency_name').val(data.emergency_name)
                    $('#emergency_number').val(data.emergency_number)
                    // $('#emergency_relation').val(data.emergency_relation)
                    $('#employe_status').val(data.employe_status)
                    $('#input-joined-update').val(data.joined_at)
                    $('#input-resign-update').val(data.resign_at)
                    $('#twitter').val(data.twitter)
                    $('#facebook').val(data.facebook)
                    $('#linkedin').val(data.linkedin)
                    $('#youtube').val(data.youtube)
                    $('#instagram').val(data.instagram)

                    $('#input-status-update').select2()
                    $('#input-status-update').val(data.employe_status)
                    $('#input-status-update').select2().trigger('change');
                    $('#input-status-update').select2({
                        width: '100%'
                    });

                    $('#input-identity-update').select2()
                    $('#input-identity-update').val(data.identity_type)
                    $('#input-identity-update').select2().trigger('change');
                    $('#input-identity-update').select2({
                        width: '100%'
                    });

                    $('#input-gender-update').select2()
                    $('#input-gender-update').val(data.gender)
                    $('#input-gender-update').select2().trigger('change');
                    $('#input-gender-update').select2({
                        width: '100%'
                    });

                    $('#input-mariage-update').select2()
                    $('#input-mariage-update').val(data.mariage_status)
                    $('#input-mariage-update').select2().trigger('change');
                    $('#input-mariage-update').select2({
                        width: '100%'
                    });

                    $('#input-blood-update').select2()
                    $('#input-blood-update').val(data.blood_type)
                    $('#input-blood-update').select2().trigger('change');
                    $('#input-blood-update').select2({
                        width: '100%'
                    });

                    $('#input-blood-update').select2()
                    $('#input-blood-update').val(data.blood_type)
                    $('#input-blood-update').select2().trigger('change');
                    $('#input-blood-update').select2({
                        width: '100%'
                    });

                    $('#input-province-update').select2()
                    $('#input-province-update').val(data.current_address_province)
                    $('#input-province-update').select2().trigger('change');
                    $('#input-province-update').select2({
                        width: '100%'
                    });

                    $('#input-relation-update').select2()
                    $('#input-relation-update').val(data.emergency_relation)
                    $('#input-relation-update').select2().trigger('change');
                    $('#input-relation-update').select2({
                        width: '100%'
                    });

                    var province_id = $("option:selected", '#input-province-update').data('province-code');
                    city = data.current_address_city;

                    $.ajax({
                        url: BASE_URL+'/city_by_province/'+province_id,
                        type: "GET",
                        dataType: "json",
                        beforeSend: function() {
                            $('#input-city-update').empty();
                        },
                        success: function(data) {
                        $.each(data, function(key, value) {
                            tmp = '';
                            if(city == value.name){
                                tmp = 'selected';
                            }
                            $('#input-city-update').append('<option value="'+ value.name +'" data-city="'+ value.code+'"'+tmp+'>' + value.name + '</option>');
                        });
                        }
                    });

                }
            })
        });

        $('form#update-form').submit(function(e){
            e.preventDefault();
            var loading_text = $('.loading').data('loading-text');
            $('.loading').html(loading_text).attr('disabled', true);
            var form_data = new FormData( this );
            
            $.ajax({
                type: 'post',
                url: BASE_URL+'/employe',
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
                                $('#main-table').DataTable().ajax.reload(null, false);
                                $('#update-modal').modal('hide');
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
            })  
        });
        $(document).on('click', '#delete', function(e){
            event.preventDefault()
            var id = $(this).data("id")
            swal({
                    title: 'Apakah kamu yakin untuk menghapus?',
                    text: "Data ini tidak bisa dikembalikan lagi",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Hapus'
                }, function(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'delete',
                    url: BASE_URL+'/employe/'+id,
                    data: {
                        'id' : id,
                        '_method' : 'DELETE',
                        '_token' : $('meta[name="csrf-token"]').attr('content')
                    },
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
                                title: "sukses",
                                text: msg.message,
                                type:"success",
                                html: true
                            }, function() {
                                $('#main-table').DataTable().ajax.reload(null, false);
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
                })
                })
        });

    </script>
@endsection
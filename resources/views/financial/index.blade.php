@extends('layouts.main')

@section('title', 'Pengajuan Keuangan')

@section('content')
<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title">Data Pengajuan Keuangan</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="index-2.html">Pengajuan Keuangan</a></li>
                <li class="breadcrumb-item active">Pengajuan Keuangan</li>
            </ul>
        </div>
        <div class="col-auto float-right ml-auto">
            <a href="{{route('financial.create')}}" class="btn add-btn"><i class="fa fa-plus"></i> Tambah Pengajuan Keuangan</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 d-flex">
            <div class="card card-table flex-fill">
                <div class="card-header">
                    <div class="card-title mb-0">Data Pengajuan Keuangan</div>
                </div>
                <div class="card-body ml-3 mt-3 mr-3 mb-3">
                    <div class="table-responsive">
                        <table id="main-table" class="table table-striped table-nowrap custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nomor Pengajuan</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Nama Perumahan</th>
                                    <th>Total</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Disetujui Oleh</th>
                                    <th class="text-right" width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('additionalScriptJS')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#main-table').DataTable({
                "pageLength": 10,
                "processing": true,
                "serverSide": true,
                // "searching": false,
                // "ordering": false,
                "ajax":{
                    "url": BASE_URL+"/financial-submission/datatable",
                    "dataType": "json",
                    "type": "POST",
                    "data":function(d) { 
                        d._token = "{{csrf_token()}}"
                    },
                },
                "columns": [
                    {data: 'id', name: 'id', width: '5%', "visible": false},
                    {data: 'number', name:'number', className: 'td-limit'},
                    {data: 'date', name: 'date', className: 'td-limit'},
                    {data: 'cluster', name: 'cluster', className: 'td-limit'},
                    {data: 'total', name: 'total', className: 'td-limit'},
                    {data: 'created_by_user_id', name: 'created_by_user_id', className: 'td-limit'},
                    {data: 'approved_by_user_id', name: 'approved_by_user_id', className: 'td-limit'},
                    {data: 'action', name: 'action', className: 'text-right'},
                ],
            })
        })

        $(document).on('click', '#delete', function(e){
            event.preventDefault();
            
            var id = $(this).data("id");

            swal({
                    title: 'Apakah anda yakin untuk menghapus?',
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
                    url: BASE_URL+'/financial-submission/delete/'+id,
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

        $(document).on('click', '#approve-data', function(e){
            event.preventDefault();
            
            var id = $(this).data("id");

            swal({
                    title: 'Apakah anda yakin untuk menyetujui?',
                    text: 'Aksi tidak bisa diulang kembali',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    cancelButtonText: 'Batal',
                    confirmButtonText: 'Setujui'
                }, function(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: 'post',
                    url: BASE_URL+'/financial-submission/'+id+'/toggle-approval',
                    data: {
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
                });
            });
        });
    </script>
@endsection
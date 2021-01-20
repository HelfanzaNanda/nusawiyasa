@extends('layouts.main')

@section('title', 'Pegawai')

@section('additionalFileCSS')
  <link rel="stylesheet" href="{{ URL::asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
@endsection

@section('content')
<script type="text/javascript">
  var URL = "{{ substr_replace(asset(''), "", -1) }}";
  let employee_status = "active";
</script>
<div id="alert_success" class="alert alert-success alert-dismissible" style="display: none">
  <button type="button" class="close" data-dismiss="alert"></button>
  <span id="message"></span>
</div>
<div class="content-wrapper">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              <div class="border-bottom text-center pb-4">
                <img src="{{ ($employee->avatar) ? asset($employee->avatar) : asset('template/assets/img/user.jpg')}}" alt="profile" class="img-lg rounded-circle mb-3" id="modal-upload-avatar" style="cursor: pointer;" width="100px" height="100px"/>
                <div class="mb-3">
                  <h3>{{ $employee->fullname ? $employee->fullname : '-' }}</h3>
                  <h5 class="mb-0 mr-2 text-muted">{{ $employee->email ? $employee->email : '-' }}</h5>
                  <div class="d-flex align-items-center">
                  </div>
                </div>
                <p class="w-75 mx-auto mb-3">{{ $employee->place_birth ? $employee->place_birth : '-' }}, {{ $employee->date_birth ? $employee->date_birth : '-' }} ({{ $employee['age'] }})</p>
              </div>
              <div class="py-4">
                <div class="row">
                  <div class="col-md-6">
                    <p class="clearfix">
                        <span class="float-left">
                          Status
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->employe_status) ? $employee->employe_status : '-' }}
                        </span>
                    </p>
                    <p class="clearfix">
                      <span class="float-left">
                        Pemilik Rekening
                      </span>
                      <span class="float-right ml-3 text-muted">
                        {{ ($employee->owner_bank_number) ? $employee->owner_bank_number : '-' }}
                      </span>
                  </p>
                    <p class="clearfix">
                        <span class="float-left">
                          Nomor Rekening
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->bank_account) ? $employee->bank_account : '-' }}({{($employee->bank_name) ? $employee->bank_name : '-'}})
                        </span>
                    </p>
                    <p class="clearfix">
                        <span class="float-left">
                          Awal Masuk Kerja
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->joined_at) ? $employee->joined_at : '-' }}
                          ({{ $employee['lama_kerja'] }})
                        </span>
                    </p>
                    <p class="clearfix">
                        <span class="float-left">
                          Keluar Kerja
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->employe_resign_at) ? $employee->resign_at : '-' }}
                        </span>
                    </p>
                    <p class="clearfix">
                        <span class="float-left">
                          Jenis Kelamin
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->gender) ? $employee->gender : '-' }}
                        </span>
                    </p>
                    <p class="clearfix">
                        <span class="float-left">
                          Golongan Darah
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->blood_type) ? $employee->blood_type : '-' }}
                        </span>
                    </p>
                    <p class="clearfix">
                        <span class="float-left">
                          Agama
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->religion) ? $employee->religion : '-' }}
                        </span>
                    </p>
                    <p class="clearfix">
                        <span class="float-left">
                          Status Penikahan
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->mariage_status) ? $employee->mariage_status : '-' }}
                        </span>
                    </p>
                    <p class="clearfix">
                        <span class="float-left">
                          Nomor Tanda Pengenal
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->identity_card_number) ? $employee->identity_card_number : '-' }}({{ ($employee->identity_type) ? $employee->identity_type : '-' }})
                        </span>
                    </p>
                    <p class="clearfix">
                        <span class="float-left">
                          Twitter
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->twitter) ? $employee->twitter : '-' }}
                        </span>
                    </p>
                    <p class="clearfix">
                        <span class="float-left">
                          Facebook
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->facebook) ? $employee->facebook : '-' }}
                        </span>
                    </p>
                    <p class="clearfix">
                        <span class="float-left">
                          Instagram
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->instagram) ? $employee->instagram : '-' }}
                        </span>
                    </p>
                    <p class="clearfix">
                        <span class="float-left">
                          LinkedIn
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->linkedin) ? $employee->linkedin : '-' }}
                        </span>
                    </p>
                    <p class="clearfix">
                        <span class="float-left">
                          Youtube
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->youtube) ? $employee->youtube : '-' }}
                        </span>
                    </p>      
                  </div>
                  <div class="col-md-6">
                    <p class="clearfix">
                      <span class="float-left">
                        Alamat Asal
                      </span>
                      <span class="float-right ml-3 text-muted">
                        {{ ($employee->current_address_street) ? $employee->current_address_street : '' }},
                         RT. {{ ($employee->current_address_rt) ? $employee->current_address_rt : '000' }},
                         RT. {{ ($employee->current_address_rw) ? $employee->current_address_rw : '000' }},
                         {{ ($employee->current_address_kelurahan) ? 'Kel. '.$employee->current_address_kelurahan.', ' : '' }}
                         {{ ($employee->current_address_kecamatan) ? 'Kec. '.$employee->current_address_kecamatan.', ' : '' }}
                         {{ ($employee->current_address_city) ? $employee->current_address_city.', ' : '' }}
                         {{ ($employee->current_address_province) ? 'Provinsi '.$employee->current_address_province.' ' : '' }}
                      </span>
                    </p> 
                    <p class="clearfix">
                        <span class="float-left">
                          No. HP
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->phone_number) ? $employee->phone_number : '-' }}
                        </span>
                    </p>
                    <p class="clearfix">
                        <span class="float-left">
                          Nama Ayah
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->father_name) ? $employee->father_name : '-' }}
                        </span>
                    </p>
                    <p class="clearfix">
                        <span class="float-left">
                          Nama Ibu
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->mother_name) ? $employee->mother_name : '-' }}
                        </span>
                    </p>
                    <p class="clearfix">
                        <span class="float-left">
                          Nomor Kontak Darurat
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->emergency_number) ? $employee->emergency_number : '-' }}
                        </span>
                    </p>
                    <p class="clearfix">
                        <span class="float-left">
                          Nama Kontak Darurat
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->emergency_name) ? $employee->emergency_name : '-' }}
                        </span>
                    </p>
                    <p class="clearfix">
                        <span class="float-left">
                          Status Hubungan Kontak Darurat
                        </span>
                        <span class="float-right ml-3 text-muted">
                          {{ ($employee->emergency_relation) ? $employee->emergency_relation : '-' }}
                        </span>
                    </p>
                  </div>
                </div>

                <hr>             
              </div>
              <button class="btn btn-info btn-block mb-2" id="edit" data-id="{{ $employee->id}}">Edit</button>
              <a href="{{ route('employe.pdf', $employee->id) }}" target="_blank"><button class="btn btn-success btn-block mb-2"><i class="fa fa-print"></i> Print</button></a>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="mt-4 py-2 border-top border-bottom">
                <ul class="nav profile-navbar">
                  <li class="nav-item">
                    <a class="nav-link active" href="javascript:void(0);" id="education-tab">
                      <i class="ti-receipt"></i>
                      Pendidikan
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0);" id="media-tab">
                      <i class="ti-file"></i>
                      Media
                    </a>
                  </li>
                </ul>
              </div>
              <div class="profile-feed" id="education-detail">
                @foreach($employee->educations as $education)
                <div class="d-flex align-items-start profile-feed-item">
                  <div class="ml-4 mr-4" style="width: 100%;">
                    <h6>
                      {{$education->school}} ({{$education->major}})
                    </h6>
                    <p>
                      Tahun Lulus : {{$education->graduation_year}}
                    </p>
                    <p class="small text-muted mt-2 mb-0 pull-right">
                      <span>
                        <button type="button" class="btn btn-info btn-sm" id="edit-education" data-id="{{$education->id}}" data-toggle="tooltip" data-placement="top" title="Ubah"><i class="fa fa-pencil"></i></button>
                        <button type="button" class="btn btn-warning btn-sm" data-id="{{$education->id}}" id="delete-education" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></button>
                      </span>
                    </p>
                  </div>
                </div>

                <hr>
                @endforeach
                <div class="d-flex align-items-start profile-feed-item" style="border-bottom: 0px !important;">
                  <div class="ml-4 mr-4" style="width: 100%;">
                    <button type="button" class="btn btn-primary btn-block" id="add-education" data-toggle="tooltip" data-placement="top" title="Tambah"><i class="fa fa-plus"></i></button>
                  </div>
                </div>
              </div>
              <div class="profile-feed" id="media-detail">
                <br>
                @foreach($employee->medias as $row)
                <div class="d-flex align-items-start profile-feed-item">
                  <div class="ml-4 mr-4 row" style="width: 100%;">
                    <div class="col-sm-6">
                      <h6>
                        <a href="{{ URL::asset($row->filepath) }}" download="{{$employee->fullname}}-{{$row->filename}}.{{$row->type}}">{{$row->filename}}.{{$row->type}}</a>
                      </h6>
                      <p>
                        Nama File : {{$row->filename}}
                      </p>
                      <p>
                        Ekstensi : {{$row->type}}
                      </p>
                    </div>
                    <div class="col-sm-6">
                      <p class="small text-muted mt-2 mb-0 pull-right">
                        <span>
                          <button type="button" class="btn btn-warning btn-sm" data-id="{{$row->id}}" id="delete-media" data-toggle="tooltip" data-placement="top" title="Hapus"><i class="fa fa-trash"></i></button>
                        </span>
                      </p>
                    </div>
                  </div>
                </div>
                <hr>
                @endforeach
                <div class="d-flex align-items-start profile-feed-item" style="border-bottom: 0px !important;">
                  <div class="ml-4 mr-4" style="width: 100%;">
                    <button type="button" class="btn btn-primary btn-block" id="add-media" data-toggle="tooltip" data-placement="top" title="Tambah"><i class="fa fa-plus"></i></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="mediaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title">Loading...</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="media-form" method="POST" action="{{asset('')}}employe/media" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <div class="col-md-12">
                 <label for="first_name">Nama File :</label>
                 <input type="text" class="form-control" name="filename" placeholder="Nama File.." style="width: 100% !important; margin-bottom: 20px;">
                 <input type="hidden" name="employe_id" value="{{ $employee->id }}">
                 <label for="file">File :</label>
                  <input type="file" class="form-control" name="file" style="width: 100% !important;">
              </div>
            </div>
          </div>

        </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="submit">Submit</button>
            <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
          </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="mediaEditModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title2">Loading...</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="media-edit-form" method="POST">
        {{ csrf_field() }}
        <div class="modal-body">
          <div class="form-group">
            <div class="row">
              <div class="col-md-12">
                <input type="hidden" id="input-id-media">
                 <label for="first_name">Nama File :</label>
                 <input type="text" class="form-control" name="filename2" placeholder="Nama File.." style="width: 100% !important; margin-bottom: 20px;">
                 <label for="file">File :</label>
                  <input type="file" class="form-control" name="file2" style="width: 100% !important;">
              </div>
            </div>
          </div>

        </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="submit">Submit</button>
            <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
          </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="input-add-education" style="overflow-y:auto;" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-2" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="title">Loading...</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="input-form-education" method="POST" action="">
        <div class="modal-body">
          <input type="hidden" class="form-control" id="input-employee-id" value="{{$employee->id}}">
          <input type="hidden" class="form-control" id="input-education-id">
          <div class="form-group">
            <label for="grade">Pendidikan:</label>
            <input type="text" class="form-control" id="input-grade">
          </div>
          <div class="form-group">
            <label for="grade">Jurusan:</label>
            <input type="text" class="form-control" id="input-major">
          </div>
          <div class="form-group">
            <label for="school">Nama Sekolah:</label>
            <input type="text" class="form-control" id="input-school">
          </div>
          <div class="form-group">
            <label for="graduation_year">Tahun kelulusan:</label>
            <input type="text" class="form-control" id="input-graduation_year">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success" id="submit">Submit</button>
          <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>

@include('employe.modal.update', ['provinces' => $provinces])

@endsection

@section('additionalScriptJS')
<script>
@if (session()->has('success'))
  
  swal( "Sukses", "{{session('success')}}", "success" );
  
@endif
</script>
<script type="text/javascript">
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  $('#media-detail').hide();

  $(document).on('click', '#education-tab', function() {
    $("#media-tab").removeClass("active");
    $(this).addClass("active");

    $('#education-detail').show();
    $("#media-detail").hide();
  });

  $('#input-identity-update').select2({
      width: '100%'
  });

  $(document).on('click', '#media-tab', function() {
    $("#education-tab").removeClass("active");
    $(this).addClass("active");

    $('#education-detail').hide();
    $("#media-detail").show();
  });


  $(document).on('click', '#modal-upload-avatar', function() {
    $('h5#title').text('Upload Foto');
    $('#input-add-avatar').modal('show');
  });

 
  $(document).on('click', '#add-education', function() {
      $('h5#title').text('Tambah pendidikan');
      $("#input-id").val('');
      $("#input-grade").val('');
      $("#input-school").val('');
      $("#input-graduation_year").val('');
      $('#input-add-education').modal('show');
      $('#modal-education-datatable').modal('hide');
  });

  $(document).on('click', '#add-media', function() {
      $('h5#title').text('Tambah Media');
      $("#filename").val('');
      $('#file').val('');
      $('#mediaModal').modal('show');
  });

  $('#province2').on('change', function() {
    var province_id = $("option:selected", this).data('provinsi2');
    
    if(province_id) {
      $.ajax({
        url: URL+'/master/city_by_province/'+province_id,
        type: "GET",
        dataType: "json",
        beforeSend: function() {
            // $('#loader').css("visibility", "visible");
            $('#city2').empty();
            $('#city2').append('<option value="" data-city2="0"> - Silahkan Pilih - </option>');
        },
        success: function(data) {
          $.each(data, function(key, value) {
              $('#city2').append('<option value="'+ value.name +'" data-city2="'+ value.code+'">' + value.name + '</option>');
          });
        }
      });
    } else {
        // $('#city2').empty();
    }
  });

  //ADD
  $( 'form#input-form-education' ).submit( function( e ) {
    e.preventDefault();

    $.ajax({
      type: 'post',
      url: URL+'/employe/education',
      data: {
        "_token": "{{ csrf_token() }}",
        "id": $("#input-education-id").val(),
        "employe_id": $("#input-employee-id").val(),
        "grade": $("#input-grade").val(),
        "school": $("#input-school").val(),
        "major": $("#input-major").val(),
        "graduation_year": $("#input-graduation_year").val(),
      },
      beforeSend: function() {
        // $('.loading-area').show();
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
                    $('#input-add-education').modal('hide');
                    window.location.reload();
                    // window.location.replace(URL_LIST_PURCHASES);
                });
            }, 500);
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
  
  //ADD
  $( 'form#input-form-salary' ).submit( function( e ) {
    e.preventDefault();

    $.ajax({
      type: 'post',
      url: URL+'/employee/salaries',
      data: {
        "_token": "{{ csrf_token() }}",
        "code": $("#input-salary-id").val(),
        "employee_code": $("#input-employee-id").val(),
        "currency_code": $("#input-salary-currency").val(),
        "amount": $("#input-salary-amount").val(),
        "tmt_salary": $("#input-tmt_salary").val(),
        "tat_salary": $("#input-tat_salary").val(),
      },
      beforeSend: function() {
        // $('.loading-area').show();
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
                    $('#input-add-salary').modal('hide');
                    window.location.reload();
                    // window.location.replace(URL_LIST_PURCHASES);
                });
            }, 500);
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

  //ADD
  $( 'form#input-form-grade' ).submit( function( e ) {
    e.preventDefault();

    $.ajax({
      type: 'post',
      url: URL+'/employee/grade_employees',
      data: {
        "_token": "{{ csrf_token() }}",
        "code": $("#input-grade-id").val(),
        "employee_code": $("#input-employee-id").val(),
        "grade_code": $("#input-employee-grade").val(),
        "tmt_grade": $("#input-tmt-grade").val(),
        "tat_grade": $("#input-tat-grade").val(),
      },
      beforeSend: function() {
        // $('.loading-area').show();
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
                    $('#input-add-grade').modal('hide');
                    window.location.reload();
                    // window.location.replace(URL_LIST_PURCHASES);
                });
            }, 500);
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
      $('#input-status-update').select2({
            width: '100%'
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
                    $('#nik').val(data.nik)
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
                    $('#owner_bank_number').val(data.owner_bank_number)
                    $('#identity_card_number').val(data.identity_card_number)
                    $('#father_name').val(data.father_name)
                    $('#mother_name').val(data.mother_name)
                    $('#emergency_name').val(data.emergency_name)
                    $('#emergency_number').val(data.emergency_number)
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
                    if(msg.status == 'success'){
                        setTimeout(function() {
                            swal({
                                title: "Sukses",
                                text: msg.message,
                                type:"success",
                                html: true
                            }, function() {
                                $('#update-modal').modal('hide');
                                window.location.reload();
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

  //ADD
  $( 'form#input-form-position' ).submit( function( e ) {
    e.preventDefault();

    $.ajax({
      type: 'post',
      url: URL+'/employee/position_employees',
      data: {
        "_token": "{{ csrf_token() }}",
        "code": $("#input-position-id").val(),
        "employee_code": $("#input-employee-id").val(),
        "position_code": $("#input-position").val(),
        "currency_code": $("#input-position-currency").val(),
        "amount": $("#input-position-amount").val(),
        "description": $("#input-description").val(),
        "tmt_position": $("#input-tmt-position").val(),
        "tat_position": $("#input-tat-position").val(),
      },
      beforeSend: function() {
        // $('.loading-area').show();
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
                    $('#input-add-position').modal('hide');
                    window.location.reload();
                    // window.location.replace(URL_LIST_PURCHASES);
                });
            }, 500);
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

  $("form#media-edit-form").submit(function(e){
    e.preventDefault()
    $("#createModal").modal("hide")

    if (window.FormData){
        formData = new FormData()

        formData.append('filename',$("input[name=filename2]").val())

        formData.append('file',$("input[name=file2]")[0].files[0])
        
        formData.append('_method','PUT')
    }

    $(() => {
      $.ajax({
        url: '/employee/update_media/'+$("#input-id-media").val(),
        type: 'POST',
        cache       : false,
        contentType : false,
        processData : false,
        dataType: 'json',
        data: formData,
        success: (msg) => {
        let $el = $("input[name=filename2]");
        $el.wrap('<form>').closest('form').get(0).reset();
        $el.unwrap();
        $("input[name=filename2]").val('')
        
        setTimeout(function() {
            swal({
                title: "Sukses",
                text: msg.message,
                type:"success",
                html: true
            }, function() {
                window.location.reload()
            });
        }, 500); 
        }

      })
    })  
  }) 
  
  $(document).on('click', '#active_employee', function() {
    employee_status = 'active';
    $('#employee-listing').DataTable().ajax.reload(null, false);
    $(this).removeClass("btn-secondary");
    $("#resign_employee").removeClass("btn-warning");
    $("#resign_employee").addClass("btn-secondary");
    $(this).addClass("btn-warning");
  });

  $(document).on('click', '#resign_employee', function() {
    employee_status = 'resign';
    $('#employee-listing').DataTable().ajax.reload(null, false);
    $(this).removeClass("btn-secondary");
    $("#active_employee").removeClass("btn-warning");
    $("#active_employee").addClass("btn-secondary");
    $(this).addClass("btn-warning");
  });
  

  $(document).on('click', '#edit-education', function() {
    var id = $(this).attr('data-id');

    $.ajax({
      url: URL+'/employe/education/'+id,
      type: 'GET',
      dataType: 'JSON',
      success: function(data, textStatus, jqXHR) {
        console.log(data);
        $('h5#title').text('Edit pendidikan');
        $("#input-education-id").val(data.id);
        $("#input-grade").val(data.grade);
        $("#input-school").val(data.school);
        $("#input-major").val(data.major);
        $("#input-graduation_year").val(data.graduation_year);
        $('#input-add-education').modal('show');
      }
    });
  });

  $(document).on('click', '#edit-media', function() {
    var id = $(this).attr('data-id');

    $.ajax({
      url: URL+'/employee/edit_media/'+id,
      type: 'GET',
      dataType: 'JSON',
      success: function(data, textStatus, jqXHR) {
        console.log(data);
        $('h5#title2').text('Edit Media');
        $("#input-id-media").val(data.id);

        $("#mediaEditModal").modal("show");
      }
    });
  });


  $(document).on('click', '#delete-employee', function(e) {
    e.preventDefault();
    var id = $(this).attr('data-id');

    swal({
      title: "Apakah Anda Yakin?",
      text: "Apakah Anda Yakin Menghapus Data Ini?",
      type: "warning",
      showConfirmButton: true,
      showCancelButton: true,
      closeOnConfirm: false,
      showLoaderOnConfirm: true,
      confirmButtonText: "Ya!",
      cancelButtonText: "Tidak",
      confirmButtonColor: "#ec6c62"
    }, function() {
      $.ajax({
        url: URL+'/employee/employee/'+id,
        type: 'DELETE',
        data: {
          "_token": "{{csrf_token()}}"
        },
      })
      .done( function( data ) {
        swal( "Dihapus!", "Data Telah Berhasil Dihapus!", "success" );
        $('#grade-listing').DataTable().ajax.reload(null, false);
      })
      .fail( function( data ) {
        swal( "Oops", "We couldn't connect to the server!", "error" );
      });
    });
  });

  
 
$(document).on('click', '#delete-education', function(e) {
  var id = $(this).attr('data-id');

  swal({
    title: "Apakah Anda Yakin?",
    text: "Apakah Anda Yakin Menghapus Data Ini?",
    type: "warning",
    showConfirmButton: true,
    showCancelButton: true,
    closeOnConfirm: false,
    showLoaderOnConfirm: true,
    confirmButtonText: "Ya!",
    cancelButtonText: "Tidak",
    confirmButtonColor: "#ec6c62"
  }, function() {
    $.ajax({
      url: URL+ `/employe/education/`+id,
      type: 'DELETE',
      dataType: 'json',
      data: {
        _token: "{{ csrf_token() }}",
      },
      success: (msg) => {
        setTimeout(function() {
            swal({
                title: "Sukses",
                text: msg.message,
                type:"success",
                html: true
            }, function() {
                swal( "Dihapus!", "Data Telah Berhasil Dihapus!", "success" );
                window.location.reload();
            });
        }, 500);
        // window.location.reload();
      }
    })
  });
});
$(document).on('click', '#delete-grade', function(e) {
  var id = $(this).attr('data-id');

  swal({
    title: "Apakah Anda Yakin?",
    text: "Apakah Anda Yakin Menghapus Data Ini?",
    type: "warning",
    showConfirmButton: true,
    showCancelButton: true,
    closeOnConfirm: false,
    showLoaderOnConfirm: true,
    confirmButtonText: "Ya!",
    cancelButtonText: "Tidak",
    confirmButtonColor: "#ec6c62"
  }, function() {
    $.ajax({
      url: URL+ `/employee/detail_grade/`+id,
      type: 'DELETE',
      dataType: 'json',
      data: {
        _token: "{{ csrf_token() }}",
      },
      success: (msg) => {
        setTimeout(function() {
            swal({
                title: "Sukses",
                text: msg.message,
                type:"success",
                html: true
            }, function() {
                swal( "Dihapus!", "Data Telah Berhasil Dihapus!", "success" );
                window.location.reload();
            });
        }, 500);
        // window.location.reload();
      }
    })
  });
});
$(document).on('click', '#delete-position', function(e) {
  var id = $(this).attr('data-id');

  swal({
    title: "Apakah Anda Yakin?",
    text: "Apakah Anda Yakin Menghapus Data Ini?",
    type: "warning",
    showConfirmButton: true,
    showCancelButton: true,
    closeOnConfirm: false,
    showLoaderOnConfirm: true,
    confirmButtonText: "Ya!",
    cancelButtonText: "Tidak",
    confirmButtonColor: "#ec6c62"
  }, function() {
    $.ajax({
      url: URL+ `/employee/detail_position/`+id,
      type: 'DELETE',
      dataType: 'json',
      data: {
        _token: "{{ csrf_token() }}",
      },
      success: (msg) => {
        setTimeout(function() {
            swal({
                title: "Sukses",
                text: msg.message,
                type:"success",
                html: true
            }, function() {
                swal( "Dihapus!", "Data Telah Berhasil Dihapus!", "success" );
                window.location.reload();
            });
        }, 500);
        // window.location.reload();
      }
    })
  });
});
$(document).on('click', '#delete-media', function(e) {
  var id = $(this).attr('data-id');

  swal({
    title: "Apakah Anda Yakin?",
    text: "Apakah Anda Yakin Menghapus Data Ini?",
    type: "warning",
    showConfirmButton: true,
    showCancelButton: true,
    closeOnConfirm: false,
    showLoaderOnConfirm: true,
    confirmButtonText: "Ya!",
    cancelButtonText: "Tidak",
    confirmButtonColor: "#ec6c62"
  }, function() {
    $.ajax({
      url: URL+ `/employe/media/`+id,
      type: 'DELETE',
      dataType: 'json',
      data: {
        _token: "{{ csrf_token() }}",
      },
      success: (msg) => {
        setTimeout(function() {
            swal({
                title: "Sukses",
                text: msg.message,
                type:"success",
                html: true
            }, function() {
                swal( "Dihapus!", "Data Telah Berhasil Dihapus!", "success" );
                window.location.reload();
            });
        }, 500);
        // window.location.reload();
      }
    })
  });
});
</script>
@endsection
@extends('layouts.main')

@section('title', 'Progress Pembangunan')

@section('content')
<!-- Page Header -->
<div class="page-header">
  <div class="row align-items-center">
    <div class="col">
      <h3 class="page-title">Detail Progress Pembangunan</h3>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="index-2.html">Progress Pembangunan</a></li>
        <li class="breadcrumb-item active">Detail Progress Pembangunan</li>
      </ul>
    </div>
{{--     <div class="col-auto float-right ml-auto">
      <a href="#" class="btn add-btn" id="show-add-modal"><i class="fa fa-plus"></i> Tambah Progress Pembangunan</a>
    </div> --}}
  </div>
</div>
<!-- /Page Header -->

<div class="row">
  <div class="col-md-12 d-flex">
    <div class="card card-table flex-fill">
      <div class="card-header">
        <h3 class="card-title mb-0">Detail Progress Pembangunan a/n {{$data['customer_name']}} Tanggal {{$data['date']}}</h3>
      </div>
      <div class="card-body ml-3 mt-3 mr-3 mb-3">
        <h4 class="text-primary">Dokumentasi</h4>
        <div class="row"> 
          @foreach($files as $file)
            <div class="col-md-6">
              <img src="{{env('APP_URL').$file['filepath'].'/'.$file['filename']}}" width="350px">
            </div>
          @endforeach
        </div>
        <hr/>
        <h4 class="text-primary">Pekerjaan</h4>
        <div class="row"> 
          <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0">
              <thead>
                <tr>
                  <th width="40%">Pekerjaan Yang Dilaksanakan</th>
                  <th>Lokasi</th>
                  <th>Volume</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody>
                @foreach($jobs as $job)
                <tr>
                  <td>{{$job['jobs']}}</td>
                  <td>{{$job['location']}}</td>
                  <td>{{$job['volume']}}</td>
                  <td>{{$job['note']}}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="mt-5"></div>
        <h4 class="text-primary">Bahan</h4>
        <div class="row"> 
          <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0">
              <thead>
                <tr>
                  <th width="40%">Bahan Yang Digunakan</th>
                  <th>Jumlah</th>
                  <th>Unit</th>
                </tr>
              </thead>
              <tbody>
                @foreach($materials as $material)
                @if($material['type'] == 'materials')
                <tr>
                  <td>{{$material['inventory_name']}}</td>
                  <td>{{$material['qty']}}</td>
                  <td>{{$material['inventory_unit']}}</td>
                </tr>
                @endif
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="mt-5"></div>
        <h4 class="text-primary">Alat</h4>
        <div class="row"> 
          <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0">
              <thead>
                <tr>
                  <th width="40%">Alat Yang Digunakan</th>
                  <th>Jumlah</th>
                  <th>Unit</th>
                </tr>
              </thead>
              <tbody>
                @foreach($materials as $tool)
                @if($tool['type'] == 'tools')
                <tr>
                  <td>{{$tool['inventory_name']}}</td>
                  <td>{{$tool['qty']}}</td>
                  <td>{{$tool['inventory_unit']}}</td>
                </tr>
                @endif
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="mt-5"></div>
        <h4 class="text-primary">Tenaga Kerja</h4>
        <div class="row"> 
          <div class="table-responsive">
            <table class="table table-hover table-bordered mb-0">
              <thead>
                <tr>
                  <th width="40%">Tenaga Kerja Yang Digunakan</th>
                  <th>Jumlah</th>
                  <th>Unit</th>
                </tr>
              </thead>
              <tbody>
                @foreach($materials as $service)
                @if($service['type'] == 'service')
                <tr>
                  <td>{{$service['inventory_name']}}</td>
                  <td>{{$service['qty']}}</td>
                  <td>{{$service['inventory_unit']}}</td>
                </tr>
                @endif
                @endforeach
              </tbody>
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

  $('#input-province').select2({
    width: '100%'
  });

  $('#input-city').select2({
    width: '100%'
  });
</script>
@endsection
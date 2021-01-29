@extends('layouts.main')

@section('title', 'SLF Template')

@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">SLF Template</h4>
        <ul class="nav nav-tabs nav-tabs-solid">
          @foreach($tabs as $key => $tab)
            <li class="nav-item">
                <a class="nav-link {{$key < 1 ? 'active' : ''}}" id="tab" href="#solid-tab" data-toggle="tab">{{ $tab }}</a>
            </li>
          @endforeach
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="solid-tab">
                <div class="col-md-12">
                    <form action="{{ url('/slf-template-store') }}" method="POST">
                        @csrf
                        <input type="hidden" class="form-control" id="name" name="name" value="BAB I">
                        <div class="form-group">
                            <label>Konten</label>
                            <textarea id="content" name="content"></textarea>
                        </div>
                        <button class="btn btn-primary float-right" type="submit">submit</button>
                    </form>
                </div>
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
        $('#content').summernote({
            height: 200
        });

        $('a#tab').on('click', function(){
            $('#name').val(this.text);
        })

        var sessionSuccess = "{{ Session::get('success') }}"
        if (sessionSuccess) {
            swal({
                title: "sukses",
                text: sessionSuccess,
                type:"success",
            });
        }
    })
</script>
@endsection
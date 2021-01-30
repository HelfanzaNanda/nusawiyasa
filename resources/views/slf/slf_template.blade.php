@extends('layouts.main')

@section('title', 'SLF Template')

@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">SLF Template</h4>
        <ul class="nav nav-tabs nav-tabs-solid">
          @foreach($slfTemplates as $keyHeader => $template)
            <li class="nav-item">
                <a class="nav-link {{$keyHeader < 1 ? 'active' : ''}}" id="tab" href="#solid-tab{{$keyHeader}}" data-toggle="tab">{{ $template[1] }}</a>
            </li>
          @endforeach
        </ul>
        <div class="tab-content">
          @foreach ($slfTemplates as $keyContent => $template)
          <div class="tab-pane {{$keyContent < 1 ? 'show active' : ''}}" id="solid-tab{{$keyContent}}">
            <div class="col-md-12">
                <form action="{{ url('/slf-template-store') }}" method="POST">
                    @csrf
                    <input type="hidden" class="form-control" id="name" name="name" value="{{ $template[1] }}">
                    <div class="form-group">
                        <label>Konten</label>
                        <textarea id="content" class="summernote" name="content">{!! $template[0]['template_text'] ?? '' !!}</textarea>
                    </div>
                    <button class="btn btn-primary float-right" type="submit">submit</button>
                </form>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('additionalScriptJS')
<script type="text/javascript">
    $(document).ready(function(){
        $('.summernote').summernote({
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
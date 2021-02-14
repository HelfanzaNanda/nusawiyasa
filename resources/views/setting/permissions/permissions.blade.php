@extends('layouts.main')

@section('title', 'User Permissions')

@section('content')
<style type="text/css">
  table {
      display: block;
      overflow-x: auto;
      white-space: nowrap;
  }

  table tbody {
      display: table;
      width: 100%;
  }
</style>
<div class="row horizontal-scroll">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body" style="overflow-x:auto;">
        <h4 class="card-title">User Permissions</h4>
        <table class="table table-light">
          <thead>
            <tr>
              <th>Permission Name</th>
              <th>Guard Name</th>
              @foreach ($roles as $role)
                <th>{{ $role->name }}</th>    
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach ($permissions as $key => $permission)
            <tr class="table-secondary">
              {{-- <td colspan="8"><strong>{{ __('permissions.'.$key) }}</strong></td> --}}
              <td colspan="{{ $roles->count() + 3 }}"><strong>{{ $key }}</strong></td>
              @foreach ($permission as $item)
              <tr>
                <td>{{ __('permissions.'.$item['name']) }}</td>
                <td>{{ $item['guard_name'] }}</td>
                @foreach ($roles as $role)
                  <td>
                    <div class="form-check">
                      <input class="form-check-input checkbox" data-perm="{{ $item['name'] }}" data-role="{{ $role->name }}" 
                      type="checkbox" {{ collect($item['roles'])->contains($role->name) ? 'checked' : '' }}>
                    </div>
                  </td>
                @endforeach
              </tr>
              @endforeach
            </tr>
            @endforeach
          </tbody>
        </table>
        {{ $permissions->links() }}
      </div>
    </div>
  </div>
</div>


@endsection

@section('additionalScriptJS')
<script type="text/javascript">
    $(document).ready(function(){
      $('.checkbox').on('click', function(){
        const perm = $(this).data('perm');
        const role = $(this).data('role');
        const bool = $(this).is(':checked');
        const data = { 'perm': perm, 'role': role, 'bool': bool };
        $.ajax({
            type: 'POST',
            url: BASE_URL+'/user-permissions-update',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: data,
            success: function(res) {
                if (res.status) {
                  location.reload()
                }
            }
          })
      });
        
    });
</script>
@endsection
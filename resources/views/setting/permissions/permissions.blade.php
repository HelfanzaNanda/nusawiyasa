@extends('layouts.main')

@section('title', 'User Permissions')

@section('content')

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">User Permissions</h4>
        <table class="table table-light">
          <thead>
            <tr>
              <th>Permission Name</th>
              <th>Guard Name</th>
              <th>Super Admin</th>
              <th>Marketing</th>
              <th>Project</th>
              <th>WareHouse</th>
              <th>Purchasing</th>
              <th>Customer</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($permissions as $key => $permission)
            <tr class="table-secondary">
              {{-- <td colspan="8"><strong>{{ __('permissions.'.$key) }}</strong></td> --}}
              <td colspan="8"><strong>{{ $key }}</strong></td>
              @foreach ($permission as $item)
              <tr>
                <td>{{ __('permissions.'.$item['name']) }}</td>
                <td>{{ $item['guard_name'] }}</td>
                <td>
                  <div class="form-check">
                    <input class="form-check-input checkbox" data-perm="{{ $item['name'] }}" data-role="SuperAdmin" type="checkbox" {{ collect($item['roles'])->contains('SuperAdmin') ? 'checked' : '' }}>
                  </div>
                </td>
                <td>
                  <div class="form-check">
                    <input class="form-check-input checkbox" data-perm="{{ $item['name'] }}" data-role="Marketing" type="checkbox" {{ collect($item['roles'])->contains('Marketing') ? 'checked' : '' }}>
                  </div>
                </td>
                <td>
                  <div class="form-check">
                    <input class="form-check-input checkbox" data-perm="{{ $item['name'] }}" data-role="Project" type="checkbox" {{ collect($item['roles'])->contains('Project') ? 'checked' : '' }}>
                  </div>
                </td>
                <td>
                  <div class="form-check">
                    <input class="form-check-input checkbox" data-perm="{{ $item['name'] }}" data-role="WareHouse" type="checkbox" {{ collect($item['roles'])->contains('WareHouse') ? 'checked' : '' }}>
                  </div>
                </td>
                <td>
                  <div class="form-check">
                    <input class="form-check-input checkbox" data-perm="{{ $item['name'] }}" data-role="Purchashing" type="checkbox" {{ collect($item['roles'])->contains('Purchashing') ? 'checked' : '' }}>
                  </div>
                </td>
                <td>
                  <div class="form-check">
                    <input class="form-check-input checkbox" data-perm="{{ $item['name'] }}" data-role="Customer" type="checkbox" {{ collect($item['roles'])->contains('Customer') ? 'checked' : '' }}>
                  </div>
                </td>
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
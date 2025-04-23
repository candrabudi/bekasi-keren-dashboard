<tr>
    <td class="text-gray-800 {{ $level > 0 ? 'ps-' . ($level * 5) : '' }}">
        {!! str_repeat('↳ ', $level) !!}{{ $menu->name }}
    </td>
    <td>
        <div class="d-flex flex-wrap">
            @foreach ($permissions as $permission)
                @php
                    $hasPermission = DB::table('menu_permission_role')
                        ->where('role_id', $role->id)
                        ->where('menu_id', $menu->id)
                        ->where('permission_id', $permission->id)
                        ->exists();
                @endphp
                <label class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                    <input type="checkbox"
                        class="form-check-input"
                        name="permissions[{{ $menu->id }}][]"
                        value="{{ $permission->id }}"
                        {{ $hasPermission ? 'checked' : '' }}>
                    <span class="form-check-label">
                        {{ ucfirst($permission->name) }}
                    </span>
                </label>
            @endforeach
        </div>
    </td>
</tr>

@if ($menu->children)
    @foreach ($menu->children as $child)
        @include('roles.partials.permission_row_edit', [
            'menu' => $child,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
            'level' => $level + 1,
        ])
    @endforeach
@endif

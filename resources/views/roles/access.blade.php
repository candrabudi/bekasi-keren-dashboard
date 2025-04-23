<h2>Manage Access for Role: {{ $role->name }}</h2>
<a href="{{ route('roles.index') }}">← Back to Roles</a>

<form method="POST" action="{{ route('roles.access.update', $role) }}">
    @csrf

    <table border="1" cellpadding="6">
        <thead>
            <tr>
                <th>Menu</th>
                @foreach ($permissions as $perm)
                    <th>{{ ucfirst($perm->name) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($menus as $menu)
                <tr>
                    <td><strong>{{ $menu->name }}</strong></td>
                    @foreach ($permissions as $perm)
                        <td>
                            <input type="checkbox" name="access[{{ $menu->id }}][]" value="{{ $perm->id }}"
                                {{ in_array($perm->id, $access[$menu->id] ?? []) ? 'checked' : '' }}>
                        </td>
                    @endforeach
                </tr>

                @foreach ($menu->children as $child)
                    <tr>
                        <td style="padding-left: 30px;">↳ {{ $child->name }}</td>
                        @foreach ($permissions as $perm)
                            <td>
                                <input type="checkbox" name="access[{{ $child->id }}][]" value="{{ $perm->id }}"
                                    {{ in_array($perm->id, $access[$child->id] ?? []) ? 'checked' : '' }}>
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <br>
    <button type="submit">💾 Save Access</button>
</form>

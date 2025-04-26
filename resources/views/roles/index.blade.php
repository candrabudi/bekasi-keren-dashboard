@extends('layouts.app')
@section('title', 'Data Role')
@section('page-title', 'Data Role')
@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-1">
        <li class="breadcrumb-item text-white opacity-75">
            <a href="" class="text-white text-hover-primary">
                Home
            </a>
        </li>
        <li class="breadcrumb-item">
            <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
        </li>
        <li class="breadcrumb-item text-white opacity-75">
            User Managements
        </li>
        <li class="breadcrumb-item">
            <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
        </li>
        <li class="breadcrumb-item text-white opacity-75">
            Role
        </li>
    </ul>
@endsection
@section('content')
    <div class="card card-flush ">
        <div class="card-header mt-6">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative my-1 me-5">
                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    <input type="text" data-kt-permissions-table-filter="search"
                        class="form-control form-control-solid w-250px ps-13" placeholder="Search Permissions">
                </div>
            </div>
            <div class="card-toolbar">
                <a href="{{ route('roles.create') }}" class="btn btn-light-primary">
                    <i class="ki-duotone ki-plus-square fs-3">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i> Add Role
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0" id="kt_permissions_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-125px">Name</th>
                            <th class="min-w-250px">Accessible Menus</th>
                            <th class="min-w-125px">Created Date</th>
                            <th class="text-end min-w-100px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>
                                    @php
                                        // Group by menu_id
                                        $grouped = $role->menuPermissions->groupBy('menu_id');
                                    @endphp

                                    @foreach ($grouped as $menuId => $items)
                                        @php
                                            $menuName = $items->first()->menu->name ?? '-';
                                            $permissions = $items->pluck('permission.name');
                                        @endphp
                                        <div class="mb-2">
                                            <strong>{{ $menuName }}</strong>:
                                            @foreach ($permissions as $perm)
                                                <span class="badge badge-light-info fs-8">{{ $perm }}</span>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </td>
                                <td>{{ $role->created_at->format('d M Y, h:i a') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('roles.edit', $role->id) }}"
                                        class="btn btn-icon btn-active-light-primary w-30px h-30px me-3">
                                        <i class="ki-duotone ki-setting-3 fs-3">
                                            <span class="path1"></span><span class="path2"></span><span
                                                class="path3"></span>
                                            <span class="path4"></span><span class="path5"></span>
                                        </i>
                                    </a>
                                    <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-active-light-primary w-30px h-30px"
                                            onclick="return confirm('Yakin hapus role ini?')">
                                            <i class="ki-duotone ki-trash fs-3">
                                                <span class="path1"></span><span class="path2"></span><span
                                                    class="path3"></span>
                                                <span class="path4"></span><span class="path5"></span>
                                            </i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                    <tfoot></tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush

@extends('layouts.app')
@section('page-title', 'Data Tickets')
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
            Roles
        </li>
    </ul>
@endsection
@section('partial-navbar')
    <div class="d-flex align-items-center gap-3 mb-5">
        <input type="text" id="kt_daterangepicker_1" class="form-control form-control-solid w-250px"
            placeholder="Select Date Range" readonly>
    </div>
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
                <a href="{{ route('menus.create') }}" class="btn btn-light-primary">
                    <i class="ki-duotone ki-plus-square fs-3">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i> Add Menu
                </a>
            </div>
        </div>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle table-row-dashed fs-6 gy-5 mb-0" id="kt_permissions_table">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                            <th class="min-w-150px">Menu</th>
                            <th class="min-w-250px">Route</th>
                            <th class="min-w-150px">Parent</th>
                            <th class="text-end min-w-150px">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-700">
                        @foreach ($menus as $menu)
                            <tr>
                                <td>{{ $menu->name }}</td>
                                <td>{{ $menu->route }}</td>
                                <td>{{ $menu->parent->name ?? '-' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('menus.edit', $menu) }}" class="btn btn-sm btn-warning me-2">
                                        <i class="ki-duotone ki-pencil fs-5 me-1"></i> Edit
                                    </a>
                
                                    <form method="POST" action="{{ route('menus.destroy', $menu) }}" style="display:inline" onsubmit="return confirm('Delete this menu?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="ki-duotone ki-trash fs-5 me-1"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush

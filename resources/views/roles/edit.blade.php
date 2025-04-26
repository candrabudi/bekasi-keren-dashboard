@extends('layouts.app')
@section('title', 'Edit Role')
@section('page-title', 'Edit Role')
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
        <li class="breadcrumb-item">
            <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
        </li>
        <li class="breadcrumb-item text-white opacity-75">
            Edit Role
        </li>
    </ul>
@endsection
@section('content')
    <div class="card card-flush ">
        <div class="card-header mt-6 d-flex justify-content-between align-items-center">
            <div></div> <!-- Biarkan kosong untuk posisi kiri -->
            <div class="card-toolbar">
                <a href="{{ route('roles.index') }}" class="btn btn-light-primary">
                    <i class="ki-duotone ki-arrow-left fs-3 me-2">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Back to Data
                </a>
            </div>
        </div>
        
        
        <div class="card-body pt-0">
            <form id="kt_modal_add_role_form" class="form fv-plugins-bootstrap5 fv-plugins-framework"
                action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_role_scroll" data-kt-scroll="true"
                    data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
                    data-kt-scroll-dependencies="#kt_modal_add_role_header"
                    data-kt-scroll-wrappers="#kt_modal_add_role_scroll" data-kt-scroll-offset="300px"
                    style="max-height: 611px;">

                    <div class="fv-row mb-10 fv-plugins-icon-container">
                        <label class="fs-5 fw-bold form-label mb-2">
                            <span class="required">Role name</span>
                        </label>
                        <input class="form-control form-control-solid" placeholder="Enter a role name" name="name"
                            value="{{ old('name', $role->name) }}">
                        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        </div>
                    </div>

                    <div class="fv-row">
                        <label class="fs-5 fw-bold form-label mb-2">Role Permissions</label>
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <tbody class="text-gray-600 fw-semibold">
                                    @foreach ($menus as $menu)
                                        @include('roles.partials.permission_row_edit', [
                                            'menu' => $menu,
                                            'permissions' => $permissions,
                                            'rolePermissions' => $rolePermissions,
                                            'level' => 0,
                                        ])
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="text-center pt-15">
                    <button type="submit" class="btn btn-primary" data-kt-roles-modal-action="submit">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">
                            Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
@endpush

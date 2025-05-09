@extends('layouts.app')
@section('title', 'Create Menu')
@section('page-title', 'Create Menu')
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
            Menu
        </li>
        <li class="breadcrumb-item">
            <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
        </li>
        <li class="breadcrumb-item text-white opacity-75">
            Create Menu
        </li>
    </ul>
@endsection
@section('content')
    <div class="card card-flush ">
        <div class="card-header mt-6 d-flex justify-content-between align-items-center">
            <div></div>
            <div class="card-toolbar">
                <a href="{{ route('menus.index') }}" class="btn btn-light-primary">
                    <i class="ki-duotone ki-arrow-left fs-3 me-2">
                        <span class="path1"></span><span class="path2"></span>
                    </i>
                    Back to Data
                </a>
            </div>

        </div>
        <div class="card-body pt-0">
            @if ($errors->any())
                <div class="alert alert-dismissible bg-danger d-flex flex-column flex-sm-row p-5 mb-10">
                    <i class="ki-duotone ki-information fs-2hx text-light me-4 mb-5 mb-sm-0">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i>

                    <div class="d-flex flex-column text-light pe-0 pe-sm-10">
                        <h4 class="mb-2 light">Validation Error</h4>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <button type="button"
                        class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                        data-bs-dismiss="alert">
                        <i class="ki-duotone ki-cross fs-1 text-light"><span class="path1"></span><span
                                class="path2"></span></i>
                    </button>
                </div>
            @endif

            <form method="POST" action="{{ route('menus.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Name</label>
                    <input type="text" id="name" name="name" class="form-control"
                        value="{{ old('name', $menu->name ?? '') }}" placeholder="Enter menu name">
                </div>

                <div class="mb-3">
                    <label for="route" class="form-label fw-bold">Route</label>
                    <input type="text" id="route" name="route" class="form-control"
                        value="{{ old('route', $menu->route ?? '') }}" placeholder="Enter route name">
                </div>

                <div class="mb-4">
                    <label for="parent_id" class="form-label fw-bold">Parent Menu</label>
                    <select id="parent_id" name="parent_id" class="form-select">
                        <option value="">-- None --</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}"
                                {{ old('parent_id', $menu->parent_id ?? '') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
@push('scripts')
@endpush

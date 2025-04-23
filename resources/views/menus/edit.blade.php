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
            <form method="POST" action="{{ route('menus.update', $menu) }}">
                @csrf
                @method('PUT')

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
                        Update
                    </button>
                </div>
            </form>
        </div>


    </div>
@endsection
@push('scripts')
@endpush

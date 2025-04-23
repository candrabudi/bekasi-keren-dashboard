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
            <form method="POST" action="{{ route('backstreet.users.update', $user->id) }}">
                @csrf
                @method('PUT')
            
                <div class="mb-3">
                    <label for="full_name" class="form-label fw-bold">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="form-control"
                        value="{{ old('full_name', $user->full_name) }}" placeholder="Enter full name" required>
                </div>
            
                <div class="mb-3">
                    <label for="username" class="form-label fw-bold">Username</label>
                    <input type="text" id="username" name="username" class="form-control"
                        value="{{ old('username', $user->username) }}" placeholder="Enter username" required>
                </div>
            
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Email</label>
                    <input type="email" id="email" name="email" class="form-control"
                        value="{{ old('email', $user->email) }}" placeholder="Enter email address" required>
                </div>
            
                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Password</label>
                    <input type="password" id="password" name="password" class="form-control"
                        placeholder="Leave blank if not changing">
                </div>
            
                <div class="mb-4">
                    <label for="role_id" class="form-label fw-bold">Role</label>
                    <select name="role_id" id="role_id" class="form-select" required>
                        <option value="">-- Choose Role --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}"
                                {{ old('role_id', $userRole) == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
            
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
            


        </div>

    </div>
@endsection
@push('scripts')
@endpush

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
            <form method="POST" action="{{ route('backstreet.users.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="full_name" class="form-label fw-bold">Full Name</label>
                    <input type="text" id="full_name" name="full_name" class="form-control"
                        value="{{ old('full_name') }}" placeholder="Enter full name" required>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label fw-bold">Username</label>
                    <input type="text" id="username" name="username" class="form-control" value="{{ old('username') }}"
                        placeholder="Enter username" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}"
                        placeholder="Enter email address" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter password"
                        required>
                </div>

                <div class="mb-4">
                    <label for="status" class="form-label fw-bold">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="">-- Select Status --</option>
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="role_id" class="form-label fw-bold">Role</label>
                    <select name="role_id" id="role_id" class="form-select" required>
                        <option value="">Choose Role</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="mt-5 mb-3">
                    <h4>User Details</h4>
                    <span>Tidak perlu diisi jika di khususkan untuk Wali Kota atau jabatan lebih tinggi</span>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label fw-bold">Phone Number</label>
                    <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}"
                        placeholder="Enter phone number">
                </div>

                <div class="mb-4">
                    <label for="department_id" class="form-label fw-bold">Departments</label>
                    <select name="department_id" id="department_id" class="form-select">
                        <option value="">Choose Department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}"
                                {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Save User</button>
                </div>
            </form>

        </div>
    </div>
@endsection
@push('scripts')
@endpush

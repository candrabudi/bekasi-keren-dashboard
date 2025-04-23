@extends('layouts.app')
@section('page-title', 'Users')
@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7">
        <li class="breadcrumb-item text-gray-700 fw-bold lh-1 mx-n1">
            <a href="/" class="text-hover-primary">
                <i class="ki-duotone ki-home text-gray-700 fs-6"></i>
            </a>
        </li>
        <li class="breadcrumb-item">
            <i class="ki-duotone ki-right fs-7 text-gray-700"></i>
        </li>
        <li class="breadcrumb-item text-gray-700 fw-bold lh-1 mx-n1">
            Users
        </li>
    </ul>
@endsection
@section('content')
    <div class="card card-flush">
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
                <a href="{{ route('backstreet.users.create') }}" class="btn btn-light-primary">
                    <i class="ki-duotone ki-plus-square fs-3">
                        <span class="path1"></span><span class="path2"></span><span class="path3"></span>
                    </i> Add User
                </a>
            </div>
        </div>
        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                    <thead>
                        <tr class="text-start text-gray-700 fw-bold fs-7 text-uppercase gs-0">
                            <th>No</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach ($users as $index => $user)
                            <tr>
                                <td>{{ $index + $users->firstItem() }}</td>
                                <td>{{ $user->full_name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="text-end">
                                    <a href="{{ route('backstreet.users.edit', $user) }}" class="btn btn-sm btn-warning me-2">
                                        <i class="bi bi-pencil-fill me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('backstreet.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash-fill me-1"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                                
            </div>


            @php
                $currentPage = $users->currentPage();
                $lastPage = $users->lastPage();
                $start = max(1, $currentPage - 1);
                $end = min($lastPage, $currentPage + 1);
            @endphp

            <div class="row mt-4">
                <div
                    class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar">
                    <div>
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }}
                        of a total of {{ $users->total() }} tickets
                    </div>
                </div>

                <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                    <div class="dt-paging paging_simple_numbers">
                        <nav aria-label="pagination">
                            <ul class="pagination mb-0">
                                {{-- Previous --}}
                                <li
                                    class="dt-paging-button page-item {{ $users->onFirstPage() ? 'disabled' : '' }}">
                                    <a href="{{ $users->previousPageUrl() ?? '#' }}" class="page-link"
                                        aria-label="Previous">
                                        <i class="fa fa-angle-left"></i>
                                    </a>
                                </li>

                                {{-- First page --}}
                                @if ($start > 1)
                                    <li class="dt-paging-button page-item">
                                        <a href="{{ $users->url(1) }}" class="page-link">1</a>
                                    </li>
                                    @if ($start > 2)
                                        <li class="dt-paging-button page-item disabled"><span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endif

                                {{-- Dynamic pages --}}
                                @for ($i = $start; $i <= $end; $i++)
                                    <li class="dt-paging-button page-item {{ $i == $currentPage ? 'active' : '' }}">
                                        <a href="{{ $users->url($i) }}"
                                            class="page-link">{{ $i }}</a>
                                    </li>
                                @endfor

                                {{-- Last page --}}
                                @if ($end < $lastPage)
                                    @if ($end + 1 < $lastPage)
                                        <li class="dt-paging-button page-item disabled"><span class="page-link">...</span>
                                        </li>
                                    @endif
                                    <li class="dt-paging-button page-item">
                                        <a href="{{ $users->url($lastPage) }}"
                                            class="page-link">{{ $lastPage }}</a>
                                    </li>
                                @endif

                                {{-- Next --}}
                                <li
                                    class="dt-paging-button page-item {{ $users->hasMorePages() ? '' : 'disabled' }}">
                                    <a href="{{ $users->nextPageUrl() ?? '#' }}" class="page-link"
                                        aria-label="Next">
                                        <i class="fa fa-angle-right"></i>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

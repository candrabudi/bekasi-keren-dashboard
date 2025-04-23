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
            Call Center
        </li>
        <li class="breadcrumb-item">
            <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
        </li>
        <li class="breadcrumb-item text-white opacity-75">
            Data Tickets
        </li>
    </ul>
@endsection
@section('content')

    <div class="row mb-5">
        {{-- 📌 CARD: Total Semua Tiket --}}
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="text-gray-700">Total Tiket</h5>
                    <div class="fs-2 fw-bold text-dark">
                        {{ $ticketsByStatus->sum('total') }}
                    </div>
                </div>
            </div>
        </div>

        @foreach ($ticketsByStatus as $status)
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="text-gray-700">{{ $status->status_name }}</h5>
                        <div class="fs-2 fw-bold text-primary">{{ $status->total }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row mb-5">
        @foreach ($ticketsByCategories as $ticketct)
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="text-gray-700">{{ $ticketct->call_type_name }}</h5>
                        <div class="fs-2 fw-bold text-primary">{{ $ticketct->total }}</div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card card-flush ">
        <form method="GET" action="{{ route('backstreet.tickets.index') }}">
            <div class="card-header mt-6 d-flex flex-column gap-4">

                {{-- Baris 1: Search dan Date Range --}}
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
                    <!-- Search -->
                    <div class="d-flex align-items-center position-relative flex-grow-1 me-3">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                            <span class="path1"></span><span class="path2"></span>
                        </i>
                        <input type="text" name="search" class="form-control form-control-solid ps-13"
                            placeholder="Search Ticket / Phone / Caller" value="{{ request('search') }}">
                    </div>

                    <div class="d-flex align-items-center">
                        <input type="text" id="kt_daterangepicker_1" name="date_range"
                            class="form-control form-control-solid w-250px" placeholder="Select Date Range"
                            value="{{ request('date_range') }}">
                    </div>
                </div>

                <div class="d-flex flex-wrap align-items-center justify-content-between gap-4 mb-5">

                    <div class="flex-grow-1">
                        <select name="district" id="district-dropdown" class="form-select form-select-solid">
                            <option value="">All Districts</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}"
                                    {{ request('district') == $district->id ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-grow-1">
                        <select name="subdistrict" id="village-dropdown" class="form-select form-select-solid">
                            <option value="">All Subdistricts</option>
                            @if (request('district'))
                                @foreach ($subdistricts as $village)
                                    <option value="{{ $village->id }}"
                                        {{ request('subdistrict') == $village->id ? 'selected' : '' }}>
                                        {{ $village->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    @if (!Auth::user()->userDetail)     
                        <div class="flex-grow-1">
                            <select name="department_id" class="form-select form-select-solid">
                                <option value="">Departments</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="flex-grow-1">
                        <select name="status" class="form-select form-select-solid">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status') == 1 ? 'selected' : '' }}>Open</option>
                            <option value="2" {{ request('status') == 2 ? 'selected' : '' }}>In Progress</option>
                            <option value="3" {{ request('status') == 3 ? 'selected' : '' }}>Resolved</option>
                            <option value="4" {{ request('status') == 4 ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Apply</button>
                        <a href="{{ route('backstreet.tickets.index') }}" class="btn btn-light">Reset</a>
                    </div>
                </div>
            </div>
        </form>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Ticket</th>
                            <th>Status</th>
                            <th>Call Type</th>
                            <th>Phone</th>
                            <th>Caller</th>
                            <th>Created At</th>
                            <th>Last Update</th>
                            <th>Action</th> <!-- Kolom untuk tombol detail -->
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach ($tickets as $index => $ticket)
                            <tr>
                                <td>{{ $index + $tickets->firstItem() }}</td>
                                <td>{{ $ticket->ticket }}</td>
                                <td>{{ $ticket->status_name }}</td>
                                <td>{{ $ticket->call_type_name }}</td>
                                <td>{{ $ticket->phone }}</td>
                                <td>{{ $ticket->caller }}</td>
                                <td>{{ $ticket->created_at->format('d-m-Y H:i') }}</td>
                                <td>{{ $ticket->updated_at ? $ticket->updated_at->format('d-m-Y H:i') : '-' }}</td>
                                <td>
                                    <a href="{{ route('backstreet.tickets.detail', $ticket->ticket) }}"
                                        class="btn btn-sm btn-info">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            @php
                $currentPage = $tickets->currentPage();
                $lastPage = $tickets->lastPage();
                $start = max(1, $currentPage - 1);
                $end = min($lastPage, $currentPage + 1);
            @endphp

            <div class="row mt-4">
                <div
                    class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar">
                    <div>
                        Menampilkan {{ $tickets->firstItem() }} sampai {{ $tickets->lastItem() }} dari total
                        {{ $tickets->total() }} tiket
                    </div>
                </div>
                <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                    <div class="dt-paging paging_simple_numbers">
                        <nav aria-label="pagination">
                            <ul class="pagination mb-0">
                                {{-- Previous --}}
                                <li class="dt-paging-button page-item {{ $tickets->onFirstPage() ? 'disabled' : '' }}">
                                    <a href="{{ $tickets->previousPageUrl() ?? '#' }}" class="page-link"
                                        aria-label="Previous">
                                        <i class="fa fa-angle-left"></i>
                                    </a>
                                </li>

                                {{-- First page --}}
                                @if ($start > 1)
                                    <li class="dt-paging-button page-item">
                                        <a href="{{ $tickets->url(1) }}" class="page-link">1</a>
                                    </li>
                                    @if ($start > 2)
                                        <li class="dt-paging-button page-item disabled"><span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endif

                                {{-- Dynamic pages --}}
                                @for ($i = $start; $i <= $end; $i++)
                                    <li class="dt-paging-button page-item {{ $i == $currentPage ? 'active' : '' }}">
                                        <a href="{{ $tickets->url($i) }}" class="page-link">{{ $i }}</a>
                                    </li>
                                @endfor

                                {{-- Last page --}}
                                @if ($end < $lastPage)
                                    @if ($end + 1 < $lastPage)
                                        <li class="dt-paging-button page-item disabled"><span class="page-link">...</span>
                                        </li>
                                    @endif
                                    <li class="dt-paging-button page-item">
                                        <a href="{{ $tickets->url($lastPage) }}"
                                            class="page-link">{{ $lastPage }}</a>
                                    </li>
                                @endif

                                {{-- Next --}}
                                <li class="dt-paging-button page-item {{ $tickets->hasMorePages() ? '' : 'disabled' }}">
                                    <a href="{{ $tickets->nextPageUrl() ?? '#' }}" class="page-link" aria-label="Next">
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
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const districtDropdown = document.getElementById('district-dropdown');
            const villageDropdown = document.getElementById('village-dropdown');

            districtDropdown.addEventListener('change', function() {
                const districtId = this.value;
                villageDropdown.innerHTML = '<option value="">Loading...</option>';

                fetch(`/backstreet/get-villages?district_id=${districtId}`)
                    .then(response => response.json())
                    .then(data => {
                        let options = '<option value="">All</option>';
                        Object.entries(data).forEach(([id, name]) => {
                            options += `<option value="${id}">${name}</option>`;
                        });
                        villageDropdown.innerHTML = options;
                    });
            });
        });
    </script>
    <script>
        $('#kt_daterangepicker_1').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('#kt_daterangepicker_1').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format('YYYY-MM-DD'));
        });

        $('#kt_daterangepicker_1').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    </script>
@endpush

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
            Call Records
        </li>
    </ul>
@endsection
@section('content')
    <div class="row mb-5">
        @php
            $cards = [
                ['label' => 'Calls Today', 'value' => $stats['total_today']],
                ['label' => 'Calls This Month', 'value' => $stats['total_month']],
                ['label' => 'Average Duration', 'value' => number_format($stats['avg_duration'], 2) . ' seconds'],
                ['label' => 'Average Wait Time', 'value' => number_format($stats['avg_wait'], 2) . ' seconds'],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h5 class="text-gray-700">{{ $card['label'] }}</h5>
                        <h3 class="text-primary">{{ $card['value'] }}</h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>


    @php
        $statusMap = [
            'Abandonada' => 'Abandoned',
            'Activa' => 'Active',
            'Terminada' => 'Completed',
        ];

        $statusColors = [
            'Abandonada' => 'danger',
            'Activa' => 'info',
            'Terminada' => 'success',
        ];
    @endphp

    <div class="card mb-5">
        <div class="card-header">
            <h3 class="card-title">Call Status Distribution</h3>
        </div>
        <div class="card-body p-0">
            @forelse ($stats['status_distribution'] as $status)
                @php
                    $statusKey = ucfirst($status->status);
                    $label = $statusMap[$statusKey] ?? $statusKey;
                    $badgeColor = $statusColors[$statusKey] ?? 'secondary';
                @endphp

                <div class="d-flex align-items-center justify-content-between px-9 py-4 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-40px me-4">
                            <span class="symbol-label bg-light-{{ $badgeColor }}">
                                <i class="ki-outline ki-phone fs-2 text-{{ $badgeColor }}"></i>
                            </span>
                        </div>
                        <div class="text-dark fw-bold fs-6">{{ $label }}</div>
                    </div>
                    <span class="badge badge-light-{{ $badgeColor }} fs-6 fw-semibold">{{ $status->total }}</span>
                </div>
            @empty
                <div class="text-center text-muted py-10">
                    No Status Found.
                </div>
            @endforelse
        </div>
    </div>


    <div class="card card-flush">
        <form method="GET" action="{{ route('backstreet.callrecords.index') }}">
            <div class="card-header mt-6 d-flex flex-column gap-4">

                <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
                    <!-- Pencarian -->
                    <div class="d-flex align-items-center position-relative flex-grow-1 me-3">
                        <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5"></i>
                        <input type="text" name="search" class="form-control form-control-solid ps-13"
                            placeholder="Cari Ticket / Telepon / Pemanggil" value="{{ request('search') }}">
                    </div>
                    <input type="text" id="kt_daterangepicker_1" name="date_range"
                        class="form-control form-control-solid w-250px" placeholder="Pilih Rentang Tanggal"
                        value="{{ request('date_range') }}">
                </div>
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-4 mb-5">
                    <select name="status" class="form-select w-auto">
                        <option value="">Semua Status</option>
                        <option value="terminada" {{ request('status') == 'terminada' ? 'selected' : '' }}>Completed
                        </option>
                        <option value="activa" {{ request('status') == 'activa' ? 'selected' : '' }}>Active</option>
                        <option value="abandonada" {{ request('status') == 'abandonada' ? 'selected' : '' }}>Abandoned
                        </option>
                    </select>

                    <input type="number" name="min_wait" class="form-control w-150px" placeholder="Min Tunggu (s)"
                        value="{{ request('min_wait') }}">

                    <input type="number" name="min_duration" class="form-control w-150px" placeholder="Min Durasi (s)"
                        value="{{ request('min_duration') }}">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Terapkan</button>
                        <a href="{{ route('backstreet.callrecords.index') }}" class="btn btn-light">Reset</a>
                    </div>
                </div>
            </div>
        </form>

        <div class="card-body pt-0">
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                    <thead>
                        <tr class="text-start text-gray-700 fw-bold fs-7 text-uppercase gs-0">
                            <th>No</th>
                            <th>Phone</th>
                            <th>Entered Queue</th>
                            <th>Waiting Time</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Recording</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                        @foreach ($callCenterRecords as $index => $record)
                            <tr>
                                <td>{{ $index + $callCenterRecords->firstItem() }}</td>
                                <td>{{ $record->phone }}</td>
                                <td>{{ $record->datetime_entry_queue }}</td>
                                <td>{{ $record->duration_wait }} seconds</td>
                                <td>{{ $record->datetime_init }}</td>
                                <td>{{ $record->datetime_end }}</td>
                                <td>{{ ucfirst($record->status) }}</td>
                                <td>
                                    @if ($record->recording_file)
                                        <a href="{{ $record->recording_file }}" target="_blank" class="btn btn-sm btn-primary">
                                            Play
                                        </a>
                                    @else
                                        <span class="text-muted">Not Available</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            

            @php
                $currentPage = $callCenterRecords->currentPage();
                $lastPage = $callCenterRecords->lastPage();
                $start = max(1, $currentPage - 1);
                $end = min($lastPage, $currentPage + 1);
            @endphp

            <div class="row mt-4">
                <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start dt-toolbar">
                    <div>
                        Showing {{ $callCenterRecords->firstItem() }} to {{ $callCenterRecords->lastItem() }}
                        of a total of {{ $callCenterRecords->total() }} tickets
                    </div>
                </div>
                
                <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end">
                    <div class="dt-paging paging_simple_numbers">
                        <nav aria-label="pagination">
                            <ul class="pagination mb-0">
                                {{-- Previous --}}
                                <li
                                    class="dt-paging-button page-item {{ $callCenterRecords->onFirstPage() ? 'disabled' : '' }}">
                                    <a href="{{ $callCenterRecords->previousPageUrl() ?? '#' }}" class="page-link"
                                        aria-label="Previous">
                                        <i class="fa fa-angle-left"></i>
                                    </a>
                                </li>

                                {{-- First page --}}
                                @if ($start > 1)
                                    <li class="dt-paging-button page-item">
                                        <a href="{{ $callCenterRecords->url(1) }}" class="page-link">1</a>
                                    </li>
                                    @if ($start > 2)
                                        <li class="dt-paging-button page-item disabled"><span class="page-link">...</span>
                                        </li>
                                    @endif
                                @endif

                                {{-- Dynamic pages --}}
                                @for ($i = $start; $i <= $end; $i++)
                                    <li class="dt-paging-button page-item {{ $i == $currentPage ? 'active' : '' }}">
                                        <a href="{{ $callCenterRecords->url($i) }}"
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
                                        <a href="{{ $callCenterRecords->url($lastPage) }}"
                                            class="page-link">{{ $lastPage }}</a>
                                    </li>
                                @endif

                                {{-- Next --}}
                                <li
                                    class="dt-paging-button page-item {{ $callCenterRecords->hasMorePages() ? '' : 'disabled' }}">
                                    <a href="{{ $callCenterRecords->nextPageUrl() ?? '#' }}" class="page-link"
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

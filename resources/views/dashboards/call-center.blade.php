@extends('layouts.app')
@section('title', 'Dashboard Call Center')
@section('page-title', 'Dashboard Call Center')
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
            Dashboards
        </li>
        <li class="breadcrumb-item">
            <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
        </li>
        <li class="breadcrumb-item text-white opacity-75">
            Call Center
        </li>
    </ul>
@endsection
@section('partial-navbar')
    <div class="d-flex align-items-center gap-3 mb-5">
        <input type="text" id="kt_daterangepicker_1" class="form-control form-control-solid w-250px"
            placeholder="Select Date Range" readonly>
        <button id="apply-date-filter" class="btn btn-primary">Apply</button>
        <button id="reset-date-filter" class="btn btn-light">Reset</button>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col">
            <div class="card card-dashed flex-center min-w-175px my-3 p-6">
                <span class="fs-4 fw-semibold text-info pb-1 px-2">New Reports</span>
                <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                    <span id="count-new" class="counted">0</span>
                </span>
            </div>
        </div>

        <div class="col">
            <div class="card card-dashed flex-center min-w-175px my-3 p-6">
                <span class="fs-4 fw-semibold text-success pb-1 px-2">Process Reports</span>
                <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                    <span id="count-process" class="counted">0</span>
                </span>
            </div>
        </div>

        <div class="col">
            <div class="card card-dashed flex-center min-w-175px my-3 p-6">
                <span class="fs-4 fw-semibold text-danger pb-1 px-2">Completed Reports</span>
                <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                    <span id="count-completed" class="counted">0</span>
                </span>
            </div>
        </div>

        <div class="col">
            <div class="card card-dashed flex-center min-w-175px my-3 p-6">
                <span class="fs-4 fw-semibold text-primary pb-1 px-2">Total Reports</span>
                <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                    <span id="count-total" class="counted">0</span>
                </span>
            </div>
        </div>
    </div>


    <div class="row g-5 g-xl-10 mt-2">
        <div class="col-xl-4">
            <div class="card card-flush">
                <div class="card-header">
                    <h3 class="card-title">Top 5 Ticket Categories</h3>
                    <div class="card-toolbar">
                        <a href="{{ route('backstreet.tickets.categories.index') }}" class="btn btn-sm btn-light-primary">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-row-bordered gy-5">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th>Kategori</th>
                                    <th>Total Tiket</th>
                                </tr>
                            </thead>
                            <tbody id="table-categories">
                                <tr>
                                    <td colspan="2" class="text-center text-muted">Memuat data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-xl-4">
            <div class="card card-flush">
                <div class="card-header">
                    <h3 class="card-title">Top 5 Ticket Districts</h3>
                    <div class="card-toolbar">
                        <a href="" class="btn btn-sm btn-light-primary">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-row-bordered gy-5">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th>District Name</th>
                                    <th>Total Tiket</th>
                                </tr>
                            </thead>
                            <tbody id="table-districts">
                                <tr>
                                    <td colspan="2" class="text-center text-muted">Memuat data...</td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card card-flush">
                <div class="card-header">
                    <h3 class="card-title">Top 5 Ticket Sub Districts</h3>
                    <div class="card-toolbar">
                        <a href="" class="btn btn-sm btn-light-primary">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="table-responsive">
                        <table class="table table-row-bordered gy-5">
                            <thead>
                                <tr class="fw-bold fs-6 text-gray-800">
                                    <th>SubDistrict Name</th>
                                    <th>Total Tiket</th>
                                </tr>
                            </thead>
                            <tbody id="table-subdistricts">
                                <tr>
                                    <td colspan="2" class="text-center text-muted">Memuat data...</td>
                                </tr>
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-charts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js" integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(function() {
            const input = $('#kt_daterangepicker_1');
            const today = moment().format('YYYY-MM-DD');
    
            input.daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear'
                }
            });
    
            input.on('apply.daterangepicker', function(ev, picker) {
                const selectedDateRange = `${picker.startDate.format('YYYY-MM-DD')} to ${picker.endDate.format('YYYY-MM-DD')}`;
                $(this).val(selectedDateRange);
                fetchAllData(selectedDateRange);
            });
    
            input.on('cancel.daterangepicker', function() {
                const selectedDateRange = `${today} to ${today}`;
                $(this).val(selectedDateRange);
                fetchAllData(selectedDateRange);
            });
            const selectedDateRange = `${today} to ${today}`;
            input.val(selectedDateRange);
            fetchAllData(selectedDateRange);
        });

        function fetchAllData(dateRange) {
            const params = {
                date_range: dateRange
            };
            axios.get('{{ route('backstreet.dashboards.callcenter.countreports') }}', { params })
                .then(response => {
                    const data = response.data;
                    document.getElementById('count-new').innerText = data.new;
                    document.getElementById('count-process').innerText = data.process;
                    document.getElementById('count-completed').innerText = data.completed;
                    document.getElementById('count-total').innerText = data.new + data.process + data.completed;
                })
                .catch(err => console.error('Failed to fetch count data:', err));
            axios.get('{{ route('backstreet.dashboards.callcenter.ticketcategories') }}', { params })
                .then(res => updateTable('table-categories', res.data, 'category'))
                .catch(() => showEmptyRow('table-categories'));
            axios.get('{{ route('backstreet.dashboards.callcenter.ticketdistricts') }}', { params })
                .then(res => updateTable('table-districts', res.data, 'district'))
                .catch(() => showEmptyRow('table-districts'));
            axios.get('{{ route('backstreet.dashboards.callcenter.ticketsubdistricts') }}', { params })
                .then(res => updateTable('table-subdistricts', res.data, 'subdistrict'))
                .catch(() => showEmptyRow('table-subdistricts'));
        }
        function updateTable(tableId, data, keyName) {
            const table = document.getElementById(tableId);
            table.innerHTML = '';
    
            if (data.length === 0) {
                showEmptyRow(tableId);
                return;
            }
    
            data.forEach(item => {
                const name = item[keyName] ?? 'Tidak diketahui';
                const shortName = name.length > 20 ? name.slice(0, 20) + '...' : name;
    
                const row = `
                    <tr>
                        <td><span title="${name}" data-bs-toggle="tooltip">${shortName}</span></td>
                        <td>${item.total}</td>
                    </tr>`;
                table.insertAdjacentHTML('beforeend', row);
            });
        }
        function showEmptyRow(tableId) {
            document.getElementById(tableId).innerHTML = `
                <tr>
                    <td colspan="2" class="text-center text-gray-400">Belum ada data tiket.</td>
                </tr>`;
        }
    </script>
@endpush

@push('scripts')
    
@endpush

@extends('layouts.app')
@section('title', 'Kategori Insiden')
    @section('page-title', 'Kategori Insiden')
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
            Kategori Insiden
        </li>
    </ul>
@endsection
@section('partial-navbar')
    <div class="d-flex align-items-center flex-wrap py-2">
        <div class="d-flex justify-content-end mb-5">
            <div class="d-flex align-items-center gap-3">
                <input type="text" id="date-range" class="form-control form-control-solid w-250px"
                    placeholder="Select Date Range">
                <button type="submit" class="btn btn-primary" id="filter">Filter</button>
            </div>
        </div>
    </div>
@endsection
@section('content')
<div class="card card-flush">
    <div class="card-header border-0 pt-6">
        <div class="row w-100 d-flex align-items-center">
            <div class="col-md-6">
                <h5 class="mb-0">
                    Total Tickets: 
                    <span id="total_count" class="badge bg-success">0</span>
                </h5>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                <div class="d-flex align-items-center">
                    <label for="table-search" class="form-label me-2">Search:</label>
                    <input 
                        type="text" 
                        id="table-search" 
                        class="form-control" 
                        placeholder="Search category or status..."
                    >
                </div>
            </div>
        </div>
    </div>
    <div class="card-body pt-0">
        <div class="table-responsive">
            <table id="ticketsTable" class="table align-middle table-row-dashed fs-6 gy-5 dataTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Category</th>
                        <th>Total Tickets</th>
                        <th>Baru</th>
                        <th>Proses</th>
                        <th>Selesai</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

    <script>
        let table;
        let startDate = '';
        let endDate = '';

        $(document).ready(function() {
            var startOfMonth = moment().startOf('month').format('YYYY-MM-DD');
            var endOfMonth = moment().endOf('month').format('YYYY-MM-DD');

            // Inisialisasi daterangepicker dengan default bulan ini
            $('#date-range').daterangepicker({
                startDate: startOfMonth,
                endDate: endOfMonth,
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'YYYY-MM-DD'
                }
            });

            // Set default value pada input field
            $('#date-range').val(startOfMonth + ' to ' + endOfMonth);

            $('#date-range').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' to ' + picker.endDate.format(
                    'YYYY-MM-DD'));
                startDate = picker.startDate.format('YYYY-MM-DD');
                endDate = picker.endDate.format('YYYY-MM-DD');
            });

            $('#date-range').on('cancel.daterangepicker', function() {
                $(this).val('');
                startDate = '';
                endDate = '';
            });

            table = $('#ticketsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('backstreet.tickets.categories.data') }}",
                    data: function(d) {
                        d.start_date = startDate;
                        d.end_date = endDate;
                    },
                    dataSrc: function(json) {
                        const total = json.data.reduce((sum, row) => sum + parseInt(row.total_tickets),
                            0);
                        $('#total_count').text(total);
                        return json.data;
                    }
                },
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search category..."
                },
                columns: [{
                        data: null,
                        render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1
                    },
                    {
                        data: 'category'
                    },
                    {
                        data: 'total_tickets'
                    },
                    {
                        data: 'baru'
                    },
                    {
                        data: 'proses'
                    },
                    {
                        data: 'selesai'
                    }
                ]
            });

            $('#filter').on('click', function() {
                table.ajax.reload();
            });

            $('#table-search').on('keyup', function() {
                table.search(this.value).draw();
            });
        });
    </script>


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

@extends('layouts.app')
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
    <div class="d-flex align-items-center flex-wrap py-2">
        <form action="{{ route('backstreet.dashboards.callcenter') }}" method="GET"
            class="d-flex justify-content-end mb-5">
            <div class="d-flex align-items-center gap-3">
                <input type="text" id="kt_daterangepicker_1" name="date_range"
                    class="form-control form-control-solid w-250px" placeholder="Select Date Range"
                    value="{{ request('date_range') }}">
                <button type="submit" class="btn btn-primary">Apply</button>
                <a href="{{ route('backstreet.dashboards.callcenter') }}" class="btn btn-light">Reset</a>
            </div>
        </form>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col">

            <div class="card bg-primary text-white p-4">
                <div class="row mb-4">
                    <div class="col-md-7 d-flex align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Total Panggilan
                        </h4>
                    </div>
                    <div class="col-md-5 d-flex align-items-center justify-content-end">
                        <h4 class="fw-bold mb-0">SLA</h4>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-7 text-start">
                                <div class="fs-1 fw-bold" style="font-size: 5.5rem !important;">166</div>
                            </div>
                            <div class="col-md-5 text-center">
                                <div id="slaSpeedometer">
                                    <!-- Simulasi Speedometer Placeholder -->
                                    <svg class="speedometer" width="150" height="80">
                                        <text x="75" y="45" text-anchor="middle" fill="#ccc" font-size="24"
                                            font-weight="bold">49.4%</text>
                                    </svg>
                                </div>
                                <div class="fw-bold fs-6 mt-2">49.4%</div>
                            </div>
                        </div>
                    </div>
    
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-comment-alt me-2"></i>
                                    <span class="fs-7">Terjawab</span>
                                </div>
                                <div class="fs-1 fw-semibold">82</div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-times me-2"></i>
                                    <span class="fs-7">Tak Terjawab</span>
                                </div>
                                <div class="fs-1 fw-semibold">84</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card card-dashed flex-center min-w-175px my-3 p-6">
                <span class="fs-4 fw-semibold text-info pb-1 px-2">New Reports</span>
                <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                    <span data-kt-countup="true" data-kt-countup-value="63,240.00" class="counted"
                        data-kt-initialized="1">{{ $countNewReports }}</span>
                </span>
            </div>
        </div>

        <div class="col">
            <div class="card card-dashed flex-center min-w-175px my-3 p-6">
                <span class="fs-4 fw-semibold text-success pb-1 px-2">Process Reports</span>
                <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                    <span data-kt-countup="true" data-kt-countup-value="8,530.00" class="counted" data-kt-initialized="1">
                        {{ $countProcessReports }}
                    </span>
                </span>
            </div>
        </div>

        <div class="col">
            <div class="card card-dashed flex-center min-w-175px my-3 p-6">
                <span class="fs-4 fw-semibold text-danger pb-1 px-2">Completed Reports</span>
                <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                    <span data-kt-countup="true" data-kt-countup-value="2,600" class="counted" data-kt-initialized="1">
                        {{ $countCompletedReports }}
                    </span>
                </span>
            </div>
        </div>

        <div class="col">
            <div class="card card-dashed flex-center min-w-175px my-3 p-6">
                <span class="fs-4 fw-semibold text-primary pb-1 px-2">Total Reports</span>
                <span class="fs-lg-2tx fw-bold d-flex justify-content-center">
                    <span data-kt-countup="true" data-kt-countup-value="783&quot;" class="counted" data-kt-initialized="1">
                        {{ $countNewReports + $countProcessReports + $countCompletedReports }}
                    </span>
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
                                    <th>Kategori</th>
                                    <th>Total Tiket</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ticketCategories as $category)
                                    <tr>
                                        <td>
                                            <span title="{{ $category->category ?? 'Tidak diketahui' }}"
                                                data-bs-toggle="tooltip" data-bs-placement="top">
                                                {{ Str::limit($category->category ?? 'Tidak diketahui', 20) }}
                                            </span>
                                        </td>

                                        <td>{{ $category->total }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-gray-400">Belum ada data tiket.</td>
                                    </tr>
                                @endforelse
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
                            <tbody>
                                @forelse ($ticketDistricts as $district)
                                    <tr>
                                        <td>
                                            <span title="{{ $district->district ?? 'Tidak diketahui' }}"
                                                data-bs-toggle="tooltip" data-bs-placement="top">
                                                {{ Str::limit($district->district ?? 'Tidak diketahui', 20) }}
                                            </span>
                                        </td>

                                        <td>{{ $district->total }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-gray-400">Belum ada data tiket.</td>
                                    </tr>
                                @endforelse
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
                            <tbody>
                                @forelse ($ticketSubDistricts as $subdistrict)
                                    <tr>
                                        <td>
                                            <span title="{{ $subdistrict->subdistrict ?? 'Tidak diketahui' }}"
                                                data-bs-toggle="tooltip" data-bs-placement="top">
                                                {{ Str::limit($subdistrict->subdistrict ?? 'Tidak diketahui', 20) }}
                                            </span>
                                        </td>

                                        <td>{{ $subdistrict->total }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-gray-400">Belum ada data tiket.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row gy-5 g-xl-10 mt-2">
        <div class="col-xl-6 mb-xl-10">
            <div class="card card-flush h-lg-100">
                <div class="card-header flex-nowrap pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-900">Activity Report Insident</span>
                    </h3>
                </div>

                <div class="card-body pt-5 ps-6">

                    <div id="ticketsPerHourChart" class="mt-5"></div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-xl-10">
            <div class="card card-flush h-lg-100">
                <div class="card-header flex-nowrap pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-900">Activity Call Today</span>
                    </h3>
                </div>

                <div class="card-body pt-5 ps-6">
                    <div id="callStatusChart" style="height: 400px;"></div>
                </div>
            </div>
        </div>

    </div>


    <div class="row gy-5 g-xl-10 mt-2">
        <div class="col-xl-12 mb-xl-10">
            <div class="card card-flush h-lg-100">
                <div class="card-header flex-nowrap pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-900">Data Geo Report Insident</span>
                    </h3>
                </div>

                <div class="card-body pt-5 ps-6">
                    <div id="map" style="width: 100%; height: 600px;"></div>

                    <div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalTitle"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-xl">
                            <div class="modal-content rounded">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="ticketModalTitle">Detail Tiket</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="ticketModalLoader" style="display:none; text-align:center; padding:20px;">
                                        <div class="spinner-border text-primary" role="status"
                                            style="width: 3rem; height: 3rem;">
                                            <span class="visually-hidden">Memuat...</span>
                                        </div>
                                        <p style="margin-top:10px;">Memuat data tiket...</p>
                                    </div>

                                    <table id="ticketTable"
                                        class="table align-middle table-row-dashed fs-6 gy-5 dataTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Ticket</th>
                                                <th>Category</th>
                                                <th>Status</th>
                                                <th>Caller</th>
                                                <th>Phone</th>
                                                <th>Address</th>
                                                <th>Notes</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ticketModalBody"></tbody>
                                    </table>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <style>
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/ol@latest/dist/ol.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@latest/ol.css" />
    <!-- Bootstrap 5.3+ -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const ticketData = @json($data);

        const normalize = str => (str || '').toUpperCase().replace(/\s+/g, '');

        const getColor = (total) => {
            const max = Math.max(...ticketData.map(d => d.total));
            const min = Math.min(...ticketData.map(d => d.total));
            const ratio = (total - min) / (max - min);
            const red = Math.round(ratio * 255);
            const green = Math.round((1 - ratio) * 255);
            return `rgba(${red}, ${green}, 0, 0.6)`;
        };

        const map = new ol.Map({
            target: 'map',
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                })
            ],
            view: new ol.View({
                center: ol.proj.fromLonLat([106.992416, -6.241586]),
                zoom: 11
            })
        });

        const geojsonUrl =
            'https://raw.githubusercontent.com/JfrAziz/indonesia-district/master/id32_jawa_barat/id3275_kota_bekasi/id3275_kota_bekasi.geojson';

        const vectorSource = new ol.source.Vector({
            url: geojsonUrl,
            format: new ol.format.GeoJSON()
        });

        const vectorLayer = new ol.layer.Vector({
            source: vectorSource,
            style: function(feature) {
                const districtName = feature.get('district');
                const match = ticketData.find(d => normalize(d.district) === normalize(districtName));
                const total = match ? match.total : 0;

                return new ol.style.Style({
                    fill: new ol.style.Fill({
                        color: getColor(total)
                    }),
                    stroke: new ol.style.Stroke({
                        color: '#555',
                        width: 1
                    }),
                    text: new ol.style.Text({
                        text: `${districtName}\n${total} tiket`,
                        fill: new ol.style.Fill({
                            color: '#000'
                        }),
                        stroke: new ol.style.Stroke({
                            color: '#fff',
                            width: 2
                        }),
                        font: 'bold 16px sans-serif',
                        overflow: true,
                        placement: 'point'
                    })
                });
            }
        });

        vectorSource.on('change', function() {
            if (vectorSource.getState() === 'ready') {
                map.getView().fit(vectorSource.getExtent(), {
                    padding: [20, 20, 20, 20],
                    maxZoom: 14
                });
            }
        });

        map.addLayer(vectorLayer);

        const popup = document.createElement('div');
        popup.className = 'ol-popup';
        const overlay = new ol.Overlay({
            element: popup,
            positioning: 'bottom-center',
            stopEvent: false,
            offset: [0, -15]
        });
        map.addOverlay(overlay);

        const modalElement = document.getElementById('ticketModal');
        const bsModal = new bootstrap.Modal(modalElement);

        map.on('click', function(event) {
            overlay.setPosition(undefined);
            map.forEachFeatureAtPixel(event.pixel, function(feature) {
                const name = feature.get('district') || '';
                document.getElementById('ticketModalTitle').innerText = '';
                document.getElementById('ticketModalBody').innerHTML = '';
                document.getElementById('ticketModalLoader').style.display = 'block';

                // Tampilkan modal Bootstrap
                bsModal.show();

                fetch(`/tickets/by-district?district=${encodeURIComponent(name)}`)
                    .then(res => res.json())
                    .then(data => {
                        showTicketModal(name, data);
                    });
            });
        });

        function showTicketModal(districtName, tickets) {
            const loader = document.getElementById('ticketModalLoader');
            loader.style.display = 'none';

            const tbody = document.getElementById('ticketModalBody');
            document.getElementById('ticketModalTitle').innerText = `Daftar Tiket di ${districtName}`;
            tbody.innerHTML = '';

            if ($.fn.DataTable.isDataTable('#ticketTable')) {
                $('#ticketTable').DataTable().clear().destroy();
            }

            if (tickets.length === 0) {
                tbody.innerHTML = `
            <tr>
                <td colspan="8" style="padding:10px; text-align:center;">Tidak ada tiket ditemukan.</td>
            </tr>
        `;
            } else {
                tickets.forEach(ticket => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${ticket.ticket}</td>
                        <td>${ticket.category || '-'}</td>
                        <td>${ticket.status_name}</td>
                        <td>${ticket.caller || '-'}</td>
                        <td>${ticket.phone || '-'}</td>
                        <td>${ticket.address || '-'}</td>
                        <td>${ticket.notes || '-'}</td>
                    `;
                    tbody.appendChild(row);
                });
            }

            $('#ticketTable').DataTable({
                searching: true,
                paging: true,
                pageLength: 10,
                lengthChange: true,
                ordering: true,
            });
        }
    </script>

@endsection
@push('script-charts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch("{{ route('dashboard.chart.tickets-per-hour') }}")
                .then(response => response.json())
                .then(data => {
                    const options = {
                        chart: {
                            type: 'line',
                            height: 350,
                            toolbar: {
                                show: false
                            }
                        },
                        series: [{
                            name: 'Tickets',
                            data: data.series
                        }],
                        xaxis: {
                            categories: data.labels,
                            title: {
                                text: 'Jam'
                            }
                        },
                        yaxis: {
                            title: {
                                text: 'Jumlah Tiket'
                            }
                        },
                        stroke: {
                            curve: 'smooth'
                        },
                        tooltip: {
                            y: {
                                formatter: val => `${val} Tiket`
                            }
                        }
                    };

                    const ticketChart = new ApexCharts(document.querySelector("#ticketsPerHourChart"), options);
                    ticketChart.render();
                });
        });
    </script>

    <script>
        let callStatusChart;

        function fetchCallStatusChart() {
            fetch(`/dashboard/call-status-chart-data`)
                .then(response => response.json())
                .then(data => {
                    const options = {
                        chart: {
                            type: 'line',
                            height: 400,
                            toolbar: {
                                show: false
                            }
                        },
                        series: data.series.map(item => {
                            let translatedName = item.name;
                            if (translatedName.toLowerCase() === 'abandonada') {
                                translatedName = 'Abandoned';
                            } else if (translatedName.toLowerCase() === 'terminada') {
                                translatedName = 'Completed';
                            }
                            return {
                                ...item,
                                name: translatedName
                            };
                        }),

                        xaxis: {
                            categories: data.categories,
                            title: {
                                text: 'Hour (Today)'
                            }
                        },
                        yaxis: {
                            title: {
                                text: 'Number of Calls'
                            }
                        },
                        stroke: {
                            curve: 'smooth',
                            width: 2
                        },
                        tooltip: {
                            y: {
                                formatter: function(val, {
                                    series,
                                    seriesIndex,
                                    w
                                }) {
                                    const status = w.config.series[seriesIndex].name;
                                    let translatedStatus = status;

                                    switch (status.toLowerCase()) {
                                        case 'abandonada':
                                            translatedStatus = 'Abandoned';
                                            break;
                                        case 'terminada':
                                            translatedStatus = 'Completed';
                                            break;
                                    }

                                    return `${val} calls (${translatedStatus})`;
                                }
                            }
                        },
                        colors: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1']
                    };

                    if (callStatusChart) {
                        callStatusChart.updateOptions(options);
                    } else {
                        callStatusChart = new ApexCharts(document.querySelector("#callStatusChart"), options);
                        callStatusChart.render();
                    }
                });
        }

        document.addEventListener("DOMContentLoaded", () => {
            fetchCallStatusChart();
        });
    </script>
@endpush
@push('scripts')
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush

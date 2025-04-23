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
    @php
        use Carbon\Carbon;

        // Menentukan tanggal default (awal bulan dan hari ini)
        $defaultStart = Carbon::now()->startOfMonth()->format('d/m/Y');
        $defaultEnd = Carbon::now()->format('d/m/Y');

        // Mendapatkan tanggal yang dipilih (dari query string) atau menggunakan tanggal default
        $selectedRange = request('date_range') ?? "$defaultStart to $defaultEnd";
    @endphp

    <!-- Form Date Range -->
    <div class="d-flex align-items-center flex-wrap py-2">
        <form id="date-range-form" method="GET" class="d-flex justify-content-between mb-5 w-100">
            <div class="d-flex align-items-center gap-3 w-100 flex-wrap justify-content-between">
                <!-- Input Date Picker -->
                <input type="text" id="kt_daterangepicker_1" name="date_range"
                    class="form-control form-control-solid w-100 w-md-250px" placeholder="Select Date Range"
                    value="{{ $selectedRange }}">

                <div class="d-flex gap-3 mt-2 mt-md-0">
                    <button type="submit" class="btn btn-primary">Apply</button>
                    <a href="{{ url()->current() }}" class="btn btn-light">Reset</a>
                </div>
            </div>
        </form>
    </div>

@endsection
@section('content')
    <style>
        .highcharts-figure,
        .highcharts-data-table table {
            min-width: 400px;
            max-width: 700px;
            margin: 2em auto;
        }

        .highcharts-data-table table {
            font-family: Verdana, sans-serif;
            border-collapse: collapse;
            border: 1px solid #ebebeb;
            margin: 10px auto;
            text-align: center;
            width: 100%;
            max-width: 700px;
        }

        .highcharts-data-table caption {
            padding: 1em 0;
            font-size: 1.2em;
            color: #555;
        }

        .highcharts-description {
            margin: 0.3rem 10px;
        }


        .summary-card {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
        }

        .summary-card-body {
            padding: 20px;
            color: white;
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .summary-data-section {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 15px;
        }

        .summary-data-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .summary-data-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .summary-data-text {
            flex: 1;
        }

        .summary-data-label {
            font-size: clamp(1rem, 1.5vw, 1.2rem);
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2px;
            font-weight: 500;
        }

        .summary-data-value {
            font-size: clamp(1.5rem, 2vw, 1.75rem);
            font-weight: 700;
            color: #ffffff;
        }

        .summary-speedometer-section {
            flex: 0 0 400px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #summary-speedometer-chart {
            max-height: 350px;
            max-width: 400px;
        }

        @media (max-width: 991.98px) {
            .summary-card {
                max-width: 800px;
            }

            .summary-card-body {
                flex-direction: column;
                padding: 15px;
            }

            .summary-data-section {
                width: 100%;
                padding: 10px;
                flex-direction: row;
                flex-wrap: nowrap;
                gap: 10px;
                justify-content: space-between;
            }

            .summary-data-item {
                flex: 1;
                flex-direction: column;
                align-items: center;
                text-align: center;
                min-width: 100px;
            }

            .summary-data-text {
                flex: none;
            }

            .summary-data-label {
                font-size: 1rem;
            }

            .summary-data-value {
                font-size: 1.5rem;
            }

            .summary-speedometer-section {
                flex: 0 0 auto;
                width: 100%;
                padding-right: 0;
            }

            #summary-speedometer-chart {
                max-height: 300px;
                max-width: 350px;
            }
        }

        @media (max-width: 575.98px) {
            .summary-card {
                max-width: 100%;
            }

            .summary-data-section {
                flex-wrap: wrap;
                gap: 8px;
            }

            .summary-data-item {
                min-width: 45%;
            }

            .summary-data-label {
                font-size: 0.9rem;
            }

            .summary-data-value {
                font-size: 1.2rem;
            }

            .summary-data-icon {
                width: 35px;
                height: 35px;
                font-size: 1.2rem;
            }

            #summary-speedometer-chart {
                max-height: 250px;
                max-width: 300px;
            }
        }




        .incident-summary-card {
            background: #ffffff;
            border: none;
            border-radius: 10px;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 15px;
        }

        .incident-summary-card h3 {
            font-weight: 700;
            font-size: clamp(1.5rem, 2.5vw, 2rem);
            margin-bottom: 15px;
            color: #1e40af;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .incident-summary-grid {
            display: flex;
            flex-wrap: nowrap;
            gap: 10px;
            justify-content: space-between;
        }

        .incident-item {
            flex: 1;
            background: #f9fafb;
            border: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            text-align: center;
            padding: 10px;
            min-width: 120px;
        }

        .incident-item-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
        }

        .incident-item i {
            font-size: clamp(1.5rem, 3vw, 2rem);
        }

        .incident-item h3 {
            font-size: clamp(1.2rem, 2vw, 1.5rem);
            font-weight: 700;
            margin: 0;
            color: #1f2937;
        }

        .incident-item p {
            font-size: clamp(0.8rem, 1.5vw, 1rem);
            color: #6b7280;
            margin: 0;
        }

        @media (max-width: 1200px) {
            .incident-summary-card {
                max-width: 100%;
            }

            .incident-summary-grid {
                flex-wrap: wrap;
                gap: 8px;
            }

            .incident-item {
                min-width: 48%;
            }

            .incident-item h3 {
                font-size: 1.2rem;
            }

            .incident-item p {
                font-size: 0.85rem;
            }
        }

        @media (max-width: 767.98px) {
            .incident-item {
                min-width: 100%;
            }

            .incident-item h3 {
                font-size: 1.1rem;
            }

            .incident-item p {
                font-size: 0.8rem;
            }
        }
    </style>
    <div class="row g-3">
        <!-- Card SLA -->
        <div class="col-md-8 d-flex flex-column">
            <div class="summary-card text-white h-100">
                <div class="summary-card-body h-100">
                    <!-- Data Section (Left on Desktop, Top on Mobile) -->
                    <div class="summary-data-section">
                        <div class="summary-data-item">
                            <div class="summary-data-icon bg-white text-primary">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div class="summary-data-text">
                                <div class="summary-data-label">Total Panggilan</div>
                                <div class="summary-data-value" id="total-calls">0</div>
                            </div>
                        </div>
                        <div class="summary-data-item">
                            <div class="summary-data-icon bg-success">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div class="summary-data-text">
                                <div class="summary-data-label">Terjawab</div>
                                <div class="summary-data-value" id="answered">0</div>
                            </div>
                        </div>
                        <div class="summary-data-item">
                            <div class="summary-data-icon bg-danger">
                                <i class="bi bi-telephone-x-fill"></i>
                            </div>
                            <div class="summary-data-text">
                                <div class="summary-data-label">Terlewat</div>
                                <div class="summary-data-value" id="abandoned">0</div>
                            </div>
                        </div>
                        <div class="summary-data-item">
                            <div class="summary-data-icon bg-white text-primary">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div class="summary-data-text">
                                <div class="summary-data-label">Avg Durasi</div>
                                <div class="summary-data-value" id="avg-call-duration">00:00:00</div>
                            </div>
                        </div>
                        <div class="summary-data-item">
                            <div class="summary-data-icon bg-white text-primary">
                                <i class="bi bi-clock"></i>
                            </div>
                            <div class="summary-data-text">
                                <div class="summary-data-label">Total Durasi</div>
                                <div class="summary-data-value" id="total-call-duration">00:00:00</div>
                            </div>
                        </div>
                    </div>
                    <!-- Speedometer Section (Right on Desktop, Bottom on Mobile) -->
                    <div class="summary-speedometer-section">
                        <div id="summary-speedometer-chart" style="width: 360px; height: 100%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 d-flex flex-column">
            <div class="card card-flush h-100">
                <div class="card-header py-7">
                    <div class="m-0">
                        <div class="d-flex align-items-center mb-2">
                            <span class="fs-2hx fw-bold text-gray-800 me-2 lh-1 ls-n2" id="total-calls-service"></span>
                        </div>
                        <span class="fs-6 fw-semibold text-gray-500">Ringkasan Insiden</span>
                    </div>
                </div>
                <div class="card-body card-body d-flex justify-content-between flex-column pt-3">
                    <div class="d-flex flex-stack">
                        <img src="https://cdn-icons-png.flaticon.com/512/7235/7235497.png" class="me-4 w-30px"
                            style="border-radius: 4px" alt="">
                        <div class="d-flex align-items-center flex-stack flex-wrap flex-row-fluid d-grid gap-2">
                            <div class="me-5">
                                <a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Baru</a>
                                <span class="text-gray-500 fw-semibold fs-7 d-block text-start ps-0">Data Insiden
                                    Baru</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-gray-800 fw-bold fs-4 me-3" id="active-calls">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-3"></div>

                    <div class="d-flex flex-stack">
                        <img src="https://cdn-icons-png.flaticon.com/512/9789/9789278.png" class="me-4 w-30px"
                            style="border-radius: 4px" alt="">
                        <div class="d-flex align-items-center flex-stack flex-wrap flex-row-fluid d-grid gap-2">
                            <div class="me-5">
                                <a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Proses</a>
                                <span class="text-gray-500 fw-semibold fs-7 d-block text-start ps-0">Data Insiden
                                    Proses</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-gray-800 fw-bold fs-4 me-3" id="handling-calls">0</span>
                            </div>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-3"></div>

                    <div class="d-flex flex-stack">
                        <img src="https://cdn-icons-png.flaticon.com/512/6276/6276642.png" class="me-4 w-30px"
                            style="border-radius: 4px" alt="">
                        <div class="d-flex align-items-center flex-stack flex-wrap flex-row-fluid d-grid gap-2">
                            <div class="me-5">
                                <a href="#" class="text-gray-800 fw-bold text-hover-primary fs-6">Selesai</a>
                                <span class="text-gray-500 fw-semibold fs-7 d-block text-start ps-0">Data Insiden
                                    Selesai</span>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="text-gray-800 fw-bold fs-4 me-3" id="closed-calls">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <div class="card incident-summary-card text-dark">
                <div class="mb-3">
                    <h4 class="fw-bold d-flex align-items-center gap-2 mb-0 text-primary">
                        <i class="bi bi-graph-up-arrow"></i>
                        Tipe Laporan
                    </h4>
                </div>
                <div class="incident-summary-grid">
                    <div class="incident-item">
                        <div class="incident-item-content">
                            <i class="bi bi-person-check" style="color: #ec4899;"></i>
                            <h3 id="normal-reports" class="fw-bold">0</h3>
                            <p class="text-muted">Normal</p>
                        </div>
                    </div>
                    <!-- Prank -->
                    <div class="incident-item">
                        <div class="incident-item-content">
                            <i class="bi bi-emoji-angry text-danger"></i>
                            <h3 id="prank-reports" class="fw-bold">0</h3>
                            <p class="text-muted">Prank</p>
                        </div>
                    </div>
                    <!-- Ghost -->
                    <div class="incident-item">
                        <div class="incident-item-content">
                            <i class="bi bi-incognito text-dark"></i>
                            <h3 id="ghost-reports" class="fw-bold">0</h3>
                            <p class="text-muted">Ghost</p>
                        </div>
                    </div>
                    <!-- Info -->
                    <div class="incident-item">
                        <div class="incident-item-content">
                            <i class="bi bi-info-circle-fill text-info"></i>
                            <h3 id="info-reports" class="fw-bold">0</h3>
                            <p class="text-muted">Info</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row mt-5">
        <div class="col-xl-6 mb-5 mb-xl-10">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Top 5 Insiden Berdasarkan Kategori
                        </span>
                    </h3>
                </div>
                <div class="card-body d-flex justify-content-between flex-column py-3">
                    <div class="table-responsive mb-n2">
                        <table class="table table-row-dashed gs-0 gy-4" id="top-area-table">
                            <thead>
                                <tr class="fs-7 fw-bold border-0 text-gray-500">
                                    <th class="min-w-300px">Kategori</th>
                                    <th class="min-w-100px">Total</th>
                                </tr>
                            </thead>
                            <tbody id="tb-top-categories">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-xl-6 mb-5 mb-xl-10">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Top 5 Insiden Berdasarkan Wilayah
                        </span>
                    </h3>
                </div>
                <div class="card-body d-flex justify-content-between flex-column py-3">
                    <div class="table-responsive mb-n2">
                        <table class="table table-row-dashed gs-0 gy-4" id="top-area-table">
                            <thead>
                                <tr class="fs-7 fw-bold border-0 text-gray-500">
                                    <th class="min-w-300px">Wilayah</th>
                                    <th class="min-w-100px">Total</th>
                                </tr>
                            </thead>
                            <tbody id="tb-top-areas">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-flush h-xl-100">
                <div class="card-header pt-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Online Departments</span>
                        <span class="text-gray-500 pt-1 fw-semibold fs-6">Live status & stats</span>
                    </h3>
                    <div class="card-toolbar d-flex gap-2">
                        <button id="toggleShowBtn" class="btn btn-sm btn-light">Show All</button>
                        <button class="btn btn-sm btn-icon btn-light" data-bs-toggle="modal"
                            data-bs-target="#fullScreenModal">
                            <i class="bi bi-arrows-fullscreen fs-4"></i>
                        </button>

                    </div>
                </div>

                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table class="table table-row-dashed align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fs-7 fw-bold border-0 text-gray-500">
                                    <th>Dinas</th>
                                    <th>Login</th>
                                    <th>L3 Online</th>
                                    <th>L3 Offline</th>
                                    <th>Aktif</th>
                                    <th>Handling</th>
                                    <th>Verifikasi</th>
                                    <th>Selesai</th>
                                    <th>SL%</th>
                                </tr>
                            </thead>
                            <tbody id="department-table-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Full Screen Modal -->
    <div class="modal fade" id="fullScreenModal" tabindex="-1" aria-labelledby="fullScreenModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fullScreenModalLabel">Online Departments (Full View)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-row-dashed align-middle gs-0 gy-4">
                            <thead>
                                <tr class="fs-7 fw-bold border-0 text-gray-500">
                                    <th>Dinas</th>
                                    <th>Login</th>
                                    <th>L3 Online</th>
                                    <th>L3 Offline</th>
                                    <th>Aktif</th>
                                    <th>Handling</th>
                                    <th>Verifikasi</th>
                                    <th>Selesai</th>
                                    <th>SL%</th>
                                </tr>
                            </thead>
                            <tbody id="modal-table-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function fetchSummaryData() {
                const dateRange = document.getElementById("kt_daterangepicker_1")?.value || '';

                axios.get('/backstreet/wallboard/get-summary-all', {
                        params: {
                            date_range: dateRange
                        }
                    })
                    .then(function(response) {
                        var data = response.data.data;
                        document.getElementById("answered").textContent = data.answer;
                        document.getElementById("abandoned").textContent = data.abandon;
                        document.getElementById("total-calls").textContent = data.total_call;
                        document.getElementById("avg-call-duration").textContent = data.avg_call_duration;
                        document.getElementById("total-call-duration").textContent = data.total_call_duration;

                        Highcharts.chart('summary-speedometer-chart', {
                            chart: {
                                type: 'gauge',
                                backgroundColor: null,
                                plotBackgroundColor: null,
                                plotBorderWidth: 0,
                                plotShadow: false,
                                height: '100%'
                            },
                            title: {
                                text: 'Speedometer SLA',
                                style: {
                                    color: '#ffffff',
                                    fontSize: '16px'
                                }
                            },
                            pane: {
                                startAngle: -90,
                                endAngle: 90,
                                background: null,
                                center: ['50%', '75%'],
                                size: '120%'
                            },
                            yAxis: {
                                min: 0,
                                max: 100,
                                tickPixelInterval: 72,
                                tickPosition: 'inside',
                                tickLength: 20,
                                tickWidth: 2,
                                minorTickInterval: null,
                                labels: {
                                    distance: 20,
                                    style: {
                                        fontSize: '16px',
                                        color: '#ffffff'
                                    }
                                },
                                lineWidth: 0,
                                plotBands: [{
                                        from: 0,
                                        to: 20,
                                        color: 'rgb(173, 216, 230)',
                                        thickness: 20
                                    },
                                    {
                                        from: 20,
                                        to: 40,
                                        color: 'rgb(100, 149, 237)',
                                        thickness: 20
                                    },
                                    {
                                        from: 40,
                                        to: 60,
                                        color: 'rgb(70, 130, 180)',
                                        thickness: 20
                                    },
                                    {
                                        from: 60,
                                        to: 80,
                                        color: 'rgb(65, 105, 225)',
                                        thickness: 20
                                    },
                                    {
                                        from: 80,
                                        to: 100,
                                        color: 'rgb(0, 0, 139)',
                                        thickness: 20
                                    }
                                ]
                            },
                            series: [{
                                name: 'SLA',
                                data: [data.kpi_call],
                                tooltip: {
                                    valueSuffix: ' %',
                                    backgroundColor: '#00796B',
                                    style: {
                                        color: '#ffffff'
                                    }
                                },
                                dataLabels: {
                                    format: '<div style="text-align:center;"><span style="font-size:20px; color: white;">{y} %</span></div>',
                                    useHTML: true,
                                    y: 50
                                },
                                dial: {
                                    radius: '80%',
                                    backgroundColor: '#ffffff',
                                    baseWidth: 12,
                                    baseLength: '0%',
                                    rearLength: '0%'
                                },
                                pivot: {
                                    backgroundColor: '#ffffff',
                                    radius: 6
                                }
                            }],
                            credits: {
                                enabled: false
                            },
                            navigation: {
                                buttonOptions: {
                                    enabled: false
                                }
                            }
                        });
                    })
                    .catch(function(error) {
                        console.log("Error fetching data", error);
                    });
            }

            fetchSummaryData();
            const form = document.getElementById("date-range-form");
            form.addEventListener("submit", function(e) {
                e.preventDefault();
                fetchSummaryData();
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            function fetchSummaryInsident() {
                const dateRange = document.getElementById("kt_daterangepicker_1")?.value || '';

                axios.get('/backstreet/wallboard/get-summary-insident', {
                        params: {
                            date_range: dateRange
                        }
                    })
                    .then(function(response) {
                        var data = response.data.data;

                        var totalCalls = document.getElementById("total-calls-service");
                        if (totalCalls) totalCalls.textContent = data.total || 0;

                        var activeCalls = document.getElementById("active-calls");
                        if (activeCalls) activeCalls.textContent = data.active || 0;

                        var handlingCalls = document.getElementById("handling-calls");
                        if (handlingCalls) handlingCalls.textContent = data.handling || 0;

                        var closedCalls = document.getElementById("closed-calls");
                        if (closedCalls) closedCalls.textContent = data.closed || 0;

                        var normalReports = document.getElementById("normal-reports");
                        if (normalReports) normalReports.textContent = data.type_laporan.normal || 0;

                        var prankReports = document.getElementById("prank-reports");
                        if (prankReports) prankReports.textContent = data.type_laporan.prank || 0;

                        var ghostReports = document.getElementById("ghost-reports");
                        if (ghostReports) ghostReports.textContent = data.type_laporan.ghost || 0;

                        var infoReports = document.getElementById("info-reports");
                        if (infoReports) infoReports.textContent = data.type_laporan.info || 0;

                        var serviceLevel = document.getElementById("service-level");
                        if (serviceLevel) {
                            serviceLevel.textContent = data.service_level ? data.service_level + "%" : "0%";
                        }
                    })
                    .catch(function(error) {
                        console.log("Error fetching data", error);
                    });
            }

            fetchSummaryInsident();

            const form = document.getElementById("date-range-form");
            form.addEventListener("submit", function(e) {
                e.preventDefault();
                fetchSummaryInsident();
            });
        });
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function fetchTopArea() {
                const dateRange = document.getElementById("kt_daterangepicker_1")?.value || '';

                axios.get('/backstreet/wallboard/get-top-area', {
                        params: {
                            date_range: dateRange
                        }
                    })
                    .then(function(response) {
                        const data = response.data.data;
                        const tableBody = document.getElementById('tb-top-areas');
                        tableBody.innerHTML = "";
                        data.forEach(function(item) {
                            const row = document.createElement('tr');
                            const nameCell = document.createElement('td');
                            nameCell.innerHTML =
                                `<a href="#" class="text-gray-600 fw-bold text-hover-primary mb-1 fs-6">${item.name}</a>`;
                            row.appendChild(nameCell);
                            const totalCell = document.createElement('td');
                            totalCell.classList.add('d-flex', 'align-items-center', 'border-0');
                            totalCell.innerHTML = `
                        <span class="fw-bold text-gray-800 fs-6 me-3">${item.total}</span>
                        <div class="progress rounded-start-0" style="width: 100px; height: 10px;">
                            <div class="progress-bar bg-success m-0" role="progressbar"
                                style="width:${Math.min(item.total * 6, 100)}px" 
                                aria-valuenow="${item.total * 6}" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    `;
                            row.appendChild(totalCell);

                            // Menambahkan baris ke dalam tabel
                            tableBody.appendChild(row);
                        });
                    })
                    .catch(function(error) {
                        console.log("Error fetching data", error);
                    });
            }
            fetchTopArea();
            const form = document.getElementById('date-range-form');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                fetchTopArea();
            });
        });
    </script>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function fetchTopCategories() {
                const dateRange = document.getElementById("kt_daterangepicker_1")?.value || '';
                axios.get('/backstreet/wallboard/get-top-categories', {
                        params: {
                            date_range: dateRange
                        }
                    })
                    .then(function(response) {
                        const data = response.data.data;
                        const tableBody = document.getElementById('tb-top-categories');
                        tableBody.innerHTML = "";

                        data.forEach(function(item) {
                            const row = document.createElement('tr');

                            const nameCell = document.createElement('td');
                            nameCell.innerHTML = `
                                <a href="#" class="text-gray-600 fw-bold text-hover-primary mb-1 fs-6" 
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="${item.name}">
                                    ${item.name.length > 30 ? item.name.substring(0, 30) + '...' : item.name}
                                </a>`;
                            row.appendChild(nameCell);

                            const totalCell = document.createElement('td');
                            totalCell.classList.add('d-flex', 'align-items-center', 'border-0');
                            totalCell.innerHTML = `
                                <span class="fw-bold text-gray-800 fs-6 me-3">${item.total}</span>
                                <div class="progress rounded-start-0" style="max-width: 150px; width: 100%;">
                                    <div class="progress-bar bg-success m-0" role="progressbar"
                                        style="height: 8px; width: ${Math.min(item.total * 2, 150)}px" 
                                        aria-valuenow="${item.total * 2}" aria-valuemin="0" aria-valuemax="150">
                                    </div>
                                </div>`;
                            row.appendChild(totalCell);

                            tableBody.appendChild(row);
                        });

                        var tooltipTriggerList = Array.from(document.querySelectorAll(
                            '[data-bs-toggle="tooltip"]'));
                        tooltipTriggerList.forEach(function(tooltipEl) {
                            new bootstrap.Tooltip(tooltipEl);
                        });
                    })
                    .catch(function(error) {
                        console.log("Error fetching data", error);
                    });
            }

            fetchTopCategories();
            const form = document.getElementById('date-range-form');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                fetchTopCategories();
            });
        });
    </script>

    <script>
        let allDepartments = [];
        let showingAll = false;
        async function loadDepartmentTable() {
            try {
                const dateRange = document.getElementById("kt_daterangepicker_1")?.value || '';
                const response = await axios.get('/backstreet/wallboard/get-online-department', {
                    params: {
                        ...(dateRange && {
                            date_range: dateRange
                        }) // hanya kirim kalau ada date range
                    }
                });

                const [array1, object2] = response.data;
                allDepartments = [...array1, ...Object.values(object2)];

                renderMainTable(showingAll ? allDepartments : allDepartments.slice(0, 10));
                renderModalTable(allDepartments);
            } catch (err) {
                console.error('Failed loading departments:', err);
            }
        }

        function renderMainTable(data) {
            const tbody = document.getElementById('department-table-body');
            tbody.innerHTML = '';
            data.forEach(dept => {
                tbody.innerHTML += `
            <tr>
                <td><a href="#" class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">${dept.dinas}</a></td>
                <td>${dept.login ? '✅' : '❌'}</td>
                <td>${dept.l3online}</td>
                <td>${dept.l3offline}</td>
                <td>${dept.aktif}</td>
                <td>${dept.handling}</td>
                <td>${dept.verifikasi}</td>
                <td>${dept.selesai}</td>
                <td>${dept.service_level}%</td>
            </tr>`;
            });
        }

        function renderModalTable(data) {
            const modalBody = document.getElementById('modal-table-body');
            modalBody.innerHTML = '';
            data.forEach(dept => {
                modalBody.innerHTML += `
            <tr>
                <td>${dept.dinas}</td>
                <td>${dept.login ? '✅' : '❌'}</td>
                <td>${dept.l3online}</td>
                <td>${dept.l3offline}</td>
                <td>${dept.aktif}</td>
                <td>${dept.handling}</td>
                <td>${dept.verifikasi}</td>
                <td>${dept.selesai}</td>
                <td>${dept.service_level}%</td>
            </tr>`;
            });
        }

        document.getElementById('toggleShowBtn').addEventListener('click', () => {
            showingAll = !showingAll;
            renderMainTable(showingAll ? allDepartments : allDepartments.slice(0, 10));
            document.getElementById('toggleShowBtn').textContent = showingAll ? 'Show Less' : 'Show All';
        });

        loadDepartmentTable();
        document.getElementById('date-range-form')?.addEventListener('submit', function(e) {
            e.preventDefault();
            loadDepartmentTable();
        });
    </script>
@endpush
@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#kt_daterangepicker_1').daterangepicker({
                locale: {
                    format: 'DD/MM/YYYY',
                    separator: ' to '
                },
                startDate: '{{ $defaultStart }}',
                endDate: '{{ $defaultEnd }}',
                autoUpdateInput: false
            });

            $('#kt_daterangepicker_1').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' to ' + picker.endDate.format(
                    'DD/MM/YYYY'));
            });
            const form = document.getElementById('date-range-form');
            form.addEventListener('submit', function(e) {
                const dateRangeInput = document.getElementById('kt_daterangepicker_1');
                const dateRange = dateRangeInput.value;

                if (!dateRange) {
                    e.preventDefault();
                    alert("Please select a valid date range.");
                }
            });
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

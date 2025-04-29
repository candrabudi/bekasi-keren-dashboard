@extends('layouts.app')
@section('title', 'Dashboard Omni Channel')
@section('page-title', 'Dashboard Omni Channel')
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
            Omni Channel
        </li>
    </ul>
@endsection
@push('styles')
    <style>
        .card-custom {
            border-left: 5px solid;
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            min-height: 100px;
            width: 100%;
            display: flex;
            align-items: center;
        }

        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .card-custom.blue {
            border-left-color: #007bff;
        }

        .card-custom.orange {
            border-left-color: #ff9500;
        }

        .card-custom.cyan {
            border-left-color: #17a2b8;
        }

        .card-custom .icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            transition: background-color 0.3s ease;
            flex-shrink: 0;
        }

        .card-custom .icon.blue-bg {
            background-color: #e7f1ff;
        }

        .card-custom .icon.orange-bg {
            background-color: #fff3e0;
        }

        .card-custom .icon.cyan-bg {
            background-color: #e6f7fa;
        }

        .card-custom .icon img {
            width: 22px;
            height: 22px;
        }

        .card-custom .title {
            font-size: 13px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            white-space: normal;
            overflow-wrap: break-word;
        }

        .card-custom .value {
            font-size: 26px;
            font-weight: 600;
            color: #343a40;
        }

        .container {
            max-width: 1200px;
        }

        .col-md-3 {
            display: flex;
            justify-content: center;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <!-- Active Agents -->
        <div class="col-md-3">
            <div class="card-custom blue d-flex align-items-center">
                <div class="icon blue-bg">
                    <img src="https://cdn-icons-png.flaticon.com/512/9095/9095819.png" alt="Agent Icon">
                </div>
                <div>
                    <div class="title">Active Agents</div>
                    <div class="value"><span id="active-agent">0</span> / <span id="max-agent">0</span></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-custom blue d-flex align-items-center">
                <div class="icon blue-bg">
                    <img src="https://cdn-icons-png.flaticon.com/512/811/811476.png" alt="Chat Icon">
                </div>
                <div>
                    <div class="title">Avg 1st Reply Time</div>
                    <div class="value">00:00:00</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-custom blue d-flex align-items-center">
                <div class="icon blue-bg">
                    <img src="https://cdn-icons-png.flaticon.com/512/15480/15480282.png" alt="Reply Icon">
                </div>
                <div>
                    <div class="title">Avg Reply Time</div>
                    <div class="value">00:00:00</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-custom blue d-flex align-items-center">
                <div class="icon blue-bg">
                    <img src="https://cdn-icons-png.flaticon.com/512/4285/4285648.png" alt="Clock Icon">
                </div>
                <div>
                    <div class="title">Avg Duration per Conversation</div>
                    <div class="value">00:00:00</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="card-custom orange d-flex align-items-center">
                <div class="icon orange-bg">
                    <img src="https://cdn-icons-png.flaticon.com/512/190/190406.png" alt="Cross Icon">
                </div>
                <div>
                    <div class="title">Unassigned Conversations</div>
                    <div class="value" id="unassigned-value">0</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-custom blue d-flex align-items-center">
                <div class="icon blue-bg">
                    <img src="https://cdn-icons-png.flaticon.com/512/2972/2972543.png" alt="Hourglass Icon">
                </div>
                <div>
                    <div class="title">Active Conversations</div>
                    <div class="value" id="active-value">0</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-custom cyan d-flex align-items-center">
                <div class="icon cyan-bg">
                    <img src="https://cdn-icons-png.flaticon.com/512/190/190411.png" alt="Check Icon">
                </div>
                <div>
                    <div class="title">Completed Conversations</div>
                    <div class="value" id="completed-value">0</div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card-custom blue d-flex align-items-center">
                <div class="icon blue-bg">
                    <img src="https://cdn-icons-png.flaticon.com/512/14862/14862551.png" alt="Smile Icon">
                </div>
                <div>
                    <div class="title">Avg CSAT</div>
                    <div class="value"><span id="avg-csat">0.00</span> / 5</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row align-items-stretch">
        <div class="col-md-6 d-flex">
            <div class="card card-flush w-100">
                <!-- Card Header -->
                <div class="card-header pt-5 d-flex justify-content-between align-items-start">
                    <h3 class="card-title flex-column">
                        <span class="card-label fw-bold text-gray-800">WhatsApp Conversation Usage</span>
                        <span id="total-conversations" class="text-gray-500 mt-1 fw-semibold fs-6"></span>
                    </h3>

                    <!-- Date Range Picker -->
                    <div class="card-toolbar">
                        <div id="wa-date-range" class="btn btn-flex btn-sm btn-light d-flex align-items-center px-4">
                            <div class="text-gray-600 fw-bold" id="wa-range-text">
                                Loading date range...
                            </div>
                            <i class="ki-duotone ki-calendar-8 text-gray-500 lh-0 fs-2 ms-2 me-0">
                                <span class="path1"></span><span class="path2"></span>
                                <span class="path3"></span><span class="path4"></span>
                                <span class="path5"></span><span class="path6"></span>
                            </i>
                        </div>
                    </div>

                </div>

                <!-- Card Body -->
                <div id="wa-usage-body" class="card-body">

                </div>
            </div>
        </div>


        <div class="col-md-6 d-flex">
            <div class="card shadow-sm border-0 w-100">
                <div class="card-header pt-5 d-flex justify-content-between align-items-start">
                    <h3 class="card-title flex-column">
                        <span class="card-label fw-bold text-gray-800">Hourly Conversations</span>
                        <span id="total-conversations" class="text-gray-500 mt-1 fw-semibold fs-6"></span>
                    </h3>

                    <div class="card-toolbar">
                        <div id="conversations-date-range"
                            class="btn btn-flex btn-sm btn-light d-flex align-items-center px-4">
                            <div class="text-gray-600 fw-bold" id="conversations-range-text">Loading date range...</div>
                            <i class="ki-duotone ki-calendar-8 text-gray-500 lh-0 fs-2 ms-2 me-0">
                                <span class="path1"></span><span class="path2"></span>
                                <span class="path3"></span><span class="path4"></span>
                                <span class="path5"></span><span class="path6"></span>
                            </i>
                        </div>
                    </div>

                </div>
                <div class="card-body d-flex flex-column">
                    <div class="flex-grow-1" style="position: relative;">
                        <canvas id="conversationsChart" style="max-height: 350px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row g-5 g-xl-10 mt-3">
        <div class="col-xxl-12">

            <div class="card card-flush h-xl-100">
                <div class="card-header pt-7">
                    <h4 class="card-title align-items-start flex-column">
                        <span class="card-label fw-bold text-gray-800">Agent Performance</span>
                    </h4>
                    <div class="d-flex justify-content-end mb-4">
                        <div id="agent-date-range" class="btn btn-light btn-sm px-4 d-flex align-items-center">
                            <div class="text-gray-600 fw-bold" id="agent-range-text">Loading date range...</div>
                            <i class="ki-duotone ki-calendar-8 text-gray-500 fs-2 ms-2 me-0"></i>
                        </div>
                    </div>

                </div>

                <div class="card-body py-3">
                    <div class="table-responsive">
                        <table class="table table-row-dashed align-middle gs-0 gy-4 my-0">
                            <thead>
                                <tr class="border-bottom-0">
                                    <th class="p-0 w-50px"></th>
                                    <th class="p-0 min-w-175px">Agent</th>
                                    <th class="p-0 min-w-175px">Status</th>
                                    <th class="p-0 min-w-150px">Active Chats</th>
                                    <th class="p-0 min-w-150px">Completed Chats</th>
                                    <th class="p-0 min-w-50px">Avg 1st Reply</th>
                                    <th class="p-0 min-w-50px">Avg Reply</th>
                                    <th class="p-0 min-w-50px">Avg Duration</th>
                                </tr>
                            </thead>
                            <tbody id="agent-performance-body">
                                <!-- Data will be injected here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-charts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            axios.get('/backstreet/dashboards/omni-channel/active-agent')
                .then(function(response) {
                    const data = response.data;
                    document.getElementById('active-agent').innerText = data.active_agent
                        .active;
                    document.getElementById('max-agent').innerText = data.active_agent.max_agent;
                })
                .catch(function(error) {
                    console.error('Error fetching data:', error);
                });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rangeText = document.getElementById("conversations-range-text");
            let start = moment().startOf('month');
            let end = moment().endOf('month');

            function updateRangeDisplay(start, end) {
                rangeText.textContent = `${start.format('MMM D, YYYY')} - ${end.format('MMM D, YYYY')}`;
            }

            function fetchConversationsSummary(startDate, endDate) {
                axios.get('/backstreet/dashboards/omni-channel/conversations-summary', {
                    params: {
                        start_date: startDate,
                        end_date: endDate
                    }
                }).then(function(response) {
                    const data = response.data;

                    document.getElementById('avg-csat').innerText = data.avg_csat;
                    document.getElementById('unassigned-value').innerText = data.conversations_status
                        .unassigned;
                    document.getElementById('active-value').innerText = data.conversations_status.active;
                    document.getElementById('completed-value').innerText = data.conversations_status
                        .completed;

                    const ctx = document.getElementById('conversationsChart').getContext('2d');
                    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                    gradient.addColorStop(0, 'rgba(0, 123, 255, 0.3)');
                    gradient.addColorStop(1, 'rgba(0, 123, 255, 0)');

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.conversations_data.chart_data.labels,
                            datasets: [{
                                label: 'Conversations',
                                data: data.conversations_data.chart_data.datasets
                                    .conversations,
                                fill: true,
                                backgroundColor: gradient,
                                borderColor: '#007bff',
                                borderWidth: 2,
                                pointRadius: 0,
                                tension: 0.4,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                                    titleColor: '#fff',
                                    bodyColor: '#ddd',
                                }
                            },
                            scales: {
                                x: {
                                    ticks: {
                                        color: '#999',
                                        font: {
                                            size: 12
                                        }
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        color: '#999',
                                        font: {
                                            size: 12
                                        },
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });

                }).catch(function(error) {
                    console.error('Error fetching data:', error);
                });
            }

            $('#conversations-date-range').daterangepicker({
                startDate: start,
                endDate: end,
                opens: 'left',
                ranges: {
                    'Today': [moment(), moment()],
                    'This Week': [moment().startOf('week'), moment().endOf('week')],
                    'Last Week': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week')
                        .endOf('week')
                    ],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                }
            }, function(start, end) {
                updateRangeDisplay(start, end);
                fetchConversationsSummary(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            });

            // Initial Load
            updateRangeDisplay(start, end);
            fetchConversationsSummary(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const rangeText = document.getElementById("wa-range-text");
            let start = moment().startOf('month');
            let end = moment().endOf('month');

            function updateRangeDisplay(start, end) {
                rangeText.textContent = `${start.format('MMM D, YYYY')} - ${end.format('MMM D, YYYY')}`;
            }

            function fetchWhatsAppUsage(startDate, endDate) {
                axios.get('/backstreet/dashboards/omni-channel/whatsapp-usage', {
                    params: {
                        start_date: startDate,
                        end_date: endDate
                    }
                }).then(response => {
                    const data = response.data.wa_conversation_usage;
                    const total = Object.values(data).reduce((a, b) => a + b, 0);
                    document.getElementById("total-conversations").innerText =
                        `Total: ${total} conversations`;

                    const usageItems = {
                        user_initiated: {
                            label: "User Initiated",
                            icon: "ki-user",
                            color: "info"
                        },
                        business_initiated: {
                            label: "Business Initiated",
                            icon: "ki-briefcase",
                            color: "warning"
                        },
                        service: {
                            label: "Service",
                            icon: "ki-gear",
                            color: "success"
                        },
                        marketing: {
                            label: "Marketing",
                            icon: "ki-megaphone",
                            color: "danger"
                        },
                        utility: {
                            label: "Utility",
                            icon: "ki-clipboard",
                            color: "primary"
                        },
                        authentication: {
                            label: "Authentication",
                            icon: "ki-shield",
                            color: "dark"
                        }
                    };

                    const container = document.getElementById("wa-usage-body");
                    container.innerHTML = '';
                    const entries = Object.entries(data);

                    for (let i = 0; i < entries.length; i += 2) {
                        const [key1, value1] = entries[i];
                        const item1 = usageItems[key1];
                        const secondExists = i + 1 < entries.length;
                        let secondHTML = '';
                        if (secondExists) {
                            const [key2, value2] = entries[i + 1];
                            const item2 = usageItems[key2];
                            secondHTML = `
                        <div class="d-flex align-items-center me-5 w-50">
                            <div class="symbol symbol-40px me-3">
                                <span class="symbol-label bg-light-${item2.color}">
                                    <i class="ki-duotone ${item2.icon} fs-2x text-${item2.color}"></i>
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-800 fw-bold fs-6">${item2.label}</span>
                                <span class="fw-semibold fs-7 d-block text-gray-600">${value2} messages</span>
                            </div>
                        </div>`;
                        }

                        container.innerHTML += `
                    <div class="d-flex justify-content-between mb-4">
                        <div class="d-flex align-items-center me-5 w-50">
                            <div class="symbol symbol-40px me-3">
                                <span class="symbol-label bg-light-${item1.color}">
                                    <i class="ki-duotone ${item1.icon} fs-2x text-${item1.color}"></i>
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-800 fw-bold fs-6">${item1.label}</span>
                                <span class="fw-semibold fs-7 d-block text-gray-600">${value1} messages</span>
                            </div>
                        </div>
                        ${secondHTML}
                    </div>`;
                    }
                }).catch(error => {
                    console.error("Failed to fetch data:", error);
                });
            }

            // Date Range Picker Initialization
            $('#wa-date-range').daterangepicker({
                startDate: start,
                endDate: end,
                opens: 'left',
                ranges: {
                    'Today': [moment(), moment()],
                    'This Week': [moment().startOf('week'), moment().endOf('week')],
                    'Last Week': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week')
                        .endOf('week')
                    ],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')],
                    'Custom Range': [moment().subtract(29, 'days'), moment()]
                }
            }, function(start, end) {
                updateRangeDisplay(start, end);
                fetchWhatsAppUsage(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            });

            // Initial Load
            updateRangeDisplay(start, end);
            fetchWhatsAppUsage(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const agentRangeText = document.getElementById("agent-range-text");
            let start = moment().startOf('month');
            let end = moment().endOf('month');

            function updateRangeDisplay(start, end) {
                agentRangeText.textContent = `${start.format('MMM D, YYYY')} - ${end.format('MMM D, YYYY')}`;
            }

            function fetchAgentPerformance(startDate, endDate) {
                axios.get('/backstreet/dashboards/omni-channel/agent-performance', {
                        params: {
                            start_date: startDate,
                            end_date: endDate
                        }
                    })
                    .then(response => {
                        const users = response.data.users;
                        const tableBody = document.getElementById('agent-performance-body');
                        tableBody.innerHTML = '';

                        users.forEach(user => {
                            const row = document.createElement('tr');
                            const statusIcon = user.status_online === 'online' ?
                                'ki-circle fs-1x text-success' : 'ki-circle fs-1x text-muted';

                            row.innerHTML = `
                        <td>
                            <div class="symbol symbol-40px">
                                <span class="symbol-label bg-light-info">
                                    <i class="ki-duotone ki-abstract-24 fs-2x text-info"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                        </td>
                        <td class="ps-0">
                            <a href="#" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">${user.fullname || user.username}</a>
                            <span class="text-muted fw-semibold d-block fs-7">${user.status_online.charAt(0).toUpperCase() + user.status_online.slice(1)}</span>
                        </td>
                        <td>
                            <span class="text-gray-900 fw-bold d-block fs-6">${user.status_online === 'online' ? 'Online' : 'Offline'}</span>
                        </td>
                        <td>
                            <span class="text-gray-900 fw-bold d-block fs-6">${user.active_chat}</span>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Active Chats</span>
                        </td>
                        <td>
                            <span class="text-gray-900 fw-bold d-block fs-6">${user.completed_chat}</span>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Completed Chats</span>
                        </td>
                        <td>
                            <span class="text-gray-900 fw-bold d-block fs-6">${user.avg_1st_reply_time}</span>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Avg 1st Reply</span>
                        </td>
                        <td>
                            <span class="text-gray-900 fw-bold d-block fs-6">${user.avg_reply_time}</span>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Avg Reply</span>
                        </td>
                        <td>
                            <span class="text-gray-900 fw-bold d-block fs-6">${user.avg_duration_time}</span>
                            <span class="text-gray-500 fw-semibold d-block fs-7">Avg Duration</span>
                        </td>
                    `;
                            tableBody.appendChild(row);
                        });
                    })
                    .catch(error => {
                        console.error("Failed to fetch agent performance:", error);
                    });
            }

            $('#agent-date-range').daterangepicker({
                startDate: start,
                endDate: end,
                opens: 'left',
                ranges: {
                    'Today': [moment(), moment()],
                    'This Week': [moment().startOf('week'), moment().endOf('week')],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                }
            }, function(start, end) {
                updateRangeDisplay(start, end);
                fetchAgentPerformance(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            });

            // First load
            updateRangeDisplay(start, end);
            fetchAgentPerformance(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        });
    </script>
@endpush

@push('scripts')
@endpush

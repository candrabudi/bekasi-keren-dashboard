@extends('layouts.app')
@section('title', 'Call Center Map')
@section('page-title', 'Call Center Map')
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
            Maps
        </li>
    </ul>
@endsection
@section('content')
    <style>
        .card-elegant {
            background-color: #ffffff;
            border: none;
            border-radius: 1rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        }

        .map-controls {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 10;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 1rem;
            padding: 1rem;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            min-width: 250px;
        }

        .map-controls .btn {
            border-radius: 0.75rem;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }

        /* Sidebar Styles */
        .sidebar {
            position: absolute;
            top: 60px;
            right: 10px;
            width: 500px;
            height: 80%;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 1rem;
            overflow-y: auto;
            z-index: 20;
            display: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .sidebar-header {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .sidebar-body {
            max-height: 90%;
            overflow-y: auto;
        }

        .close-sidebar {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 18px;
            background: none;
            border: none;
            cursor: pointer;
        }

        /* Map Container */
        #map-container {
            position: relative;
            height: 1000px;
        }
    </style>

    <div class="card card-elegant">
        <div class="card-header bg-white border-0 d-flex align-items-center justify-content-between px-4 py-3">
            <h5 class="mb-0 text-primary">
                <i class="bi bi-map-fill me-2"></i>Distribusi Tiket Kota Bekasi
            </h5>
        </div>
        <div class="card-body p-0">
            <div id="map-container" class="position-relative" style="height: 1000px;">
                <div id="map" style="height: 100%; width: 100%;"></div>
                <div class="map-controls">
                    <button id="fullscreen-btn"
                        class="btn btn-outline-primary w-100 mb-3 d-flex align-items-center justify-content-center gap-2">
                        <i id="fullscreen-icon" class="bi bi-arrows-fullscreen"></i>
                        <span>Fullscreen</span>
                    </button>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 rounded-start"><i
                                class="bi bi-calendar-event"></i></span>
                        <input type="text" id="kt_daterangepicker_1" class="form-control border-start-0"
                            placeholder="Pilih Rentang Tanggal" readonly>
                    </div>
                </div>
                <div class="sidebar" id="district-sidebar">
                    <div class="sidebar-content">
                        <!-- Sidebar header -->
                        <div class="sidebar-header d-flex justify-content-between align-items-center px-4 py-3 bg-light border-bottom">
                            <h5 class="mb-0 text-primary font-weight-bold">Ticket Details</h5>
                            <button class="btn btn-icon btn-light btn-sm" onclick="closeSidebar()" aria-label="Close">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                
                        <!-- Sidebar body -->
                        <div class="sidebar-body px-4 py-3">
                            <div id="ticket-list" class="d-flex flex-column">
                                <!-- Tickets will be dynamically inserted here -->
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection
@push('script-charts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/ol@latest/dist/ol.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ol@latest/ol.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        let ticketData = [];
    
        const normalize = str => (str || '').toUpperCase().replace(/\s+/g, '');
    
        const getColor = (total) => {
            const max = Math.max(...ticketData.map(d => d.total));
            const min = Math.min(...ticketData.map(d => d.total));
            const ratio = (total - min) / (max - min || 1);
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
    
        map.removeControl(map.getControls().getArray().find(control => control instanceof ol.control.Zoom));
    
        const geojsonUrl = 'https://raw.githubusercontent.com/JfrAziz/indonesia-district/master/id32_jawa_barat/id3275_kota_bekasi/id3275_kota_bekasi.geojson';
    
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
                        font: 'bold 14px sans-serif',
                        overflow: true,
                        placement: 'point'
                    })
                });
            }
        });
    
        vectorSource.on('change', function() {
            if (vectorSource.getState() === 'ready') {
                const features = vectorSource.getFeatures();
                if (features.length > 0) {
                    map.getView().fit(vectorSource.getExtent(), {
                        padding: [20, 20, 20, 20],
                        maxZoom: 14
                    });
                }
            }
        });
    
        map.addLayer(vectorLayer);
    
        function openSidebar(districtName) {
            const sidebar = document.getElementById('district-sidebar');
            const ticketList = document.getElementById('ticket-list');
            ticketList.innerHTML = '';
    
            const match = ticketData.find(d => normalize(d.district) === normalize(districtName));
            if (match) {
                const tickets = match.tickets;
                tickets.forEach(ticket => {
                    const card = document.createElement('div');
                    card.classList.add('card', 'card-elegant', 'mb-3');
    
                    const cardBody = document.createElement('div');
                    cardBody.classList.add('card-body');
    
                    const header = document.createElement('h6');
                    header.classList.add('card-title', 'text-primary');
                    header.textContent = `Ticket ID: ${ticket.ticket}`;
    
                    const ticketDetails = document.createElement('p');
                    ticketDetails.classList.add('card-text');
                    ticketDetails.innerHTML = `
                        <strong>Address:</strong> ${ticket.address}<br>
                        <strong>Phone:</strong> ${ticket.phone}<br>
                        <strong>Created At:</strong> ${ticket.created_at}
                    `;
    
                    const status = document.createElement('span');
                    status.classList.add('badge');
                    switch (ticket.status_name) {
                        case 'Baru':
                            status.classList.add('bg-info');
                            break;
                        case 'Proses':
                            status.classList.add('bg-warning');
                            break;
                        case 'Selesai':
                            status.classList.add('bg-success');
                            break;
                        default:
                            status.classList.add('bg-secondary');
                    }
                    status.textContent = ticket.status_name;
    
                    cardBody.appendChild(header);
                    cardBody.appendChild(ticketDetails);
                    cardBody.appendChild(status);
                    card.appendChild(cardBody);
    
                    ticketList.appendChild(card);
                });
            } else {
                ticketList.innerHTML = '<div class="alert alert-info">No tickets found for this district.</div>';
            }
    
            sidebar.style.display = 'block';
        }
    
        function closeSidebar() {
            const sidebar = document.getElementById('district-sidebar');
            sidebar.style.display = 'none';
        }
    
        map.on('click', function(event) {
            const feature = map.forEachFeatureAtPixel(event.pixel, function(f) {
                return f;
            });
    
            if (feature) {
                const districtName = feature.get('district');
                openSidebar(districtName);
            }
        });
    
        function fetchTicketData(startDate, endDate) {
            axios.get(`/backstreet/call-center/ticket-distributions?start_date=${startDate}&end_date=${endDate}`)
                .then(response => {
                    ticketData = response.data;
                    vectorLayer.setStyle(function(feature) {
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
                                font: 'bold 14px sans-serif',
                                overflow: true,
                                placement: 'point'
                            })
                        });
                    });
                    vectorSource.refresh();
                })
                .catch(error => {
                    console.error('Gagal memuat data tiket:', error);
                });
        }
    
        $(function() {
            const startOfWeek = moment().startOf('week').format('YYYY-MM-DD');
            const endOfWeek = moment().endOf('week').format('YYYY-MM-DD');
    
            $('#kt_daterangepicker_1').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                },
                startDate: startOfWeek,
                endDate: endOfWeek,
                opens: 'left'
            }, function(start, end) {
                fetchTicketData(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            });
    
            fetchTicketData(startOfWeek, endOfWeek);
        });
    
        let loading = false;
    
        function loadMoreTickets(districtName) {
            if (loading) return;
            loading = true;
    
            setTimeout(() => {
                const ticketList = document.getElementById('ticket-list');
                const match = ticketData.find(d => normalize(d.district) === normalize(districtName));
                if (match) {
                    const tickets = match.tickets.slice(ticketList.children.length, ticketList.children.length + 5);
                    tickets.forEach(ticket => {
                        const card = document.createElement('div');
                        card.classList.add('card', 'card-elegant', 'mb-3');
                        card.innerHTML = `<div class="card-body">
                            <h6 class="card-title text-primary">Ticket ID: ${ticket.ticket}</h6>
                            <p><strong>Address:</strong> ${ticket.address}</p>
                            <p><strong>Phone:</strong> ${ticket.phone}</p>
                            <p><strong>Created At:</strong> ${ticket.created_at}</p>
                            <span class="badge bg-${ticket.status_name === 'Baru' ? 'info' : ticket.status_name === 'Proses' ? 'warning' : 'success'}">${ticket.status_name}</span>
                        </div>`;
                        ticketList.appendChild(card);
                    });
                }
                loading = false;
            }, 1000);
        }
    
        const sidebarBody = document.querySelector('.sidebar-body');
        sidebarBody.addEventListener('scroll', function() {
            if (sidebarBody.scrollTop + sidebarBody.clientHeight >= sidebarBody.scrollHeight) {
                loadMoreTickets('BANTARGEBANG');
            }
        });
    
        document.getElementById('fullscreen-btn').addEventListener('click', function() {
            const mapContainer = document.getElementById('map-container');
            if (!document.fullscreenElement) {
                mapContainer.requestFullscreen().catch(err => console.error(err));
            } else {
                document.exitFullscreen().catch(err => console.error(err));
            }
        });
    </script>
@endpush

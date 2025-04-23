@extends('layouts.app')
@section('page-title', 'Data Ticket ' . $ticket->ticket)
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
        <li class="breadcrumb-item">
            <span class="bullet bg-white opacity-75 w-5px h-2px"></span>
        </li>
        <li class="breadcrumb-item text-white opacity-75">
            Ticket {{ $ticket->ticket }}
        </li>
    </ul>
@endsection
@section('content')
    <div class="d-flex flex-column flex-lg-row">
        <!--begin::Sidebar-->
        <div class="flex-column flex-lg-row-auto w-lg-250px w-xl-350px mb-10">

            <!--begin::Card-->
            <div class="card mb-5 mb-xl-8">
                <!--begin::Card body-->
                <div class="card-body">
                    <!--begin::Summary-->


                    <!--begin::User Info-->
                    <div class="d-flex flex-center flex-column py-5">
                        <!--begin::Avatar-->
                        <div class="symbol symbol-100px symbol-circle mb-7">
                            <img src="../../../assets/media/avatars/300-6.jpg" alt="image">
                        </div>
                        <!--end::Avatar-->

                        <!--begin::Name-->
                        <p class="fs-3 text-gray-800 text-hover-primary fw-bold mb-3">
                            {{ $ticket->caller != '-' ? $ticket->caller : 'Unknown' }}
                        </p>
                        <div class="mb-9">
                            <div class="badge badge-lg badge-light-primary d-inline">{{ $ticket->phone }}</div>
                        </div>
                    </div>
                    <div class="d-flex flex-stack fs-4 py-3">
                        <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details"
                            role="button" aria-expanded="false" aria-controls="kt_user_view_details">
                            Details
                            <span class="ms-2 rotate-180">
                                <i class="ki-duotone ki-down fs-3"></i> </span>
                        </div>
                    </div>

                    <div class="separator"></div>

                    <div id="kt_user_view_details" class="collapse show">
                        <div class="pb-5 fs-6">
                            <div class="fw-bold mt-5">Category</div>
                            <div class="text-gray-600">{{ $ticket->category }}</div>
                            <div class="fw-bold mt-5">Status</div>
                            <div class="text-gray-600">{{ $ticket->status_name }}</div>
                            <div class="fw-bold mt-5">Call Type</div>
                            <div class="text-gray-600">{{ $ticket->call_type_name }}</div>
                            <div class="fw-bold mt-5">Created By</div>
                            <div class="text-gray-600">{{ $ticket->created_by }}</div>
                            <div class="fw-bold mt-5">Email</div>
                            <div class="fw-bold mt-5">Address</div>
                            <div class="text-gray-600">
                                @if ($ticket->address || $ticket->subdistrict || $ticket->district)
                                    {{ $ticket->address ?? '-' }},
                                    {{ $ticket->subdistrict ?? '-' }},
                                    {{ $ticket->district ?? '-' }}
                                @else
                                    <span class="text-danger">Address No Found</span>
                                @endif
                            </div>

                            <div class="fw-bold mt-5">Location</div>
                            <div class="text-gray-600">{{ $ticket->location ?? '-' }}</div>
                            <div class="fw-bold mt-5">Description</div>
                            <div class="text-gray-600">{{ $ticket->description }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-lg-row-fluid ms-lg-15">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active show" id="kt_user_view_overview_events_and_logs_tab" role="tabpanel">
                    <div class="card pt-4 mb-6 mb-xl-9">
                        <div class="card-header border-0">
                            <div class="card-title">
                                <h2>Department Ticket</h2>
                            </div>
                        </div>
                        <div class="card-body pt-0 pb-5">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed gy-5" id="kt_table_users_login_session">
                                    <thead class="border-bottom border-gray-200 fs-7 fw-bold">
                                        <tr class="text-start text-muted text-uppercase gs-0">
                                            <th class="min-w-100px">Report ID</th>
                                            <th>Department</th>
                                            <th>Status</th>
                                            <th class="min-w-125px">Created At</th>
                                            <th class="min-w-125px">Updated At</th>
                                        </tr>
                                    </thead>
                                    <tbody class="fs-6 fw-semibold text-gray-600">
                                        @foreach ($ticket->departmentTickets as $departmentTicket)
                                            <tr>
                                                <td>
                                                    {{ $departmentTicket->report_id }}
                                                </td>
                                                <td>
                                                    {{ $departmentTicket->department_name }}
                                                </td>
                                                <td>
                                                    {{ $departmentTicket->status_name }}
                                                </td>
                                                <td>
                                                    {{ $departmentTicket->created_at }}
                                                </td>
                                                <td>
                                                    {{ $departmentTicket->updated_at }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card pt-4 mb-6 mb-xl-9">
                        <div class="card-header border-0">
                            <div class="card-title">
                                <h2>Ticket Logs</h2>
                            </div>
                        </div>
                        <div class="card-body py-0">
                            <div class="table-responsive">
                                <table class="table align-middle table-row-dashed fw-semibold text-gray-600 fs-6 gy-5"
                                    id="kt_table_users_logs">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Ticket</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Updated By</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($ticket->ticketLogs as $tl)
                                            <tr>
                                                <td class="min-w-70px">
                                                    <span
                                                        class="badge badge-light-{{ $tl->status_name === 'Resolved' ? 'info' : 'success' }}">
                                                        {{ $tl->status_name }}
                                                    </span>
                                                </td>
                                                <td>{{ $ticket->ticket }}</td>
                                                <td class="pe-0 min-w-200px">{{ $tl->created_at->format('d-m-Y H:i') }}
                                                </td>
                                                <td class="pe-0 min-w-200px">
                                                    {{ $tl->updated_at ? $tl->updated_at->format('d-m-Y H:i') : '-' }}
                                                </td>
                                                <td class="pe-0 min-w-150px">
                                                    {{ $tl->updated_by ?? '-' }}
                                                </td>
                                                <td>
                                                    {{ $tl->note ?? '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">Belum ada log untuk tiket
                                                    ini.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush

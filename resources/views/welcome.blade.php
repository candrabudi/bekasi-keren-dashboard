@extends('layouts.app')
@section('navbar-wrapper')
    <div class="d-flex flex-stack justify-content-end flex-row-fluid" id="kt_app_navbar_wrapper">
        <div class="page-entry d-flex flex-column flex-row-fluid" data-kt-swapper="true"
            data-kt-swapper-mode="{default: 'prepend', lg: 'prepend'}"
            data-kt-swapper-parent="{default: '#kt_app_toolbar_container', lg: '#kt_app_navbar_wrapper'}">
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-base my-1 ">
                <li class="breadcrumb-item text-gray-500">
                    <a href="../../index.html" class="text-gray-500 text-hover-primary">Home </a>
                </li>
                <li class="breadcrumb-item">
                    <span class="text-gray-500">/</span>
                </li>
                <li class="breadcrumb-item text-gray-500">
                    Pages
                </li>
                <li class="breadcrumb-item">
                    <span class="text-gray-500">/</span>
                </li>
                <li class="breadcrumb-item text-gray-500">
                    Careers
                </li>
            </ul>
            <div class="page-title d-flex align-items-center">
                <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-2x flex-column justify-content-center my-0">
                    Careers List
                </h1>
            </div>
        </div>
    </div>
@endsection
@section('second-sidebar')
    @include('layouts.second-sidebar.dashboards')
@endsection
@section('content')
@endsection

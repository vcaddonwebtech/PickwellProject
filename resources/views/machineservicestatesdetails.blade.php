@extends('layouts.app')
@section('title', 'TEXTALK - Dashboard')
@section('css')
<style>
    /* Hide the default close button of InfoWindow */
    .gm-style-iw-d {
        padding-right: 0 !important;
    }

    .gm-style-iw-c button.gm-ui-hover-effect {
        display: none !important;
    }
</style>

    <link rel="stylesheet" href="{{ asset('libs/apexcharts/apexcharts.css') }}">
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-1 mb-md-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('machineservicedata', ['main_machine_type' => $main_machine_type]) }}">{{ $main_machine_name }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Statistics</li>
                </ol>
            </nav>
        </div>
        <!-- Page Header Close -->
        
            
                
       
                     
            @if (Auth::check())

                <div class="col-sm-12 col-lg-12 col-xl-12 col-xxl-12">
                    <div class="row">
                        <div class="col-sm-4">
                            <a href="{{ route('today-total-complaints') }}">
                                <div class="card card-dashboard">
                                    <div class="card-body d-flex align-items-center py-3 flex-wrap">
                                        <span class="avatar avatar-lg rounded-pill bg-secondary-transparent"><i
                                                class="bx bx-shopping-bag text-secondary fs-20"></i></span>
                                        <div class="ms-3 flex-grow-1">
                                            <span class="text-muted dashboardText">Today Total Complaints</span>
                                            <h5 class="mb-0 mt-1 dashboardText">{{ $total_todays_complaints }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-sm-4">
                            <a href="{{ route('today-done-complaints') }}">
                                <div class="card card-dashboard">
                                    <div class="card-body d-flex align-items-center py-3 flex-wrap">
                                        <span class="avatar avatar-lg rounded-pill bg-success-transparent"><i
                                                class="bx bx-trending-up text-success fs-20"></i></span>
                                        <div class="ms-3 flex-grow-1">
                                            <span class="text-muted dashboardText">Today Total Done</span>
                                            <h5 class="mb-0 mt-1 dashboardText">{{ $todays_total_done_complaints }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-4">
                            <a href="{{ route('total-pending-complaints') }}">
                                <div class="card card-dashboard">
                                    <div class="card-body d-flex align-items-center py-3 flex-wrap">
                                        <span class="avatar avatar-lg rounded-pill bg-primary-transparent"><i
                                                class="bx bx-dollar-circle text-primary fs-20"></i></span>
                                        <div class="ms-3 flex-grow-1">
                                            <span class="text-muted dashboardText">Today Pending Complaints</span>
                                            <h5 class="mb-0 mt-1 dashboardText">{{ $todays_total_pending_complaints }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-4">
                            <a href="#">
                                <div class="card card-dashboard">
                                    <div class="card-body d-flex align-items-center py-3 flex-wrap">
                                        <span class="avatar avatar-lg rounded-pill bg-primary-transparent"><i
                                                class="bx bx-dollar-circle text-primary fs-20"></i></span>
                                        <div class="ms-3 flex-grow-1">
                                            <span class="text-muted dashboardText">Today In-Progress Complaints</span>
                                            <h5 class="mb-0 mt-1 dashboardText">{{ $todays_total_inprogress_complaints }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-sm-4">
                            <form id="todayPresentEngineerForm" method="POST"
                                action="{{ route('report.date-wise-present-engineer') }}" target="_blank">
                                @csrf
                                <div class="card card-dashboard">
                                    <div class="card-body d-flex align-items-center py-3 flex-wrap">
                                        <span class="avatar avatar-lg rounded-pill bg-success-transparent">
                                            <i class="bx bx-trending-up text-success fs-20"></i>
                                        </span>
                                        <div class="ms-3">
                                            <a href="{{ route('today-present-engineer') }}" target="_blank">
                                                <span class="text-muted dashboardText">Today Present Engineer
                                                    {{ '(' . $today_present_engineer . ')' }}</span>
                                            </a>
                                            <div class="w-100 mt-2">
                                                <input type="text" id="today_present_employee"
                                                    name="today_present_employee" placeholder="dd-mm-yyyy"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-sm-4">
                            <a href="#">
                                <div class="card card-dashboard">
                                    <div class="card-body d-flex align-items-center py-3 flex-wrap">
                                        <span class="avatar avatar-lg rounded-pill bg-secondary-transparent"><i
                                                class="bx bx-shopping-bag text-secondary fs-20"></i></span>
                                        <div class="ms-3 flex-grow-1">
                                            <span class="text-muted dashboardText">Total Done Complaints</span>
                                            <h5 class="mb-0 mt-1 dashboardText">{{ $total_done_complaints }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-4">
                            <a href="{{ route('free-engineers') }}">
                                <div class="card card-dashboard">
                                    <div class="card-body d-flex align-items-center py-3 flex-wrap">
                                        <span class="avatar avatar-lg rounded-pill bg-primary-transparent"><i
                                                class="bx bx-trending-up text-primary fs-20"></i></span>
                                        <div class="ms-3 flex-grow-1">
                                            <span class="text-muted dashboardText">Free Engineers</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-sm-4">
                            <a href="#">
                                <div class="card card-dashboard">
                                    <div class="card-body d-flex align-items-center py-3 flex-wrap">
                                        <span class="avatar avatar-lg rounded-pill bg-success-transparent"><i
                                                class="bx bx-trending-up text-success fs-20"></i></span>
                                        <div class="ms-3 flex-grow-1">
                                            <span class="text-muted dashboardText">Total In-Progess Complaints</span>
                                            <h5 class="mb-0 mt-1 dashboardText">{{ $total_inprogress_complaints }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-4">
                            <a href="{{ route('total-pending-complaints') }}">
                                <div class="card card-dashboard">
                                    <div class="card-body d-flex align-items-center py-3 flex-wrap">
                                        <span class="avatar avatar-lg rounded-pill bg-primary-transparent"><i
                                                class="bx bx-dollar-circle text-primary fs-20"></i></span>
                                        <div class="ms-3 flex-grow-1">
                                            <span class="text-muted dashboardText">Total Pending Complaints</span>
                                            <h5 class="mb-0 mt-1 dashboardText">{{ $total_pending_complaints }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="col-sm-4">
                            <a href="{{ route('eng-pending-complaints') }}">
                                <div class="card card-dashboard">
                                    <div class="card-body d-flex align-items-center py-3 flex-wrap">
                                        <span class="avatar avatar-lg rounded-pill bg-success-transparent"><i
                                                class="bx bx-trending-up text-success fs-20"></i></span>
                                        <div class="ms-3 flex-grow-1">
                                            <span class="text-muted dashboardText">Eng. Wise Pending Complaints</span>
                                            <h5 class="mb-0 mt-1 dashboardText">{{ $eng_pending_complaints }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-4">
                            <a href="{{ route('today-eng-done-complaints') }}">
                                <div class="card card-dashboard">
                                    <div class="card-body d-flex align-items-center py-3 flex-wrap">
                                        <span class="avatar avatar-lg rounded-pill bg-success-transparent"><i
                                                class="bx bx-trending-up text-success fs-20"></i></span>
                                        <div class="ms-3 flex-grow-1">
                                            <span class="text-muted dashboardText">Eng. Wise Done Complaints</span>
                                            <h5 class="mb-0 mt-1 dashboardText">{{ $today_eng_done_complaints }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-sm-4">
                            <a href="{{ route('pending-assign-complaints') }}">
                                <div class="card card-dashboard">
                                    <div class="card-body d-flex align-items-center py-3 flex-wrap">
                                        <span class="avatar avatar-lg rounded-pill bg-success-transparent"><i
                                                class="bx bx-trending-up text-success fs-20"></i></span>
                                        <div class="ms-3 flex-grow-1">
                                            <span class="text-muted dashboardText">Pending Assign Complaints</span>
                                            <h5 class="mb-0 mt-1 dashboardText">{{ $pending_assign_complaints }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <div class="col-sm-4">
                            <a href="{{ route('engineer-last-visit') }}">
                                <div class="card card-dashboard">
                                    <div class="card-body d-flex align-items-center py-3 flex-wrap">
                                        <span class="avatar avatar-lg rounded-pill bg-success-transparent"><i
                                                class="bx bx-trending-up text-success fs-20"></i></span>
                                        <div class="ms-3 flex-grow-1">
                                            <span class="text-muted dashboardText">Engineer Last Visit Status</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@section('scripts')
    <!-- Apex Charts JS -->
    <script src="{{ asset('libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            $(function() {
                $('#today_admin_report').datepicker({
                    dateFormat: "dd-mm-yy",
                    onSelect: function(dateText) {
                        // Convert to yyyy-mm-dd format
                        var dateParts = dateText.split('-');
                        var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[
                            0]; // yyyy-mm-dd
                        $(this).val(dateText); // Set the displayed value
                        $('#todayAdminReportForm').append(
                            '<input type="hidden" name="date" value="' + formattedDate +
                            '">');
                        $('#todayAdminReportForm').submit(); // Submit the form
                    }
                });
            });

            $(function() {
                $('#today_present_employee').datepicker({
                    dateFormat: "dd-mm-yy",
                    onSelect: function(dateText) {
                        // Convert to yyyy-mm-dd format
                        var dateParts = dateText.split('-');
                        var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[
                            0]; // yyyy-mm-dd
                        $(this).val(dateText); // Set the displayed value
                        $('#todayPresentEngineerForm').append(
                            '<input type="hidden" name="date" value="' + formattedDate +
                            '">');
                        $('#todayPresentEngineerForm').submit(); // Submit the form
                    }
                });
            });
        });
    </script>
@endsection
@endsection

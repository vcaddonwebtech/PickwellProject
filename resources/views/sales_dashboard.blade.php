@extends('sales_layouts.app')
@section('title', 'Dashboard')
@section('css')
<link rel="stylesheet" href="{{ asset('libs/apexcharts/apexcharts.css') }}">
@endsection
@section('content')
<!-- Start::app-content -->
<!-- <div class="p-3 header-secondary row">
                <div class="col">
                    <div class="d-flex">
                        <a class="btn btn-danger d-flex" href="javascript:void(0);"><i
                                class="fe fe-rotate-cw me-2 mt-1"></i> Upgrade </a>
                    </div>
                </div>
                <div class="col col-auto">
                    <div class="btn-list">
                        <a class="btn btn-outline-light border" href="javascript:void(0);"><i
                                class="fe fe-help-circle me-1 mt-1"></i>
                            Support</a>
                        <a class="btn btn-success me-0" href="javascript:void(0);"><i class="fe fe-plus me-1 mt-1"></i>
                            Add
                            New</a>
                    </div>
                </div>
            </div> -->
<div class="container-fluid">

    <!-- page-header -->
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <div class="">
            <div class="">
                <nav>
                    <ol class="breadcrumb mb-1 mb-md-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Sales Dashboard</li>
                    </ol>
                </nav>
            </div>
        </div>

    </div>
    <!-- Page Header Close -->

    <!-- End page-header -->

    <!-- ROW-1 -->
    <div class="row">
        @if(Auth::check())

        <div class="col-sm-12 col-lg-12 col-xl-12 col-xxl-12">
            <div class="row">
                {{-- <div class="col-12">
                    <div class="card text-bg-dark bg-primary-gradient">
                        <img src="../assets/images/media/bg4.jpg" class="card-img index-card op-2" alt="Background">
                        <div class="card-img-overlay d-flex flex-column justify-content-center align-items-start">
                            <h5 class="fw-semibold  text-fixed-white mb-1">Annual Details Are Now Available!
                            </h5>
                            <p class="text-fixed-white">You Will Get Complete Overview Of
                                Revenue, Orders And Profit.</p>
                            <a href="javascript:void(0);" class="btn btn-sm bg-success">View Details<i
                                    class="bi bi-arrow-up-right-circle ms-2"></i></a>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body d-flex align-items-center py-3 flex-wrap">
                            <span class="avatar avatar-lg rounded-pill bg-primary-transparent"><i
                                    class="bx bx-dollar-circle text-primary fs-20"></i></span>
                            <div class="ms-3 flex-grow-1">
                                <span class="text-muted">Total Pending Complaints</span>
                                <h5 class="fw-semibold mb-0 mt-1">{{ $total_pending_complaints }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body d-flex align-items-center py-3 flex-wrap">
                            <span class="avatar avatar-lg rounded-pill bg-secondary-transparent"><i
                                    class="bx bx-shopping-bag text-secondary fs-20"></i></span>
                            <div class="ms-3 flex-grow-1">
                                <span class="text-muted">Today's Total Complaints</span>
                                <h5 class="fw-semibold mb-0 mt-1">{{ $total_todays_complaints }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body d-flex align-items-center py-3 flex-wrap">
                            <span class="avatar avatar-lg rounded-pill bg-danger-transparent"><i
                                    class="bx bx-user-plus text-danger fs-20"></i></span>
                            <div class="ms-3 flex-grow-1">
                                <span class="text-muted">Today's cleared Complaints</span>
                                <h5 class="fw-semibold mb-0 mt-1">{{$todays_clear_complaints}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body d-flex align-items-center py-3 flex-wrap">
                            <span class="avatar avatar-lg rounded-pill bg-success-transparent"><i
                                    class="bx bx-trending-up text-success fs-20"></i></span>
                            <div class="ms-3 flex-grow-1">
                                <span class="text-muted">Today's assigned Complaints</span>
                                <h5 class="fw-semibold mb-0 mt-1">{{$todays_assigned_complaints}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body d-flex align-items-center py-3 flex-wrap">
                            <span class="avatar avatar-lg rounded-pill bg-success-transparent"><i
                                    class="bx bx-trending-up text-success fs-20"></i></span>
                            <div class="ms-3 flex-grow-1">
                                <span class="text-muted">Today's COmplaint with engineer IN</span>
                                <h5 class="fw-semibold mb-0 mt-1">{{$todays_in_engineer_complaints}}</h5>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="card">
                        <div class="card-body d-flex align-items-center py-3 flex-wrap">
                            <span class="avatar avatar-lg rounded-pill bg-success-transparent"><i
                                    class="bx bx-trending-up text-success fs-20"></i></span>
                            <div class="ms-3 flex-grow-1">
                                <span class="text-muted">Urgent Complaints</span>
                                <h5 class="fw-semibold mb-0 mt-1">{{$urgent_complaints}}</h5>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
        {{-- <div class="col-sm-12 col-md-12 col-lg-12 col-xl-7 col-xxl-8">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Earning Details </div>
                    <div class="btn-group btn-sm">
                        <button type="button" class="btn btn-light btn-sm">
                            <span class="">Today</span>
                        </button>
                        <button type="button" class="btn btn-light btn-sm">
                            <span class="">Month</span>
                        </button>
                        <button type="button" class="btn btn-light btn-sm">
                            <span class="">Year</span>
                        </button>
                    </div>
                </div>
                <div class="card-body pb-0">
                    <div class="row py-2 justify-content-center">
                        <div class="col-3 d-flex align-items-center justify-content-center flex-wrap">
                            <p class="text-muted mb-0 me-2"><span class="legend bg-primary rounded-5"></span>Last Year
                            </p>
                            <span class="badge bg-danger-transparent"><i class="bx bx-trending-down me-1"></i>5%</span>
                        </div>
                        <div class="col-3 d-flex align-items-center justify-content-center flex-wrap">
                            <p class="text-muted mb-0 me-2"><span class="legend bg-secondary rounded-5"></span>Last
                                Month</p>
                            <span class="badge bg-success-transparent"><i class="bx bx-trending-up me-1"></i>22%</span>
                        </div>
                        <div class="col-3 d-flex align-items-center justify-content-center flex-wrap">
                            <p class="text-muted mb-0 me-2"><span class="legend bg-danger rounded-5"></span>Today</p>
                            <span class="badge bg-danger-transparent"><i class="bx bx-trending-down me-1"></i>13%</span>
                        </div>
                    </div>
                    <div id="earnings"></div>
                </div>
            </div>
        </div> --}}
        @endif
    </div>
    <!-- ROW-1 END -->
    <canvas id="myChart"></canvas>
    <!-- Row 2 -->

    {{-- <div class="row">
        <div class="col-md-6 col-xxl-3 col-xl-6 col-lg-6">
            <div class="card custom-card">
                <div class="card-header d-inline-flex justify-content-between">
                    <div class="card-title">Preferences</div>
                    <div>
                        <a href="javascript:void(0);" class="option-dots" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                        <div class="dropdown-menu rounded-2">
                            <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-edit me-2"></i>
                                Edit</a>
                            <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-share me-2"></i>
                                Share</a>
                            <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-download me-2"></i>
                                Download</a>
                            <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-trash me-2"></i>
                                Delete</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-group list-item-spacing1">
                        <li class="list-group-item px-0 pt-0 d-flex align-items-center border-0">
                            <span class="avatar rounded-5 bg-primary-transparent"><i
                                    class="bx bx-like text-primary fs-16"></i></span>
                            <div class="ms-3 flex-grow-1">
                                <h6 class="fw-senibold mb-0 mt-1 fs-14">Likes</h6>
                                <span class="text-muted">Your Liked Products</span>
                            </div>
                            <span class="badge bg-primary">2, 554</span>
                        </li>
                        <li class="list-group-item px-0  d-flex align-items-center border-0">
                            <span class="avatar rounded-5 bg-info-transparent"><i
                                    class="bx bx-comment text-info  fs-16"></i></span>
                            <div class="ms-3 flex-grow-1">
                                <h6 class="fw-senibold mb-0 mt-1 fs-14">Comments</h6>
                                <span class="text-muted">Your Comments</span>
                            </div>
                            <span class="badge bg-info">1, 388</span>
                        </li>
                        <li class="list-group-item px-0  d-flex align-items-center border-0">
                            <span class="avatar rounded-5 bg-warning-transparent"><i
                                    class="bx bx-wallet text-warning  fs-16"></i></span>
                            <div class="ms-3 flex-grow-1">
                                <h6 class="fw-senibold mb-0 mt-1 fs-14">Purchase</h6>
                                <span class="text-muted">Your Purchased Products</span>
                            </div>
                            <span class="badge bg-warning">9, 725</span>
                        </li>
                        <li class="list-group-item px-0  d-flex align-items-center border-0">
                            <span class="avatar rounded-5 bg-danger-transparent"><i
                                    class="bx bx-heart text-danger  fs-16"></i></span>
                            <div class="ms-3 flex-grow-1">
                                <h6 class="fw-senibold mb-0 mt-1 fs-14">Favorites</h6>
                                <span class="text-muted">Your Favorite List Is Here</span>
                            </div>
                            <span class="badge bg-danger">11, 571</span>
                        </li>
                        <li class="list-group-item px-0 pb-0 d-flex align-items-center border-0">
                            <span class="avatar rounded-5 bg-secondary-transparent"><i
                                    class="bx bx-share text-secondary  fs-16"></i></span>
                            <div class="ms-3 flex-grow-1">
                                <h6 class="fw-senibold mb-0 mt-1 fs-14">Shared</h6>
                                <span class="text-muted">Your Shared Products</span>
                            </div>
                            <span class="badge bg-secondary">6, 869</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3 col-xl-6 col-lg-6">
            <div class="card custom-card">
                <div class="card-header d-inline-flex justify-content-between">
                    <div class="card-title">Sales By Country</div>
                    <div>
                        <a href="javascript:void(0);" class="option-dots" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                        <div class="dropdown-menu rounded-2">
                            <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-edit me-2"></i>
                                Edit</a>
                            <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-share me-2"></i>
                                Share</a>
                            <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-download me-2"></i>
                                Download</a>
                            <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-trash me-2"></i>
                                Delete</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-group list-item-spacing2">
                        <li class="list-group-item px-0 pt-0 border-0  mb-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>United States</span>
                                <span class="tx-12">50%</span>
                            </div>
                            <div class="progress progress-xs" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                aria-valuemax="100">
                                <div class="progress-bar   bg-primary progress-bar-striped progress-bar-animated"
                                    style="width: 70%"></div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 border-0 mb-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>China</span>
                                <span class="tx-12">80%</span>
                            </div>
                            <div class="progress progress-xs" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                aria-valuemax="100">
                                <div class="progress-bar   bg-secondary progress-bar-striped progress-bar-animated"
                                    style="width: 70%"></div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 border-0 mb-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>Russia</span>
                                <span class="tx-12">25%</span>
                            </div>
                            <div class="progress progress-xs" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                aria-valuemax="100">
                                <div class="progress-bar   bg-info progress-bar-striped progress-bar-animated"
                                    style="width: 70%"></div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 border-0 mb-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>Canada</span>
                                <span class="tx-12">60%</span>
                            </div>
                            <div class="progress progress-xs" role="progressbar" aria-valuenow="40" aria-valuemin="0"
                                aria-valuemax="100">
                                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                                    style="width: 40%"></div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 border-0 mb-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>Japan</span>
                                <span class="tx-12">30%</span>
                            </div>
                            <div class="progress progress-xs" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                aria-valuemax="100">
                                <div class="progress-bar   bg-danger progress-bar-striped progress-bar-animated"
                                    style="width: 40%"></div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 border-0">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>United Kingdom</span>
                                <span class="tx-12">75%</span>
                            </div>
                            <div class="progress progress-xs" role="progressbar" aria-valuenow="60" aria-valuemin="0"
                                aria-valuemax="100">
                                <div class="progress-bar   bg-warning progress-bar-striped progress-bar-animated"
                                    style="width: 70%"></div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3 col-xl-6 col-lg-6">
            <div class="card custom-card">
                <div class="card-header d-inline-flex justify-content-between">
                    <div class="card-title">Customer Acquisition</div>
                    <div>
                        <a href="javascript:void(0);" class="option-dots" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                        <div class="dropdown-menu rounded-2">
                            <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-edit me-2"></i>
                                Edit</a>
                            <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-share me-2"></i>
                                Share</a>
                            <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-download me-2"></i>
                                Download</a>
                            <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-trash me-2"></i>
                                Delete</a>
                        </div>
                    </div>
                </div>
                <div class="card-body text-center">
                    <div id="candidates-chart" class="p-3"></div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3 col-xl-6 col-lg-6">
            <div class="card custom-card">
                <div class="card-header d-inline-flex justify-content-between">
                    <div class="card-title">Insights</div>
                    <div>
                        <a href="javascript:void(0);" class="option-dots" data-bs-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                        <div class="dropdown-menu rounded-2">
                            <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-edit me-2"></i>
                                Edit</a>
                            <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-share me-2"></i>
                                Share</a>
                            <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-download me-2"></i>
                                Download</a>
                            <a class="dropdown-item" href="javascript:void(0);"><i class="fe fe-trash me-2"></i>
                                Delete</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-group list-item-spacing3">
                        <li class="list-group-item px-0 mb-2 pt-0 border-0">
                            <i class="bx bx-check-circle  fs-18 text-success align-middle me-1"></i>
                            <span class="text-muted fw-semibold fs-13"><b>39%</b> Clients Are
                                From Twitter</span>
                            <a href="javascript:void(0);" class="text-dark"><i
                                    class="bx bx-chevron-right float-end mt-1"></i></a>
                        </li>
                        <li class="list-group-item px-0 mb-2 border-0">
                            <i class="bx bx-check-circle  fs-18 text-success align-middle me-1"></i>
                            <span class="text-muted fw-semibold fs-13">We Hit All Time High Sales</span>
                            <a href="javascript:void(0);" class="text-dark"><i
                                    class="bx bx-chevron-right float-end mt-1"></i></a>
                        </li>
                        <li class="list-group-item px-0 mb-2 border-0">
                            <i class="bx bx-check-circle  fs-18 text-success align-middle me-1"></i>
                            <span class="text-muted fw-semibold fs-13">Revenue Jumped To <b>55%</b></span>
                            <a href="javascript:void(0);" class="text-dark"><i
                                    class="bx bx-chevron-right float-end mt-1"></i></a>
                        </li>
                        <li class="list-group-item px-0 mb-2 border-0">
                            <i class="bx bx-check-circle  fs-18 text-success align-middle me-1"></i>
                            <span class="text-muted fw-semibold fs-13"> Customer Growth Is
                                <b>14</b></span>
                            <a href="javascript:void(0);" class="text-dark"><i
                                    class="bx bx-chevron-right float-end mt-1"></i></a>
                        </li>
                        <li class="list-group-item px-0 mb-2 border-0">
                            <i class="bx bx-check-circle  fs-18 text-success align-middle me-1"></i>
                            <span class="text-muted fw-semibold fs-13"><b>13%</b> Less Bounce Back</span>
                            <a href="javascript:void(0);" class="text-dark"><i
                                    class="bx bx-chevron-right float-end mt-1"></i></a>
                        </li>
                        <li class="list-group-item px-0 mb-2 border-0">
                            <i class="bx bx-check-circle  fs-18 text-success align-middle me-1"></i>
                            <span class="text-muted fw-semibold fs-13"> Growth In All
                                Markets</span>
                            <a href="javascript:void(0);" class="text-dark"><i
                                    class="bx bx-chevron-right float-end mt-1"></i></a>
                        </li>
                        <li class="list-group-item px-0 pb-0 border-0">
                            <i class="bx bx-check-circle  fs-18 text-success align-middle me-1"></i>
                            <span class="text-muted fw-semibold fs-13">Return Rate Fallen To
                                <b>3%</b></span>
                            <a href="javascript:void(0);" class="text-dark"><i
                                    class="bx bx-chevron-right float-end mt-1"></i></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Row 2 -->
    <!-- ROW-3 -->
    {{-- <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Recent Orders
                    </div>
                    <div class="btn-group btn-sm">
                        <button type="button" class="btn btn-light btn-sm">
                            <span class="">Today</span>
                        </button>
                        <button type="button" class="btn btn-light btn-sm">
                            <span class="">Month</span>
                        </button>
                        <button type="button" class="btn btn-light btn-sm">
                            <span class="">Year</span>
                        </button>
                    </div>

                </div>
                <div class="card-body">
                    <div class="d-sm-flex mb-4 justify-content-between">
                        <div>
                            <span class="">Show</span>
                            <div class="btn-group">
                                <button type="button" class="btn btn-outline-light dropdown-toggle btn-sm"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    10
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:void(0);">10</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">25</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">50</a></li>
                                </ul>
                            </div>
                            <span class="">Entries</span>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-1 mt-sm-0">
                            <div>
                                <input class="form-control form-control-sm" type="text" placeholder="Search Here"
                                    aria-label=".form-control-sm example">
                            </div>
                            <div class="dropdown">
                                <a href="javascript:void(0);"
                                    class="btn btn-primary btn-sm btn-wave waves-effect waves-light"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Sort By<i class="ri-arrow-down-s-line align-middle ms-1 d-inline-block"></i>
                                </a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a class="dropdown-item" href="javascript:void(0);">New</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">Popular</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">Relevant</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="data-table" class="table table-bordered p-0 text-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th class="border-top-0 ">Order ID</th>
                                    <th class="border-top-0">Product</th>
                                    <th class="border-top-0">Billing Name</th>
                                    <th class="border-top-0">Amount</th>
                                    <th class="border-top-0">Date</th>
                                    <th class="border-top-0">Time</th>
                                    <th class="border-top-0">Status</th>
                                    <th class="border-top-0">Invoice</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-bottom">
                                    <td>#1254</td>
                                    <td class="border-bottom-0">
                                        <div class="d-flex align-items-center border-0 flex-wrap">
                                            <img src="../assets/images/ecommerce/5.jpg" class="avatar rounded-circle"
                                                alt="Headphones">
                                            <div class="ms-2 flex-grow-1">
                                                <h6 class="fs-14 mb-0 mt-1">Boat Headphones</h6>
                                                <span class="text-muted">Strong Bass
                                                    Headphones</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Lenny Schultz</td>
                                    <td><span><i class="bi bi-currency-dollar"></i>3,
                                            558</span></td>
                                    <td class="fs-13 fw-semibold text-dark">22 December 2023</td>
                                    <td class="fs-13 fw-semibold text-dark">11:00 AM</td>
                                    <td><span class="badge bg-danger-transparent px-3 py-2">Unpaid</span>
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <a aria-label="anchor" href="javascript:void(0);"
                                                class="btn btn-wave waves-effect waves-light btn-sm btn-primary-light"><i
                                                    class="bi bi-download"></i></a>
                                            <a aria-label="anchor" href="javascript:void(0);"
                                                class="btn btn-wave waves-effect waves-light btn-sm btn-danger-light"><i
                                                    class="bi bi-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <td>#5587</td>
                                    <td class="">
                                        <div class="d-flex align-items-center border-0 flex-wrap">
                                            <img src="../assets/images/ecommerce/10.jpg" class="avatar rounded-circle"
                                                alt="Watch">
                                            <div class="ms-2 flex-grow-1">
                                                <h6 class="fs-14 mb-0 mt-1">Smart Watch</h6>
                                                <span class="text-muted">Water Resistant
                                                    Watch</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Franziska Klein</td>
                                    <td><span><i class="bi bi-currency-dollar"></i>4,
                                            874</span></td>
                                    <td class="fs-13 fw-semibold text-dark">05 January 2023</td>
                                    <td class="fs-13 fw-semibold text-dark">12:15 PM</td>
                                    <td><span class="badge bg-success-transparent px-3 py-2">Paid</span>
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <a aria-label="anchor" href="javascript:void(0);"
                                                class="btn btn-wave waves-effect waves-light btn-sm btn-primary-light"><i
                                                    class="bi bi-download"></i></a>
                                            <a aria-label="anchor" href="javascript:void(0);"
                                                class="btn btn-wave waves-effect waves-light btn-sm btn-danger-light"><i
                                                    class="bi bi-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <td>#9985</td>
                                    <td class="">
                                        <div class="d-flex align-items-center border-0 flex-wrap">
                                            <img src="../assets/images/ecommerce/6.jpg" class="avatar rounded-circle"
                                                alt="Lens">
                                            <div class="ms-2 flex-grow-1">
                                                <h6 class="fs-14 mb-0 mt-1">DSLR Lens</h6>
                                                <span class="text-muted">Super Zoom
                                                    Lens</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Julian Schäfer</td>
                                    <td><span><i class="bi bi-currency-dollar"></i>8,
                                            447</span></td>
                                    <td class="fs-13 fw-semibold text-dark">08 January 2023</td>
                                    <td class="fs-13 fw-semibold text-dark">14:30 PM</td>
                                    <td><span class="badge bg-success-transparent px-3 py-2">Paid</span>
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <a aria-label="anchor" href="javascript:void(0);"
                                                class="btn btn-wave waves-effect waves-light btn-sm btn-primary-light"><i
                                                    class="bi bi-download"></i></a>
                                            <a aria-label="anchor" href="javascript:void(0);"
                                                class="btn btn-wave waves-effect waves-light btn-sm btn-danger-light"><i
                                                    class="bi bi-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <td>#6669</td>
                                    <td class="">
                                        <div class="d-flex align-items-center border-0 flex-wrap">
                                            <img src="../assets/images/ecommerce/9.jpg" class="avatar rounded-circle"
                                                alt="Laptop">
                                            <div class="ms-2 flex-grow-1">
                                                <h6 class="fs-14 mb-0 mt-1">Dell Laptop</h6>
                                                <span class="text-muted">22" Latest
                                                    Laptop</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Bianca Thoma</td>
                                    <td><span><i class="bi bi-currency-dollar"></i>6,
                                            441</span></td>
                                    <td class="fs-13 fw-semibold text-dark">09 February 2023</td>
                                    <td class="fs-13 fw-semibold text-dark">15:45 PM</td>
                                    <td><span class="badge bg-danger-transparent px-3 py-2">Unpaid</span>
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <a aria-label="anchor" href="javascript:void(0);"
                                                class="btn btn-wave waves-effect waves-light btn-sm btn-primary-light"><i
                                                    class="bi bi-download"></i></a>
                                            <a aria-label="anchor" href="javascript:void(0);"
                                                class="btn btn-wave waves-effect waves-light btn-sm btn-danger-light"><i
                                                    class="bi bi-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border-bottom">
                                    <td>#0147</td>
                                    <td class="">
                                        <div class="d-flex align-items-center border-0 flex-wrap">
                                            <img src="../assets/images/ecommerce/4.jpg" class="avatar rounded-circle"
                                                alt="Shoes">
                                            <div class="ms-2 flex-grow-1">
                                                <h6 class="fs-14 mb-0 mt-1">Casual Shoes</h6>
                                                <span class="text-muted">Soft And
                                                    Comfortable Shoes</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Jan Christ</td>
                                    <td><span><i class="bi bi-currency-dollar"></i>2,
                                            550</span></td>
                                    <td class="fs-13 fw-semibold text-dark">12 March 2023</td>
                                    <td class="fs-13 fw-semibold text-dark">16:00 PM</td>
                                    <td><span class="badge bg-success-transparent px-3 py-2">Paid</span>
                                    </td>
                                    <td>
                                        <div class="hstack gap-2 fs-15">
                                            <a aria-label="anchor" href="javascript:void(0);"
                                                class="btn btn-wave waves-effect waves-light btn-sm btn-primary-light"><i
                                                    class="bi bi-download"></i></a>
                                            <a aria-label="anchor" href="javascript:void(0);"
                                                class="btn btn-wave waves-effect waves-light btn-sm btn-danger-light"><i
                                                    class="bi bi-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex align-items-center">
                        <div>
                            Showing 5 Entries <i class="bi bi-arrow-right ms-2 fw-semibold"></i>
                        </div>
                        <div class="ms-auto">
                            <nav aria-label="Page navigation" class="pagination-style-4">
                                <ul class="pagination mb-0">
                                    <li class="page-item disabled">
                                        <a class="page-link" href="javascript:void(0);">
                                            Prev
                                        </a>
                                    </li>
                                    <li class="page-item active"><a class="page-link" href="javascript:void(0);">1</a>
                                    </li>
                                    <li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
                                    <li class="page-item">
                                        <a class="page-link text-primary" href="javascript:void(0);">
                                            next
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div> --}}
    <!-- ROW-3 END -->

</div>
<!-- End::app-content -->
@section('scripts')
<!-- Apex Charts JS -->
<script src="{{ asset('libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{asset('js/index.js')}}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const myChart = new Chart(
        document.getElementById('myChart'),
        config // We'll add the configuration details later.
    );
</script>
@endsection
@endsection
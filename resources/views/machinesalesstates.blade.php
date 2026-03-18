@extends('layouts.app')
@section('title', 'Dashboard')
@section('css')
    <link rel="stylesheet" href="{{ asset('libs/apexcharts/apexcharts.css') }}">
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <nav>
                <ol class="breadcrumb mb-1 mb-md-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $main_machine_name }} -> Sales</li>
                </ol>
            </nav>
        </div>
        <!-- Page Header Close -->
        <!-- ROW-1 -->
        <div class="row">
            
        <div class="col-lg-4 col-xl-3">
                            <div class="card custom-card custom-card">
                                <div class="card-header">
                                    <div class="card-title">{{ $main_machine_name }} </div>
                                </div>
                                <div class="main-content-left main-content-left-mail card-body pt-0 ">
                                    <div class="main-settings-menu">
                                        <nav class="nav main-nav-column">
                                            <a class="nav-link thumb active mb-2" href="{{ route('salesleadcreate', ['main_machine_type' => $main_machine_type]) }}"><i
                                                    class="bx bxs-error"></i> <span class="badge bg-outline-danger" style="font-size: 14px; margin-top: 3px;"> Create New Lead </span></a>
                                            <a class="nav-link thumb mb-2" href="{{ route('salse-lead-report', ['main_machine_type' => $main_machine_type]) }}"><i
                                                    class="bx bxs-file"></i> Sales Lead List </a>
                                    <?php if(auth()->user()->getRoleNames()->first() == "Sales Team Leader" || auth()->user()->getRoleNames()->first() == "Team Leader") { ?>               
                                            <a class="nav-link border-top-0 thumb mb-2" href="{{ route('salesusers', ['main_machine_type' => $main_machine_type]) }}"><i
                                                    class="bx bxs-user"></i> Sales Person</a>
                                    <?php } ?>
                                    <?php if(auth()->user()->getRoleNames()->first() == "Admin" || auth()->user()->getRoleNames()->first() == "Manager") { ?>               
                                            <a class="nav-link border-top-0 thumb mb-2" href="{{ route('salesusers', ['main_machine_type' => $main_machine_type]) }}"><i
                                                    class="bx bxs-user"></i> Users</a>
                                    <?php } ?>                      
                                            
                                            <a class="nav-link border-top-0 thumb mb-2" href="{{ route('saletodayReport', ['main_machine_type' => $main_machine_type]) }}"><i
                                                    class="bx bxs-bookmarks"></i> A/P Report Today</a>
                                            <a class="nav-link border-top-0 thumb mb-2" href="{{ route('saleap_summary', ['main_machine_type' => $main_machine_type]) }}"><i
                                                    class="bx bxs-report"></i> A/P Details Summary</a>
                                            <a class="nav-link border-top-0 thumb mb-2" href="{{ route('salesalaries', ['main_machine_type' => $main_machine_type]) }}"><i
                                                    class="bx bxs-file"></i> Salaries</a>
                                            
                                        </nav>
                                    </div>

                                </div>
                            </div>

            </div>
            <div class="col-lg-8 col-xl-9">
                <div class="card custom-card custom-card">
                                <div class="card-header">
                                    <div class="card-title">Live Location - <button onclick="resetMap()" class="badge bg-outline-danger" style="font-size: 12px; margin-top: 3px;">Reset Zoom</button></div>
                                </div>
                </div>
                
                <?php if(auth()->user()->getRoleNames()->first() == "Admin" || auth()->user()->getRoleNames()->first() == "Manager" || auth()->user()->getRoleNames()->first() == "Team Leader" || auth()->user()->getRoleNames()->first() == "Service Team Leader" || auth()->user()->getRoleNames()->first() == "Sales Team Leader") { ?>
                <div id="map" style="width:98%; height:525px; z-index: 2; margin:10px;"></div>

            </div>

<script>
        let map;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12,
                center: { lat: 21.2382997, lng: 72.8882355 }, // Center of India
            });

            const engineers =  @json($all_present_sales_person);
            //console.log(engineers);

            engineers.forEach(engineer => {
                if (engineer.in_late && engineer.in_long && engineer.in_selfie) {
                    const marker = new google.maps.Marker({
                        position: {
                            lat: parseFloat(engineer.in_late),
                            lng: parseFloat(engineer.in_long)
                        },
                        map: map,
                        title: engineer.engineer_name,
                        icon: {
                            url: 'https://saistar.addonwebtech.com/atdselfie/' + engineer.in_selfie, // Use full image URL
                            scaledSize: new google.maps.Size(25, 25), // Resize image
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(20, 20)
                        }
                    });
                }
            });
        }

    function resetMap() {
        map.setZoom(11);
        map.setCenter({ lat: 21.2382997, lng: 72.8882355 });
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDu9lahjAIj7IbilbNEAr76hjgUtcq8AM4&loading=async&libraries=maps&v=weekly&callback=initMap" defer>
</script>           
 <?php } ?>                       
            @if (Auth::check())

                <!-- ROW-1 -->
                <div class="row">
					<div class="col-xl-3 col-md-6 ">
						<div class="card card-img-holder">
							<div class="card-body">
								<div class="clearfix">
									<div class="float-sm-start">
										<p class="text-muted mb-1">Today Total Leads</p>
										<h3 class="mb-0">{{ $total_todays_leads }}</h3>
										    
									</div>
									<div class="float-sm-end text-end mt-2">
										<div id="total-purchase"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-md-6">
						<div class="card card-img-holder">
							<div class="card-body">
								<div class="clearfix">
									<div class="float-sm-start">
										<p class="text-muted mb-1">Today Done Leads</p>
										<h3 class="mb-0">{{ $todays_total_done_leads }}</h3>
										
									</div>
									<div class="float-sm-end text-end mt-2">
										<div id="total-profit"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-md-6">
						<div class="card card-img-holder">
							<div class="card-body">
								<div class="clearfix">
									<div class="float-sm-start">
										<p class="text-muted mb-1">Today Pending Leads</p>
										<h3 class="mb-0">{{ $todays_total_pending_leads }}</h3>
										
									</div>
									<div class="float-sm-end text-end mt-2">
										<div id="total-sales"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-md-6">
						<div class="card card-img-holder">
							<div class="card-body">
								<div class="clearfix">
									<div class="float-sm-start">
										<p class="text-muted mb-1">Today In-Progress Leads</p>
										<h3 class="mb-0">{{ $todays_total_inprogress_leads }}</h3>
										
									</div>
									<div class="float-sm-end text-end mt-2">
										<div id="total-returns"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
                </div>
                <!-- ROW-1 END --> 
            @endif
        </div>
    </div>
@section('scripts')
    <!-- Apex Charts JS -->
    <script src="{{ asset('libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('js/index.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
@endsection

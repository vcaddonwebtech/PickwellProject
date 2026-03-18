@extends('layouts.app')
@section('title', $main_machine_name.' - Dashboard')
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
                    <li class="breadcrumb-item active" aria-current="page">{{ $main_machine_name }} -> Services</li>
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
                                            <a class="nav-link thumb active mb-2" href="{{ route('complaints.create', ['main_machine_type' => $main_machine_type]) }}"><i
                                                    class="bx bxs-error"></i> <span class="badge bg-primary" style="font-size: 14px; margin-top: 3px;"> Create New Complaint </span></a>
                                            <a class="nav-link thumb mb-2" href="{{ route('complaints.index', ['main_machine_type' => $main_machine_type]) }}"><i
                                                    class="bx bxs-file"></i> Complaints List </a>
                                            <a class="nav-link border-top-0 thumb mb-2" href="{{ route('MachineSales.index', ['main_machine_type' => $main_machine_type]) }}"><i
                                                    class="bx bxs-cart"></i>Machine installations </a>        
                                    <?php if(auth()->user()->getRoleNames()->first() == "Service Team Leader" || auth()->user()->getRoleNames()->first() == "Team Leader") { ?>               
                                            <a class="nav-link border-top-0 thumb mb-2" href="{{ route('users.index', ['main_machine_type' => $main_machine_type]) }}"><i
                                                    class="bx bxs-user"></i> Staff</a>
                                    <?php } ?>
                                    <?php if(auth()->user()->getRoleNames()->first() == "Admin" || auth()->user()->getRoleNames()->first() == "Manager") { ?>               
                                            <a class="nav-link border-top-0 thumb mb-2" href="{{ route('users.index', ['main_machine_type' => $main_machine_type]) }}"><i
                                                    class="bx bxs-user"></i> Users</a>
                                    <?php } ?>                      
                                            <a class="nav-link thumb mb-2" href="{{ route('parties.index', ['main_machine_type' => $main_machine_type]) }}"><i
                                                    class="bx bxs-user-account"></i> Customers </a>
                                            <a class="nav-link border-top-0 thumb mb-2" href="{{ route('products.index', ['main_machine_type' => $main_machine_type]) }}"><i
                                                    class="bx bxs-package"></i> Products</a>
                                            
                                            <a class="nav-link border-top-0 thumb mb-2" href="{{ route('todayReport', ['main_machine_type' => $main_machine_type]) }}"><i
                                                    class="bx bxs-bookmarks"></i> A/P Report Today</a>
                                            <a class="nav-link border-top-0 thumb mb-2" href="{{ route('ap_summary', ['main_machine_type' => $main_machine_type]) }}"><i
                                                    class="bx bxs-report"></i> A/P Details Summary</a>
                                            <a class="nav-link border-top-0 thumb mb-2" href="{{ route('salaries', ['main_machine_type' => $main_machine_type]) }}"><i
                                                    class="bx bxs-file"></i> Salaries</a>
                                            <a class="nav-link border-top-0 thumb mb-2" href="{{ route('machineservicestatesdata', ['main_machine_type' => $main_machine_type]) }}"><i
                                                    class="bx bx-bar-chart"></i> Statistics </a>
                                            <a class="nav-link border-top-0 thumb mb-2" href="{{ route('complaint-types.index', ['main_machine_type' => $main_machine_type]) }}"><i
                                                    class="bx bxs-error"></i> Complaint Types </a>                                           
                                            <a class="nav-link border-top-0 thumb mb-2" href="{{ route('customer-feedback') }}"><i
                                                    class="bx bxs-star"></i> Customer Feedback</a>
                                        </nav>
                                    </div>

                                </div>
                            </div>
            </div>
            <div class="col-lg-8 col-xl-9">
                <div class="card custom-card custom-card">
                                <div class="card-header">
                                    <div class="card-title">Live Location - <button onclick="resetMap()" class="badge bg-outline-primary" style="font-size: 12px; margin-top: 3px;">Reset Zoom</button></div>
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
            center: { lat: 21.2382997, lng: 72.8882355 },
        });

        const engineers = @json($all_present_engineers);

        const grouped = {};
        engineers.forEach(e => {
            const key = `${e.lat},${e.lng}`;
            if (!grouped[key]) grouped[key] = [];
            grouped[key].push(e);
        });

        const markers = [];
        const openInfoWindows = new Map(); // To track each marker’s info window state

        Object.entries(grouped).forEach(([key, group]) => {
            const [lat, lng] = key.split(',').map(parseFloat);

            const marker = new google.maps.Marker({
                position: { lat, lng },
                map,
                title: `${group.length} engineer(s)`
            });

            const content = `
                <div style="max-width:220px;">
                    ${group.map(e => `
                        <div style="margin-bottom:10px; text-align:center;">
                            <img src="https://pickwell.addonwebtech.com/atdselfie/${e.in_selfie}" style="width:55px; height:55px; border-radius:50%; object-fit:cover;"><br>
                            <small>${e.engineer_name}</small>
                        </div>
                    `).join('')}
                </div>
            `;

            const infowindow = new google.maps.InfoWindow({ content });

            // Open on load
            //infowindow.open(map, marker);
            //openInfoWindows.set(marker, true); // Mark it as open

            // Toggle on marker click
            marker.addListener("click", () => {
                const isOpen = openInfoWindows.get(marker);
                if (isOpen) {
                    infowindow.close();
                    openInfoWindows.set(marker, false);
                } else {
                    infowindow.open(map, marker);
                    openInfoWindows.set(marker, true);
                }
            });

            markers.push(marker);
        });

        const clusterer = new markerClusterer.MarkerClusterer({ map, markers });

        clusterer.addListener('clusterclick', (event) => {
            const cluster = event.cluster;
            const position = cluster.position;
            const currentZoom = map.getZoom();
            map.panTo(position);
            map.setZoom(Math.min(currentZoom + 2, 19));
        });
    }

    function resetMap() {
        map.setZoom(11);
        map.setCenter({ lat: 21.2382997, lng: 72.8882355 });
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDu9lahjAIj7IbilbNEAr76hjgUtcq8AM4&loading=async&libraries=maps&v=weekly&callback=initMap" defer>
</script>
<script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
<!-- <script src="https://unpkg.com/@googlemaps/markerclustererplus/dist/index.min.js"></script> -->

 <?php } ?>                       
            @if (Auth::check())

             <!-- ROW-1 -->
                <div class="row">
					<div class="col-xl-3 col-md-6 ">
						<div class="card card-img-holder">
							<div class="card-body">
								<div class="clearfix">
									<div class="float-sm-start">
										<p class="text-muted mb-1">Today Total Complaints</p>
										<h3 class="mb-0">{{ $total_todays_complaints }}</h3>
										    
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
										<p class="text-muted mb-1">Today Done Complaints</p>
										<h3 class="mb-0">{{ $todays_total_done_complaints }}</h3>
										
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
										<p class="text-muted mb-1">Today Pending Complaints</p>
										<h3 class="mb-0">{{ $todays_total_pending_complaints }}</h3>
										
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
										<p class="text-muted mb-1">Today In-Progress Complaints</p>
										<h3 class="mb-0">{{ $todays_total_inprogress_complaints }}</h3>
										
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
    <script src="{{ asset('js/index2.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
@endsection
@endsection

@extends('layouts.app')
@section('title', $title)
@section('content')
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div class="">
                <div class="">
                    <nav>
                        <ol class="breadcrumb mb-1 mb-md-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
							<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('adminusers.index') }}">Users</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <!-- ROW-1 OPEN -->
				<div class="row row-sm">
					<div class="col-xl-3">
						<div class="card custom-card mg-b-20">
							<div class="p-0">
								<div class="ps-0">
									<div class="main-profile-overview">
										<div class="p-4">
											<div class="main-img-user profile-user">
												<img alt="" src="{{ $userDetail->profile ? asset('user_dp/' . $userDetail->profile) : '' }}"><a
													style="height:40px; width:40px;line-height:40px;"
													class="fe fe-edit-2 profile-edit text-primary" data-bs-toggle="tooltip" title="Edit Profile" data-bs-placement="top" data-bs-original-title="Edit Profile" 
													href="#"></a>
													<span class="ms-auto">
                                            </span>
											</div>
											<div class="d-flex justify-content-between mg-b-20">
												<div>
													<h6 class="main-profile-name">{{ $userDetail->name }}</h6>
													<p class="main-profile-name-text">{{ $userDetail->roles->first()->name ?? 'No role assigned' }}</p>
												</div>
											</div>
											<div class="table-responsive" style="margin-top:15px;">
												<table class="table row table-borderless">
													<tbody class="col-lg-12 col-xl-12 p-0">
														<tr>
															<td><strong>ID :</strong> {{ $userDetail->id }}</td>
														</tr>
														<tr>
															<td><strong>Full Name :</strong> {{ $userDetail->name }}</td>
														</tr>
														<tr>
															<td><strong>Phone :</strong> {{ $userDetail->phone_no }} </td>
														</tr>
														<tr>
															<td><strong>Location :</strong> Surat, Gujarat</td>
														</tr>
														<tr>
															<td><strong>Email :</strong> {{ $userDetail->email }}</td>
														</tr>
														<tr>
															<td><strong>Working Hours :</strong> {{ $userDetail->duty_start }} - {{ $userDetail->duty_end }}</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div><!-- main-profile-overview -->
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-9">
						<div class="row row-sm">
							<div class="col-sm-12 col-xl-4 col-lg-12 col-md-12">
								<div class="card  custom-card">
									<div class="card-body">
										<div class="counter-status d-flex md-mb-0">
											<div class="counter-icon rounded-circle bg-primary-transparent">
												<i class="bx bxs-user-account text-primary"></i>
											</div>
											<div class="ms-auto">
												<h5 class="fs-13">Leaves</h5>
												<h2 class="mb-0 fs-22 mb-1 mt-1">{{ $total_leaves }}</h2>
												
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-xl-4 col-lg-12 col-md-12">
								<div class="card  custom-card">
									<div class="card-body">
										<div class="counter-status d-flex md-mb-0">
											<div class="counter-icon rounded-circle bg-danger-transparent">
												<i class="bx bx-calendar text-danger"></i>
											</div>
											<div class="ms-auto">
												<h5 class="fs-13">Date Of Joining</h5>
												<h2 class="mb-0 fs-22 mb-1 mt-1">{{ $userDetail->doj }}</h2>
												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card custom-card main-content-body-profile">
							<nav class="nav main-nav-line mb-0">
								<a class="nav-link active" data-bs-toggle="tab" href="#tab1over">Payroll Details</a>
								<a class="nav-link" data-bs-toggle="tab" href="#tab2rev">Bank Details</a>
								<a class="nav-link" data-bs-toggle="tab" href="#tab3rev">Attendence</a>
								<a class="nav-link" data-bs-toggle="tab" href="#tab4rev">Salaries</a>
								<a class="nav-link" data-bs-toggle="tab" href="#tab5rev">Doccuments</a>
								<a class="nav-link" data-bs-toggle="tab" href="#tab6rev">Locations</a>
							</nav>
							<div class="card-body tab-content p-0 h-100">
								<div class="tab-pane border-0 active" id="tab1over">
									<div class="main-content-label fs-13 mg-b-20">
										Payroll Information
									</div>
									<div class="table-responsive ">
										<table class="table row table-borderless">
											<tbody class="col-lg-8 col-xl-4 p-0">
												<tr>
													<td><strong>Basic Salary :</strong> {{ $userPayroll->basic_sal ?? '' }}</td>
												</tr>
												<tr>
													<td><strong>HRA :</strong> {{ $userPayroll->hra ?? '' }}</td>
												</tr>
												<tr>
													<td><strong>DA :</strong> {{ $userPayroll->da ?? '' }}</td>
												</tr>
												<tr>
													<td><strong>Prophestional Tax :</strong> {{ $userPayroll->pt ?? '' }}</td>
												</tr>
												<tr>
													<td><strong>Aadhar Number :</strong> {{ $userPayroll->aadharno ?? '' }}</td>
												</tr>
											</tbody>
											<tbody class="col-lg-8 col-xl-4 p-0">
												<tr>
													<td><strong>Misc Allowance :</strong> {{ $userPayroll->msc_allow ?? '' }}</td>
												</tr>
												<tr>
													<td><strong>Petrol Allowance :</strong> {{ $userPayroll->ptrl_allow ?? '' }}</td>
												</tr>
												<tr>
													<td><strong>Monthly Salary :</strong> {{ $userPayroll->total_act_sal_monthly ?? '' }} </td>
												</tr>
												<tr>
													<td><strong>Yearly CTC :</strong> {{ $userPayroll->total_ctc_yearly ?? '' }} </td>
												</tr>
												<tr>
													<td><strong>PAN Number :</strong> {{ $userPayroll->panno ?? '' }}</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="tab-pane" id="tab2rev">
									<div class="main-content-label fs-13 mg-b-20">
										Bank Details
									</div>
									<div class="d-flex mb-3">
										<div class="table-responsive">
											<table class="table row table-borderless">
												<tbody class="col-lg-8 col-xl-4 p-0">
													<tr>
														<td><strong>Bank Name :</strong> {{ $userPayroll->bank_name ?? '' }}</td>
													</tr>
													<tr>
														<td><strong>Account Holder Name :</strong> {{ $userPayroll->ahname ?? '' }}</td>
													</tr>
													<tr>
														<td><strong>Account Numnber :</strong> {{ $userPayroll->account_no ?? '' }}</td>
													</tr>
													<tr>
														<td><strong>UPI ID :</strong> {{ $userPayroll->upi_id ?? '' }}</td>
													</tr>
													<tr>
														<td><strong>PAN Number :</strong> {{ $userPayroll->panno ?? '' }}</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="tab3rev">
									<div class="main-content-label fs-13 mg-b-20">
										Attendence
									</div>
									<div class="d-flex mb-3">
										<div class="table-responsive">
											<table class="table row text-nowrap table-bordered" style="margin-left:1px">
												<tbody class="col-lg-9 col-xl-9 p-0">	
												<tr>
													<th><strong>Date</strong></th>
													<th><strong>A/P</strong></th>
													<th><strong>In Image</strong></th>
													<th><strong>In Time</strong></th>
													<th><strong>In Address</strong></th>
													<th><strong>Out Image</strong></th>
													<th><strong>Out Time</strong></th>
													<th><strong>Out Address</strong></th>
													<th><strong>Late Hrs</strong></th>
													<th><strong>Worked Hrs</strong></th>
												</tr>													
												@foreach ($userAttendance as $item)
                                            	<tr>
														<td> {{ $item->in_date ?? '' }}</td>
														<td> {{ $item->ap ?? '' }}</td>
														<td> <img src="https://pickwell.addonwebtech.com/atdselfie/{{ $item->in_selfie ?? '' }}" height="50" width="50" style="border-radius:50%; object-fit:cover;" /></td>
														<td> {{ $item->in_time ?? '' }}</td>
														<td> {{ $item->in_address ?? '' }}</td>
														<td>
														@if($item->out_selfie!='')
															<img src="https://pickwell.addonwebtech.com/atdselfie/{{ $item->out_selfie ?? '' }}" height="50" width="50" style="border-radius:50%; object-fit:cover;" />
														@endif
														</td>
														<td> {{ $item->out_time ?? '' }}</td>
														<td> {{ $item->out_address ?? '' }}</td>
														<td> {{ $item->late_hrs ?? '' }}</td>
														<!-- <td><strong>Early Going hrs :</strong> {{ $item->earligoing_hrs ?? '' }}</td> -->
														<td> {{ $item->working_hrs ?? '' }}</td>
														
														
														

												</tr>
                                        		@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="tab4rev">
									<div class="main-content-label fs-13 mg-b-20">
										Salaries
									</div>
									<div class="d-flex mb-3">
										<div class="table-responsive">
											<table class="table row text-nowrap table-bordered" style="margin-left:1px">
												<tbody class="col-lg-9 col-xl-9 p-0">	
												<tr>
													<th><strong>Salary Date</strong></th>
													<th><strong>Month</strong></th>
													<th><strong>Present Days</strong></th>
													<th><strong>Working Days</strong></th>
													<th><strong>Perday Sal</strong></th>
													<th><strong>Total Salary</strong></th>
												</tr>													
												@foreach ($userSalaries as $item)
                                            	<tr>
														<td> {{ $item->created_at ?? '' }}</td>
														<td> {{ $item->month_id ?? '' }}</td>
														<td> {{ $item->pdays ?? '' }}</td>
														<td> {{ $item->working_days ?? '' }}</td>
														<td> {{ $item->perday_sal ?? '' }}</td>
														<td> {{ $item->total_salary ?? '' }}</td>
												</tr>
                                        		@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="tab5rev">
									<div class="main-content-label fs-13 mg-b-20">
										Doccuments
									</div>
									<div class="d-flex mb-3">
										<div class="row gy-3"> 
											<!-- <div class="col-6 col-md-3"> <img id="profilePreview"
                                            src="{{ $userDetail->aadhar_card ? asset('user_dp/' . $userDetail->aadhar_card) : '' }}"
                                            alt="Profile Preview"
                                            style="max-width: 100px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;"
                                            onclick="showLargeImage(this)"> </div> 
											<div class="row gy-3"> 
											<div class="col-6 col-md-3"> <img id="profilePreview"
                                            src="{{ $userDetail->pan_card ? asset('user_dp/' . $userDetail->pan_card) : '' }}"
                                            alt="Profile Preview"
                                            style="max-width: 100px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;"
                                            onclick="showLargeImage(this)"> </div> -->
											<!-- <div class="col-6 col-md-3"> <img alt="Responsive image" class="img-thumbnail border-0 p-0 br-3" src="../assets/images/media/2.jpg"> </div> 
											<div class="col-6 col-md-3 mg-t-10 mg-sm-t-0"> <img alt="Responsive image" class="img-thumbnail border-0 p-0 br-3" src="../assets/images/media/3.jpg"> </div> 
											<div class="col-6 col-md-3 mg-t-10 mg-sm-t-0"> <img alt="Responsive image" class="img-thumbnail border-0 p-0 br-3" src="../assets/images/media/4.jpg"> </div>  -->
											@php
												$file = $userDetail->aadhar_card;
												$extension = pathinfo($file, PATHINFO_EXTENSION);
											@endphp

											<div class="col-12 col-md-12">
												@if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
													<img id="profilePreview"
														src="{{ asset('user_dp/' . $file) }}"
														alt="Profile Preview"
														style="max-width: 100px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;"
														onclick="showLargeImage(this)">
														<p>View Aadhar</p>
												@elseif(strtolower($extension) === 'pdf')
												<iframe src="{{ asset('user_dp/' . $file) }}" width="100%" height="500px"></iframe>
													<!-- <a href="{{ asset('user_dp/' . $file) }}" target="_blank">
														<img src="{{ asset('images/pdficon.png') }}" alt="PDF" style="max-width: 100px; cursor: pointer;">
														<p>View Aadhar PDF</p>
													</a> -->
													<p>Aadhar PDF</p>
												@else
													<p>No valid preview available</p>
												@endif
											</div>
											@php
												$file = $userDetail->pan_card;
												$extension = pathinfo($file, PATHINFO_EXTENSION);
											@endphp

											<div class="col-12 col-md-12">
												@if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
													<img id="profilePreview"
														src="{{ asset('user_dp/' . $file) }}"
														alt="Profile Preview"
														style="max-width: 100px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;"
														onclick="showLargeImage(this)">
														<p>View PAN</p>
												@elseif(strtolower($extension) === 'pdf')
												<iframe src="{{ asset('user_dp/' . $file) }}" width="100%" height="500px"></iframe>
													<!-- <a href="{{ asset('user_dp/' . $file) }}" target="_blank">
														<img src="{{ asset('images/pdficon.png') }}" alt="PDF" style="max-width: 100px; cursor: pointer;">
														<p>View PAN PDF</p>
													</a> -->
													<p>PAN PDF</p>
												@else
													<p>No valid preview available</p>
												@endif
											</div>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="tab6rev">
									<div class="main-content-label fs-13 mg-b-20">
										Locations
									</div>
									<div class="d-flex mb-3">
										<div class="table-responsive">
											<table class="table row text-nowrap table-bordered" style="margin-left:1px">
												<tbody class="col-lg-9 col-xl-9 p-0">	
												<tr>
													<th><strong>Date</strong></th>
													<th><strong>Latitude</strong></th>
													<th><strong>Longtitude</strong></th>
												</tr>													
												@foreach ($usersLocations as $item)
                                            	<tr>
														<td> {{ $item->created_at ?? '' }}</td>		
														<td> {{ $item->lat ?? '' }}</td>
														<td> {{ $item->lng ?? '' }}</td>
												</tr>
                                        		@endforeach
												</tbody>
											</table>
										</div>
									</div>
									<div>
										<div id="map" style="height: 350px; width: 100%;"></div>
											<script>
											// Define your data before using it in initMap()
											
											const engineerPath = JSON.parse(@json($locationsJson));

    										console.log("Engineer Path:", engineerPath);

											function initMap() {
												if (!engineerPath || !engineerPath.length) {
													console.error("No path data found");
													return;
												}

												// Convert to coordinates
												const routeCoordinates = engineerPath.map(loc => ({
													lat: parseFloat(loc.lat),
													lng: parseFloat(loc.lng)
												}));

												// Create map
												const map = new google.maps.Map(document.getElementById('map'), {
													zoom: 13,
													center: routeCoordinates[0],
												});

												// Draw route line
												new google.maps.Polyline({
													path: routeCoordinates,
													geodesic: true,
													strokeColor: "#FF0000",
													strokeOpacity: 1.0,
													strokeWeight: 3,
													map: map,
												});

												// Start & end markers
												new google.maps.Marker({
													position: routeCoordinates[0],
													map: map,
													label: "Start",
												});

												new google.maps.Marker({
													position: routeCoordinates[routeCoordinates.length - 1],
													map: map,
													label: "End",
												});
											}
										</script>
										<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDu9lahjAIj7IbilbNEAr76hjgUtcq8AM4&loading=async&libraries=maps&v=weekly&callback=initMap" defer></script>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- ROW-1 CLOSED -->

				 <!-- Modal for Large Image Preview -->
				<div id="imageModal" class="modal" tabindex="-1" role="dialog" style="display: none;">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-body">
								<img id="modalImage" src="" alt="Large Preview" style="width: 100%; height: auto;">
							</div>
						</div>
					</div>
				</div>
				<!-- End:: row-1 -->
    </div>

@endsection

@section('scripts')
    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script> --}}
    {{-- <script src="http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script> --}}
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script type="text/javascript">
        // JavaScript function to handle image preview
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('profilePreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }

        // Function to preview the selected image
        function previewImage(event) {
            const preview = document.getElementById('profilePreview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function() {
                    preview.src = reader.result; // Set the small preview to the uploaded image
                };
                reader.readAsDataURL(file);
            }
        }

        // Function to show a large image in the modal
        function showLargeImage(img) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');

            // Ensure the modal's image source is updated immediately
            modalImage.src = img.src;

            // Delay the modal display slightly to ensure the source is updated
            setTimeout(() => {
                modal.style.display = 'block'; // Show the modal
            }, 50);
        }

        // Close the modal when clicking anywhere on it
        document.getElementById('imageModal').onclick = function() {
            this.style.display = 'none';
        };
    </script>
   
@endSection


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
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('machinesalesdata', ['main_machine_type' => $main_machine_type]) }}">{{ $main_machine_name }}</a></li>
							<li class="breadcrumb-item active" aria-current="page"><a href="{{ route('salesusers', ['main_machine_type' => $main_machine_type]) }}">Users</a></li>
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
													href="{{route('saleuseredit', ['main_machine_type' => $main_machine_type, $user_id])}}"></a>
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
							<div class="col-sm-12 col-xl-4 col-lg-12 col-md-12">
								<div class="card  custom-card">
									<div class="card-body">
										<div class="counter-status d-flex md-mb-0">
											<div class="counter-icon rounded-circle bg-danger-transparent">
												<i class="bx bxs-cog text-danger"></i>
											</div>
											<div class="ms-auto">
												<h5 class="fs-13">Machine</h5>
												<h2 class="mb-0 fs-22 mb-1 mt-1">{{ $main_machine_name }}</h2>
												
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
													<th><strong>In Time</strong></th>
													<th><strong>Out Time</strong></th>
													<th><strong>Late Hrs</strong></th>
													<th><strong>Worked Hrs</strong></th>
												</tr>													
												@foreach ($userAttendance as $item)
                                            	<tr>
														<td> {{ $item->in_date ?? '' }}</td>
														<td> {{ $item->ap ?? '' }}</td>
														<td> {{ $item->in_time ?? '' }}</td>
														<td> {{ $item->out_time ?? '' }}</td>
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
							</div>
						</div>
					</div>
				</div>
				<!-- ROW-1 CLOSED -->
    </div>

@endsection

@section('scripts')
    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script> --}}
    {{-- <script src="http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script> --}}

    {{-- {{ $dataTable->scripts(attributes: ['type' => 'module']) }} --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#is_active').select2();
            $('#role').select2();
            window.deleteUser = function(user) {
                // event.preventDefault();
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('users.destroy', ':user') }}".replace(':user',
                                user),
                            data: {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function(data) {
                                if (data.success == 0) {
                                    Swal.fire({
                                        title: "Can't Deleted!",
                                        text: data.message,
                                        icon: "error"
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Record has been deleted.",
                                        icon: "success",
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.reload();
                                        }
                                    });
                                }
                            },
                            error: function(data) {
                                console.log('Error:', data);
                                Swal.fire({
                                    title: "Not Deleted!",
                                    text: data.responseJSON.message,
                                    icon: "error"
                                });
                            }
                        });

                    }
                });
            }


            var table = $('#user-table').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                // dom: 'Bfrtip',
                // buttons: [
                //     'copy', 'csv', 'excel', 'pdf', 'print'
                // ],
                ajax: {
                    url: "{{ route('salesusers', ['main_machine_type' => $main_machine_type]) }}",
                    type: 'GET',
                    data: function(d) {
                        d.is_active = $('select[name="is_active"]').val();
                        d.role = $('select[name="role"]').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'No'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'phone_no',
                        name: 'phone_no'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'roles',
                        name: 'roles'
                    },
                    {
                        data: 'inTime',
                        name: 'inTime'
                    },
                    {
                        data: 'outTime',
                        name: 'outTime'
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        render: function(data, type, row) {
                            // Change text color based on status
                            if (data == 1) {
                                return '<span style="color: Green;">Active</span>';
                            } else if (data != null) {
                                return '<span style="color: red;">Not Active</span>';
                            }
                            return data;
                        }
                    },
                    {
                        data: 'leader',
                        name: 'leader',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#search-button').click(function() {
                table.draw();
            })

            $('#toggleFormBtn').click(function() {
                $('#search-form').toggle(); // This will show or hide the form based on its current state
            });
        });
    </script>
@endSection

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
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Home</li>
                    </ol>
                </nav>
            </div>
        <div>
</div>
</div>
        <div class="row">
            @if (Auth::check())
                <div class="col-sm-12 col-lg-12 col-xl-12 col-xxl-12">
                    <div class="row">
                         @foreach($assignedMachineTypes as $machineTypeKey)
                        <div class="col-sm-4">
                            
                                <div class="card card-dashboard">
                                    <div class="card-body d-flex align-items-center py-3 flex-wrap">
                                        <!-- <span class="avatar avatar-lg rounded-pill bg-primary-transparent" style="margin-right: 10px;">
                                            <i class="bx bx-box text-primary fs-20"></i>
                                        </span> -->
                                        <?php if(auth()->user()->getRoleNames()->first() == "Admin") { ?>
                                        <span class="avatar avatar-xxl me-4 online avatar-rounded" style="width: 8rem; height: 6rem; line-height: 7rem;"> <img src="{{$machineTypeKey->machine_image}}" alt="img"> </span>
                                        <?php } ?>
                                        <?php if(auth()->user()->getRoleNames()->first() == "Service Team Leader") { ?>
                                            <span class="avatar avatar-xxl me-4 online avatar-rounded" style="width: 8rem; height: 6rem; line-height: 7rem;"> <img src="{{$machineTypeKey->machine->machine_image}}" alt="img"> </span>
                                        <?php } ?>
                                        <?php if(auth()->user()->getRoleNames()->first() == "Sales Team Leader") { ?>
                                            <span class="avatar avatar-xxl me-4 online avatar-rounded" style="width: 8rem; height: 6rem; line-height: 7rem;"> <img src="{{$machineTypeKey->machine->machine_image}}" alt="img"> </span>
                                        <?php } ?>
                                        <?php if(auth()->user()->getRoleNames()->first() == "Payroll Manager") { ?>
                                            <span class="avatar avatar-xxl me-4 online avatar-rounded" style="width: 8rem; height: 6rem; line-height: 7rem;"> <img src="{{$machineTypeKey->machine->machine_image}}" alt="img"> </span>
                                        <?php } ?>    
                                        <?php if(auth()->user()->getRoleNames()->first() == "Admin") { ?>
                                        <div class="ms-1 flex-grow-1 d-flex flex-column">
                                            <!-- Machine Name -->
                                            <span class="text-muted dashboardText mb-2">
                                                {{ $machineTypeKey->machine_name }}
                                            </span>
                                            <!-- Buttons Below -->
                                            <div>
                                                <a href="{{ route('machineservicedata', ['main_machine_type' => $machineTypeKey->id]) }}" class="btn btn-primary-light btn-border-down me-2">Services</a>
                                                <a href="{{ route('machinesalesdata', ['main_machine_type' => $machineTypeKey->id]) }}" class="btn btn-primary-light btn-border-down">Sales</a>  
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if(auth()->user()->getRoleNames()->first() == "Service Team Leader") { ?>
                                        <div class="ms-6 flex-grow-1">
                                            <!-- <a href="{{ route('machineservicedata', ['main_machine_type' => $machineTypeKey->machine->id]) }}"  style="line-height: 15px;">
                                            <span class="text-muted dashboardText">
                                                {{ $machineTypeKey->machine->machine_name }} 
                                            </span>
                                            </a> -->
                                            <div class="ms-1 flex-grow-1 d-flex flex-column">
                                            <!-- Machine Name -->
                                            <span class="text-muted dashboardText mb-2">
                                                {{ $machineTypeKey->machine->machine_name }}
                                            </span>
                                            <!-- Buttons Below -->
                                            <div>
                                                <a href="{{ route('machineservicedata', ['main_machine_type' => $machineTypeKey->machine->id]) }}" class="btn btn-primary-light btn-border-down me-2">Services</a>
                                                  
                                            </div>
                                        </div>
                                        </div>
                                        <?php } ?>
                                        <?php if(auth()->user()->getRoleNames()->first() == "Sales Team Leader" || auth()->user()->getRoleNames()->first() == "Payroll Manager") { ?>
                                        <div class="ms-6 flex-grow-1">
                                            <!-- <a href="{{ route('machineservicedata', ['main_machine_type' => $machineTypeKey->machine->id]) }}"  style="line-height: 15px;">
                                            <span class="text-muted dashboardText">
                                                {{ $machineTypeKey->machine->machine_name }} 
                                            </span>
                                            </a> -->
                                            <div class="ms-1 flex-grow-1 d-flex flex-column">
                                            <!-- Machine Name -->
                                            <span class="text-muted dashboardText mb-2">
                                                {{ $machineTypeKey->machine->machine_name }}
                                            </span>
                                            <!-- Buttons Below -->
                                            <div>
                                                <a href="{{ route('machinesalesdata', ['main_machine_type' => $machineTypeKey->machine->id]) }}" class="btn btn-primary-light btn-border-down">Sales</a>  
                                            </div>
                                        </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <?php if(auth()->user()->getRoleNames()->first() == "Service Team Leader" || auth()->user()->getRoleNames()->first() == "Admin") { ?> 
        <div class="row">
            <div class="col-sm-6">
                <div class="card custom-card"> 
                <div class="card-header justify-content-between"> 
                    <div class="card-title"> Service - DPR - {{ $today }} </div>  
                </div> 
                <div class="card-body"> 
                    <div class="table-responsive"> 
                        <table class="table text-nowrap"> 
                            <thead class="table-info">
                                <tr>
                                    <th scope="col">Machine</th>
                                    <th scope="col">Total</th>
                                    <th scope="col"><button class="btn btn-sm btn-success-light">Closed</button></th>
                                    <th scope="col"><button class="btn btn-sm btn-primary-light">In Progress</button></th>
                                    <th scope="col"><button class="btn btn-sm btn-danger-light">Pending</button></th>
                                </tr> 
                            </thead> 
                            <tbody>
                                <tr>
                                    <th scope="row">Airjet</th>
                                    <td>{{ $airjet_total_complaints }}</td>
                                    <td>{{ $airjet_closed_complaints }}</td>
                                    <td>{{ $airjet_in_progress_complaints }}</td>
                                    <td>{{ $airjet_pending_complaints }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Waterjet</th>
                                    <td>{{ $waterjet_total_complaints }}</td>
                                    <td>{{ $waterjet_closed_complaints }}</td>
                                    <td>{{ $waterjet_in_progress_complaints }}</td>
                                    <td>{{ $waterjet_pending_complaints }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Rapier Jaquard</th>
                                    <td>{{ $rapier_total_complaints }}</td>
                                    <td>{{ $rapier_closed_complaints }}</td>
                                    <td>{{ $rapier_in_progress_complaints }}</td>
                                    <td>{{ $rapier_pending_complaints }}</td>
                                </tr>
                                 
                            </tbody>
                        </table> 
                    </div> 
                </div>
            </div>
        </div>   
        <div class="col-sm-6">
            <div class="card custom-card"> 
                <div class="card-header justify-content-between"> 
                    <div class="card-title"> Installation - Visits - DPR - {{ $today }} </div>  
                </div> 
                <div class="card-body"> 
                    <div class="table-responsive"> 
                        <table class="table text-nowrap"> 
                            <thead class="table-info">
                                <tr>
                                    <th scope="col">Machine</th>
                                    <th scope="col">Installations</th>
                                    <th scope="col">Total</th>
                                    <th scope="col"><button class="btn btn-sm btn-success-light">Closed</button></th>
                                    <th scope="col"><button class="btn btn-sm btn-primary-light">In Progress</button></th>
                                    <th scope="col"><button class="btn btn-sm btn-danger-light">Pending</button></th>
                                </tr> 
                            </thead> 
                            <tbody>
                                <tr>
                                    <th scope="row">Airjet</th>
                                    <td>{{ $airjet_closed_installationss }}</td>
                                    <td>{{ $airjet_total_installation }}</td>
                                    <td>{{ $airjet_closed_installation }}</td>
                                    <td>{{ $airjet_in_progress_installation }}</td>
                                    <td>{{ $airjet_pending_installation }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Waterjet</th>
                                    <td>{{ $waterjet_closed_installationss }}</td>
                                    <td>{{ $waterjet_total_installation }}</td>
                                    <td>{{ $waterjet_closed_installation }}</td>
                                    <td>{{ $waterjet_in_progress_installation }}</td>
                                    <td>{{ $waterjet_pending_installation }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Rapier Jaquard</th>
                                    <td>{{ $rapier_closed_installationss }}</td>
                                    <td>{{ $rapier_total_installation }}</td>
                                    <td>{{ $rapier_closed_installation }}</td>
                                    <td>{{ $rapier_in_progress_installation }}</td>
                                    <td>{{ $rapier_pending_installation }}</td>
                                </tr>
                                 
                            </tbody>
                        </table> 
                    </div> 
                </div>
            </div>
        </div> 
        <?php } ?>
        <?php if(auth()->user()->getRoleNames()->first() == "Sales Team Leader" || auth()->user()->getRoleNames()->first() == "Admin" || auth()->user()->getRoleNames()->first() == "Payroll Manager") { ?> 
         <div class="col-sm-6">
            <div class="card custom-card"> 
                <div class="card-header justify-content-between"> 
                    <div class="card-title"> Sales - Leads - DPR - {{ $today }} </div>  
                </div> 
                <div class="card-body"> 
                    <div class="table-responsive"> 
                        <table class="table text-nowrap"> 
                            <thead class="table-info">
                                <tr>
                                    <th scope="col">Machine</th>
                                    <th scope="col">Total</th>
                                    <th scope="col"><button class="btn btn-sm btn-success-light">Closed</button></th>
                                    <th scope="col"><button class="btn btn-sm btn-primary-light">In Progress</button></th>
                                    <th scope="col"><button class="btn btn-sm btn-danger-light">Pending</button></th>
                                </tr> 
                            </thead> 
                            <tbody>
                                <tr>
                                    <th scope="row">Airjet</th>
                                    <td>{{ $airjet_total_leads }}</td>
                                    <td>{{ $airjet_done_leads }}</td>
                                    <td>{{ $airjet_inprogress_leads }}</td>
                                    <td>{{ $airjet_pending_leads }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Waterjet</th>
                                    <td>{{ $waterjet_total_leads }}</td>
                                    <td>{{ $waterjet_done_leads }}</td>
                                    <td>{{ $waterjet_inprogress_leads }}</td>
                                    <td>{{ $waterjet_pending_leads }}</td>
                                </tr>
                                <tr>
                                    <th scope="row">Rapier Jaquard</th>
                                    <td>{{ $rapier_total_leads }}</td>
                                    <td>{{ $rapier_done_leads }}</td>
                                    <td>{{ $rapier_inprogress_leads }}</td>
                                    <td>{{ $rapier_pending_leads }}</td>
                                </tr>
                                 
                            </tbody>
                        </table> 
                    </div> 
                </div>
            </div>
        </div>
        <?php } ?>     
    </div>
</div>    
@endsection

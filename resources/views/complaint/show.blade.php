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
                            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xxl-6 col-xl-8 col-lg-8 col-md-10 col-sm-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">{{ $title }}</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Party Name</th>
                                        <td>{{ $complaint->party->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Party Email</th>
                                        <td>{{ $complaint->party->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Party Mobile</th>
                                        <td>{{ $complaint->party->phone_no }}</td>
                                    </tr>
                                    {{-- <tr>
                                        <th>Complaint</th>
                                        <td>{{ $complaint->complaint }}</td>
                                    </tr> --}}
                                    <tr>
                                        <th>Date</th>
                                        <td>{{ $complaint->date }}</td>
                                    </tr>
                                    <tr>
                                        <th>Time</th>
                                        <td>{{ $complaint->time }}</td>
                                    </tr>
                                    <tr>
                                        <th>Complaint Type</th>
                                        <td>{{ $complaint->ComplaintType->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Machine Number</th>
                                        <td>{{ $complaint->salesEntry->mc_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Product Name</th>
                                        <td>{{ $complaint->product->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Service type Name</th>
                                        <td>{{ $complaint->serviceType->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Complaint No</th>
                                        <td>{{ $complaint->complaint_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Engineer Name</th>
                                        <td>{{ $complaint->engineer->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Engineer In Time</th>
                                        <td>{{ $complaint->engineer_in_time ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Engineer In Date</th>
                                        <td>{{ $complaint->engineer_in_date ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Engineer In Address</th>
                                        <td>{{ $complaint->engineer_in_address ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Engineer Out Time</th>
                                        <td>{{ $complaint->engineer_out_time ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Engineer Out Date</th>
                                        <td>{{ $complaint->engineer_out_date ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Engineer Out Address</th>
                                        <td>{{ $complaint->engineer_out_address ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Is Urgent</th>
                                        <td>{{ $complaint->is_urgent ? 'Yes' : 'No' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Is assigned</th>
                                        <td>{{ $complaint->is_assigned ? 'Yes' : 'No' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Remarks</th>
                                        <td>{{ $complaint->remarks ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>{{ $complaint->status->name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
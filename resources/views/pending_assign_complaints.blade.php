@extends('layouts.app')
@section('title', $title)
@section('content')
    <div class="container-fluid">
        <!-- page-header -->
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div class="">
                <div class="">
                    <nav>
                        <ol class="breadcrumb mb-1 mb-md-0">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">{{ $title }}</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-bordered" id="product-table">
                                <thead class="table-secondary bg-gray">
                                    <tr>
                                        <th>Sr No</th>
                                        <th>Complaint No</th>
                                        <th>Date</th>
                                        <th>Party</th>
                                        <th>Product</th>
                                        <th>M/c Serial No</th>
                                        <th>Machine No</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($pending_assign_complaints) && !empty($pending_assign_complaints))
                                        @php
                                            $totalPendingAssignComplains = 0;
                                        @endphp
                                        @foreach ($pending_assign_complaints as $key => $value)
                                            @php
                                                $totalPendingAssignComplains += $value->engineer_count;
                                            @endphp
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $value->complaint_no }}</td>
                                                <td>{{ $value->date }}</td>
                                                <td>{{ $value->party->name }}</td>
                                                <td>{{ $value->product->name }}</td>
                                                <td>{{ $value->machineSalesEntry->serial_no }}</td>
                                                <td>{{ $value->machineSalesEntry->mc_no }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="6"><b>Total Pending Assign Complaints</b></td>
                                            <td><b>{{ isset($key) ? $key + 1 : 0 }}</b></td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="6"><b>No Record Found!</b></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

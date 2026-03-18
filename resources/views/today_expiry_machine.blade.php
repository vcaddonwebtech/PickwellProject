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
            <div class="col-xl-12">
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
                                        <th>Expiry Date</th>
                                        <th>Party Name</th>
                                        <th>Mobile No</th>
                                        <th>Address</th>
                                        <th>Product</th>
                                        <th>M/c Serial No</th>
                                        <th>Machine No</th>
                                        <th>Contact Person</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($today_expiry_machine) && !empty($today_expiry_machine))
                                        @foreach ($today_expiry_machine as $key => $value)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $value->service_expiry_date }}</td>
                                                <td>{{ $value->party->name }}</td>
                                                <td>{{ $value->party->phone_no }}</td>
                                                <td>{{ $value->party->address }}</td>
                                                <td>{{ $value->product->name }}</td>
                                                <td>{{ $value->serial_no }}</td>
                                                <td>{{ $value->mc_no }}</td>
                                                <td>{{ $value->party->contactPerson->name }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="8"><b>Total Expiry Machines</b></td>
                                            <td><b>{{ isset($key) ? $key + 1 : 0 }}</b></td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="8"><b>No Record Found!</b></td>
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

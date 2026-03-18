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
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-10">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">{{ $title }}</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-bordered" id="product-table">
                                <thead class="table-secondary bg-gray">
                                    <tr>
                                        <th>#</th>
                                        <th>Engineer</th>
                                        <th>Complaint Date</th>
                                        <th>Complaint No</th>
                                        <th>Party Name</th>
                                        <th>Address</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($engineer_last_visit) && !empty($engineer_last_visit))
                                        @foreach ($engineer_last_visit as $key => $value)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $value->name }}</td>
                                                @if ($value->latestComplaint) 
                                                    <td>{{ $value->latestComplaint->date }}</td>
                                                    <td>{{ $value->latestComplaint->complaint_no }}</td>
                                                    @if ($value->latestComplaint->party)
                                                        <td>{{ $value->latestComplaint->party->name }}</td>
                                                    @else
                                                        <td>N/A</td>
                                                    @endif
                                                    <!-- Check if engineer_out_address is null, use engineer_in_address if true -->
                                                    <td>
                                                        {{ $value->latestComplaint->engineer_out_address ?? $value->latestComplaint->engineer_in_address }}
                                                    </td>
                                                @else
                                                    <td colspan="4">No Complaints</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5">No Engineers Found</td>
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

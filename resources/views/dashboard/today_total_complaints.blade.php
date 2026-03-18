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
            <div class="col-xl-6">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">{{ $title }}</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-bordered" id="product-table">
                                <thead class="table-secondary bg-gray">
                                    <tr>
                                        <th>Date</th>
                                        <th>Comp No</th>
                                        <th>Party</th>
                                        <th>Mobile</th>
                                        <th>Machine</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($total_pending_complaints) && !empty($total_pending_complaints))
                                        @foreach ($total_pending_complaints as $key => $value)
                                            <tr>
                                                <td>{{ date('d-m-Y', strtotime($value->date)) }}</td>
                                                <td>{{ $value->complaint_no }}</td>
                                                <td>{{ $value->party->name }}</td>
                                                <td>{{ $value->party->phone_no }}</td>
                                                <td>{{ $value->machineSalesEntry->mc_no }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4"><b>Total Complaints</b></td>
                                            <td><b>{{ isset($key) ? $key + 1 : 0 }}</b></td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="4"><b>No Record Found!</b></td>
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

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
                        <div class="card-title">{{ $title }}
                            {{ '( ' . date('d-m-Y', strtotime($report_date)) . ' )' }}</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-bordered" id="product-table">
                                <thead class="table-secondary bg-gray">
                                    <tr>
                                        <th>Name</th>
                                        <th>In Time</th>
                                        <th>Late Hours</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($today_present_engineers) && !empty($today_present_engineers))
                                        @foreach ($today_present_engineers as $key => $value)
                                            <tr>
                                                <td>{{ $value->users->name }}</td>
                                                <td>{{ date('h:i A', strtotime($value->in_time)) }}</td>
                                                <td>{{ $value->late_hrs }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="2"><b>Total Present Engineer</b></td>
                                            <td><b>{{ isset($key) ? $key + 1 : 0 }}</b></td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="2"><b>No Record Found!</b></td>
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

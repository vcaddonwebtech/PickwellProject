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
                                        <th>Engineer Name</th>
                                        <th>Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($today_eng_done_complaints) && !empty($today_eng_done_complaints))
                                        @php
                                            $totalDoneComplains = 0;
                                        @endphp
                                        @foreach ($today_eng_done_complaints as $key => $value)
                                            @php
                                                $totalDoneComplains += $value->engineer_count;
                                            @endphp
                                            <tr>
                                                <td>{{ !empty($value->engineer) ? $value->engineer->name : '' }}</td>
                                                <td>{{ !empty($value->engineer) ? $value->engineer_count : '' }}</td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td><b>Total Engineer Wise Done Complaints</b></td>
                                            <td><b>{{ $totalDoneComplains }}</b></td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td><b>No Record Found!</b></td>
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

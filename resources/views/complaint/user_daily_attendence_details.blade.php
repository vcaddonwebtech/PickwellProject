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
                            <li class="breadcrumb-item"><a href="{{ route('attendap-today-report') }}">Attendence</a></li>
                            <li class="breadcrumb-item"><a href="{{ url('user-monthly-attendence-list', $user) }}">Monthly Attendence</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $title }} -  {{ $userAttendance->first()?->user?->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div style="padding:15px;">
                        <div class="row card-title align-items-end">
                            <!-- Form Section -->
                            <div class="col-xl-6">
                                <form id="search-form" action="{{ route('user-daily-attendence-details', [$user, $date]) }}">
                                    <input type="hidden" id="engineer_id" value="{{$user}}">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-auto">
                                            <input type="text" class="form-control" placeholder="yyyy-mm-dd" name="date" id="date">
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" id="search-button" class="btn btn-primary"> Filter </button>
                                        </div>
                                        <div class="col-auto"><div id="exportapsum"> </div>
                                        </div>
                                        <div class="col-auto"><a  class="btn btn-primary" href="{{ url('user-daily-attendence-details', $user) }}">Clear Filter</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                @foreach($userAttendance as $attendance)
                                <tbody>
                                    <tr>
                                        <th>Name</th>
                                        <td>{{ $userAttendance->first()?->user?->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Date</th>
                                        <td>{{ $today }}</td>
                                    </tr>
                                    <tr>
                                        <th>A/P</th>
                                        <td>{{ $attendance->ap }}</td>
                                    </tr>
                                    <tr>
                                        <th>In Time</th>
                                        <td>{{ $attendance->in_time }}</td>
                                    </tr>
                                    <tr>
                                        <th>Late Time</th>
                                        <td>{{ $attendance->late_hrs }}</td>
                                    </tr>
                                    <tr>
                                        <th>In Address</th>
                                        <td>{{ $attendance->in_address }}</td>
                                    </tr>
                                    <tr>
                                        <th>In Selfie</th>
                                        <td><img src="https://pickwell.addonwebtech.com/atdselfie/{{ $attendance->in_selfie ?? 'N/A' }} " height="250" width="260" style="border-radius:10%; object-fit:cover;"></td>
                                    </tr>
                                    <tr>
                                        <th>Out Time</th>
                                        <td>{{ $attendance->out_time }}</td>
                                    </tr>
                                    <tr>
                                        <th>Out Address</th>
                                        <td>{{ $attendance->out_address }}</td>
                                    </tr>
                                    <tr>
                                        <th>Out Selfie</th>
                                        <td><img src="https://pickwell.addonwebtech.com/atdselfie/{{ $attendance->out_selfie ?? 'N/A' }} " height="250" width="260" style="border-radius:10%; object-fit:cover;"></td>
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script> --}}
    {{-- <script src="http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script> --}}

    {{-- {{ $dataTable->scripts(attributes: ['type' => 'module']) }} --}}
    <script type="text/javascript">
        $(document).ready(function() {
            
            $('#date').datepicker({
                dateFormat: "yy-mm-dd",  // Change format to d-m-Y (day-month-year)
            });
        });
    </script>
@endSection

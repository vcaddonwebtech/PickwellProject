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
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('machineservicedata', ['main_machine_type' => $main_machine_type]) }}">{{ $main_machine_name }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('ap_summary', ['main_machine_type' => $main_machine_type]) }}">A/P Summary</a></li>
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
                        <div class="card-title">{{ $title }} of <span class="text-primary" style="font-size:16px; font-weight:700">{{$attendtl->users->name}} ({{$attendtl->users->roles[0]->name}})</span></div>
                        <div class="justify-content-end">
                            <a href="{{ route('ap_summary', ['main_machine_type' => $main_machine_type]) }}" class="btn btn-md btn-primary "> Back </a>
                            <button class="btn btn-primary" id="toggleFormBtn"><i class="fa fa-filter"
                                    aria-hidden="true"></i></button>
                        </div>
                    </div>
                   
                    <div class="header-element px-4">
                        <form id="search-form" style="display: none;">
                            <input type="hidden" id="engineer_id" value="{{$attendtl->engineer_id}}">
                            <!-- Add your custom search fields here -->
                            <div class="row mb-5 mt-3">
                                <div class="col col-md-2">
                                    <label for="month">Months</label>
                                    <select name="month" class="form-control" id="month">
                                        <option value="" selected>All</option>
                                        @foreach (App\Models\Months::get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col col-md-2">
                                    <label for="attn">Attendance</label>
                                    <select name="attn" class="form-control" id="attn">
                                        <option value="" selected>All</option>
                                        <option value="P">P</option>
                                        <option value="A">A</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <button type="button" id="search-button" class="btn btn-primary mt-3">Filter</button>
                                </div>
                                <!-- Add more fields as needed -->
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-bordered" id="ap-details">
                                <thead class="table-secondary bg-gray">
                                    <tr>
                                        <th>No.</th>
                                        <th>Date</th>
                                        <th>Day</th>
                                        <th>In Time</th>
                                        <th>Out Time</th>
                                        <th>AP</th>
                                        <th>Present Days</th>
                                        <th>Working Hours</th>
                                        <th>Late Hours</th>
                                        <th>Early Going</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6">Total</th>
                                        <th id="total-pdays">0</th> <!-- Cell for total pdays -->
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#month').select2();
            $('#attn').select2();
            var id = $('#engineer_id').val();
            apDetailAjaxCall(id);
            function apDetailAjaxCall(id) {
                var table = $('#ap-details').DataTable({
                    processing: true,
                    serverSide: true,
                    search: {
                        smart: false
                    },
                    searchinput: false,
                    ajax: {
                        url: "{{ route('ap_detail', ['main_machine_type' => $main_machine_type, 'engineer' => $enginner_id]) }}",
                        type: 'GET',
                        data: function(d) {
                            d.attn = $('#attn').val();
                            d.month = $('#month').val();
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'No'
                        },
                        {
                            data: 'in_date',
                            name: 'in_date'
                        },
                        {
                            data: 'day',
                            name: 'day'
                        },
                        {
                            data: 'in_time',
                            name: 'in_time'
                        },
                        {
                            data: 'out_time',
                            name: 'out_time',
                            render: function(data, type, row) {
                                // Check if data is null or empty
                                return (data === null || data === "") ? '0' : data; // Return 0 if null or empty
                            }
                        },
                        {
                            data: 'ap',
                            name: 'ap'
                        },
                        {
                            data: 'pdays',
                            name: 'pdays'
                        },
                        {
                            data: 'working_hrs',
                            name: 'working_hrs',
                            render: function(data, type, row) {
                                // Check if data is null or empty
                                return (data === null || data === "") ? '0' : data; // Return 0 if null or empty
                            }
                        },
                        {
                            data: 'late_hrs',
                            name: 'late_hrs',
                            render: function(data, type, row) {
                                // Check if data is null or empty
                                return (data === null || data === "") ? '0' : data; // Return 0 if null or empty
                            }
                        },
                        {
                            data: 'earligoing_hrs',
                            name: 'earligoing_hrs',
                            render: function(data, type, row) {
                                // Check if data is null or empty
                                return (data === null || data === "") ? '0' : data; // Return 0 if null or empty
                            }
                        },
                    ],
                    drawCallback: function(settings) {
                        var api = this.api();
                        var totalPdays = api
                            .column(6) // The index of the 'pdays' column (0-based index)
                            .data()
                            .reduce(function(a, b) {
                                return parseFloat(a) + parseFloat(b); // Handle decimal values correctly
                            }, 0);
                        $('#total-pdays').text(totalPdays.toFixed(1)); // Set the total in the footer
                    }
                });
                $('#toggleFormBtn').click(function() {
                    $('#search-form').toggle(); // This will show or hide the form based on its current state
                });
                $('#search-button').click(function() {
                    table.draw();
                });
            }
        });
    </script>
@endSection

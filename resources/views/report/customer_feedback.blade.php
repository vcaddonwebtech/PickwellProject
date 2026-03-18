<!-- resources/views/complaints/report.blade.php -->

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
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">{{ $title }}</div>
                        <div class="justify-content-end">
                            <a href="{{ route('export-customer-feedback') }}" class="btn btn-primary"><i
                                    class="fa fa-download" aria-hidden="true"></i></a>
                            <button class="btn btn-primary" id="toggleFormBtn"><i class="fa fa-filter"
                                    aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="header-element px-4">
                        <form id="search-form" style="display: none;">
                            <!-- Add your custom search fields here -->
                            <div class="row mb-5 mt-3">
                                {{-- <div class="col">
                                    <label for="from_date">Date From</label>
                                    <input type="text" name="from_date" id="from_date" placeholder="Date"
                                        class="form-control">
                                </div>
                                <div class="col">
                                    <label for="to_date">Date To</label>
                                    <input type="text" name="to_date" id="to_date" placeholder="To Date Name"
                                        class="form-control">
                                </div> --}}
                                <div class="col">
                                    <label for="date_range">Date Range</label>
                                    <input type="text" class="form-control" placeholder="Select date range" id="date_range" name="date_range">
                                </div>

                                <div class="col">
                                    <label for="party_id">Party</label>
                                    <select name="party_id" class="form-control" id="party_id">
                                        <option value="" selected>All</option>
                                        @foreach (\App\Models\CustomerFeedback::with('party')->get()->pluck('party')->unique('id') as $user)
                                            @if ($user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col">
                                    <label for="engineer_id">Engineer</label>
                                    <select name="engineer_id" class="form-control" id="engineer_id">
                                        <option value="" selected>All</option>
                                        @foreach (\App\Models\CustomerFeedback::with('engineer')->get()->pluck('engineer')->unique('id') as $user)
                                            @if ($user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            
                                <div class="col">
                                    <label for="star_rating">Rating</label>
                                    <select name="star_rating" class="form-control" id="star_rating">
                                        <option value="" selected>All</option>
                                        <option value="1">1 ★</option>
                                        <option value="2">2 ★</option>
                                        <option value="3">3 ★</option>
                                        <option value="4">4 ★</option>
                                        <option value="5">5 ★</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <button type="button" id="search-button" class="btn btn-primary mt-3">Filter</button>
                                    {{-- <a href="javascript:void(0);" id="download-excel" class="btn btn-primary mt-3">Excel</a> --}}
                                </div>
                                <!-- Add more fields as needed -->
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-bordered" id="customer-feedback-table">
                                <thead class="table-secondary bg-gray">
                                    <tr>
                                        <th>No.</th>
                                        <th>Party</th>
                                        <th>Engineer</th>
                                        <th>Date</th>
                                        <th>Rating</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
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
            // $('#status_id').select2();
            var table = $('#customer-feedback-table').DataTable({
                processing: true,
                serverSide: true,
                search: {
                    smart: false
                },
                searchinput: false,
                ajax: {
                    url: '{{ route('customer-feedback') }}',
                    type: 'GET',
                    data: function(d) {
                        d.date_range = $('#date_range').val();
                        d.party_id = $('#party_id').val();
                        d.engineer_id = $('#engineer_id').val();
                        d.star_rating = $('#star_rating').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'No'
                    },
                    {
                        data: 'party',
                        name: 'party'
                    },
                    {
                        data: 'engineer',
                        name: 'engineer'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'star_rating',
                        name: 'star_rating'
                    },
                    {
                        data: 'remark',
                        name: 'remark'
                    },
                ]
            });
            $('#search-button').click(function() {
                table.draw();
            })

            $('#date_range').daterangepicker({
                locale: {
                    format: 'DD-MM-YYYY',
                },
                autoUpdateInput: false,
                opens: 'right',
            });

            $('#date_range').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' to ' + picker.endDate.format('DD-MM-YYYY'));
            });

            $('#date_range').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
            
            $('#toggleFormBtn').click(function() {
                $('#search-form').toggle(); // This will show or hide the form based on its current state
            });
        });
    </script>
@endsection

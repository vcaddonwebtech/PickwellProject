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
                            <a href="{{ route('export-engineer-done-summary') }}" class="btn btn-primary"><i
                                class="fa fa-download" aria-hidden="true"></i></a>
                            <button class="btn btn-primary" id="toggleFormBtn"><i class="fa fa-filter"
                                    aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="header-element px-4">
                        <form id="search-form" style="display: none;">
                            <!-- Add your custom search fields here -->
                            <div class="row mb-5 mt-3">
                                <div class="col">
                                    <label for="engineer_id">Engineer</label>
                                    <select name="engineer_id" class="form-control" id="engineer_id">
                                        <option value="" selected>All</option>
                                        @foreach (App\Models\User::orderBy('name')->role('Engineer')->where('is_active', 1)->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="date_range">Date Range</label>
                                    <input type="text" class="form-control" placeholder="Select date range" id="date_range" name="date_range">
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
                            <table class="table text-nowrap table-bordered" id="end-done-summary-table">
                                <thead class="table-secondary bg-gray">
                                    <tr>
                                        <th>No.</th>
                                        <th>Engineer Name</th>
                                        <th>Month - Year</th>
                                        <th>Count</th>
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
            $('#engineer_id').select2();
            var table = $('#end-done-summary-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('report.eng-done-summary') }}',
                    type: 'GET',
                    data: function(d) {
                        d.date_range = $('#date_range').val();
                        d.engineer_id = $('#engineer_id').val();
                    }
                },
                order: [[1, 'asc']],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'No'
                    },
                    {
                        data: 'engineer_name',
                        name: 'engineer_name'
                    },
                    {
                        data: 'month_year',
                        name: 'month_year'
                    },
                    {
                        data: 'done_count',
                        name: 'done_count'
                    },
                ]
            });

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

            $('#search-button').click(function() {
                table.draw();
            })

            $('#toggleFormBtn').click(function() {
                $('#search-form').toggle(); // This will show or hide the form based on its current state
            });
        });
    </script>
@endsection

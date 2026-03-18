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
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('machinesalesdata', ['main_machine_type' => $main_machine_type]) }}">{{ $main_machine_name }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sales Leade List</li>
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
                            <a href="{{ route('export-sales-lead') }}" class="btn btn-primary"><i
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
                                    <label for="date_range">Remind/Lead Date Range</label>
                                    <input type="text" class="form-control" placeholder="Select date range" id="date_range" name="date_range">
                                </div>
                                <div class="col">
                                    <label for="next_reminder_date">Remind Date</label>
                                    <input type="text" name="next_reminder_date" id="next_reminder_date" placeholder="dd-mm-yyyy"
                                        class="form-control">
                                </div>
                                <div class="col">
                                    <label for="sale_user_id">Lead User</label>
                                    <select name="sale_user_id" class="form-control" id="sale_user_id">
                                        <option value="" selected>All</option>
                                        @php
                                            // Fetch the data and eager load the related salseUserDetail
                                            $salesPersons = App\Models\SalesPerson::with('salseUserDetail')
                                                ->get()
                                                ->groupBy('sale_user_id'); // Group by sale_user_id
                                
                                            // Remove duplicates and ensure unique sale_user_id values
                                            $salesPersons = $salesPersons->map(function ($items) {
                                                return $items->first(); // Only keep the first item for each sale_user_id
                                            });
                                
                                            // Sort the groups by the salseUserDetail name
                                            $salesPersons = $salesPersons->sortBy(function ($item) {
                                                return $item->salseUserDetail ? $item->salseUserDetail->name : '';
                                            });
                                        @endphp
                                
                                        @foreach ($salesPersons as $item)
                                            @if($item->salseUserDetail) <!-- Ensure salseUserDetail is not null -->
                                                <option value="{{ $item->sale_user_id }}">{{ $item->salseUserDetail->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="sale_assign_user_id">Lead Assign User</label>
                                    <select name="sale_assign_user_id" class="form-control" id="sale_assign_user_id">
                                        <option value="" selected>All</option>
                                        @php
                                            // Fetch the data and eager load the related saleAssignUserDetail
                                            $salesPersons = App\Models\SalesPerson::with('saleAssignUserDetail')
                                                ->get()
                                                ->groupBy('sale_assign_user_id'); // Group by sale_assign_user_id
                                
                                            // Remove duplicates and ensure unique sale_assign_user_id values
                                            $salesPersons = $salesPersons->map(function ($items) {
                                                return $items->first(); // Only keep the first item for each sale_assign_user_id
                                            });
                                
                                            // Sort the groups by the saleAssignUserDetail name
                                            $salesPersons = $salesPersons->sortBy(function ($item) {
                                                return $item->saleAssignUserDetail ? $item->saleAssignUserDetail->name : '';
                                            });
                                        @endphp
                                
                                        @foreach ($salesPersons as $item)
                                            @if($item->saleAssignUserDetail) <!-- Ensure saleAssignUserDetail is not null -->
                                                <option value="{{ $item->sale_assign_user_id }}">{{ $item->saleAssignUserDetail->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="status_id">Status</label>
                                    <select name="status_id" class="form-control" id="status_id">
                                        <option value="" selected>All</option>
                                        @foreach (App\Models\Priority::all()->where('is_status', 1) as $item)
                                            <option value="{{ $item->id }}">{{ $item->priority }}</option>
                                        @endforeach
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
                            <table class="table text-nowrap table-bordered" id="salse-lead-report-table">
                                <thead class="table-secondary bg-gray">
                                    <tr>
                                        <th>No.</th>
                                        <th>Party Name</th>
                                        <th>Mobile</th>
                                        <th>Address</th>
                                        <th>Lead User</th>
                                        <th>Lead Assign User</th>
                                        <th>Lead Date & Time</th>
                                        <th>Remind Date & Time</th>
                                        <th>Status</th>
                                        <th>Comments</th>
                                        <th>Action</th>
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
            $('#sale_user_id').select2();
            $('#sale_assign_user_id').select2();
            $('#status_id').select2();
            var table = $('#salse-lead-report-table').DataTable({
                processing: true,
                serverSide: true,
                // dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                search: {
                    smart: false
                },
                searchinput: false,
                ajax: {
                    url: "{{ route('salse-lead-report', ['main_machine_type' => $main_machine_type]) }}", 
                    type: 'GET',
                    data: function(d) {
                        d.date_range = $('#date_range').val();
                        d.next_reminder_date = $('#next_reminder_date').val();
                        d.sale_user_id = $('#sale_user_id').val();
                        d.sale_assign_user_id = $('#sale_assign_user_id').val();
                        d.status_id = $('#status_id').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'No'
                    },
                    {
                        data: 'partyname',
                        name: 'partyname'
                    },
                    {
                        data: 'mobile_no',
                        name: 'mobile_no'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'sale_user',
                        name: 'sale_user'
                    },
                    {
                        data: 'sale_assign_user',
                        name: 'sale_assign_user'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'next_reminder_date',
                        name: 'next_reminder_date'
                    },
                    {
                        data: 'prioritys.priority',
                        name: 'prioritys.priority'
                    },
                    {
                        data: 'comments',
                        name: 'comments'
                    },
                    {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                    }
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

            $('#next_reminder_date').datepicker({
                dateFormat: "dd-mm-yy",
            });
        });

        $('#toggleFormBtn').click(function() {
            $('#search-form').toggle(); // This will show or hide the form based on its current state
        });
    </script>
@endsection

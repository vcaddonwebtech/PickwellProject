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
                            <a href="{{ route('report.export-engineer-complaints') }}" class="btn btn-primary"><i
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
                                    <label for="date_range">Date Range</label>
                                    <input type="text" class="form-control" placeholder="Select date range" id="date_range" name="date_range">
                                </div>
                                <div class="col">
                                    <label for="party_id">Party Name</label>
                                    <select name="party_id" class="form-control" id="party_id">
                                        <option value="" selected>All</option>
                                        @foreach (App\Models\Party::orderBy('name')->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="engineer_id">Engineer</label>
                                    <select name="engineer_id" class="form-control" id="engineer_id">
                                        <option value="" selected>All</option>
                                        @foreach (App\Models\User::orderBy('name')->role('Engineer')->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <button type="button" id="search-button" class="btn btn-primary mt-3">Filter</button>
                                    <a href="javascript:void(0);" id="download-excel" class="btn btn-primary mt-3">Excel</a>
                                </div>
                                <!-- Add more fields as needed -->
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-bordered" id="engineer-visit-table">
                                <thead class="table-secondary bg-gray">
                                    <tr>
                                        <th>No.</th>
                                        <th>Done Date</th>
                                        <th>Complain Date</th>
                                        <th>Complaint No</th>
                                        <th>Party Code</th>
                                        <th>Party Name</th>
                                        <th>Party Phone</th>
                                        <th>Engineer</th>
                                        <th>Time Duration</th>
                                        <th>Complaint Type</th>
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
            $('#party_id').select2();
            $('#engineer_id').select2();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            var table = $('#engineer-visit-table').DataTable({
                processing: true,
                serverSide: true,
                search: {
                    smart: false
                },
                searchinput: false,
                ajax: {
                    url: '{{ route('report.engineervisit') }}',
                    type: 'GET',
                    data: function(d) {
                        d.date_range = $('#date_range').val();
                        d.party_id = $('#party_id').val();
                        d.engineer_id = $('#engineer_id').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'No'
                    },
                    {
                        data: 'engineer_out_date',
                        name: 'engineer_out_date'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'complaint_no',
                        name: 'complaint_no'
                    },
                    {
                        data: 'party_code',
                        name: 'party_code'
                    },
                    {
                        data: 'party_name',
                        name: 'party_name'
                    },
                    {
                        data: 'party_phone',
                        name: 'party_phone'
                    },
                    {
                        data: 'engineer_name',
                        name: 'engineer_name'
                    },
                    {
                        data: 'engineer_time_duration',
                        name: 'engineer_time_duration'
                    },
                    {
                        data: 'complaint_type',
                        name: 'complaint_type'
                    }
                ]
            });
            $('#search-button').click(function() {
                table.draw();
            })

            $('#toggleFormBtn').click(function() {
                $('#search-form').toggle(); // This will show or hide the form based on its current state
            });

            // Download Excel
            $('#download-excel').click(function() {
                const params = {
                    engineer_visit_from: $('#engineer_visit_from').val(),
                    engineer_visit_to: $('#engineer_visit_to').val(),
                    party_id: $('#party_id').val(),
                    engineer_id: $('#engineer_id').val(),
                    _token: '{{ csrf_token() }}'
                };

                const query = $.param(params);
                window.location.href = '{{ route('report.export-engineer-complaints') }}' + '?' + query;
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
        });
    </script>
@endSection

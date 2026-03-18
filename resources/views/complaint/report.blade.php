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
                            <a href="{{ route('export-complaint-status') }}" class="btn btn-primary"><i
                                class="fa fa-download" aria-hidden="true"></i></a>
                            <button class="btn btn-primary" id="toggleFormBtn"><i class="fa fa-filter"
                                    aria-hidden="true"></i></button>
                        </div>

                        {{-- <div class="prism-toggle d-flex gap-3">
                        <div class="header-element profile-1">
                            <a href="{{ route('complaints.create') }}" class="btn btn-md btn-primary "> Create </a>
                        </div>

                    </div> --}}

                    </div>
                    <div class="header-element px-4">
                        <form id="search-form" style="display: none;">
                            <!-- Add your custom search fields here -->
                            <div class="row mb-3 mt-3">
                                <div class="col">
                                    <label for="complaint_no">Complaint No</label>
                                    <input type="text" name="complaint_no" id="complaint_no" placeholder="Complaint no"
                                        class="form-control">
                                </div>
                                <div class="col">
                                    <label for="date_range">Date Range</label>
                                    <input type="text" class="form-control" placeholder="Select date range" id="date_range" name="date_range">
                                </div>
                                <div class="col">
                                    <label for="mobile_no">Mobile No</label>
                                    <input type="text" name="phone_no" id="phone_no" placeholder="Mobile no"
                                        class="form-control">
                                </div>
                                <div class="col">
                                    <label for="name">Owner Name</label>
                                    <Select name="owner_id" class="form-control mt-1" id="owner_id">
                                        <option value="" selected>All</option>
                                        @foreach (App\Models\Owner::orderBy('name')->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </Select>
                                </div>
                                <div class="col">
                                    <label for="name">Party Name</label>
                                    <Select name="party_id" id="party_id" class="form-control mt-1">
                                        <option value="" selected>All</option>
                                        @foreach (App\Models\Party::orderBy('name')->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </Select>
                                </div>
                                <div class="col">
                                    <label for="name">Engineer</label>
                                    <Select name="engineer_id" id="engineer_id" class="form-control mt-1">
                                        <option value="" selected>All</option>
                                        @foreach (App\Models\User::role('Engineer')->orderBy('name')->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </Select>
                                </div>
                                <div class="col">
                                    <label for="name">Status</label>
                                    <Select name="status_id" id="status_id" class="form-control mt-1">
                                        <option value="" selected>All</option>
                                        @foreach (App\Models\Status::all() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </Select>
                                </div>
                                <div class="col">
                                    <label for="name">Service Type</label>
                                    <Select name="service_type_id" id="service_type_id" class="form-control mt-1">
                                        <option value="" selected>All</option>
                                        @foreach (App\Models\ServiceType::orderBy('name')->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </Select>
                                </div>
                                <div class="col">
                                    <label for="name">Complaint Type</label>
                                    <Select name="complaint_type_id" id="complaint_type_id" class="form-control mt-1">
                                        <option value="" selected>All</option>
                                        @foreach (App\Models\ComplaintType::orderBy('name')->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </Select>
                                </div>
                                <div class="col">
                                    <button type="button" id="search-button" class="btn btn-primary mt-3">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-bordered" id="complaint-table">
                                <thead class="table-secondary bg-gray">
                                    <tr>
                                        <th>No.</th>
                                        <th>Date</th>
                                        <th>Engineer Out Date</th>
                                        <th>Time</th>
                                        <th>Complaint No</th>
                                        <th>Party</th>
                                        <th>Address</th>
                                        <th>Mobile No.</th>
                                        <th>Area</th>
                                        <th>Product</th>
                                        <th>Product Serial</th>
                                        <th>Mc no.</th>
                                        <th>Complaint Type.</th>
                                        <th>Service Type.</th>
                                        <th>Status</th>
                                        <th>Engineer Name</th>
                                        <th>Days</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>


                        {{-- {{ $dataTable->table() }} --}}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#owner_id').select2();
            $('#party_id').select2();
            $('#engineer_id').select2();
            $('#status_id').select2();
            $('#service_type_id').select2();
            $('#complaint_type_id').select2();
            var table = $('#complaint-table').DataTable({
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
                    url: '{{ route('complaints.report') }}',
                    type: 'GET',
                    data: function(d) {
                        d.complaint_no = $('#complaint_no').val();
                        d.date_range = $('#date_range').val();
                        d.phone_no = $('#phone_no').val();
                        d.owner_id = $('#owner_id').val();
                        d.party_id = $('#party_id').val();
                        d.engineer_id = $('#engineer_id').val();
                        d.status_id = $('#status_id').val();
                        d.service_type_id = $('#service_type_id').val();
                        d.complaint_type_id = $('#complaint_type_id').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'No'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'engineer_out_date',
                        name: 'engineer_out_date'
                    },
                    {
                        data: 'time',
                        name: 'time'
                    },
                    {
                        data: 'complaint_no',
                        name: 'complaint_no'
                    },
                    {
                        data: 'party',
                        name: 'party'
                    },
                    {
                        data: 'address',
                        name: 'address',
                        title: 'Address'
                    },
                    {
                        data: 'mobile_no',
                        name: 'mobile_no',
                        title: 'Mobile Number'
                    },
                    {
                        data: 'area',
                        name: 'area'
                    },
                    {
                        data: 'product',
                        name: 'product'
                    },
                    {
                        data: 'product_serial',
                        name: 'product_serial',
                        title: 'Serial No'
                    },
                    {
                        data: 'mc_no',
                        name: 'mc_no',
                        title: 'Machine No'
                    },
                    {
                        data: 'complaint_type',
                        name: 'complaint_type'
                    },
                    {
                        data: 'service_type',
                        name: 'service_type'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'engineer',
                        name: 'engineer'
                    },
                    {
                        data: 'days',
                        name: 'days'
                    },
                ]
            });
            $('#search-button').click(function() {
                table.draw();
            })

            // $('#complaint-table_filter').hide();
            $('#toggleFormBtn').click(function() {
                $('#search-form').toggle(); // This will show or hide the form based on its current state
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
@endsection

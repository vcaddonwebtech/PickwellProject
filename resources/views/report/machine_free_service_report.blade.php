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
                            <a href="{{ route('report.export-free-service') }}" class="btn btn-primary"><i
                                    class="fa fa-download" aria-hidden="true"></i></a>
                            <button class="btn btn-primary" id="toggleFormBtn"><i class="fa fa-filter"
                                    aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="header-element px-4">
                        <form id="search-form" style="display: none;">
                            <!-- Add your custom search fields here -->
                            <div class="row mb-5 mt-3">
                                <div class="col-md-2">
                                    <label for="date_range">Free Service Date Range</label>
                                    <input type="text" class="form-control" placeholder="Select date range" id="date_range" name="date_range">
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
                            <table class="table text-nowrap table-bordered" id="expiry-report-table">
                                <thead class="table-secondary bg-gray">
                                    <tr>
                                        <th>No.</th>
                                        <th>Free Service Date</th>
                                        <th>complaint Date</th>
                                        <th>complaint No</th>
                                        <th>Party</th>
                                        <th>Mobile No.</th>
                                        <th>Address</th>
                                        <th>Product</th>
                                        <th>Eng Out Date</th>
                                        <th>Status</th>
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
            var table = $('#expiry-report-table').DataTable({
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
                    url: '{{ route('report.free-service-report') }}',
                    type: 'GET',
                    data: function(d) {
                        d.date_range = $('#date_range').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'No'
                    },
                    {
                        data: 'free_service_date',
                        name: 'free_service_date'
                    },
                    {
                        data: 'complaint_date',
                        name: 'complaint_date'
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
                        data: 'mobile_no',
                        name: 'mobile_no'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'product',
                        name: 'product'
                    },
                    {
                        data: 'engineer_out_date',
                        name: 'engineer_out_date'
                    },
                    {
                        data: 'engineer_out_date',
                        name: 'engineer_out_date',
                        render: function(data, type, row) {
                            // Change text color based on status
                            if (data == null) {
                                return '<span style="color: red;">Pending</span>';
                            } else if (data != null) {
                                return '<span style="color: blue;">Closed</span>';
                            }
                            return data;
                        }
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
            // $('#complaint-table_filter').hide();
        });

        $('#toggleFormBtn').click(function() {
            $('#search-form').toggle(); // This will show or hide the form based on its current state
        });

        // Download Excel
        $('#download-excel').click(function() {
            const params = {
                free_service_date_from: $('#free_service_date_from').val(),
                free_service_date_to: $('#free_service_date_to').val(),
                status_id: $('#status_id').val(),
                _token: '{{ csrf_token() }}'
            };

            const query = $.param(params);
            window.location.href = '{{ route('report.export-free-service') }}' + '?' + query;
        });
    </script>
@endsection

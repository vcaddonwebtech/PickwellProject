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
                            <a href="{{ route('report.export-machine-salse') }}" class="btn btn-primary"><i
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
                                    <label for="party_id">Party</label>
                                    <select name="party_id" class="form-control" id="party_id">
                                        <option value="" selected>All</option>
                                        @foreach (App\Models\Party::all() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="product_id">Product</label>
                                    <select name="product_id" class="form-control" id="product_id">
                                        <option value="" selected>All</option>
                                        @foreach (App\Models\Product::all() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="area_id">Area</label>
                                    <select name="area_id" class="form-control" id="area_id">
                                        <option value="" selected>All</option>
                                        @foreach (App\Models\Area::all() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="is_active">Status</label>
                                    <select name="is_active" class="form-control" id="is_active">
                                        <option value="" selected>All</option>
                                        <option value="1">Active</option>
                                        <option value="0">Not Active</option>
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
                            <table class="table text-nowrap table-bordered" id="expiry-report-table">
                                <thead class="table-secondary bg-gray">
                                    <tr>
                                        <th>No.</th>
                                        <th>Date</th>
                                        <th>Order No</th>
                                        <th>Party</th>
                                        <th>Mobile</th>
                                        <th>Address</th>
                                        <th>Area</th>
                                        <th>Product Name</th>
                                        <th>Serial No</th>
                                        <th>M/C No</th>
                                        <th>Install Date</th>
                                        <th>Service Expiry Date</th>
                                        <th>Free Service</th>
                                        <th>Service type</th>
                                        <th>Is Active</th>
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
            $('#product_id').select2();
            $('#area_id').select2();
            $('#is_active').select2();
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
                    url: '{{ route('report.machine-salse-report') }}',
                    type: 'GET',
                    data: function(d) {
                        d.date_range = $('#date_range').val();
                        d.party_id = $('#party_id').val();
                        d.product_id = $('#product_id').val();
                        d.area_id = $('#area_id').val();
                        d.is_active = $('#is_active').val();
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
                        data: 'order_no',
                        name: 'order_no'
                    },
                    {
                        data: 'party.name',
                        name: 'party_name'
                    },
                    {
                        data: 'party.phone_no',
                        name: 'party_phone_no'
                    },
                    {
                        data: 'party.address',
                        name: 'party_address'
                    },
                    {
                        data: 'party.area.name',
                        name: 'party_area'
                    },
                    {
                        data: 'product.name',
                        name: 'product'
                    },
                    {
                        data: 'serial_no',
                        name: 'serial_no'
                    },
                    {
                        data: 'mc_no',
                        name: 'mc_no'
                    },
                    {
                        data: 'install_date',
                        name: 'install_date'
                    },
                    {
                        data: 'service_expiry_date',
                        name: 'service_expiry_date'
                    },
                    {
                        data: 'free_service',
                        name: 'free_service'
                    },
                    {
                        data: 'service_type.name',
                        name: 'service_type.name',
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        render: function(data, type, row) {
                            // Change text color based on status
                            if (data == 1) {
                                return '<span style="color: Green;">Active</span>';
                            } else if (data != null) {
                                return '<span style="color: red;">Not Active</span>';
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
                from_date: $('#from_date').val(),
                to_date: $('#to_date').val(),
                party_id: $('#party_id').val(),
                product_id: $('#product_id').val(),
                area_id: $('#area_id').val(),
                is_active: $('#is_active').val(),
                _token: '{{ csrf_token() }}'
            };

            const query = $.param(params);
            window.location.href = '{{ route('report.export-machine-salse') }}' + '?' + query;
        });
    </script>
@endsection

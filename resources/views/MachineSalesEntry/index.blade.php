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
                            <button class="btn btn-primary" id="toggleFormBtn"><i class="fa fa-filter" aria-hidden="true"></i></button>  | 
                            <a href="{{ route('MachineSales.addmachine-oldcust', ['main_machine_type' => $main_machine_type]) }}" class="btn btn-md btn-primary "> Exsisting Customer </a> | 
                            <a href="{{ route('MachineSales.new-create', ['main_machine_type' => $main_machine_type]) }}" class="btn btn-md btn-primary "> New Customer</a>
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
                                    <label for="product_id">Product</label>
                                    <select name="product_id" class="form-control" id="product_id">
                                        <option value="" selected>All</option>
                                        @foreach (App\Models\Product::orderBy('name')->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    {{-- <label for="to_date"></label> --}}
                                    <button type="button" id="search-button" class="btn btn-primary mt-3">Filter</button>
                                </div>
                                <!-- Add more fields as needed -->
                            </div>

                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-bordered" id="sales-table">
                                <thead class="table-secondary bg-gray">
                                    <tr>
                                        <th>No.</th>
                                        <th>Party Name</th>
                                        <th>Party Phone</th>
                                        <th>Total Machines</th>
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
    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
<script src="http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script> --}}

    <script type="text/javascript">
        $(document).ready(function() {
            $('#party_id').select2();
            $('#product_id').select2();
            var table = $('#sales-table').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                // dom: 'Bfrtip',
                // buttons: [
                //     'copy', 'csv', 'excel', 'pdf', 'print'
                // ],
                data: {
                    engineer_name: $('input[name="engineer_name"]').val(),
                },
                dom: '<"top row"<"col-2"l><"col-8"B><"col-2"f>>rt<"bottom"ip><"clear">',
                buttons: ['excel', 'pdf', 'print'],
                lengthMenu: [10, 25, 50, 100],
                ajax: {
                    url: "{{ route('MachineSales.index', ['main_machine_type' => $main_machine_type]) }}",
                    type: 'GET',
                    data: function(d) {
                        d.date_range = $('#date_range').val();
                        d.party_id = $('select[name="party_id"]').val();
                        d.product_id = $('select[name="product_id"]').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    }, 
                    {
                        data: 'party.name',
                        name: 'party.name'
                    },
                    {
                        data: 'party.phone_no',
                        name: 'party.phone_no'
                    },
                    {
                        data: 'total_machines',
                        name: 'total_machines'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [[3, 'desc']]
            });
            $('#search-button').click(function() {
                table.draw();
            })

            window.deleteParty = function(MachineSales) {
                // event.preventDefault();
                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ route('MachineSales.destroy', ':MachineSales') }}".replace(
                                ':MachineSales', MachineSales),
                            data: {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function(data) {
                                if (data.success == 0) {
                                    Swal.fire({
                                        title: "Can't Deleted!",
                                        text: data.message,
                                        icon: "error"
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Deleted!",
                                        text: "Record has been deleted.",
                                        icon: "success",
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.reload();
                                        }
                                    });
                                }
                            },
                            error: function(data) {
                                Swal.fire({
                                    title: "Can't Deleted!",
                                    text: data.responseJSON.message,
                                    icon: "error"
                                });
                            }
                        });

                    }
                });
            }

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
@endSection

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
                        <div class="d-flex gap-2">
                            <div class="form-check form-check-md form-switch d-flex align-items-center pe-3">
                                <input type="checkbox" name="today_complain" id="today_complain"
                                    class="form-check-input toggle-switch" value="0" role="switch">
                                <label class="mb-0 col-form-label" for="today_complain">Today Complain
                                    {{ '(' . $todayComplain . ')' ?? 0 }}</label>
                            </div>
                            <div class="form-check form-check-md form-switch d-flex align-items-center pe-3">
                                <input type="checkbox" name="today_done" id="today_done"
                                    class="form-check-input toggle-switch" value="0" role="switch">
                                <label class="mb-0 col-form-label" for="today_done">Today Done
                                    {{ '(' . $todayDone . ')' ?? 0 }}</label>
                            </div>
                            <div class="form-check form-check-md form-switch d-flex align-items-center pe-3">
                                <input type="checkbox" name="till_not_assign" id="till_not_assign"
                                    class="form-check-input toggle-switch" value="0" role="switch">
                                <label class="mb-0 col-form-label" for="till_not_assign">Till Not Assign
                                    {{ '(' . $tillNotAssign . ')' ?? 0 }}</label>
                            </div>
                            <div class="form-check form-check-md form-switch d-flex align-items-center pe-3">
                                <input type="checkbox" name="till_in_progress" id="till_in_progress"
                                    class="form-check-input toggle-switch" value="0" role="switch">
                                <label class="mb-0 col-form-label" for="till_in_progress">Till In Progress
                                    {{ '(' . $tillInProgress . ')' ?? 0 }}</label>
                            </div>
                            <div class="form-check form-check-md form-switch d-flex align-items-center pe-3">
                                <input type="checkbox" name="till_not_in" id="till_not_in"
                                    class="form-check-input toggle-switch" value="0" role="switch">
                                <label class="mb-0 col-form-label" for="till_not_in">Till Not In
                                    {{ '(' . $tillNotIn . ')' ?? 0 }}</label>
                            </div>
                            <div class="form-check form-check-md form-switch d-flex align-items-center pe-3">
                                <input type="checkbox" name="till_pending" id="till_pending"
                                    class="form-check-input toggle-switch" value="0" role="switch">
                                <label class="mb-0 col-form-label" for="till_pending">Till Pending
                                    {{ '(' . $tillPending . ')' ?? 0 }}</label>
                            </div>
                        </div>
                        <div class="justify-content-end">
                            <button class="btn btn-primary" id="toggleFormBtn"><i class="fa fa-filter"
                                    aria-hidden="true"></i></button>
                            
                        </div>
                    </div>
                    <div class="header-element px-4">
                        <form id="search-form" style="display: none;">
                            <div class="row mb-5 mt-3">
                                <div class="col">
                                    <label for="complaint_no">Complaint No</label>
                                    <input type="text" name="complaint_no" id="complaint_no" placeholder="complaint no" class="form-control mt-1">
                                </div>
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
                                    <label for="to_date">Engineer Name</label>
                                    <select name="engineer_id" class="form-control" id="engineer_id">
                                        <option value="" selected>All</option>
                                        @foreach (App\Models\User::orderBy('name')->role('Engineer')->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control" id="status">
                                        <option value="" selected>All</option>
                                        @foreach (App\Models\Status::all() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="service_type_id">Service Type</label>
                                    <select name="service_type_id" class="form-control" id="service_type_id">
                                        <option value="" selected>All</option>
                                        <option value="2">Free</option>
                                        <option value="3">Paid</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <button type="button" id="search-button"
                                        class="btn btn-primary mt-3">Filter</button>
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
                                        <th>Time</th>
                                        <th>Product</th>
                                        <th>Mc no.</th>
                                        <th>Party Name</th>
                                        <th>Party Mobile No.</th>
                                        <th>Complaint Type.</th>
                                        <th>Service Type.</th>
                                        <th>Status</th>
                                        <th>Engineer Name</th>
                                        <th>Action</th>
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

    <div class="modal fade" id="addItem" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Item Parts</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('complaints.itemPartStore') }}">
                        @csrf
                        <input type="hidden" name="complaint_id" value="" id="complaint_id">
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Part Name</label>
                            <select name="part_id" class="form-control" id="part_id">
                                @foreach (App\Models\ItemPart::all() as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Quantity:</label>
                            <input type="number" class="form-control" id="quantity" name="quantity">
                        </div>

                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Remark:</label>
                            <textarea class="form-control" id="remark" name="remark"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Urgent</label>
                            <input type="checkbox" name="remark" id="remark" class="form-check-input"
                                value="0">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" value="submit" class="btn btn-primary">
                </div>
                </form>
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
            $('#status').select2();
            $('#party_id').select2();
            $('#engineer_id').select2();
            $('#service_type_id').select2();
            
            dataTable();

            function dataTable() {
                $('#complaint-table').DataTable().clear().destroy();
                var table = $('#complaint-table').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: true,
                    // dom: 'Bfrtip',
                    // buttons: [
                    //     'copy', 'csv', 'excel', 'pdf', 'print'
                    // ],
                    data: {
                        engineer_id: $('input[name="engineer_id"]').val(),
                    },
                    ajax: {
                        url: "{{ route('complaints.index', ['main_machine_type' => $main_machine_type]) }}",
                        type: 'GET',
                        data: function(d) {
                            d.engineer_id = $('select[name="engineer_id"]').val();
                            d.date_range = $('#date_range').val();
                            d.status = $('select[name="status"]').val();
                            d.service_type_id = $('select[name="service_type_id"]').val();
                            d.complaint_no = $('input[name="complaint_no"]').val();
                            d.party_id = $('select[name="party_id"]').val();
                            d.today_complain = $('input[name="today_complain"]').val();
                            d.today_done = $('input[name="today_done"]').val();
                            d.till_not_assign = $('input[name="till_not_assign"]').val();
                            d.till_in_progress = $('input[name="till_in_progress"]').val();
                            d.till_pending = $('input[name="till_pending"]').val();
                            d.till_not_in = $('input[name="till_not_in"]').val();
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'No',
                            render: function(data, type, row) {
                                if (row.is_customer_complaint == 1) {
                                    return data +
                                        ' <span style="color: red; font-size: 15px;">C</span>';
                                }
                                return data;
                            }
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'time',
                            name: 'time'
                        },
                        {
                            data: 'product.name',
                            name: 'product.name'
                        },
                        {
                            data: 'mc_no',
                            name: 'mc_no',
                            title: 'M/c No'
                        },
                        {
                            data: 'party.name',
                            name: 'party.name',
                            title: 'Party Name'
                        },
                        {
                            data: 'party.phone_no',
                            name: 'party.phone_no',
                            title: 'Party M No'
                        },
                        {
                            data: 'complaint_type.name',
                            name: 'complaint_type'
                        },
                        {
                            data: 'service_type.name',
                            name: 'service_type.name'
                        },
                        {
                            data: 'status.id',
                            name: 'status',
                            render: function(data, type, row) {
                                // Change text color based on status
                                if (data == '1') {
                                    return '<span style="color: red;">Pending</span>';
                                } else if (data == '2') {
                                    return '<span style="color: blue;">In Progress</span>';
                                } else if (data == '3') {
                                    return '<span style="color: blue;">Closed</span>';
                                }
                                return data;
                            }
                        },
                        {
                            data: 'engineer.name',
                            name: 'engineer.name',
                            render: function(data, type, row) {
                                return data && data.trim() !== '' ? data : 'N/A';
                            }
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                });
                $('#search-button').click(function() {
                    table.draw();
                })
            }

            // Uncheck all other checkboxes
            $('.toggle-switch').on('change', function() {
                $('.toggle-switch').not(this).prop('checked', false);

                // Set values based on toggle state
                $('.toggle-switch').each(function() {
                    const isChecked = $(this).is(':checked');
                    $(this).val(isChecked ? '1' : '0'); // Set value to 1 if checked, otherwise 0
                });

                dataTable();
            });

            window.deleteParty = function(complaint) {
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
                            url: "{{ route('complaints.destroy', ':complaint') }}".replace(
                                ':complaint', complaint),
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
            window.addItemPart = function(complaint) {
                var addItem = new bootstrap.Modal(document.getElementById('addItem'), {
                    keyboard: false
                })

                addItem.show();
                $('#complaint_id').val(complaint);
            }

            $('#toggleFormBtn').click(function() {
                $('#search-form').toggle(); // This will show or hide the form based on its current state

                // Disable all toggle switches and set the value to be 0
                $('.toggle-switch').each(function() {
                    $(this).prop('checked', false); // Uncheck the toggle
                    $(this).val('0'); // Set the value to 0
                });

                // Reinitialize the datatable
                dataTable();
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

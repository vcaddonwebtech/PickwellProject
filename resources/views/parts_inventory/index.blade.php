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
                        <button class="btn btn-primary" id="toggleFormBtn"><i class="fa fa-filter"
                                aria-hidden="true"></i></button>
                                <a href="{{ route('parts_inventory.create') }}" class="btn btn-md btn-primary "> Create </a>
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
                                <label for="repair_status">Status</label>
                                <select name="repair_status" class="form-control" id="repair_status">
                                    <option value="" selected>All</option>
                                    <option value="1">Receive Parts</option>
                                    <option value="2">Repair Out</option>
                                    <option value="3">Repair In</option>
                                    <option value="4">Issue to Party</option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="in_party_id">Party Name</label>
                                <select name="in_party_id" class="form-control" id="in_party_id">
                                    <option value="" selected>All</option>
                                    @foreach (App\Models\PartsInventory::with('in_party')->select('in_party_id')->distinct()->get() as $item)
                                        <option value="{{ $item->in_party->id }}">{{ $item->in_party->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label for="repair_out_party_id">Repair Party Name</label>
                                <select name="repair_out_party_id" class="form-control" id="repair_out_party_id">
                                    <option value="" selected>All</option>
                                    @foreach (App\Models\PartsInventory::with('repair_out_party')->select('repair_out_party_id')->whereNotNull('repair_out_party_id')->distinct()->get() as $item)
                                        <option value="{{ $item->repair_out_party->id }}">{{ $item->repair_out_party->name }}</option>
                                    @endforeach
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
                        <table class="table text-nowrap table-bordered" id="parts-table">
                            <thead class="table-secondary bg-gray">
                                <tr>
                                    <th>No.</th>
                                    <th>Date</th>
                                    <th>Repair Out Date</th>
                                    <th>Repair In Date</th>
                                    <th>Issue Date</th>
                                    <th>Engineer Name</th>
                                    <th>Party Name</th>
                                    <th>M/C No</th>
                                    <th>Repair Out Party</th>
                                    <th>Status</th>
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

@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#repair_status').select2();
        $('#in_party_id').select2();
        $('#repair_out_party_id').select2();
        var table = $('#parts-table').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: '{{ route('parts_inventory.index') }}',
                type: 'GET',
                data: function(d) {
                    d.date_range = $('#date_range').val();
                    d.repair_status = $('select[name="repair_status"]').val();
                    d.in_party_id = $('select[name="in_party_id"]').val();
                    d.repair_out_party_id = $('select[name="repair_out_party_id"]').val();
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
                    data: 'repair_out_date',
                    name: 'repair_out_date'
                },
                {
                    data: 'repair_in_date',
                    name: 'repair_in_date'
                },
                {
                    data: 'issue_date',
                    name: 'issue_date'
                },
                {
                    data: 'engineer_name',
                    name: 'engineer_name'
                },
                {
                    data: 'in_party_name',
                    name: 'in_party_name',
                },
                {
                    data: 'mc_no',
                    name: 'mc_no'
                },
                {
                    data: 'repair_out_party',
                    name: 'repair_out_party'
                },
                {
                    data: 'status',
                    name: 'status',
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

        window.deleteParth = function(partsInventory) {
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
                        url: "{{ route('parts_inventory.destroy', ':parts_inventory') }}".replace(
                            ':parts_inventory', partsInventory),
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
    });
</script>
@endSection
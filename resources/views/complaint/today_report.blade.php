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
                            <a href="{{ route('export-ap-today', ['main_machine_type' => $main_machine_type]) }}" class="btn btn-primary"><i
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
                                    <label for="date_from">Date</label>
                                    <input type="text" class="form-control" placeholder="yyyy-mm-dd" name="date_from" id="date_from">
                                </div>
                                <div class="col col-md-2">
                                    <label for="attn">Attendance</label>
                                    <select name="attn" class="form-control" id="attn">
                                        <option value="" selected>All</option>
                                        <option value="P">P</option>
                                        <option value="A">A</option>
                                        <option value="L">L</option>
                                        <option value="H">H</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="role_id">Roles</label>
                                    <select class="form-control" id="role_id" name="role_id">
                                        <option value="" selected>All</option>
                                        @foreach (Spatie\Permission\Models\Role::all() as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
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
                            <table class="table text-nowrap table-bordered" id="complaint-table">
                                <thead class="table-secondary bg-gray">
                                    <tr>
                                        <th>No.</th>
                                        <th>Engineer Name</th>
                                        <th>Designation</th>
                                        <th>A/P</th>
                                        <th>In Time</th>
                                        <th>Out Time</th>
                                        <th>Pending</th>
                                        <th>In Progress</th>
                                        <th>Today Done</th>
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
    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script> --}}
    {{-- <script src="http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script> --}}

    {{-- {{ $dataTable->scripts(attributes: ['type' => 'module']) }} --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#role_id').select2();
            var table = $('#complaint-table').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: "{{ route('todayReport', ['main_machine_type' => $main_machine_type]) }}",
                    type: 'GET',
                    data: function(d) {
                        d.date_from = $('#date_from').val();
                        d.attn = $('#attn').val();
                        d.role_id = $('#role_id').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'No'
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'designation',
                        name: 'designation',
                    },
                    {
                        data: 'attendance_status',
                        name: 'attendance_status',
                    },
                    {
                        data: 'in_time',
                        name: 'in_time',
                    },
                    {
                        data: 'out_time',
                        name: 'out_time',
                    },
                    {
                        data: 'pendingComplaintsCount',
                        name: 'pendingComplaintsCount',
                    },
                    {
                        data: 'inProgressComplaintsCount',
                        name: 'inProgressComplaintsCount',
                    },
                    {
                        data: 'closedComplaintsCount',
                        name: 'closedComplaintsCount',
                    },
                ],
            });

            $('#search-button').click(function() {
                table.draw();
            })

            $('#toggleFormBtn').click(function() {
                $('#search-form').toggle(); // This will show or hide the form based on its current state
            });

            $('#date_from').datepicker({
                dateFormat: "yy-mm-dd",  // Change format to d-m-Y (day-month-year)
            });
        });
    </script>
@endSection

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
                        <div class="card-title">{{ $title }}:</div>
                        <div class="row card-title col-xl-8">
                            <div class="col-xxl-3">Total: {{ $totalstaff }}</div>
                            <div class="col-xxl-3">Pending Approval: <span style="color: red; font-size: 15px;"> {{ $pendingforapproval }} </span></div>
                            <div class="col-xxl-3">Present: <span style="color: green; font-size: 15px;"> {{ $present }} </span></div>
                            <div class="col-xxl-3">Absent: <span style="color: red; font-size: 15px;"> {{ $absent }} </span></div>
                            <div class="col-xxl-3">Leave: <span style="color: red; font-size: 15px;"> {{ $leave }} </span></div>
                        </div>
                        <div class="justify-content-end">
                            <!-- <a href="#" class="btn btn-primary"><i class="fa fa-download" aria-hidden="true"></i></a> -->
                            <button class="btn btn-primary" id="toggleFormBtn"><i class="fa fa-filter" aria-hidden="true"></i></button>
                            <a href="{{ route('work-update-list') }}" class="btn btn-md btn-primary "> Sales Work Update </a>
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
                                    <label for="is_active">Department</label>
                                    <select class="form-control" id="department" name="department">
                                        <option value="">Choose a Option</option>
                                        @foreach (App\Models\Department::all() as $item)
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
                                        <th>In Selfie</th>
                                        <th>Engineer Name</th>
                                        <th>Department</th>
                                        <th>Shift</th>
                                        <th>Date</th>
                                        <th>A/P</th>
                                        <th>In Time</th>
                                        <th>In Address</th>
                                        <th>Out Time</th>
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
            $('#department').select2();
            var table = $('#complaint-table').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                pageLength: 100,
                ajax: {
                    url: "{{ route('attendap-today-report') }}",
                    type: 'GET',
                    data: function(d) {
                        d.date_from = $('#date_from').val();
                        d.attn = $('#attn').val();
                        d.role_id = $('#role_id').val();
                        d.department = $('#department').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'No'
                    },
                    {
                        data: 'in_selfie',
                        name: 'in_selfie',
                        render: function(data, type, row) {
                                // Change text color based on status
                                if (row.in_selfie != null && row.in_selfie !== '') {
                                 let baseUrl = "{{ url('user-monthly-attendence-list') }}";   
                         return '<a href="' + baseUrl + '/'+row.id+'" data-bs-toggle="tooltip" title="View Profile" data-bs-placement="top" data-bs-original-title="View Profile"><img src="https://pickwell.addonwebtech.com/atdselfie/' 
                                   + row.in_selfie + '" height="60" width="60" style="border-radius:50%; object-fit:cover;"></a>';
                            }

                         return '';
                        }
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data, type, row) {
                            // Change text color based on status
                                
                               // let baseUrl = "{{ url('adminusers/adminusersprofile') }}";
                                let baseUrl = "{{ url('user-monthly-attendence-list') }}";
                                return '<a href="' + baseUrl + '/'+row.id+'" data-bs-toggle="tooltip" title="View Profile" data-bs-placement="top" data-bs-original-title="View Profile">' + data + '</a>';
                                //return '<a href="' + baseUrl + '/' + row.id + '" data-bs-toggle="tooltip" title="View Profile" data-bs-placement="top" data-bs-original-title="View Profile">' + data + '</a>';
                            //return data;
                        }
                    },
                    {
                        data: 'department.name',
                        name: 'department.name',
                    },
                    {
                        data: 'shift',
                        name: 'shift.shift_start',
                        render: function (data, type, row) {

                            if (!data) {
                                return '';
                            }

                            return data.title + ' (' + data.shift_start + ')';
                        }
                    },
                    {
                        data: 'in_date',
                        name: 'in_date',
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
                        data: 'in_address',
                        name: 'in_address',
                    },
                    {
                        data: 'out_time',
                        name: 'out_time',
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

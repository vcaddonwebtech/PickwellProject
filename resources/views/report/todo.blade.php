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
                            <a href="{{ route('export-todo') }}" class="btn btn-primary"><i
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
                                    <label for="date_range">Assign/Reminder Date Range</label>
                                    <input type="text" class="form-control" placeholder="Select date range" id="date_range" name="date_range">
                                </div>

                                <div class="col">
                                    <label for="user">User</label>
                                    <select name="user_id" class="form-control" id="user_id">
                                        <option value="" selected>All</option>
                                        @foreach (\App\Models\Todo::with('todoUser')->get()->pluck('todoUser')->unique('id')->sortBy('name') as $user)
                                            @if ($user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col">
                                    <label for="assign_user_id">Assign User</label>
                                    <select name="assign_user_id" class="form-control" id="assign_user_id">
                                        <option value="" selected>All</option>
                                        @foreach (\App\Models\TodoAssignUser::with('assignUserDetail')
                                            ->get()
                                            ->filter(function ($user) {
                                                return $user->assignUserDetail !== null;
                                            })
                                            ->sortBy('assignUserDetail.name')->unique('assign_user_id') as $user)
                                            <option value="{{ $user->assignUserDetail->id }}">
                                                {{ $user->assignUserDetail->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            
                                <div class="col">
                                    <label for="priority_id">Priority</label>
                                    <select name="priority_id" class="form-control" id="priority_id">
                                        <option value="" selected>All</option>
                                        @foreach (\App\Models\Priority::where('is_priority', 1)->get() as $user)
                                            @if ($user)
                                                <option value="{{ $user->id }}">{{ $user->priority }}</option>
                                            @endif
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
                            <table class="table text-nowrap table-bordered" id="todo-report-table">
                                <thead class="table-secondary bg-gray">
                                    <tr>
                                        <th>No.</th>
                                        <th>Reminder Date</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>User</th>
                                        <th>Assign Users</th>
                                        <th>Assign Date & Time</th>
                                        <th>Status</th>
                                        <th>Comment</th>
                                        <th>Priority</th>
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
            $('#priority_id').select2();
            $('#user_id').select2();
            $('#assign_user_id').select2();
            var table = $('#todo-report-table').DataTable({
                processing: true,
                serverSide: true,
                search: {
                    smart: false
                },
                searchinput: false,
                ajax: {
                    url: '{{ route('report.todo-report') }}',
                    type: 'GET',
                    data: function(d) {
                        d.priority_id = $('#priority_id').val();
                        d.user_id = $('#user_id').val();
                        d.assign_user_id = $('#assign_user_id').val();
                        d.date_range = $('#date_range').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'No'
                    },
                    {
                        data: 'reminder_date',
                        name: 'reminder_date'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'description',
                        name: 'description'
                    },
                    {
                        data: 'user_name',
                        name: 'user_name'
                    },
                    {
                        data: 'assign_users',
                        name: 'assign_users'
                    },
                    {
                        data: 'assign_date_time',
                        name: 'assign_date_time'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'comment',
                        name: 'comment'
                    },
                    {
                        data: 'priority',
                        name: 'priority'
                    },
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

            $('#toggleFormBtn').click(function() {
                $('#search-form').toggle(); 
            });
        });
    </script>
@endsection

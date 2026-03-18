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
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('machinesalesdata', ['main_machine_type' => $main_machine_type]) }}">{{ $main_machine_name }}</a></li>
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
                            <a href="{{ route('generate-salesalaries', ['main_machine_type' => $main_machine_type]) }}" class="btn btn-primary"><i class="fa fa-file" aria-hidden="true"></i> Generate Salaries</a>
                            <button class="btn btn-primary" id="toggleFormBtn"><i class="fa fa-filter" aria-hidden="true"></i></button>
                        </div>

                    </div>
                    <div class="header-element px-4">
                        <form id="search-form" style="display: none;" >
                            <!-- Add your custom search fields here -->
                            <div class="row mb-5 mt-3">
                                <div class="col col-md-2">
                                    <label for="month">Months</label>
                                    <select name="month" class="form-control" id="month">
                                        <option value="" selected>All</option>
                                        @foreach (App\Models\Months::get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col">
                                    <div class="col">
                                        <button type="button" id="search-button" class="btn btn-primary mt-3">Filter</button>
                                    </div>
                                </div>
                                <!-- Add more fields as needed -->
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-bordered" id="sal-details">
                                <thead class="table-secondary bg-gray">
                                    <tr>
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Present Days</th>
                                        <th>W Days</th>
                                        <th>PD Salary</th>
                                        <th>Total Salary</th>
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('#month').select2();
            $('#role_id').select2();
            $('#engineer_id').select2();
            var table = $('#sal-details').DataTable({
                processing: true,
                serverSide: true,
                search: {
                    smart: false
                },
                searchinput: false,
                ajax: {
                    url: "{{ route('salesalaries', ['main_machine_type' => $main_machine_type]) }}",
                    type: 'GET',
                    data: function(d) {
                        d.month = $('#month').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'No'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'role',
                        name: 'role'
                    },
                    {
                        data: 'total_p',
                        name: 'total_p'
                    },
                    {
                        data: 'w_days',
                        name: 'w_days'
                    },
                    {
                        data: 'pd_sal',
                        name: 'pd_sal'
                    },
                    {
                        data: 't_sal',
                        name: 't_sal'
                    },
                    { 
                        data: 'action', 
                        name: 'action', 
                        orderable: false, 
                        searchable: false 
                    }
                ]
            });
            $('#toggleFormBtn').click(function() {
                $('#search-form').toggle(); // This will show or hide the form based on its current state
            });
            $('#search-button').click(function() {
                table.draw();
            });
        });
    </script>
@endSection

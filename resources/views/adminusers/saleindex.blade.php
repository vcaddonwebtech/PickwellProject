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
                            <button class="btn btn-primary" id="toggleFormBtn"><i class="fa fa-filter"
                                    aria-hidden="true"></i></button>
                            <a href="{{ route('salesusercreate', ['main_machine_type' => $main_machine_type]) }}" class="btn btn-md btn-primary "> Create </a>
                        </div>
                    </div>
                    <div class="header-element px-4">
                        <form id="search-form" style="display: none;">
                            <!-- Add your custom search fields here -->
                            <div class="row mb-5 mt-3">
                                <div class="col">
                                    <label for="is_active">Status</label>
                                    <select name="is_active" class="form-control" id="is_active">
                                        <option value="" selected>All</option>
                                        <option value="1">Active</option>
                                        <option value="0">Not Active</option>
                                    </select>
                                </div>

                                <div class="col">
                                    <label for="role">Role</label>
                                    <select class="form-control" id="role" name="role">
                                        <option value="" selected>All</option>
                                        @foreach (Spatie\Permission\Models\Role::all() as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col">
                                    <button type="button" id="search-button" class="btn btn-primary mt-3">Filter</button>
                                    <a href="javascript:void(0);" id="download-excel" class="btn btn-primary mt-3">Excel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table text-nowrap table-bordered" id="user-table">
                                <thead class="table-secondary bg-gray">
                                    <tr>
                                        <th>No.</th>
                                        <th>Name</th>
                                        <th>Mobile</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>In Time</th>
                                        <th>Out Time</th>
                                        <th>Is Active</th>
                                        <th>Is Leader</th>
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
    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script> --}}
    {{-- <script src="http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script> --}}

    {{-- {{ $dataTable->scripts(attributes: ['type' => 'module']) }} --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#is_active').select2();
            $('#role').select2();
            window.deleteUser = function(user) {
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
                            url: "{{ route('users.destroy', ':user') }}".replace(':user',
                                user),
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
                                console.log('Error:', data);
                                Swal.fire({
                                    title: "Not Deleted!",
                                    text: data.responseJSON.message,
                                    icon: "error"
                                });
                            }
                        });

                    }
                });
            }


            var table = $('#user-table').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                // dom: 'Bfrtip',
                // buttons: [
                //     'copy', 'csv', 'excel', 'pdf', 'print'
                // ],
                ajax: {
                    url: "{{ route('salesusers', ['main_machine_type' => $main_machine_type]) }}",
                    type: 'GET',
                    data: function(d) {
                        d.is_active = $('select[name="is_active"]').val();
                        d.role = $('select[name="role"]').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'No'
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function(data, type, row) {
                            // Change text color based on status
                                //return '<a href="https://saistar.addonwebtech.com/users/usersprofile/{{$main_machine_type}}/'+row.id+'">' + data + '</a>';
                                let baseUrl = "{{ url('saleusersprofile/' . $main_machine_type) }}";
                                return '<a href="' + baseUrl + '/' + row.id + '" data-bs-toggle="tooltip" title="View Profile" data-bs-placement="top" data-bs-original-title="View Profile">' + data + '</a>';
                            //return data;
                        }
                    },
                    {
                        data: 'phone_no',
                        name: 'phone_no'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'roles',
                        name: 'roles'
                    },
                    {
                        data: 'inTime',
                        name: 'inTime'
                    },
                    {
                        data: 'outTime',
                        name: 'outTime'
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
                    },
                    {
                        data: 'leader',
                        name: 'leader',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#search-button').click(function() {
                table.draw();
            })

            $('#toggleFormBtn').click(function() {
                $('#search-form').toggle(); // This will show or hide the form based on its current state
            });
        });
    </script>
@endSection

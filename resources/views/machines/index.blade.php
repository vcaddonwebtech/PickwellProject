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
                        <a href="{{ route('machines.create') }}" class="btn btn-md btn-primary "> Create </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-nowrap table-bordered" id="machine-table">
                            <thead class="table-secondary bg-gray">
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Specifications</th>
                                    <th>RPM</th>
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
        var table = $('#machine-table').DataTable({
            processing: true,
            serverSide: true,
            searching: true,
            ajax: {
                url: '{{ route('machines.index') }}',
                type: 'GET',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'No'
                },
                {
                    data: 'machine_name',
                    name: 'machine_name'
                },
                {
                    data: 'machine_specification',
                    name: 'machine_specification'
                },
                {
                    data: 'machine_rpm',
                    name: 'machine_rpm'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
        });

        window.deleteParth = function(machine) {
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
                        url: "{{ route('machines.destroy', ':machine') }}".replace(
                            ':machine', machine),
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
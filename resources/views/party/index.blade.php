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
    <!-- Page Header Close -->


    <!-- Start:: row-4 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">{{ $title }}</div>
                    <div class="justify-content-end">
                        <a href="{{ route('export-party', ['main_machine_type' => $main_machine_type]) }}" class="btn btn-primary"><i
                            class="fa fa-download" aria-hidden="true"></i></a>
                        <a href="{{ route('parties.create', ['main_machine_type' => $main_machine_type]) }}" class="btn btn-md btn-primary "> Create </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-nowrap table-bordered" id="party-table">
                            <thead class="table-secondary bg-gray">
                                <tr>
                                    <th width="5%">No</th>
                                    <th> ID</th>
                                    <th width="15%">Name</th>
                                    <th width="5%">Address</th>
                                    <th>Mobile No.</th>
                                    <th width="10%">Contact Person </th>
                                    <th> Owner</th>
                                    <th width="10%">Action</th>
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

{{-- <script src="{{asset('js\datatables.js')}}"></script> --}}
{{-- <script src="http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script> --}}
<script type="text/javascript">
    $(document).ready(function() {
        var table = $('#party-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('parties.index', ['main_machine_type' => $main_machine_type]) }}",
                type: 'GET',
            },
            columns: [
                { data: 'DT_RowIndex', name: 'No' },
                { data: 'id', name: 'id'},
                { data: 'name', name: 'name' },
                { data: 'address', name: 'address' }, 
                { data: 'phone_no', name: 'phone_no' },
                { data: 'contact_person', name: 'contact_person' },
                { data: 'owner_name', name: 'owner_name' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
        });

            window.deleteParty = function(party) {
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
                            url: "{{ route('parties.destroy', ':party') }}".replace(':party',
                                party),
                            data: {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function(data) {
                                if(data.success == 0) {
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
@endsection
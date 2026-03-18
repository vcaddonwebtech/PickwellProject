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
                    <div class="header-element">
                    </div>
                    <div class="prism-toggle d-flex gap-3">
                        <div class="header-element profile-1">
                            <a href="{{ route('item-parts.create') }}" class="btn btn-md btn-primary "> Create </a>
                        </div>

                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-nowrap table-bordered" id="item-part-table">
                            <thead class="table-secondary bg-gray">
                                <tr>
                                    <th width="5%">No.</th>
                                    <th width="15%">Name</th>
                                    <th width="15%">Product Group Name </th>
                                    <th width="10%">HSN Code</th>
                                    <th width="10%">GST%</th>
                                    <th width="10%">Rate</th>
                                    <th width="10%">Action</th>
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

            window.deleteItemPart = function(item_part) {
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
                            url: "{{ route('item-parts.destroy', ':item_part') }}".replace(':item_part',
                            item_part),
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


            var table = $('#item-part-table').DataTable({
               processing: true,
               serverSide: true,
               searching: true,
               // dom: 'Bfrtip',
               // buttons: [
               //     'copy', 'csv', 'excel', 'pdf', 'print'
               // ],
               ajax: {
                   url: "{{ route('item-parts.index') }}",
                   type: 'GET',
               },
               columns: [
                        { data: 'DT_RowIndex', name: 'No' },
                        { data: 'name', name: 'name' },
                        { data: 'product_group', name: 'product_group' },
                        { data: 'hsn_code', name: 'hsn_code' },
                        { data: 'gst', name: 'gst' },
                        { data: 'rate', name: 'rate' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ]
            });
        });
</script>
@endSection
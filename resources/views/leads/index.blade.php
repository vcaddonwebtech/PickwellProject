@extends($layout)
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
                    <div class=" d-flex gap-3">
                        <div class="header-element profile-1">
                            <a href="{{ route('leads.create') }}" class="btn btn-md btn-primary "> Create </a>
                        </div>

                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-nowrap table-bordered" id="lead-table">
                            <thead class="table-secondary bg-gray">
                                <tr>
                                    <th width="5%">No.</th>
                                    <th width="15%">Party Name</th>
                                    <th width="10%">Date Of Lead</th>
                                    <th width="10%">Product</th>
                                    <th width="10%"> Status</th>
                                    <th width="10%">type Of Lead</th>
                                    <th width="10%">Schedule meeting</th>
                                    <th width="10%"> Date of Meeting</th>
                                    <th width="10%"> Source</th>
                                    <th width="10%"> Action</th>
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


            window.deleteLead = function(contact_person) {
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
                            url: "{{ route('leads.destroy', ':contact_person') }}".replace(':contact_person',
                                contact_person),
                            data: {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function(data) {
                                console.log(data);
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "lead deleted successfully.",
                                    icon: "success"
                                });
                                if (data.success) {
                                    window.location.reload();
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


            var table = $('#lead-table').DataTable({
               processing: true,
               serverSide: true,
               searching: true,
               // dom: 'Bfrtip',
               // buttons: [
               //     'copy', 'csv', 'excel', 'pdf', 'print'
               // ],
               ajax: {
                   url: '{{ route('leads.index') }}',
                   type: 'GET',
               },
               columns: [
                        { data: 'DT_RowIndex', name: 'No' },
                        { data: 'party_name', name: 'party_name' },
                        { data: 'date_of_lead', name: 'date_of_lead' },
                        { data: 'product', name: 'product' },
                        { data: 'status', name: 'status' },
                        { data: 'type_of_lead', name: 'type_of_lead' },
                        { data: 'schedule_meeting', name: 'schedule_meeting' },
                        { data: 'date_of_meeting', name: 'date_of_meeting' },
                        { data: 'source', name: 'source' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ]
            });
        });
</script>
@endSection
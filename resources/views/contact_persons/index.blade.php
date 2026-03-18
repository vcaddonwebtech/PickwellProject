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
                    <div class=" d-flex gap-3">
                        <div class="header-element profile-1">
                            <a href="{{ route('contact_persons.create') }}" class="btn btn-md btn-primary "> Create </a>
                        </div>

                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-nowrap table-bordered" id="contact-person-table">
                            <thead class="table-secondary bg-gray">
                                <tr>
                                    <th width="5%">No.</th>
                                    <th width="15%">Name</th>
                                    <th width="10%">Mobile no</th>
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
<div class="modal fade" id="createContactPerson" aria-hidden="true" aria-labelledby="createContactPersonTitle"
    tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createContactPersonTitle">Create Contact Person</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('contact_persons.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label for="mobile_no" class="form-label">Mobile no</label>
                        <input type="text" class="form-control" id="phone_no" name="phone_no" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editContactPerson" aria-hidden="true" aria-labelledby="editContactPersonTitle"
    tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editContactPersonTitle">Edit Contact Person</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('contact_persons.update', ':contact_persons') }}" method="POST"
                    id="contact-persons-form">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="mobile_no" class="form-label">Mobile no</label>
                        <input type="text" class="form-control" id="phone_no" name="phone_no" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
            window.createArea = function() {
                $('#createContactPerson').modal('show');
            }

            window.edit = function(contact_person) {
                console.log(contact_person);
                $.ajax({
                    type: "GET",
                    url: "{{ route('contact_persons.edit', ':contact_person') }}".replace(':contact_person',
                        contact_person),
                    success: function(data) {
                        console.log(data);
                        $('#editContactPerson').modal('show');
                        $('#editContactPerson').find('#name').val(data.name);
                        $('#editContactPerson').find('#phone_no').val(data.phone_no);
                        $('#contact-persons-form').attr('action', "{{ route('contact_persons.update', ':contact_person') }}".replace(':contact_person', data.id));
                    }
                })
            }

            window.deleteUser = function(contact_person) {
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
                            url: "{{ route('contact_persons.destroy', ':contact_person') }}".replace(':contact_person',
                                contact_person),
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
                                    title: "Can't Deleted!",
                                    text: data.responseJSON.error,
                                    icon: "error"
                                });
                            }
                        });

                    }
                });
            }


            var table = $('#contact-person-table').DataTable({
               processing: true,
               serverSide: true,
               searching: true,
               // dom: 'Bfrtip',
               // buttons: [
               //     'copy', 'csv', 'excel', 'pdf', 'print'
               // ],
               ajax: {
                   url: '{{ route('contact_persons.index') }}',
                   type: 'GET',
               },
               columns: [
                        { data: 'DT_RowIndex', name: 'No' },
                        { data: 'name', name: 'name' },
                        { data: 'phone_no', name: 'phone_no' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ]
            });
        });
</script>
@endSection
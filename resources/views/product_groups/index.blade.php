@extends('layouts.app')
@section('title', $title)
@section('content')
<div class="container-fluid">
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
                            <a href="{{ route('product-groups.create') }}" class="btn btn-md btn-primary "> Create </a>
                        </div>

                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-nowrap table-bordered" id="product-group-table">
                            <thead class="table-secondary bg-gray">
                                <tr>
                                    <th width="5%">No.</th>
                                    <th width="15%">Name</th>
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
<div class="modal fade" id="createProductGroup" aria-hidden="true" aria-labelledby="createProductGroupTitle"
    tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProductGroupTitle">Create Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
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
<div class="modal fade" id="editProductGroup" aria-hidden="true" aria-labelledby="editProductGroupTitle" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductGroupTitle">Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('products.update', ':product') }}" method="POST" id="product-group-form">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
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
			$('#createProductGroup').modal('show');
		}

		window.editProductGroup = function(product_group) {
            $.ajax({
                type: "GET",
                url: "{{ route('product-groups.edit', ':product_group') }}".replace(':product_group',
                    product_group),
                success: function(data) {
                    console.log(data);
                    $('#editProductGroup').modal('show');
                    $('#editProductGroup').find('#name').val(data.productGroup.name);
                    $('#product-group-form').attr('action', "{{ route('product-groups.update', ':product_group') }}".replace(':product_group', data.productGroup.id));
                }
            })
		}

		window.deleteProductGroup = function(product_group) {
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
					url: "{{ route('product-groups.destroy', ':product_group') }}".replace(':product_group',
					product_group),
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


		var table = $('#product-group-table').DataTable({
               processing: true,
               serverSide: true,
               searching: true,
               ajax: {
                   url: "{{ route('product-groups.index') }}",
                   type: 'GET',
               },
               columns: [
                        { data: 'DT_RowIndex', name: 'No' },
                        { data: 'name', name: 'name' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ]
            });
  });
</script>
@endSection
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
	<div class="row">
		<div class="col-xl-12">
			<div class="card custom-card">
				<div class="card-header justify-content-between">
					<div class="card-title">{{ $title }}</div>
					<div class="header-element">
					</div>
					<div class=" d-flex gap-3">
						<div class="header-element profile-1">
							<a href="{{ route('products.create', ['main_machine_type' => $main_machine_type]) }}" class="btn btn-md btn-primary "> Create </a>
						</div>

					</div>

				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table text-nowrap table-bordered" id="product-table">
							<thead class="table-secondary bg-gray">
								<tr>
									<th width="5%">No.</th>
									<th width="15%">Name</th>
									<th width="10%">Group Name</th>
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
<div class="modal fade" id="createProduct" aria-hidden="true" aria-labelledby="createProductTitle" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="createProductTitle">Create Product</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="{{ route('products.store') }}" method="POST">
					@csrf
					<div class="mb-3">
						<label for="name" class="form-label">Name</label>
						<input type="text" class="form-control" id="name" name="name" required>
					</div>

					<div class="mb-3">
						<label for="product_group_id" class="form-label">Product Group Name</label>
						<button type="button" class="btn btn-link product-group float-end" onclick="productGroup()"
							id="createProductGroup">add new</button>

						<select class="form-select" id="product_group_id" name="product_group_id" required>
							<option value="">Choose a Product Group</option>
							@foreach (App\Models\ProductGroup::all() as $item)
							<option value="{{ $item->id }}">{{ $item->name }}</option>
							@endforeach
						</select>
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


<div class="modal fade" id="editProduct" aria-hidden="true" aria-labelledby="editProductTitle" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editProductTitle">Edit Product</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="{{ route('products.update', ':product') }}" method="POST" id="product-form">
					@csrf
					<div class="mb-3">
						<label for="name" class="form-label">Name</label>
						<input type="text" class="form-control" id="name" name="name" required>
					</div>
					<div class="mb-3">
						<label for="product_group_id" class="form-label">Product Group Name</label>
						<button type="button" class="btn btn-link product-group float-end" onclick="productGroup()"
							id="createProductGroup">add new</button>
						<select class="form-select" id="product_group_id" name="product_group_id" required>
							<option value="">Choose a Product Group</option>
							@foreach (App\Models\ProductGroup::all() as $item)
							<option value="{{ $item->id }}">{{ $item->name }}</option>
							@endforeach
							{{-- <input type="text" class="form-control" id="product_group_id" name="product_group_id"
								required> --}}
						</select>
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

<div class="modal fade" id="addProductItem" aria-hidden="true" aria-labelledby="addProductItemTitle" tabindex="-1">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addProductItemTitle">Edit Product</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form action="{{ route('products.update', ':product') }}" method="POST" id="product-form">
					@csrf
					<div class="mb-3">
						<label for="name" class="form-label">Name</label>
						<input type="text" class="form-control" id="name" name="name" required>
					</div>
					<div class="mb-3">
						<label for="product_group_id" class="form-label">Product Group Name</label>
						<button type="button" class="btn btn-link product-group float-end" onclick="productGroup()"
							id="createProductGroup">add new</button>
						<select class="form-select" id="product_group_id" name="product_group_id" required>
							<option value="">Choose a Product Group</option>
							@foreach (App\Models\ProductGroup::all() as $item)
							<option value="{{ $item->id }}">{{ $item->name }}</option>
							@endforeach
							{{-- <input type="text" class="form-control" id="product_group_id" name="product_group_id"
								required> --}}
						</select>
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
		$('#product_group_id').select2();
		window.productGroup = function() {
			const newDiv = document.createElement('div');
			newDiv.className = 'mb-3';
			newDiv.id = 'newProductGroupDiv';

			// Create the input field
			const input = document.createElement('input');
			input.type = 'text';
			input.className = 'form-control';
			input.id = 'newProductGroup';
			input.placeholder = 'Enter new product group name';
			input.name = 'name';
			input.setAttribute('required', true);
			// Create the add button
			const addButton = document.createElement('button');
			addButton.type = 'button';
			addButton.className = 'btn btn-primary mt-2';
			addButton.textContent = 'Add';
			addButton.onclick = function() {
				// Disable the input field
				input.disabled = true;
				addButton.disabled = true;
				$.ajax({
					type: "POST",
					url: "{{ route('products.addProductGroup') }}",
					data: {
						'_token': '{{ csrf_token() }}',
						name: input.value
					},
					success: function(response) {
						console.log(response);
						// Get the new product group name
						const newProductName = response.name;
		
						// Add the new product group to the select element
						const select = document.getElementById('product_group_id');
						const option = document.createElement('option');
						option.value = response.id; // Assuming the name is the value
						option.textContent = newProductName;
						select.appendChild(option);
		
						// Optionally, select the new product group
						select.value = newProductName;
					}
				})
			};

			// Append the input field and add button to the new div
			newDiv.appendChild(input);
			newDiv.appendChild(addButton);

			// Insert the new div after the label
			const label = document.querySelector('label[for="product_group_id"]');
			label.parentNode.insertBefore(newDiv, label.nextSibling);
		}

		window.createArea = function() {
			$('#createProduct').modal('show');
		}

		window.edit = function(product) {
			//console.log(product);
			$.ajax({
				type: "GET",
				url: "",
				success: function(data) {
				console.log(data);
				$('#editProduct').modal('show');
				$('#editProduct').find('#name').val(data.product.name);
				$('#editProduct').find('#product_group_id').find('option[value="' + data.product.product_group_id + '"]').attr('selected', 'selected');
				$('#product-form').attr('action', "{{ route('products.update', ':product') }}".replace(':product', data.product.id));
				}
			})
		}

		window.deleteProduct = function(product) {
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
					url: "{{ route('products.destroy', ':product') }}".replace(':product',
					product),
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

		

		// window.productGroup = function(product) {
		// 	var 
		// }

		var table = $('#product-table').DataTable({
               processing: true,
               serverSide: true,
               searching: true,
               // dom: 'Bfrtip',
               // buttons: [
               //     'copy', 'csv', 'excel', 'pdf', 'print'
               // ],
               ajax: {
                   url: "{{ route('products.index', ['main_machine_type' => $main_machine_type]) }}",
                   type: 'GET',
               },
               columns: [
                        { data: 'DT_RowIndex', name: 'No' },
                        { data: 'name', name: 'name' },
                        { data: 'product_group', name: 'product_group' },
                        { data: 'action', name: 'action', orderable: false, searchable: false }
                    ]
            });
  });
</script>
@endSection
@extends('layouts.app')
@section('title', $title)
@section('content')
    {{-- <div class="p-3 header-secondary row client-name-wpr  bg-primary-transparent">
    <div class="col">
        <div class="d-flex">
            <!-- <h6>Petey Cruiser</h6> -->
            <h6 class="mb-0"> <strong class="mb-0 px-2"> Petey Cruiser </strong> 29/06/24 </h6>
            <!-- <a class=" d-flex client-name" href="javascript:void(0);"> <span class=""></span>  </a> -->
        </div>
    </div>
</div> --}}

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
                        <div class="card-title">
                            {{ $title }}
                        </div>
                    </div>
                    <div class="card-body gy-4">
                        <div class="row">
                            <div class="col-xl-6 col-md-6 col-sm-12">
                                @if ($errors->any())
                                    <div class="alert alert-danger mt-3">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li class="text-danger">{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form action="{{ route('MachineSales.update', ['machinesale' => $machine->id]) }}" method="POST" id="machine-sales-form" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" class="form-control" placeholder="Firm Id" value="{{ $machine->firm_id ?? App\Models\Firm::first()->id }}" name="firm_id">
                                    <input type="hidden" class="form-control" placeholder="year_id" value="{{ $machine->year_id ?? App\Models\Year::first()->id }}" name="year_id">
                                    <input type="hidden" name="main_machine_type" value="{{ $main_machine_type }}">
                                    <div class="row mb-1">
                                        <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <label for="inputParty" class="col-form-label">Party Name</label> <i
                                                class="text-danger">*</i>
                                            <select class="form-control" name="party_id" id="party_id">
                                                <option value="">Choose a Party Name</option>
                                                @foreach ($parties as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if ($machine->party_id == $item->id || old('party_id') == $item->id) selected @endif
                                                        data-value={{ json_encode($item) }}>{{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <label for="inputMFCN" class="col-form-label">Firm</label> 
                                            <select class="form-control" name="firm_id" id="firm_id">
                                                <option value="">Choose a Firm</option>
                                                @foreach ($partyfirms as $item)
                                                    <option value="{{ $item->id }}"
                                                    @if ($machine->firm_id == $item->id) selected @endif>
                                                    {{ $item->firm_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                         </div>
                                        <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <label for="inputParty" class="col-form-label">Product Name</label> 
                                            <select class="form-control" name="product_id" id="product_id">
                                                <option value="">Choose a Product</option>
                                                @foreach ($products as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if ($machine->product_id == $item->id || old('product_id') == $item->id) selected @endif>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="row mb-1">
                                        <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <label for="inputOrder" class="col-form-label">Contract No.</label> 
                                            <input type="text" id="inputOrder" class="form-control" name="contenor_no" value="{{ $machine->contenor_no ?? old('contenor_no') }}" />
                                        </div>
                                        <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <label for="inputProdsr" class="col-form-label">Serial No.</label> 
                                            <input type="text" id="inputProdsr" class="form-control" name="serial_no" value="{{ $machine->serial_no ?? old('serial_no') }}" />
                                        </div>
                                        <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <label for="inputOrder" class="col-form-label">M/c Factory No.</label> 
                                            <input type="text" id="inputOrder" class="form-control" name="order_no" value="{{ $machine->order_no ?? old('order_no') }}" />
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <label for="inputOrder" class="col-form-label">Width</label> 
                                            <input type="text" id="inputOrder" class="form-control" name="width" value="{{ $machine->width ?? old('width') }}" />
                                        </div>
                                        <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <label for="inputProdsr" class="col-form-label">Color</label> 
                                            <input type="text" id="inputProdsr" class="form-control" name="color" value="{{ $machine->color ?? old('color') }}" />
                                        </div>
                                        <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <label for="inputOrder" class="col-form-label">Shadding</label> 
                                            <input type="text" id="inputOrder" class="form-control" name="shadding" value="{{ $machine->shadding ?? old('shadding') }}" />
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                            <label for="inputOrder" class="col-form-label">Remark </label>
                                            <input type="text" id="inputOrder" class="form-control" name="remarks" value="{{ $machine->remarks ?? old('remarks') }}" />
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                            <label for="inputOrder" class="col-form-label">Status</label>
                                            <select class="form-control" name="status" id="status_id">
                                                <option value="">Choose a Product</option>
                                                @foreach (App\Models\Status::all() as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if ($machine->status == $item->id) selected @endif>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-2">
                                        <div class="row justify-content-end mt-3">
                                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-2 ">
                                                <button class="btn btn-outline-light w-100" type="reset"
                                                    onclick="location.reload()">Reset</button>
                                            </div>

                                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-2 ">
                                                <input type="submit" class="btn btn-primary w-100" value="Submit">
                                            </div>


                                        </div>
                                    </div>

                                </form>
                            </div>
                            <div class="col-xl-6 col-md-6 col-sm-12">
                                <div class="party_details">
                                    <h5>Party Details</h5>
                                    <p> Name : {{ !empty($machine->party) ? $machine->party->name : '' }}</p>
                                    <p> Address : {{ !empty($machine->party) ? $machine->party->address : '' }}</p>
                                    <p> Mobile No : {{ !empty($machine->party) ? $machine->party->phone_no : '' }}</p>
                                    <p> City : {{ !empty($machine->party->city) ? $machine->party->city->name : '' }}</p>
                                    <p> Area : {{ !empty($machine->party->area) ? $machine->party->area->name : '' }}</p>
                                    <p> Contact Person :
                                        {{ !empty($machine->party->contactPerson) ? $machine->party->contactPerson->name : '' }}
                                    </p>
                                    <p> Owner : {{ !empty($machine->party->owner) ? $machine->party->owner->name : '' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Modal for Large Image Preview -->
        <div id="imageModal" class="modal" tabindex="-1" role="dialog" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <img id="modalImage" src="" alt="Large Preview" style="width: 100%; height: auto;">
                    </div>
                </div>
            </div>
        </div>


    </div>

@endSection
@section('scripts')
    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script> --}}
    {{-- <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script> --}}
    {{-- {!! JsValidator::formRequest('App\Http\Requests\MachineSalesEntryRequest', '#machine-sales-form'); !!} --}}
    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script> --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#party_id').select2();

            $('#product_id').select2();

            $('#status_id').select2();

            $('#MachineSales-form').validate({
                rules: {
                    party_id: {
                        required: true
                    },
                    product_id: {
                        required: true
                    },
                    serial_no: {
                        required: true
                    }
                },
                messages: {
                    party_id: {
                        required: "Please select a party"
                    },
                    product_id: {
                        required: "Please select a product"
                    }
                },
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });

            $('#party_code').on('change', function() {
                var party_code = $(this).val();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "{{ route('parties.partyCode') }}",
                    data: {
                        'party_code': party_code
                    },
                    success: function(data) {
                        if (data.party) {
                            $('#party_id').val(data.party.id).trigger('change');
                            $('#party_code').val(data.party.code);
                            if (data.party.city == null) {
                                var city = '';
                            } else {
                                var city = data.party.city.name;
                            }
                            if (data.party.area == null) {
                                var area = '';
                            } else {
                                var area = data.party.area.name;
                            }
                            if (data.party.contact_person == null) {
                                var contact_person = '';
                            } else {
                                var contact_person = data.party.contact_person.name;
                            }
                            if (data.party.owner == null) {
                                var phone = '';
                                var name = '';
                            } else {
                                var phone = data.party.owner.phone_no;
                                var name = data.party.owner.name;
                            }
                            var html = '<h5>Party Details</h5>';
                            html += '<p> Name: ' + data.party.name + '</p>';
                            html += '<p> Mobile No: ' + data.party.phone_no + '</p>';
                            html += '<p> Email: ' + data.party.email + '</p>';
                            html += '<p> Address: ' + data.party.address + '</p>';
                            html += '<p> City: ' + city + '</p>';
                            html += '<p> Area: ' + area + '</p>';
                            html += '<p> Contact Person: ' + contact_person + ' (' + phone +
                                ')' + '</p>';
                            html += '<p> Owner: ' + name + ' (' + phone + ')' + '</p>';
                            $('.party_details').html(html);
                        } else {
                            $('#party_id').val('').trigger('change');
                            $('#party_code').val('');
                        }
                    }
                })
            });

            $('#party_id').on('change', function() {
                var party = $(this).val();
                var $products = $('#sales_entry_id');

                $.ajax({
                    type: "GET",
                    url: "{{ route('party-products') }}",
                    data: {
                        'id': party
                    },
                    success: function(data) {
                        if (data.party) {
                            $('.party_details').html('');
                            if (data.party.city == null) {
                                var city = '';
                            } else {
                                var city = data.party.city.name;
                            }
                            if (data.party.area == null) {
                                var area = '';
                            } else {
                                var area = data.party.area.name;
                            }
                            if (data.party.contact_person == null) {
                                var contact_person = '';
                            } else {
                                var contact_person = data.party.contact_person.name;
                            }
                            if (data.party.owner == null) {
                                var phone = '';
                                var name = '';
                            } else {
                                var phone = data.party.owner.phone_no;
                                var name = data.party.owner.name;
                            }
                            $('#party_code').val(data.party.code);
                            var html = '<h5>Party Details</h5>';
                            html += '<p> Name: ' + data.party.name + '</p>';
                            html += '<p> Mobile No: ' + data.party.phone_no + '</p>';
                            html += '<p> Email: ' + data.party.email + '</p>';
                            html += '<p> Address: ' + data.party.address + '</p>';
                            html += '<p> City: ' + city + '</p>';
                            html += '<p> Area: ' + area + '</p>';
                            html += '<p> Contact Person: ' + contact_person + ' (' + phone +
                                ')' + '</p>';
                            html += '<p> Owner: ' + name + ' (' + phone + ')' + '</p>';
                            $('.party_details').html(html);
                        }
                    }
                })
            });

            $('#is_active').on('change', function() {
                if ($(this).prop('checked')) {
                    this.value = 1; // Set the value to 1 if checked
                } else {
                    this.value = 0; // Set the value to 0 if unchecked
                }
            });

            $('#date').datepicker({
                dateFormat: 'dd-mm-yy',
            });

            $('#install_date').datepicker({
                dateFormat: 'dd-mm-yy',
                maxDate: 0
            }).on('change', function(e) {
                // Get the selected date from install_date input
                var selectedDate = $(this).datepicker('getDate'); // This returns a JavaScript Date object

                if (selectedDate) {
                    // Add 2 years to the selected date
                    var newDate = new Date(selectedDate);
                    newDate.setFullYear(newDate.getFullYear() + 2); // Add 2 years

                    // Format the new date to dd-mm-yyyy
                    var day = ("0" + newDate.getDate()).slice(-2);
                    var month = ("0" + (newDate.getMonth() + 1)).slice(-2); // Months are zero-based
                    var year = newDate.getFullYear();

                    var formattedNewDate = day + '-' + month + '-' + year;

                    // Set the new date in the service_expiry_date input field
                    $('#service_expiry_date').val(formattedNewDate);

                    // Add 6 months to the free service start date
                    var freeServiceStartDate = new Date(selectedDate);
                    freeServiceStartDate.setMonth(freeServiceStartDate.getMonth() + 6); // Add 6 months

                    // Format the new free service expiry date to dd-mm-yyyy
                    var day = ("0" + freeServiceStartDate.getDate()).slice(-2);
                    var month = ("0" + (freeServiceStartDate.getMonth() + 1)).slice(-
                        2); // Months are zero-based
                    var year = freeServiceStartDate.getFullYear();

                    var formattedFreeServiceExpiryDate = day + '-' + month + '-' + year;

                    // Set the new date in the service_expiry_date input field
                    $('#free_service_date').val(formattedFreeServiceExpiryDate);
                }
            });

            $('#service_expiry_date').datepicker({
                dateFormat: 'dd-mm-yy',
                maxDate: 0
            }).on('change', function(e) {
                // Get the selected date from install_date input
                var selectedDate = $(this).datepicker('getDate'); // This returns a JavaScript Date object

                if (selectedDate) {
                    // Add 6 months to the free service start date
                    var freeServiceStartDate = new Date(selectedDate);
                    freeServiceStartDate.setMonth(freeServiceStartDate.getMonth() + 6); // Add 6 months

                    // Format the new free service expiry date to dd-mm-yyyy
                    var day = ("0" + freeServiceStartDate.getDate()).slice(-2);
                    var month = ("0" + (freeServiceStartDate.getMonth() + 1)).slice(-
                        2); // Months are zero-based
                    var year = freeServiceStartDate.getFullYear();

                    var formattedFreeServiceExpiryDate = day + '-' + month + '-' + year;

                    // Set the new date in the service_expiry_date input field
                    $('#free_service_date').val(formattedFreeServiceExpiryDate);
                }
            });

            $('#free_service_date').datepicker({
                dateFormat: "dd-mm-yy",
                minDate: 0
            });
        })

        // Function to handle image click and show modal
        function showLargeImage(event) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            modalImage.src = event.target.src; // Set the modal image source to the clicked image
            modal.style.display = 'block'; // Show the modal
        }

        // Close the modal when clicking outside the image
        document.getElementById('imageModal').onclick = function() {
            this.style.display = 'none';
        };

        // Add event listeners to all image previews
        document.querySelectorAll('.image-preview').forEach(image => {
            image.addEventListener('click', showLargeImage);
        });
    </script>
    <style>
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .modal img {
            border: 1px solid #fff;
            border-radius: 4px;
        }
    </style>

@endSection

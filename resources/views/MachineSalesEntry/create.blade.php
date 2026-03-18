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
                                <form action="{{ route('MachineSales.store') }}" method="POST" id="MachineSales-form" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" class="form-control" placeholder="Firm Id" value="{{ App\Models\Firm::first()->id }}" name="firm_id">
                                    <input type="hidden" class="form-control" placeholder="year_id" value="1" name="year_id">
                                    <input type="hidden" name="main_machine_type" value="{{ $main_machine_type }}">

                                    <div class="row mb-1">
                                        <!-- <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputOrder" class="col-form-label">Order No.</label> <i
                                                class="text-danger">*</i>
                                            <input type="text" id="inputOrder" class="form-control" name="order_no" value="" />
                                        </div> -->
                                        
                                    </div>

                                    <div class=" row mb-1">
                                        <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                            <label for="input-Date" class="col-form-label">Party Code</label><i
                                                class="text-danger">*</i>
                                            <input type="text" name="party_code" id="party_code" class="form-control" placeholder="Party Code">
                                        </div>
                                        <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <label for="inputParty" class="col-form-label">Party Name</label> <i
                                                class="text-danger">*</i>
                                            <select class="form-control" name="party_id" id="party_id">
                                                <option value="">Choose a Party Name</option>
                                                @foreach ($parties as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if (old('party_id') == $item->id) selected @endif
                                                        data-value={{ json_encode($item) }}>{{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputParty" class="col-form-label">Product Name</label> <i
                                                class="text-danger">*</i>
                                            <select class="form-control" name="product_id" id="product_id">
                                                <option value="">Choose a Product</option>
                                                @foreach ($products as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if (old('product_id') == $item->id) selected @endif>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class=" row mb-1">
                                        
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputMFCN" class="col-form-label">M/c Factory No.</label> <i class="text-danger">*</i>
                                            <input type="text" id="factory_no" class="form-control" name="order_no" value="{{ old('order_no') }}" />
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputM/C" class="col-form-label">M/c serial No.</label> <i
                                                class="text-danger">*</i>
                                            <input type="text" id="serial_no" class="form-control" name="serial_no"
                                                value="{{ old('serial_no') }}" />
                                        </div>
                                        <!-- <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputM/C" class="col-form-label">M/c No.</label> <i
                                                class="text-danger">*</i>
                                            <input type="text" id="inputM/C" class="form-control" name="mc_no" value="" />
                                        </div> -->
                                        
                                    </div>

                                    <div class="row mb-1">
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputExpiry" class="col-form-label">Install Date</label> <i
                                                class="text-danger">*</i>
                                            <input type="text" class="form-control" placeholder="dd-mm-yyyy"
                                                id="install_date" name="install_date" value="{{ old('install_date') }}">
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputExpiry" class="col-form-label">Expiry Date</label> <i
                                                class="text-danger">*</i>
                                            <input type="text" class="form-control" placeholder="dd-mm-yyyy"
                                                id="service_expiry_date" name="service_expiry_date"
                                                value="{{ old('service_expiry_date') }}">
                                        </div>
                                    </div>

                                    <div class=" row mb-1">
                                        <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                            <label for="inputParty" class="col-form-label">Free Service</label>
                                            <input type="text" name="free_service" id="free_service"
                                                class="form-control" value="{{ old('free_service') ?? 1 }}">
                                        </div>
                                        <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                            <label for="inputParty" class="col-form-label">Free Service Date</label> <i
                                                class="text-danger">*</i>
                                            <input type="text" class="form-control" placeholder="dd-mm-yyyy"
                                                id="free_service_date" name="free_service_date"
                                                value="{{ old('free_service_date') }}" autocomplete="off">
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputParty" class="col-form-label">Service Type</label> <i
                                                class="text-danger">*</i>
                                            <select class="form-control" name="service_type_id" id="service_type_id">
                                                @foreach (App\Models\ServiceType::all() as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if (old('service_type_id') == $item->id) selected @endif
                                                        {{ $loop->first ? 'selected' : '' }}>{{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-1">
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputOrder" class="col-form-label">Contract No.</label>
                                            <input type="text" id="inputOrder" class="form-control"
                                                name="contenor_no" value="{{ old('contenor_no') }}" />
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputOrder" class="col-form-label">Remark </label>
                                            <input type="text" id="inputOrder" class="form-control" name="remarks"
                                                value="{{ old('remark') }}" />
                                        </div>
                                    </div>

                                    <div class="row mb-1">
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputParty" class="col-form-label">Delivery Person</label> <i
                                                class="text-danger">*</i>
                                            <select class="form-control" name="delivery_engineer_id"
                                                id="delivery_engineer_id">
                                                <option value="">Choose a Delivery Person</option>
                                                @foreach ($engineers as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if (old('delivery_engineer_id') == $item->id) selected @endif>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputParty" class="col-form-label">Fitting Engineer.</label> <i
                                                class="text-danger">*</i>
                                            <select class="form-control" name="mic_fitting_engineer_id"
                                                id="mic_fitting_engineer_id">
                                                <option value="">Choose a Fitting Engineer.</option>
                                                @foreach ($engineers as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if (old('mic_fitting_engineer_id') == $item->id) selected @endif>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-1">
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputImage" class="col-form-label">Image 1</label>
                                            <input type="file" id="inputImage" class="form-control" name="image" />
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputImage" class="col-form-label">Image 2</label>
                                            <input type="file" id="inputImage" class="form-control" name="image1" />
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputImage" class="col-form-label">Image 3</label>
                                            <input type="file" id="inputImage" class="form-control" name="image2" />
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputImage" class="col-form-label">Image 4</label>
                                            <input type="file" id="inputImage" class="form-control" name="image3" />
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="input-Date" class="form-label">Date</label> <i
                                                class="text-danger">*</i>
                                            <input type="text" class="form-control" placeholder="dd-mm-yyyy" id="date" name="date" value="{{ $today }}">
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <div class="form-check form-switch form-switch-margin">
                                                <input class="form-check-input" type="checkbox" id="is_active"
                                                    name="is_active" value="1" checked>
                                                <label for="inputActive" class="col-form-label py-0">Is Active</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-2">
                                        <div class="row justify-content-end mt-3">
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-2">
                                                <button class="btn btn-outline-light w-100" type="reset"
                                                    onclick="location.reload()">Reset</button>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-2">
                                                <input type="submit" class="btn btn-primary w-100" value="Submit">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-xl-6 col-md-6 col-sm-12">
                                <div class="party_details">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>




    </div>

@endSection
@section('scripts')
    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script> --}}
    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script> --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#party_id').select2();

            $('#product_id').select2();

            $('#service_type_id').select2();

            $('#mic_fitting_engineer_id').select2();

            $('#delivery_engineer_id').select2();

            // Custom validation method to check if the service_expiry_date is greater than install_date
            $.validator.addMethod("greaterThan", function(value, element, param) {
                var installDate = $(param).datepicker("getDate");
                var serviceExpiryDate = $(element).datepicker("getDate");
                return serviceExpiryDate && installDate && serviceExpiryDate > installDate;
            }, "Expiry date must be greater than install date.");

            $('#MachineSales-form').validate({
                rules: {
                    bill_no: {
                        required: true,
                        maxlength: 8
                    },
                    date: {
                        required: true,
                        // date: true
                    },
                    party_id: {
                        required: true
                    },
                    product_id: {
                        required: true
                    },
                    order_no: {
                        required: true
                    },
                    serial_no: {
                        required: true
                    },
                    mc_no: {
                        required: true
                    },
                    install_date: {
                        required: true,
                        // date: true
                    },
                    service_expiry_date: {
                        required: true,
                        // date: true
                        greaterThan: "#install_date" // Custom rule
                    },
                    service_type_id: {
                        required: true
                    },
                    free_service: {
                        number: true
                    },
                    mic_fitting_engineer_id: {
                        required: true
                    },
                    delivery_engineer_id: {
                        required: true
                    },
                    map_url: {
                        url: true
                    }
                },
                messages: {
                    bill_no: {
                        required: "Please enter the bill number"
                    },
                    date: {
                        required: "Please enter the date",
                        // date: "Please enter a valid date"
                    },
                    party_id: {
                        required: "Please select a party"
                    },
                    product_id: {
                        required: "Please select a product"
                    },
                    order_no: {
                        required: "Please enter the order number"
                    },
                    mc_no: {
                        required: "Please enter the machine number"
                    },
                    install_date: {
                        required: "Please enter the installation date",
                        // date: "Please enter a valid date"
                    },
                    service_expiry_date: {
                        required: "Please enter the service expiry date",
                        // date: "Please enter a valid date"
                    },
                    service_type_id: {
                        required: "Please select a service type"
                    },
                    free_service: {
                        required: "Please select a free service option"
                    },
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

            $('#party_id').on('change', function() {
                var party = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "{{ route('party-products') }}",
                    data: {
                        'id': party
                    },
                    success: function(data) {
                        if (data.party) {
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

            $('#date').datepicker({
                dateFormat: "dd-mm-yy", // Change format to d-m-Y (day-month-year)
                onSelect: function(selectedDate) {
                    // When a date is selected, update the minDate for install_date
                    var selectedDateObj = $(this).datepicker('getDate');
                    $('#install_date').datepicker('option', 'minDate', selectedDateObj);
                    $('#service_expiry_date').datepicker('option', 'minDate', selectedDateObj);
                }
            });

            $('#install_date').datepicker({
                dateFormat: "dd-mm-yy",
                minDate: 0
            }).on('change', function(e) {
                // Get the selected date from install_date input
                var selectedDate = $(this).datepicker('getDate'); // This returns a JavaScript Date object

                if (selectedDate) {
                    // Add 1 years to the selected date
                    var newDate = new Date(selectedDate);
                    newDate.setFullYear(newDate.getFullYear() + 1); // Add 2 years

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
                dateFormat: "dd-mm-yy",
                minDate: 0
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

            $('#is_active').on('change', function() {
                if ($(this).prop('checked')) {
                    this.value = 1; // Set the value to 1 if checked
                } else {
                    this.value = 0; // Set the value to 0 if unchecked
                }
            });
        });
    </script>
    {{-- <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
{!! JsValidator::formRequest('App\Http\Requests\MachineSalesEntryRequest', '#MachineSales-form') !!} --}}

    {{-- <script type="text/javascript"></script> --}}


@endSection

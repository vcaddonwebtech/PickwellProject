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

        <!-- Start:: row-1 -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            {{ $title }}
                        </div>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li class="text-danger">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('parties.update', ['party' => $party]) }}" method="POST" id="party-form">
                        @csrf
                        @method('post')
                        <input type="hidden" name="code" value="{{ $party->id }}">
                        <input type="hidden" name="party_id" value="{{ $party->id }}">
                        <input type="hidden" class="form-control" placeholder="Firm Id" value="{{ $party->firm_id ?? App\Models\Firm::first()->id }}" name="firm_id">
                        <input type="hidden" name="main_machine_type" value="{{ $main_machine_type }}">
                        <div class="card-body gy-4">
                            <div class="row mb-2">
                                <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                    <label for="input_name" class="col-form-label">Name</label> <i class="text-danger">*</i>
                                    <input type="text" id="input_name" class="form-control" name="name" value="{{ $party->name ?? old('name') }}" >
                                </div>
                                <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                    <label for="inputshort" class="col-form-label">Mobile no.</label> <i class="text-danger">*</i>
                                    <input type="tel" id="inputshort" class="form-control" name="phone_no" value="{{ $party->phone_no ?? old('phone_no') }}" maxlength="10">
                                </div>
                                <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                    <label for="inputName" class="col-form-label">Email</label>
                                    <input type="email" id="email" class="form-control" name="email" value="{{ $party->email ?? old('email') }}">
                                </div>
                            </div>
                            <div class="form-group row mb-2">
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                    <label for="input-textarea" class="col-form-label">Address</label> <i class="text-danger">*</i>
                                    <textarea type="text" class="form-control" id="input-textarea" name="address" >{{ $party->address ?? old('address') }}</textarea>
                                </div>
                                <div class="form-group  col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                    <label for="city" class="col-form-label">City</label>
                                    <select class="form-control" id="city" name="city_id">
                                        <option value="">Choose a Option</option>
                                        @foreach (App\Models\City::all() as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($item->id == 1) selected @endif>
                                                {{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                    <label for="area" class="col-form-label">Area</label> <i class="text-danger">*</i>
                                    <select class="form-control" id="area" name="area_id">
                                        <option value="">Choose a Option</option>
                                        @foreach (App\Models\Area::all() as $item)
                                            <option value="{{ $item->id }}"
                                                @if ($party->area_id == $item->id || old('area_id') == $item->id) selected @endif>{{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                    <label for="location_address" class="col-form-label">Map Location</label>
                                    <input type="tel" id="location_address" class="form-control" name="location_address" value="{{ $party->location_address ?? old('location_address') }}">
                                </div>
                                <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                    <label for="other_phone_no" class="col-form-label">Supervisor Phone No.</label>
                                    <input type="tel" id="other_phone_no" class="form-control" name="other_phone_no" value="{{ $party->other_phone_no ?? old('other_phone_no') }}">   
                                </div>
                                <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                    <div class="form-check form-switch form-switch-margin">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @if ($party->is_active == 1) checked @endif>
                                        <label for="inputActive" class="col-form-label py-0">Is Active</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div id="firmsWrapper">
                                    <br>
                                    <a href="#" id="addfirms" class="btn btn-md btn-primary">Add Firms</a>
                                    <br>
                                    <div class="firm-row">
                                        @foreach ($party_firm_data as $item)
                                        <br>
                                        <input type="hidden" name="pfirm_id[]" value="{{ $item->id }}">
                                        <div class="row mb-1">
                                            <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                                <label>Firm Name</label> <i class="text-danger">*</i>
                                                <input type="text" class="form-control" name="firm_name[{{ $item->id }}]" value="{{ $item->firm_name }}">
                                            </div>
                                            <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                                <label>Firm Owner</label>
                                                <input type="text" class="form-control" name="firm_owner[{{ $item->id }}]" value="{{ $item->firm_owner }}">
                                            </div>
                                            <div class="form-group col-xl-2 col-lg-2 col-md-3 col-sm-12">
                                                <label>Owner Phone</label>
                                                <input type="text" class="form-control" name="firmowner_phone[{{ $item->id }}]" value="{{ $item->firmowner_phone }}">
                                            </div>
                                            <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                                <label>Firm GST</label>
                                                <input type="text" class="form-control" name="firm_gst[{{ $item->id }}]" value="{{ $item->firm_gst }}">
                                            </div>
                                            <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                                <label>Firm Address</label>
                                                <input type="text" class="form-control" name="firm_address[{{ $item->id }}]" value="{{ $item->firm_address }}">
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div> 
                             </div> 
                            <div class="row mb-2">  
                                <!-- ======submit=button==== -->
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-2">
                                    <div class="row justify-content-end mt-3">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-2 ">
                                            <button class="btn btn-primary w-100" type="submit">Submit</button>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-2 ">
                                            <button type="reset" class="btn btn-outline-light w-100" id="resetButton">Reset</button>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
        <!-- End:: row-1 -->

    </div>
@endSection
@section('scripts')
    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script> --}}
    {{-- <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script> --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('#city').select2({
                placeholder: 'Select an option'
            });
            $('#state').select2({
                placeholder: 'Select an option'
            });
            $('#party-form').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    address: {
                        required: true,
                    },
                    phone_no: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    area_id: {
                        required: true
                    },
                    gst_no: {
                        minlength: 15,
                        maxlength: 15
                    },
                    pan_no: {
                        minlength: 10,
                        maxlength: 10
                    }
                },
                messages: {
                    name: {
                        required: "Please enter the party name",
                        minlength: "The party name must be at least 3 characters long"
                    },
                    address: {
                        required: "Please enter the address",
                        minlength: "The address must be at least 10 characters long"
                    },
                    phone_no: {
                        required: "Please enter the mobile number",
                        digits: "The mobile number must be numeric",
                        minlength: "The mobile number must be 10 digits long",
                        maxlength: "The mobile number must be 10 digits long"
                    },
                    area_id: {
                        required: "Please select an area"
                    },
                    gst_no: {
                        required: "Please enter the GST number",
                        minlength: "The GST number must be 15 characters long",
                        maxlength: "The GST number must be 15 characters long"
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

            $('#resetButton').click(function() {
                $('#city').val(null).trigger('change');
                $('#state').val(null).trigger('change');
                $('#area').val(null).trigger('change');
                $('#contact_person_id').val(null).trigger('change');
                $('#owner_id').val(null).trigger('change');
                
            });

            $('#is_active').on('change', function() {
                if ($(this).prop('checked')) {
                    this.value = 1; // Set the value to 1 if checked
                } else {
                    this.value = 0; // Set the value to 0 if unchecked
                }
            });

            $('#addfirms').on('click', function (e) {
            e.preventDefault();

            let firmRow = `
            <div class="firm-row">
                <br>
                <div class="row mb-1">
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                        <label>Firm Name</label>
                        <input type="text" class="form-control" name="firm_namen[]">
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                        <label>Firm Owner</label>
                        <input type="text" class="form-control" name="firm_ownern[]">
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-3 col-sm-12">
                        <label>Owner Phone</label>
                        <input type="text" class="form-control" name="firmowner_phonen[]">
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                        <label>Firm GST</label>
                        <input type="text" class="form-control" name="firm_gstn[]">
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                        <label>Firm Address</label>
                        <input type="text" class="form-control" name="firm_addressn[]">
                    </div>
                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12 d-flex align-items-end">
                        <button type="button" class="btn btn-danger removeFirm">Remove</button>
                    </div>
                </div>
            </div>`;

            $('#firmsWrapper').append(firmRow);
        });

        // Remove firm row
        $(document).on('click', '.removeFirm', function () {
            $(this).closest('.firm-row').remove();
        });

        });
    </script>


@endSection

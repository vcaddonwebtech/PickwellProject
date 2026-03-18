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
                        <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('machinesalesdata', ['main_machine_type' => $main_machine_type]) }}">{{ $main_machine_name }}</a></li>
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
                <form action="{{ route('salesleadstore') }}" method="POST" id="lead-form">
                    @csrf
                    <input type="hidden" name="sale_user_id" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="firm_id" value="{{ App\Models\Firm::first()->id }}">
                    <input type="hidden" name="year_id" value="{{ App\Models\Year::first()->id }}">
                    <input type="hidden" name="main_machine_type" value="{{ $main_machine_type }}">
                    <div class="card-body gy-4">
                        <div class="row mb-2">
                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="input-Date" class="col-form-label">Date</label> <i class="text-danger">*</i>
                                <input type="text" class="form-control" placeholder="yyyy-mm-dd" id="date" name="date" value="{{ old('date') }}" required>
                            </div>
                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="inputBill" class="col-form-label">Time</label>
                                <input type="time" name="time" value="{{ date('H:i') }}" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="inputpartyname" class="col-form-label">Party Name</label> <i class="text-danger">*</i>
                                <input type="text" id="partyname" class="form-control" name="partyname" value="{{ old('partyname') }}" required>
                            </div>
                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="inputmobile_no" class="col-form-label">Party Phone</label> <i class="text-danger">*</i>
                                <input type="text" id="mobile_no" class="form-control" name="mobile_no" value="{{ old('mobile_no') }}" required>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="inputaddress" class="col-form-label">Party Address</label> <i class="text-danger">*</i>
                                <input type="text" id="address" class="form-control" name="address" value="{{ old('address') }}" required>
                            </div>
                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                <label for="inputlocation_Address" class="col-form-label">Party Area</label> <i class="text-danger">*</i>
                                <input type="text" id="location_Address" class="form-control" name="location_Address" value="{{ old('location_Address') }}" required>
                            </div>
                        </div>
                        <div class="row mb-2" >

                            <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                <label for="inputName" class="col-form-label">Product</label> <i
                                    class="text-danger">*</i>
                                <select name="product_id" id="product_id" class="form-control">
                                    <option value="">Select</option>
                                    @foreach (App\Models\Product::all() as $key => $product)
                                    <option value="{{ $product->id }}" {{
                                        old('product_id')==$product->id ? 'selected' : '' }}>
                                        {{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                <label for="inputName" class="col-form-label">Status</label> <i
                                    class="text-danger">*</i>
                                <select name="status_id" id="status_id" class="form-control">
                                    <option value="4" {{old('status') == '4' ? 'selected' : ''}}> Pending</option>
                                    <option value="5" {{old('status') == '5' ? 'selected' : ''}}> In Progress</option>
                                    <option value="7" {{old('status') == '7' ? 'selected' : ''}}> Closed</option>

                                </select>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-1">
                                <div id="multiCollapseExample1">
                                    <label for="inputParty" class="col-form-label">Sales Person</label>
                                    <select class="form-control" name="sale_assign_user_id" id="sale_assign_user_id">
                                        <option value="">Choose a Sales Person</option>
                                            @foreach ($salespersons as $item)
                                                <option value="{{ $item->id }}"
                                                @if (old('sale_assign_user_id') == $item->id) selected @endif>
                                                {{ $item->name . ' ( ' . $item->pending_sales . ' ) ' }}
                                                </option>
                                            @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>                          
                        <div class="row mb-1">
                                        <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                            <label for="inputremarks" class="col-form-label">Remark</label>
                                            <textarea type="text" id="remarks" class="form-control" name="remarks" aria-describedby="nameHelpInline"> {{ old('remarks') }}</textarea>
                                        </div>

                        </div>
                        <div class="row mb-2">
                                <div class="col-xl-6 col-lg-6 col-md-8 col-sm-12 mb-2">
                                    <div class="row justify-content-end mt-3">
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-2 ">
                                            <input type="reset" class="btn btn-outline-light w-100">
                                        </div>
                                        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 mb-2">
                                            <button class="btn btn-primary w-100" type="submit">Submit</button>
                                        </div>
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
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

            $('#engineer-form').validate({
                rules: {
                    party_name: {
                        required: true,
                    },
                    product_id: {
                        required: true,
                    },
                    type_of_lead: {
                        required: true,
                    },
                    schedule_meeting: {
                        required: true,
                    },
                    date_of_lead: {
                        required: true
                    },
                    status:{
                        required:true
                    }

                },
                messages: {
                    party_name: {
                        required: "Please enter name",
                    },
                    product_id: {
                        required: "Please select product group",
                    },
                    type_of_lead: {
                        required: "Please enter hsn code",
                    },
                    schedule_meeting: {
                        required: "Please enter gst",
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
            $('#date').datepicker({
                dateFormat: "yy-mm-dd", // Change format to d-m-Y (day-month-year)
            });
        });
</script>
@endSection
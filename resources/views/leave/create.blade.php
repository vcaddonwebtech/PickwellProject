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
                            <form action="{{ route('leave.store') }}" method="POST" id="holiday-form">
                                @csrf
                                <div class="row mb-1">
                                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="user_id" class="col-form-label">Staff</label> <i class="text-danger">*</i>
                                        <select class="form-control" name="user_id" id="user_id">
                                                <option value="">Choose a Option</option>
                                                @foreach ($users as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                        <label for="input-Date" class="col-form-label">Leave From</label> <i class="text-danger">*</i>
                                        <input type="text" class="form-control" placeholder="dd-mm-yyyy" id="leave_from" name="leave_from" value="{{ old('leave_from') }}">
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                        <label for="input-Date" class="col-form-label">Leave Till</label> <i class="text-danger">*</i>
                                        <input type="text" class="form-control" placeholder="dd-mm-yyyy" id="leave_till" name="leave_till" value="{{ old('leave_till') }}">
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="description" class="col-form-label">Days</label> 
                                        <input type="text" class="form-control" id="total_leave" name="total_leave">
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                        <label for="input-Date" class="col-form-label">Leave Type</label> <i class="text-danger">*</i>
                                        <select class="form-control" name="leave_type" id="leave_type">
                                                <option value="">Choose a Option</option>
                                                <option value="Casual">Casual</option>
                                                <option value="Sick">Sick</option>
                                                <option value="Paid">Paid</option>
                                                <option value="Unpaid">Unpaid</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                        <label for="input-Date" class="col-form-label">Leave Duration</label> 
                                        <select class="form-control" name="leave_duration" id="leave_duration">
                                                <option value="">Choose a Option</option>
                                                <option value="Full">Full Day</option>
                                                <option value="Half">Half Day</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="reason" class="col-form-label">Reason</label> <i class="text-danger">*</i>
                                        <textarea class="form-control" placeholder="Leave Reason here..." id="reason" name="reason"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="is_approved" class="col-form-label">Approved</label> 
                                        <select class="form-control" name="is_approved" id="is_approved">
                                                <option value=1>Approved</option>
                                                <option value=0>Not Approved</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <!-- ======submit=button==== -->
                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-2 justify-content-end">
                                        <div class="row justify-content-end mt-3">
                                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-2 ">
                                                <input type="reset" class="btn btn-outline-light w-100">
                                            </div>

                                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-2 ">
                                                <button class="btn btn-primary w-100">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- End:: row-1 -->

</div>
@endSection

@section('scripts')


    <script type="text/javascript">
        $(document).ready(function() {
            $('#user_id').select2({
                placeholder: 'Select Staff'
            });
            $('#leave_from').datepicker({
                dateFormat: "dd-mm-yy",  // Change format to d-m-Y (day-month-year)
            });
            $('#leave_till').datepicker({
                dateFormat: "dd-mm-yy",  // Change format to d-m-Y (day-month-year)
            });
            $('#leave_type').select2({
                placeholder: 'Select type'
            });
            $('#leave_duration').select2({
                placeholder: 'Select duration'
            });
            $('#is_approved').select2({
                placeholder: 'Approved'
            });
            $("#holiday-form").validate({
                rules: {
                    leave_from: {
                        required: true,
                    },
                    reason: {
                        required: true,
                    },
                },
                messages: {
                    leave_from: {
                        required: "Please enter the date",
                        date: "Please enter a valid date"
                    },
                    reason: {
                        required: "Please enter reason"
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
            
        });
    </script>


@endSection
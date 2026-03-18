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
                @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li class="text-danger">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form action="{{ route('engineers.update', ['engineer' => $engineer->id]) }}" method="POST"
                    id="engineer-form">
                    @csrf
                    <div class="card-body gy-4">
                        <div class=" row mb-2">
                            <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                <label for="inputName" class="col-form-label">engineer Name</label> <i
                                    class="text-danger">*</i>
                                <input type="text" id="name" class="form-control" name="name"
                                    value="{{ $engineer->name ?? old('name') }}" required>
                            </div>
                            <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                <label for="inputName" class="col-form-label">engineer Email</label> <i
                                    class="text-danger">*</i>
                                <input type="email" id="email" class="form-control" name="email"
                                    value="{{ $engineer->email ?? old('email') }}">
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                <label for="inputName" class="col-form-label">Password</label> <i
                                    class="text-danger">*</i>
                                <input type="password" id="password" class="form-control" name="password"
                                    autocomplete="on">
                            </div>

                            <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                <label for="inputName" class="col-form-label">Confirm Password</label> <i
                                    class="text-danger">*</i>
                                <input type="password" id="confirm_password" class="form-control"
                                    name="confirm_password" autocomplete="on">
                            </div>
                        </div>
                        <div class=" row mb-2">
                            <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                <label for="inputName" class="col-form-label">Phone</label> <i class="text-danger">*</i>
                                <input type="text" id="phone_no" class="form-control" name="phone_no"
                                    value="{{ $engineer->phone_no ?? old('phone') }}" required>
                            </div>
                            <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                <label for="inputName" class="col-form-label">Area</label> <i class="text-danger">*</i>
                                <select name="area_id" id="area" class="form-control">
                                    <option value="">Choose a Option</option>
                                    @foreach (App\Models\Area::all() as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $engineer->area_id ? 'selected' : ''
                                        }}>{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class=" row mb-2">
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 form-group">
                                    <label for="input-textarea" class="col-form-label">Is Active</label> <i
                                        class="text-danger">*</i>
                                    <input type="checkbox" name="is_active" id="is_active"
                                        value="{{ $engineer->is_active ? '1' : '0'}}" {{ $engineer->is_active ?
                                    'checked' : '' }} class= "form-check-input">
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
            $('#is_active').trigger('change');

            $('#is_active').on('change', function() {
                if ($(this).is(':checked')) {
                    $(this).val('1');
                } else {
                    $(this).val('0');
                }
            });
            $('#area_id').select2({
                placeholder: 'Select an option'
            });
            $('#engineer-form').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true
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
                },
                messages: {
                    name: {
                        required: "Please enter your name",
                        minlength: "Your name must be at least 3 characters long"
                    },
                    email: {
                        required: "Please enter your email",
                        email: "Please enter a valid email address"
                    },
                    password: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 6 characters long"
                    },
                    password_confirmation: {
                        required: "Please provide a password",
                        minlength: "Your password must be at least 6 characters long"
                    },
                    phone_no: {
                        required: "Please enter your phone number",
                        digits: "Please enter a valid phone number",
                        minlength: "The phone number must be 10 digits long",
                        maxlength: "The phone number must be 10 digits long"
                    },
                    area_id: {
                        required: "Please select an area"
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
        });
</script>


@endSection
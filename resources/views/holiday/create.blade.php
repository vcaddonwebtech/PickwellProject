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
                            <form action="{{ route('holiday.store') }}" method="POST" id="holiday-form">
                                @csrf
                                <div class="row mb-1">
                                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="input-Date" class="col-form-label">Date</label> <i class="text-danger">*</i>
                                        <input type="text" class="form-control" placeholder="dd-mm-yyyy" id="date" name="date" value="{{ old('date') }}">
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="description" class="col-form-label">Description</label> <i
                                            class="text-danger">*</i>
                                        <textarea class="form-control" placeholder="Description here..." id="description" name="description"></textarea>
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
            $('#date').datepicker({
                dateFormat: "dd-mm-yy",  // Change format to d-m-Y (day-month-year)
            });

            $("#holiday-form").validate({
                rules: {
                    date: {
                        required: true,
                    },
                    description: {
                        required: true,
                    },
                },
                messages: {
                    date: {
                        required: "Please enter the date",
                        date: "Please enter a valid date"
                    },
                    description: {
                        required: "Please enter description"
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
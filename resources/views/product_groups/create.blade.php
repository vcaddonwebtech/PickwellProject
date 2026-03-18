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
                <form action="{{ route('product-groups.store') }}" method="POST" id="service-type-form">
                    @csrf
                    <div class="card-body gy-4">
                        <div class=" row mb-2">
                            <div class="form-group col-xl-6 col-lg-6 col-md-8 col-sm-12">
                                <label for="inputName" class="col-form-label">Name</label> <i class="text-danger">*</i>
                                <input type="text" id="inputName" class="form-control" name="name"
                                    value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class=" row mb-2">
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

            $('#service-type-form').validate({
                rules: {
                    name: {
                        required: true,
                    },
                    product_group_id: {
                        required: true,
                    }
                },
                messages: {
                    name: {
                        required: "Please enter name",
                    },
                    product_group_id: {
                        required: "Please select a product group",
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
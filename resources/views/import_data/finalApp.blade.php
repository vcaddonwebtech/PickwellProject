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

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Contact Person</div>
                </div>
                <div class="card-body">
                    <form action="{{ route('importData.contactPerson') }}" method="POST" id="finalAppContactForm" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <input type="file" name="file">
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-primary" type="submit">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Party</div>
                </div>
                <div class="card-body">
                    <form action="{{ route('importData.finalAppPost') }}" method="POST" id="finalAppForm" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <input type="file" name="file">
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-primary" type="submit">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Product</div>
                </div>
                <div class="card-body">
                    <form action="{{ route('importData.product') }}" method="POST" id="finalAppProductForm" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <input type="file" name="file">
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-primary" type="submit">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Machine Sales</div>
                </div>
                <div class="card-body">
                    <form action="{{ route('importData.machine-sales') }}" method="POST" id="finalAppMachineForm" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <input type="file" name="file">
                        </div>
                        <div class="mt-2">
                            <button class="btn btn-primary" type="submit">Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#finalAppContactForm').validate({
                rules: {
                    file: {
                        required: true,
                    },
                },
                submitHandler: function(form) {
                    let form = $('#finalAppForm')[0];
                    let formData = new FormData(form);
                    $.ajax({
                        url: $(form).attr('action'),
                        type: "post",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log(response);
                        },
                    });
                }
            });
            $('#finalAppForm').validate({
                rules: {
                    file: {
                        required: true,
                    },
                },
                submitHandler: function(form) {
                    let form = $('#finalAppForm')[0];
                    let formData = new FormData(form);
                    $.ajax({
                        url: $(form).attr('action'),
                        type: "post",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log(response);
                        },
                    });
                }
            });
            $('#finalAppProductForm').validate({
                rules: {
                    file: {
                        required: true,
                    },
                },
                submitHandler: function(form) {
                    let form = $('#finalAppProductForm')[0];
                    let formData = new FormData(form);
                    $.ajax({
                        url: $(form).attr('action'),
                        type: "post",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log(response);
                        },
                    });
                }
            });
            $('#finalAppMachineForm').validate({
                rules: {
                    file: {
                        required: true,
                    },
                },
                submitHandler: function(form) {
                    let form = $('#finalAppMachineForm')[0];
                    let formData = new FormData(form);
                    $.ajax({
                        url: $(form).attr('action'),
                        type: "post",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            console.log(response);
                        },
                    });
                }
            });
        });
    </script>
@endsection
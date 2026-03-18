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


    <!-- Start:: row-4 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">User Import</div>
                </div>
                <div class="card-body">
                    <form action="{{ route('importData.store') }}" method="POST" id="importDataForm" enctype="multipart/form-data">
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
            $('#importDataForm').validate({
                rules: {
                    file: {
                        required: true,
                    },
                },
                submitHandler: function(form) {
                    let form = $('#importDataForm')[0];
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
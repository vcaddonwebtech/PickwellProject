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
                        {{ $title }} [Vouchar No : {{ $vou_no }}]
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
                            <form action="{{ route('parts_inventory.store') }}" method="POST" id="parth-form">
                                <input type="hidden" name="vou_no" value="{{ $vou_no }}">
                                @csrf
                                <div class="row mb-1">
                                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="input-Date" class="col-form-label">Date</label> <i class="text-danger">*</i>
                                        <input type="text" class="form-control" placeholder="dd-mm-yyyy" id="date" name="date" value="{{ old('date') }}">
                                    </div>

                                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="inputBill" class="col-form-label">Time</label>
                                        <input type="time" name="time" value="{{ date('H:i') }}"
                                            class="form-control">
                                    </div>

                                </div>
                                <div class="row mb-1">
                                    <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <label for="in_engineer_id" class="col-form-label">Engineer</label><i class="text-danger">*</i>
                                        <select class="form-control" name="in_engineer_id"
                                            id="in_engineer_id">
                                            <option value="">Choose a Engineer</option>
                                            @foreach (App\Models\User::role('Engineer')->get() as $item)
                                                <option value="{{ $item->id }}"
                                                    @if (old('in_engineer_id') == $item->id) selected @endif>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                        <label for="input-Date" class="col-form-label">Party Code</label>
                                        <i class="text-danger">*</i>
                                        <input type="text" name="party_code" id="party_code" class="form-control"
                                            placeholder="Party Code">
                                    </div>
                                    <div class="form-group col-xl-10 col-lg-10 col-md-10 col-sm-12">
                                        <label for="inputSale" class="col-form-label">Party Name</label>
                                        <i class="text-danger">*</i>
                                        <select class="form-control" name="in_party_id" id="in_party_id">
                                            <option value="">Choose a Option</option>
                                            @foreach (App\Models\Party::all() as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->name . '(' . $item->address . ')' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <label for="product_id" class="col-form-label">Product</label> <i
                                            class="text-danger">*</i>
                                        <select type="text" id="product_id" name="product_id"
                                            class="form-control">
                                            <option value="">Choose a Option</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <div id="card_no">
                                            <label for="card_no" class="col-form-label">Card No</label><i class="text-danger">*</i>
                                            <input type="text" id="card_no" class="form-control" placeholder="Enter card no" name="card_no">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <label for="remarks" class="col-form-label">Remarks</label> <i
                                            class="text-danger">*</i>
                                        <textarea class="form-control" placeholder="Remarks here..." id="remarks" name="remarks"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <!-- ======submit=button==== -->
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-2 justify-content-end">
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
            var today = new Date();
            var day = String(today.getDate()).padStart(2, '0');
            var month = String(today.getMonth() + 1).padStart(2, '0'); // January is 0
            var year = today.getFullYear();

            // Format today's date as dd-mm-yyyy
            var formattedDate = day + '-' + month + '-' + year;

            // Set the value of the input field to today's date if it's empty
            if (!$('#date').val()) {
                $('#date').val(formattedDate);
            }

            $('#product_id').select2({
                placeholder: 'Select an option'
            });
            $('#in_engineer_id').select2({
                placeholder: 'Select an option'
            })
            $('#in_party_id').select2({
                placeholder: 'Select an option'
            })

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
                            $('#in_party_id').val(data.party.id).trigger('change');
                        } else {
                            $('#in_party_id').val('').trigger('change');
                            $('#party_code').val('');
                        }
                    }
                })
            });

            $('#in_party_id').on('change', function() {
                var party = $(this).val();
                var $products = $('#product_id');

                $.ajax({
                    type: "GET",
                    url: "{{ route('party-products') }}",
                    data: {
                        'id': party
                    },
                    success: function(data) {
                        if (data.party) {
                            $('#party_code').val(data.party.code);
                        }

                        $products.empty();
                        $products.append('<option selected disabled>Select Product</option>');
                        if (data.partyProducts.length > 0) {

                            $.each(data.partyProducts, function(key, value) {
                                $products.append('<option value="' + value
                                    .id + '">' + value.mc_no + '- ' + value.product.name + '- ' + value
                                    .serial_no + '</option>');
                            })
                        }
                        $('#product_id').select2({
                            placeholder: 'Select an option'
                        });
                    }
                })

            });

            $('#date').datepicker({
                dateFormat: "dd-mm-yy",  // Change format to d-m-Y (day-month-year)
            });

            $("#parth-form").validate({
                rules: {
                    date: {
                        required: true,
                    },
                    time: {
                        required: true,
                    },
                    in_engineer_id: {
                        required: true
                    },
                    product_id: {
                        required: true
                    },
                    party_code: {
                        required: true
                    },
                    in_party_id: {
                        required: true
                    },
                    card_no: {
                        required: true
                    },
                },
                messages: {
                    date: {
                        required: "Please enter the date",
                        date: "Please enter a valid date"
                    },
                    time: {
                        required: "Please enter the time",
                        time: "Please enter a valid time"
                    },
                    in_engineer_id: {
                        required: "Please select a engineer"
                    },
                    product_id: {
                        required: "Please select a product"
                    },
                    party_code: {
                        required: "Please enter a party code"
                    },
                    in_party_id: {
                        required: "Please select a party name"
                    },
                    card_no: {
                        required: "Please enter a card no"
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
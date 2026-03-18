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
                        {{ $title }} [Vouchar No : {{ $partsInventory->vou_no }}]
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
                <form action="{{ route('parts_inventory.update', ['parts_inventory' => $partsInventory->id]) }}"
                    method="POST" id="item-part-form">
                    @csrf
                    <div class="card-body gy-4">
                        <div class="row">
                            <div class="col-xl-6 col-md-6 col-sm-12">
                                <div class="row mb-1">
                                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="input-Date" class="col-form-label">Date</label> <i class="text-danger">*</i>
                                        <input type="text" class="form-control" placeholder="dd-mm-yyyy" id="date" name="date" value="{{ $partsInventory->date ? date('d-m-Y', strtotime($partsInventory->date)) : old('date') }}">
                                    </div>

                                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="inputBill" class="col-form-label">Time</label>
                                        <input type="time" name="time" value="{{ $partsInventory->time ?? date('H:i') }}"
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
                                                    @if ($partsInventory->in_engineer_id == $item->id) selected @endif>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <label for="inputSale" class="col-form-label">Party Name</label> <i
                                            class="text-danger">*</i>
                                        {{-- <input type="text" id="inputSale" class="form-control" /> --}}
                                        <select class="form-control" name="in_party_id" id="in_party_id">
                                            <option value="">Choose a Option</option>
                                            @foreach (App\Models\Party::all() as $item)
                                            <option value="{{ $item->id }}" @if ($partsInventory->in_party_id == $item->id)
                                                selected
                                                @endif>{{ $item->name . ' ( ' . $item->address . ' )'
                                                }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <label for="inputComplain" class="col-form-label">Product</label> <i
                                            class="text-danger">*</i>
                                        <select type="text" id="product_id" name="product_id"
                                            class="form-control">
                                            <option value="">Choose a Option</option>
                                            @foreach ($sales_entries as $item)
                                            <option value="{{ $item->id }}" {{ $item->id == $partsInventory->product_id ?
                                                'selected' : '' }}>
                                                {{ $item->product->name . ' - ' . $item->serial_no . ' - ' .
                                                $item->mc_no }}
                                            </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <div id="card_no">
                                            <label for="card_no" class="col-form-label">Card No</label><i class="text-danger">*</i>
                                            <input type="text" id="card_no" class="form-control" placeholder="Enter card no" name="card_no" value="{{ $partsInventory->card_no ? $partsInventory->card_no : old('card_no') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-1">
                                    <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                        <div id="card_no">
                                            <label for="remarks" class="col-form-label">Remarks</label> <i class="text-danger">*</i>
                                            <textarea class="form-control" placeholder="Remarks here..." id="remarks" name="remarks">{{ old('remarks', $partsInventory->remarks) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class=" row mb-2">
                                    <div class="row mb-2">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-2">
                                            <div class="row justify-content-end mt-3">
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-2 ">
                                                    <input type="reset" class="btn btn-outline-light w-100" {{ !empty($partsInventory->repair_out_date) ? 'disabled' : '' }}>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 mb-2">
                                                    <button class="btn btn-primary w-100" type="submit" {{ !empty($partsInventory->repair_out_date) ? 'disabled' : '' }}>Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class=" row mb-2">
                                    <div class="row mb-2">
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-2">
                                            <div class="row mt-3">
                                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 mb-2">
                                                    <button type="button" class="btn btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#repairOutModal"> Repair Out </button>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 mb-2">
                                                    <button type="button" class="btn btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#repairInModal"> Repair In </button>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 mb-2">
                                                    <button type="button" class="btn btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#issueToCustmoerModal"> Issue to Party </button>
                                                </div>
                                            </div>
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

<!-- Modal Repair Out-->
<div class="modal fade" id="repairOutModal" tabindex="-1" aria-labelledby="repairOutModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('parts_inventory.repairout.update') }}"
                method="POST" id="repair-out-form">
                @csrf
                <input type="hidden" name="parts_inventory_id" value="{{ $partsInventory->id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="repairOutModalLabel">Repair Out</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-1">
                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="input-Date" class="col-form-label">Repair Out Date</label><i class="text-danger">*</i>
                            <input type="text" class="form-control" placeholder="dd-mm-yyyy" id="repair_out_date" name="repair_out_date" value="{{ $partsInventory->repair_out_date ? date('d-m-Y', strtotime($partsInventory->repair_out_date)) : old('repair_out_date') }}">
                        </div>

                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="inputBill" class="col-form-label">Repair Out Time</label><i class="text-danger">*</i>
                            <input type="time" name="repair_out_time" value="{{ $partsInventory->repair_out_time ?? date('H:i') }}" id="repair_out_time" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-12">
                            <label for="input-Date" class="col-form-label">Repair Out Party Code</label>
                            <i class="text-danger">*</i>
                            <input type="text" name="repair_out_party_code" id="repair_out_party_code" class="form-control"
                                placeholder="Party Code" value="{{ old('repair_out_party_code', $partsInventory->repair_out_party_code) }}">
                        </div>
                        <div class="form-group col-xl-9 col-lg-9 col-md-9 col-sm-12">
                            <label for="inputSale" class="col-form-label">Repair Out Party Name</label>
                            <i class="text-danger">*</i>
                            <select class="form-control" name="repair_out_party_id" id="repair_out_party_id">
                                <option value="">Choose a Option</option>
                                @foreach (App\Models\Party::all() as $item)
                                <option value="{{ $item->id }}" {{ $item->id == $partsInventory->repair_out_party_id ?
                                                'selected' : '' }}>{{ $item->name . ' ( ' . $item->address . ' )' }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="repair_out_remarks" class="col-form-label">Repair Out Remarks</label> <i
                                class="text-danger">*</i>
                            <textarea class="form-control" placeholder="Repair out remarks here..." id="repair_out_remarks" name="repair_out_remarks" style="height: auto">{{ old('repair_out_remarks', $partsInventory->repair_out_remarks) }}</textarea>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="repair_out_remarks" class="col-form-label">Expexted Required Date</label> <i
                                class="text-danger">*</i>
                            <input type="text" class="form-control" placeholder="dd-mm-yyyy" id="expexted_required_date" name="expexted_required_date" value="{{ $partsInventory->expexted_required_date ? date('d-m-Y', strtotime($partsInventory->expexted_required_date)) : old('expexted_required_date') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" {{ !empty($partsInventory->repair_in_date) ? 'disabled' : '' }}>Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Repair In-->
<div class="modal fade" id="repairInModal" tabindex="-1" aria-labelledby="repairInModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('parts_inventory.repairin.update') }}"
                method="POST" id="repair-in-form">
                @csrf
                <input type="hidden" name="parts_inventory_id" value="{{ $partsInventory->id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="repairInModalLabel">Repair In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-1">
                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="input-Date" class="col-form-label">Repair In Date</label><i class="text-danger">*</i>
                            <input type="text" class="form-control" placeholder="dd-mm-yyyy" id="repair_in_date" name="repair_in_date" value="{{ $partsInventory->repair_in_date ? date('d-m-Y', strtotime($partsInventory->repair_in_date)) : old('repair_in_date') }}">
                        </div>

                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="inputBill" class="col-form-label">Repair In Time</label><i class="text-danger">*</i>
                            <input type="time" name="repair_in_time" value="{{ $partsInventory->repair_in_time ?? date('H:i') }}" id="repair_in_time" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="repair_out_remarks" class="col-form-label">Repair In Remarks</label> <i
                                class="text-danger">*</i>
                            <textarea class="form-control" placeholder="Repair in remarks here..." id="repair_in_remarks" name="repair_in_remarks" style="height: auto">{{ old('repair_in_remarks', $partsInventory->repair_in_remarks) }}</textarea>
                        </div>
                    </div>

                    
                    @if(!empty($partsInventory->repair_out_party_id))
                    @php
                        $party = App\Models\Party::where('id',$partsInventory->repair_out_party_id)->first();
                    @endphp
                    <div class="row mb-1">
                        <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="repair_out_remarks" class="col-form-label">Repair Out Party Name</label>
                            <input type="text" class="form-control" value="{{ $party->name }}" readonly>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" {{ !empty($partsInventory->issue_date) ? 'disabled' : '' }}>Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Issue to Party In-->
<div class="modal fade" id="issueToCustmoerModal" tabindex="-1" aria-labelledby="issueToCustmoerModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('parts_inventory.issuetoparty.update') }}"
                method="POST" id="issuetoparty-form">
                @csrf
                <input type="hidden" name="parts_inventory_id" value="{{ $partsInventory->id }}">
                <div class="modal-header">
                    <h5 class="modal-title" id="issueToCustmoerModalLabel">Issue to Party</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-1">
                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="input-Date" class="col-form-label">Issue to Party Date</label><i class="text-danger">*</i>
                            <input type="text" class="form-control" placeholder="dd-mm-yyyy" id="issue_date" name="issue_date" value="{{ $partsInventory->issue_date ? date('d-m-Y', strtotime($partsInventory->issue_date)) : old('issue_date') }}">
                        </div>

                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <label for="inputBill" class="col-form-label">Issue to Party Time</label><i class="text-danger">*</i>
                            <input type="time" name="issue_time" value="{{ $partsInventory->issue_time ?? date('H:i') }}" id="issue_time" class="form-control">
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="issue_engineer_id" class="col-form-label">Issue to Engineer</label><i class="text-danger">*</i>
                            <select class="form-control" name="issue_engineer_id"
                                id="issue_engineer_id">
                                <option value="">Choose a Engineer</option>
                                @foreach (App\Models\User::role('Engineer')->get() as $item)
                                    <option value="{{ $item->id }}" {{ $item->id == $partsInventory->issue_engineer_id ?
                                                'selected' : '' }}> {{ $item->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                            <label for="repair_out_remarks" class="col-form-label">Issue to Party Remarks</label> <i
                                class="text-danger">*</i>
                            <textarea class="form-control" placeholder="Issue to party remarks here..." id="issue_remarks" name="issue_remarks" style="height: auto">{{ old('issue_remarks', $partsInventory->issue_remarks) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" {{ $partsInventory->repair_status == 3 ? '' : 'disabled' }}>Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endSection


@section('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
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
        });
        $('#in_party_id').select2({
            placeholder: 'Select an option'
        });
        
        $('#repairOutModal').on('shown.bs.modal', function () {
            $('#repair_out_party_id').select2({
                dropdownParent: $('#repairOutModal') // Ensure Select2 dropdown renders inside the modal
            });
        });

        $('#issueToCustmoerModal').on('shown.bs.modal', function () {
            $('#issue_engineer_id').select2({
                dropdownParent: $('#issueToCustmoerModal') // Ensure Select2 dropdown renders inside the modal
            });
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
                                .id + '">' + value.product.name + '- ' + value
                                .serial_no + '- ' + value.mc_no + '</option>');
                        })
                    }
                    $('#product_id').select2({
                        placeholder: 'Select an option'
                    });
                }
            })

        });

        $('#repair_out_party_code').on('change', function() {
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
                        $('#repair_out_party_id').val(data.party.id).trigger('change');
                    } else {
                        $('#repair_out_party_id').val('').trigger('change');
                        $('#repair_out_party_code').val('');
                    }
                }
            })
        });

        $('#repair_out_party_id').on('change', function() {
            var party = $(this).val();

            $.ajax({
                type: "GET",
                url: "{{ route('party-products') }}",
                data: {
                    'id': party
                },
                success: function(data) {
                    if (data.party) {
                        $('#repair_out_party_code').val(data.party.code);
                    }
                }
            })

        });
        
        $('#date').datepicker({
            dateFormat: "dd-mm-yy",  // Change format to d-m-Y (day-month-year)
        });
        $('#repair_out_date').datepicker({
            dateFormat: "dd-mm-yy",  // Change format to d-m-Y (day-month-year)
        });
        $('#expexted_required_date').datepicker({
            dateFormat: "dd-mm-yy",  // Change format to d-m-Y (day-month-year)
        });
        $('#repair_in_date').datepicker({
            dateFormat: "dd-mm-yy",  // Change format to d-m-Y (day-month-year)
        });
        $('#issue_date').datepicker({
            dateFormat: "dd-mm-yy",  // Change format to d-m-Y (day-month-year)
        });
        
        $('#item-part-form').validate({
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
        $('#repair-out-form').validate({
            rules: {
                repair_out_date: {
                    required: true,
                },
                repair_out_time: {
                    required: true,
                },
                repair_out_party_code: {
                    required: true,
                },
                repair_out_party_id: {
                    required: true
                },
                repair_out_remarks: {
                    required: true
                },
                expexted_required_date: {
                    required: true
                },
            },
            messages: {
                repair_out_date: {
                    required: "Please enter the repair out date",
                    date: "Please enter a valid date"
                },
                repair_out_time: {
                    required: "Please enter the repair out time",
                    time: "Please enter a valid time"
                },
                repair_out_party_code: {
                    required: "Please enter a repair out party code"
                },
                repair_out_party_id: {
                    required: "Please select a repair out party name"
                },
                repair_out_remarks: {
                    required: "Please enter a repair out remarks"
                },
                expexted_required_date: {
                    required: "Please enter the expexted required date",
                    date: "Please enter a valid date"
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
        $('#repair-in-form').validate({
            rules: {
                repair_in_date: {
                    required: true,
                },
                repair_in_time: {
                    required: true,
                },
                repair_in_remarks: {
                    required: true
                },
            },
            messages: {
                repair_in_date: {
                    required: "Please enter the repair out date",
                    date: "Please enter a valid date"
                },
                repair_in_time: {
                    required: "Please enter the repair out time",
                    time: "Please enter a valid time"
                },
                repair_in_remarks: {
                    required: "Please enter a repair in remarks"
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
        $('#issuetoparty-form').validate({
            rules: {
                issue_date: {
                    required: true,
                },
                issue_time: {
                    required: true,
                },
                issue_engineer_id: {
                    required: true,
                },
                issue_remarks: {
                    required: true
                },
            },
            messages: {
                issue_date: {
                    required: "Please enter the issue to party date",
                    date: "Please enter a valid date"
                },
                issue_time: {
                    required: "Please enter the issue to party time",
                    time: "Please enter a valid time"
                },
                issue_engineer_id: {
                    required: "Please select a issue to party engineer"
                },
                issue_remarks: {
                    required: "Please enter a issue to party remarks"
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
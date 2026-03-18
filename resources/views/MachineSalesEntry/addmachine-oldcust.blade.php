@extends('layouts.app')
@section('title', $title)
@section('content')
    {{-- <div class="p-3 header-secondary row client-name-wpr  bg-primary-transparent">
    <div class="col">
        <div class="d-flex">
            <!-- <h6>Petey Cruiser</h6> -->
            <h6 class="mb-0"> <strong class="mb-0 px-2"> Petey Cruiser </strong> 29/06/24 </h6>
            <!-- <a class=" d-flex client-name" href="javascript:void(0);"> <span class=""></span>  </a> -->
        </div>
    </div>
</div> --}}

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
                            <div class="col-xl-12 col-md-12 col-sm-12">
                                @if ($errors->any())
                                    <div class="alert alert-danger mt-3">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li class="text-danger">{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form action="{{ route('MachineSales.new-store') }}" method="POST" id="MachineSales-form" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" class="form-control" placeholder="year_id" value="1" name="year_id">
                                    <input type="hidden" name="main_machine_type" value="{{ $main_machine_type }}">
                                    <input type="hidden" name="activetab" value="1">
                                    <input type="hidden" name="oldcust" value="1">
                                            <div class="col-xl-12"> 
                                                <div class="card-body"> 
                                                    <div class="row gy-2"> 
                                                                <div class="col-xl-1"> 
                                                                            <ul class="nav nav-tabs flex-column vertical-tabs-2" role="tablist"> 
                                                                                <li class="nav-item" role="presentation"> 
                                                                                    <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page" href="#about-vertical-custom" aria-selected="true" tabindex="-1">
                                                                                     <p class="mb-1"><i class="ri-gift-line"></i></p><p class="mb-0 text-break">Sold Machines</p></a> 
                                                                                </li>  
                                                                            </ul> 
                                                                </div>
                                                                <div class="col-xl-11"> 
                                                                        <div class="tab-pane text-muted active show" id="about-vertical-custom" role="tabpanel">
                                                                                <ul class="mb-0"> 
                                                                                        <div class="row mb-1">
                                                                                            <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                                                                                <label for="inputMFCN" class="col-form-label">Customer</label> <i class="text-danger">*</i>
                                                                                                
                                                                                                <select class="form-control" name="party_id" id="party_id">
                                                                                                    <option value="">Choose a Party</option>
                                                                                                    @foreach ($parties as $item)
                                                                                                        <option value="{{ $item->id }}"
                                                                                                            @if (old('party_id') == $item->id) selected @endif>
                                                                                                            {{ $item->name }}
                                                                                                        </option>
                                                                                                    @endforeach
                                                                                                </select>
                                                                                            </div>
                                                                                            <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                                                                                <label for="inputMFCN" class="col-form-label">Firm</label> <i class="text-danger">*</i>
                                                                                                <div class="party_firms">
                                                                                                    <select class="form-control" name="firm_id" id="firm_id">
                                                                                                        <option value="">Choose a Firm</option>
                                                                                                    </select>
                                                                                                </div>    
                                                                                            </div>
                                                                                            <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                                                                                <label for="inputM/C" class="col-form-label">Contract</label> <i class="text-danger">*</i>
                                                                                                <input type="text" id="contenor_no" class="form-control" name="contenor_no" value="{{ old('contenor_no') }}" />
                                                                                            </div>
                                                                                        </div>
                                                                                        <div id="machinesWrapper">
                                                                                                    <br>
                                                                                                    <br>
                                                                                                    <div class="machine-row">
                                                                                                        <br>
                                                                                                        <div class="row mb-1">
                                                                                                            <!-- Serial Number -->
                                                                                                            <div class="row mb-1">
                                                                                                               <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 d-flex gap-2 align-items-end">
                                                                                                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12"><label for="inputM/C" class="col-form-label">Machine Sr. No.</label></div>
                                                                                                                    <div class="col-xl-1 col-lg-1 col-md-1 col-sm-12">
                                                                                                                    <input type="text" class="form-control sr-no" readonly style="border:none; box-shadow:none; background:transparent; padding-left:0; hight:2px;">
                                                                                                                    </div>
                                                                                                                </div> 
                                                                                                            </div>    
                                                                                                            <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                                                                                                <label>Prodduct</label>
                                                                                                                <select class="form-control" name="product_id[]" id="product_id[]">
                                                                                                                    <option value="">Product</option>
                                                                                                                    @foreach ($products as $item)
                                                                                                                        <option value="{{ $item->id }}"
                                                                                                                            @if (old('product_id') == $item->id) selected @endif>
                                                                                                                            {{ $item->name }}
                                                                                                                        </option>
                                                                                                                    @endforeach
                                                                                                                </select>
                                                                                                            </div>
                                                                                                            <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                                                                                                    <label>Weft Insertion</label>
                                                                                                                    <select class="form-control" name="weft_insertion[]">
                                                                                                                        <option value="Free Fly">Free Fly</option>
                                                                                                                        <option value="Guide">Guide</option> 
                                                                                                                    </select>
                                                                                                            </div>
                                                                                                            <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                                                                                                <label>Width</label>
                                                                                                                <!-- <input type="text" class="form-control" name="width[]"> -->
                                                                                                                <select class="form-control" name="width[]">
                                                                                                                    <option value="">Width</option>
                                                                                                                    @foreach ($width as $item)
                                                                                                                        <option value="{{ $item->width }}">
                                                                                                                            {{ $item->width }}
                                                                                                                        </option>
                                                                                                                    @endforeach
                                                                                                                </select>
                                                                                                            </div>
                                                                                                            <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                                                                                                <label>Color</label>
                                                                                                                <!-- <input type="text" class="form-control" name="color[]"> -->
                                                                                                                 <select class="form-control" name="color[]">
                                                                                                                    <option value="">Color</option>
                                                                                                                    @foreach ($color as $item)
                                                                                                                        <option value="{{ $item->color }}">
                                                                                                                            {{ $item->color }}
                                                                                                                        </option>
                                                                                                                    @endforeach
                                                                                                                </select>
                                                                                                            </div>
                                                                                                            <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                                                                                                <label>Shadding</label>
                                                                                                                <!-- <input type="text" class="form-control" name="shadding[]"> -->
                                                                                                                 <select class="form-control" name="shadding[]">
                                                                                                                    <option value="">Shadding</option>
                                                                                                                    @foreach ($shadding as $item)
                                                                                                                        <option value="{{ $item->name }}">
                                                                                                                            {{ $item->name }}
                                                                                                                        </option>
                                                                                                                    @endforeach
                                                                                                                </select>
                                                                                                            </div>
                                                                                                            <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12 extra-shadding" style="display:none;">
                                                                                                                <label>Shadding Type</label>
                                                                                                                <select class="form-control extra-shadding-select" name="extra_shadding[]">
                                                                                                                    <option value="">Shadding Type</option>
                                                                                                                    @foreach ($shadding_options as $item)
                                                                                                                        <option value="{{ $item->name }}">
                                                                                                                            {{ $item->name }}
                                                                                                                        </option>
                                                                                                                    @endforeach
                                                                                                                </select>
                                                                                                            </div> 
                                                                                                        </div>
                                                                                                        <br>
                                                                                                            <div class="row mb-1">
                                                                                                                <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                                                                                                    <label>Fact. No.</label>
                                                                                                                    <input type="text" class="form-control" name="order_no[]">
                                                                                                                </div>
                                                                                                                <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                                                                                                    <label>Serial No.</label>
                                                                                                                    <input type="text" class="form-control" name="serial_no[]">
                                                                                                                </div>
                                                                                                                <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                                                                                                    <label>Installation Status</label>
                                                                                                                    <select class="form-control" name="status[]">
                                                                                                                        <option value="1">Pending</option>
                                                                                                                        <option value="2">In progress</option>
                                                                                                                        <option value="3">Completed</option>  
                                                                                                                    </select>
                                                                                                                </div>
                                                                                                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 d-flex gap-2 align-items-end">
                                                                                                                    <a href="#" id="addmachines" class="btn btn-primary w-50">
                                                                                                                        Add Machines
                                                                                                                    </a>
                                                                                                                    <button type="button" class="btn btn-danger w-50 removeMachine">
                                                                                                                        Remove
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                    </div>
                                                                                            </div>       
                                                                            </ul>                
                                                                        </div>
                                                                    </div>    
                                                            </div> 
                                                        </div> 
                                                    </div> 
                                                </div> 
                                            </div> 
                                        </div>    
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-2">
                                        <div class="row justify-content-end mt-3">
                                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 mb-2">
                                                <input type="submit" class="btn btn-primary w-100" value="Submit">
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
    </div>

@endSection
@section('scripts')
<script type="text/javascript">
$(document).ready(function () {
    // $('#status_id').select2();
    // $('.status-select').select2({
    // width: '100%'
    // });
    updateSerialNumbers();
    $('#MachineSales-form').validate({
                rules: {
                    party_id: {
                        required: true
                    },
                    party_firm_id: {
                        required: true,
                    },       
                },
                messages: {
                    party_id: {
                        required: "Please select the Party"
                    },
                    party_firm_id: {
                        required: "Please select the Firm"
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

            $('#party_id').on('change', function () {
            var partyId = $(this).val();

            if (partyId === '') {
                $('#firm_id').html('<option value="">Choose a Firm</option>');
                return;
            }

    $.ajax({
        type: "GET",
        url: "{{ route('party-firms') }}",
        data: {
            id: partyId
        },
        success: function (response) {
            var html = '<option value="">Choose a Firm</option>';
            if (response.firms.length > 0) {
                $.each(response.firms, function (index, firm) {
                    html += '<option value="' + firm.id + '">' + firm.firm_name + '</option>';
                });
            }
            $('#firm_id').html(html);
        },
        error: function () {
            alert('Failed to load firms');
        }
        });
    });

   $(document).on('click', '#addmachines', function (e) {
    e.preventDefault();

    let $wrapper = $('#machinesWrapper');
    let $lastRow = $wrapper.find('.machine-row:last');
    let $newRow  = $lastRow.clone(false);

    // Remove Select2 DOM artifacts
    $newRow.find('.select2-container').remove();
    $newRow.find('select').show();

    // Copy input values
    $lastRow.find('input').each(function (index) {
        $newRow.find('input').eq(index).val($(this).val());
    });

    // Copy select values
    $lastRow.find('select').each(function (index) {
        $newRow.find('select').eq(index).val($(this).val());
    });

    // Reset extra shadding in cloned row
    //$newRow.find('.extra-shadding').hide();
    //$newRow.find('.extra-shadding-select').val('');

    // Ensure remove button is visible in cloned rows
    $newRow.find('.removeMachine').show();

    $wrapper.append($newRow);

    updateSerialNumbers();

    // Hide remove button in first row always
    $('#machinesWrapper .machine-row:first .removeMachine').hide();

    // Reinitialize Select2 if used
    $newRow.find('.select2').select2({ width: '100%' });
    }); 

    // Remove firm row
    $(document).on('click', '.removeMachine', function () {
    $(this).closest('.machine-row').remove();

    updateSerialNumbers();    
    // Always keep first row remove button hidden
    $('#machinesWrapper .machine-row:first .removeMachine').hide();
    });
});
$(document).on('change', 'select[name="shadding[]"]', function () {

    let selected = $(this).val();
    let $row = $(this).closest('.machine-row');
    let $extraBox = $row.find('.extra-shadding');

    if (selected === 'J/Q') {
        $extraBox.show();
    } else {
        $extraBox.hide();
        $extraBox.find('select').val('');
    }
});

function updateSerialNumbers() {
    $('#machinesWrapper .machine-row').each(function (index) {
        $(this).find('.sr-no').val(index + 1);
    });
}
</script>
    


@endSection

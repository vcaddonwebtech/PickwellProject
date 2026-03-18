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
                                <form action="{{ route('visits.update', ['machinesale' => $machinesale, 'visit' => $visit_id]) }}" method="POST" id="machine-sales-form" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="main_machine_type" value="{{ $main_machine_type }}">
                                    <input type="hidden" name="machinesale" value="{{ $machinesale }}">
                                    <input type="hidden" name="party_id" value="{{ $party_id }}">
                                    <input type="hidden" name="product_id" value="{{ $product_id }}">
                                    <input type="hidden" name="visit_id" value="{{ $visit_id }}">
                                    <div class="row mb-1">
                                        <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                            <label for="inputOrder" class="col-form-label">Visit Title </label> <i class="text-danger">*</i>
                                            <input type="text" id="inputtitle" class="form-control" name="title" value="{{ $visitdata->title }}" />
                                        </div>
                                    </div>
                                    <div class=" row mb-1">
                                        
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="input-Date" class="form-label">Start Date</label> 
                                            <input type="text" class="form-control" placeholder="yyyy-mm-dd" id="date" name="date" value="{{ $visitdata->date }}">
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputExpiry" class="col-form-label">End Date</label> 
                                            <input type="text" class="form-control" placeholder="yyyy-mm-dd" id="end_date" name="end_date" value="{{ $visitdata->end_date }}">
                                        </div>
                                        <!-- <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputM/C" class="col-form-label">M/c No.</label> <i
                                                class="text-danger">*</i>
                                            <input type="text" id="inputM/C" class="form-control" name="mc_no" value="" />
                                        </div> -->   
                                    </div>
                                    <div class="row mb-1">
                                        <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                            <label for="inputOrder" class="col-form-label">Remark </label>
                                            <input type="text" id="inputOrder" class="form-control" name="visit_remark" value="{{ $visitdata->visit_remark }}" />
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <div id="multiCollapseExample1">
                                                            <label for="inputParty" class="col-form-label">Engineer</label> <i class="text-danger">*</i>
                                                            <select class="form-control" name="engineer_id" id="engineer_id">
                                                                <option value="">Choose a Engineer</option>
                                                                @foreach ($engineers as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        @if ($visitdata->engineer_id == $item->id) selected @endif>
                                                                        {{ $item->name . ' ( ' . $item->pending_complaints . ' ) ' }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <div id="multiCollapseExample1">
                                                            <label for="inputJointengg" class="col-form-label">Joint Engineer</label>
                                                            <select class="form-control" name="jointengg[]" id="jointengg" multiple>
                                                                <option value="">Choose a Option</option>
                                                                 @foreach ($engineers as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        {{ in_array($item->id, $jointengg ?? []) ? 'selected' : '' }}>
                                                                        {{ $item->name }} ( {{ $item->pending_complaints }} )
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputStatus" class="col-form-label">Status</label> 
                                            <select class="form-control" name="status" id="status">
                                                <option value="">Choose a Status</option>
                                                @foreach (App\Models\Status::all() as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if ($item->id == $visitdata->status) selected @endif>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <div id="multiCollapseExample1">
                                                            <label for="inputstepdone" class="col-form-label">Work Done</label>
                                                            <select class="form-control" name="stepsdone[]" id="stepsdone" multiple>
                                                                <option value="">Choose a Option</option>
                                                                 @foreach (App\Models\VisitSteps::all() as $item)
                                                                    <option value="{{ $item->id }}"
                                                                    {{ in_array($item->id, $visitdonesteps ?? []) ? 'selected' : '' }}>
                                                                    {{ $item->work }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-2">
                                        <div class="row justify-content-end mt-3">
                                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-2 ">
                                                <button class="btn btn-outline-light w-100" type="reset"
                                                    onclick="location.reload()">Reset</button>
                                            </div>

                                            <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-2 ">
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
        $(document).ready(function() {

            $('#jointengg').select2({
                placeholder: 'Select an option'
            });
            $('#engineer_id').select2({
                placeholder: 'Select an option'
            });
            $('#status').select2({
                placeholder: 'Select an option'
            });
             $('#stepsdone').select2({
                placeholder: 'Select an option'
            });

            $('#MachineSales-form').validate({
                rules: {
                    title: {
                        required: true,
                        maxlength: 3
                    },
                    engineer_id: {
                        required: true,
                        // date: true
                    }
                },
                messages: {
                    title: {
                        required: "Please enter the visit title"
                    },
                    engineer_id: {
                        required: "Please select an engineer",
                        // date: "Please enter a valid date"
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

            $('#date').datepicker({
                dateFormat: "yy-mm-dd",
                minDate: 0
            });

            $('#end_date').datepicker({
                dateFormat: "yy-mm-dd",
                minDate: 0
            });
        });
    </script>
@endSection

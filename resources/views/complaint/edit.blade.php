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
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('machineservicedata', ['main_machine_type' => $main_machine_type]) }}">{{ $main_machine_name }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>

        </div>
        <!-- Start:: row-1 -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            {{ $title }} [Compliant No : {{ $complaint->complaint_no }}]
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
                                <form action="{{ route('complaints.update', ['complaint' => $complaint]) }}" method="POST"
                                    id="complaint-form">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                    <input type="hidden" name="firm_id" value="{{ App\Models\Firm::first()->id }}">
                                    <input type="hidden" name="year_id" value="{{ App\Models\Year::first()->id }}">
                                    <input type="hidden" name="product_id" id="product_id" value="{{ $complaint->product_id }}">
                                    <input type="hidden" name="main_machine_type" value="{{ $main_machine_type }}">

                                    <div class="row mb-1">
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="input-Date" class="col-form-label">Date</label>
                                            <input type="text" class="form-control " placeholder="dd-mm-yyyy"
                                                name="date" id="date"
                                                value="{{ $complaint->date ? date('d-m-Y', strtotime($complaint->date)) : old('date') }}">
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputBill" class="col-form-label">Time</label>
                                            <input type="time" name="time"
                                                value="{{ $complaint->time ?? date('H:i') }}" class="form-control">
                                        </div>

                                    </div>

                                    <div class="row mb-1">
                                        <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                            <label for="inputSale" class="col-form-label">Party Name</label> <i
                                                class="text-danger">*</i>
                                            {{-- <input type="text" id="inputSale" class="form-control" /> --}}
                                            <select class="form-control" name="party_id" id="party_id">
                                                <option value="">Choose a Option</option>
                                                @foreach (App\Models\Party::all() as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if ($complaint->party_id == $item->id) selected @endif>
                                                        {{ $item->name . ' ( ' . $item->address . ' )' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                            <label for="inputComplain" class="col-form-label">Product</label> <i
                                                class="text-danger">*</i>
                                            <select type="text" id="sales_entry_id" name="sales_entry_id"
                                                class="form-control">
                                                <option value="">Choose a Option</option>
                                                @foreach ($sales_entries as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $item->id == $complaint->sales_entry_id ? 'selected' : '' }}>
                                                        {{ $item->product->name . ' - ' . $item->serial_no . ' - ' . $item->mc_no }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        {{-- <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="inputProduct" class="col-form-label">Product </label>
                                        <select class="form-control" name="product_id" id="product_id">
                                            <option value="">Choose a Product</option>
                                            @foreach (App\Models\Product::all() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}
                                        <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                            <label for="inputParty" class="col-form-label">Complaint Type</label> <i
                                                class="text-danger">*</i>
                                            <select class="form-control" name="complaint_type_id" id="complaint_type_id">
                                                <option value="">Choose a Option</option>
                                                @foreach (App\Models\ComplaintType::all() as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if ($complaint->complaint_type_id == $item->id) selected @endif>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-1">
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputParty" class="col-form-label">Service Type</label> <i
                                                class="text-danger">*</i>
                                            <select class="form-control" name="service_type_id" id="service_type_id">
                                                <option value="">Choose a Service Type</option>
                                                @foreach (App\Models\ServiceType::all() as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if ($complaint->service_type_id == $item->id) selected @endif>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputStatus" class="col-form-label">Status</label> <i
                                                class="text-danger">*</i>
                                            <select class="form-control" name="status_id" id="status_id">
                                                <option value="">Choose a Product</option>
                                                @foreach (App\Models\Status::all() as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if ($complaint->status_id == $item->id) selected @endif>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="row mb-1">
                                        <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                            <label for="inputRemark" class="col-form-label">Remark</label>
                                            <textarea type="text" id="remark" class="form-control" name="remarks" aria-describedby="nameHelpInline">{{ $complaint->remarks }}</textarea>
                                        </div>

                                    </div>

                                    <div class="collapse-wpr my-3">
                                        {{-- <div
                                        class=" p-3 header-secondary row client-name-wpr bg-primary-transparent d-flex align-items-center gap-1">
                                        <div class="form-check form-check-md form-switch">
                                            <input class="form-check-input" type="checkbox" id="switch-md"
                                                role="checkbox" data-bs-toggle="collapse" href="#multiCollapseExample1"
                                                aria-expanded="false" aria-controls="multiCollapseExample1"
                                                name="is_assigned">
                                            <h5 class="px-4 mb-0" for="switch-md">Engineer Select</h5>
                                        </div>
                                    </div> --}}

                                        <div class="row my-3">
                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="row">
                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-1">
                                                        <div id="multiCollapseExample1">
                                                            <label for="inputParty"
                                                                class="col-form-label">Engineer</label>
                                                            <select class="form-control" name="engineer_id"
                                                                id="engineer_id">
                                                                <option value="">Choose a Engineer</option>
                                                                 @foreach ($engineers as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        @if ($complaint->engineer_id == $item->id) selected @endif>
                                                                        {{ $item->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                     {{-- <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-1">
                                                        <div id="multiCollapseExample1">
                                                            <label for="inputDate" class="col-form-label">Engineer In
                                                                Date</label>
                                                            <input type="text" class="form-control "
                                                                placeholder="dd-mm-yyyy" name="engineer_in_date"
                                                                id="engineer_in_date"
                                                                value="{{ $complaint->engineer_in_date ? date('d-m-Y', strtotime($complaint->engineer_in_date)) : old('engineer_in_date') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-1">
                                                        <div id="multiCollapseExample1">
                                                            <label for="inputDate" class="col-form-label">Engineer Out
                                                                Date</label>
                                                            <input type="text" class="form-control "
                                                                placeholder="dd-mm-yyyy" name="engineer_out_date"
                                                                id="engineer_out_date"
                                                                value="{{ $complaint->engineer_out_date ? date('d-m-Y', strtotime($complaint->engineer_out_date)) : old('engineer_out_date') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-1">
                                                        <div id="multiCollapseExample1">
                                                            <label class="col-form-label">Engineer In Address</label>
                                                            <textarea type="text" class="form-control form-control-textarea" readonly> {{ $complaint->engineer_in_address }} </textarea>
                                                        </div>
                                                    </div> --}}


                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-1">
                                                        <div id="multiCollapseExample1">
                                                            <label for="inputJointengg" class="col-form-label">Joint
                                                                Engineer</label>
                                                            <select class="form-control" name="jointengg[]"
                                                                id="jointengg" multiple>
                                                                <option value="">Choose a Option</option>
                                                                 @foreach ($engineers as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        @if (isset($complaint->jointengg) && in_array($item->id, explode(',', $complaint->jointengg))) selected @endif>
                                                                        {{ $item->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="col-lg-6 col-md-6 col-sm-12">
                                                <div class="row">
                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-1">
                                                        <div id="multiCollapseExample1">
                                                            <label for="inputJointengg" class="col-form-label">Actual
                                                                Complain</label>
                                                            <select class="form-control" name="engineer_complaint_id"
                                                                id="engineer_complaint_id">
                                                                <option value="">Choose a Option</option>
                                                                @foreach (App\Models\ComplaintType::all() as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        @if ($complaint->engineer_complaint_id == $item->id) selected @endif>
                                                                        {{ $item->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{-- <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-1">
                                                        <div id="multiCollapseExample1">
                                                            <label for="inputshort" class="col-form-label">Engineer In
                                                                Time</label>
                                                            <input type="time" class="form-control"
                                                                placeholder="hh:mm" name="engineer_in_time"
                                                                id="engineer_in_time"
                                                                value="{{ $complaint->engineer_in_time }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-1">
                                                        <div id="multiCollapseExample1">
                                                            <label for="inputshort" class="col-form-label">Engineer Out
                                                                Time</label>
                                                            <input type="time" class="form-control"
                                                                id="engineer_out_time" name="engineer_out_time"
                                                                value="{{ $complaint->engineer_out_time }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-1">
                                                        <div id="multiCollapseExample1">
                                                            <label class="col-form-label">Engineer Out Address</label>
                                                            <textarea type="text" class="form-control form-control-textarea" readonly> {{ $complaint->engineer_out_address }} </textarea>
                                                        </div>
                                                    </div> --}}

                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-1">
                                                        <div class="p-3 d-flex align-items-center gap-1">
                                                            <div
                                                                class="form-check form-check-md form-switch d-flex align-items-center">
                                                                <input type="checkbox" name="is_urgent" id="is_urgent"
                                                                    class="form-check-input"
                                                                    value="{{ $complaint->is_urgent }}"
                                                                    @if ($complaint->is_urgent == 1) checked @endif
                                                                    role="switch">
                                                                <label class="px-2 mb-0 col-form-label" for="switch-md">Is
                                                                    Urgent</label>
                                                            </div>
                                                            @if (isset($complaint->engineer_in_date) && $complaint->engineer_in_date != '')
                                                                @php $is_free_service = 'disabled'; @endphp
                                                            @else
                                                                @php $is_free_service = '' @endphp
                                                            @endif
                                                            <!-- <div
                                                                class="form-check form-check-md form-switch d-flex align-items-center">
                                                                <input type="checkbox" name="is_free_service"
                                                                    id="is_free_service" class="form-check-input"
                                                                    value="{{ $complaint->is_free_service }}"
                                                                    @if ($complaint->is_free_service == 1) checked @endif
                                                                    role="switch" {{ $is_free_service }}>
                                                                <label class="px-2 mb-0 col-form-label" for="switch-md">Is
                                                                    Free Service</label>
                                                            </div> -->
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                        <div class="row my-3">
                                            @if (!empty($complaint->image))
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <div id="multiCollapseExample1">
                                                        <label class="col-form-label">Image</label>
                                                        <a href="javascript:void(0)" id="imageClick"><img
                                                                src="{{ asset('complaint/images/' . $complaint->image) }}"
                                                                style="max-width: 100px; max-height: 100px;"></a>
                                                    </div>
                                                </div>
                                            @endif
                                            @if (!empty($complaint->video))
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <div id="multiCollapseExample1">
                                                        <label class="col-form-label">Video</label>
                                                        <video width="320" height="240" controls>
                                                            <source
                                                                src="{{ asset('complaint/videos/' . $complaint->video) }}"
                                                                type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    </div>
                                                </div>
                                            @endif
                                            @if (!empty($complaint->audio))
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <div id="multiCollapseExample1">
                                                        <label class="col-form-label">Audio</label>
                                                        <audio controls>
                                                            <source
                                                                src="{{ asset('complaint/audios/' . $complaint->audio) }}"
                                                                type="audio/mpeg">
                                                            Your browser does not support the audio element.
                                                        </audio>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <!-- ======submit=button==== -->
                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-2 justify-content-end">
                                                <div class="row justify-content-end mt-3">
                                                    @if ($complaint->status_id == 3 && $complaint->is_reassign == 0 && (Auth::user()->phone_no == "9913750000" || Auth::user()->phone_no == "1111111111"))
                                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-2">
                                                            <a href="javascript:void(0);" 
                                                               class="btn btn-success w-100" 
                                                               onclick="return reassignComplaint({{ $complaint->id }});">
                                                               Reassign
                                                            </a>
                                                        </div>
                                                    @endif
                                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-2 ">
                                                        <button class="btn btn-outline-light w-100">Reset</button>
                                                    </div>
                                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-2 ">
                                                        <button class="btn btn-primary w-100">Submit</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-xl-6 col-md-6 col-sm-12">
                                <div class="party_details">
                                    <h5>Party Details</h5>
                                    <p> Name : {{ !empty($complaint->party) ? $complaint->party->name : '' }}</p>
                                    <p> Address : {{ !empty($complaint->party) ? $complaint->party->address : '' }}</p>
                                    <p> Mobile No : {{ !empty($complaint->party) ? $complaint->party->phone_no : '' }}</p>
                                    <p> City : {{ !empty($complaint->party->city) ? $complaint->party->city->name : '' }}</p>
                                    <p> Area : {{ !empty($complaint->party->area) ? $complaint->party->area->name : '' }}</p>
                                    <p> Contact Person : {{ !empty($complaint->party->contactPerson) ? $complaint->party->contactPerson->name : '' }}</p>
                                    <p> Owner : {{ !empty($complaint->party->owner) ? $complaint->party->owner->name : '' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End:: row-1 -->
    </div>
@endSection

<div class="modal fade" id="imageDisplay" aria-hidden="true" aria-labelledby="editAreaTitle" tabindex="-1"
    data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog-centered modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAreaTitle">Complaint Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 modal-img-box">
                    <img src="{{ asset('complaint/images/' . $complaint->image) }}">
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')


    <script type="text/javascript">
        $(document).ready(function() {
            $('#date').datepicker({
                dateFormat: "dd-mm-yy" // Change format to d/m/Y (day/month/year)
            });

            $('#engineer_in_date').datepicker({
                dateFormat: "dd-mm-yy" // Change format to d/m/Y (day/month/year)
            });

            $('#engineer_out_date').datepicker({
                dateFormat: "dd-mm-yy" // Change format to d/m/Y (day/month/year)
            });
            // $("#switch-md").on("change", function() {
            //         if(this.checked || this.value == 'on') {
            //             this.setAttribute("checked", true);
            //             this.setAttribute("value", 1);
            //         }
            //         else{
            //             this.setAttribute("checked", false);
            //             this.setAttribute("value", 0);
            //         }
            //     });
            // var is_assigned = '{{ $complaint->is_assigned ?? 0 }}';
            // if(is_assigned != 0){
            //     $("#switch-md").trigger("click");
            // }
            // var engineer_id = '{{ $complaint->engineer_id ?? 0 }}';
            // if (engineer_id != 0) {
            //     $("#switch-md").trigger("click");
            // }
            $('#party_id').on('change', function() {
                var party = $(this).val();
                var $products = $('#sales_entry_id');

                $.ajax({
                    type: "GET",
                    url: "{{ route('party-products') }}",
                    data: {
                        'id': party
                    },
                    success: function(data) {
                        console.log(data.length, data);
                        $products.empty();
                        $products.append('<option selected disabled>Select Product</option>');
                        if (data.partyProducts.length > 0) {
                            $.each(data.partyProducts, function(key, value) {
                                $products.append('<option value="' + value
                                    .id + '">' + value.product.name + '- ' + value
                                    .serial_no + '- ' + value.mc_no + '</option>');
                            })
                        }

                        var html = '<h5>Party Details</h5>';
                        html += '<p> Name: ' + data.party.name + '</p>';
                        html += '<p> Mobile No: ' + data.party.phone_no + '</p>';
                        html += '<p> Email: ' + data.party.email + '</p>';
                        html += '<p> Address: ' + data.party.address + '</p>';
                        html += '<p> City: ' + data.party.city.name + '</p>';
                        html += '<p> Area: ' + data.party.area.name + '</p>';
                        html += '<p> Contact Person: ' + data.party.contact_person.name + ' (' +
                            data.party.owner.phone_no + ')' + '</p>';
                        html += '<p> Owner: ' + data.party.owner.name + ' (' + data.party.owner
                            .phone_no + ')' + '</p>';
                        $('.party_details').empty();
                        $('.party_details').html(html);
                        $('#sales_entry_id').select2({
                            placeholder: 'Select an option'
                        });
                    }
                })

            });

            $('#complaint_type_id').on('change', function() {
                $('#engineer_complaint_id').val($(this).val()).trigger('change');
            })

            $('#sales_entry_id').select2({
                placeholder: 'Select an option'
            });
            $('#party_id').select2({
                placeholder: 'Select an option'
            });
            // $('#product_id').select2({
            //     placeholder: 'Select an option'
            // });
            $('#complaint_type_id').select2({
                placeholder: 'Select an option'
            });
            $('#service_type_id').select2({
                placeholder: 'Select an option'
            });
            $('#status_id').select2({
                placeholder: 'Select an option'
            });
            $('#engineer_id').select2({
                placeholder: 'Select an option'
            });
            $('#joint_engineer_id').select2({
                placeholder: 'Select an option'
            });
            $('#engineer_complaint_id').select2({
                placeholder: 'Select an option'
            });
            $('#jointengg').select2({
                placeholder: 'Select an option'
            });

            $('#is_urgent').on('change', function() {
                if ($(this).prop('checked')) {
                    this.value = 1; // Set the value to 1 if checked
                } else {
                    this.value = 0; // Set the value to 0 if unchecked
                }
            })

            $('#is_free_service').on('change', function() {
                if ($(this).prop('checked')) {
                    this.value = 1; // Set the value to 1 if checked
                } else {
                    this.value = 0; // Set the value to 0 if unchecked
                }
            })

            $('#engineer_in_date').on('change', function() {
                const currentTime = new Date();
                const hours = currentTime.getHours();
                const minutes = currentTime.getMinutes();
                const formattedTime =
                    `${hours < 10 ? '0' : ''}${hours}:${minutes < 10 ? '0' : ''}${minutes}`;
                $('#engineer_in_time').val(formattedTime);
                $('#engineer_in_time').trigger('change');

            })
            $('#engineer_out_date').on('change', function() {
                const currentTime = new Date();
                const hours = currentTime.getHours();
                const minutes = currentTime.getMinutes();
                const formattedTime =
                    `${hours < 10 ? '0' : ''}${hours}:${minutes < 10 ? '0' : ''}${minutes}`;
                $('#engineer_out_time').val(formattedTime);
                $('#engineer_out_time').trigger('change');
            })

            $('#imageClick').on('click', function() {
                $('#imageDisplay').modal('show');
            })

            $("#complaint-form").validate({
                rules: {
                    date: {
                        required: true,
                    },
                    time: {
                        required: true,
                    },
                    sales_entry_id: {
                        required: true
                    },
                    party_id: {
                        required: true
                    },
                    // product_id: {
                    //     required: true
                    // },
                    complaint_type_id: {
                        required: true
                    },
                    service_type_id: {
                        required: true
                    },
                    status_id: {
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
                    sales_entry_id: {
                        required: "Please select a complain number"
                    },
                    party_id: {
                        required: "Please select a party name"
                    },
                    product_id: {
                        required: "Please select a product"
                    },
                    complaint_type_id: {
                        required: "Please select a complaint type"
                    },
                    service_type_id: {
                        required: "Please select a service type"
                    },
                    status_id: {
                        required: "Please select a status"
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

            window.reassignComplaint = function(complaint) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "Could you please reassign this?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, reassign it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "GET",
                            url: "{{ route('reassign-complaint', ':id') }}".replace(
                                ':id', complaint),
                            data: {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function(data) {
                                if (data.success == 0) {
                                    Swal.fire({
                                        title: "Can't Reassign!",
                                        text: data.message,
                                        icon: "error"
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Reassign!",
                                        text: data.message,
                                        icon: "success",
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = "{{ route('complaints.index', ['main_machine_type' => $main_machine_type]) }}";
                                        }
                                    });
                                }
                            },
                            error: function(data) {
                                Swal.fire({
                                    title: "Can't Reassign!",
                                    text: data.responseJSON.message,
                                    icon: "error"
                                });
                            }
                        });

                    }
                });
            }
        });
    </script>


@endSection

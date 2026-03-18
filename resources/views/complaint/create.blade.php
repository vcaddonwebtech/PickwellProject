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
                            {{ $title }} [Compliant No : {{ $complaint_no }}]
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
                                <form action="{{ route('complaints.store') }}" method="POST" id="complaint-form">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                    <input type="hidden" name="firm_id" value="{{ App\Models\Firm::first()->id }}">
                                    <input type="hidden" name="year_id" value="{{ App\Models\Year::first()->id }}">
                                    <input type="hidden" name="complaint_no" value="{{ $complaint_no }}">
                                    <input type="hidden" name="product_id" id="product_id">
                                    <input type="hidden" name="main_machine_type" value="{{ $main_machine_type }}" id="main_machine_type">
                                    <div class="row mb-1">
                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="input-Date" class="col-form-label">Date</label> <i
                                                class="text-danger">*</i>
                                            <input type="text" class="form-control" placeholder="dd-mm-yyyy"
                                                id="date" name="date" value="{{ old('date') }}">
                                        </div>

                                        <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                            <label for="inputBill" class="col-form-label">Time</label>
                                            <input type="time" name="time" value="{{ date('H:i') }}"
                                                class="form-control">
                                        </div>

                                    </div>

                                    <div class="row mb-1">
                                        <div class="form-group col-xl-2 col-lg-2 col-md-2 col-sm-12">
                                            <label for="input-Date" class="col-form-label">Code</label>
                                            <i class="text-danger">*</i>
                                            <input type="text" name="party_code" id="party_code" class="form-control"
                                                placeholder="Party Code">
                                        </div>
                                        <div class="form-group col-xl-10 col-lg-10 col-md-10 col-sm-12">
                                            <label for="inputSale" class="col-form-label">Party Name</label>
                                            <i class="text-danger">*</i>
                                            {{-- <input type="text" id="inputSale" class="form-control" /> --}}
                                            <select class="form-control" name="party_id" id="party_id">
                                                <option value="">Choose a Option</option>
                                                @foreach ($parties as $item)
                                                    <option value="{{ $item->id }}">
                                                        {{ $item->name . '(' . $item->address . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                        <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                            <label for="inputComplain" class="col-form-label">Product</label> <i
                                                class="text-danger">*</i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <!-- <small class="text-danger">Note : Sales M/c expired than not show product.</small> -->
                                            <select type="text" id="sales_entry_id" name="sales_entry_id" class="form-control">
                                                <option value="">Choose a Option</option>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="row mb-1">
                                            <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                            <label for="inputParty" class="col-form-label"><b>Complaint Type</b></label> <i
                                                class="text-danger">*</i>
                                            <select class="form-control" name="complaint_type_id" id="complaint_type_id">
                                                <option value="">Choose a Option</option>
                                               @foreach ($complaint_types as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
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
                                                        {{ $loop->first ? 'selected' : '' }}>{{ $item->name }}
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
                                                        @if ($item->name == 'pending') selected @endif>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="row mb-1">
                                        <div class="form-group col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                            <label for="inputRemark" class="col-form-label">Remark</label>
                                            <textarea type="text" id="remark" class="form-control" name="remarks" aria-describedby="nameHelpInline"> {{ old('remarks') }}</textarea>
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
                                                                        @if (old('engineer_id') == $item->id) selected @endif>
                                                                        {{ $item->name . ' ( ' . $item->pending_complaints . ' ) ' }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                  {{--  <!-- <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-1">
                                                        <div id="multiCollapseExample1">
                                                            <label for="inputDate" class="col-form-label">Engineer In
                                                                Date</label>
                                                            <input type="text" class="form-control"
                                                                placeholder="dd-mm-yyyy" id="engineer_in_date"
                                                                name="engineer_in_date"
                                                                value="{{ old('engineer_in_date') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-1">
                                                        <div id="multiCollapseExample1">
                                                            <label for="inputDate" class="col-form-label">Engineer Out
                                                                Date</label>
                                                            <input type="text" class="form-control"
                                                                placeholder="dd-mm-yyyy" id="engineer_out_date"
                                                                name="engineer_out_date"
                                                                value="{{ old('engineer_out_date') }}">
                                                        </div>
                                                    </div> --> --}}
                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-1">
                                                        <div id="multiCollapseExample1">
                                                            <label for="inputJointengg" class="col-form-label">Actual
                                                                Complain</label>
                                                            <select class="form-control" name="engineer_complaint_id"
                                                                id="engineer_complaint_id">
                                                                <option value="">Choose a Option</option>
                                                                @foreach (App\Models\ComplaintType::all() as $item)
                                                                    <option value="{{ $item->id }}">
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
                                                            <label for="inputJointengg" class="col-form-label">Joint
                                                                Engineer</label>
                                                            <select class="form-control" name="jointengg[]"
                                                                id="jointengg" multiple>
                                                                <option value="">Choose a Option</option>
                                                                 @foreach ($engineers as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        @if (old('engineer_id') == $item->id) selected @endif>
                                                                        {{ $item->name . ' ( ' . $item->pending_complaints . ' ) ' }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    {{--  <!-- <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-1">
                                                        <div id="multiCollapseExample1">
                                                            <label for="inputshort" class="col-form-label">Engineer In
                                                                Time</label>
                                                            <input type="time" class="form-control"
                                                                placeholder="hh:mm" name="engineer_in_time"
                                                                id="engineer_in_time" value="">
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-1">
                                                        <div id="multiCollapseExample1">
                                                            <label for="inputshort" class="col-form-label">Engineer out
                                                                Time</label>
                                                            <input type="time" class="form-control"
                                                                id="engineer_out_time" name="engineer_out_time"
                                                                value="{{ old('engineer_out_time') }}">
                                                        </div>
                                                    </div> --> --}}

                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-1">
                                                        <div class="p-3 mt-3 d-flex align-items-center gap-1">
                                                            <div
                                                                class="form-check form-check-md form-switch d-flex align-items-center">
                                                                <input type="checkbox" name="is_urgent" id="is_urgent"
                                                                    class="form-check-input" value="1"
                                                                    role="switch">
                                                                <label class="px-2 mb-0 col-form-label" for="switch-md">Is
                                                                    Urgent</label>
                                                            </div>
                                                            <!-- <div
                                                                class="form-check form-check-md form-switch d-flex align-items-center">
                                                                <input type="checkbox" name="is_free_service"
                                                                    id="is_free_service" class="form-check-input"
                                                                    value="0" role="switch">
                                                                <label class="px-2 mb-0 col-form-label"
                                                                    for="switch-md">Is Free Service</label>
                                                            </div> --> 
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <!-- ======submit=button==== -->
                                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-2 justify-content-end">
                                                <div class="row justify-content-end mt-3">
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
                                <div class="party_details"></div>
                                <button onclick="resetMap()" style="top:10px; right:10px; z-index:5; padding:8px 12px;">Reset Zoom</button><div id="map" style="width:98%; height:625px; z-index: 2;"></div>
                                
                            </div>    

<script>
    let map;

    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            zoom: 12,
            center: { lat: 21.2382997, lng: 72.8882355 },
        });

        const engineers = @json($all_present_engineers);

        const grouped = {};
        engineers.forEach(e => {
            const key = `${e.lat},${e.lng}`;
            if (!grouped[key]) grouped[key] = [];
            grouped[key].push(e);
        });

        const markers = [];
        const openInfoWindows = new Map(); // To track each marker’s info window state

        Object.entries(grouped).forEach(([key, group]) => {
            const [lat, lng] = key.split(',').map(parseFloat);

            const marker = new google.maps.Marker({
                position: { lat, lng },
                map,
                title: `${group.length} engineer(s)`
            });

            const content = `
                <div style="max-width:220px;">
                    ${group.map(e => `
                        <div style="margin-bottom:10px; text-align:center;">
                            <img src="https://pickwell.addonwebtech.com/atdselfie/${e.in_selfie}" style="width:55px; height:55px; border-radius:50%; object-fit:cover;"><br>
                            <small>${e.engineer_name}</small>
                        </div>
                    `).join('')}
                </div>
            `;

            const infowindow = new google.maps.InfoWindow({ content });

            // Open on load
            infowindow.open(map, marker);
            openInfoWindows.set(marker, true); // Mark it as open

            // Toggle on marker click
            marker.addListener("click", () => {
                const isOpen = openInfoWindows.get(marker);
                if (isOpen) {
                    infowindow.close();
                    openInfoWindows.set(marker, false);
                } else {
                    infowindow.open(map, marker);
                    openInfoWindows.set(marker, true);
                }
            });

            markers.push(marker);
        });

        const clusterer = new markerClusterer.MarkerClusterer({ map, markers });

        clusterer.addListener('clusterclick', (event) => {
            const cluster = event.cluster;
            const position = cluster.position;
            const currentZoom = map.getZoom();
            map.panTo(position);
            map.setZoom(Math.min(currentZoom + 2, 19));
        });
    }

    function resetMap() {
        map.setZoom(11);
        map.setCenter({ lat: 21.2382997, lng: 72.8882355 });
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDu9lahjAIj7IbilbNEAr76hjgUtcq8AM4&loading=async&libraries=maps&v=weekly&callback=initMap" defer>
</script>
<script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>            

                                
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
            // $("#switch-md").on("change", function() {
            //     if(this.checked || this.value == 'on') {
            //         this.setAttribute("checked", true);
            //         this.setAttribute("value", 1);
            //     }
            //     else{
            //         this.setAttribute("checked", false);
            //         this.setAttribute("value", 0);
            //     }
            // });
            // $('#sales_entry_id').select2({
            //     placeholder: 'Select an option'
            // });
            $('#party_id').select2({
                placeholder: 'Select an option'
            });
            $('#jointengg').select2({
                placeholder: 'Select an option'
            });
            $('#complaint_type_id').select2({
                placeholder: 'Select an option'
            });
            $('#service_type_id').select2({
                placeholder: 'Select an option'
            });
            $('#status_id').select2({
                placeholder: 'Select an option'
            });
            $('#sales_entry_id').select2({
                placeholder: 'Select an option'
            });
            $('#machine_type_id').select2({
                placeholder: 'Select an option'
            });
            $('#engineer_id').select2({
                placeholder: 'Select an option'
            })

            $('#engineer_complaint_id').select2({
                placeholder: 'Select an option'
            });

            $('#joint_engineer_id').select2({
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

            $('#complaint_type_id').on('change', function() {
                $('#engineer_complaint_id').val($(this).val()).trigger('change');
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
                            $('#party_id').val(data.party.id).trigger('change');
                        } else {
                            $('#party_id').val('').trigger('change');
                            $('#party_code').val('');
                        }
                    }
                })
            });

            $('#party_id').on('change', function() {
                var party = $(this).val();
                var $products = $('#sales_entry_id');
                var main_machine_type = $('#main_machine_type').val();
                

                $.ajax({
                    type: "GET",
                    url: "{{ route('party-products') }}",
                    data: {
                        'id': party,
                        'main_machine_type': main_machine_type
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.party) {
                            $('#party_code').val(data.party.code);
                        }

                        $products.empty();
                        $products.append('<option selected disabled>Select Product</option>');
                        if (data.partyProducts.length > 0) {
                            var today = new Date();
                            var currentDate = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0') + '-' + String(today.getDate()).padStart(2, '0');
                            $.each(data.partyProducts, function(key, value) {
                                // Machine is disabled when expired
                                var isMachineExpired = '';
                                var isMachineExpiredMsg = '';
                                if (currentDate > value.service_expiry_date) {
                                    var isMachineExpired = '';
                                    var isMachineExpiredMsg = '(Free Service is expired)';
                                }
                                $products.append('<option value="' + value
                                    .id + '" ' + isMachineExpired + ' > ' + value
                                    .product.name + '- ' + value
                                    .serial_no + '- ' + value.mc_no + ' ' +
                                    isMachineExpiredMsg + '</option>');
                            })
                        }
                        $('#sales_entry_id').select2({
                            placeholder: 'Select an option'
                        });

                        var html = '<h5>Party Details</h5>';
                        html += '<p> Name : ' + data.party.name + '</p>';
                        html += '<p> Mobile No : ' + data.party.phone_no + '</p>';
                        html += '<p> Email : ' + data.party.email + '</p>';
                        html += '<p> Address : ' + data.party.address + '</p>';
                        html += '<p> City : ' + data.party.city.name + '</p>';
                        html += '<p> Area : ' + data.party.area.name + '</p>';
                        html += '<p> Contact Person : ' + data.party.contact_person + ' (' +
                            data.party.phone_no + ')' + '</p>';
                        html += '<p> Owner : ' + data.party.owner_name + ' (' + data.party.phone_no + ')' + '</p>';
                        
                        // Check and append the complaint details if available
                        // if (data.party.complaints[0].date && data.party.complaints[0].complaint_no) {
                        //     // Extract the date in yyyy-mm-dd format
                        //     var originalDate = data.party.complaints[0].date;

                        //     // Convert to dd-mm-yyyy format
                        //     var dateParts = originalDate.split('-'); // Split the date by '-'
                        //     var formattedDate = dateParts[2] + '-' + dateParts[1] + '-' + dateParts[0]; // Rearrange to dd-mm-yyyy

                        //     html += '<p> Last Complaint Date : <b>' + formattedDate + '</b></p>';
                        //     html += '<p> Last Complaint No : <b>' + data.party.complaints[0].complaint_no + '</b></p>';
                        // }
                        $('.party_details').html(html);
                        $('#sales_entry_id').select2({
                            placeholder: 'Select an option'
                        });
                    }
                })

            });
            <?php if($main_machine_type == 1) { ?>
            $('#sales_entry_id').on('change', function() {
                var sales_entry = $(this).val();
                var $machine_type = $('#machine_type_id');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: "{{ route('getmachine-type') }}",
                    data: {
                        'id': sales_entry
                    },
                    success: function(data) {
                        // if (data.party) {
                        //     $('#party_id').val(data.party.id).trigger('change');
                        // } else {
                        //     $('#party_id').val('').trigger('change');
                        //     $('#party_code').val('');
                        // }
                        //$('#machine_type_id').val(data.id);
                        // console.log(data);
                      
                        

                        $machine_type.empty();
                        //$machine_type.append('<option selected disabled>Select Machine Type ' + data.id +'</option>');
                        $machine_type.append('<option selected value="' + data.id +'">' + data.name +  '</option>');
                        $('#machine_type_id').select2({
                            placeholder: 'Select an option'
                        });
                        $('#machine_type_id').val(data.id).trigger('change');
                    }
                })
            })

            $('#machine_type_id').on('change', function() {
                var machine_type = $(this).val();
                var $complaint_type = $('#complaint_type_id');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: "{{ route('getcomplaint-types', ['main_machine_type' => $main_machine_type]) }}",
                    data: {
                        'mid': machine_type
                    },
                    success: function(data) {
                        //$('#machine_type_id').val(data.id);
                        //console.log(data);
                        if (data.id) {
                            $('#machine_type_id').val(data.id);
                        }

                        $complaint_type.empty();
                        //$machine_type.append('<option selected disabled>Select Machine Type ' + data.id +'</option>');
                        //$machine_type.append('<option selected value="' + data.id +'">' + data.name +  '</option>');
                        $complaint_type.append('<option disabled>Select Complaint Type</option>');
                        if (data.length > 0) {
                             $.each(data, function(key, value) {
                                $complaint_type.append('<option value="' + value.id + '">' + value.name +  '</option>');
                            })
                        }
                        $('#complaint_type_id').select2({
                            placeholder: 'Select an option'
                        });
                    }
                })
            })
            <?php } ?>
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
                const outTime = new Date();
                const hours = outTime.getHours();
                const minutes = outTime.getMinutes();
                const formattedOutTime =
                    `${hours < 10 ? '0' : ''}${hours}:${minutes < 10 ? '0' : ''}${minutes}`;
                $('#engineer_out_time').val(formattedOutTime);
                $('#engineer_out_time').trigger('change');
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

            $('#date').datepicker({
                dateFormat: "dd-mm-yy", // Change format to d-m-Y (day-month-year)
            });

            $('#engineer_in_date').datepicker({
                dateFormat: "dd-mm-yy" // Change format to d-m-Y (day-month-year)
            });

            $('#engineer_out_date').datepicker({
                dateFormat: "dd-mm-yy" // Change format to d-m-Y (day-month-year)
            });

        });
    </script>


@endSection

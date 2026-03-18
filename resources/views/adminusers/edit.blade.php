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
                    {{-- @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li class="text-danger">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif --}}
                    <form action="{{ route('adminusers.update', ['user' => $user]) }}" method="POST" id="party-form" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body gy-4">
                            <div class=" row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="userName" class="col-form-label">User Name</label> <i
                                        class="text-danger">*</i>
                                    <input type="text" id="userName" class="form-control" name="name" value="{{ $user->name ?? old('name') }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="userEmail" class="col-form-label">User Email</label>
                                    <input type="email" id="userEmail" class="form-control" name="email" value="{{ $user->email ?? old('email') }}" autocomplete="off">
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="role" class="col-form-label">Role</label> <i class="text-danger">*</i>
                                    <select class="form-control" id="role" name="roles">
                                        <option value="">Choose a Option</option>
                                        @foreach (Spatie\Permission\Models\Role::all() as $item)
                                            <option value="{{ $item->name }}"
                                                {{ $user->hasRole($item->name) ? 'selected' : '' }}>{{ $item->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <?php if(auth()->user()->getRoleNames()->first() == "Admin" || auth()->user()->getRoleNames()->first() == "Payroll Manager") { ?>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="input-textarea" class="col-form-label">Department</label> <i class="text-danger">*</i>
                                    <select class="form-control" id="department" name="deparment_id">
                                        <option value="">Choose a Option</option>
                                        @foreach (App\Models\Department::all() as $item)
                                            <option value="{{ $item->id }}" @if ($user->deparment_id == $item->id) selected @endif>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <?php } ?>
                            </div>
                            <div class="form-group row mb-2">
                                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <label for="inputPhone" class="col-form-label">Mobile No</label><i
                                        class="text-danger">*</i>
                                    <input type="phone_no" id="inputPhone" class="form-control" name="phone_no" value="{{ $user->phone_no ?? old('phone_no') }}">
                                    @error('phone_no')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>    
                            <div class="form-group row mb-2"> 
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="input-textarea" class="col-form-label">Machine Type</label>
                                    <select class="form-control" id="main_machine_type" name="main_machine_type[]" multiple>
                                        @foreach ($all_machine_data as $item)
                                            <option value="{{ $item->id }}" @if (isset($main_machine_type) && in_array($item->id, $main_machine_type['main_machine_type'])) selected @endif>{{ $item->machine_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputdoj" class="col-form-label">Date of Joining</label>
                                    <input type="text" class="form-control" placeholder="yyyy-mm-dd" id="inputdoj" name="doj" value="{{ $user->doj ?? old('phone_no') }}">
                                    
                                </div>
                            </div>

                            <div class="row">
                                <!-- <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="duty_start" class="col-form-label">In Time</label>
                                    <input type="time" id="duty_start" class="form-control" name="duty_start" value="{{ $user->duty_start ?? '09:00:00' }}">
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="duty_end" class="col-form-label">Out Time</label>
                                    <input type="time" id="duty_end" class="form-control" name="duty_end" value="{{ $user->duty_end ?? '17:00:00' }}">
                                </div> -->
                                <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <label for="input-textarea" class="col-form-label">Shift</label>
                                        <select class="form-control" id="shift_id" name="shift_id">
                                            <option value="">Choose a Shift</option>
                                            @foreach (App\Models\Shift::where('is_active', 1)->get() as $item)
                                                <option value="{{ $item->id }}" @if ($item->id == $user->shift_id) selected @endif>{{ $item->title }} - {{ $item->shift_start }} - {{$item->shift_end }}</option>
                                            @endforeach
                                        </select>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <span> <i class="fa fa-info-circle" aria-hidden="true" style="color:blue"
                                        data-bs-placement="top"
                                        title="Leave blank if you don't want to change password"></i> </span>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="password" class="col-form-label">Password</label>
                                    <input type="password" id="password" class="form-control" name="password"
                                        autocomplete="off">
                                </div>

                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="confirmPassword" class="col-form-label">Confirm Password</label>
                                    <input type="password" id="confirmPassword" class="form-control"
                                        name="confirm_password" autocomplete="off">
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputProfile" class="col-form-label">Profile</label>
                                    <input type="file" id="inputProfile" class="form-control" name="profile" onchange="previewImage(event)">

                                    <!-- Display validation error -->
                                    @error('profile')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                    <!-- Small image preview -->
                                    <div class="mt-2">
                                        <img id="profilePreview"
                                            src="{{ $user->profile ? asset('user_dp/' . $user->profile) : '' }}"
                                            alt="Profile Preview"
                                            style="max-width: 100px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;"
                                            onclick="showLargeImage(this)">
                                    </div>
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                    <label for="input-textarea" class="col-form-label">Leaders</label>
                                    <select class="form-control" id="leader_id" name="leader_id">
                                        <option value="">Choose a Option</option>
                                        @foreach (App\Models\User::where('is_leader', 1)->get() as $item)
                                            <option value="{{ $item->id }}" @if ($user->leader_id == $item->id) selected @endif>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mb-1">
                                        <div class="p-3 mt-3 d-flex align-items-center gap-1">
                                            <div class="form-check form-check-md form-switch d-flex align-items-center">
                                                <input type="checkbox" name="is_active" id="is_active"
                                                    class="form-check-input" value="{{ $user->is_active }}"
                                                    @if ($user->is_active == 1) checked @endif
                                                    role="switch">
                                                <label class="px-2 mb-0 col-form-label" for="switch-md">Is Active</label>
                                            </div>
                                            <div class="form-check form-check-md form-switch d-flex align-items-center" style="margin-left: 50px;">
                                                <input type="checkbox" name="is_leader"
                                                    id="is_leader" class="form-check-input"
                                                    value="{{ $user->is_leader }}"
                                                    @if ($user->is_leader == 1) checked @endif role="switch">
                                                <label class="px-2 mb-0 col-form-label"
                                                    for="switch-md">Is Leader</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-header justify-content-between">
                                <div class="card-title">
                                    User Payroll Detials
                                </div>
                            </div>
                            <div class=" row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputbasic_sal" class="col-form-label"> Basic Monthly Salary</label> 
                                    <input type="text" id="inputbasic_sal" class="form-control" name="basic_sal" value="{{ $userPayroll->basic_sal ?? old('name') }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputhra" class="col-form-label"> Monthly HRA</label>
                                    <input type="text" id="inputhra" class="form-control" name="hra" value="{{ $userPayroll->hra ?? old('name') }}">
                                </div>
                            </div>
                            <div class=" row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputda" class="col-form-label"> Monthly DA</label> 
                                    <input type="text" id="inputda" class="form-control" name="da" value="{{ $userPayroll->da ?? old('name') }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputpt" class="col-form-label"> Monthly PT</label>
                                    <input type="text" id="inputpt" class="form-control" name="pt" value="{{ $userPayroll->pt ?? old('name') }}">
                                </div>
                            </div>
                            <div class=" row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputmsc_allow" class="col-form-label"> Monthly Misc Allowance</label> 
                                    <input type="text" id="inputmsc_allow" class="form-control" name="msc_allow" value="{{ $userPayroll->msc_allow ?? old('name') }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputptrl_allow" class="col-form-label"> Monthly Petrol Allowance</label>
                                    <input type="text" id="inputptrl_allow" class="form-control" name="ptrl_allow" value="{{ $userPayroll->ptrl_allow ?? old('name') }}">
                                </div>
                            </div>   
                            <div class=" row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputaadharno" class="col-form-label">Aadhar Number</label> 
                                    <input type="text" id="inputaadharno" class="form-control" name="aadharno" value="{{ $userPayroll->aadharno ?? old('aadharno') }}">
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputpanno" class="col-form-label">PAN Number</label>
                                    <input type="text" id="inputpanno" class="form-control" name="panno" value="{{ $userPayroll->panno ?? old('panno') }}">
                                </div>
                            </div>           
                            <div class="row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputaadhar" class="col-form-label">Aadhar Card</label>
                                        <input type="file" id="inputaadhar" class="form-control" name="aadhar_card" onchange="previewImage(event)">
                                        @error('aadhar_card')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                        <!-- Small image preview -->
                                    <div class="mt-2">
                                        <img id="aadharPreview"
                                            src="{{ $user->aadhar_card ? asset('user_dp/' . $user->aadhar_card) : '' }}"
                                            alt="aadhar Preview"
                                            style="max-width: 100px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;"
                                            onclick="showLargeImage(this)">
                                    </div>
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                    <label for="inputpan" class="col-form-label">Pan Card</label>
                                        <input type="file" id="inputpan" class="form-control" name="pan_card" onchange="previewImage(event)">
                                        @error('pan_card')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror

                                        <!-- Small image preview -->
                                    <div class="mt-2">
                                        <img id="panPreview"
                                            src="{{ $user->pan_card ? asset('user_dp/' . $user->pan_card) : '' }}"
                                            alt="Pan Preview"
                                            style="max-width: 100px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer;"
                                            onclick="showLargeImage(this)">
                                    </div>
                                </div>
                            </div> 
                            <div class="card-header justify-content-between" style="margin-top:15px;">
                                <div class="card-title">
                                    User Bank Detials
                                </div>
                            </div>
                            <div class=" row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputbank_name" class="col-form-label"> Bank Name</label> 
                                    <input type="text" id="inputbank_name" class="form-control" name="bank_name" value="{{ $userPayroll->bank_name ?? old('bank_name') }}">
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputifsc" class="col-form-label"> IFSC Code</label>
                                    <input type="text" id="inputifsc" class="form-control" name="ifsc" value="{{ $userPayroll->ifsc ?? old('ifsc') }}">
                                </div>
                            </div>
                            <div class=" row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputahn" class="col-form-label"> Account Holder Name</label> 
                                    <input type="text" id="inputahn" class="form-control" name="ahname" value="{{ $userPayroll->ahname ?? old('ahname') }}">
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputano" class="col-form-label"> Account Number</label>
                                    <input type="text" id="inputano" class="form-control" name="account_no" value="{{ $userPayroll->account_no ?? old('account_no') }}">
                                </div>
                            </div>
                            <div class=" row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputupi" class="col-form-label"> UPI Id</label>
                                    <input type="text" id="inputupi" class="form-control" name="upi_id" value="{{ $userPayroll->upi_id ?? old('upi_id') }}">
                                </div>
                            </div>   
                            <div class="row mb-2">
                                <div class="col-xl-6 col-lg-6 col-md-8 col-sm-12 mb-2">
                                    <div class="row justify-content-end mt-3">
                                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 mb-2 ">
                                            <input type="reset" class="btn btn-outline-light w-100">
                                            {{-- <button class="btn btn-outline-light w-100">Reset</button> --}}
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
        <!-- Modal for Large Image Preview -->
        <div id="imageModal" class="modal" tabindex="-1" role="dialog" style="display: none;">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <img id="modalImage" src="" alt="Large Preview" style="width: 100%; height: auto;">
                    </div>
                </div>
            </div>
        </div>
        <!-- End:: row-1 -->

    </div>
@endSection

@section('scripts')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <script>
        // JavaScript function to handle image preview
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('profilePreview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            var selectedRoleValue = $("#role").val();
            if (selectedRoleValue == 'Sales') {
                $(".hideengineer").show();
            } else {
                $(".hideengineer").hide();
            }
            $("#role").change(function() {
                var selectedRoleValue = $(this).val();
                if (selectedRoleValue == 'Sales') {
                    $(".hideengineer").show();
                } else {
                    $(".hideengineer").hide();
                }
            });
            $('#role').select2({
                placeholder: "Select Role",
            });
            $('#department').select2({
                placeholder: "Select Department",
            });
            $('#leader_id').select2({
                placeholder: "Select Role",
            });
            $('#engineer').select2();
            // $('#user-form').validate({
            //     rules: {
            //         name: {
            //             required: true,
            //             minlength: 3
            //         },
            //         email: {
            //             required: true,
            //             email: true
            //         },
            //     },
            //     errorElement: 'div',
            //     errorPlacement: function(error, element) {
            //         error.addClass('invalid-feedback');
            //         element.closest('.form-group').append(error);
            //     },
            //     highlight: function(element, errorClass, validClass) {
            //         $(element).addClass('is-invalid');
            //     },
            //     unhighlight: function(element, errorClass, validClass) {
            //         $(element).removeClass('is-invalid');
            //     }
            // });
            $('#is_active').on('change', function() {
                if ($(this).prop('checked')) {
                    this.value = 1; // Set the value to 1 if checked
                } else {
                    this.value = 0; // Set the value to 0 if unchecked
                }
            });

            $('#is_leader').on('change', function() {
                if ($(this).prop('checked')) {
                    this.value = 1; // Set the value to 1 if checked
                } else {
                    this.value = 0; // Set the value to 0 if unchecked
                }
            });

            $('#main_machine_type').select2({
                placeholder: 'Select an option'
            });


            // $('#is_salse_emb').on('change', function() {
            //     if ($(this).prop('checked')) {
            //         this.value = 1; // Set the value to 1 if checked
            //     } else {
            //         this.value = 0; // Set the value to 0 if unchecked
            //     }
            // });

            // $('#is_salse_cir').on('change', function() {
            //     if ($(this).prop('checked')) {
            //         this.value = 1; // Set the value to 1 if checked
            //     } else {
            //         this.value = 0; // Set the value to 0 if unchecked
            //     }
            // });
        });

        // Function to preview the selected image
        function previewImage(event) {
            const preview = document.getElementById('profilePreview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function() {
                    preview.src = reader.result; // Set the small preview to the uploaded image
                };
                reader.readAsDataURL(file);
            }
        }

        // Function to show a large image in the modal
        function showLargeImage(img) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');

            // Ensure the modal's image source is updated immediately
            modalImage.src = img.src;

            // Delay the modal display slightly to ensure the source is updated
            setTimeout(() => {
                modal.style.display = 'block'; // Show the modal
            }, 50);
        }

        // Close the modal when clicking anywhere on it
        document.getElementById('imageModal').onclick = function() {
            this.style.display = 'none';
        };
    </script>
    <style>
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .modal img {
            border: 1px solid #fff;
            border-radius: 4px;
        }
    </style>

@endSection

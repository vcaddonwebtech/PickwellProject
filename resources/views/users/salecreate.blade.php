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
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('machinesalesdata', ['main_machine_type' => $user_main_machine_type]) }}">{{ $main_machine_name }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('salesusers', ['main_machine_type' => $user_main_machine_type]) }}">Users</a></li>
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
                    <form action="{{ route('users.store') }}" method="POST" id="party-form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="user_main_machine_type" value="{{ $user_main_machine_type }}">
                        <input type="hidden" name="is_saleuser" value="1">
                        <div class="card-body gy-4">
                            <div class=" row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputName" class="col-form-label"> Name</label> <i class="text-danger">*</i>
                                    <input type="text" id="inputName" class="form-control" name="name"
                                        value="{{ old('name') }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputName" class="col-form-label"> Email</label>
                                    <input type="email" id="inputName" class="form-control" name="email"
                                        value="{{ old('email') }}">
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <?php if(auth()->user()->getRoleNames()->first() == "Manager") { ?>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="input-textarea" class="col-form-label">Role</label> <i
                                        class="text-danger">*</i>
                                    <select class="form-control" id="role" name="roles">
                                        <option value="">Choose a Option</option>
                                       @foreach ($specificRoles as $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('roles')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <?php } ?>
                                <?php if(auth()->user()->getRoleNames()->first() == "Admin") { ?>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="input-textarea" class="col-form-label">Role</label> <i
                                        class="text-danger">*</i>
                                    <select class="form-control" id="role" name="roles">
                                        <option value="">Choose a Option</option>
                                        @foreach (Spatie\Permission\Models\Role::all() as $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('roles')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <?php } ?>
                                <?php if(auth()->user()->getRoleNames()->first() == "Service Team Leader") { ?>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="input-textarea" class="col-form-label">Role</label> <i
                                        class="text-danger">*</i>
                                    <select class="form-control" id="role" name="roles">
                                        <option value="">Choose a Option</option>
                                       @foreach ($specificRoles as $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('roles')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <?php } ?>
                                <?php if(auth()->user()->getRoleNames()->first() == "Sales Team Leader") { ?>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="input-textarea" class="col-form-label">Role</label> <i
                                        class="text-danger">*</i>
                                    <select class="form-control" id="role" name="roles">
                                        <option value="">Choose a Option</option>
                                       @foreach ($specificRoles as $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('roles')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <?php } ?>
                                <?php if(auth()->user()->getRoleNames()->first() == "Team Leader") { ?>
                                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="input-textarea" class="col-form-label">Role</label> <i
                                        class="text-danger">*</i>
                                    <select class="form-control" id="role" name="roles">
                                        <option value="">Choose a Option</option>
                                       @foreach ($specificRoles as $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('roles')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <?php } ?>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputName" class="col-form-label">Mobile No</label><i
                                        class="text-danger">*</i>
                                    <input type="phone_no" id="inputPhone" class="form-control" name="phone_no"
                                        value="{{ old('phone_no') }}">
                                    @error('phone_no')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <div id="multiCollapseExample1">
                                    <label for="main_machine_type" class="col-form-label">Machine Type</label>
                                        <select class="form-control" id="main_machine_type" name="main_machine_type[]" multiple>
                                            <option value="1">TEXTALK</option>
                                            <option value="2">ZETA</option>
                                            <option value="3">RARE</option>
                                            <option value="4">BEADS</option>
                                            <option value="5">INK</option>
                                        </select>
                                    </div>    
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputdoj" class="col-form-label">Date of Joining</label>
                                    <input type="text" class="form-control" placeholder="yyyy-mm-dd" id="inputdoj" name="doj" value="{{ old('doj') }}">
                                    
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputName" class="col-form-label">In Time</label>
                                    <input type="time" id="duty_start" class="form-control" name="duty_start" value="{{ old('duty_start') ?? '10:00:00' }}">
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputName" class="col-form-label">Out Time</label>
                                    <input type="time" id="duty_end" class="form-control" name="duty_end" value="{{ old('duty_end') ?? '19:00:00' }}">
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputName" class="col-form-label">Password</label>
                                    <input type="password" id="password" class="form-control" name="password">
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputName" class="col-form-label">Confirm Password</label>
                                    <input type="password" id="confirm_password" class="form-control"
                                        name="confirm_password">
                                    @error('confirm_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputProfile" class="col-form-label">Profile</label>
                                    <input type="file" id="inputProfile" class="form-control" name="profile">
                                    @error('profile')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                    <label for="input-textarea" class="col-form-label">Leaders</label>
                                    <select class="form-control" id="leader_id" name="leader_id">
                                        <option value="">Choose a Option</option>
                                        @foreach (App\Models\User::where('is_leader', 1)->where('is_active', 1)->get() as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
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
                                                    class="form-check-input" value="1"
                                                    role="switch">
                                                <label class="px-2 mb-0 col-form-label" for="switch-md">Is Active</label>
                                            </div>
                                            <div class="form-check form-check-md form-switch d-flex align-items-center" style="margin-left: 50px;">
                                                <input type="checkbox" name="is_leader"
                                                    id="is_leader" class="form-check-input"
                                                    value="0" role="switch">
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
                                    <input type="text" id="inputbasic_sal" class="form-control" name="basic_sal" value="{{ old('basic_sal') }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputhra" class="col-form-label"> Monthly HRA</label>
                                    <input type="text" id="inputhra" class="form-control" name="hra" value="{{ old('hra') }}">
                                </div>
                            </div>
                            <div class=" row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputda" class="col-form-label"> Monthly DA</label> 
                                    <input type="text" id="inputda" class="form-control" name="da" value="{{ old('da') }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputpt" class="col-form-label"> Monthly PT</label>
                                    <input type="text" id="inputpt" class="form-control" name="pt" value="{{ old('pt') }}">
                                </div>
                            </div>
                            <div class=" row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputmsc_allow" class="col-form-label"> Monthly Misc Allowance</label> 
                                    <input type="text" id="inputmsc_allow" class="form-control" name="msc_allow" value="{{ old('msc_allow') }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputptrl_allow" class="col-form-label"> Monthly Petrol Allowance</label>
                                    <input type="text" id="inputptrl_allow" class="form-control" name="ptrl_allow" value="{{ old('ptrl_allow') }}">
                                </div>
                            </div>
                            <div class=" row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputaadharno" class="col-form-label">Aadhar Number</label> 
                                    <input type="text" id="inputaadharno" class="form-control" name="aadharno" value="{{ old('aadharno') }}">
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputpanno" class="col-form-label">PAN Number</label>
                                    <input type="text" id="inputpanno" class="form-control" name="panno" value="{{ old('panno') }}">
                                </div>
                            </div>           
                            <div class="row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputaadhar" class="col-form-label">Aadhar Card</label>
                                        <input type="file" id="inputaadhar" class="form-control" name="aadhar_card">     
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                    <label for="inputpan" class="col-form-label">Pan Card</label>
                                        <input type="file" id="inputpan" class="form-control" name="pan_card">
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
                                    <input type="text" id="inputbank_name" class="form-control" name="bank_name" value="{{ old('bank_name') }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputifsc" class="col-form-label"> IFSC Code</label>
                                    <input type="text" id="inputifsc" class="form-control" name="ifsc" value="{{ old('ifsc') }}">
                                </div>
                            </div>
                            <div class=" row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputahn" class="col-form-label"> Account Holder Name</label> 
                                    <input type="text" id="inputahn" class="form-control" name="ahname" value="{{ old('ahname') }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputano" class="col-form-label"> Account Number</label>
                                    <input type="text" id="inputano" class="form-control" name="account_no" value="{{ old('account_no') }}">
                                </div>
                            </div>
                            <div class=" row mb-2">
                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputupi" class="col-form-label"> UPI Id</label>
                                    <input type="text" id="inputupi" class="form-control" name="upi_id" value="{{ old('upi_id') }}">
                                </div>
                            </div>
                            <div class="row mb-2">
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
            $('#leader_id').select2({
                placeholder: "Select Leader",
            });
            $('#engineer').select2();

            $('#user-form').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    password_confirmation: {
                        required: true,
                        minlength: 6
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

            $('#inputPhone').on('change', function() {
                var phone = $(this).val();
                $('#password').val(phone);
                $('#confirm_password').val(phone);
            });

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

            $('#inputdoj').datepicker({
                dateFormat: "yy-mm-dd", // Change format to d-m-Y (day-month-year)
                onSelect: function(selectedDate) {
                    // When a date is selected, update the minDate for install_date
                    //var selectedDateObj = $(this).datepicker('getDate');
                    $('#inputdoj').val(selectedDate);
                }
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
    </script>


@endSection

@include('layouts.guest')

<div class="row authentication mx-0 justify-content-center align-items-center" >
    <div class="col-xxl-7  col-xl-7 col-lg-12">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-xxl-6 col-xl-7 col-lg-7 col-md-7 col-sm-8 col-12">
                <div class="p-5">
                    <div class="mb-3">
                        <a href="{{ route('home') }}">
                            <img src="{{asset('images/brand-logos/desktop-logo.png')}}" alt=""
                                class="authentication-brand desktop-logo">
                            <img src="{{asset('images/brand-logos/desktop-white.png')}}" alt=""
                                class="authentication-brand desktop-dark">
                        </a>
                    </div>
                    <p class="h5 fw-semibold mb-2">Sign In</p>
                    <p>Please enter OTP sent to mobile no:{{ $mobile }}</p>
                    <div class="row gy-3">
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <input type="hidden" name="phone_no" class="form-control form-control-lg" id="phone_no" value="{{ $mobile }}">
                            <div class="col-xl-12 mt-0">
                                <label for="otp" class="form-label text-default">OTP</label>
                                <input 
                                    type="text"
                                    id="otp"
                                    name="otp"
                                    maxlength="6"
                                    pattern="\d{6}"
                                    required
                                    class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="Enter 6-digit OTP"
                                >
                            </div>
                            <div class="mt-2">
                                <div class="col-xl-12 mt-0">
                                    <button type="submit" class="btn btn-primary">Sign In</button>
                                </div>
                                <p class="text-center text-sm text-gray-500 mt-4">
                                    Didn't receive the code?
                                    <a href="{{ route('resendotp', ['mobile' => $mobile]) }}" class="text-blue-600 hover:underline">Resend OTP</a>
                                </p>
                            </div>    
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @endsection --}}
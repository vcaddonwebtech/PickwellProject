@include('layouts.guest')

<div class="row authentication mx-0 justify-content-center align-items-center" >

    
    <div class="col-xxl-7  col-xl-7 col-lg-12">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-xxl-6 col-xl-7 col-lg-7 col-md-7 col-sm-8 col-12">
                <div class="p-5">
                    <div class="mb-3">
                        <a href="{{route('home')}}}">
                            <img src="{{asset('images/brand-logos/desktop-logo.png')}}" alt=""
                                class="authentication-brand desktop-logo">
                            <img src="{{asset('images/brand-logos/desktop-white.png')}}" alt=""
                                class="authentication-brand desktop-dark">
                        </a>
                    </div>
                    <p class="h5 fw-semibold mb-2">Sign In</p>
                    <div class="row gy-3">
                        <form action="{{ route('loginwithotp') }}" method="POST">
                            @csrf
                            <div class="col-xl-12 mt-0">
                                <label for="phone_no" class="form-label text-default">Mobile No</label>
                                <input required type="text" name="phone_no" class="form-control form-control-lg" id="phone_no" value="{{ old('phone_no') }}">
                            </div>
                            <div class="mt-2">
                                <div class="col-xl-12 mt-0">
                                    <button type="submit" class="btn btn-primary">Sign In</button>
                                </div>
                            </div>    
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
{{-- @endsection --}}
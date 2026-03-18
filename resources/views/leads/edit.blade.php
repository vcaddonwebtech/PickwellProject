@extends($layout)
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
                @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li class="text-danger">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form action="{{ route('updateswork', ['lead' => $lead]) }}" method="POST" id="lead-form">
                    @csrf
                    <div class="card-body gy-4">
                        <div class=" row mb-2">
                            <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                <label for="inputName" class="col-form-label">Party Name</label> <i class="text-danger">*</i>
                                <input type="text" id="partyname" class="form-control" name="partyname" value="{{ $lead->partyname ?? old('partyname') }}" required>
                            </div>
                            <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                <label for="inputName" class="col-form-label">Date of Lead</label> <i class="text-danger">*</i>
                                <input type="datetime-local" id="date_of_lead" class="form-control" name="date_of_lead"
                                    value="{{ $lead->date_of_lead ?? old('date_of_lead') }}" required>
                            </div>
                        </div>
                        <div class="row mb-2" >

                            <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                <label for="inputName" class="col-form-label">Product</label> <i
                                    class="text-danger">*</i>
                                <select name="product_id" id="product_id" class="form-control">
                                    <option value="">Select</option>
                                    @foreach (App\Models\Product::all() as $key => $product)
                                    <option value="{{ $product->id }}" {{ 
                                        $lead->product_id ?? old('product_id')==$product->id ? 'selected' : '' }}>
                                        {{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                <label for="inputName" class="col-form-label">Status</label> <i
                                    class="text-danger">*</i>
                                <select name="status" id="status" class="form-control">
                                    <option value="pending" {{old('status') == 'pending' ? 'selected' : ''}}> Pending</option>
                                    <option value="open" {{old('status') == 'open' ? 'selected' : ''}}> Open</option>
                                    <option value="closed" {{old('status') == 'closed' ? 'selected' : ''}}> Closed</option>

                                </select>
                            </div>
                        </div>

                        <div class=" row mb-2">
                            <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                <label for="inputName" class="col-form-label">Type of Lead</label> <i
                                    class="text-danger">*</i>
                                <select name="type_of_lead" id="type_of_lead" class="form-control">
                                    <option value="telephone" {{old('type_of_lead') == 'telephone' ? 'selected' : ''}}> Telephonic</option>
                                    <option value="email" {{old('type_of_lead') == 'email' ? 'selected' : ''}}> Email</option>
                                    <option value="reference" {{old('type_of_lead') == 'reference' ? 'selected' : ''}}> Reference</option>

                                </select>
                            </div>
                            <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                <label for="inputName" class="col-form-label">Date of Meeting</label> <i class="text-danger">*</i>
                                <input type="datetime-local" id="date_of_meeting" class="form-control" name="date_of_meeting" value="{{ old('date_of_meeting') }}"
                                    required>
                            </div>
                        </div>
                        <div class="row mb-2">
                            
                            <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                <label for="inputName" class="col-form-label">Source</label> <i class="text-danger">*</i>
                                <input type="text" id="source" class="form-control" name="source" value="{{ old('source') }}"
                                    required>
                            </div>
                        </div>
                            <div class="row mb-2">

                                <div class="form-group col-xl-3 col-lg-3 col-md-4 col-sm-12">
                                    <label for="inputName" class="col-form-label">Schedule A Meeting </label>
                                    <input type="checkbox" id="schedule_meeting" class="form-check-input" name="schedule_meeting" value="1" checked>
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
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- End:: row-1 -->

</div>
@endsection

@section('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

            $('#engineer-form').validate({
                rules: {
                    partyname: {
                        required: true,
                    },
                    product_id: {
                        required: true,
                    },
                    type_of_lead: {
                        required: true,
                    },
                    schedule_meeting: {
                        required: true,
                    },
                    date_of_lead: {
                        required: true
                    },
                    status:{
                        required:true
                    }

                },
                messages: {
                    partyname: {
                        required: "Please enter name",
                    },
                    product_id: {
                        required: "Please select product group",
                    },
                    type_of_lead: {
                        required: "Please enter hsn code",
                    },
                    schedule_meeting: {
                        required: "Please enter gst",
                    }
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
@endsection
<header class="app-header">

    <!-- Start::main-header-container -->
    <div class="main-header-container container-fluid">

        <!-- Start::header-content-left -->
        <div class="header-content-left">

            <!-- Start::header-element -->
            {{-- <div class="header-element">
                <div class="horizontal-logo">
                    <a href="{{ route('dashboard') }}" class="header-logo">
                        <img src="{{ asset('images/brand-logos/desktop-logo.png') }}" alt="logo" class="" style="height: 60px;">
                        <img src="{{ asset('images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
                        <img src="{{ asset('images/brand-logos/desktop-white.png') }}" alt="logo"
                            class="desktop-dark">
                        <img src="{{ asset('images/brand-logos/toggle-dark.png') }}" alt="logo" class="toggle-dark">
                        <img src="{{ asset('images/brand-logos/desktop-white.png') }}" alt="logo"
                            class="desktop-white">
                        <img src="{{ asset('images/brand-logos/toggle-white.png') }}" alt="logo"
                            class="toggle-white">
                    </a>
                </div>
            </div> --}}
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element">
                <!-- Start::header-link -->
                <a aria-label="Hide Sidebar"
                    class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle mx-0"
                    data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->
            <!-- Start::header-element -->
            <div class="header-element header-search">
                <!-- Start::header-link -->
                {{-- <div class="main-header-search ms-3 d-none d-lg-block my-auto">
                    <input class="form-control" placeholder="Search..." type="search">
                    <button class="btn"><i class="fe fe-search"></i></button>
                </div> --}}
                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->
        </div>
        <!-- End::header-content-left -->

        <!-- Start::header-content-right -->
        <div class="header-content-right">
            <div class="header-element header-search  d-lg-none d-block">
                <!-- Start::header-link -->
                <a href="javascript:void(0);" class="header-link search-icon" id="searchButton">
                    <i class="bx bx-search-alt-2 header-link-icon"></i>
                </a>
                <!-- End::header-link -->
                <li class="nav-link ">
                    <form class="navbar-form" role="search" id="myNavbarForm">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search">
                            <span class="input-group-btn">
                                <button type="reset" class="btn btn-default close-btn text-muted">
                                    <i class="fa fa-times"></i>
                                </button>
                            </span>
                        </div>
                    </form>
                </li>
            </div>

            <!-- Start::header-element -->
            <div class="header-element header-theme-mode">
                <!-- Start::header-link|layout-setting -->
                <a href="javascript:void(0);" class="header-link layout-setting">
                    <span class="light-layout">
                        <!-- Start::header-link-icon -->
                        <i class="bx bx-moon  fe-moon header-link-icon"></i>
                        <!-- End::header-link-icon -->
                    </span>
                    <span class="dark-layout">
                        <!-- Start::header-link-icon -->
                        <i class="bx bx-sun header-link-icon"></i>
                        <!-- End::header-link-icon -->
                    </span>
                </a>
                <!-- End::header-link|layout-setting -->
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element header-fullscreen">
                <!-- Start::header-link -->
                <a onclick="openFullscreen();" href="javascript:void(0);" class="header-link">
                    <i class="bx bx-fullscreen full-screen-open header-link-icon"></i>
                    <i class="bx bx-exit-fullscreen full-screen-close header-link-icon d-none"></i>


                    <!-- <box-icon name='exit-fullscreen'></box-icon> -->
                </a>
                <!-- End::header-link -->
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            {{-- <div class="header-element meassage-dropdown">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-auto-close="outside"
                    data-bs-toggle="dropdown">
                    <i class="bx bx-message-square-dots  header-link-icon"></i>
                    <span class="pulse-danger"></span>
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <!-- Start::main-header-dropdown -->
                <div class="main-header-dropdown dropdown-menu dropdown-menu-end overflow-hidden"
                    data-popper-placement="none">
                    <div class="p-3 bg-primary">
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="mb-0 fs-17 fw-500">Messages</p>
                            <span class="badge bg-success fw-normal rounded-pill" id="message-data">5
                                Unread</span>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <ul class="list-unstyled mb-0" id="header-notification-scroll1">
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start">
                                <div class="pe-2">
                                    <img src="{{asset('images/faces/1.jpg')}}" alt="img"
                                        class="rounded-circle avatar">
                                </div>
                                <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold"><a href="chat.html">Gerninia<span
                                                    class="text-muted fs-12 fw-normal ps-1"> 6:45 am </span></a>
                                        </p>
                                        <span
                                            class="text-muted fw-normal fs-12 header-notification-text">Commented
                                            on file Guest list....</span>
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);"
                                            class="min-w-fit-content text-muted me-1 dropdown-item-close3"><i
                                                class="ti ti-x fs-16"></i></a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start">
                                <div class="pe-2">
                                    <img src="{{asset('images/faces/2.jpg')}}" alt="img"
                                        class="rounded-circle avatar">
                                </div>
                                <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold"><a href="chat.html">Peter Theil<span
                                                    class="text-muted fs-12 fw-normal ps-1"> 5 hours ago
                                                </span></a>
                                        </p>
                                        <span class="text-muted fw-normal fs-12 header-notification-text">Brizid
                                            is in the Warehouse...</span>
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);"
                                            class="min-w-fit-content text-muted me-1 dropdown-item-close3"><i
                                                class="ti ti-x fs-16"></i></a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start">
                                <div class="pe-2">
                                    <img src="{{asset('images/faces/3.jpg')}}" alt="img"
                                        class="rounded-circle avatar">
                                </div>
                                <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold"><a href="chat.html">John vally<span
                                                    class="text-muted fs-12 fw-normal ps-1">45 mintues ago
                                                </span></a>
                                        </p>
                                        <span class="text-muted fw-normal fs-12 header-notification-text">New
                                            Product Realease......</span>
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);"
                                            class="min-w-fit-content text-muted me-1 dropdown-item-close3"><i
                                                class="ti ti-x fs-16"></i></a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start">
                                <div class="pe-2">
                                    <img src="{{asset('images/faces/4.jpg')}}" alt="img"
                                        class="rounded-circle avatar">
                                </div>
                                <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold"><a href="chat.html">Samonu Show<span
                                                    class="text-muted fs-12 fw-normal ps-1">20 mintues ago
                                                </span></a>
                                        </p>
                                        <span class="text-muted fw-normal fs-12 header-notification-text">New
                                            Meetup Started......</span>
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);"
                                            class="min-w-fit-content text-muted me-1 dropdown-item-close3"><i
                                                class="ti ti-x fs-16"></i></a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start">
                                <div class="pe-2">
                                    <img src="{{asset('images/faces/2.jpg')}}" alt="img"
                                        class="rounded-circle avatar">
                                </div>
                                <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold"><a href="chat.html">Senrio<span
                                                    class="text-muted fs-12 fw-normal ps-1"> 10 hours ago
                                                </span></a>
                                        </p>
                                        <span class="text-muted fw-normal fs-12 header-notification-text">New
                                            product
                                            Launching...</span>
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);"
                                            class="min-w-fit-content text-muted me-1 dropdown-item-close3"><i
                                                class="ti ti-x fs-16"></i></a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class=" p-3 empty-header-item1 border-top dropdown-footer">
                        <div class="d-grid">
                            <a href="chat.html" class="btn text-muted p-0 text-primary">View all</a>
                        </div>
                    </div>
                    <div class="p-5 empty-item1 d-none">
                        <div class="text-center">
                            <span class="avatar avatar-xl avatar-rounded bg-primary-transparent">
                                <i class="ri-mail-close-line fs-2"></i>
                            </span>
                            <h6 class="fw-semibold mt-3">No New Messages</h6>
                        </div>
                    </div>
                </div>
                <!-- End::main-header-dropdown -->
            </div> --}}
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element notifications-dropdown">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-auto-close="outside"
                    data-bs-toggle="dropdown">
                    <i class="bx bx-bell  header-link-icon"></i>
                    <span class="badge bg-secondary fw-normal rounded-pill cart-badge" id="notifiation-data">5</span>
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <!-- Start::main-header-dropdown -->
                <div class="main-header-dropdown dropdown-menu dropdown-menu-end overflow-hidden"
                    data-popper-placement="none">
                    <div class="p-3 bg-primary overflow-hidden">
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="mb-0 fs-17 fw-500">Notifications</p>
                            <span class="badge bg-success fw-normal rounded-pill">Mark All Read</span>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <ul class="list-unstyled mb-0" id="header-notification-scroll">
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start">
                                <div class="pe-2">
                                    <span class="avatar avatar-md bg-primary-transparent rounded-3"><i
                                            class="bx bx-duplicate fs-18"></i></span>
                                </div>
                                <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold"><a href="chat.html"> Updates Available </a>
                                        </p>
                                        <span class="text-muted fw-normal fs-12 header-notification-text">2 days
                                            ago</span>
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);"
                                            class="min-w-fit-content text-muted me-1 dropdown-item-close2"><i
                                                class="ti ti-x fs-16"></i></a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start">
                                <div class="pe-2">
                                    <span class="avatar avatar-md bg-danger-transparent rounded-3"><i
                                            class="bx bx-folder-open fs-18"></i></span>
                                </div>
                                <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold"><a href="chat.html"> New files available
                                            </a>
                                        </p>
                                        <span class="text-muted fw-normal fs-12 header-notification-text"> 10
                                            hour ago </span>
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);"
                                            class="min-w-fit-content text-muted me-1 dropdown-item-close2"><i
                                                class="ti ti-x fs-16"></i></a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start">
                                <div class="pe-2">
                                    <span class="avatar avatar-md bg-success-transparent rounded-3"><i
                                            class="bx bx-cart fs-18"></i></span>
                                </div>
                                <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold"><a href="chat.html"> New Order Received </a>
                                        </p>
                                        <span class="text-muted fw-normal fs-12 header-notification-text">1 hour
                                            ago</span>
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);"
                                            class="min-w-fit-content text-muted me-1 dropdown-item-close2"><i
                                                class="ti ti-x fs-16"></i></a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start">
                                <div class="pe-2">
                                    <span class="avatar avatar-md bg-warning-transparent rounded-3"><i
                                            class="bx bx-envelope fs-18"></i></span>
                                </div>
                                <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold"><a href="chat.html">New Updates
                                                available</a>
                                        </p>
                                        <span class="text-muted fw-normal fs-12 header-notification-text">1 day
                                            ago</span>
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);"
                                            class="min-w-fit-content text-muted me-1 dropdown-item-close2"><i
                                                class="ti ti-x fs-16"></i></a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start">
                                <div class="pe-2">
                                    <span class="avatar avatar-md bg-danger-transparent rounded-3"><i
                                            class="bx bx-file fs-18"></i></span>
                                </div>
                                <div class="flex-grow-1 d-flex align-items-center justify-content-between">
                                    <div>
                                        <p class="mb-0 fw-semibold"><a href="chat.html"> 2 verified
                                                registrations </a>
                                        </p>
                                        <span class="text-muted fw-normal fs-12 header-notification-text">2 hour
                                            ago</span>
                                    </div>
                                    <div>
                                        <a href="javascript:void(0);"
                                            class="min-w-fit-content text-muted me-1 dropdown-item-close2"><i
                                                class="ti ti-x fs-16"></i></a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class=" p-3 empty-header-item2 border-top dropdown-footer">
                        <div class="d-grid">
                            <a href="chat.html" class="btn text-muted p-0 text-primary">View all</a>
                        </div>
                    </div>
                    <div class="p-5 empty-item2 d-none">
                        <div class="text-center">
                            <span class="avatar avatar-xl avatar-rounded bg-secondary-transparent">
                                <i class="ri-notification-off-line fs-2"></i>
                            </span>
                            <h6 class="fw-semibold mt-3">No New Notifications</h6>
                        </div>
                    </div>
                </div>
                <!-- End::main-header-dropdown -->
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            {{-- <div class="header-element cart-dropdown">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-auto-close="outside"
                    data-bs-toggle="dropdown">
                    <i class="bx bx-cart-alt  header-link-icon"></i>
                    <span class="badge rounded-pill bg-success cart-badge fw-normal" id="cart-badge">5</span>
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <!-- Start::main-header-dropdown -->
                <div class="main-header-dropdown dropdown-menu dropdown-menu-end overflow-hidden"
                    data-popper-placement="none">
                    <div class="p-3 bg-primary">
                        <div class="d-flex align-items-center justify-content-between">
                            <p class="mb-0 fs-17 fw-500">Cart Items</p>
                            <span class="badge bg-success fw-normal rounded-pill" id="cart-data">5 Items</span>
                        </div>
                    </div>
                    <div>
                        <hr class="dropdown-divider">
                    </div>
                    <ul class="list-unstyled mb-0" id="header-cart-items-scroll">
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start cart-dropdown-item">
                                <img src="/images/ecommerce/1.jpg" alt="img"
                                    class="avatar avatar-md avatar-rounded br-5 me-3">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-start justify-content-between mb-0">
                                        <div class="mb-0 fs-13 text-dark fw-semibold">
                                            <a href="cart.html">Flower Pot</a>
                                        </div>
                                        <div class="my-auto">
                                            <a href="javascript:void(0);"
                                                class="header-cart-remove float-end dropdown-item-close"><i
                                                    class="fe fe-trash text-danger"></i></a>
                                        </div>
                                    </div>
                                    <div
                                        class="min-w-fit-content d-flex align-items-start justify-content-between">
                                        <ul class="header-product-item d-flex">
                                            <li> Price: $ 39.99 </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start cart-dropdown-item">
                                <img src="/images/ecommerce/3.jpg" alt="img"
                                    class="avatar avatar-md avatar-rounded br-5 me-3">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-start justify-content-between mb-0">
                                        <div class="mb-0 fs-13 text-dark fw-semibold">
                                            <a href="cart.html">AirPods</a>
                                        </div>
                                        <div class="my-auto">
                                            <a href="javascript:void(0);"
                                                class="header-cart-remove float-end dropdown-item-close"><i
                                                    class="fe fe-trash text-danger"></i></a>
                                        </div>
                                    </div>
                                    <div
                                        class="min-w-fit-content d-flex align-items-start justify-content-between">
                                        <ul class="header-product-item">
                                            <li> Price: $ 29.99 </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start cart-dropdown-item">
                                <img src="/images/ecommerce/5.jpg" alt="img"
                                    class="avatar avatar-md avatar-rounded br-5 me-3">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-start justify-content-between mb-0">
                                        <div class="mb-0 fs-13 text-dark fw-semibold">
                                            <a href="cart.html">HeadPhones</a>
                                        </div>
                                        <div class="my-auto">
                                            <a href="javascript:void(0);"
                                                class="header-cart-remove float-end dropdown-item-close"><i
                                                    class="fe fe-trash text-danger"></i></a>
                                        </div>
                                    </div>
                                    <div
                                        class="min-w-fit-content d-flex align-items-start justify-content-between">
                                        <ul class="header-product-item d-flex">
                                            <li> Price: $ 20.99 </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start cart-dropdown-item">
                                <img src="/images/ecommerce/4.jpg" alt="img"
                                    class="avatar avatar-md avatar-rounded br-5 me-3">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-start justify-content-between mb-0">
                                        <div class="mb-0 fs-13 text-dark fw-semibold">
                                            <a href="cart.html">Sports Shooes</a>
                                        </div>
                                        <div class="my-auto">
                                            <a href="javascript:void(0);"
                                                class="header-cart-remove float-end dropdown-item-close"><i
                                                    class="fe fe-trash text-danger"></i></a>
                                        </div>
                                    </div>
                                    <div
                                        class="min-w-fit-content d-flex align-items-start justify-content-between">
                                        <ul class="header-product-item d-flex">
                                            <li> Price: $ 19.99 </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-item">
                            <div class="d-flex align-items-start cart-dropdown-item">
                                <img src="/images/ecommerce/6.jpg" alt="img"
                                    class="avatar avatar-md avatar-rounded br-5 me-3">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-start justify-content-between mb-0">
                                        <div class="mb-0 fs-13 text-dark fw-semibold">
                                            <a href="cart.html">Camera Lence</a>
                                        </div>
                                        <div class="my-auto">
                                            <a href="javascript:void(0);"
                                                class="header-cart-remove float-end dropdown-item-close"><i
                                                    class="fe fe-trash text-danger"></i></a>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-start justify-content-between">
                                        <ul class="header-product-item d-flex">
                                            <li> Price: $ 59.99 </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="p-3 empty-header-item border-top dropdown-footer">
                        <div class="d-grid">
                            <a href="checkout.html" class="btn text-muted p-0 text-primary">Proceed to
                                checkout</a>
                        </div>
                    </div>
                    <div class="p-5 empty-item d-none">
                        <div class="text-center">
                            <span class="avatar avatar-xl avatar-rounded bg-warning-transparent">
                                <i class="ri-shopping-cart-2-line fs-2"></i>
                            </span>
                            <h6 class="fw-bold mb-1 mt-3">Your Cart is Empty</h6>
                            <span class="mb-3 fw-normal fs-13 d-block">Add some items to make me happy :)</span>
                            <a href="shop.html" class="btn btn-primary btn-wave btn-sm m-1"
                                data-abc="true">continue
                                shopping <i class="bi bi-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>
                <!-- End::main-header-dropdown -->
            </div> --}}
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element profile-1">
                <!-- Start::header-link|dropdown-toggle -->
                <a href="javascript:void(0);"
                    class=" dropdown-toggle leading-none header-link d-flex justify-content-center"
                    id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                    aria-expanded="false">
                    <img src="{{ asset('/images/faces/21104.png') }}" alt="img" width="32" height="32"
                        class="rounded-circle">
                    <!-- <div class="d-flex align-items-center">
                </div> -->
                </a>
                <!-- End::header-link|dropdown-toggle -->
                <div class="main-header-dropdown dropdown-menu dropdown-menu-end overflow-hidden profile-dropdown"
                    data-popper-placement="none">
                    <div class="main-header-profile bg-primary border-bottom p-3">
                        <div class="d-flex wd-100p">
                            <div class="main-img-user">
                                <img alt="" src="{{ asset('/images/faces/21104.png') }}"
                                    class="avatar avatar-md">
                            </div>
                            <div class="ms-2 my-auto text-white">
                                <h6 class="mb-0 text-fixed-white">{{ Auth::user()->name }}</h6>
                                <span class=" text-fixed-white op-7 fs-13">({{ Auth::user()->email }})</span>
                            </div>
                        </div>
                    </div>
                    {{-- <a class="dropdown-item d-flex" href="profile.html"><i
                            class="bx bx-user-circle fs-18  me-2"></i>Profile</a> --}}
                    <a class="dropdown-item d-flex" href="{{ route('profile.edit') }}"><i
                            class="bx bx-cog  fs-18  me-2"></i> Edit Profile</a>
                    {{-- <a class="dropdown-item d-flex" href="{{ route('report.today') }}"><i
                            class="bx bxs-report side-menu__icon fs-18 me-2"></i> Today Report</a> --}}
                    {{-- <a class="dropdown-item d-flex" href="email.html"><i
                            class="bx bxs-inbox  fs-18  me-2"></i>Inbox</a>
                    <a class="dropdown-item d-flex" href="chat.html"><i
                            class="bx bx-envelope  fs-18  me-2"></i>Messages</a>
                    <a class="dropdown-item d-flex" href="settings.html"><i
                            class="bx bx-slider-alt  fs-18  me-2"></i> Account Settings</a> --}}
                    <a class="dropdown-item d-flex logout-user" id="logout-user" href="javascript:void(0);"><i
                            class="bx bx-log-out  fs-18  me-2"></i>Sign Out</a>
                </div>
            </div>
            <!-- End::header-element -->



        </div>
        <!-- End::header-content-right -->

    </div>
    <!-- End::main-header-container -->
</header>

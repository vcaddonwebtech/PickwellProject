<!-- Start::app-sidebar -->
<aside class="app-sidebar sticky" id="sidebar">

    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="{{ route('dashboard') }}" class="header-logo">
            <img src="{{ asset('images/brand-logos/desktop-logo.png') }}" alt="logo" class="" style="height: 60px;">
            <img src="{{ asset('images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">

        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
                    viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path>
                </svg>
            </div>
            <ul class="main-menu mt-5">
                <li class="slide">
                    <a href="{{ route('dashboard') }}"
                        class="side-menu__item @if (request()->routeIs('dashboard') || request()->routeIs('home') || request()->routeIs('machineservicedata') || request()->routeIs('complaints.create') || request()->routeIs('complaints.index')) active @endif">
                        <i class="bx bx-desktop side-menu__icon"></i>
                        <span class="side-menu__label">Dashboard</span>
                    </a>
                </li>
                <?php  
                if(auth()->user()->getRoleNames()->first() == "Payroll Manager") { ?>
                <li class="slide">
                    <a href="{{ route('adminusers.index') }}"
                        class="side-menu__item @if (request()->routeIs('adminusers.index') || request()->routeIs('adminusers.create') || request()->routeIs('adminusers.edit') || request()->routeIs('adminusersprofile')) active @endif">
                        <i class="bx bx-user side-menu__icon"></i>
                        <span class="side-menu__label">Staff List</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="{{ route('attendap-today-report') }}"
                        class="side-menu__item @if (request()->routeIs('attendap-today-report') || request()->routeIs('work-update-list') || request()->routeIs('edit-work') || request()->routeIs('user-monthly-attendence-list')) active @endif">
                        <i class="bx bx-file side-menu__icon"></i>
                        <span class="side-menu__label">Attendence</span>
                    </a>
                </li>
                <?php } ?>
                <?php  
                if(auth()->user()->getRoleNames()->first() == "Admin") { ?>
                <li class="slide">
                    <a href="{{ route('machines.index') }}"
                        class="side-menu__item @if (request()->routeIs('machines.index') || request()->routeIs('machines.create') || request()->routeIs('machines.edit')) active @endif">
                        <i class="bx bx-box side-menu__icon"></i>
                        <span class="side-menu__label">Machines</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="{{ route('adminusers.index') }}"
                        class="side-menu__item @if (request()->routeIs('adminusers.index') || request()->routeIs('adminusers.create') || request()->routeIs('adminusers.edit') || request()->routeIs('adminusersprofile')) active @endif">
                        <i class="bx bx-user side-menu__icon"></i>
                        <span class="side-menu__label">Staff List</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="{{ route('attendap-today-report') }}"
                        class="side-menu__item @if (request()->routeIs('attendap-today-report') || request()->routeIs('work-update-list') || request()->routeIs('edit-work') || request()->routeIs('user-monthly-attendence-list')) active @endif">
                        <i class="bx bx-file side-menu__icon"></i>
                        <span class="side-menu__label">Attendence</span>
                    </a>
                </li>
                {{-- <!-- <li class="slide">
                    <a href="{{ route('parties.index') }}"
                        class="side-menu__item @if (request()->routeIs('parties.index') || request()->routeIs('parties.create') || request()->routeIs('parties.edit')) active @endif">
                        <i class="bx bxs-user-account side-menu__icon"></i>
                        <span class="side-menu__label">Customers</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="{{ route('products.index') }}"
                        class="side-menu__item @if (request()->routeIs('products.index') ||
                                request()->routeIs('products.create') ||
                                request()->routeIs('products.edit')) active @endif">
                        <i class="bx bx-package side-menu__icon"></i>
                        <span class="side-menu__label">Products</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="{{ route('MachineSales.index') }}"
                        class="side-menu__item @if (request()->routeIs('MachineSales.index') ||
                                request()->routeIs('MachineSales.create') ||
                                request()->routeIs('MachineSales.edit')) active @endif">
                        <i class="bx bx-cart side-menu__icon"></i>
                        <span class="side-menu__label">Sold Machines</span>
                    </a>
                </li>
                
                 <li class="slide">
                    <a href="{{ route('complaints.index') }}"
                        class="side-menu__item @if (request()->routeIs('complaints.index') ||
                                request()->routeIs('complaints.create') ||
                                request()->routeIs('complaints.edit')) active @endif">
                        <i class="bx bx-error-alt side-menu__icon"></i>
                        <span class="side-menu__label">Complaints</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="{{ route('parts_inventory.index') }}"
                        class="side-menu__item @if (request()->routeIs('parts_inventory.index') ||
                                request()->routeIs('parts_inventory.create') ||
                                request()->routeIs('parts_inventory.edit')) active @endif">
                        <i class="bx bx-area side-menu__icon"></i>
                        <span class="side-menu__label">Parts Inventory</span>
                    </a>
                </li> --> --}}
                
                {{-- <!--<li class="slide has-sub master_sub_menu">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="bx bx-data side-menu__icon"></i>
                        <span class="side-menu__label">Master</span>
                        <i class="fe fe-chevron-down side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu sub_menu_list"
                        style="position: relative; left: 0px; top: 0px; margin: 0px; transform: translate(120px, 610px); box-sizing: border-box; display: none;"
                        data-popper-placement="bottom">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">complain</a>
                        </li> 
                        <li class="slide">
                            <a href="{{ route('users.index') }}"
                                class="side-menu__item @if (request()->routeIs('users.index') || request()->routeIs('users.create') || request()->routeIs('users.edit')) active @endif">
                                <i class="bx bx-user side-menu__icon"></i>
                                <span class="side-menu__label">Users</span>
                            </a>
                        </li>
                         <li class="slide">
                            <a href="{{ route('item-parts.index') }}"
                                class="side-menu__item @if (request()->routeIs('item-parts.index') ||
                                        request()->routeIs('item-parts.create') ||
                                        request()->routeIs('item-parts.edit')) active @endif">
                                <i class="bx bx-package side-menu__icon"></i>
                                <span class="side-menu__label">Product Item Parts</span>
                            </a>
                        </li> 
                        <li class="slide">
                            <a href="{{ route('product-groups.index') }}"
                                class="side-menu__item @if (request()->routeIs('product-groups.index') ||
                                        request()->routeIs('product-groups.create') ||
                                        request()->routeIs('product-groups.edit')) active @endif">
                                <i class="bx bx-cube side-menu__icon"></i>
                                <span class="side-menu__label">Product Groups</span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('service-types.index') }}"
                                class="side-menu__item @if (request()->routeIs('service-types.index') ||
                                        request()->routeIs('service-types.create') ||
                                        request()->routeIs('service-types.edit')) active @endif">
                                <i class="bx bxs-cog side-menu__icon"></i>
                                <span class="side-menu__label">Service Types</span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('complaint-types.index') }}"
                                class="side-menu__item @if (request()->routeIs('complaint-types.index') ||
                                        request()->routeIs('complaint-types.create') ||
                                        request()->routeIs('complaint-types.edit')) active @endif">
                                <i class="bx bx-question-mark side-menu__icon"></i>
                                <span class="side-menu__label">Complaint Types</span>
                            </a>
                        </li>

                        

                          <li class="slide">
                            <a href="{{route('engineers.index')}}"
                                class="side-menu__item @if (request()->routeIs('engineers.index') || request()->routeIs('engineers.create') || request()->routeIs('engineers.edit')) active @endif">
                                <i class="bx bx-user side-menu__icon"></i>
                                <span class="side-menu__label">Engineers</span>
                            </a>
                        </li> 



                        <li class="slide">
                            <a href="{{ route('areas.index') }}"
                                class="side-menu__item @if (request()->routeIs('areas.index') || request()->routeIs('areas.create') || request()->routeIs('areas.edit')) active @endif">
                                <i class="bx bx-area side-menu__icon"></i>
                                <span class="side-menu__label">Area</span>
                            </a>
                        </li>

                         <li class="slide">
                            <a href="{{ route('owners.index') }}"
                                class="side-menu__item @if (request()->routeIs('owners.index') || request()->routeIs('owners.create') || request()->routeIs('owners.edit')) active @endif">
                                <i class="bx bx-user-circle side-menu__icon"></i>
                                <span class="side-menu__label">Owners</span>
                            </a>
                        </li>

                        <li class="slide">
                            <a href="{{ route('contact_persons.index') }}"
                                class="side-menu__item @if (request()->routeIs('contact_persons.index') ||
                                        request()->routeIs('contact_persons.create') ||
                                        request()->routeIs('contact_persons.edit')) active @endif">
                                <i class="bx bx-mobile-alt side-menu__icon"></i>
                                <span class="side-menu__label">Contact Persons</span>
                            </a>
                        </li> 
                    </ul>
                </li>--> --}}

                {{-- <li class="slide">
                    <a href="{{route('importData')}}"
                        class="side-menu__item @if (request()->routeIs('importData')) active @endif">
                        <i class="bx bxs-report side-menu__icon"></i>
                        <span class="side-menu__label">User Import Data</span>
                    </a>
                </li>

                <li class="slide">
                    <a href="{{route('finalApp')}}"
                        class="side-menu__item @if (request()->routeIs('finalApp')) active @endif">
                        <i class="bx bxs-report side-menu__icon"></i>
                        <span class="side-menu__label">Final App Data</span>
                    </a>
                </li> --}}

                {{-- <!-- <li class="slide has-sub master_sub_menu">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="bx bxs-report side-menu__icon"></i>
                        <span class="side-menu__label">Service Reports</span>
                        <i class="fe fe-chevron-down side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu sub_menu_list"
                        style="position: relative; left: 0px; top: 0px; margin: 0px; transform: translate(120px, 610px); box-sizing: border-box; display: none;"
                        data-popper-placement="bottom">
                         <li class="slide">
                            <a href="{{ route('report.machine-salse-report') }}"
                                class="side-menu__item @if (request()->routeIs('report.machine-salse-report')) active @endif">
                                <i class="bx bxs-report side-menu__icon"></i>
                                <span class="side-menu__label">Machine Salse Report</span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('machine-sales.expiry-report') }}"
                                class="side-menu__item @if (request()->routeIs('machine-sales.expiry-report')) active @endif">
                                <i class="bx bx-x-circle side-menu__icon"></i>
                                <span class="side-menu__label">M/c Sales Expiry </span>
                            </a>
                        </li> 
                        <li class="slide">
                            <a href="{{ route('report.report-complaint') }}"
                                class="side-menu__item @if (request()->routeIs('report.report-complaint')) active @endif">
                                <i class="bx bx-question-mark side-menu__icon"></i>
                                <span class="side-menu__label">Complaint</span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('report.report-complaint-pending') }}"
                                class="side-menu__item @if (request()->routeIs('report.report-complaint-pending')) active @endif">
                                <i class="bx bx-error side-menu__icon"></i>
                                <span class="side-menu__label">Complaint Pending</span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('complaints.report') }}"
                                class="side-menu__item @if (request()->routeIs('complaints.report')) active @endif">
                                <i class="bx bx-error-circle side-menu__icon"></i>
                                <span class="side-menu__label">Complaints Status </span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('report.complainttype') }}"
                                class="side-menu__item @if (request()->routeIs('report.complainttype')) active @endif">
                                <i class="bx bx-error-circle side-menu__icon"></i>
                                <span class="side-menu__label">Comp Type Summary</span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('report.free-service-report') }}"
                                class="side-menu__item @if (request()->routeIs('report.free-service-report')) active @endif">
                                <i class="bx bxs-report side-menu__icon"></i>
                                <span class="side-menu__label">Free Service Report</span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('report.engineervisit') }}"
                                class="side-menu__item @if (request()->routeIs('report.engineervisit')) active @endif">
                                <i class="bx bx-check side-menu__icon"></i>
                                <span class="side-menu__label">Engineer Comp. Done</span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('report.eng-done-summary') }}"
                                class="side-menu__item @if (request()->routeIs('report.eng-done-summary')) active @endif">
                                <i class="bx bx-check side-menu__icon"></i>
                                <span class="side-menu__label">Eng. Done Summary</span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('report.engineer-performance') }}"
                                class="side-menu__item @if (request()->routeIs('report.engineer-performance')) active @endif">
                                <i class="bx bx-check side-menu__icon"></i>
                                <span class="side-menu__label">Engineer Performance</span>
                            </a>
                        </li>
                        <li class="slide">
                            <a href="{{ route('report.today') }}"
                                class="side-menu__item @if (request()->routeIs('report-today')) active @endif"
                                target="_blank">
                                <i class="bx bxs-report side-menu__icon"></i>
                                <span class="side-menu__label">Today Admin Report</span>
                            </a>
                        </li> 
                        <li class="slide">
                            <a href="{{ route('customer-feedback') }}"
                                class="side-menu__item @if (request()->routeIs('customer-feedback')) active @endif">
                                <i class="bx bxs-report side-menu__icon"></i>
                                <span class="side-menu__label">Customer Feedback</span>
                            </a>
                        </li> 
                    </ul>
                </li> 

                <li class="slide has-sub master_sub_menu">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="bx bxs-report side-menu__icon"></i>
                        <span class="side-menu__label">Sales Reports</span>
                        <i class="fe fe-chevron-down side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu sub_menu_list"
                        style="position: relative; left: 0px; top: 0px; margin: 0px; transform: translate(120px, 610px); box-sizing: border-box; display: none;"
                        data-popper-placement="bottom">
                        <li class="slide">
                            <a href="{{ route('salse-lead-report') }}"
                                class="side-menu__item @if (request()->routeIs('report.salse-lead-report') ||
                                        request()->routeIs('parts_inventory.create') ||
                                        request()->routeIs('parts_inventory.edit')) active @endif">
                                <i class="bx bx-area side-menu__icon"></i>
                                <span class="side-menu__label">Salse Lead Report</span>
                            </a>
                        </li>

                        <li class="slide">
                            <a href="{{ route('report.sales-report') }}"
                                class="side-menu__item @if (request()->routeIs('report.sales-report')) active @endif">
                                <i class="bx bx-area side-menu__icon"></i>
                                <span class="side-menu__label">Sales Summary</span>
                            </a>
                        </li>
                    </ul>
                </li> --> --}}

                <!-- <li class="slide has-sub master_sub_menu">
                    <a href="javascript:void(0);" class="side-menu__item">
                        <i class="bx bxs-report side-menu__icon"></i>
                        <span class="side-menu__label">To Do Reports</span>
                        <i class="fe fe-chevron-down side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu sub_menu_list"
                        style="position: relative; left: 0px; top: 0px; margin: 0px; transform: translate(120px, 610px); box-sizing: border-box; display: none;"
                        data-popper-placement="bottom">
                        <li class="slide">
                            <a href="{{ route('report.todo-report') }}"
                                class="side-menu__item @if (request()->routeIs('report.todo-report')) active @endif">
                                <i class="bx bx-area side-menu__icon"></i>
                                <span class="side-menu__label">To Do Report</span>
                            </a>
                        </li>
                    </ul>
                </li> -->
                
                {{-- <li class="slide">
                    <a href="{{ route('ap_summary') }}"
                        class="side-menu__item @if (request()->routeIs('ap_summary') || request()->routeIs('ap_detail')) active @endif">
                        <i class="bx bx-purchase-tag-alt side-menu__icon"></i>
                        <span class="side-menu__label">A/P Details & Summary</span>
                    </a>
                </li> --}}
                <?php } ?>
                 {{-- <!-- <li class="slide">
                            <a href="{{ route('customer-feedback') }}"
                                class="side-menu__item @if (request()->routeIs('customer-feedback')) active @endif">
                                <i class="bx bxs-star side-menu__icon"></i>
                                <span class="side-menu__label">Customer Feedback</span>
                            </a>
                </li>            
                <li class="slide">
                    <a href="{{ route('todayReport') }}"
                        class="side-menu__item @if (request()->routeIs('todayReport')) active @endif">
                        <i class="bx bxs-bookmarks side-menu__icon"></i>
                        <span class="side-menu__label">A/P Report Today</span>
                    </a>
                </li>
                <li class="slide">
                    <a href="{{ route('ap_summary') }}"
                        class="side-menu__item @if (request()->routeIs('ap_summary') || request()->routeIs('ap_detail')) active @endif">
                        <i class="bx bxs-report side-menu__icon"></i>
                        <span class="side-menu__label">A/P Details Summary</span>
                    </a>
                </li> -->  --}} 
                <li class="slide">
                    <a href="{{ route('leave.index') }}"
                        class="side-menu__item @if (request()->routeIs('leave.index') || request()->routeIs('leave.create') || request()->routeIs('leave.edit')) active @endif">
                        <i class="bx bx-calendar side-menu__icon"></i>
                        <span class="side-menu__label">Leaves</span>
                    </a>
                </li>
                 <?php  
                if(auth()->user()->getRoleNames()->first() == "Payroll Manager" || auth()->user()->getRoleNames()->first() == "Admin" || auth()->user()->getRoleNames()->first() == "Service Team Leader") { ?>
                <li class="slide">
                            <a href="{{ route('department.index') }}"
                                class="side-menu__item @if (request()->routeIs('department.index') || request()->routeIs('department.create') || request()->routeIs('department.edit')) active @endif">
                                <i class="bx bx-user side-menu__icon"></i>
                                <span class="side-menu__label">Department</span>
                            </a>
                </li>
                <li class="slide">
                            <a href="{{ route('shift.index') }}"
                                class="side-menu__item @if (request()->routeIs('shift.index') || request()->routeIs('shift.create') || request()->routeIs('shift.edit')) active @endif">
                                <i class="bx bx-calendar side-menu__icon"></i>
                                <span class="side-menu__label">Shifts</span>
                            </a>
                </li>
                <?php } ?>
                <li class="slide">
                            <a href="{{ route('areas.index') }}"
                                class="side-menu__item @if (request()->routeIs('areas.index') || request()->routeIs('areas.create') || request()->routeIs('areas.edit')) active @endif">
                                <i class="bx bx-area side-menu__icon"></i>
                                <span class="side-menu__label">Area</span>
                            </a>
                </li>
                <li class="slide">
                    <a href="{{ route('holiday.index') }}"
                        class="side-menu__item @if (request()->routeIs('holiday.index') || request()->routeIs('holiday.create') || request()->routeIs('holiday.edit')) active @endif">
                        <i class="bx bx-calendar side-menu__icon"></i>
                        <span class="side-menu__label">Holidays</span>
                    </a>
                </li>
                
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                    width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path>
                </svg></div>
        </nav>
        <!-- End::nav -->

    </div>
    <!-- End::main-sidebar -->

</aside>
<!-- End::app-sidebar -->

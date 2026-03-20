<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\SalesPersonController;
use App\Http\Controllers\ComplaintTypeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EngineerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ItemPartsController;
use App\Http\Controllers\MachineSaleEntryController;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ImportDataController;
use App\Http\Controllers\PricvacyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PartsInventoryController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\MachineController;
use App\Models\Complaint;
use Illuminate\Support\Facades\Route;


Route::get('/sendmail', [ContactController::class, 'send']);
//Route::match(['get', 'post'], '/sendmail', [ContactController::class, 'send']);

Route::get('/', [DashboardController::class, 'states'])->name('home')->middleware('auth');
Route::get('/privacy-policy', [PricvacyController::class, 'index']);

Route::post('/loginwithotp', [UserController::class, 'verifyopt'])->name('loginwithotp');    
Route::get('/resendotp/{mobile}', [UserController::class, 'resendotp'])->name('resendotp');  

Route::get('/dashboard', [DashboardController::class, 'states'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/machineservicedata/{main_machine_type}', [DashboardController::class, 'machineservicestates'])->middleware(['auth', 'verified'])->name('machineservicedata');
Route::get('/machineservicestatesdata/{main_machine_type}', [DashboardController::class, 'machineservicestatesdata'])->middleware(['auth', 'verified'])->name('machineservicestatesdata');
Route::get('/machinesalesdata/{main_machine_type}', [DashboardController::class, 'machinesalesstates'])->middleware(['auth', 'verified'])->name('machinesalesdata');
Route::get('/today-report/{main_machine_type}', [ComplaintController::class, 'todayReport'])->name('mReport')->middleware('auth');
Route::get('/attendap-today-report', [ComplaintController::class, 'attendapTodayReport'])->name('attendap-today-report')->middleware('auth');
Route::get('/user-monthly-attendence-list/{user}', [ComplaintController::class, 'userMonthlyAttendenceList'])->name('user-monthly-attendence-list')->middleware('auth');
Route::get('/user-daily-attendence-details/{user}/{date}', [ComplaintController::class, 'userDailyAttendenceDetails'])->name('user-daily-attendence-details')->middleware('auth');
Route::get('/work-update-list', [SalesPersonController::class, 'workUpdateList'])->name('work-update-list')->middleware('auth');
Route::get('/edit-work/{lead}', [SalesPersonController::class, 'editWork'])->name('edit-work')->middleware('auth');
Route::post('/updateswork/{lead}', [SalesPersonController::class, 'updateSwork'])->name('updateswork')->middleware('auth');
Route::get('/saletoday-report/{main_machine_type}', [ComplaintController::class, 'saleTodayReport'])->name('saletodayReport')->middleware('auth');
Route::get('sales/dashboard', [DashboardController::class, 'salesDashboard'])->name('sales-dashboard')->middleware('auth');
Route::get('engineer-pending-complaints', [DashboardController::class, 'engPendingComplaints'])->name('eng-pending-complaints')->middleware('auth');
Route::get('today-engineer-done-complaints', [DashboardController::class, 'todayEngDoneComplaints'])->name('today-eng-done-complaints')->middleware('auth');
Route::get('pending-assign-complaints', [DashboardController::class, 'pendingAssignComplaints'])->name('pending-assign-complaints')->middleware('auth');
Route::get('today-expiry-machine', [DashboardController::class, 'todayExpiryMachine'])->name('today-expiry-machine')->middleware('auth');
Route::get('total-pending-complaints', [DashboardController::class, 'todayPendingComplaint'])->name('total-pending-complaints')->middleware('auth');
Route::get('today-total-complaints', [DashboardController::class, 'todayTotalComplaints'])->name('today-total-complaints')->middleware('auth');
Route::get('today-done-complaints', [DashboardController::class, 'todayDoneComplaints'])->name('today-done-complaints')->middleware('auth');
Route::get('today-present-engineer', [DashboardController::class, 'todayPresentEngineer'])->name('today-present-engineer')->middleware('auth');
Route::get('/ap-summary/{main_machine_type}', [ComplaintController::class, 'apSummary'])->name('ap_summary')->middleware('auth');
Route::get('/saleap-summary/{main_machine_type}', [ComplaintController::class, 'saleapSummary'])->name('saleap_summary')->middleware('auth');
Route::get('ap-detail/{main_machine_type}/{engineer}', [ComplaintController::class, 'apDetails'])->name('ap_detail')->middleware('auth');
Route::get('salaries/{main_machine_type}', [ComplaintController::class, 'salaries'])->name('salaries')->middleware('auth');
Route::get('salesalaries/{main_machine_type}', [ComplaintController::class, 'saleSalaries'])->name('salesalaries')->middleware('auth');
Route::get('party-firms', [PartyController::class, 'partyFirms'])->name('party-firms')->middleware('auth');
//Route::get('dpr', [DashboardController::class, 'dpr'])->name('dpr')->middleware('auth');

Route::post('/approve-attendance', [ComplaintController::class, 'approveAttendance'])->name('approve-attendance')->middleware('auth');
Route::get('/pending-approval', [ComplaintController::class, 'pendingApproval'])->name('pending-approval')->middleware('auth');

Route::get('/salesleadcreate/{main_machine_type}', [SalesPersonController::class, 'salesleadcreate'])->name('salesleadcreate');
Route::post('/salesleadstore', [SalesPersonController::class, 'store'])->name('salesleadstore');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

route::group(['prefix' => 'adminusers', 'middleware' => ['auth']], function () {
    Route::get('/index', [AdminUserController::class, 'index'])->name('adminusers.index');
    Route::get('/create', [AdminUserController::class, 'create'])->name('adminusers.create');
    Route::post('/store', [AdminUserController::class, 'store'])->name('adminusers.store');
    Route::get('/adminusersprofile/{user}', [AdminUserController::class, 'adminusersProfile'])->name('adminusersprofile');
    Route::get('/edit/{user}', [AdminUserController::class, 'edit'])->name('adminusers.edit');
    Route::post('/update/{user}', [AdminUserController::class, 'update'])->name('adminusers.update');
    Route::delete('/destroy/{user}', [AdminUserController::class, 'destroy'])->name('adminusers.destroy');
    Route::post('/assign-roles/{user}', [AdminUserController::class, 'assignRoles'])->name('adminusers.assignRoles');
});

route::group(['prefix' => 'users', 'middleware' => ['auth']], function () {
    Route::get('/index/{main_machine_type}', [UserController::class, 'index'])->name('users.index');
    Route::get('/create/{main_machine_type}', [UserController::class, 'create'])->name('users.create');
    Route::post('/store', [UserController::class, 'store'])->name('users.store');
    Route::get('/usersprofile/{main_machine_type}/{user}', [UserController::class, 'usersProfile'])->name('usersprofile');
    Route::get('/edit/{main_machine_type}/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/update/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/destroy/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/assign-roles/{user}', [UserController::class, 'assignRoles'])->name('users.assignRoles');
});

Route::group(['prefix' => 'department', 'middleware' => ['auth']], function () {
    Route::get('/index', [DepartmentController::class, 'index'])->name('department.index');
    Route::get('/create', [DepartmentController::class, 'create'])->name('department.create');
    Route::post('/store', [DepartmentController::class, 'store'])->name('department.store');
    Route::get('/edit/{department}', [DepartmentController::class, 'edit'])->name('department.edit');
    Route::post('/update/{department}', [DepartmentController::class, 'update'])->name('department.update');
    Route::delete('/destroy/{department}', [DepartmentController::class, 'destroy'])->name('department.destroy');
});

Route::group(['prefix' => 'shift', 'middleware' => ['auth']], function () {
    Route::get('/index', [ShiftController::class, 'index'])->name('shift.index');
    Route::get('/create', [ShiftController::class, 'create'])->name('shift.create');
    Route::post('/store', [ShiftController::class, 'store'])->name('shift.store');
    Route::get('/edit/{shift}', [ShiftController::class, 'edit'])->name('shift.edit');
    Route::post('/update/{shift}', [ShiftController::class, 'update'])->name('shift.update');
    Route::delete('/destroy/{shift}', [ShiftController::class, 'destroy'])->name('shift.destroy');
});

Route::resource('machines', MachineController::class);

Route::get('/salesusers/{main_machine_type}', [UserController::class, 'salesuserlist'])->name('salesusers');
Route::get('/salesusercreate/{main_machine_type}', [UserController::class, 'saleUserCreate'])->name('salesusercreate');
Route::get('/saleusersprofile/{main_machine_type}/{user}', [UserController::class, 'saleUsersProfile'])->name('saleusersprofile');
Route::get('/saleuseredit/{main_machine_type}/{user}', [UserController::class, 'saleUserEdit'])->name('saleuseredit');

route::group(['prefix' => 'engineers', 'middleware' => ['auth']], function () {
    Route::get('/index', [EngineerController::class, 'index'])->name('engineers.index');
    Route::get('/create', [EngineerController::class, 'create'])->name('engineers.create');
    Route::post('/store', [EngineerController::class, 'store'])->name('engineers.store');
    Route::get('/edit/{engineer}', [EngineerController::class, 'edit'])->name('engineers.edit');
    Route::post('/update/{engineer}', [EngineerController::class, 'update'])->name('engineers.update');
    Route::delete('/destroy/{engineer}', [EngineerController::class, 'destroy'])->name('engineers.destroy');
    Route::post('/assign-roles/{engineer}', [EngineerController::class, 'assignRoles'])->name('engineers.assignRoles');
});


// Route::resource('parties', PartyController::class)->middleware(['auth']);

//Route::resource('MachineSales', MachineSaleEntryController::class)->middleware(['auth']);
//Route::post('update-machine-sales/{id}', [MachineSaleEntryController::class, 'update'])->name('machine-sales.update')->middleware(['auth']);
//Route::get('/machine-sales-expiry-report', [MachineSaleEntryController::class, 'expiryReport'])->name('machine-sales.expiry-report')->middleware('auth');

Route::group(['prefix' => 'MachineSales'], function () {
    Route::get('/index/{main_machine_type}', [MachineSaleEntryController::class, 'index'])->name('MachineSales.index')->middleware('auth');
    Route::get('/create/{main_machine_type}', [MachineSaleEntryController::class, 'create'])->name('MachineSales.create')->middleware('auth');
    Route::get('/new-create/{main_machine_type}', [MachineSaleEntryController::class, 'newCreate'])->name('MachineSales.new-create')->middleware('auth');
    Route::get('/addmachine-oldcust/{main_machine_type}', [MachineSaleEntryController::class, 'addMachinesForOldCust'])->name('MachineSales.addmachine-oldcust')->middleware('auth');
    Route::get('/party-machines/{main_machine_type}/{party}', [MachineSaleEntryController::class, 'partyMachines'])->name('MachineSales.party-machines')->middleware('auth');
    Route::post('/store', [MachineSaleEntryController::class, 'store'])->name('MachineSales.store')->middleware('auth');
    Route::post('/new-store', [MachineSaleEntryController::class, 'newStore'])->name('MachineSales.new-store')->middleware('auth');
    Route::get('edit/{main_machine_type}/{machinesale}', [MachineSaleEntryController::class, 'edit'])->name('MachineSales.edit')->middleware('auth');
    Route::post('/update/{machinesale}', [MachineSaleEntryController::class, 'update'])->name('MachineSales.update')->middleware('auth');
    Route::delete('/destroy/{machinesale}', [MachineSaleEntryController::class, 'destroy'])->name('MachineSales.destroy')->middleware('auth');
});

Route::group(['prefix' => 'visits'], function () {
    Route::get('/index/{main_machine_type}/{machinesale}', [VisitController::class, 'index'])->name('visits.index')->middleware('auth');
    Route::get('/create/{main_machine_type}/{machinesale}', [VisitController::class, 'create'])->name('visits.create')->middleware('auth');
    Route::post('/store', [VisitController::class, 'store'])->name('visits.store')->middleware('auth');
    Route::get('edit/{main_machine_type}/{machinesale}/{visit}', [VisitController::class, 'edit'])->name('visits.edit')->middleware('auth');
    Route::post('/update/{machinesale}/{visit}', [VisitController::class, 'update'])->name('visits.update')->middleware('auth');
    Route::delete('/destroy/{machinesale}', [VisitController::class, 'destroy'])->name('visits.destroy')->middleware('auth');
});

Route::group(['prefix' => 'parties'], function () {
    Route::get('/index/{main_machine_type}', [PartyController::class, 'index'])->name('parties.index')->middleware('auth');
    Route::get('/create/{main_machine_type}', [PartyController::class, 'create'])->name('parties.create')->middleware('auth');
    Route::post('/store', [PartyController::class, 'store'])->name('parties.store')->middleware('auth');
    Route::get('edit/{main_machine_type}/{party}', [PartyController::class, 'edit'])->name('parties.edit')->middleware('auth');
    Route::post('/update/{party}', [PartyController::class, 'update'])->name('parties.update')->middleware('auth');
    Route::delete('/destroy/{party}', [PartyController::class, 'destroy'])->name('parties.destroy')->middleware('auth');
    Route::post('partyCode', [PartyController::class, 'partyCode'])->name('parties.partyCode')->middleware('auth');
});

Route::group(['prefix' => 'complaints', 'middleware' => ['auth']], function () {
    Route::get('/index/{main_machine_type}', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/create/{main_machine_type}', [ComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/store', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/edit/{main_machine_type}/{complaint}', [ComplaintController::class, 'edit'])->name('complaints.edit');
    Route::post('/update/{complaint}', [ComplaintController::class, 'update'])->name('complaints.update');
    Route::delete('/destroy/{complaint}', [ComplaintController::class, 'destroy'])->name('complaints.destroy');
    Route::post('/add-complaint-item-part', [ComplaintController::class, 'AddComplaintItemPart'])->name('complaints.itemPartStore');
    Route::get('/report', [ComplaintController::class, 'report'])->name('complaints.report');
    Route::get('/data', [ComplaintController::class, 'data'])->name('complaints.data');
    Route::get('/view/{complaint}', [ComplaintController::class, 'view'])->name('complaints.view');
    Route::get('/pdf/{complaint}', [ComplaintController::class, 'downloadPDF'])->name('complaints.pdf');
});

Route::group(['prefix' => 'areas', 'middleware' => ['auth']], function () {
    Route::get('/index', [AreaController::class,   'index'])->name('areas.index');
    Route::get('/create', [AreaController::class, 'create'])->name('areas.create');
    Route::post('/store', [AreaController::class, 'store'])->name('areas.store');
    Route::get('/edit/{area}', [AreaController::class, 'edit'])->name('areas.edit');
    Route::post('/update/{area}', [AreaController::class, 'update'])->name('areas.update');
    Route::delete('/destroy/{area}', [AreaController::class, 'destroy'])->name('areas.destroy');
});

Route::group(['prefix' => 'owners', 'middleware' => ['auth']], function () {
    Route::get('/index', [UserController::class,   'ownerIndex'])->name('owners.index');
    Route::get('/create', [UserController::class, 'ownerCreate'])->name('owners.create');
    Route::post('/store', [UserController::class, 'ownerStore'])->name('owners.store');
    Route::get('/edit/{owner}', [UserController::class, 'ownerEdit'])->name('owners.edit');
    Route::post('/update/{owner}', [UserController::class, 'ownerUpdate'])->name('owners.update');
    Route::delete('/destroy/{owner}', [UserController::class, 'ownerDestroy'])->name('owners.destroy');
});

Route::group(['prefix' => 'contact-persons', 'middleware' => ['auth']], function () {
    Route::get('/index', [UserController::class,   'contactPersonIndex'])->name('contact_persons.index');
    Route::get('/create', [UserController::class, 'contactPersonCreate'])->name('contact_persons.create');
    Route::post('/store', [UserController::class, 'contactPersonStore'])->name('contact_persons.store');
    Route::get('/edit/{contact_person}', [UserController::class, 'contactPersonEdit'])->name('contact_persons.edit');
    Route::post('/update/{contact_person}', [UserController::class, 'contactPersonUpdate'])->name('contact_persons.update');
    Route::delete('/destroy/{contact_person}', [UserController::class, 'contactPersonDestroy'])->name('contact_persons.destroy');
});

Route::group(['prefix' => 'products', 'middleware' => ['auth']], function () {
    Route::get('/index/{main_machine_type}', [ProductController::class, 'index'])->name('products.index');
    Route::get('/create/{main_machine_type}', [ProductController::class, 'create'])->name('products.create');
    Route::post('/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/edit/{main_machine_type}/{product}', [ProductController::class, 'edit'])->name('products.edit');
    Route::post('/update/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/destroy/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    route::post('add-product-group', [ProductController::class, 'addProductGroup'])->name('products.addProductGroup');
});

Route::group(['prefix' => 'item-parts', 'middleware' => ['auth']], function () {
    Route::get('/index', [ItemPartsController::class, 'index'])->name('item-parts.index');
    Route::get('/create', [ItemPartsController::class, 'create'])->name('item-parts.create');
    Route::post('/store', [ItemPartsController::class, 'store'])->name('item-parts.store');
    Route::get('/edit/{item_part}', [ItemPartsController::class, 'edit'])->name('item-parts.edit');
    Route::post('/update/{item_part}', [ItemPartsController::class, 'update'])->name('item-parts.update');
    Route::delete('/destroy/{item_part}', [ItemPartsController::class, 'destroy'])->name('item-parts.destroy');
});

Route::group(['prefix' => 'complaint-types', 'middleware' => ['auth']], function () {
    Route::get('/index/{main_machine_type}', [ComplaintTypeController::class, 'index'])->name('complaint-types.index');
    Route::get('/create/{main_machine_type}', [ComplaintTypeController::class, 'create'])->name('complaint-types.create');
    Route::post('/store', [ComplaintTypeController::class, 'store'])->name('complaint-types.store');
    Route::get('/edit/{main_machine_type}/{complaint_type}', [ComplaintTypeController::class, 'edit'])->name('complaint-types.edit');
    Route::post('/update/{complaint_type}', [ComplaintTypeController::class, 'update'])->name('complaint-types.update');
    Route::delete('/destroy/{complaint_type}', [ComplaintTypeController::class, 'destroy'])->name('complaint-types.destroy');
});

Route::group(['prefix' => 'product-groups', 'middleware' => ['auth']], function () {
    Route::get('/index', [ProductController::class, 'productGroupIndex'])->name('product-groups.index');
    Route::get('/create', [ProductController::class, 'productGroupCreate'])->name('product-groups.create');
    Route::post('/store', [ProductController::class, 'addProductGroup'])->name('product-groups.store');
    Route::get('/edit/{product_group}', [ProductController::class, 'productGroupEdit'])->name('product-groups.edit');
    Route::post('/update/{product_group}', [ProductController::class, 'productGroupUpdate'])->name('product-groups.update');
    Route::delete('/destroy/{product_group}', [ProductController::class, 'productGroupDestroy'])->name('product-groups.destroy');
});

Route::group(['prefix' => 'service-types', 'middleware' => ['auth']], function () {
    Route::get('/index', [ServiceController::class, 'index'])->name('service-types.index');
    Route::get('/create', [ServiceController::class, 'create'])->name('service-types.create');
    Route::post('/store', [ServiceController::class, 'store'])->name('service-types.store');
    Route::get('/edit/{service_type}', [ServiceController::class, 'edit'])->name('service-types.edit');
    Route::post('/update/{service_type}', [ServiceController::class, 'update'])->name('service-types.update');
    Route::delete('/destroy/{service_type}', [ServiceController::class, 'destroy'])->name('service-types.destroy');
});

Route::group(['prefix' => 'parts_inventory', 'middleware' => ['auth']], function () {
    Route::get('/index', [PartsInventoryController::class, 'index'])->name('parts_inventory.index');
    Route::get('/create', [PartsInventoryController::class, 'create'])->name('parts_inventory.create');
    Route::post('/store', [PartsInventoryController::class, 'store'])->name('parts_inventory.store');
    Route::get('/edit/{parts_inventory}', [PartsInventoryController::class, 'edit'])->name('parts_inventory.edit');
    Route::post('/update/{parts_inventory}', [PartsInventoryController::class, 'update'])->name('parts_inventory.update');
    Route::post('/repairout/update', [PartsInventoryController::class, 'repairOutUpdate'])->name('parts_inventory.repairout.update');
    Route::post('/repairin/update', [PartsInventoryController::class, 'repairInUpdate'])->name('parts_inventory.repairin.update');
    Route::post('/issuetoparty/update', [PartsInventoryController::class, 'issueToPartyUpdate'])->name('parts_inventory.issuetoparty.update');
    Route::delete('/destroy/{parts_inventory}', [PartsInventoryController::class, 'destroy'])->name('parts_inventory.destroy');
    Route::get('/pdf/{parts_inventory}/{repair_status}', [PartsInventoryController::class, 'downloadPDF'])->name('parts_inventory.pdf');
});

Route::group(['prefix' => 'holiday', 'middleware' => ['auth']], function () {
    Route::get('/index', [HolidayController::class, 'index'])->name('holiday.index');
    Route::get('/create', [HolidayController::class, 'create'])->name('holiday.create');
    Route::post('/store', [HolidayController::class, 'store'])->name('holiday.store');
    Route::get('/edit/{holiday}', [HolidayController::class, 'edit'])->name('holiday.edit');
    Route::post('/update/{holiday}', [HolidayController::class, 'update'])->name('holiday.update');
    Route::delete('/destroy/{holiday}', [HolidayController::class, 'destroy'])->name('holiday.destroy');
});

Route::group(['prefix' => 'leave', 'middleware' => ['auth']], function () {
    Route::get('/index', [LeaveController::class, 'index'])->name('leave.index');
    Route::get('/create', [LeaveController::class, 'create'])->name('leave.create');
    Route::post('/store', [LeaveController::class, 'store'])->name('leave.store');
    Route::get('/edit/{leave}', [LeaveController::class, 'edit'])->name('leave.edit');
    Route::post('/update/{leave}', [LeaveController::class, 'update'])->name('leave.update');
    Route::delete('/destroy/{leave}', [LeaveController::class, 'destroy'])->name('leave.destroy');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/report-today', [DashboardController::class, 'reportToday'])->name('report.today');
    Route::post('/report-today/date', [DashboardController::class, 'reportToday'])->name('report.selected-date');
    Route::post('/date-wise-present-engineer', [DashboardController::class, 'todayPresentEngineer'])->name('report.date-wise-present-engineer');
    Route::get('/free-engineers', [DashboardController::class, 'freeEngineers'])->name('free-engineers');
    Route::get('/engineer-last-visit', [DashboardController::class, 'engineerLastVisit'])->name('engineer-last-visit');
    Route::get('/report-engineer-visit', [ReportController::class, 'reportEngineerVisit'])->name('report.engineervisit');
    Route::get('/report-eng-done-summary', [ReportController::class, 'reportEngDoneSummary'])->name('report.eng-done-summary');
    Route::get('/report-engineer-performance', [ReportController::class, 'reporEngineerPerformance'])->name('report.engineer-performance');
    Route::get('/report-complaint-pending', [ReportController::class, 'reportComplaintPending'])->name('report.report-complaint-pending');
    Route::get('/report-complaint', [ReportController::class, 'reportComplaint'])->name('report.report-complaint');
    Route::get('/free-service-report', [ReportController::class, 'freeServiceReport'])->name('report.free-service-report');
    Route::get('/machine-salse-report', [ReportController::class, 'machineSalseReport'])->name('report.machine-salse-report');
    Route::get('/salse-lead-report/{main_machine_type}', [ReportController::class, 'salseLeadReport'])->name('salse-lead-report');
    Route::get('/export-engineer-complaints', [ReportController::class, 'exportEngineerComplaints'])->name('report.export-engineer-complaints');
    Route::get('/export-complaints', [ReportController::class, 'exportComplaints'])->name('report.export-complaints');
    Route::get('/export-free-service', [ReportController::class, 'exportFreeService'])->name('report.export-free-service');
    Route::get('/export-machine-salse', [ReportController::class, 'exportMachineSalse'])->name('report.export-machine-salse');
    Route::get('/todo-report', [ReportController::class, 'reportTodo'])->name('report.todo-report');
    Route::get('/sales-report', [ReportController::class, 'reportSales'])->name('report.sales-report');
    Route::get('/report-complainttype', [ReportController::class, 'complaintTypeReport'])->name('report.complainttype');
});

route::get('party-products', [ComplaintController::class, 'partyProducts'])->name('party-products')->middleware('auth');
Route::get('sales-entry-details', [ComplaintController::class, 'machineEntry'])->name('sales-entry-details')->middleware('auth');
Route::get('getmachine-type', [ComplaintController::class, 'machineCType'])->name('getmachine-type')->middleware('auth');
Route::get('getcomplaint-types/{main_machine_type}', [ComplaintController::class, 'getcomplaintType'])->name('getcomplaint-types')->middleware('auth');
Route::get('reassign-complaint/{id}', [ComplaintController::class, 'reassignComplaint'])->name('reassign-complaint')->middleware('auth');
Route::get('importData', [ImportDataController::class, 'importData'])->name('importData')->middleware('auth');
Route::post('/store', [ImportDataController::class, 'store'])->name('importData.store')->middleware('auth');
Route::get('finalApp', [ImportDataController::class, 'finalApp'])->name('finalApp')->middleware('auth');
Route::post('/finalAppPost', [ImportDataController::class, 'finalAppPost'])->name('importData.finalAppPost')->middleware('auth');
Route::post('/product', [ImportDataController::class, 'product'])->name('importData.product')->middleware('auth');
Route::post('/machine-sales', [ImportDataController::class, 'machineSales'])->name('importData.machine-sales')->middleware('auth');
Route::post('/contactPerson', [ImportDataController::class, 'contactPerson'])->name('importData.contactPerson')->middleware('auth');
Route::get('/customer-feedback', [ComplaintController::class, 'customerFeedback'])->name('customer-feedback')->middleware('auth');
Route::get('export-party/{main_machine_type}', [PartyController::class, 'exportParty'])->name('export-party')->middleware('auth');
Route::get('export-machine-sales-expiry', [MachineSaleEntryController::class, 'exportMachineSalesExpiry'])->name('export-machine-sales-expiry')->middleware('auth');
Route::get('export-complaint-pending', [ComplaintController::class, 'exportComplaintPending'])->name('export-complaint-pending')->middleware('auth');
Route::get('export-complaint-status', [ComplaintController::class, 'exportComplaintStatus'])->name('export-complaint-status')->middleware('auth');
Route::get('export-complaint-type-summary', [ComplaintController::class, 'exportComplaintTypeSummary'])->name('export-complaint-type-summary')->middleware('auth');
Route::get('export-engineer-done-summary', [ComplaintController::class, 'exportEngineerDoneSummary'])->name('export-engineer-done-summary')->middleware('auth');
Route::get('export-engineer-performance', [ComplaintController::class, 'exportEngineerPerformance'])->name('export-engineer-performance')->middleware('auth');
Route::get('export-customer-feedback', [ComplaintController::class, 'exportCustomerFeedback'])->name('export-customer-feedback')->middleware('auth');
Route::get('export-sales-lead', [ReportController::class, 'exportSalesLead'])->name('export-sales-lead')->middleware('auth');
Route::get('export-sales-summary', [ReportController::class, 'exportSalesSummary'])->name('export-sales-summary')->middleware('auth');
Route::get('export-todo', [ReportController::class, 'exportTodo'])->name('export-todo')->middleware('auth');
Route::get('export-ap-today/{main_machine_type}', [ComplaintController::class, 'exportAPToday'])->name('export-ap-today')->middleware('auth');
Route::get('export-ap-summary/{main_machine_type}', [ComplaintController::class, 'exportAPSummary'])->name('export-ap-summary')->middleware('auth');
Route::get('export-engineer-ap-summary/{month}/{engineer_id}', [ComplaintController::class, 'exportEngineerAPSummary'])->name('export-engineer-ap-summary')->middleware('auth');
Route::get('/generate-salaries/{main_machine_type}', [ComplaintController::class, 'generateSalaries'])->name('generate-salaries')->middleware('auth');
Route::get('/generate-salesalaries/{main_machine_type}', [ComplaintController::class, 'generateSaleSalaries'])->name('generate-salesalaries')->middleware('auth');
Route::post('/process-salaries', [ComplaintController::class, 'processSalaries'])->name('process-salaries')->middleware('auth');
Route::get('/download-salesalaryslip/{main_machine_type}/{id}', [ComplaintController::class, 'downloadSalarySlip'])->name('download-salesalaryslip')->middleware('auth');

require __DIR__ . '/auth.php';

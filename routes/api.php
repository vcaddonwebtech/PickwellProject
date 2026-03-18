<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Api\MachineSaleEntryController;
use App\Http\Controllers\Api\AttendanceDetailController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\LeaveController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\PartyController;
use App\Http\Controllers\Api\PriorityController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\SalesLeadReportController;
use App\Http\Controllers\Api\ServiceTypeController;
use App\Http\Controllers\Api\SalesPersonController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\TodoController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AdvertisementController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\OnspotInquiriesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Events\LocationUpdated;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::post('/update-location', function (Request $request) {
//     event(new LocationUpdated(
//         $request->user_id,
//         $request->latitude,
//         $request->longitude
//     ));
//     return response()->json(['status' => 'OK', 'user_id' => $request->user_id, 'lat' => $request->latitude, 'lng' => $request->longitude]);
// });



Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('change-password', [AuthController::class, 'changePassword']);
        Route::post('device-token', [AuthController::class, 'deviceToken']);
        Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'user']);
        Route::get('/engineer-dashboard', [DashboardController::class, 'dashboardApi']);
        Route::get('/sales-dashboard', [DashboardController::class, 'salesDashboardApi']);
        Route::get('/engineer-complaints', [AuthController::class, 'engineerComplaints']);
        Route::get('/manager-complaints', [AuthController::class, 'managerComplaints']);
        Route::get('/manager-notassigned-complaints', [AuthController::class, 'managerNotAssignedComplaints']);
        Route::get('/installation-complaints', [AuthController::class, 'installationComplaints']);
        Route::get('/get-complaint/{id}', [AuthController::class, 'complaint']);
        Route::post('/engineer-in/{id}', [AuthController::class, 'EngineerInComplaint']);
        Route::post('/engineer-out/{id}', [AuthController::class, 'EngineerOutComplaint']);
        Route::get('/complaints', [AuthController::class, 'getComplaints']);
        Route::post('/accept-complaint', [ComplaintController::class, 'acceptComplaint']);
        Route::get('/isengin', [AuthController::class, 'isEngIn']);
        Route::get('engineers', [AuthController::class, 'engineers']);
        Route::post('adminDashboard', [DashboardController::class, 'statesAdmin']);
        Route::get('manager-machine-list', [DashboardController::class, 'managerMachineList']);
        Route::get('party-machine-list', [DashboardController::class, 'partyMachineList']);
        Route::get('party-firms', [PartyController::class, 'partyFirms']);
        Route::get('visit-steps', [MachineSaleEntryController::class, 'visitSteps']);
        Route::get('leader-machine-list', [DashboardController::class, 'leaderMachineList']);
        Route::post('managerDashboard', [DashboardController::class, 'statesManager']);
        Route::post('manager-installation-dashboard', [DashboardController::class, 'installationStatesManager']);
        Route::post('manager-installation-complaint-dashboard', [DashboardController::class, 'installationComplaintStatesManager']);
        Route::post('leaderDashboard', [DashboardController::class, 'statesLeader']);
        Route::post('assign-engineer', [AuthController::class, 'assignEngineer']);
        Route::post('employee-checkin', [AuthController::class, 'employeeClockIn']);
        Route::post('employee-checkout/{id}', [AuthController::class, 'employeeClockOut']);
        Route::post('check-holiday', [AuthController::class, 'checkHoliday']);
        Route::post('/machine-number', [MachineSaleEntryController::class, 'machineNumber']);
        Route::get('/months', [AuthController::class, 'months']);
        Route::get('/holiday-list', [AuthController::class, 'holidayList']);
        Route::post('attendance-store', [AttendanceDetailController::class, 'store']);
        Route::post('attendance-update', [AttendanceDetailController::class, 'update']);
        Route::post('attendance-today', [AttendanceDetailController::class, 'todayAttendance']);
        Route::get('complaint-number', [ComplaintController::class, 'complaintNumber']);
        Route::post('complaint-store', [ComplaintController::class, 'store']);
        Route::post('customer-complaint-store', [ComplaintController::class, 'CustomerComplainstore']);
        Route::post('customer-complaint-update-status/{id}', [ComplaintController::class, 'CustomerComplainUpdateStatus']);
        Route::get('customer-complaints/{id}', [ComplaintController::class, 'customerComplaints']);
        Route::get('customer-machines/{id}/{machine_no?}', [MachineSaleEntryController::class, 'customerMachines']);
        Route::get('machines-installationlist', [MachineSaleEntryController::class, 'machineInstallationlist']);
        Route::get('party-machines-installationlist', [MachineSaleEntryController::class, 'partyMachineInstallationlist']);
        Route::get('getmachine-installation-byid/{machinesale}', [MachineSaleEntryController::class, 'getMachineInsbyId']);
        Route::post('update-machine-installation-byid/{machinesale}', [MachineSaleEntryController::class, 'updateMachineInsbyId']);
        Route::post('store-machine-installation', [MachineSaleEntryController::class, 'storeMachineIns']);
        Route::post('store-machine-installation-withparty-firms', [MachineSaleEntryController::class, 'storeMachineInsWithPartyFirm']);
        Route::get('machine-installationvisitslist/{machinesale}', [MachineSaleEntryController::class, 'machineInsVisitlist']);
        Route::post('create-machine-installation-visit/{machinesale}', [MachineSaleEntryController::class, 'CreateMachineInsVisit']);
        Route::post('update-machine-installation-visit/{machinesale}/{vid}', [MachineSaleEntryController::class, 'UpdateMachineInsVisit']);
        Route::get('machine-expiry/{partyId}/{is_expired?}', [MachineSaleEntryController::class, 'machineExpiry']);
        Route::get('dashboard-complain-counter/{id}', [ComplaintController::class, 'dashboardComplaintCounter']);
        Route::get('party-instalation-counter/{id}', [ComplaintController::class, 'partyInstallationCounter']);
        Route::get('engineer-attendance-by-date/{date?}', [AttendanceDetailController::class, 'engineerAttendanceByDate']);
        Route::get('servicetl-attendance-by-date/{date?}', [AttendanceDetailController::class, 'servicetlAttendanceByDate']);
        Route::get('attendance-mapreport', [AttendanceDetailController::class, 'attendanceonMap']);
        Route::post('attendance-half-day/{id}', [AttendanceDetailController::class, 'attendanceHalfDay']);
        Route::get('attendance-report', [AttendanceDetailController::class, 'attendanceReport']);
        Route::get('previous-complaints-report/{id}/{sales_id}', [ComplaintController::class, 'previousComplaintsReport']);
        Route::apiResource('party', PartyController::class);
        Route::get('party-detail-by-partycode/{id}', [PartyController::class, 'partyDetailByPartyCode']);
        Route::get('machine-detail-by-part-id/{id}', [PartyController::class, 'machineDetailByPartId']);
        Route::apiResource('service-type', ServiceTypeController::class);
        Route::apiResource('sales-person', SalesPersonController::class);
        Route::get('sales-person-user-priority-filter/{saleAssign_user_id?}/{priority_id?}', [SalesPersonController::class, 'getAssignsalsePersonPriorityFilterDetail']);
        Route::get('assign-salse-person-priority-filter', [SalesPersonController::class, 'assignSalsePersonPriorityFilter']);
        Route::get('assign-salse-person-favorite', [SalesPersonController::class, 'assignSalsePersonFavorite']);
        Route::POST('sales-person/favorite', [SalesPersonController::class, 'updateFavorite']);
        Route::POST('sales-person/in-out', [SalesPersonController::class, 'updateSalesPerson']);

        Route::get('salesperson-inquiries', [OnspotInquiriesController::class, 'onspotSPInquiries']);
        Route::POST('store-salesperson-inquiry', [OnspotInquiriesController::class, 'store']);

        Route::get('/areas', [AuthController::class, 'areas']);
        Route::get('/lead-stage', [AuthController::class, 'leadStage']);
        Route::get('/products', [AuthController::class, 'products']);
        Route::get('/products-type-salse', [AuthController::class, 'productsTypeSalse']);
        Route::get('/sales-person-all', [AuthController::class, 'salesPerson']);

        Route::apiResource('notification', NotificationController::class);
        Route::get('sales-lead/group-wise-sales-lead', [SalesLeadReportController::class, 'groupWiseSalesLead']);
        Route::get('sales-lead/user-wise-sales-lead', [SalesLeadReportController::class, 'userWiseSalesLead']);
        Route::apiResource('sales-lead', SalesLeadReportController::class);
        Route::apiResource('todos', TodoController::class);
        Route::get('todo/filter', [TodoController::class, 'todosFilter']);
        Route::get('todo/assign-users-list', [TodoController::class, 'assignUsersList']);
        Route::POST('todos/add-comment', [TodoController::class, 'addNewComment']);
        Route::POST('todo/update-status/{id}', [TodoController::class, 'updateTodoStatus']);
        Route::get('admin-todos/{userId?}/{priorityId?}', [TodoController::class, 'getAdminTodos']);
        Route::get('get-assign-users-details/{assign_user_id?}/{priorityId?}', [TodoController::class, 'getAssignUserDetail']);
        Route::get('get-assign-users-filter-details/{assign_user_id?}', [TodoController::class, 'getAssignUserFilterDetail']);
        Route::get('get-assign-users-priority-details/{assign_user_id?}/{priority_id?}', [TodoController::class, 'getAssignUserPriorityFilterDetail']);
        Route::POST('todos/favorite', [TodoController::class, 'updateFavorite']);

        Route::apiResource('priorities', PriorityController::class);
        //Route::apiResource('users', UserController::class);
        Route::get('/users', [UserController::class, 'index']);
        Route::post('getuser-salarydata', [UserController::class, 'userSalarydata']);
        

        Route::apiResource('leaves', LeaveController::class);
        Route::get('leaves-accept-reject/{id}', [LeaveController::class, 'approveLeave']);

        Route::get('/parties', [AuthController::class, 'parties']);
        Route::get('/complaint-no', [ComplaintController::class, 'getComplaintNo']);

        Route::get('report-today/{date?}', [ReportController::class, 'reportToday']);
        Route::get('report-sales-summary', [ReportController::class, 'reportSalesSummary']);

        Route::post('/update-location', [LocationController::class, 'store']);
        Route::get('/engineer-live-location', [LocationController::class, 'getEngLivelocation']);

        Route::post('ap-details', [AttendanceDetailController::class, 'apDetails']);
        Route::post('ap-details-report', [AttendanceDetailController::class, 'apDetailsReport']);
        Route::post('today-expiry', [ReportController::class, 'todayExpiry']);
        Route::get('free-engineers', [ReportController::class, 'freeEngineers']);
        Route::get('tlwisefree-engineers', [ReportController::class, 'tlwiseFreeEngineers']);
        Route::apiResource('roles', RoleController::class);
        Route::get('role-wise-users', [UserController::class, 'roleWiseUsers']);

        Route::apiResource('teams', TeamController::class);
        Route::post('role-wise-group', [TeamController::class, 'roleWiseGroup']);

        Route::post('advertisement/store', [AdvertisementController::class, 'store']);
        Route::post('check-advertisement', [AdvertisementController::class, 'checkAdvertisement']);

        Route::post('complaint-done', [ComplaintController::class, 'complaintDone']);
        Route::post('customer-feedback', [AuthController::class, 'customerFeedback']);

        Route::post('group-user', [UserController::class, 'groupUser']);
        // Route::post('group-details', [TeamController::class, 'groupDetails']);
        Route::post('group-details', [TodoController::class, 'groupDetails']);
        Route::post('group-todo', [TeamController::class, 'groupTodo']);
        Route::post('user_dp', [UserController::class, 'userDp']);
        Route::post('update-user-profile', [UserController::class, 'updateUserProfile']);
    });
    Route::get('/complaint-types', [AuthController::class, 'complaintTypes']);
    Route::get('/statuses', [AuthController::class, 'statuses']);
    Route::get('/notifylocationoff', [ComplaintController::class, 'notifyLocationOff']);
});

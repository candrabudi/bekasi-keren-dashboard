<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CallCenterRecordController;
use App\Http\Controllers\CrawlCallCenterController;
use App\Http\Controllers\DashboardCallCenterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ReportDepartmentController;
use App\Http\Controllers\RoleAccessController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\WallboardCallCenterController;
use Illuminate\Support\Facades\Route;

Route::get('/crawl/call-center/today-incidents', [CrawlCallCenterController::class, 'crawlTodayIncidents']);
Route::get('/crawl/call-center/call-detail-record', [CrawlCallCenterController::class, 'crawlCallDetailRecord']);
Route::get('/crawl/call-center/call-departments', [CrawlCallCenterController::class, 'updateReportDepartment']);

Route::get('/', [AuthController::class, 'index']);
Route::get('/backstreet/login', [AuthController::class, 'index'])->name('backstreet.login');
Route::post('/backstreet/login/process', [AuthController::class, 'loginProcess'])->name('backstreet.login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/backstreet/call-center/reports/departments', [ReportDepartmentController::class, 'index']);
Route::get('/updateReportCrd', [DashboardController::class, 'updateReportCrd']);
Route::middleware(['auth'])->group(function () {

    Route::get('/backstreet/wallboards/call-center', [DashboardCallCenterController::class, 'wallboardCallCenter'])->name('backstreet.wallboards.callcenter');
    Route::get('/backstreet/dashboards/call-center', [DashboardController::class, 'dashboardCallCenter'])->name('backstreet.dashboards.callcenter');
    
    Route::get('/backstreet/wallboard/get-summary-all', [WallboardCallCenterController::class, 'wallBoardGetSummaryCall']);
    Route::get('/backstreet/wallboard/get-summary-insident', [WallboardCallCenterController::class, 'getSummaryInsiden']);
    Route::get('/backstreet/wallboard/get-top-categories', [WallboardCallCenterController::class, 'getTopCategories']);
    Route::get('/backstreet/wallboard/get-online-department', [WallboardCallCenterController::class, 'getOnlineDepartment']);
    Route::get('/backstreet/wallboard/get-top-area', [WallboardCallCenterController::class, 'getTopArea']);
    Route::get('/dashboard/chart/tickets-per-hour', [DashboardController::class, 'chartDataPerJam'])->name('dashboard.chart.tickets-per-hour');
    Route::get('/dashboard/call-status-chart-data', [DashboardController::class, 'callStatusChartData']);
    Route::get('/tickets/by-district', [TicketController::class, 'getByDistrict']);

    Route::get('/updateTickets', [TicketController::class, 'updateTickets'])->name('backstreet.tickets.updateTickets');
    Route::get('/backstreet/call-center/insidents', [TicketController::class, 'index'])->name('backstreet.tickets.index');
    Route::get('/backstreet/call-center/insidents/{a}', [TicketController::class, 'detail'])->name('backstreet.tickets.detail');
    Route::get('/backstreet/get-villages', [TicketController::class, 'getVillages'])->name('get.villages');

    Route::get('/backstreet/call-center/call-records', [CallCenterRecordController::class, 'index'])->name('backstreet.callrecords.index');

    Route::get('/backstreet/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::post('/backstreet/menus/store', [MenuController::class, 'store'])->name('menus.store');
    Route::post('/backstreet/menus/update/{id}', [MenuController::class, 'update'])->name('menus.update');
    Route::post('/backstreet/menus/delete/{id}', [MenuController::class, 'destroy'])->name('menus.destroy');

    Route::get('/backstreet/user-managements/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/backstreet/user-managements/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/backstreet/user-managements/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/backstreet/user-managements/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/backstreet/user-managements/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/backstreet/user-managements/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/backstreet/user-managements/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    Route::get('/backstreet/user-managements/users', [UserManagementController::class, 'index'])->name('backstreet.users.index');
    Route::get('/backstreet/user-managements/users/create', [UserManagementController::class, 'create'])->name('backstreet.users.create');
    Route::post('/backstreet/user-managements/users/store', [UserManagementController::class, 'store'])->name('backstreet.users.store');
    Route::get('/backstreet/user-managements/users/{a}/edit', [UserManagementController::class, 'edit'])->name('backstreet.users.edit');
    Route::put('/backstreet/user-managements/users/{a}/update', [UserManagementController::class, 'update'])->name('backstreet.users.update');
    Route::delete('/backstreet/user-managements/users/{a}/delete', [UserManagementController::class, 'destroy'])->name('backstreet.users.destroy');

    Route::get('/backstreet/user-managements/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::get('/backstreet/user-managements/menus/create', [MenuController::class, 'create'])->name('menus.create');
    Route::post('/backstreet/user-managements/menus', [MenuController::class, 'store'])->name('menus.store');
    Route::get('/backstreet/user-managements/menus/{menu}', [MenuController::class, 'show'])->name('menus.show');
    Route::get('/backstreet/user-managements/menus/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
    Route::put('/backstreet/user-managements/menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
    Route::delete('/backstreet/user-managements/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');

    Route::get('/roles/{role}/access', [RoleAccessController::class, 'edit'])
        ->name('roles.access');

    Route::post('/roles/{role}/access', [RoleAccessController::class, 'update'])
        ->name('roles.access.update');
    // Route::get('/roles/{role}/access', [RoleAccessController::class, 'edit'])
    //     ->name('roles.access')
    //     ->middleware(['check.menu.permission:Roles,read']);

    // Route::post('/roles/{role}/access', [RoleAccessController::class, 'update'])
    //     ->name('roles.access.update')
    //     ->middleware(['check.menu.permission:Roles,read']);
});

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CallCenterController;
use App\Http\Controllers\CallCenterRecordController;
use App\Http\Controllers\CrawlCallCenterController;
use App\Http\Controllers\DashboardCallCenterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ReportDepartmentController;
use App\Http\Controllers\RoleAccessController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TicketCategoryController;
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

    Route::get('/backstreet/get-villages', action: [TicketController::class, 'getVillages'])->name('get.villages');


    Route::get('/backstreet/wallboards/call-center', [DashboardCallCenterController::class, 'wallboardCallCenter'])->name('backstreet.wallboards.callcenter');
    Route::get('/backstreet/call-center/maps', [CallCenterController::class, 'map'])->name('backstreet.callcenter.map');
    Route::get('/backstreet/call-center/ticket-distributions', [CallCenterController::class, 'ticketDistribution'])->name('backstreet.callcenter.map');

    Route::prefix('backstreet/dashboards/call-center')
        ->controller(DashboardCallCenterController::class)
        ->name('backstreet.dashboards.callcenter.')
        ->group(function () {
            Route::get('/', 'dashboardCallCenter')->name('index');
            Route::get('/count-reports', 'countReports')->name('countreports');
            Route::get('/ticket-categories', 'ticketCategories')->name('ticketcategories');
            Route::get('/ticket-districts', 'ticketDistricts')->name('ticketdistricts');
            Route::get('/ticket-subdistricts', 'ticketSubDistricts')->name('ticketsubdistricts');
            Route::get('/chart-insident-hours', 'chatDataHours')->name('chartinsidenthours');
            Route::get('/chart-call-status', 'callStatusChartData')->name('chartcallstatus');
            Route::get('/ticket-distributions', 'ticketDistribution')->name('ticketdistributions');
        });

    Route::prefix('/backstreet/wallboard')->controller(WallboardCallCenterController::class)->group(function () {
        Route::get('/get-summary-all', 'wallBoardGetSummaryCall');
        Route::get('/get-summary-insident', 'getSummaryInsiden');
        Route::get('/get-top-categories', 'getTopCategories');
        Route::get('/get-online-department', 'getOnlineDepartment');
        Route::get('/get-top-area', 'getTopArea');
    });



    Route::get('/dashboard/chart/tickets-per-hour', [DashboardController::class, 'chartDataPerJam'])->name('dashboard.chart.tickets-per-hour');
    Route::get('/dashboard/call-status-chart-data', [DashboardController::class, 'callStatusChartData']);
    Route::get('/tickets/by-district', [TicketController::class, 'getByDistrict']);

    Route::get('/updateTickets', [TicketController::class, 'updateTickets'])->name('backstreet.tickets.updateTickets');

    Route::prefix('/backstreet/call-center')->group(function () {
        Route::controller(TicketController::class)->group(function () {
            Route::get('/insidents', 'index')->name('backstreet.tickets.index');
            Route::get('/insidents/{a}', 'detail')->name('backstreet.tickets.detail');
        });

        Route::controller(TicketCategoryController::class)->group(function () {
            Route::get('/categories', 'index')->name('backstreet.tickets.categories.index');
            Route::get('/categories/data', 'getData')->name('backstreet.tickets.categories.data');
        });

        Route::controller(CallCenterRecordController::class)->group(function () {
            Route::get('/call-records', 'index')->name('backstreet.callrecords.index');
        });
    });


    Route::prefix('/backstreet/user-managements')->group(function () {
        Route::controller(RoleController::class)->prefix('roles')->name('roles.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{role}', 'show')->name('show');
            Route::get('/{role}/edit', 'edit')->name('edit');
            Route::put('/{role}', 'update')->name('update');
            Route::delete('/{role}', 'destroy')->name('destroy');
        });

        Route::controller(UserManagementController::class)->prefix('users')->name('backstreet.users.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/{a}/edit', 'edit')->name('edit');
            Route::put('/{a}/update', 'update')->name('update');
            Route::delete('/{a}/delete', 'destroy')->name('destroy');
        });

        Route::controller(MenuController::class)->prefix('menus')->name('menus.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{menu}', 'show')->name('show');
            Route::get('/{menu}/edit', 'edit')->name('edit');
            Route::put('/{menu}', 'update')->name('update');
            Route::delete('/{menu}', 'destroy')->name('destroy');
        });
    });


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

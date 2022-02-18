<?php

use App\Http\Controllers\V1\Promo\PromosController;
use App\Models\User;
use App\Services\Merchant\Reports\IReportsService;
use App\Services\Merchant\Reports\ReportsService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/pos-summary-report', function () {

    /** @var ReportsService $service */
    $service = App::make(IReportsService::class);
    return view('reports.merchants.pos.pos-summary-report', ['report' => $service->posSummaryReport(User::query()->find(1), '2021-01-01','2022-02-06')]);

});



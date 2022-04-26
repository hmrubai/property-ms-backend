<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
    
Route::get('/test', 'CommonController@home');

Route::post('login', 'JwtAuthController@login');
Route::post('register', 'JwtAuthController@register');

Route::group(['middleware' => 'jwt.verify'], function () 
{
    Route::post('logout', 'JwtAuthController@logout');
    Route::get('user-info', 'JwtAuthController@get_user');

    Route::get('get-modarator-list', 'CommonController@modaratorList');
    
    Route::get('dashboard-report', 'ReportController@ReporCount');
    Route::get('report-list', 'ReportController@conditionReporList');
    Route::get('pending-report-list', 'ReportController@pendingConditionReporList');
    Route::get('completed-report-list', 'ReportController@completedConditionReporList');
    Route::get('generate-report-no', 'ReportController@generateReportNo');
    Route::post('save-cr-first-stage', 'ReportController@saveReport');
    Route::post('update-cr-second-stage', 'ReportController@updateCRReport');

    Route::get('report-details-by-id/{report_id}', 'ReportController@reportDetailsById');

});
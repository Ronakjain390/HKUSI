<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\AdminController;

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

Route::post('login', [AdminController::class, 'login']);
Route::get('app-version', [AdminController::class, 'getAppVersion']);
Route::post('forgot-password', [AdminController::class, 'forgotPassword']);
Route::post('reset-password', [AdminController::class, 'resetPassword']);
Route::group(['middleware' => ['auth:sanctum', 'check_admin']], function () {
	
	Route::get('profile', [AdminController::class, 'profile']);
	Route::post('change-password', [AdminController::class, 'changePassword']);
	Route::post('update-settings', [AdminController::class, 'updateSettings']);
	Route::post('logout', [AdminController::class, 'logout']);

	Route::get('scan-activity-qr', [AdminController::class, 'scanActivityQr']);
	Route::post('activity-check-in', [AdminController::class, 'activityCheckIn']);
	Route::get('event-bookings', [AdminController::class, 'eventBookings']);
	Route::get('event-booking-details', [AdminController::class, 'eventBookingDetails']);
	
	Route::get('scan-hall-qr', [AdminController::class, 'scanHallQr']);
	Route::post('hall-check-in', [AdminController::class, 'hallCheckIn']);
	Route::post('hall-check-out', [AdminController::class, 'hallCheckOut']);
	Route::get('hall-records', [AdminController::class, 'hallRecords']);
	Route::get('hall-booking-details', [AdminController::class, 'hallBookingDetails']);
	Route::get('notification/{type}', [AdminController::class, 'getNotification']);
	Route::post('notification', [AdminController::class, 'updateNotification']);
	
});

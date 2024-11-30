<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\V1\UserController;
use App\Http\Controllers\API\V1\AdminController;
use App\Http\Controllers\API\V1\AccommodationController;
use App\Http\Controllers\API\V1\HomeController;
use App\Http\Controllers\API\V1\EventController;
use App\Http\Controllers\API\V1\HotelController;

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

Route::group(['prefix' => 'registration'], function () {
	Route::post('login', [UserController::class, 'loginUser']);
	Route::post('register', [UserController::class, 'createMember']);
	Route::post('forgot-password', [UserController::class, 'forgotPassword']);
	Route::post('reset-password', [UserController::class, 'resetPassword']);
	Route::get('getcountry', [UserController::class, 'countryList']);
	Route::group(['middleware' => ['auth:sanctum', 'check_member']], function () {
		Route::post('change-password', [UserController::class, 'changePassword']);
		Route::get('logout', [UserController::class, 'logout']);
	});
});

Route::group(['middleware' => ['auth:sanctum', 'check_member']], function () {
	Route::group(['prefix' => 'member'], function () {
		Route::get('get-profile', [UserController::class, 'getMemberProfile']);
		Route::post('update-profile', [UserController::class, 'updateMemberProfile']);
		Route::post('updateProfileImage', [UserController::class, 'updateProfileImage']);
		Route::post('update-settings', [UserController::class, 'updateSettings']);
		Route::get('get-card', [UserController::class, 'getProfileCard']);
		Route::get('notification/{type}', [UserController::class, 'getNotification']);
		Route::post('notification', [UserController::class, 'updateNotification']);
		Route::get('my-accomandation', [AccommodationController::class, 'myAccomandation']);
		Route::get('get-booking-details', [AccommodationController::class, 'getBookingDetails']);
	});
	
	Route::group(['prefix' => 'accommodation'], function () {
		Route::get('get-program-list', [AccommodationController::class, 'getProgram']);
		Route::post('book-accommodation', [AccommodationController::class, 'bookAccommodation']);
		Route::get('get-payment-data', [AccommodationController::class, 'getPaymentDetails']);
		Route::post('update-payment-data', [AccommodationController::class, 'updatePaymentData']);
		Route::post('book-event', [AccommodationController::class, 'bookProgrammeEvent']);
		Route::post('update-payment-status', [AccommodationController::class, 'updatePaymentStatus']);
	});
});

Route::group(['prefix' => 'event'], function () {
	Route::get('get-event-filter', [EventController::class, 'getEventFilter']);
	Route::get('get-event-list', [EventController::class, 'getEventList']);
	Route::get('get-event-detail', [EventController::class, 'getEventDetail']);
	Route::post('check-event', [EventController::class, 'checkEventDetail']);

	Route::group(['middleware' => ['auth:sanctum', 'check_member']], function () {
		Route::get('my-events', [EventController::class, 'getMyEventList']);
		Route::get('get-event-details', [EventController::class, 'getMyEventDetails']);
		Route::get('get-event-cart', [EventController::class, 'getMyEventCart']);
		Route::get('remove-event-cart-item', [EventController::class, 'removeMyEventCart']);
	});
});

Route::group(['prefix' => 'hotel'], function () {
	Route::get('get-hotel-list', [HotelController::class, 'getHotelList']);
	Route::get('get-hotel-detail', [HotelController::class, 'getHotelDetail']);
});

Route::post('login', [AdminController::class, 'login']);
Route::get('app-version', [UserController::class, 'getAppVersion']);
Route::post('forgot-password', [AdminController::class, 'forgotPassword']);
Route::post('reset-password', [AdminController::class, 'resetPassword']);
Route::group(['middleware' => ['auth:sanctum']], function () {
	Route::post('change-password', [AdminController::class, 'changePassword']);
	Route::get('logout', [AdminController::class, 'logout']);
});

Route::get('hkuapp', [HomeController::class, 'hkuApp']);
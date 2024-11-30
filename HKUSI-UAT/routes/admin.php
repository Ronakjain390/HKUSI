<?php
/*Admin Controller*/
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\ImageBankController;
use App\Http\Controllers\Admin\ProgrammeController;
use App\Http\Controllers\Admin\HallController;
use App\Http\Controllers\Admin\QuotaController;
use App\Http\Controllers\Admin\QuotaRoomController;
use App\Http\Controllers\Admin\HallBookingController;
use App\Http\Controllers\Admin\QuotaHallController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\UpdatePaymentController;
use App\Http\Controllers\Admin\EventSettingController;
use App\Http\Controllers\Admin\HotelSettingController;
use App\Http\Controllers\Admin\EventBookingController;
use App\Http\Controllers\Admin\PrivateEventController;
use App\Http\Controllers\Admin\PrivateEventSettingController;
use App\Http\Controllers\Admin\EventPaymentController;
use App\Http\Controllers\Admin\EventTypeController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\AdminAppVersionController;
use App\Http\Controllers\Admin\StudentAppVersionController;
use App\Http\Controllers\Admin\DiningTokenController;
use App\Http\Controllers\Admin\ImportantNoticeController;
use App\Http\Controllers\Admin\EmailSettingController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\ActivityLogController;


Route::get('/', [DashboardController::class, 'redirect']);
Route::group(['prefix' => 'ukHstrkv', 'as' => 'admin.'], function () {
    Route::group(['middleware' => ['auth']], function () {
    	/* Dashboard Route Start */ 
			Route::get('/pending-payment-status', [DashboardController::class, 'pendingPaymentStatus']);
			Route::get('/insert-record', [DashboardController::class, 'insertRecord']);
			Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    	/* Dashboard Route End */ 

    	/* User Route Start */
		Route::get('/user-detail/{id}/{type}', [UserController::class, 'userDetails'])->name('userDetails')->whereNumber('id');
		Route::post('/userselectstatuschange/{id}', [UserController::class, 'userselectstatuschange'])->name('users.userselectstatuschange');
		Route::post('/userinfostatus/{id}', [UserController::class, 'userinfostatus'])->name('users.userinfostatus');
		Route::post('/user-password/{id}', [UserController::class, 'updateUserPassword'])->name('users.updateUserPassword');
		Route::post('/user-delete-multiple', [UserController::class, 'multipleusersdelete'])->name('users.multipleusersdelete');
		Route::get('/user-status-change/{id}/{status}', [UserController::class, 'userstatusChange'])->name('users.userstatuschange')->whereNumber('id')->whereNumber('status');
		Route::post('/user-settings/{id}',[UserController::class,'updateSettings'])->name('users.updateSettings');

		Route::resource('users', UserController::class);
		/* User Route End */

    	/* Profile Info */
    		Route::get('/profileinfo', [ProfileController::class, 'getProfile'])->name('getProfile');
    		Route::post('/update-profile', [ProfileController::class, 'updateProfile'])->name('updateProfile');
    	/* End Info */

    	/* Member Route Start */ 
		Route::post('/multipleusersdelete', [MemberController::class,'multipleusersdelete'])->name('members.multipleusersdelete');
		Route::post('/memberimageChange/{id}', [MemberController::class,'memberimageChange'])->name('members.memberimageChange');
		Route::post('/memberselectstatuschange/{id}', [MemberController::class,'memberselectstatuschange'])->name('members.memberselectstatuschange');
		Route::post('/memberinfostatus/{id}', [MemberController::class,'memberinfostatus'])->name('members.memberinfostatus');

		Route::post('/member-password/{id}',[MemberController::class,'updateMemberPassword'])->name('members.updateMemberPassword');
		Route::post('/member-settings/{id}',[MemberController::class,'updateMemberSettings'])->name('members.updateMemberSettings');
		
		Route::get('/userstatuschange/{id}/{status}', [MemberController::class, 'userstatusChange'])->name('members.userstatuschange')->whereNumber('id')->whereNumber('status');

		Route::get('/memberstatuschange/{id}/{status}', [MemberController::class, 'memberstatusChange'])->name('members.memberstatuschange')->whereNumber('id')->whereNumber('status');

		Route::get('/member-detail/{id}/{type}',[MemberController::class,'memberDetail'])->name('memberDetail')->whereNumber('id');
		Route::get('/imageRenameFolder',[MemberController::class,'imageRenameFolder'])->name('members.imageRenameFolder');
		Route::resource('members', MemberController::class);

    	/* Member Route End */ 


    	/* Import Route Start */ 
    		Route::post('/multiple-action', [ImportController::class,'multipleImportDataDelete'])->name('import.multipleAction');
    		Route::get('import/{type}',[ImportController::class,'importData'])->name('importData');
    		Route::get('import/{id}/{type}',[ImportController::class,'importDetail'])->name('import.importDetail');
			Route::resource('import', ImportController::class)->only('index','destroy');
    	/* Import Route End */ 


    	/* Export Route Start */ 
    		Route::post('/multiple-dataDelete', [ExportController::class,'multipleExportDataDelete'])->name('export.multipleExportDataDelete');
    		Route::get('export/{type}',[ExportController::class,'exportData'])->name('exportData');
    		Route::get('export/{id}/{type}',[ExportController::class,'exportDetail'])->name('export.exportDetail');
			Route::resource('export', ExportController::class)->only('index','destroy');
    	/* Export Route End */ 

    	/*ImageBank Route Start*/
			Route::delete('/deleteall',[ImageBankController::class,'deleteall'])->name('imagebank.deleteall');
			Route::get('/imagebank/getprogram', [ImageBankController::class, 'getprogram'])->name('imagebank.getprogram');
    		Route::resource('imagebank',ImageBankController::class);
    	/*ImageBank Route End*/

    	/*Programe Route Start*/
    		Route::post('/multipleprogramedelete', [ProgrammeController::class,'multipleprogramedelete'])->name('programe.multipleprogramedelete');
			Route::post('/programmeselectstatuschange/{id}', [ProgrammeController::class,'programmeselectstatuschange'])->name('programme-setting.programmeselectstatuschange');

			Route::get('/programstatuschange/{id}/{status}', [ProgrammeController::class, 'statusChange'])->name('programe.statuschange')->whereNumber('id')->whereNumber('status');

    		Route::get('/programme-setting/{id}/{type}',[ProgrammeController::class,'programmeDetail'])->name('programmeDetail');
    		Route::get('/programes/getfilterdata', [ProgrammeController::class, 'getfilterdata'])->name('programe.getfilterdata');
    		Route::resource('programme-setting',ProgrammeController::class)->only('index','update','destroy','store','create');
    	/*Programe Route End*/

    	/*Hall Setting Route Start*/
    		Route::post('/multiplehalldelete', [HallController::class,'multipleHallDelete'])->name('hall.multiplehalldelete');
			Route::post('/hallselectstatuschange/{id}', [HallController::class,'hallselectstatuschange'])->name('accommondation-setting.hallselectstatuschange');
    		Route::resource('accommondation-setting',HallController::class)->only('destroy','update','store','create','index');
    		Route::get('/accommondation-setting/{id}/{type}',[HallController::class,'hallDetails'])->name('hallDetails');
    	/*Hall Setting Route End */


        /* Private Event Setting Route Start By Akash*/
            Route::post('/multiplePrivateEventDelete', [PrivateEventSettingController::class,'multiplePrivateEventDelete'])->name('private-event-setting.multiplePrivateEventDelete');
            Route::get('/private-event-setting/{id}/{type}',[PrivateEventSettingController::class,'privateEventSettingDetails'])->name('privateEventSettingDetails');
            Route::get('/private-event-setting/getYearProgramme', [PrivateEventSettingController::class, 'getYearProgramme'])->name('private-event-setting.getYearProgramme');
            Route::resource('private-event-setting',PrivateEventSettingController::class)->only('destroy','update','store','create','index');
        /* Private Event Setting Route End */

    	/*Event Setting Route Start*/
    		Route::post('/multipleEventDelete', [EventSettingController::class,'multipleEventDelete'])->name('event-setting.multipleEventDelete');
    		Route::get('/event-setting/{id}/{type}',[EventSettingController::class,'eventSettingDetails'])->name('eventSettingDetails');
    		Route::get('/event-setting/getYearProgramme', [EventSettingController::class, 'getYearProgramme'])->name('event-setting.getYearProgramme');
    		Route::resource('event-setting',EventSettingController::class)->only('destroy','update','store','create','index');
    	/*Event Setting Route End */

        /*Hotel Setting Route Start By Akash*/
            Route::post('/multipleHotelDelete', [HotelSettingController::class,'multipleHotelDelete'])->name('hotel-setting.multipleHotelDelete');
            Route::get('/hotel-setting/{id}/{type}',[HotelSettingController::class,'hotelSettingDetails'])->name('hotelSettingDetails');
            Route::resource('hotel-setting',HotelSettingController::class)->only('destroy','update','store','create','index');
        /*Hotel Setting Route End By Akash */

    	/* Quota Controller */ 
    		Route::post('/multiplequotadelete', [QuotaController::class,'multipleQuotaDelete'])->name('quota.multiplequotadelete');
    		Route::resource('quota',QuotaController::class)->only('destroy','update','store');
    		Route::get('/quota/{id}/{type}',[QuotaController::class,'quotaDetail'])->name('quota.quotaDetail');
    	/* End Quota Controller */ 


    	/* Quota Room Controller */ 
    		// Route::post('/multiplequotadelete', [QuotaRoomController::class,'multipleQuotaDelete'])->name('quota.multiplequotadelete');
    		// Route::resource('room',QuotaRoomController::class)->only('destroy','update','store');
    	
    		Route::post('/multipleroomdelete', [QuotaRoomController::class,'multipleRoomDelete'])->name('room.multipleRoomDelete');
    		Route::resource('room',QuotaRoomController::class)->only('destroy','update','store');
    		Route::get('/room/{id}/{type}',[QuotaRoomController::class,'roomDetail'])->name('room.roomDetail'); 
    	/* End Quota Room Controller */ 

    	/* Quota-Hall Route Start */ 
    		Route::post('/multiplequotahalldelete', [QuotaHallController::class,'multipleQuotaHallDelete'])->name('quota-hall.multiplequotahalldelete');
    		Route::resource('quota-hall',QuotaHallController::class)->only('destroy','update','store','show');
    		Route::get('/quota-hall/{id}/{type}',[QuotaHallController::class,'quotahallDetails'])->name('quotahall.quotahallDetails'); 
			Route::get('/quotahall/getquotahalldetails',[QuotaHallController::class,'getquotahalldetails'])->name('quotahall.getquotahalldetails');
    	/*End Quota-Hall Route */

    	/* Hall Booking Route Start */ 
	    	Route::get('/hallbooking-detail/{id}/{type}',[HallBookingController::class,'hallbookingDetails'])->name('hallbookingDetails');
	    	Route::post('/multiplebookingdelete', [HallBookingController::class,'multipleBookingDelete'])->name('hallbooking.multiplebookingdelete');
			Route::post('/hallbookingstatuschange/{id}', [HallBookingController::class,'hallbookingstatuschange'])->name('hallbooking.hallbookingstatuschange');
	    	// Route::get('/hallbookingstatus/{id}/{status}', [MemberController::class, 'hallbookingStatus'])->name('hallbooking.hallbookingstatus');
			Route::resource('hallbooking', HallBookingController::class);
    	/*Hall Booking Route End */

    	/*Payment Route Controller*/
    		Route::get('/payment-details/{id}/{type}',[PaymentController::class,'paymentDetails'])->name('payments.paymentDetails');
    		Route::post('/multiplepaymentdelet', [PaymentController::class,'multiplepaymentdelet'])->name('payments.multiplepaymentdelet');
			
			Route::get('/updatePaymentStatusByApiStatus',[PaymentController::class,'updatePaymentStatusByApiStatus'])->name('payments.updatePaymentStatusByApiStatus');
			Route::get('/updatePaymentStatusByApiStatusbyId',[PaymentController::class,'updatePaymentStatusByApiStatusbyId'])->name('payments.updatePaymentStatusByApiStatusbyId');
			Route::get('/statusComplatedToCompleted',[PaymentController::class,'statusComplatedToCompleted'])->name('payments.statusComplatedToCompleted');
    		Route::resource('payments',PaymentController::class);
    	/*End Payment root*/

    	/*Country Route Controller*/
    		Route::post('/multiplecountrydelete', [CountryController::class,'multipleCountrydelete'])->name('country.multiplecountrydelete');
    		Route::resource('/country',CountryController::class);
    	/* End Courntry Route */ 


    	Route::get('/updatePaymentDateTime',[UpdatePaymentController::class,'updatePaymentDateTime'])->name('updatePaymentDateTime');
    	Route::get('/updatePaymentRecords',[UpdatePaymentController::class,'updatePaymentRecords'])->name('updatePaymentRecords');
    	Route::get('/updatePaymentDeadlineDate',[UpdatePaymentController::class,'updatePaymentDeadlineDate'])->name('updatePaymentDeadlineDate');

    	/*Event Booking Route Start*/
    	Route::get('/eventbooking-detail/{id}/{type}',[EventBookingController::class,'eventBookingDetail'])->name('eventbookingDetail');
    	Route::post('/multiplebookingeevents', [EventBookingController::class,'multipleEventsBookings'])->name('eventbooking.multipleEventsBookings');
    	Route::resource('eventbooking',EventBookingController::class);
    	/*End Event Booking Route*/

        /*Private Event Booking Route Start By Akash*/
        Route::resource('private-event-order',PrivateEventController::class);
        Route::post('/multiplePrivateEventOrderDelete', [PrivateEventController::class,'multiplePrivateEventOrderDelete'])->name('private-event-order.multiplePrivateEventOrderDelete');
        Route::get('/private-event-order-detail/{id}/{type}',[PrivateEventController::class,'privateEventOrderDetail'])->name('privateEventOrderDetail');

        /*End Private Event Booking Route*/


    		/*Payment Route Controller*/
    		Route::get('/event-payment/{id}/{type}',[EventPaymentController::class,'eventpaymentDetails'])->name('eventpayment.eventpaymentDetails');
    		Route::post('/multipleEventpaymentdelete', [EventPaymentController::class,'multipleEventpaymentdelete'])->name('eventpayment.multipleEventpaymentdelete');
    		Route::resource('eventpayment',EventPaymentController::class);
    	/*End Payment root*/
    	/*Event Type Route Start*/
	    	Route::post('/eventypemultiple', [EventTypeController::class,'eventypemultiple'])->name('event-type.eventypemultiple');
	    	Route::resource('event-type',EventTypeController::class);
    	/*End Event Type Route */
		
		/*Event Type Route Start*/
	    	Route::post('/multipleLangeuage', [LanguageController::class,'multipleLangeuage'])->name('language.multipleLangeuage');
	    	Route::resource('language',LanguageController::class);
    	/*End Event Type Route */
		
		/*Dining Token Route Start*/
			Route::resource('dining-token',DiningTokenController::class);
    	/*End Dining Token Route */
		
		/*Adminappversion Route Start*/
		Route::get('/adminappversion/{id}/{type}',[AdminAppVersionController::class,'versionDetail'])->name('adminappversion.versionDetail')->whereNumber('id');
		Route::post('/multipleAdminAppVersion', [AdminAppVersionController::class,'multipleAdminAppVersion'])->name('adminappversion.multipleAdminAppVersion');
		Route::resource('adminappversion',AdminAppVersionController::class);
		/*End Event Type Route */

		/*Event Type Route Start*/
		Route::get('/studentappversion/{id}/{type}',[StudentAppVersionController::class,'versionDetail'])->name('studentappversion.versionDetail')->whereNumber('id');
		Route::post('/multipleStudentAppVersion', [StudentAppVersionController::class,'multipleStudentAppVersion'])->name('studentappversion.multipleStudentAppVersion');
		Route::resource('studentappversion',StudentAppVersionController::class);
		/*End Event Type Route */ 

    	/*Important noteice Route Start vinod*/
		Route::post('/multipleimportentnoticedelete', [ImportantNoticeController::class,'multipleimportentnoticedelete'])->name('importantnotice.multipleimportentnoticedelete');
		Route::get('/importantnotice/{id}/{type}',[ImportantNoticeController::class,'importantnoticesetting'])->name('importantnoticesetting');
		Route::resource('importantnotice',ImportantNoticeController::class);
    	/*Important noteic Route End */

    	/*Email setting Route Start vinod*/
		Route::post('/multipleemailsettingdelete', [EmailSettingController::class,'multipleemailsettingdelete'])->name('email-setting.multipleemailsettingdelete');
		Route::get('/email-setting/{id}/{type}',[EmailSettingController::class,'emailsettingDetails'])->name('email-settingdetails');
		Route::resource('email-setting',EmailSettingController::class);
    	/*Email setting Route End */

    	/*Email template Route Start vinod*/
		Route::post('/emailtemplatesettingmultiple', [EmailTemplateController::class,'emailtemplatesettingmultiple'])->name('email-template.emailtemplatesettingmultiple');
		Route::get('/email-template/{id}/{type}',[EmailTemplateController::class,'emailtemplatesetting'])->name('emailtemplatesetting');
		Route::resource('email-template',EmailTemplateController::class);
    	/*Email template Route End */

    	/*Activity log Route Start vinod*/
		Route::post('/multiplActivityLogs', [ActivityLogController::class,'multiplActivityLogs'])->name('activity-logs.multiplActivityLogs');
		Route::resource('activity-logs',ActivityLogController::class);
    	/*Activity log Route End*/
    });
});
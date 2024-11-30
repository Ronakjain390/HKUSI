<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmailJob;
use App\Models\AdminNotificationInfo;
use App\Models\AppVersion;
use App\Models\EventBooking;
use App\Models\PrivateEventOrder;
use App\Models\HallBookingAttendance;
use App\Models\HallBookingInfo;
use App\Models\MemberInfo;
use App\Models\Programme;
use App\Models\QuotaRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public $response = [];

    public function __construct()
    {
        // $this->response['version'] = AppVersion::latest()->first();
    }
    public function getAppVersion()
    {
        $this->response['data'] = AppVersion::latest()->first();
        $this->response['message'] = 'App Version';
        return response()->json($this->response);
    }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, 400);
        }

        if (!Auth::attempt(array_merge($request->only('email', 'password')))) {
            $this->response['message'] = 'Email & Password does not match with our record.';
            return response()->json($this->response, 400);
        }

        $loginuserinfo = Auth::user();
        if ($loginuserinfo->hasRole('Super Admin')) {
            if (!isset($loginuserinfo->status)) {
                Auth::user()->tokens()->delete();
                $this->response['message'] = 'Email & Password does not match with our record.';
                return response()->json($this->response, 400);
            }
            if ($loginuserinfo->status == '0') {
                Auth::user()->tokens()->delete();
                $this->response['message'] = 'Your account is inactive. Please active your account.';
                return response()->json($this->response, 400);
            }

            Auth::user()->tokens()->delete();

            $token = $loginuserinfo->createToken($request->email);
            $this->response['data'] = [
                "email" => $loginuserinfo->email,
                "title" => $loginuserinfo->title,
                "gender" => $loginuserinfo->gender,
                "surname" => $loginuserinfo->surname,
                "given_name" => $loginuserinfo->given_name,
                "mobile_tel_no" => $loginuserinfo->mobile_tel_no,
                "department" => $loginuserinfo->department,
            ];
            $this->response['token'] = $token->plainTextToken;
            $this->response['message'] = 'Login sucessfully.';
            return response()->json($this->response);
        }

        Auth::user()->tokens()->delete();
        $this->response['message'] = 'You are not authorized to login in this site.';
        return response()->json($this->response, 400);
    }
    public function logout()
    {
        Auth::user()->tokens()->delete();
        $this->response['message'] = 'Logout sucessfully.';
        return response()->json($this->response);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|min:8',
            'confirm_password' => 'required_with:password|same:password|min:8',
        ]);

        if ($validator->fails()) {
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, 400);
        }

        $user = $request->user();

        if (Hash::check($request->current_password, $user->password)) {
            $user->fill(['password' => Hash::make($request->password)])->save();
            $details = ['type' => 'UpdatePassword', 'email' => $user->email, 'mailInfo' => $user];
            SendEmailJob::dispatchNow($details);
            $this->response['message'] = "Password changed successfully.";
            return response()->json($this->response);
        } else {
            $this->response['message'] = 'Current password not matched, please enter correct current password.';
            return response()->json($this->response, 400);
        }
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        $this->response['data'] = ['surname' => $user->surname, 'given_name' => $user->given_name, 'push_notification' => $user->push_notification];
        $this->response['message'] = 'Profile info.';
        return response()->json($this->response);
    }

    public function updateSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'push_notification' => 'required',
        ]);

        if ($validator->fails()) {
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, 400);
        }

        $user = $request->user();
        $user->push_notification = $request->push_notification;
        $user->save();

        $this->response['message'] = 'Settings updated.';
        return response()->json($this->response);
    }

    public function forgotPassword(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
            if ($validator->fails()) {
                $this->response['message'] = $validator->messages()->first();
                return response()->json($this->response, 400);
            }

            $userPassword = User::where('email', $request->email)->first();
            if (!empty($userPassword)) {
                $token = Str::random(64);
                DB::table('password_resets')->where('email', $userPassword->email)->delete();
                DB::table('password_resets')->insert(['email' => $userPassword->email, 'token' => $token]);
                $url = route('password.reset', [$token, 'email' => $request->email]);
                $mailInfo = [
                    'email' => $userPassword->email,
                    'given_name' => $userPassword->given_name,
                    'application_number' => $userPassword->application_number,
                    'url' => $url,
                ];
                $details = ['type' => 'ResetPasswordTemplate', 'email' => $userPassword->email, 'mailInfo' => $mailInfo];
                SendEmailJob::dispatchNow($details);

                $this->response['message'] = 'An email sent to your email to reset password.';
                return response()->json($this->response);
            }

            $this->response['message'] = 'You are not register with us.';
            return response()->json($this->response, 400);
        } catch (\Exception $e) {
            $this->response['message'] = $e->getMessage();
            return response()->json($this->response, 400);
        }
    }

    public function scanHallQr(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_id' => 'required',
            'act' => 'required',
        ]);

        if ($validator->fails()) {
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, 400);
        }
        if (!in_array($request->act, ['check-in', 'check-out'])) {
            $this->response['message'] = 'Invalid act value use only [check-in,check-out].';
            return response()->json($this->response, 400);
        }

        $memberInfo = MemberInfo::where('application_number', $request->application_id)->first();
        if (empty($memberInfo)) {
            $this->response['message'] = 'Member Information not found.';
            return response()->json($this->response, 400);
        }

        $eventBookingQuery = HallBookingInfo::with(['getQuotaHallDetail', 'getQuotaRoomDetail'])->whereIn('status', ['Paid', 'Updated'])->where('application_id', $request->application_id);
        if ($request->act == 'check-in') {
            $eventBookingQuery->whereDoesntHave('getBookingAttendanceInfo');
        } elseif ($request->act == 'check-out') {
            $eventBookingQuery->whereHas('getBookingAttendanceInfo', function($q){
				$q->where('status', 'Check-in');
			});
        }
        $eventBooking = $eventBookingQuery->get();

        $bookings = [];
        foreach ($eventBooking as $eb) {
            $bookings[] = [
                'id' => $eb->id,
                'room' => $eb->getQuotaRoomDetail->room_code ?? 'N/A',
                'college_name' => $eb->getQuotaHallDetail->college_name ?? 'N/A',
                'address' => $eb->getQuotaHallDetail->address ?? 'N/A',
                'room_type' => $eb->getQuotaHallDetail->room_type ?? 'N/A',
                'check_in_date' => !empty($eb->getQuotaHallDetail->check_in_date) ? date('Y-m-d', $eb->getQuotaHallDetail->check_in_date) : 'N/A',
                'check_in_time' => !empty($eb->getQuotaHallDetail->check_in_time) ? date('H:i', $eb->getQuotaHallDetail->check_in_time) : 'N/A',
                'check_out_date' => !empty($eb->getQuotaHallDetail->check_out_date) ? date('Y-m-d', $eb->getQuotaHallDetail->check_out_date) : 'N/A',
                'check_out_time' => !empty($eb->getQuotaHallDetail->check_out_time) ? date('H:i', $eb->getQuotaHallDetail->check_out_time) : 'N/A',
                'status' => (isset($eb->getQuotaRoomDetail->status) ? ($eb->getQuotaRoomDetail->status == 1 ? 'Yes' : 'No') : null),
            ];
        }

        $pids = $memberInfo->getMemberProgrammeDetail()->pluck('programme_id')->toArray();
        $end_date = Programme::whereIn('id', $pids)->max('end_date');

        $DISK_NAME = Config::get('DISK_NAME');
        $data = array(
            'application_id' => $memberInfo->application_number,
            'given_name' => $memberInfo->given_name,
            'surname' => $memberInfo->surname,
            'expiry_date' => !empty($end_date) ? date('Y-m-d', $end_date) : 'N/A',
            'profile_image' => (isset($memberInfo->getMemberInfo->getImageBankDetail->profile_image) && $memberInfo->getMemberInfo->getImageBankDetail->profile_image != '' && Storage::disk($DISK_NAME)->exists($memberInfo->getMemberInfo->getImageBankDetail->profile_image)) ? asset(Storage::url($memberInfo->getMemberInfo->getImageBankDetail->profile_image)) : asset('img/default-image.jpg'),
            'bookings' => $bookings,
        );

        $this->response['data'] = $data;
        $this->response['message'] = 'Hall Info';

        return response()->json($this->response);
    }

    public function hallCheckIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
            'room_id' => 'required',
        ]);
        if ($validator->fails()) {
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, 400);
        }

        $hallBooking = HallBookingInfo::find($request->booking_id);
        if (empty($hallBooking)) {
            $this->response['message'] = 'No hall booking found.';
            return response()->json($this->response, 400);
        }


        $isQuotaRoom = QuotaRoom::where(['room_code' => $request->room_id, 'quota_id' => $hallBooking->quota_id, 'quota_hall_id' => $hallBooking->quota_hall_id])->first();
        if (!empty($isQuotaRoom)) {
            if ($isQuotaRoom->status == 0) {
                $this->response['message'] = 'The room ID is disabled.';
                return response()->json($this->response, 400);
            } elseif (HallBookingInfo::where(['quota_id' => $hallBooking->quota_id, 'quota_hall_id' => $hallBooking->quota_hall_id, 'quota_room_id' => $isQuotaRoom->id])->where('id', '<>', $hallBooking->id)->exists()) {
                $this->response['message'] = 'The room ID is already used with another booking.';
                return response()->json($this->response, 400);
            }
        }

        $quotaRoom = QuotaRoom::updateOrCreate(
            ['room_code' => $request->room_id, 'quota_id' => $hallBooking->quota_id, 'quota_hall_id' => $hallBooking->quota_hall_id],
            [
                'room_code' => $request->room_id,
                'hall_setting_id' => $hallBooking->hall_setting_id,
                'quota_id' => $hallBooking->quota_id,
                'quota_hall_id' => $hallBooking->quota_hall_id,
                'quota_room_id' => $hallBooking->quota_room_id,
                'booking_number' => $hallBooking->booking_number,
                'user_type_id' => $hallBooking->user_type_id,
                'college_name' => $hallBooking->getQuotaHallDetail->college_name ?? null,
                'gender' => $hallBooking->getMemberdata->gender ?? null,
                'user_type' => $hallBooking->user_type,
                'start_date' => $hallBooking->start_date,
                'end_date' => $hallBooking->end_date,
                'check_in_date' => $hallBooking->check_in_date,
                'check_in_time' => $hallBooking->check_in_time,
                'check_out_date' => $hallBooking->check_out_date,
                'check_out_time' => $hallBooking->check_out_time,
            ]
        );
		
		if (HallBookingInfo::where(['quota_id' => $hallBooking->quota_id, 'quota_hall_id' => $hallBooking->quota_hall_id, 'quota_room_id' => $quotaRoom->id])->where('id', '<>', $hallBooking->id)->exists()) {
			$this->response['message'] = 'The room ID is already used with another booking.';
			return response()->json($this->response, 400);
		}

        $hallBooking->quota_room_id = $quotaRoom->id;
        $hallBooking->save();

        $user = $request->user();
        HallBookingAttendance::updateOrCreate(
            ['hall_booking_info_id' => $hallBooking->id],
            [
                'actual_check_in_date' => strtotime(date('Y-m-d')),
                'actual_check_in_time' => strtotime(date('H:i')),
                'check_in_operator' => $user->id,
                'status' => 'Check-in',
            ]
        );

        $this->response['room_id'] = $quotaRoom->room_code;
        $this->response['message'] = 'You are successfully checked-in to this hall.';
        return response()->json($this->response);
    }

    public function hallCheckOut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
        ]);
        if ($validator->fails()) {
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, 400);
        }

        $hallBooking = HallBookingInfo::find($request->booking_id);
        if (empty($hallBooking)) {
            $this->response['message'] = 'Invalid booking id.';
            return response()->json($this->response, 400);
        }
        if (is_null($hallBooking->quota_room_id)) {
            $this->response['message'] = 'No room found with this booking.';
            return response()->json($this->response, 400);
        }

        $hallBooking->status = 'Completed';
        $hallBooking->save();

        $user = $request->user();
        HallBookingAttendance::updateOrCreate(
            ['hall_booking_info_id' => $hallBooking->id],
            [
                'actual_check_out_date' => strtotime(date('Y-m-d')),
                'actual_check_out_time' => strtotime(date('H:i')),
                'check_out_operator' => $user->id,
                'status' => 'Check-out',
            ]
        );

		
		if(!empty($hallBooking->getMemberdata)) {
			$mailInfo = [
				'given_name'            => $hallBooking->getMemberdata->given_name,
				'application_id'    => $hallBooking->getMemberdata->application_number,
			];
	
			$HallCheckOutEmail = ['type'=>'HallCheckOut','email' =>$hallBooking->getMemberdata->email_address,'mailInfo' => $mailInfo];
			SendEmailJob::dispatch($HallCheckOutEmail);
		}

        $this->response['message'] = 'You are successfully checked-out from this hall.';
        return response()->json($this->response);
    }

    public function scanActivityQr(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'application_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, 400);
        }

        $memberInfo = MemberInfo::where('application_number', $request->application_id)->first();
        if (empty($memberInfo)) {
            $this->response['message'] = 'Member Information not found.';
            return response()->json($this->response, 400);
        }

        // $eventBooking = EventBooking::select('id','event_id','payment_id as booking_id',\DB::raw("'public_event' as event_type"))->whereNotIn('booking_status', ['Pending', 'Cancelled', 'Completed'])->with('getEventSetting')->where('application_id', $request->application_id)->get();
		// $privateEventOrder = PrivateEventOrder::select('id','event_id','booking_id',\DB::raw("'private_event' as event_type"))->whereNotIn('booking_status', ['Pending', 'Cancelled', 'Completed'])->with('getEventSetting')->where('application_id', $request->application_id)->get();
		
		// $mergedCollection = $eventBooking->merge($privateEventOrder);

		$eventBooking = EventBooking::whereNotIn('booking_status', ['Pending', 'Cancelled', 'Completed'])->with('getEventSetting')->where('application_id', $request->application_id)->get();
		
		$events = [];
        foreach ($eventBooking as $eb) {

            $events[] = [
                'id' => $eb->id,
                'event_name' => $eb->getEventSetting->event_name??'N/A',
                'date' => !empty($eb->getEventSetting->date) ? date('Y-m-d', $eb->getEventSetting->date) : 'N/A',
                'start_time' => !empty($eb->getEventSetting->start_time) ? date('H:i', $eb->getEventSetting->start_time) : 'N/A',
                'end_time' => !empty($eb->getEventSetting->end_time) ? date('H:i', $eb->getEventSetting->end_time) : 'N/A',
                'event_type' => $eb->event_type,
            ];
        }

        $pids = $memberInfo->getMemberProgrammeDetail()->pluck('programme_id')->toArray();
        $end_date = Programme::whereIn('id', $pids)->max('end_date');

        $DISK_NAME = Config::get('DISK_NAME');
        $data = array(
            'application_id' => $memberInfo->application_number,
            'given_name' => $memberInfo->given_name,
            'surname' => $memberInfo->surname,
            'expiry_date' => !empty($end_date) ? date('Y-m-d', $end_date) : 'N/A',
            'profile_image' => (isset($memberInfo->getMemberInfo->getImageBankDetail->profile_image) && $memberInfo->getMemberInfo->getImageBankDetail->profile_image != '' && Storage::disk($DISK_NAME)->exists($memberInfo->getMemberInfo->getImageBankDetail->profile_image)) ? asset(Storage::url($memberInfo->getMemberInfo->getImageBankDetail->profile_image)) : asset('img/default-image.jpg'),
            'events' => $events,
        );

        $this->response['data'] = $data;
        $this->response['message'] = 'Activity Info';

        return response()->json($this->response);
    }

    public function activityCheckIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            // 'event_type' => 'required',
        ]);

        if ($validator->fails()) {
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, 400);
        }

		// if($request->event_type == 'public_event') {
			$eventBooking = EventBooking::find($request->event_id);
			if (empty($eventBooking)) {
				$this->response['message'] = 'Event information not found.';
				return response()->json($this->response, 400);
			}

			$user = $request->user();
			$eventBooking->check_in_date = strtotime(date('Y-m-d'));
			$eventBooking->check_in_time = strtotime(date('H:i'));
			$eventBooking->check_operater = $user->id;
			$eventBooking->booking_status = 'Completed';
			$eventBooking->save();

			$this->response['message'] = 'Activity Check In Success';

			return response()->json($this->response);
		// }
		// else if($request->event_type == 'private_event') {
			
		// 	$eventBooking = PrivateEventOrder::find($request->event_id);
		// 	if (empty($eventBooking)) {
		// 		$this->response['message'] = 'Event information not found.';
		// 		return response()->json($this->response, 400);
		// 	}
	
		// 	$user = $request->user();
		// 	$eventBooking->check_in_date = strtotime(date('Y-m-d'));
		// 	$eventBooking->check_in_time = strtotime(date('H:i'));
		// 	$eventBooking->check_operator = $user->id;
		// 	$eventBooking->booking_status = 'Completed';
		// 	$eventBooking->save();
	
		// 	$this->response['message'] = 'Activity Check In Success';
	
		// 	return response()->json($this->response);
		// }
		// else{
		// 	$this->response['message'] = 'Invalid event type.';
		// 	return response()->json($this->response, 400);
		// }

    }

    public function hallRecords(Request $request)
    {

        $user = $request->user();

        $eventBookingQuery = HallBookingInfo::with(['getMemberdata', 'getQuotaHallDetail', 'getQuotaRoomDetail', 'getBookingAttendanceInfo']);
        $eventBookingQuery->whereHas('getBookingAttendanceInfo', function ($q) use ($user) {
            $q->where('check_in_operator', $user->id);
            $q->orWhere('check_out_operator', $user->id);
        });

        if (!empty($request->search)) {
            $eventBookingQuery->where('application_id', $request->search);
        }
        $eventBooking = $eventBookingQuery->paginate(10);

        $bookings = [];
        foreach ($eventBooking as $eb) {
            $bookings[] = [
                'id' => $eb->id,
                'college_name' => $eb->getQuotaHallDetail->college_name ?? 'N/A',
                'status' => $eb->status ?? 'N/A',
                'given_name' => $eb->getMemberdata->given_name,
                'surname' => $eb->getMemberdata->surname,
                'room' => $eb->getQuotaRoomDetail->room_code ?? 'N/A',
                'check_in_date' => !empty($eb->getQuotaHallDetail->check_in_date) ? date('Y-m-d', $eb->getQuotaHallDetail->check_in_date) : 'N/A',
                'check_out_date' => !empty($eb->getQuotaHallDetail->check_out_date) ? date('Y-m-d', $eb->getQuotaHallDetail->check_out_date) : 'N/A',
                'actual_check_in_date' => !empty($eb->getBookingAttendanceInfo->actual_check_in_date) ? date('Y-m-d', $eb->getBookingAttendanceInfo->actual_check_in_date) : 'N/A',
                'actual_check_in_time' => !empty($eb->getBookingAttendanceInfo->actual_check_in_time) ? date('H:i', $eb->getBookingAttendanceInfo->actual_check_in_time) : 'N/A',
                'actual_check_out_date' => !empty($eb->getBookingAttendanceInfo->actual_check_out_date) ? date('Y-m-d', $eb->getBookingAttendanceInfo->actual_check_out_date) : 'N/A',
                'actual_check_out_time' => !empty($eb->getBookingAttendanceInfo->actual_check_out_time) ? date('H:i', $eb->getBookingAttendanceInfo->actual_check_out_time) : 'N/A',
            ];
        }

        $this->response['data'] = $bookings;
        $this->response['current_page'] = $eventBooking->currentPage();
        $this->response['per_page'] = $eventBooking->perPage();
        $this->response['last_page'] = $eventBooking->lastPage();
        $this->response['total'] = $eventBooking->total();
        $this->response['message'] = 'Hall records.';

        return response()->json($this->response);

    }

    public function hallBookingDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, 400);
        }

        $hallBookingInfo = HallBookingInfo::with(['getMemberdata', 'getQuotaHallDetail', 'getQuotaRoomDetail', 'getBookingAttendanceInfo'])->find($request->booking_id);
        if (empty($hallBookingInfo)) {
            $this->response['message'] = 'Hall booking info not found.';
            return response()->json($this->response, 400);
        }

        $programmeCode = $programmeName = '';
        if (isset($hallBookingInfo->booking_type) && $hallBookingInfo->booking_type == 'g') {
            if (isset($hallBookingInfo->getGroupHallInfo) && count($hallBookingInfo->getGroupHallInfo)) {
                foreach ($hallBookingInfo->getGroupHallInfo as $key => $groupHallInfo) {
                    if (!empty($programmeCode)) {
                        $programmeCode .= " , " . $groupHallInfo->programme_code;
                    } else {
                        $programmeCode .= $groupHallInfo->programme_code;
                    }
                    if (!empty($programmeName)) {
                        $programmeName .= " , " . $groupHallInfo->getProgrammeDetail->programme_name;
                    } else {
                        $programmeName .= $groupHallInfo->getProgrammeDetail->programme_name;
                    }
                }
            }
        } else {
            $programmeCode = $hallBookingInfo->programme_code;
            $programmeName = $hallBookingInfo->getProgrammeDetail->programme_name;
        }

        $booking = [
            'id' => $hallBookingInfo->id,
            'status' => $hallBookingInfo->status ?? 'N/A',
            'given_name' => $hallBookingInfo->getMemberdata->given_name,
            'surname' => $hallBookingInfo->getMemberdata->surname,
            'booking_number' => $hallBookingInfo->booking_number,
            'application_id' => $hallBookingInfo->application_id,
            'programme_code' => $programmeCode,
            'programe_name' => $programmeName,
            'college_name' => $hallBookingInfo->getQuotaHallDetail->college_name ?? 'N/A',
            'room' => $hallBookingInfo->getQuotaRoomDetail->room_code ?? 'N/A',
            'check_in_date' => !empty($hallBookingInfo->getQuotaHallDetail->check_in_date) ? date('Y-m-d', $hallBookingInfo->getQuotaHallDetail->check_in_date) : 'N/A',
            'check_in_time' => !empty($hallBookingInfo->getQuotaHallDetail->check_in_time) ? date('H:i', $hallBookingInfo->getQuotaHallDetail->check_in_time) : 'N/A',
            'check_out_date' => !empty($hallBookingInfo->getQuotaHallDetail->check_out_date) ? date('Y-m-d', $hallBookingInfo->getQuotaHallDetail->check_out_date) : 'N/A',
            'check_out_time' => !empty($hallBookingInfo->getQuotaHallDetail->check_out_time) ? date('H:i', $hallBookingInfo->getQuotaHallDetail->check_out_time) : 'N/A',
            'actual_check_in_date' => !empty($hallBookingInfo->getBookingAttendanceInfo->actual_check_in_date) ? date('Y-m-d', $hallBookingInfo->getBookingAttendanceInfo->actual_check_in_date) : 'N/A',
            'actual_check_in_time' => !empty($hallBookingInfo->getBookingAttendanceInfo->actual_check_in_time) ? date('H:i', $hallBookingInfo->getBookingAttendanceInfo->actual_check_in_time) : 'N/A',
            'actual_check_out_date' => !empty($hallBookingInfo->getBookingAttendanceInfo->actual_check_out_date) ? date('Y-m-d', $hallBookingInfo->getBookingAttendanceInfo->actual_check_out_date) : 'N/A',
            'actual_check_out_time' => !empty($hallBookingInfo->getBookingAttendanceInfo->actual_check_out_time) ? date('H:i', $hallBookingInfo->getBookingAttendanceInfo->actual_check_out_time) : 'N/A',
        ];

        $this->response['data'] = $booking;
        $this->response['message'] = 'Hall booking info.';

        return response()->json($this->response);

    }

    public function eventBookings(Request $request)
    {

        $user = $request->user();
        $eventBookingQuery = EventBooking::with(['getEventSetting', 'paymentBooking'])->where('check_operater', $user->id);
        if (!empty($request->search)) {
            $eventBookingQuery->whereHas('getEventApplication', function ($q) use ($request) {
                $q->where('application_number', $request->search);
            });
        }

		// $privateEventOrder = PrivateEventOrder::select('id','event_id','booking_id',\DB::raw("'private_event' as event_type"))->whereNotIn('booking_status', ['Pending', 'Cancelled', 'Completed'])->with('getEventSetting')->where('application_id', $request->application_id)->get();
		// $mergedCollection = $eventBooking->union($privateEventOrder)->paginate(10);

        $eventBooking = $eventBookingQuery->paginate(10);
        $bookings = [];
        foreach ($eventBooking as $eb) {
            $bookings[] = [
                'id' => $eb->id,
                'status' => $eb->booking_status == "Paid" ? "Enroled and Confirmed" : $eb->booking_status,
                'event_name' => $eb->getEventSetting->event_name,
                'date' => !empty($eb->getEventSetting->date) ? date('Y-m-d', $eb->getEventSetting->date) : 'N/A',
                'start_time' => !empty($eb->getEventSetting->start_time) ? date('H:i', $eb->getEventSetting->start_time) : 'N/A',
                'end_time' => !empty($eb->getEventSetting->end_time) ? date('H:i', $eb->getEventSetting->end_time) : 'N/A',
                'location' => !empty($eb->getEventSetting->location) ? $eb->getEventSetting->location : 'N/A',
                'booking_number' => $eb->paymentBooking->payment_id ?? 'N/A',
                'actual_check_in_date' => !empty($eb->check_in_date) ? date('Y-m-d', $eb->check_in_date) : 'N/A',
                'actual_check_in_time' => !empty($eb->check_in_time) ? date('H:i', $eb->check_in_time) : 'N/A',
            ];
        }

        $this->response['data'] = $bookings;
        $this->response['current_page'] = $eventBooking->currentPage();
        $this->response['per_page'] = $eventBooking->perPage();
        $this->response['last_page'] = $eventBooking->lastPage();
        $this->response['total'] = $eventBooking->total();
        $this->response['message'] = 'Hall records.';

        return response()->json($this->response);

    }

    public function eventBookingDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, 400);
        }

        $user = $request->user();

        $eventBooking = EventBooking::with(['getEventSetting', 'paymentBooking', 'getEventApplication'])->where('check_operater', $user->id)->find($request->booking_id);
        if (empty($eventBooking)) {
            $this->response['message'] = 'Event booking info not found.';
            return response()->json($this->response, 400);
        }

        $booking = [
            'id' => $eventBooking->id,
            'status' => $eventBooking->booking_status ?? '' == "Paid" ? "Enroled and Confirmed" : ($eventBooking->booking_status ?? 'N/A'),
            'surname' => $eventBooking->getEventApplication->surname ?? 'N/A',
            'given_name' => $eventBooking->getEventApplication->given_name ?? 'N/A',
            'application_number' => $eventBooking->getEventApplication->application_number ?? 'N/A',
            'event_name' => $eventBooking->getEventSetting->event_name,
            'date' => !empty($eventBooking->getEventSetting->date) ? date('Y-m-d', $eventBooking->getEventSetting->date) : 'N/A',
            'start_time' => !empty($eventBooking->getEventSetting->start_time) ? date('H:i', $eventBooking->getEventSetting->start_time) : 'N/A',
            'end_time' => !empty($eventBooking->getEventSetting->end_time) ? date('H:i', $eventBooking->getEventSetting->end_time) : 'N/A',
            'location' => !empty($eventBooking->getEventSetting->location) ? $eventBooking->getEventSetting->location : 'N/A',
            'no_of_ticket' => $eventBooking->paymentBooking->no_of_seats ?? 'N/A',
            'booking_number' => $eventBooking->paymentBooking->payment_id ?? 'N/A',
            'assembly_time' => $eventBooking->getEventSetting->assembly_time ?? 'N/A',
            'assembly_location' => $eventBooking->getEventSetting->assembly_location ?? 'N/A',
            'actual_check_in_date' => !empty($eventBooking->check_in_date) ? date('Y-m-d', $eventBooking->check_in_date) : 'N/A',
            'actual_check_in_time' => !empty($eventBooking->check_in_time) ? date('H:i', $eventBooking->check_in_time) : 'N/A',
        ];

        $this->response['data'] = $booking;
        $this->response['message'] = 'Event info.';
        return response()->json($this->response);

    }

    public function getNotification(Request $request, $type)
    {
        $user = $request->user();
        if ($type == 'list') {
            $notifications = AdminNotificationInfo::where('user_id', $user->id)->with('getAdminNotification')->latest()->get();

            // Transform the collection to include the status in the main array
            $notifications = $notifications->map(function ($notification) {
                return [
                    "id" => $notification->id,
                    "title" => $notification->getAdminNotification->title,
                    "short_description" => $notification->getAdminNotification->short_description,
                    "long_description" => $notification->getAdminNotification->long_description,
                    "status" => $notification->getAdminNotification->status == 0 ? "Disable" : "Enable",
                    "read" => $notification->read,
                    "created_at" => $notification->created_at,
                    "updated_at" => $notification->updated_at,
                ];
            });

            $this->response['data'] = $notifications;
            $this->response['message'] = 'Notification List.';
            return response()->json($this->response);
        } elseif ($type == 'unread') {
            $notification = AdminNotificationInfo::where('user_id', $user->id)->where('read', 'No')->count();
            $data = [
                'unread' => $notification,
            ];
            $this->response['data'] = $data;
            $this->response['message'] = 'Unread Count.';
            return response()->json($this->response);
        } else {
            $notification = AdminNotificationInfo::with('getAdminNotification')->find($type);
            if (empty($notification)) {
                $this->response['message'] = 'Notification info not found.';
                return response()->json($this->response, 400);
            }

            $this->response['data'] = [
                "id" => $notification->id,
                "title" => $notification->getAdminNotification->title,
                "short_description" => $notification->getAdminNotification->short_description,
                "long_description" => $notification->getAdminNotification->long_description,
                "status" => $notification->getAdminNotification->status == 0 ? "Disable" : "Enable",
                "read" => $notification->read,
                "created_at" => $notification->created_at,
                "updated_at" => $notification->updated_at,
            ];
            $this->response['message'] = 'Notification Info.';
            return response()->json($this->response);
        }
    }

    public function updateNotification(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            $this->response['message'] = $validator->messages()->first();
            return response()->json($this->response, 400);
        }

        $inputs = $request->all();
        $data = AdminNotificationInfo::where('id', $inputs['id'])->update(['read' => 'Yes']);
        $this->response['message'] = 'Status updated successfully.';
        return response()->json($this->response);
    }

}

<?php
namespace App\Http\Controllers\API\V1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Verified;
use App\Traits\VerifyTokenStatus;
use App\Traits\UploadTraits;
use App\Models\User;
use App\Models\EventBooking;
use App\Models\Programme;
use App\Models\Country;
use App\Models\QuotaProgramme;
use App\Models\HallBookingInfo;
use App\Models\HallBookingHallInfo;
use App\Models\HallBookingGroup;
use App\Models\ImageBank;
use App\Models\Payment;
use App\Models\EventPayment;
use App\Models\EventSetting;
use App\Models\MemberEventCart;
use App\Jobs\SendEmailJob;
use Carbon\Carbon;
use Exception, Validator, DB, Storage, Config, DateTime;

class AccommodationController extends Controller
{
    use VerifyTokenStatus;
    use UploadTraits;
    protected $loginField;
    protected $loginValue;

    public function __construct()
    {
        $this->apiArray = array();
        $this->apiArray['error'] = true;
        $this->apiArray['message'] = '';
        $this->apiArray['errorCode'] = 4;
        // $this->DISK_NAME = Config::get('DISK_NAME');
    }

    /* Get getPrograme API on 21/03/2023 by Vinod */
    public function getProgram(Request $request){
        try {
            $inputs = $request->all();
            $userinfo = $request->user('sanctum');
            $this->apiArray['state'] = 'getprograme';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/
            $booking = false;
            $age = 0;
            if (isset($userinfo->getMemberInfo->date_of_birth) && !empty($userinfo->getMemberInfo->date_of_birth)) {
                $dob = date('Y-m-d',Auth::user()->getMemberInfo->date_of_birth);
                if(!empty($dob)){
                    $birthdate = new DateTime($dob);
                    $today   = new DateTime('today');
                    $age = $birthdate->diff($today)->y;
                }
                if ($age >= 15) {
                    $booking = true;
                }else{
                    $booking = false;
                }
            }
            if (isset(Auth::user()->getMemberInfo->getMemberProgrammeDetail) && !empty(Auth::user()->getMemberInfo->getMemberProgrammeDetail)) {
                $checkQuota = QuotaProgramme::select('programme_id')->whereIn('programme_id',Auth::user()->getMemberInfo->getMemberProgrammeDetail)->get();
                if (isset($checkQuota) && count($checkQuota)) {
                        $existProgrammes = [];
                        $bookUserProgramme = HallBookingInfo::select('programme_code')->where('user_type_id',$userinfo->getMemberInfo->id)->whereNotIn('status',['Cancelled','Rejected'])->get();
                        $bookUserRroupProgramme = HallBookingGroup::select('programme_code')->where('user_type_id',$userinfo->getMemberInfo->id)->whereNotIn('status',['Cancelled','Rejected'])->get();
                        if (isset($bookUserRroupProgramme) && count($bookUserRroupProgramme)) {
                            foreach ($bookUserRroupProgramme as $key => $groupProgramme) {
                                $existProgrammes[] = $groupProgramme->programme_code;
                            }
                        }
                        if (isset($bookUserProgramme) && count($bookUserProgramme)) {
                            foreach ($bookUserProgramme as $key => $programme) {
                                $existProgrammes[] = $programme->programme_code;
                            }
                        }
                    $data = Programme::select('id','application_number','programme_code','programme_name','start_date','end_date')->whereIn('id',$checkQuota);
                    if(count($existProgrammes)){
                        $data = $data->whereNotIn('programme_code',$existProgrammes);
                    }
                    $data = $data->where('status',1)->orderBy("id", "DESC")->get();
                    if (count($data)) {
                        foreach($data as $key=>$programe){
                            $startDate = $endDate = '';
                            if (isset($programe->start_date) && !empty($programe->start_date)) {
                                $start_date =  $programe->start_date - 86400;
                                $startDate = date('Y-m-d',$start_date);
                            }
                            if (isset($programe->end_date) && !empty($programe->end_date)) {
                                $end_date =  $programe->end_date + 86400;
                                $endDate = date('Y-m-d',$end_date);
                            }
                            $data[$key]->start_date = $startDate;
                            $data[$key]->end_date = $endDate;
							// $data[$key]->programme_name = $programe->programme_name ." / ". $programe->programme_code;
                        }
                        $this->apiArray['data'] = $data;
                        $this->apiArray['ageLimit'] = $booking;
                        $this->apiArray['message'] = 'Success';
                    }else{                        
                        $this->apiArray['ageLimit'] = $booking;
                        $this->apiArray['message'] = "No Programme found.";
                        $this->apiArray['data'] = [];
                    }
                }else{
                    $this->apiArray['ageLimit'] = $booking;
                    $this->apiArray['message'] = "No Programme found.";
                    $this->apiArray['data'] = [];
                }
            }else{
                $this->apiArray['ageLimit'] = $booking;
                $this->apiArray['message'] = "No Programme found.";
                $this->apiArray['data'] = [];
            }
            
            $this->apiArray['errorCode'] = 0;
            $this->apiArray['error'] = false;

            return response()->json($this->apiArray, 200);
        } catch (\Exception $e) {
            $this->apiArray['message'] = 'Something is wrong, please try after some time'.$e->getMessage();
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }
    /* End */

    /* Get bookAaccommodation API on 21/03/2023 by Vinod */
    public function bookAccommodation(Request $request)
    {
        //try {
            $inputs = $request->all();
             $userinfo = $request->user('sanctum');
            $this->apiArray['state'] = 'bookAaccommodation';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/
            $inputs = $request->all();            
            $validator = Validator::make($inputs, [
                'programme_id' => ['required'],
            ]);
            if($validator->fails()){
                $this->apiArray['message'] = $validator->messages()->first();
                $this->apiArray['errorCode'] = 2;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }
            $checkProgram = Programme::where('id',$inputs['programme_id'])->first();
            if (!empty($checkProgram)){
                if(!$checkProgram->checkMemberProgramme($userinfo->getMemberInfo->id)){
                    $this->apiArray['message'] = "This programme is not allocated to you.";
                    $this->apiArray['errorCode'] = 4;
                    $this->apiArray['error'] = true;
                    return response()->json($this->apiArray, 200);
                }

                if (isset($userinfo->getMemberInfo) && !empty($userinfo->getMemberInfo->date_of_birth)) {

                    $dob = date('Y-m-d',Auth::user()->getMemberInfo->date_of_birth);
                    $programDate = $checkProgram->start_date - 86400;

                    $BirthDay   = date('d',$userinfo->getMemberInfo->date_of_birth);
                    $BirthMonth = date('m',$userinfo->getMemberInfo->date_of_birth);
                    $BirthYear  = date('Y',$userinfo->getMemberInfo->date_of_birth);

                    //convert the users DoB into UNIX timestamp
                    $stampBirth = mktime(0, 0, 0, $BirthMonth, $BirthDay, $BirthYear);

                    // fetch the current date (minus 18 years)
                    $today['day']   = date('d',$programDate);
                    $today['month'] = date('m',$programDate);
                    $today['year']  = date('Y',$programDate) - 15;

                    // generate todays timestamp
                    $stampToday = mktime(0, 0, 0, $today['month'], $today['day'], $today['year']);

                if ($stampBirth <= $stampToday) {
                } else {
                    $this->apiArray['message'] = date('Y-m-d' , $stampBirth)." User is NOT 15 years old, sorry!";
                    $this->apiArray['errorCode'] = 7;
                    $this->apiArray['error'] = true;
                    return response()->json($this->apiArray, 200);
                }

					// $dob = date('Y-m-d',Auth::user()->getMemberInfo->date_of_birth);
                    // $programDate = date('Y-m-d',($checkProgram->start_date));
                    // $birthdate = new DateTime($dob);
                    // $today   = new DateTime($programDate);
                    // $age = $birthdate->diff($today)->y;
                    // if ($age <= 15) {
                    //     $this->apiArray['message'] = $age." age over";
                    //     $this->apiArray['errorCode'] = 7;
                    //     $this->apiArray['error'] = true;
                    //     return response()->json($this->apiArray, 200);
                    // }
                }else{
                    $this->apiArray['message'] = "age over";
                    $this->apiArray['errorCode'] = 7;
                    $this->apiArray['error'] = true;
                    return response()->json($this->apiArray, 200);
                }


                if (isset($checkProgram->getQuotaProgrammeDetail->getQuotaDetail) && !empty($checkProgram->getQuotaProgrammeDetail->getQuotaDetail)){
                    if (empty($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail)){
                        $this->apiArray['message'] = "Hall booking is over";
                        $this->apiArray['errorCode'] = 4;
                        $this->apiArray['error'] = true;
                        return response()->json($this->apiArray, 200);
                    }

                    if(HallBookingInfo::where('user_type_id',$userinfo->getMemberInfo->id)->whereNotIn('status',['Cancelled','Rejected'])->where('programme_code',$checkProgram->programme_code)->where('hall_setting_id',$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->id)->exists()){
                        $this->apiArray['message'] = "You have already booked this programme.";
                        $this->apiArray['errorCode'] = 4;
                        $this->apiArray['error'] = true;
                        return response()->json($this->apiArray, 200);
                    }

                    if(HallBookingInfo::rightJoin('hall_booking_groups', 'hall_booking_groups.hall_booking_info_id' , '=' , 'hall_booking_infos.id')->where('hall_booking_infos.user_type_id',$userinfo->getMemberInfo->id)->where('hall_booking_infos.hall_setting_id',$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->id)->where('hall_booking_infos.hall_setting_id',$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->id)->where('hall_booking_groups.user_type_id',$userinfo->getMemberInfo->id)->where('hall_booking_groups.programme_code',$checkProgram->programme_code)->whereNotIn('hall_booking_infos.status',['Cancelled','Rejected'])->exists()) {
                        $this->apiArray['message'] = "You have already booked this programme.";
                        $this->apiArray['errorCode'] = 4;
                        $this->apiArray['error'] = true;
                        return response()->json($this->apiArray, 200);
                    }
					
					if($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->application_deadline<time()){
						$this->apiArray['message'] = "Application deadline is over";
                        $this->apiArray['errorCode'] = 4;
                        $this->apiArray['error'] = true;
                        return response()->json($this->apiArray, 200);
                    }
					
                    $userCountry = 'No';
                    if(isset($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getQuotaCountry) && count($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getQuotaCountry)){
                        foreach ($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getQuotaCountry as $key => $value) {
                            if (isset($userinfo->getMemberInfo->getStudyCountry->name) && !empty($userinfo->getMemberInfo->getStudyCountry->name)) {
                                if(Country::where('id',$value->country_id)->where('name',$userinfo->getMemberInfo->getStudyCountry->name)->exists()){
                                    $userCountry = 'Yes';
                                }
                            }
                        }
                    }

                    if($userCountry == 'No'){
                        $this->apiArray['message'] = "Residential halls give priority to students who are not residing/studying in Hong Kong.";
                        $this->apiArray['errorCode'] = 3;
                        $this->apiArray['error'] = true;
                        return response()->json($this->apiArray, 200);
                    }
                    $totalHallBooking = HallBookingInfo::whereIn('status',['Completed','Pending','Accepted','Paid','Updated'])->where('programme_code',$checkProgram->programme_code)->where('hall_setting_id',$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->id)->count();
					
					if($checkProgram->start_date!=$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->start_date || $checkProgram->end_date!=$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->end_date){
                        $this->apiArray['message'] = "Quota period not matched";
                        $this->apiArray['errorCode'] = 3;
                        $this->apiArray['error'] = true;
                        return response()->json($this->apiArray, 200);
                    }

                    $balance = $t = 0;
                    $balance = $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->female_max_quota + $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->male_max_quota;

                    if(isset($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallBookinInfos) && count($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallBookinInfos)){
                        foreach($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallBookinInfos as $bookingInfos){
                            if($bookingInfos->status != 'Cancelled' && $bookingInfos->status != 'Rejected'){
                                $t++;
                            }
                        }
                    }
                    $QuotaBalance = $balance - $t;
                    if($QuotaBalance<=0){
                        $this->apiArray['message'] = "We regretted to inform you that the current HKU residential hall quota is full, and your request cannot be processed. To help you ease the situation, we have arranged hotel discounts for your consideration.";
                        $this->apiArray['errorCode'] = 6;
                        $this->apiArray['error'] = true;
                        return response()->json($this->apiArray, 200);
                    }

                    /*if($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->quota_balance<=0){
                        $this->apiArray['message'] = "We regretted to inform you that the current HKU residential hall quota is full, and your request cannot be processed. To help you ease the situation, we have arranged hotel discounts for your consideration.";
                        $this->apiArray['errorCode'] = 6;
                        $this->apiArray['error'] = true;
                        return response()->json($this->apiArray, 200);
                    }*/


                    /*if($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->status!='1'){
                        $type = strtolower($userinfo->getMemberInfo->gender).'_max_quota';
                    }else{
                        $type = strtolower($userinfo->getMemberInfo->gender);
                    }*/
                    
                    $type = strtolower($userinfo->getMemberInfo->gender).'_max_quota';                    
                    $totalGenderQuote = $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->$type;

                    $totalGenderBooking = HallBookingInfo::select('hall_booking_infos.id')->leftJoin('member_infos', function ($join) {
                        $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                    })->whereNull('member_infos.deleted_at')->where('member_infos.gender',$userinfo->getMemberInfo->gender)->whereIn('hall_booking_infos.status',['Pending','Accepted','Paid','Updated'])->where('hall_setting_id',$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->id)->where('quota_id',$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->id)->count();

                    if($totalGenderBooking>=$totalGenderQuote || $totalGenderQuote<=0){
                        $this->apiArray['message'] = "We regretted to inform you that the current HKU residential hall quota is full, and your request cannot be processed. To help you ease the situation, we have arranged hotel discounts for your consideration.";
                        $this->apiArray['errorCode'] = 6;
                        $this->apiArray['error'] = true;
                        return response()->json($this->apiArray, 200);
                    }
                    
                    $date1 = $checkProgram->start_date - 172800;
                    $date2 = $checkProgram->end_date + 86400;
                    $days = (int)(($date2 - $date1)/86400);
                    $amount = $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->unit_price*($days-1);
                    $lastBooking = HallBookingInfo::select('booking_number')->whereNotNull('booking_number')->orderBy('booking_number','DESC')->first();
                    if (isset($lastBooking->id) && !empty($lastBooking->id)) {
                        $bookingNumber = $lastBooking->id + 1;
                    }else{
                        $bookingNumber = 1;
                    }
                    $data                       = new HallBookingInfo();
                    $data['booking_number']     = $bookingNumber;
                    $data['user_type_id']       = $userinfo->getMemberInfo->id;
                    $data['hall_setting_id']    = $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->id;
                    $data['quota_id']           = $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->id;
                    $data['user_type']          = str_replace(array('"','[',']'),"",$userinfo->getRoleNames());
                    $data['programme_code']     = $checkProgram->programme_code;
                    $data['start_date']         = (isset($checkProgram->start_date) && !empty($checkProgram->start_date))?$checkProgram->start_date:null;
                    $data['end_date']           = (isset($checkProgram->end_date) && !empty($checkProgram->end_date))?$checkProgram->end_date:null;
                    $data['check_in_date']      = (isset($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->check_in_date) && !empty($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->check_in_date))?$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->check_in_date:null;
                    $data['check_out_date']     = (isset($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->check_out_date) && !empty($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->check_out_date))?$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->check_out_date:null;
                    $data['check_in_time']      = $checkProgram->start_date;
                    $data['check_out_time']     = $checkProgram->end_date;
                    $data['hall_result_date']   = time();
                    $data['amount']             = $amount;                
                    $data['status']             = 'Pending';                
                    $data['application_id']     = $userinfo->getMemberInfo->application_number;
                    $data->save();

                    if(!empty($data->id)){
                        $new_booking_id = $data->id;
                    }else{
                        $new_booking_id = 1;
                    }
                    $bookingNumber = str_pad($new_booking_id, 7, "0", STR_PAD_LEFT);
                    // $bookingNumber = "HKUSI".str_pad($new_booking_id, 7, "0", STR_PAD_LEFT);
                    $data->update(['booking_number'=>$bookingNumber]);

                    $detail = [];
                    $detail['amount']           = $amount;
                    $detail['booking_number']   = $bookingNumber;
                    $detail['unit_price']       = $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->unit_price;
                    $detail['college_name']     = 'Test Collage';
                    $textResponse = 'From '.date("Y-m-d", $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->check_in_date).' to '.date("Y-m-d", $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->check_out_date).' ('. $days-1 .' Nights).';
                    $detail['days_text']        =  $textResponse;
                    $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->updateBookingQuota('minus');

                    $resultDays = 0;
                    $resultDays = $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->hall_result_days;
                    
                    if($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->status){
                        if ($checkProgram->getQuotaProgrammeDetail->getQuotaDetail->status=='1') {
                            $type = strtolower($userinfo->getMemberInfo->gender);                    
                            $totalGenderQuotaReleased = $checkProgram->getQuotaProgrammeDetail->getQuotaDetail->$type;

                            $totalGenderBookingReleased = HallBookingInfo::select('hall_booking_infos.id')->leftJoin('member_infos', function ($join) {
                                    $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                                })->whereNull('member_infos.deleted_at')->where('member_infos.gender',$userinfo->getMemberInfo->gender)->whereIn('hall_booking_infos.status',['Accepted','Paid','Updated'])->where('hall_setting_id',$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->getHallSettingDetail->id)->where('quota_id',$checkProgram->getQuotaProgrammeDetail->getQuotaDetail->id)->count();

                            if($totalGenderBookingReleased >= $totalGenderQuotaReleased || $totalGenderQuotaReleased <= 0){
                                $mailInfo = [
                                    'given_name'         => $userinfo->getMemberInfo->given_name,
                                    'application_number' => $userinfo->getMemberInfo->application_number,
                                    'hall_result_days' => (isset($resultDays) && $resultDays != '')? $resultDays :null,
                                ];
                                $details = ['type'=>'HallReservation','email' => $userinfo->email,'mailInfo' => $mailInfo];
                                SendEmailJob::dispatch($details);
                                $this->apiArray['data'] =null;
                                $this->apiArray['hall_result_days'] = $resultDays;
                                $this->apiArray['message'] = 'Your request has been successfully submitted.';
                            }else{
                                $data->update(['status' => 'Accepted']);
								$data->update(['payment_deadline_date'=>time()]);
                                $this->apiArray['data'] =$detail;
                                $this->apiArray['message'] = 'Your booking request accepted successfully.';
                            }
                        }else{

                            $mailInfo = [
                                'given_name'         => $userinfo->getMemberInfo->given_name,
                                'application_number' => $userinfo->getMemberInfo->application_number,
                                'hall_result_days' => (isset($resultDays) && $resultDays != '')? $resultDays :null,
                            ];
                            $details = ['type'=>'HallReservation','email' => $userinfo->email,'mailInfo' => $mailInfo];
                            SendEmailJob::dispatch($details);
                            $this->apiArray['data'] =null;
                            $this->apiArray['hall_result_days'] = $resultDays;
                            $this->apiArray['message'] = 'Your request has been successfully submitted.';
                        }
                        $this->apiArray['errorCode'] = 0;
                        $this->apiArray['error'] = false;
                        return response()->json($this->apiArray, 200);
                    }else{

                        $mailInfo = [
                            'given_name'         => $userinfo->getMemberInfo->given_name,
                            'application_number' => $userinfo->getMemberInfo->application_number,
                            'hall_result_days' => (isset($resultDays) && $resultDays != '')? $resultDays :null,
                        ];
                        $details = ['type'=>'HallReservation','email' => $userinfo->email,'mailInfo' => $mailInfo];
                        SendEmailJob::dispatch($details);

                        $this->apiArray['data'] =null;
                        $this->apiArray['hall_result_days'] = $resultDays;
                        $this->apiArray['message'] = 'Your request has been successfully submitted.';
                        $this->apiArray['errorCode'] = 0;
                        $this->apiArray['error'] = false;
                        return response()->json($this->apiArray, 200);
                    }
                    $this->apiArray['message'] = "Quota not found.";
                    $this->apiArray['errorCode'] = 3;
                    $this->apiArray['error'] = true;
                    return response()->json($this->apiArray, 200);
                }else{
                    $this->apiArray['message'] = "Quota not found.";
                    $this->apiArray['errorCode'] = 3;
                    $this->apiArray['error'] = true;
                    return response()->json($this->apiArray, 200);
                }
            }else{
                $this->apiArray['message'] = "Invalid Programme.";
                $this->apiArray['errorCode'] = 3;
                $this->apiArray['error'] = false;
                return response()->json($this->apiArray, 200);
            }
        /*} catch (\Exception $e) {
            $this->apiArray['message'] = 'Something is wrong, please try after some time'.$e->getMessage();
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }*/
    }
    /* End */

    /* Get myAccomandation API on 21/03/2023 by Vinod */
    public function myAccomandation(Request $request)
    {
        try {
            $inputs = $request->all();
             $userinfo = $request->user('sanctum');
            $this->apiArray['state'] = 'myaccomandation';
            /*Check header */
            $data = NULL;
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/
            $data = [];
            $typeList = ['Completed','Cancelled','Rejected'];
            if(isset($inputs['type']) && $inputs['type']=='c'){ 
                $typeList = ['Pending','Accepted','Paid','Updated','Unpaid'];
            }
            $totalBooking = HallBookingInfo::select('hall_booking_infos.*')->leftJoin('member_infos', function ($join) {
                $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
            })->whereNull('member_infos.deleted_at')->whereIn('hall_booking_infos.status',$typeList)->where('member_infos.id',$userinfo->getMemberInfo->id)->paginate(5);
            if (count($totalBooking)) {
                $payment_data = '';
                foreach ($totalBooking as $key => $value) {
                    if($value->status=='Accepted'){
                        $textResponse = 'From '.date("Y-m-d", $value->check_in_date).' to '.date("Y-m-d", $value->check_out_date).' ('.(int)((($value->check_out_date - $value->check_in_date))/86400).' Nights).';
                        $payment_data = [
                            'booking_no' => $value->booking_number,
                            'hall_name' => '',
                            'amount' => $value->amount,
                            'unit_price' => (isset($value->getHallsetting->unit_price) && !empty($value->getHallsetting->unit_price))?$value->getHallsetting->unit_price:null,
                            'days_text' => $textResponse,
                        ];
                    }
                    $resultDays = 0;
                    $payment_status = '';
                    $statusVal = null;
                    $allowPayments = false;
                    $resultDays = $value->getHallsetting->hall_result_days;

                    if(isset($value->allPaymentRecords) && count($value->allPaymentRecords)){
                        foreach ($value->allPaymentRecords as $keyPayment => $valuePayment) {
                            if($valuePayment->payment_status=='PAID'){
                                $payment_status = $valuePayment->payment_status;
                                break;
                            }else{
                                if(!empty($valuePayment->payment_status)){
                                    $payment_status = $valuePayment->payment_status;
                                }                                
                            }
                        }
                    }

                    if (isset($value->getPaymentData) && !empty($value->getPaymentData)) {
                        $payment_status = !empty($value->getPaymentData->payment_status)?$value->getPaymentData->payment_status:'Processing';
                    }

                    if(isset($value->status) && $value->status == 'Accepted'){
                        $allowPayments = true;
                    }else{
                        if ($payment_status == 'CANCELLED' || $payment_status == 'Processing' || $payment_status == 'EXPIRED' || $payment_status == 'REFUNDED' || $payment_status == 'REJECTED' || $payment_status == 'UATPAID'){
                               if($value->status !='Completed' && $value->status !='Pending' && $value->status !='Paid' && $value->status !='Cancelled' && $value->status !='Updated' && $value->status !='Rejected' && $value->status !='Unpaid'){
                                    $allowPayments = true;
                            }
                        } 
                    }

                    if($payment_status=='PENDING'){
                        $statusVal = 'Your payment is being processed currently and a confirmation email will be sent to you once the payment is successfully processed. Thank you for your patience.';
                    }

                    /*if($value->status!='Paid' && $value->status!='Accepted'){
                        $payment_status = 'PENDING';
                    }*/

                    $data[] = [
                        'booking_id' => $value->id,
                        'check_in_date' => date('Y-m-d',$value->check_in_date),
                        'check_out_date' => date('Y-m-d',$value->check_out_date),
                        'check_in_time' => (isset($value->getQuotaHallDetail->check_in_time) && !empty($value->getQuotaHallDetail->check_in_time))?date('H:i',$value->getQuotaHallDetail->check_in_time):null,
                        'check_out_time' => (isset($value->getQuotaHallDetail->check_out_time) && !empty($value->getQuotaHallDetail->check_out_time))?date('H:i',$value->getQuotaHallDetail->check_out_time):null,
                        'hall_name' => (isset($value->getQuotaHallDetail->college_name) && !empty($value->getQuotaHallDetail->college_name))?$value->getQuotaHallDetail->college_name:null,
                        'address' => (isset($value->getQuotaHallDetail->address) && !empty($value->getQuotaHallDetail->address))?$value->getQuotaHallDetail->address:null,
                        'booking_type' => (isset($value->booking_type) && !empty($value->booking_type))?$value->booking_type:null,
                        'room_type' => (isset($value->getQuotaHallDetail->room_type) && !empty($value->getQuotaHallDetail->room_type))?$value->getQuotaHallDetail->room_type:null,
                        'room_no' =>  (isset($value->getQuotaRoomDetail->room_code) && !empty($value->getQuotaRoomDetail->room_code))?$value->getQuotaRoomDetail->room_code:null,
                        'total_days' => (int)((($value->check_out_date - $value->check_in_date))/86400),
                        'status' => $value->status,
                        'status_message' => (isset($value->status) && $value->status=='Pending')? "Your request is being processed. The result will be updated within ". $resultDays ." days after submission." :$statusVal,
                        'hall_image' => null,
                        'payment_data' => $payment_data,
                        'payment_status'  => $payment_status,
                        'allow_payments'  => $allowPayments,
                    ];
                }
            }
            $this->apiArray['data']         =   $data;
            $this->apiArray['totalPage']    =   $totalBooking->lastPage();
            $this->apiArray['message']      =   'My Accommodation';
            $this->apiArray['errorCode']    =   0;
            $this->apiArray['error'] = false;

            return response()->json($this->apiArray, 200);
        } catch (\Exception $e) {
            $this->apiArray['message'] = 'Something is wrong, please try after some time'.$e->getMessage();
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }
    /*End */
    
    public function updatePaymentData(Request $request){
        try {
            $inputs = $request->all();
            // echo "<pre>";
            // print_r($request['transactions']['0']['payNo']);die();
            $userinfo = $request->user('sanctum');
            $this->apiArray['state'] = 'updatePaymentData';
            /*Check header */
            $data = $request->data;
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/

            if (isset($request['transactions']) && !empty($request['transactions'])) {
                $key = 0;
                $key = count($request['transactions']) - 1;
                $payment_id = explode('-', $request['orderNo']);
                $paymentDetails = Payment::where('order_no',$request['orderNo'])->where('payment_id',$payment_id[0])->orderby('id','DESC')->first();
                if(!empty($paymentDetails)){
                    Payment::where('id',$paymentDetails->id)->update([
                        'application_id'        => $userinfo->getMemberInfo->application_number,
                        'transaction_id'        => $request['transactions'][$key]['payNo'],
                        'reference_no'          => $request['transactions'][$key]['refNo'],
                        'card_no'               => $request['transactions'][$key]['cardNo'],
                        'approval_code'         => $request['transactions'][$key]['approvalCode'],
                        'merchant_id'           => $request['transactions'][$key]['merchantId'],
                        'expiry_time'           => strtotime($request['transactions'][$key]['expiryTime']),
                        'pay_time'              => strtotime($request['transactions'][$key]['payTime']),
                        'amount'                => $request['transactions'][$key]['amt'],
                        'payment_method'        => $request['transactions'][$key]['paymentMethod'],
                        'pay_type'              => $request['transactions'][$key]['paymentType'],
                        'status'                => ($request['transactions'][$key]['status'] == 'PAID')?'1':'0',
                        'pay_result'            => $request['transactions'][$key]['payResult'],
                        'payment_status'        => $request['transactions'][$key]['status'],
                    ]);

                    $getBookingDetails =  HallBookingInfo::where('booking_number',$payment_id[0])->first();
                    if (isset($getBookingDetails) && !empty($getBookingDetails)) {
                        if (isset($request['transactions'][$key]['status']) && $request['transactions'][$key]['status'] == 'PAID') {
                            $getBookingDetails->update(['status'=>'Paid']);
                            $hall_payment_days = (isset($getBookingDetails->getHallsetting->hall_payment_days) && !empty($getBookingDetails->getHallsetting->hall_payment_days))?$getBookingDetails->getHallsetting->hall_payment_days:'0';
                            $hall_confirmation_date = $getBookingDetails->payment_deadline_date + ($hall_payment_days * 86400);
                            $mailInfo = [
                                'given_name'                => $userinfo->getMemberInfo->given_name,
                                'application_number'        => $userinfo->getMemberInfo->application_number,
                                'Hall_confirmation_Date'    => date('Y-m-d',$hall_confirmation_date),
                            ];
                            $paymentsuccess = ['type'=>'PaymentSuccessfull','email' =>$userinfo->email,'mailInfo' => $mailInfo];
                            SendEmailJob::dispatch($paymentsuccess);
                            $this->apiArray['data']         =   null;
                            $this->apiArray['message']      =   'Payment Successful.';
                            $this->apiArray['errorCode']    =   0;
                            $this->apiArray['error'] = false;
                        }else{
                            $this->apiArray['data']         =   null;
                            $this->apiArray['message']      =   'Payment failed.';
                            $this->apiArray['errorCode']    =   3;
                            $this->apiArray['error'] = false;
                        }
                    }else{
                        $this->apiArray['data']         =   null;
                        $this->apiArray['message']      =   'Booking not found.';
                        $this->apiArray['errorCode']    =   3;
                        $this->apiArray['error'] = false;
                    }
                }else{
                    $this->apiArray['data']         =   null;
                    $this->apiArray['message']      =   'Payment status not found.';
                    $this->apiArray['errorCode']    =   3;
                    $this->apiArray['error'] = false;
                }
            }elseif (isset($request['orderNo']) && !empty($request['orderNo'])) {
                if (isset($request['status']) && !empty($request['status'])){
                    Payment::where('order_no',$request['orderNo'])->update([
                        'payment_status'                => $request['status']
                    ]);
                }else{
                    $payment_id = explode('-', $request['orderNo']);
                    $bookighall = Payment::create([
                        'application_id'        => $userinfo->getMemberInfo->application_number,
                        'payment_id'            => $payment_id[0],
                        'order_no'              => $request['orderNo'],
                        'service_type'          => 'Hall Booking',
                        'payment_status'        => 'Processing',
                        'status'                => 0,
                    ]);
                }
                $this->apiArray['data']         =   null;
                $this->apiArray['errorCode']    =   0;
                $this->apiArray['error']        = false;
            }
            return response()->json($this->apiArray, 200);
        } catch (\Exception $e) {
            $this->apiArray['message'] = 'Something is wrong, please try after some time'.$e->getMessage();
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }

    public function getPaymentDetails(Request $request){
        try{
            $inputs = $request->all();
            
            $userinfo = $request->user('sanctum');
            $this->apiArray['state'] = 'getPaymentDetails';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            $inputs = $request->all();            
            $validator = Validator::make($inputs, [
                'booking_id' => ['required'],
            ]);
            if($validator->fails()){
                $this->apiArray['message'] = $validator->messages()->first();
                $this->apiArray['errorCode'] = 2;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }
            $data = HallBookingInfo::where('booking_number',$inputs['booking_id'])->first();
            if(!empty($data)){
                $days = 0;
                $date1 = ($data->getQuotaDetail->check_in_date ?? 0) - 86400;
                $date2 = ($data->getQuotaDetail->check_out_date ?? 0);
                $days = (int)(($date2 - $date1)/86400);
                
                $data = array( 
                    'booking_no' =>   $data->booking_number,        
                    'hall_name' =>   $data->getQuotaHallDetail->college_name ?? 'N/A',        
                    'amount' =>   $data->amount,        
                    'unit_price' =>   $data->getHallsetting->unit_price ?? 'N/A',        
                    'days_text' =>   'From '. date('Y-m-d',$data->start_date). ' to ' . date('Y-m-d',$data->end_date).' ('.(isset($days) && !empty($days)? ($days - 1).' Nights' : 'N/A').') '
                );           
                $this->apiArray['data'] = $data;
                $this->apiArray['message'] = 'Payment details found successfully.';
                $this->apiArray['errorCode'] = 0;
                $this->apiArray['error'] = false;
                return response()->json($this->apiArray, 200);
            }else{
                $this->apiArray['message'] = 'Payment details not found.';
                $this->apiArray['errorCode'] = 4;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 500);
            }          
        }catch (\Exception $e){
            $this->apiArray['message'] = 'Something is wrong, please try after some time';
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 500);
        }
    }

    public function getBookingDetails(Request $request){
        try {
            $inputs = $request->all();
            $userinfo = $request->user('sanctum');
            $this->apiArray['state'] = 'getbookingdetails';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            $inputs = $request->all();            
            $validator = Validator::make($inputs, [
                'booking_id' => ['required'],
            ]);
            if($validator->fails()){
                $this->apiArray['message'] = $validator->messages()->first();
                $this->apiArray['errorCode'] = 2;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }
            $data = HallBookingInfo::where('id',$inputs['booking_id'])->where('application_id',$userinfo->getMemberInfo->application_number)->first();
            if (!empty($data)) {
                $programmeCode = $payment = $transationNo = $paymentStatus = [];
                if (isset($data->booking_type) && $data->booking_type == 'g') {
                    if (isset($data->getGroupHallInfo) && count($data->getGroupHallInfo)) {
                        foreach ($data->getGroupHallInfo as $key => $groupHallInfo) {
                            $programmeCode[] = $groupHallInfo->programme_code ."/". $groupHallInfo->getProgrammeDetail->programme_name;
                            $payment[] = $groupHallInfo->programme_code.": HKD ". $groupHallInfo->amount;
                            $transationNo[] = $groupHallInfo->programme_code.": ". ((isset($groupHallInfo->getPaymentData->transaction_id) && !empty($groupHallInfo->getPaymentData->transaction_id)) ? $groupHallInfo->getPaymentData->transaction_id : '-');
                            if (isset($groupHallInfo->getPaymentData->payment_status) && !empty($groupHallInfo->getPaymentData)) {
                                $paymentStatus[] = $data->programme_code.": ". $data->getPaymentData->payment_status;
                            }else{
                                $paymentStatus[] = $groupHallInfo->programme_code.": Not yet paid";
                            }
                        }
                    }
                }else{
                    $programmeCode[] = $data->programme_code ."/". $data->getProgrammeDetail->programme_name;
                    $payment[] = $data->programme_code.": HKD ".$data->amount;
                    $transationNo[] = $data->programme_code.": ".((isset($data->getPaymentData->transaction_id) && !empty($data->getPaymentData->transaction_id)) ? $data->getPaymentData->transaction_id : '-');
                    if (isset($data->getPaymentData->payment_status) && !empty($data->getPaymentData)) {
                        $paymentStatus[] = $data->programme_code.": ". $data->getPaymentData->payment_status;
                    }else{
                        $paymentStatus[] = $data->programme_code.": Not yet paid";
                    }
                }

                $payment_status = '';
                $allowPayments = false;

                if(isset($data->allPaymentRecords) && count($data->allPaymentRecords)){
                    foreach ($data->allPaymentRecords as $keyPayment => $valuePayment) {
                        if($valuePayment->payment_status=='PAID'){
                            $payment_status = $valuePayment->payment_status;
                            break;
                        }else{
                            if(!empty($valuePayment->payment_status)){
                                $payment_status = $valuePayment->payment_status;
                            }
                        }
                    }
                }

                if (isset($data->status) && $data->status == 'Accepted') {
                        $allowPayments = true;
                }else{
                    if ($payment_status == 'CANCELLED' || $payment_status == 'Processing' || $payment_status == 'EXPIRED' || $payment_status == 'REFUNDED' || $payment_status == 'REJECTED' || $payment_status == 'UATPAID'){
                        if($data->status !='Completed' && $data->status !='Pending' && $data->status !='Paid' && $data->status !='Cancelled' && $data->status !='Updated' && $data->status !='Rejected' && $data->status !='Unpaid'){
                            $allowPayments = true;
                        }
                    } 
                }
                
                /*
                if($data->status!='Paid' && $data->status!='Accepted'){
                    $payment_status = 'PENDING';
                }*/

                // dd($payment,$programmeCode,$transationNo,$paymentStatus);
                $details =[];
                $details['id'] = $data->id;
                $details['booking_number'] = $data->booking_number;
                $details['application_id'] = (isset($data->getMemberdata->application_number) && !empty($data->getMemberdata->application_number))?$data->getMemberdata->application_number:"R20230052";
                $details['programme_name'] = (isset($programmeCode) && count($programmeCode))? $programmeCode :null;
                $details['check_in_date']  =  (isset($data->check_in_date) && !empty($data->check_in_date))?(date('Y-m-d',$data->check_in_date)):null;
                $details['check_out_date'] =  (isset($data->check_out_date) && !empty($data->check_out_date))?(date('Y-m-d',$data->check_out_date)):null;
                $details['check_in_time'] = (isset($data->getQuotaHallDetail->check_in_time) && !empty($data->getQuotaHallDetail->check_in_time))?date('H:i',$data->getQuotaHallDetail->check_in_time):null;
                $details['check_out_time'] = (isset($data->getQuotaHallDetail->check_out_time) && !empty($data->getQuotaHallDetail->check_out_time))?date('H:i',$data->getQuotaHallDetail->check_out_time):null;
                $details['hall_name'] = (isset($data->getQuotaHallDetail->college_name) && !empty($data->getQuotaHallDetail->college_name))?$data->getQuotaHallDetail->college_name:null;
                $details['unit_price'] = (isset($data->getHallsetting->unit_price) && !empty($data->getHallsetting->unit_price))?$data->getHallsetting->unit_price:null;
                $details['address'] = (isset($data->getQuotaHallDetail->address) && !empty($data->getQuotaHallDetail->address))?$data->getQuotaHallDetail->address:null;
                $details['room_type'] = (isset($data->getQuotaHallDetail->room_type) && !empty($data->getQuotaHallDetail->room_type))?$data->getQuotaHallDetail->room_type:null;
                $details['booking_type'] = (isset($data->booking_type) && !empty($data->booking_type))?$data->booking_type:null;
                $details['room_no'] =  (isset($data->getQuotaRoomDetail->room_code) && !empty($data->getQuotaRoomDetail->room_code))?$data->getQuotaRoomDetail->room_code:null;
                $details['collage_name']   = (isset($data->getQuotaHallDetail->college_name) && !empty($data->getQuotaHallDetail->college_name))?$data->getQuotaHallDetail->college_name:null;
                $details['total_amount'] = (isset($data->amount) && !empty($data->amount))? $data->amount :null;
                $details['status'] = $data->status;
                $details['transaction_no'] =(isset($transationNo) && count($transationNo))? $transationNo :null;
                $details['payment_status'] = (isset($payment_status) && !empty($payment_status))? $payment_status :'';
                $details['total_days'] = (int)((($data->check_out_date - $data->check_in_date))/86400);
                $details['allow_payments'] = $allowPayments;

                $this->apiArray['data']         =   $details;
                $this->apiArray['message']      =   'Success';
                $this->apiArray['errorCode']    =   0;
                $this->apiArray['error'] = false;
            }else{
                $this->apiArray['message'] = "Invalid Booking id.";
                $this->apiArray['errorCode'] = 3;
                $this->apiArray['error'] = false;
                return response()->json($this->apiArray, 200);
            }
            
            return response()->json($this->apiArray, 200);
        } catch (\Exception $e) {
            $this->apiArray['message'] = 'Something is wrong, please try after some time'.$e->getMessage();
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }



    /* Get bookAaccommodation API on 21/03/2023 by Vinod */
    public function bookProgrammeEvent(Request $request)
    {   
        try {
            $inputs = $request->all();
            $userinfo = $request->user('sanctum');
            //dd($inputs);
            $this->apiArray['state'] = 'bookProgrammeEvent';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/
            $inputs1 = array();
            if(count($inputs)){
                foreach ($inputs as $key => $value) {
                    $inputs1['event_id'][$key] = $value['event_id'];
                    $inputs1['no_of_tickets'][$key] = $value['no_of_tickets'];
                }
            }
            $validator = Validator::make($inputs1, [
                'event_id' => ['required'],
                'no_of_tickets' => ['required']
            ]);
            if($validator->fails()){
                $this->apiArray['message'] = $validator->messages()->first();
                $this->apiArray['errorCode'] = 2;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }
            $payments = EventPayment::where('service_type','Event Booking')->orderBy('payment_id','DESC')->first();
            //dd($payment_id);
            if(!empty($payments)){
                $payment_id = str_replace("E","",$payments->payment_id)+1;
            }else{
                $payment_id = 1;
            }
            $totalamount = 0;
            $eventList = array();
            if(count($inputs1['event_id'])){
                $errorMessage = '';
                foreach ($inputs1['event_id'] as $key => $value) {
                    $userProgramme = '';
                    $programmeDate = '';
                    $eventData = EventSetting::where('id',$value)->where('status','Enabled')->first();
                    if(!empty($eventData)){
                        if(isset($eventData->getEventProgrammes) && count($eventData->getEventProgrammes)){
                            foreach ($eventData->getEventProgrammes as $keypro => $valuepro){
                                if(isset($valuepro->getProgrammeDetailApi) && !empty($valuepro->getProgrammeDetailApi)){
                                    if($valuepro->getProgrammeDetailApi->checkMemberProgramme($userinfo->getMemberInfo->id)){
                                        $userProgramme = 'Yes';
                                    }
                                    if($valuepro->getProgrammeDetailApi->start_date<=$eventData->date && $valuepro->getProgrammeDetailApi->end_date>=$eventData->date){
                                        $programmeDate = 'Yes';
                                    }
                                }
                            }
                        }else{
                            //$errorMessage.= "No programme associated with the event.";
                            $this->apiArray['message'] = "No programme associated with the event.";
                            $this->apiArray['errorCode'] = 4;
                            $this->apiArray['error'] = true;
                            return response()->json($this->apiArray, 200);
                        }

                        if(empty($userProgramme)){
                            //$errorMessage.= "Event ".$eventData->event_name." programme is not allocated to you.";
                            $this->apiArray['message'] = "Event ".$eventData->event_name." programme is not allocated to you.";
                            $this->apiArray['errorCode'] = 4;
                            $this->apiArray['error'] = true;
                            return response()->json($this->apiArray, 200);
                        }
                        if(empty($programmeDate)){
                            //$errorMessage.= "Event date is not matched with programme date.";
                            $this->apiArray['message'] = "Event date is not matched with programme date.";
                            $this->apiArray['errorCode'] = 4;
                            $this->apiArray['error'] = true;
                            return response()->json($this->apiArray, 200);
                        }

                        if($eventData->date<time()){
                            //$errorMessage.= "You can not book same date Evnet.";
                            $this->apiArray['message'] = "You can not book same date Evnet";
                            $this->apiArray['errorCode'] = 4;
                            $this->apiArray['error'] = true;
                            return response()->json($this->apiArray, 200);
                        }

                        if($eventData->application_deadline<time()){
                            //$errorMessage.= "Event deadline is over.";
                            $this->apiArray['message'] = "Event deadline is over";
                            $this->apiArray['errorCode'] = 4;
                            $this->apiArray['error'] = true;
                            return response()->json($this->apiArray, 200);
                        }

                        if($eventData->quota_balance<=0){
                            //$errorMessage.= "We regret to inform you that the current quota for ".$eventData->event_name." has been fully booked, and unfortunately, we are unable to process your reservation request.";
                            $this->apiArray['message'] = "We regret to inform you that the current quota for ".$eventData->event_name." has been fully booked, and unfortunately, we are unable to process your reservation request.";
                            $this->apiArray['errorCode'] = 4;
                            $this->apiArray['error'] = true;
                            return response()->json($this->apiArray, 200);
                        }

                        if($eventData->quota_balance<$inputs1['no_of_tickets'][$key]){
                            //$errorMessage.= "You can not choose no of seat.";
                            $this->apiArray['message'] = "You can not choose no of seat.";
                            $this->apiArray['errorCode'] = 4;
                            $this->apiArray['error'] = true;
                            return response()->json($this->apiArray, 200);
                        }

                        if($eventData->booking_limit<$inputs1['no_of_tickets'][$key]){
                            //$errorMessage.= "Max seat you can choose is ".$eventData->booking_limit;
                            $this->apiArray['message'] = "Max seat you can choose is ".$eventData->booking_limit;
                            $this->apiArray['errorCode'] = 4;
                            $this->apiArray['error'] = true;
                            return response()->json($this->apiArray, 200);
                        }

                        if(!empty($eventData->checkMemberEvent($userinfo->getMemberInfo->application_number))){
                            //$errorMessage.= "Event is already booked by you.";
                            $this->apiArray['message'] = "Event is already booked by you.";
                            $this->apiArray['errorCode'] = 4;
                            $this->apiArray['error'] = true;
                            return response()->json($this->apiArray, 200);
                        }
                    }else{                        
                        //$errorMessage.= "There is no event.";
                        $this->apiArray['message'] = "There is no event.";
                        $this->apiArray['errorCode'] = 4;
                        $this->apiArray['error'] = true;
                        return response()->json($this->apiArray, 200);
                    }
                }
                /*if(!empty($errorMessage)){
                    $this->apiArray['message'] = $errorMessage;
                    $this->apiArray['errorCode'] = 4;
                    $this->apiArray['error'] = true;
                    return response()->json($this->apiArray, 200);
                }*/
                $onlyFreeEvent = 'Yes';
                foreach ($inputs1['event_id'] as $key => $value) {
                    $userProgramme = '';
                    $programmeDate = '';
                    $booking_status = 'Paid';
                    $eventData = EventSetting::where('id',$value)->where('status','Enabled')->first();
                    if(!empty($eventData)){
                        if($eventData['unit_price']>0){
                            $onlyFreeEvent = 'No';
                            $booking_status = 'Pending';
                        } else { // free event case
                            $mailInfo = [
                                'given_name'          => $userinfo->getMemberInfo->given_name,
                                'application_number'  => $userinfo->getMemberInfo->application_number,
                                'event_details'       => $eventData,
                            ];
                            $paymentsuccess = ['type'=>'EventPaymentSuccessfull','email' =>$userinfo->email,'mailInfo' => $mailInfo];
                            SendEmailJob::dispatch($paymentsuccess);
                        }
                        
                        EventBooking::create([
                            'event_id'              => $value,
                            'application_id'        => $userinfo->getMemberInfo->application_number,
                            'payment_id'            => "E".str_pad($payment_id, 7, "0", STR_PAD_LEFT),
                            'no_of_seats'           => $inputs1['no_of_tickets'][$key],
                            'unit_price'            => $eventData['unit_price'],
                            'booking_status'        => $booking_status,
                        ]);
                        
                        $eventData->decrement('quota_balance',$inputs1['no_of_tickets'][$key]);
                        $totalamount+= $inputs1['no_of_tickets'][$key] * $eventData['unit_price'];

                        $eventList[$key]['event_name']      = $eventData['event_name'];
                        $eventList[$key]['amount']          = $eventData['unit_price'];
                        $eventList[$key]['event_date']      = date("Y-m-d",$eventData['date']);
                        $eventList[$key]['event_time']      = date("H:i",$eventData['time']);
                        $eventList[$key]['no_of_tickets']   = $inputs1['no_of_tickets'][$key];
                    }
                }
                $apimessages = '';
                if($onlyFreeEvent=='No'){          
                    $order_no = $this->getRandomString(); // J1NMDQAFD5LM9DK5
                    $paymentEvent = EventPayment::create([ 
                        'application_id'        => $userinfo->getMemberInfo->application_number,
                        'payment_id'            => "E".str_pad($payment_id, 7, "0", STR_PAD_LEFT),
                        'order_no'              => $order_no,
                        'service_type'          => 'Event Booking',
                        'amount'                => $totalamount,
                        'payment_status'        => 'PROCESSING',
                        'event_payment_status'  => 'Pending',
                        'status'                => 0,
                    ]);
                    $data['order_no']               =   $order_no;
                    $data['amount']                 =   $totalamount;
                    $data['eventList']              =   $eventList;
                    $this->apiArray['data']         =   $data;
                    $apimessages = 'Event added to your cart.';
                }else{        
                    $order_no = $this->getRandomString(); // J1NMDQAFD5LM9DK5
                    $paymentEvent = EventPayment::create([
                        'application_id'        => $userinfo->getMemberInfo->application_number,
                        'payment_id'            => "E".str_pad($payment_id, 7, "0", STR_PAD_LEFT),
                        'order_no'              => $order_no,
                        'service_type'          => 'Event Booking',
                        'amount'                => $totalamount,
                        'payment_status'        => 'PAID',
                        'event_payment_status'  => 'Paid',
                        'status'                => 1,
                    ]);
                    $this->apiArray['data']         =   '';

                    if($paymentEvent->getEventBookingDetails && count($paymentEvent->getEventBookingDetails)){
                        foreach($paymentEvent->getEventBookingDetails as $keyEvent => $valueEvent){
                            $mailInfo = [
                                'given_name'            => $userinfo->getMemberInfo->given_name,
                                'application_number'    => $userinfo->getMemberInfo->application_number,
                                'event_details'         => $valueEvent->getEventSetting,
                            ];
                            $paymentsuccess = ['type'=>'EventPaymentSuccessfull','email' =>$userinfo->email,'mailInfo' => $mailInfo];
                            SendEmailJob::dispatch($paymentsuccess);
                        }
                    }
                    $apimessages = 'Event booked successfully.';
                }
                MemberEventCart::where('application_id',$userinfo->getMemberInfo->application_number)->delete();
                $this->apiArray['message']      =   $apimessages;
                $this->apiArray['errorCode']    =   0;
                $this->apiArray['error']        =   false;
                return response()->json($this->apiArray, 200);
            }
            $this->apiArray['message'] = "Error in event booking can not choose no of seat";
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            return response()->json($this->apiArray, 200);            
        } catch (\Exception $e) {
            $this->apiArray['message'] = 'Something is wrong, please try after some time'.$e->getMessage();
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }
    /* End */


    public function updatePaymentStatus(Request $request){ 
        try {
            $inputs = $request->all();
            $userinfo = $request->user('sanctum');
            $this->apiArray['state'] = 'updatePaymentStatus';
            /*Check header */
            $headers = getallheaders();
            if (!$this->verifyTokens($headers['Authkey'])){
                $this->apiArray['errorCode'] = 1;
                $this->apiArray['error'] = true;
                $this->apiArray['data'] = null;
                return response()->json($this->apiArray, 401);
            }
            /*End*/
            $validator = Validator::make($inputs, [
                'orderNo' => ['required'],
            ]);
            if($validator->fails()){
                $this->apiArray['message'] = $validator->messages()->first();
                $this->apiArray['errorCode'] = 2;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }
            $paymentDetails = EventPayment::where('order_no',$request['orderNo'])->whereNotIn('payment_status',['PAID','EXPIRED','CANCELLED'])->first();
            if(!empty($paymentDetails)){
                if (isset($request['transactions']) && !empty($request['transactions'])) {     
                    $key = 0;
                    $key = count($request['transactions']) - 1;
                    $event_payment_status = 'Pending';
                    if($request['transactions'][$key]['status'] == 'PAID'){
                        $event_payment_status = 'Paid';
                    }
                    if($request['transactions'][$key]['status'] == 'CANCELLED' || $request['transactions'][$key]['status'] == 'EXPIRED' || $request['transactions'][$key]['status'] == 'REJECTED' || $request['transactions'][$key]['status'] == 'REFUNDED'){
                        $event_payment_status = 'Cancelled';
                    }
                    EventPayment::where('id',$paymentDetails->id)->update([
                        'application_id'        => $userinfo->getMemberInfo->application_number,
                        'transaction_id'        => $request['transactions'][$key]['payNo'],
                        'reference_no'          => $request['transactions'][$key]['refNo'],
                        'card_no'               => $request['transactions'][$key]['cardNo'],
                        'approval_code'         => $request['transactions'][$key]['approvalCode'],
                        'merchant_id'           => $request['transactions'][$key]['merchantId'],
                        'expiry_time'           => strtotime($request['transactions'][$key]['expiryTime']),
                        'pay_time'              => strtotime($request['transactions'][$key]['payTime']),
                        'amount'                => $request['transactions'][$key]['amt'],
                        'payment_method'        => $request['transactions'][$key]['paymentMethod'],
                        'pay_type'              => $request['transactions'][$key]['paymentType'],
                        'status'                => ($request['transactions'][$key]['status'] == 'PAID')?'1':'0',
                        'pay_result'            => $request['transactions'],
                        'payment_status'        => $request['transactions'][$key]['status'],
                        'event_payment_status'  => $event_payment_status,
                    ]);

                    if (isset($request['transactions'][$key]['status']) && $request['transactions'][$key]['status'] == 'PAID'){
                        EventBooking::where('payment_id',$paymentDetails->payment_id)->update(['booking_status'=>'Paid']);
                        if($paymentDetails->getEventBookingDetails && count($paymentDetails->getEventBookingDetails)){
                            foreach ($paymentDetails->getEventBookingDetails as $keyEvent => $valueEvent) {
                                $mailInfo = [
                                    'given_name'            => $userinfo->getMemberInfo->given_name,
                                    'application_number'    => $userinfo->getMemberInfo->application_number,
                                    'event_details'         => $valueEvent->getEventSetting,
                                ];
                                $paymentsuccess = ['type'=>'EventPaymentSuccessfull','email' =>$userinfo->email,'mailInfo' => $mailInfo];
                                SendEmailJob::dispatch($paymentsuccess);
                            }
                        }                        
                        $this->apiArray['data']         =   null;
                        $this->apiArray['message']      =   'Payment Successful.';
                        $this->apiArray['errorCode']    =   0;
                        $this->apiArray['error'] = false;
                    }else{
                        if($request['transactions'][$key]['status'] == 'CANCELLED' || $request['transactions'][$key]['status'] == 'EXPIRED' || $request['transactions'][$key]['status'] == 'REJECTED' || $request['transactions'][$key]['status'] == 'REFUNDED'){
                            EventBooking::where('payment_id',$paymentDetails->payment_id)->update(['booking_status'=>'Cancelled']);
                            if($paymentDetails->getEventBookingDetails && count($paymentDetails->getEventBookingDetails)){
                                foreach ($paymentDetails->getEventBookingDetails as $keyEvent => $valueEvent) {
                                    $eventData = EventSetting::find($valueEvent->event_id);
                                    if(!empty($eventData)){
                                        $eventData->increment('quota_balance',$valueEvent->no_of_seats);
                                    }
                                }
                            }
                        }
                        $this->apiArray['data']         =   null;
                        $this->apiArray['message']      =   'Payment failed.';
                        $this->apiArray['errorCode']    =   3;
                        $this->apiArray['error'] = false;
                    }
                }else{
                    if (isset($request['status']) && !empty($request['status'])){
                        EventPayment::where('id',$paymentDetails->id)->update([
                            'payment_status'                => $request['status'],
                            'event_payment_status'          => 'Cancelled',
                        ]);
                        $eventBookingData = EventBooking::where('payment_id',$paymentDetails->payment_id)->where('application_id',$userinfo->getMemberInfo->application_number)->get();
                        if(count($eventBookingData)){
                            foreach ($eventBookingData as $valueBooking) {
                                if(!empty($valueBooking->unit_price)){
                                    $valueBooking->update(['booking_status'=>'Cancelled']);
                                }
                                $eventData = EventSetting::find($valueBooking->event_id);
                                if(!empty($eventData)){
                                    $eventData->increment('quota_balance',$valueBooking->no_of_seats);
                                }
                            }
                        }                        
                    }
                    $this->apiArray['data']         =   null;
                    $this->apiArray['errorCode']    =   0;
                    $this->apiArray['error']        = false;
                    return response()->json($this->apiArray, 200);
                }
            }else{
                $this->apiArray['data']         =   null;
                $this->apiArray['message']      =   'Payment status not found.';
                $this->apiArray['errorCode']    =   3;
                $this->apiArray['error'] = false;
                return response()->json($this->apiArray, 200);
            }
        } catch (\Exception $e) {
            $this->apiArray['message'] = 'Something is wrong, please try after some time'.$e->getMessage();
            $this->apiArray['errorCode'] = 4;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 200);
        }
    }



    protected function getRandomString(){
        $order_no = Str::upper(Str::random(16)); // J1NMDQAFD5LM9DK5
        if(!empty($order_no)){
            $orderPayment = EventPayment::where('order_no',$order_no)->first();
            if(!empty($orderPayment)){
                return $this->getRandomString();
            }else{
                return $order_no;
            }

        }
    }


}

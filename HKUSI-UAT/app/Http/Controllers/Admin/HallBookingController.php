<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\MemberInfo;
use App\Models\ImageBank;
use App\Models\HallBookingGroup;
use App\Models\Quota;
use App\Models\QuotaRoom;
use App\Models\QuotaHall;
use App\Models\HallBookingInfo;
use App\Models\HallBookingAttendance;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmailJob;
use App\Traits\UploadTraits;
use Auth;
 
class HallBookingController extends Controller
{
    use UploadTraits;
    //
    function __construct()
    {
        $this->middleware('permission:hallbooking-list|hallbooking-create|hallbooking-edit|hallbooking-delete', ['only' => ['index','store']]);
        $this->middleware('permission:hallbooking-create', ['only' => ['create','store']]);
        $this->middleware('permission:hallbooking-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:hallbooking-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "Hall Booking";
        return view('admin.hallbooking.index',compact('headerTitle'));
    }    

    public function create(){
        $headerTitle = "Hall Booking Create";
        return view('admin.hallbooking.create',compact('headerTitle'));
    }
    
    public function hallbookingDetails(Request $request , $id ,$type){ 
        $dataId = $id;        
        $dataType = $type;
        $booking_number = '';
        $attendencebolock = '';
        $hallBookingInfo = HallBookingInfo::where('id',$id)->first();
        $allHallQuota = [];
		$allHall = [];
        $user = [];
        if ($dataType=="show") {
            $headerTitle = "Hall Booking Details";
        }elseif($dataType=="edit"){
            $user = User::role('Super Admin')->get();
            $attendencebolock = HallBookingAttendance::where('hall_booking_info_id',$id)->first();
            $allHallQuota = QuotaRoom::where('quota_id',$hallBookingInfo->quota_id)->get();
			$allHall = QuotaHall::where('quota_id',$hallBookingInfo->quota_id)->get();
            $headerTitle = "Hall Booking Details";
        }elseif($dataType=="create"){
            $headerTitle = "Add Hall Booking";
        }elseif($dataType=="payment"){
            $application = HallBookingInfo::where('id',$dataId)->first();
            if(!empty($application)){
                $booking_number = $application->booking_number;
            }
            $headerTitle = "Hall Booking Payment";
        }else{
            return redirect()->route('admin.hallbooking.index');
        }
        
        if (!empty($hallBookingInfo)) {
            return view('admin.hallbooking.comman',compact('headerTitle','hallBookingInfo','dataId','dataType','booking_number','allHallQuota','allHall','attendencebolock','user'));          
        }else{
            return redirect()->route('admin.hallbooking.index');
        }        
    }

    public function multipleBookingDelete(Request $request)
	{
        $input = $request->all();
		if (isset($input['id']) && count($input['id'])) {
            if(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Group') {
				$alreadyGroupedBookingCount  = HallBookingInfo::whereIn('id', $input['id'])->where('booking_type','g')->count();
				if($alreadyGroupedBookingCount > 0){
					 return redirect()->back()->with('groupError','Selected Hall Booking already grouped.');
				}
				if(count($input['id']) > 3) {
                    return redirect()->back()->with('groupError','You are not allowed to max 3 booking grouping.');
                }
				$completedBookingCount  = HallBookingInfo::whereIn('id', $input['id'])->whereIn('status',array('Completed','Cancelled','Rejected'))->count();
				if($completedBookingCount > 0){
					 return redirect()->back()->with('groupError','Completed/Cancelled/Rejected Booking not allowed for grouping.');
				}
				
				if(count($input['id']) > 1) {
                    $lastKey       = count($input['id']) - 1;
                    $firstBooking  = HallBookingInfo::where('id', $input['id'][$lastKey])->first();
                    $lastBooking   = HallBookingInfo::select('id','start_date','hall_setting_id','end_date')->where('id', $input['id'][0])->first();
                    $check_booking = HallBookingInfo::select('id','quota_id')->whereIn('id',$input['id'])->where('user_type_id',$firstBooking->user_type_id)->get();
                    if (count($check_booking) == count($input['id'])) {
                        $check_booking_status = HallBookingInfo::whereIn('id',$input['id'])->whereNotIn('status',['Cancelled','Rejected'])->get();
                        if (count($check_booking_status) == count($input['id'])) {
							$newQuota  = Quota::where('start_date',$firstBooking->start_date)->where('end_date',$lastBooking->end_date)->first();
                            if(isset($newQuota) && !empty($newQuota)){
                                if (isset($newQuota->getQuotaProgrammes) && !empty($newQuota->getQuotaProgrammes)) {
                                    $programme = 0;
                                    $programmeCode = '';
                                    foreach($check_booking_status as $key => $bookingProgramme){
                                        foreach ($newQuota->getQuotaProgrammes as $key => $programmeValue) {
                                            if ($bookingProgramme->programme_code == $programmeValue->getProgrammeDetail->programme_code) {
                                                $programme += 1;
                                                if (!empty($programmeCode)) {
                                                    $programmeCode .= " , ".$programmeValue->getProgrammeDetail->programme_code;
                                                }else{
                                                    $programmeCode .= $programmeValue->getProgrammeDetail->programme_code;
                                                }
                                            }
                                        }
                                    }
									if($programme != count($input['id'])){
                                        return redirect()->back()->with('groupError','Program does not match this quota.');
                                    }
                                } else {
                                    return redirect()->back()->with('groupError','No programme is assigned in this quota.');
                                }

                                if ($newQuota->quota_balance<=0) {
                                    return redirect()->back()->with('groupError','We regretted to inform you that the current HKU residential hall quota is full, and your request cannot be processed. To help you ease the situation, we have arranged hotel discounts for your consideration.');
                                }

                                $type = strtolower($firstBooking->getMemberdata->gender).'_max_quota';
                                $totalGenderQuote = $newQuota->$type;

                                $totalGenderBooking = HallBookingInfo::select('hall_booking_infos.id')->leftJoin('member_infos', function ($join) {
                                    $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                                })->whereNull('member_infos.deleted_at')->where('member_infos.gender',$firstBooking->getMemberdata->gender)
								->whereIn('hall_booking_infos.status',['Pending','Accepted','Paid','Updated'])->where('hall_setting_id',$newQuota->getHallSettingDetail->id)
								->where('quota_id',$newQuota->id)->count();
                                if($totalGenderBooking>=$totalGenderQuote || $totalGenderQuote<=0){
                                    return redirect()->back()->with('groupError','We regretted to inform you that the current HKU residential hall quota is full, and your request cannot be processed. To help you ease the situation, we have arranged hotel discounts for your consideration.');
                                }
								
                                $payAmount = $paymentAmount = $totalAmount = $total = $pendingAmount = 0;
                                if(isset($check_booking_status) && !empty($check_booking_status)){
                                    foreach($check_booking_status as $singleData){
                                        if($singleData->status == 'Accepted' || $singleData->status == 'Pending'){
                                            $pendingAmount += $singleData->amount;
                                        }else{
                                            $payAmount += $singleData->amount;
                                        }
                                        $total += $singleData->amount;
                                    }
                                }
                                $date1 = $newQuota->check_in_date - 86400;
                                $date2 = $newQuota->check_out_date;
                                $days = (int)(($date2 - $date1)/86400);
                                $paymentAmount = ($days - 1) * $newQuota->getHallSettingDetail->unit_price;
                                $lastBooking = HallBookingInfo::select('booking_number')->whereNotNull('booking_number')->orderBy('booking_number','DESC')->first();
                                if (isset($lastBooking->id) && !empty($lastBooking->id)) {
                                    $bookingNumber = $lastBooking->id + 1;
                                }else{
                                    $bookingNumber = 1;
                                }
                                // dd(date('Y-m-d',$newQuota->check_in_date),date('Y-m-d',$newQuota->check_out_date),($days - 1),($paymentAmount - $payAmount),$payAmount,$paymentAmount);
                                // dd($check_booking_status);
                                $totalAmount = $paymentAmount - $payAmount;
                                $data                       = new HallBookingInfo();
                                $data['booking_number']     = $bookingNumber;
                                $data['user_type_id']       = $firstBooking->user_type_id;
                                $data['hall_setting_id']    = $firstBooking->hall_setting_id;
                                $data['quota_id']           = $newQuota->id;
                                $data['quota_hall_id']      = Null;
                                $data['quota_room_id']      = Null;
                                $data['user_type']          = $firstBooking->user_type;
                                $data['programme_code']     = $firstBooking->programme_code;
                                $data['start_date']         = $newQuota->start_date;
                                $data['end_date']           = $newQuota->end_date;
                                $data['check_in_date']      = (isset($newQuota->check_in_date) && !empty($newQuota->check_in_date))?$newQuota->check_in_date:null;
                                $data['check_out_date']     = (isset($newQuota->check_out_date) && !empty($newQuota->check_out_date))?$newQuota->check_out_date:null;
                                $data['check_in_time']      = $newQuota->start_date;                
                                $data['check_out_time']     = $newQuota->end_date;                
                                $data['amount']             = $totalAmount;                
                                $data['booking_type']       = 'g';                
                                $data['status']             = (isset($newQuota->status) && $newQuota->status == '1')?'Accepted':'Pending';
                                $data['application_id']     = $firstBooking->application_id;
                                $data->save();
                                if(!empty($data->id)){
                                    $new_booking_id = $data->id;
                                }else{
                                    $new_booking_id = 1;
                                }
                                $bookingNumber = str_pad($new_booking_id, 7, "0", STR_PAD_LEFT);
                                // $bookingNumber = "HKUSI".str_pad($new_booking_id, 7, "0", STR_PAD_LEFT);
                                $data->update(['booking_number'=>$bookingNumber]);

                                foreach ($check_booking_status as $key => $value) {
                                    $HallBookingData                            = new HallBookingGroup();
                                    $HallBookingData['hall_booking_info_id']    = $data->id;
                                    $HallBookingData['booking_number']          = $value->booking_number;
                                    $HallBookingData['user_type_id']            = $value->user_type_id;
                                    $HallBookingData['hall_setting_id']         = $value->hall_setting_id;
                                    $HallBookingData['quota_id']                = $value->quota_id;
                                    $HallBookingData['quota_hall_id']           = $value->quota_hall_id;
                                    $HallBookingData['quota_room_id']           = $value->quota_room_id;
                                    $HallBookingData['user_type']               = $value->user_type;
                                    $HallBookingData['programme_code']          = $value->programme_code;
                                    $HallBookingData['start_date']              = $value->start_date;
                                    $HallBookingData['end_date']                = $value->end_date;
                                    $HallBookingData['check_in_date']           = $value->check_in_date;
                                    $HallBookingData['check_out_date']          = $value->check_out_date;
                                    $HallBookingData['check_in_time']           = $value->check_in_time;                
                                    $HallBookingData['check_out_time']          = $value->check_out_time;                
                                    $HallBookingData['amount']                  = $value->amount;                
                                    $HallBookingData['status']                  = $value->status;                
                                    $HallBookingData['application_id']          = $value->application_id;
                                    $HallBookingData->save();
                                    $value->getQuotaDetail->updateBookingQuota('plus'); 
                                }
                                HallBookingInfo::whereIn('id',$input['id'])->delete();

                                $mailInfo = [
                                    'given_name'         => $data->getMemberdata->given_name,
                                    'application_number' => $data->getMemberdata->application_number,                        
                                    'check_in_date'      => $newQuota->check_in_date,                        
                                    'check_out_date'     => $newQuota->check_out_date,                        
                                    'hall_fees'          => $totalAmount,                      
                                ];
                                $details = ['type'=>'SpecialConfermation','email' =>$data->getMemberdata->email_address,'mailInfo' => $mailInfo];
                                SendEmailJob::dispatch($details);
                                return redirect()->back()->with('success','Records have been grouped successfully');
                            }else{
                                return redirect()->back()->with('groupError','Quota period not matched.');
                            }
                        }else{
                            return redirect()->back()->with('groupError','It should not be canceled , rejected status for grouping.');
                        }
                    }else{
                        return redirect()->back()->with('groupError','Grouping should be booked by only one member.');
                    }
                }else{
                    return redirect()->back()->with('groupError','Must be two booking for grouping.');
                }
            }else{
                foreach ($input['id'] as $hallbooking) {
                    if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete') {
                        HallBookingInfo::where('id', $hallbooking)->delete();
                    }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Rejected'){
                        $hallbookingRecord = HallBookingInfo::where('id',$hallbooking)->first();
                        $memberinfo = MemberInfo::where('id',$hallbookingRecord->user_type_id)->first();
                        $hallbookingRecord->getQuotaDetail->updateBookingQuota('pluse');
                        $mailInfo = [
                            'given_name'     => $memberinfo->given_name,
                            'application_number' => $memberinfo->application_number,
                        ];
                        $rejected = ['type'=>'FullyBooked','email' =>$memberinfo->email_address,'mailInfo' => $mailInfo];
                        SendEmailJob::dispatch($rejected);
                        $hallbookingRecord->update(['status'=>'Rejected']);
                    }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Pending'){
                        HallBookingInfo::where('id',$hallbooking)->update(['status'=>'Pending']);
                    }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Accepted'){
                        $hallbookingRecord = HallBookingInfo::where('id',$hallbooking)->first();
                        $memberinfo = MemberInfo::where('id',$hallbookingRecord->user_type_id)->first();
                        $mailInfo = [
                            'given_name'     => $memberinfo->given_name,
                            'application_number' => $memberinfo->application_number,
                            'hall_payment_days' => $hallbookingRecord->getHallsetting->hall_payment_days,
                        ];
                        $details = ['type'=>'HallReservationConfirm','email' =>$memberinfo->email_address,'mailInfo' => $mailInfo];
                        SendEmailJob::dispatch($details);
                        $hallbookingRecord->update(['status'=>'Accepted','payment_deadline_date'=>time()]);
                    }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Paid'){
                        $hallbookingRecord = HallBookingInfo::where('id',$hallbooking)->first();
                        $memberinfo = MemberInfo::where('id',$hallbookingRecord->user_type_id)->first();

                        $hall_payment_days = (isset($hallbookingRecord->getHallsetting->hall_payment_days) && !empty($hallbookingRecord->getHallsetting->hall_payment_days))?$hallbookingRecord->getHallsetting->hall_payment_days:'0';
                        $hall_confirmation_date = $hallbookingRecord->payment_deadline_date + ($hall_payment_days * 86400);

                        $mailInfo = [
                            'given_name'                => $memberinfo->given_name,
                            'application_number'        => $memberinfo->application_number,
                            'Hall_confirmation_Date'    => date('Y-m-d',$hall_confirmation_date),
                        ];
                        $paymentsuccess = ['type'=>'PaymentSuccessfull','email' =>$memberinfo->email_address,'mailInfo' => $mailInfo];
                        SendEmailJob::dispatch($paymentsuccess);
                        $hallbookingRecord->update(['status'=>'Paid']);
                    }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Cancelled'){
                        $hallbookingRecord = HallBookingInfo::where('id',$hallbooking)->first();
                        $hallbookingRecord->update(['status'=>'Cancelled']);
                        $hallbookingRecord->getQuotaDetail->updateBookingQuota('pluse');
                    }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Updated'){
                        HallBookingInfo::where('id',$hallbooking)->update(['status'=>'Updated']);
                    }else{
                        HallBookingInfo::where('id',$hallbooking)->update(['status'=>'Completed']);
                    }
                }
            }
        }
		return redirect()->back();
	}

    public function update(Request $request, $id){ 
        $input = $request->all();
        $data                       = [];
		if (isset($input['status']) && !empty($input['status'])) {
            $statusdata = HallBookingInfo::where('id',$id)->first(); 
            $oldStatus = $statusdata->status;
            $oldRoom = $statusdata->quota_room_id;
			$oldQuotaHall = $statusdata->quota_hall_id;
            $memberinfo = MemberInfo::where('id',$statusdata->user_type_id)->first();
			$quotaHallData = QuotaHall::where('id',$statusdata->quota_hall_id)->first();
			//$quotaHallData->update(['address'=>$input['address']]);
            if ($input['status']=="Accepted" && $oldStatus !='Accepted'){
				$mailInfo = [
                    'given_name'     => $memberinfo->given_name,
                    'application_number' => $memberinfo->application_number,
                    'hall_payment_days' => $statusdata->getHallsetting->hall_payment_days,
                ];
                $details = ['type'=>'HallReservationConfirm','email' =>$memberinfo->email_address,'mailInfo' => $mailInfo];
                SendEmailJob::dispatch($details);
				$statusdata->update(['payment_deadline_date'=>time()]);
            }elseif($input['status']=="Paid" && $oldStatus !='Paid'){
                $hall_payment_days = (isset($statusdata->getHallsetting->hall_payment_days) && !empty($statusdata->getHallsetting->hall_payment_days))?$statusdata->getHallsetting->hall_payment_days:'0';
                $hall_confirmation_date = $statusdata->payment_deadline_date + ($hall_payment_days * 86400);
                $mailInfo = [
                    'given_name'                => $memberinfo->given_name,
                    'application_number'        => $memberinfo->application_number,
                    'Hall_confirmation_Date'    => date('Y-m-d',$hall_confirmation_date),
                ];
                $paymentsuccess = ['type'=>'PaymentSuccessfull','email' =>$memberinfo->email_address,'mailInfo' => $mailInfo];
                SendEmailJob::dispatch($paymentsuccess);
            }elseif($input['status']=="Cancelled" && $oldStatus !='Cancelled'){
                $statusdata->getQuotaDetail->updateBookingQuota('pluse');
            }elseif($input['status']=="Rejected" && $oldStatus !='Rejected'){
                $statusdata->getQuotaDetail->updateBookingQuota('pluse');
                $mailInfo = [
                    'given_name'     => $memberinfo->given_name,
                    'application_number' => $memberinfo->application_number,
                ];
                $rejected = ['type'=>'FullyBooked','email' =>$memberinfo->email_address,'mailInfo' => $mailInfo];
                SendEmailJob::dispatch($rejected);
            }elseif($input['status']=="Pending" && $oldStatus !='Pending'){
                $statusdata->update(['hall_result_date'=>time()]);
            }elseif(isset($input['quota_room_id']) && !empty($input['quota_room_id']) && $oldRoom !=$input['quota_room_id']){
					$totalGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                            })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->orderBy('hall_booking_infos.id','ASC')->where('quota_hall_id',$statusdata->quota_hall_id)->first();
					$totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                            })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->orderBy('hall_booking_infos.id','ASC')->where('quota_hall_id',$statusdata->quota_hall_id)->first();
                    $quotaRoom = QuotaRoom::where('id',$input['quota_room_id'])->first();
                    $hallSetting = QuotaHall::select('id','male','female','quota_id')->where('id',$statusdata->quota_hall_id)->first();

                    if (!empty($quotaRoom) && $quotaRoom->gender=="Male") {
                        $data['quota_room_id'] = $input['quota_room_id'];
                        $mailInfo = [
                            'given_name'            => $totalGenderMaleBooking->getMemberdata->given_name,
                            'application_number'    => $totalGenderMaleBooking->getMemberdata->application_number,                        
                            'accommodation'         => $totalGenderMaleBooking->getHallSettingDetail,                        
                            'quotahall'             => $hallSetting,             
                            'booking'               => $totalGenderMaleBooking,                      
                            'memberinfo'            => $totalGenderMaleBooking->getMemberdata,                        
                        ];
                        $details = ['type'=>'HallInfoUpdate','email' =>$totalGenderMaleBooking->getMemberdata->email_address,'mailInfo' => $mailInfo];
                        SendEmailJob::dispatch($details);
                    }else{
                        $data['quota_room_id'] = $input['quota_room_id'];
                        $mailInfo = [
                            'given_name'            => $totalGenderFemaleBooking->getMemberdata->given_name,
                            'application_number'    => $totalGenderFemaleBooking->getMemberdata->application_number,                        
                            'accommodation'         => $totalGenderFemaleBooking->getHallSettingDetail,                        
                            'quotahall'             => $hallSetting,             
                            'booking'               => $totalGenderFemaleBooking,                      
                            'memberinfo'            => $totalGenderFemaleBooking->getMemberdata,                        
                        ];
                        $details = ['type'=>'HallInfoUpdate','email' =>$totalGenderFemaleBooking->getMemberdata->email_address,'mailInfo' => $mailInfo];
                        SendEmailJob::dispatch($details);
                    }
			} else if(isset($input['quota_hall_id']) && !empty($input['quota_hall_id']) && $oldQuotaHall !=$input['quota_hall_id']){
				$totalGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
						})->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->orderBy('hall_booking_infos.id','ASC')->where('quota_hall_id',$statusdata->quota_hall_id)->first();
				$totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
						})->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->orderBy('hall_booking_infos.id','ASC')->where('quota_hall_id',$statusdata->quota_hall_id)->first();
				$quotaRoom = QuotaRoom::where('id',$input['quota_room_id'])->first();
				$hallSetting = QuotaHall::select('id','male','female','quota_id')->where('id',$input['quota_hall_id'])->first();

				$data['quota_hall_id'] = $input['quota_hall_id'];
				if (!empty($quotaRoom) && $quotaRoom->gender=="Male") {
					$mailInfo = [
						'given_name'            => $totalGenderMaleBooking->getMemberdata->given_name,
						'application_number'    => $totalGenderMaleBooking->getMemberdata->application_number,                        
						'accommodation'         => $totalGenderMaleBooking->getHallSettingDetail,                        
						'quotahall'             => $hallSetting,             
						'booking'               => $totalGenderMaleBooking,                      
						'memberinfo'            => $totalGenderMaleBooking->getMemberdata,                        
					];
					$details = ['type'=>'InformationReleased','email' =>$totalGenderMaleBooking->getMemberdata->email_address,'mailInfo' => $mailInfo];
					SendEmailJob::dispatch($details);
				}else{
					$mailInfo = [
						'given_name'            => $totalGenderFemaleBooking->getMemberdata->given_name,
						'application_number'    => $totalGenderFemaleBooking->getMemberdata->application_number,                        
						'accommodation'         => $totalGenderFemaleBooking->getHallSettingDetail,                        
						'quotahall'             => $hallSetting,             
						'booking'               => $totalGenderFemaleBooking,                      
						'memberinfo'            => $totalGenderFemaleBooking->getMemberdata,                        
					];
					$details = ['type'=>'InformationReleased','email' =>$totalGenderFemaleBooking->getMemberdata->email_address,'mailInfo' => $mailInfo];
					SendEmailJob::dispatch($details);
				}
			}
			$hallAttendence = HallBookingAttendance::where('hall_booking_info_id',$id)->first();
            $attendenceData = [];
            if(isset($hallAttendence) && !empty($hallAttendence)){
                $attendenceData['actual_check_in_date']   = strtotime($input['actual_check_in_date']);
                $attendenceData['actual_check_in_time']   = strtotime($input['actual_check_in_time']);
                $attendenceData['check_in_operator']      = $input['check_in_operator'];
                $attendenceData['actual_check_out_date']  = strtotime($input['actual_check_out_date']);
                $attendenceData['actual_check_out_time']  = strtotime($input['actual_check_out_time']);
                $attendenceData['check_out_operator']     = $input['check_out_operator'];
                $attendenceData['status']                 = $input['attendence_status'];
                HallBookingAttendance::where('hall_booking_info_id',$id)->update($attendenceData);
            }else{
                $bookingAttendence = new HallBookingAttendance();
                $bookingAttendence['hall_booking_info_id']   = $id;
                $bookingAttendence['actual_check_in_date']   = strtotime($input['actual_check_in_date']);
                $bookingAttendence['actual_check_in_time']   = strtotime($input['actual_check_in_time']);
                $bookingAttendence['check_in_operator']      = $input['check_in_operator'];
                $bookingAttendence['actual_check_out_date']  = strtotime($input['actual_check_out_date']);
                $bookingAttendence['actual_check_out_time']  = strtotime($input['actual_check_out_time']);
                $bookingAttendence['check_out_operator']     = $input['check_out_operator'];
                $bookingAttendence['status']                 = $input['attendence_status'];
                $bookingAttendence->save();
            }
            $data['status'] = $input['status'];
        }
		
        HallBookingInfo::where('id',$id)->update($data);
        return redirect()->route('admin.hallbooking.index')->with('success' ,'Hall booking update successfully.');
    }
    
    public function destroy($id)
    {
        $hallbooking =  HallBookingInfo::find($id);
        $hallbooking->getQuotaDetail->updateBookingQuota('pluse');
        HallBookingInfo::find($id)->delete();
        return redirect()->route('admin.hallbooking.index')->with('success', 'Hall Booking Info deleted successfully');
    }

    public function hallbookingstatuschange(Request $request, $id) {  
        
        $statusdata = HallBookingInfo::find($id); 
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $memberinfo = MemberInfo::where('id',$statusdata->user_type_id)->first();
       // dd($memberinfo);
        if(!empty($memberinfo)){
            $data =[];
            $data['status'] = $request->status;
            if ($data['status']=="Accepted") { 
                $mailInfo = [
                    'given_name'     => $memberinfo->given_name,
                    'application_number' => $memberinfo->application_number,
                    'hall_payment_days' => $statusdata->getHallsetting->hall_payment_days,
                ];
                $details = ['type'=>'HallReservationConfirm','email' =>$memberinfo->email_address,'mailInfo' => $mailInfo];
                SendEmailJob::dispatch($details);
                $statusdata->update(['payment_deadline_date'=>time()]);
            }elseif ($data['status']=="Paid") {
                $hall_payment_days = (isset($statusdata->getHallsetting->hall_payment_days) && !empty($statusdata->getHallsetting->hall_payment_days))?$statusdata->getHallsetting->hall_payment_days:'0';
                $hall_confirmation_date = $statusdata->payment_deadline_date + ($hall_payment_days * 86400);
                $mailInfo = [
                    'given_name'                => $memberinfo->given_name,
                    'application_number'        => $memberinfo->application_number,
                    'Hall_confirmation_Date'    => date('Y-m-d',$hall_confirmation_date),
                ];
                $paymentsuccess = ['type'=>'PaymentSuccessfull','email' =>$memberinfo->email_address,'mailInfo' => $mailInfo];
                SendEmailJob::dispatch($paymentsuccess);
            }elseif($data['status']=="Cancelled"){
                $statusdata->getQuotaDetail->updateBookingQuota('pluse');
            }elseif($data['status']=="Rejected"){
                $statusdata->getQuotaDetail->updateBookingQuota('pluse');
                $mailInfo = [
                    'given_name'     => $memberinfo->given_name,
                    'application_number' => $memberinfo->application_number,
                ];
                $rejected = ['type'=>'FullyBooked','email' =>$memberinfo->email_address,'mailInfo' => $mailInfo];
                SendEmailJob::dispatch($rejected);
            }elseif($data['status']=="Pending"){
                $statusdata->update(['hall_result_date'=>time()]);
            }
        }
        HallBookingInfo::where('id', $statusdata->id)->update($data);
        return redirect()->route('admin.hallbooking',[$id,'edit'])->with('success', 'Hall Booking status updated successfully!');  
    }

}

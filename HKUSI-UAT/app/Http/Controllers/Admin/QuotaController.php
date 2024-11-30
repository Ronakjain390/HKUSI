<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Quota;
use App\Models\Programme;
use App\Models\Country;
use App\Models\HallSetting;
use App\Models\QuotaProgramme;
use App\Models\MemberInfo;
use App\Models\HallBookingInfo;
use App\Models\ProgrammeHallSetting;
use App\Models\QuotaUpdate;
use App\Models\QuotaCountry;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmailJob;
use Carbon\Carbon;
use Auth;

class QuotaController extends Controller
{
    //
    function __construct()
    {
        $this->middleware('permission:quota-list|quota-create|quota-edit|quota-delete', ['only' => ['index','store']]);
        $this->middleware('permission:quota-create', ['only' => ['create','store']]);
        $this->middleware('permission:quota-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:quota-delete', ['only' => ['destroy']]);
    }

    public function quotaDetail(Request $request , $id ,$type){
        $quotaInfo = $countries = $programme = $quotaCountries = $quotaProgramme = [];
        $totalGenderMaleBooking = $totalGenderFemaleBooking = 0;
        $dataId = $id;
        $dataType = $type;
        $quotaInfo = Quota::find($id);
        $countries = Country::select('id','name')->orderBy('name','ASC')->get();
        if ($type == 'create') {
            $hallInfo = HallSetting::find($id);
            $yearProgramme = ProgrammeHallSetting::where('hall_setting_id',$id)->pluck('programme_id')->toArray();
            if (isset($yearProgramme) && count($yearProgramme)) {
                $programme = Programme::select('id','programme_code','programme_name')->whereIn('id',$yearProgramme)->orderBy('programme_name','ASC')->get();
            }
            $headerTitle = "New Quota";
            return view('admin.quota.create',compact('headerTitle','hallInfo','dataId','dataType','programme','countries','yearProgramme'));
        }
        if ($type == 'hall') {
            $headerTitle = "Quota Setting ";
        }elseif($type=="programme"){
            $headerTitle = "Quota Programme";
        }elseif ($type == 'room') {
            $headerTitle = "Quota Setting ";
        }elseif ($type == 'show' || $type == 'edit' ) {
            $headerTitle = "Quota Setting ";
            $yearProgramme = ProgrammeHallSetting::where('hall_setting_id',$quotaInfo->hall_setting_id)->pluck('programme_id')->toArray();
            if (isset($yearProgramme) && count($yearProgramme)) {
                $programme = Programme::select('id','programme_code','programme_name')->whereIn('id',$yearProgramme)->orderBy('programme_name','ASC')->get();
            }
            $quotaCountries = QuotaCountry::where('quota_id',$id)->pluck('country_id')->toArray();
            $quotaProgramme = QuotaProgramme::where('quota_id',$id)->pluck('programme_id')->toArray();
            if (isset($quotaInfo) && !empty($quotaInfo)) {
                $totalGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                        })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->whereNotIn('hall_booking_infos.status',['Cancelled','Rejected','Pending'])->where('quota_id',$quotaInfo->id)->orderBy('hall_booking_infos.id','ASC')->count();

                $totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                                })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->whereNotIn('hall_booking_infos.status',['Cancelled','Rejected','Pending'])->where('quota_id',$quotaInfo->id)->orderBy('hall_booking_infos.id','ASC')->count();
            }else{
              return redirect()->route('admin.accommondation-setting.index');  
          }
        }else{
            return redirect()->route('admin.accommondation-setting.index');
        }
        return view('admin.quota.comman',compact('headerTitle','quotaInfo','dataId','dataType','programme','countries','quotaCountries','quotaProgramme','totalGenderMaleBooking','totalGenderFemaleBooking'));
    }


    public function store(Request $request ){
        $input                              =  $request->all();
        $checkInDate = $checkOutDate = null;
        if (isset($input['start_date']) && !empty($input['start_date'])) {
            $checkInDate = strtotime($input['start_date']) - 86400;
        }
        if(isset($input['end_date']) && !empty($input['end_date'])){
            $checkOutDate = strtotime($input['end_date']) + 86400;
        }
        $quota                              =  new Quota();
        $quota['hall_setting_id']           =  $input['hall_setting_id'];
        $quota['start_date']                =  (isset($input['start_date']) && !empty($input['start_date']))?strtotime($input['start_date']):null;
        $quota['end_date']                  =  (isset($input['end_date']) && !empty($input['end_date']))?strtotime($input['end_date']):null;
        $quota['check_in_date']             =  $checkInDate;
        $quota['check_out_date']            =  $checkOutDate;
        $quota['total_quotas']              =  (isset($input['total_quotas']) && !empty($input['total_quotas']))?$input['total_quotas']:0;
        $quota['quota_balance']             =  (isset($input['quota_balance']) && !empty($input['quota_balance']))?$input['quota_balance']:0;
        $quota['male']                      =  (isset($input['male']) && !empty($input['male']))?$input['male']:0;
        $quota['female']                    =  (isset($input['female']) && !empty($input['female']))?$input['female']:0;
        $quota['female_max_quota']          =  (isset($input['female_max_quota']) && !empty($input['female_max_quota']))?$input['female_max_quota']:0;
        $quota['male_max_quota']            =  (isset($input['male_max_quota']) && !empty($input['male_max_quota']))?$input['male_max_quota']:0;
        $quota['hall_confirmation_date']    =  (isset($input['hall_confirmation_date']) && !empty($input['hall_confirmation_date']))?strtotime($input['hall_confirmation_date']):null;
        $quota['status']                    =  $input['status'];
        $quota->save();
        if(isset($quota->id)){
            if(isset($input['countries']) && count($input['countries'])){
                foreach ($input['countries'] as $key => $countryValue) {
                    QuotaCountry::insert(['quota_id'=>$quota->id,'country_id'=>$countryValue]);
                }
            }
            if (isset($input['programmes']) && count($input['programmes'])) {
                foreach ($input['programmes'] as $key => $programmeValue) {
					$quatoProgrammeCount = QuotaProgramme::where('programme_id',$programmeValue)->count();
					if($quatoProgrammeCount > 1 ){
						return redirect()->back()->with('programmeQuatoError','You are not allow to assign the same programme into more than 2 quotas.');
					} else {
						QuotaProgramme::insert(['quota_id'=>$quota->id,'programme_id'=>$programmeValue]);
					}
                }
            }
        }
        if ($input['status']=="1") {
            $status = Quota::find($quota->id); 
            Quota::where('id',$quota->id)->update(['release_date'=>time()]);   
            $totalGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                        })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->where('hall_booking_infos.status','Pending')->where('quota_id',$status->id)->orderBy('hall_booking_infos.id','ASC')->limit($status->male)->get();
            $totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                        })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->where('hall_booking_infos.status','Pending')->where('quota_id',$status->id)->orderBy('hall_booking_infos.id','ASC')->limit($status->female)->get();
            if (isset($totalGenderMaleBooking) && count($totalGenderMaleBooking)) {
                foreach ($totalGenderMaleBooking as $key => $valueData) {
                    $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                    $accetptstauts['status'] = "Accepted";
                    $accetptstauts['payment_deadline_date'] = time();
                    $mailInfo = [
                        'given_name'         => $datauser->given_name,
                        'application_number' => $datauser->application_number,                        
                        'hall_payment_days'  => (isset($status->getHallSettingDetail->hall_payment_days) && !empty($status->getHallSettingDetail->hall_payment_days))?$status->getHallSettingDetail->hall_payment_days:null,                        
                    ];
                    $details = ['type'=>'HallReservationConfirm','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                    SendEmailJob::dispatch($details);
                    HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                }
            }    
            if (isset($totalGenderFemaleBooking) && count($totalGenderFemaleBooking)) {
                foreach ($totalGenderFemaleBooking as $key => $valueData) {
                    $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                    $accetptstauts = [];
                    $accetptstauts['status'] = "Accepted";
                    $accetptstauts['payment_deadline_date'] = time();
                    $mailInfo = [
                        'given_name'         => $datauser->given_name,
                        'application_number' => $datauser->application_number,                        
                        'hall_payment_days'  => (isset($status->getHallSettingDetail->hall_payment_days) && !empty($status->getHallSettingDetail->hall_payment_days))?$status->getHallSettingDetail->hall_payment_days:null,                      
                    ];
                    $details = ['type'=>'HallReservationConfirm','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                    SendEmailJob::dispatch($details);
                    HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                }
            }     
        }
        return redirect()->route('admin.hallDetails',[$input['hall_setting_id'],'quotas']);
    }


    public function update(Request $request, $id){
        //dd($request->all());
        $input                          = $request->all();
        $quota = Quota::where('id',$id)->first();
        if ($quota->status == '1') {
            $changeQuotaLimit = Quota::where('id',$id)->where('male',$input['male'])->where('female',$input['female'])->first();
            if (empty($changeQuotaLimit)) {
                $quotaUpdate = new QuotaUpdate();
                $quotaUpdate['hall_setting_id']  = $quota->hall_setting_id;
                $quotaUpdate['quota_id']         = $id;
                $quotaUpdate['male_old_qty']     = $quota->male;
                $quotaUpdate['male_new_qty']     = (isset($input['male']) && !empty($input['male']))?$input['male']:0;
                $quotaUpdate['female_old_qty']   = $quota->female;
                $quotaUpdate['female_new_qty']   = (isset($input['female']) && !empty($input['female']))?$input['female']:0;
                $quotaUpdate->save();
            }

            $totalMaleBookingReleased = $totalFemaleBookingReleased = $maleQuotaActual = $femaleQuotaActual =  0;

            if(isset($input['male']) && $input['male']!=''){
                $maleQuotaActual = $input['male'];
            }
            if(isset($input['female']) && $input['female']!=''){
                $femaleQuotaActual = $input['female'];
            }
            $totalMaleBookingReleased = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.quota_id')->leftJoin('member_infos', function ($join) {
                        $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                    })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->whereIn('hall_booking_infos.status',['Accepted','Paid','Updated'])->where('hall_booking_infos.quota_id',$quota->id)->count();
            $totalFemaleBookingReleased = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.quota_id')->leftJoin('member_infos', function ($join) {
                        $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                    })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->whereIn('hall_booking_infos.status',['Accepted','Paid','Updated'])->where('hall_booking_infos.quota_id',$quota->id)->count();

            $maleLimit = $maleQuotaActual - $totalMaleBookingReleased;
            $femaleLimit = $femaleQuotaActual - $totalFemaleBookingReleased;
            
            if ($maleLimit > 0) {
                $totalGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                        })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->whereNotIn('hall_booking_infos.status',['Cancelled','Rejected'])->where('hall_booking_infos.status','Pending')->where('quota_id',$quota->id)->orderBy('hall_booking_infos.id','ASC')->limit($maleLimit)->get();
                if (isset($totalGenderMaleBooking) && count($totalGenderMaleBooking)) {
                    foreach ($totalGenderMaleBooking as $key => $valueData) {
                        $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                        $accetptstauts['status'] = "Accepted";
                        $accetptstauts['payment_deadline_date'] = time();
                        $mailInfo = [
                            'given_name'         => $datauser->given_name,
                            'application_number' => $datauser->application_number,                        
                            'hall_payment_days'  => (isset($quota->getHallSettingDetail->hall_payment_days) && !empty($quota->getHallSettingDetail->hall_payment_days))?$quota->getHallSettingDetail->hall_payment_days:null,                        
                        ];
                        $details = ['type'=>'HallReservationConfirm','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                        SendEmailJob::dispatch($details);
                        HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                    }
                } 
            }
            if ($femaleLimit > 0) {
                $totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                            })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->whereNotIn('hall_booking_infos.status',['Cancelled','Rejected'])->where('member_infos.gender','Female')->where('hall_booking_infos.status','Pending')->where('quota_id',$quota->id)->orderBy('hall_booking_infos.id','ASC')->limit($femaleLimit)->get();
                if (isset($totalGenderFemaleBooking) && count($totalGenderFemaleBooking)) {
                    foreach ($totalGenderFemaleBooking as $key => $valueData) {
                        $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                        $accetptstauts = [];
                        $accetptstauts['status'] = "Accepted";
                        $accetptstauts['payment_deadline_date'] = time();
                        $mailInfo = [
                            'given_name'         => $datauser->given_name,
                            'application_number' => $datauser->application_number,                        
                            'hall_payment_days'  => (isset($quota->getHallSettingDetail->hall_payment_days) && !empty($quota->getHallSettingDetail->hall_payment_days))?$quota->getHallSettingDetail->hall_payment_days:null,                      
                        ];
                        $details = ['type'=>'HallReservationConfirm','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                        SendEmailJob::dispatch($details);
                        HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                    }
                } 
            }
        }
        $checkInDate = $checkOutDate = null;
        if (isset($input['start_date']) && !empty($input['start_date'])) {
            $checkInDate = strtotime($input['start_date']) - 86400;
        }
        if(isset($input['end_date']) && !empty($input['end_date'])){
            $checkOutDate = strtotime($input['end_date']) + 86400;
        }
        $data                           = [];
        $data['hall_setting_id']        = $input['hall_setting_id'];
        $data['start_date']             = (isset($input['start_date']) && !empty($input['start_date']))?strtotime($input['start_date']):null;
        $data['end_date']               = (isset($input['end_date']) && !empty($input['end_date']))?strtotime($input['end_date']):null;
        $data['check_in_date']          =  $checkInDate;
        $data['check_out_date']         =  $checkOutDate;
        $data['total_quotas']           = (isset($input['total_quotas']) && !empty($input['total_quotas']))?$input['total_quotas']:0;
        $data['quota_balance']          = (isset($input['quota_balance']) && !empty($input['quota_balance']))?$input['quota_balance']:0;
        $data['male']                   = (isset($input['male']) && !empty($input['male']))?$input['male']:0;
        $data['female']                 = (isset($input['female']) && !empty($input['female']))?$input['female']:0;
        $data['female_max_quota']       = (isset($input['female_max_quota']) && !empty($input['female_max_quota']))?$input['female_max_quota']:0;
        $data['male_max_quota']         = (isset($input['male_max_quota']) && $input['male_max_quota']!='')?$input['male_max_quota']:0;
        $data['hall_confirmation_date'] = (isset($input['hall_confirmation_date']) && !empty($input['hall_confirmation_date']))?strtotime($input['hall_confirmation_date']):null;
        if (isset($input['status']) && !empty($input['status'])) {
            $data['status']                 =  $input['status'];
        }
        Quota::where('id',$id)->update($data);     

        if (isset($input['countries']) && !empty($input['countries'])) {
            QuotaCountry::where('quota_id',$id)->delete();
            foreach ($input['countries'] as $key => $countryValue) {
                QuotaCountry::insert(['quota_id'=>$id,'country_id'=>$countryValue]);
            }
        }  

        if (isset($input['programmes']) && !empty($input['programmes'])) {
            QuotaProgramme::where('quota_id',$id)->delete();
            foreach ($input['programmes'] as $key => $programmeValue) {
				$quatoProgrammeCount = QuotaProgramme::where('programme_id',$programmeValue)->count();
				if($quatoProgrammeCount > 1 ){
					return redirect()->back()->with('programmeQuatoError','You are not allow to assign the same programme into more than 2 quotas.');
				} else {
					QuotaProgramme::insert(['quota_id'=>$quota->id,'programme_id'=>$programmeValue]);
				}
            }
        }  
		
        if (isset($input['status']) && $input['status']=="1") {
            Quota::where('id',$id)->update(['release_date'=>time()]);  
            $totalGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                            })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->where('hall_booking_infos.status','Pending')->where('quota_id',$quota->id)->orderBy('hall_booking_infos.id','ASC')->limit($quota->male)->get();
                //dd($totalGenderMaleBooking->count(),$totalGenderFemaleBooking->count());
            if (isset($totalGenderMaleBooking) && count($totalGenderMaleBooking)) {
                foreach ($totalGenderMaleBooking as $key => $valueData) {
                    $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                    $accetptstauts['status'] = "Accepted";
                    $accetptstauts['payment_deadline_date'] = time();
                    $accetptstauts['hall_result_date'] = time();
                    $mailInfo = [
                        'given_name'         => $datauser->given_name,
                        'application_number' => $datauser->application_number,                        
                        'hall_payment_days'  => (isset($quota->getHallSettingDetail->hall_payment_days) && !empty($quota->getHallSettingDetail->hall_payment_days))?$quota->getHallSettingDetail->hall_payment_days:null,                        
                    ];
                    $details = ['type'=>'HallReservationConfirm','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                    SendEmailJob::dispatch($details);
                    HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                }
            }    
            $totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                        })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->where('hall_booking_infos.status','Pending')->where('quota_id',$quota->id)->orderBy('hall_booking_infos.id','ASC')->limit($quota->female)->get();
            if (isset($totalGenderFemaleBooking) && count($totalGenderFemaleBooking)) {
                foreach ($totalGenderFemaleBooking as $key => $valueData) {
                    $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                    $accetptstauts = [];
                    $accetptstauts['status'] = "Accepted";
                    $accetptstauts['payment_deadline_date'] = time();
                    $accetptstauts['hall_result_date'] = time();
                    $mailInfo = [
                        'given_name'         => $datauser->given_name,
                        'application_number' => $datauser->application_number,                        
                        'hall_payment_days'  => (isset($quota->getHallSettingDetail->hall_payment_days) && !empty($quota->getHallSettingDetail->hall_payment_days))?$quota->getHallSettingDetail->hall_payment_days:null,                      
                    ];
                    $details = ['type'=>'HallReservationConfirm','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                    SendEmailJob::dispatch($details);
                    HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                }
            }       
        }    
        return redirect()->route('admin.quota.quotaDetail',[$id,'show'])->with('success','Quota created successfully');
    }

    public function userstatusChange(Request $request, $id, $status) {      
        $member = Quota::select('id','user_id')->where('id',$id)->first();
        $user = User::where('id',$member->user_id)->first();
        if (isset($user) && !empty($user)) {
            $user->update(['status' => $request->status]);
        }
        return redirect()->route('admin.quota.comman',[$id,'show'])->with('success', 'HallInfo status updated successfully!');        
    }

    public function settingtatusChange(Request $request, $id, $status) {  
        $member = Quota::select('id','user_id')->where('id',$id)->first();      
        if (isset($member) && !empty($member)) {
            $member->update(['status' => $request->status]);
        }
        return redirect()->route('admin.quota.comman',[$id,'show'])->with('success', 'HallInfo status updated successfully!');        
    }

    public function destroy($id)
    {
        Quota::find($id)->delete();
        return redirect()->route('admin.quota.comman')->with('success', 'Quota deleted successfully');
    }

    public function multipleQuotaDelete(Request $request)
	{
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $quota) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete') {
                    Quota::where('id', $quota)->delete();
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'released'){
                    $quotaInof = Quota::select('id','status')->where('id', $quota)->first();
                    Quota::where('id',$quotaInof->id)->update(['status'=>'1']);
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'pending'){
                    $quotaInof = Quota::select('id','status')->where('id', $quota)->first();
                    Quota::where('id',$quotaInof->id)->update(['status'=>'0']);
                }else{
                    Quota::where('id', $quota)->update(['status'=>'1']);
                }
            }
        }
		return redirect()->back();
	}
   
}

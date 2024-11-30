<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Quota;
use App\Models\Programme;
use App\Models\Country;
use App\Models\QuotaHall;
use App\Models\QuotaProgramme;
use App\Models\QuotaRoom;
use App\Models\HallSetting;
use App\Models\HallBookingInfo;
use App\Models\MemberInfo;
use App\Models\ProgrammeHallSetting;
use App\Models\QuotaHallUpdate;
use App\Models\HallProgramme;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmailJob;
use Auth,DB;
use App\Traits\UploadTraits;

class QuotaHallController extends Controller
{
    use UploadTraits;
    function __construct()
    {
        $this->middleware('permission:quota-hall-list|quota-hall-create|quota-hall-edit|quota-hall-delete', ['only' => ['index','store']]);
        $this->middleware('permission:quota-hall-create', ['only' => ['create','store']]);
        $this->middleware('permission:quota-hall-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:quota-hall-delete', ['only' => ['destroy']]);
    }

    public function quotahallDetails(Request $request , $id ,$type){
        $quotaHallInfo = [];
        $programme = [];
        $dataId = $id;
        $dataType = $type;
        $quotaHallInfo = QuotaHall::find($id);
        $quotaInfo = Quota::where('id',$id)->first();
        if(isset($quotaHallInfo) && !empty($quotaHallInfo) || isset($quotaInfo) && !empty($quotaInfo)){
            if ($type == 'create') {   
                $checkhallsetting = HallSetting::where('id',$quotaInfo->hall_setting_id)->first();
                $yearProgramme = ProgrammeHallSetting::where('hall_setting_id',$checkhallsetting->id)->pluck('programme_id')->toArray();
                    if(isset($yearProgramme) && count($yearProgramme)) {
                        $programme = Programme::select('id','programme_code','programme_name')->whereIn('id',$yearProgramme)->orderBy('programme_name','ASC')->get();
                    } 
            if(isset($quotaInfo->getHallSettingDetail) && !empty($quotaInfo->getHallSettingDetail->year)){
                $headerTitle = $quotaInfo->getHallSettingDetail->year." (New Hall)";
            }else{
                $headerTitle = "Quota (New Hall)";
            }
            return view('admin.quota-hall.create',compact('headerTitle','dataId','dataType','quotaInfo','programme'));
            }elseif($type == 'show'){
                $quotaInfo = Quota::where('id',$quotaHallInfo->quota_id)->first();
                $headerTitle = "Hall Setting";
            }elseif($type=="programme"){
                $quotaHallInfo = QuotaHall::find($id);
                $headerTitle = "Hall Setting";
            }elseif($type == 'edit'){
                $quotaHallInfo = QuotaHall::find($id);
                $quotaInfo = Quota::where('id',$quotaHallInfo->quota_id)->first();
                $yearProgramme = ProgrammeHallSetting::where('hall_setting_id',$quotaInfo->hall_setting_id)->pluck('programme_id')->toArray();
                if (isset($yearProgramme) && count($yearProgramme)) {
                    $programme = Programme::select('id','programme_code','programme_name')->whereIn('id',$yearProgramme)->orderBy('programme_name','ASC')->get();
                }
                $quotaProgramme = HallProgramme::where('qouta_hall_id',$id)->pluck('programme_id')->toArray();
                $headerTitle = "Quota Hall Edit";
                return view('admin.quota-hall.comman',compact('headerTitle','quotaHallInfo','dataId','dataType','quotaInfo','programme','quotaProgramme'));
            }elseif($type == 'room'){
                $quotaHallInfo = QuotaHall::find($id);
                $headerTitle = "Hall Setting";
            }else{
                return redirect()->route('admin.accommondation-setting.index');
            }
        }else{
            return redirect()->route('admin.accommondation-setting.index');
        }
        return view('admin.quota-hall.comman',compact('headerTitle','quotaHallInfo','dataId','dataType'));
    }


   public function store(Request $request){
        $input = $request->all();
        if ($input['type'] == 'add') {
            $start = strtotime($input['start_date']);
            $end = strtotime($input['end_date']);
            $quota = Quota::select('id','hall_setting_id')->where('start_date',$start)->where('end_date',$end)->first();
            if (empty($quota)) {
                return redirect()->back()->with('error', 'Quota not found in this period.');
            }
        }else{
            $quota = Quota::select('id','hall_setting_id')->where('id',$input['quote_id'])->first();
        }
        $pdf ='';
        if (!empty($request->file('pdf'))) {
            $files = $request->file('pdf');
            $pdf = $this->uploadSingleImage($files,'hall','');
        }
        $addhall                       = new QuotaHall();
        $addhall['quota_id']           =  $quota->id;
        $addhall['hall_setting_id']    =  $quota->hall_setting_id;
        $addhall['start_date']         =  strtotime($input['start_date']);
        $addhall['end_date']           =  strtotime($input['end_date']);
        $addhall['total_quotas']       =  $input['total_quotas'];
        $addhall['male']               =  $input['male'];
        $addhall['female']             =  $input['female'];
        $addhall['college_name']       =  $input['college_name'];
        $addhall['address']            =  $input['address'];
        $addhall['room_type']          =  $input['room_type'];
        $addhall['ass_name']           =  $input['ass_name'];
        $addhall['ass_mobile']         =  $input['ass_mobile'];
        $addhall['ass_email']          =  $input['ass_email'];
        $addhall['room_key_location']  =  $input['room_key_location'];
        if ($input['type'] == 'add') {
            $addhall['check_in_date']      =  strtotime($input['check_in_date']);
            $addhall['check_out_date']     =  strtotime($input['check_out_date']);
        }else{
            $addhall['check_in_date']      =  $input['check_in_date'];
            $addhall['check_out_date']     =  $input['check_out_date'];
        }
        $addhall['check_in_time']      =  strtotime($input['check_in_time']);
        $addhall['check_out_time']     =  strtotime($input['check_out_time']);
        $addhall['pdf']                =  $pdf;
        $addhall['status']             =  $input['status'];
        $addhall->save();
        if(isset($addhall->id)){
            if (isset($input['programmes']) && count($input['programmes'])) {
                foreach ($input['programmes'] as $key => $programmeValue) {
                    HallProgramme::insert(['qouta_hall_id'=>$addhall->id,'programme_id'=>$programmeValue]);
                }
            }
        }
        if(isset($input['status']) && $input['status'] == '1'){
            $status = QuotaHall::find($addhall->id); 
            if(isset($input['programmes']) && !empty($input['programmes'])) {
              $HallProgramme = HallProgramme::where('qouta_hall_id',$status->id)->get();
                foreach ($HallProgramme as $getHllProgrammes) {
                    $totalGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                        })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$status->quota_id)->where('programme_code',$getHllProgrammes->getProgrammeDetail->programme_code)->limit($status->male)->whereNull('quota_hall_id')->get();
                    if (isset($totalGenderMaleBooking) && count($totalGenderMaleBooking)) {
                        foreach ($totalGenderMaleBooking as $key => $valueData) {
                            $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                            $accetptstauts = [];
                            $accetptstauts['status']        = "Updated";
                            $accetptstauts['quota_hall_id'] = $status->id;
                            $mailInfo = [
                                'given_name'            => $datauser->given_name,
                                'application_number'    => $datauser->application_number,                        
                                'accommodation'         => $valueData->getHallSettingDetail,                    
                                'booking'               => $valueData,                        
                                'quotahall'             => $status,                        
                                'memberinfo'            => $datauser,                        
                            ];
                            $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                            SendEmailJob::dispatch($details);
                            HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                        }
                    }
                }
            }else{
                $totalGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                        })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$status->quota_id)->limit($status->male)->whereNull('quota_hall_id')->get();
                if (isset($totalGenderMaleBooking) && count($totalGenderMaleBooking)) {
                    foreach ($totalGenderMaleBooking as $key => $valueData) {
                        $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                        $accetptstauts = [];
                        $accetptstauts['status']        = "Updated";
                        $accetptstauts['quota_hall_id'] = $status->id;
                        $mailInfo = [
                            'given_name'            => $datauser->given_name,
                            'application_number'    => $datauser->application_number,                        
                            'accommodation'         => $valueData->getHallSettingDetail,                    
                            'booking'               => $valueData,                        
                            'quotahall'             => $status,                        
                            'memberinfo'            => $datauser,                        
                        ];
                        $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                        SendEmailJob::dispatch($details);
                        HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                    }
                }
            }
            if(isset($input['programmes']) && !empty($input['programmes'])) {
                  $HallProgramme = HallProgramme::where('qouta_hall_id',$status->id)->get();
                    foreach ($HallProgramme as $getHllProgrammes) {
                        $totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                            })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$status->quota_id)->where('programme_code',$getHllProgrammes->getProgrammeDetail->programme_code)->limit($status->female)->whereNull('quota_hall_id')->get();  
                    if (isset($totalGenderFemaleBooking) && count($totalGenderFemaleBooking)) {
                        foreach ($totalGenderFemaleBooking as $key => $valueData) {
                            $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                            $accetptstauts = [];
                            $accetptstauts['status']        = "Updated";
                            $accetptstauts['quota_hall_id'] = $status->id;
                            $mailInfo = [
                                'given_name'            => $datauser->given_name,
                                'application_number'    => $datauser->application_number,                        
                                'accommodation'         => $valueData->getHallSettingDetail,                     
                                'booking'               => $valueData,                       
                                'quotahall'             => $status,                        
                                'memberinfo'            => $datauser,                        
                            ];
                            $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                            SendEmailJob::dispatch($details);
                            HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                        }
                    } 
                }
            }else{
                $totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                        })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$status->quota_id)->limit($status->female)->whereNull('quota_hall_id')->get();  
                if (isset($totalGenderFemaleBooking) && count($totalGenderFemaleBooking)) {
                    foreach ($totalGenderFemaleBooking as $key => $valueData) {
                        $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                        $accetptstauts = [];
                        $accetptstauts['status']        = "Updated";
                        $accetptstauts['quota_hall_id'] = $status->id;
                        $mailInfo = [
                            'given_name'            => $datauser->given_name,
                            'application_number'    => $datauser->application_number,                        
                            'accommodation'         => $valueData->getHallSettingDetail,                     
                            'booking'               => $valueData,                       
                            'quotahall'             => $status,                        
                            'memberinfo'            => $datauser,                        
                        ];
                        $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                        SendEmailJob::dispatch($details);
                        HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                    }
                } 
            } 
        }
        return redirect()->route('admin.hallDetails',[$quota->hall_setting_id,'halls']);
    }


    public function update(Request $request, $id){
        $input                          = $request->all();
        $quotaHall = QuotaHall::where('id',$id)->first();
        $oldStatus = $quotaHall->status;
        $pdf ='';
        if (!empty($request->file('pdf'))) {
            $files = $request->file('pdf');
            $pdf = $this->uploadSingleImage($files,'hall','');
        }
        $data                       = [];
        $data['start_date']         =  strtotime($input['start_date']);
        $data['end_date']           =  strtotime($input['end_date']);
        $data['male']               =  $input['male'];
        $data['female']             =  $input['female'];
        $data['total_quotas']       =  $input['total_quotas'];
        $data['college_name']       =  $input['college_name'];
        $data['address']            =  $input['address'];
        $data['room_type']          =  $input['room_type'];
        $data['ass_name']           =  $input['ass_name'];
        $data['ass_mobile']         =  $input['ass_mobile'];
        $data['ass_email']          =  $input['ass_email'];
        $data['room_key_location']  =  $input['room_key_location'];
        $data['check_in_date']      =  $input['check_in_date'];
        $data['check_in_time']      =  strtotime($input['check_in_time']);
        $data['check_out_date']     =  $input['check_out_date'];
        $data['check_out_time']     =  strtotime($input['check_out_time']);
        if (isset($input['status']) && !empty($input['status'])) {
            $data['status']         =  $input['status'];
        }
        if (!empty($request->file('pdf'))) {
            $data['pdf']                =  $pdf;
        }
        QuotaHall::where('id',$id)->update($data);
        if(isset($input['programmes']) && !empty($input['programmes'])) {
            HallProgramme::where('qouta_hall_id',$id)->delete();
            foreach ($input['programmes'] as $key => $programmeValue) {
                    HallProgramme::insert(['qouta_hall_id'=>$quotaHall->id,'programme_id'=>$programmeValue]);
            }
        } 
        if ($quotaHall->status=="1") {
            $getQuotaBookingRecord = HallBookingInfo::where('quota_hall_id',$id)->whereIn('status',['Completed','Pending','Accepted','Paid','Updated','Unpaid'])->get();
            if (isset($getQuotaBookingRecord) && count($getQuotaBookingRecord)) {
                $status = QuotaHall::find($id); 
                foreach ($getQuotaBookingRecord as $key => $bookingRecordValue) {
                    $mailInfo = [
                        'given_name'            => $bookingRecordValue->getMemberdata->given_name,
                        'application_number'    => $bookingRecordValue->getMemberdata->application_number,                        
                        'accommodation'         => $bookingRecordValue->getHallSettingDetail,                        
                        'quotahall'             => $status,                        
                        'booking'               => $bookingRecordValue,                    
                        'memberinfo'            => $bookingRecordValue->getMemberdata,                        
                    ];
                    $details = ['type'=>'HallInfoUpdate','email' =>$bookingRecordValue->getMemberdata->email_address,'mailInfo' => $mailInfo];
                    SendEmailJob::dispatch($details);
                }
            }
        }
        if ($quotaHall->status=="1" && $oldStatus=='1') {
            if (!empty($quotaHall)) {
                $maleLimit      = $input['male'] - $quotaHall->male;
                $femaleLimit    = $input['female'] - $quotaHall->female;
                $quotaHallUpdate = new QuotaHallUpdate();
                $quotaHallUpdate['hall_setting_id']  = $quotaHall->hall_setting_id;
                $quotaHallUpdate['quota_id']         = $quotaHall->quota_id;
                $quotaHallUpdate['quota_hall_id']    = $id;
                $quotaHallUpdate['male_old_qty']     = $quotaHall->male;
                $quotaHallUpdate['male_new_qty']     = (isset($input['male']) && !empty($input['male']))?$input['male']:0;
                $quotaHallUpdate['female_old_qty']   = $quotaHall->female;
                $quotaHallUpdate['female_new_qty']   = (isset($input['female']) && !empty($input['female']))?$input['female']:0;
                // $quotaHallUpdate->save(); 
                $totalGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                                })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$quotaHall->quota_id)->where('quota_hall_id',$quotaHall->id)->count();
                 $totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                                })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$quotaHall->quota_id)->where('quota_hall_id',$quotaHall->id)->count();

                $maleActualLimit = $input['male'] - $totalGenderMaleBooking;
                $FemaleActualLimit = $input['female'] - $totalGenderFemaleBooking;
               // dd($mailActualLimit);
                if ($maleActualLimit > 0) {
                    $totalGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                                })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$quotaHall->quota_id)->limit($maleActualLimit)->whereNull('quota_hall_id')->get();
                    if (isset($totalGenderMaleBooking) && count($totalGenderMaleBooking)) {
                        foreach ($totalGenderMaleBooking as $key => $valueData) {
                            $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                            $accetptstauts = [];
                            $accetptstauts['status']        = "Updated";
                            $accetptstauts['quota_hall_id'] = $quotaHall->id;
                            $mailInfo = [
                                'given_name'            => $datauser->given_name,
                                'application_number'    => $datauser->application_number,                        
                                'accommodation'         => $valueData->getHallSettingDetail,                        
                                'quotahall'             => $quotaHall,                        
                                'booking'               => $valueData,                    
                                'memberinfo'            => $datauser,                        
                            ];
                            $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                            SendEmailJob::dispatch($details);
                            HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                        }
                    } 
                }
                if ($FemaleActualLimit > 0) {
                    $totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                                })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$quotaHall->quota_id)->limit($FemaleActualLimit)->whereNull('quota_hall_id')->get();
                    if (isset($totalGenderFemaleBooking) && count($totalGenderFemaleBooking)) {
                        foreach ($totalGenderFemaleBooking as $key => $valueData) {
                            $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                            $accetptstauts = [];
                            $accetptstauts['status']        = "Updated";
                            $accetptstauts['quota_hall_id'] = $quotaHall->id;
                            $mailInfo = [
                                'given_name'            => $datauser->given_name,
                                'application_number'    => $datauser->application_number,                        
                                'accommodation'         => $valueData->getHallSettingDetail,                        
                                'quotahall'             => $quotaHall,                        
                                'booking'               => $valueData,                        
                                'memberinfo'            => $datauser,                        
                            ];
                            $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                            SendEmailJob::dispatch($details);
                            HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                        }
                    } 
                }
            }
        }elseif(isset($input['status']) && $input['status'] == '1' && $quotaHall->status!='1'){
            if(isset($input['programmes']) && !empty($input['programmes'])) {
                $HallProgramme = HallProgramme::where('qouta_hall_id',$quotaHall->id)->get();
                $getAllProgramme =[];
                foreach($HallProgramme as $getHllProgrammes){
                    $getAllProgramme[] = $getHllProgrammes->getProgrammeDetail->programme_code;
                }
                $totalFemailLimit = $quotaHall->male;
                $remaing = 0;
                $totalGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id','hall_booking_infos.programme_code')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$quotaHall->quota_id)->whereIn('programme_code',$getAllProgramme)->limit($totalFemailLimit)->whereNull('quota_hall_id')->get();
                if($totalFemailLimit >0){
                    if (isset($totalGenderMaleBooking) && count($totalGenderMaleBooking)) {
                        foreach($totalGenderMaleBooking as $key => $valueData){
                            $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                            $accetptstauts = [];
                            $accetptstauts['status']        = "Updated";
                            $accetptstauts['quota_hall_id'] = $quotaHall->id;
                            $mailInfo = [
                                'given_name'            => $datauser->given_name,
                                'application_number'    => $datauser->application_number,                        
                                'accommodation'         => $valueData->getHallSettingDetail,                     
                                'booking'               => $valueData,                       
                                'quotahall'             => $quotaHall,                        
                                'memberinfo'            => $datauser,                        
                            ];
                            $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                            SendEmailJob::dispatch($details);
                            HallBookingInfo::where('id',$valueData->id)->update($accetptstauts);
                            $remaing = $totalFemailLimit-count($totalGenderMaleBooking);
                        }
                    }
                }
                if($remaing >0){
                    $totalGenderMaleBooking1 = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id','hall_booking_infos.programme_code')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                    })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$quotaHall->quota_id)->limit($remaing)->whereNull('quota_hall_id')->get();
                    if (isset($totalGenderMaleBooking1) && count($totalGenderMaleBooking1)) {
                        foreach ($totalGenderMaleBooking1 as $key => $valueData) {
                            $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                            $accetptstauts = [];
                            $accetptstauts['status']        = "Updated";
                            $accetptstauts['quota_hall_id'] = $quotaHall->id;
                            $mailInfo = [
                                'given_name'            => $datauser->given_name,
                                'application_number'    => $datauser->application_number,                        
                                'accommodation'         => $valueData->getHallSettingDetail,                     
                                'booking'               => $valueData,                       
                                'quotahall'             => $quotaHall,                        
                                'memberinfo'            => $datauser,                        
                            ];
                            $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                            SendEmailJob::dispatch($details);
                            HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                        }
                    }
                }
            }else{
                $totalGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                        })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$quotaHall->quota_id)->limit($quotaHall->male)->whereNull('quota_hall_id')->get();  
                if (isset($totalGenderMaleBooking) && count($totalGenderMaleBooking)) {
                    foreach ($totalGenderMaleBooking as $key => $valueData) {
                        $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                        $accetptstauts = [];
                        $accetptstauts['status']        = "Updated";
                        $accetptstauts['quota_hall_id'] = $quotaHall->id;
                        $mailInfo = [
                            'given_name'            => $datauser->given_name,
                            'application_number'    => $datauser->application_number,                        
                            'accommodation'         => $valueData->getHallSettingDetail,                     
                            'booking'               => $valueData,                       
                            'quotahall'             => $quotaHall,                        
                            'memberinfo'            => $datauser,                        
                        ];
                        $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                        SendEmailJob::dispatch($details);
                        HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                    }
                }
            }
            if(isset($input['programmes']) && !empty($input['programmes'])) {
                $HallProgramme = HallProgramme::where('qouta_hall_id',$quotaHall->id)->get();
                $getAllProgramme =[];
                foreach($HallProgramme as $getHllProgrammes){
                    $getAllProgramme[] = $getHllProgrammes->getProgrammeDetail->programme_code;
                }
                $totalFemailLimit = $quotaHall->female;
                $remaing = 0;
                $totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id','hall_booking_infos.programme_code')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$quotaHall->quota_id)->whereIn('programme_code',$getAllProgramme)->limit($totalFemailLimit)->whereNull('quota_hall_id')->get();
                if($totalFemailLimit >0){
                    if (isset($totalGenderFemaleBooking) && count($totalGenderFemaleBooking)) {
                        foreach($totalGenderFemaleBooking as $key => $valueData){
                            $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                            $accetptstauts = [];
                            $accetptstauts['status']        = "Updated";
                            $accetptstauts['quota_hall_id'] = $quotaHall->id;
                            $mailInfo = [
                                'given_name'            => $datauser->given_name,
                                'application_number'    => $datauser->application_number,                        
                                'accommodation'         => $valueData->getHallSettingDetail,                     
                                'booking'               => $valueData,                       
                                'quotahall'             => $quotaHall,                        
                                'memberinfo'            => $datauser,                        
                            ];
                            $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                            SendEmailJob::dispatch($details);
                            HallBookingInfo::where('id',$valueData->id)->update($accetptstauts);
                            $remaing = $totalFemailLimit-count($totalGenderFemaleBooking);
                        }
                    }
                }
                if($remaing >0){
                    $totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id','hall_booking_infos.programme_code')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                    })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$quotaHall->quota_id)->limit($remaing)->whereNull('quota_hall_id')->get();
                    if (isset($totalGenderFemaleBooking) && count($totalGenderFemaleBooking)) {
                        foreach ($totalGenderFemaleBooking as $key => $valueData) {
                            $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                            $accetptstauts = [];
                            $accetptstauts['status']        = "Updated";
                            $accetptstauts['quota_hall_id'] = $quotaHall->id;
                            $mailInfo = [
                                'given_name'            => $datauser->given_name,
                                'application_number'    => $datauser->application_number,                        
                                'accommodation'         => $valueData->getHallSettingDetail,                     
                                'booking'               => $valueData,                       
                                'quotahall'             => $quotaHall,                        
                                'memberinfo'            => $datauser,                        
                            ];
                            $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                            SendEmailJob::dispatch($details);
                            HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                        }
                    }
                }
            }else{
                $totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                        })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->where('hall_booking_infos.status','Paid')->orderBy('hall_booking_infos.id','ASC')->where('quota_id',$quotaHall->quota_id)->limit($quotaHall->female)->whereNull('quota_hall_id')->get();  
                if (isset($totalGenderFemaleBooking) && count($totalGenderFemaleBooking)) {
                    foreach ($totalGenderFemaleBooking as $key => $valueData) {
                        $datauser = MemberInfo::where('id',$valueData->user_type_id)->first();
                        $accetptstauts = [];
                        $accetptstauts['status']        = "Updated";
                        $accetptstauts['quota_hall_id'] = $quotaHall->id;
                        $mailInfo = [
                            'given_name'            => $datauser->given_name,
                            'application_number'    => $datauser->application_number,                        
                            'accommodation'         => $valueData->getHallSettingDetail,                     
                            'booking'               => $valueData,                       
                            'quotahall'             => $quotaHall,                        
                            'memberinfo'            => $datauser,                        
                        ];
                        $details = ['type'=>'InformationReleased','email' =>$datauser->email_address,'mailInfo' => $mailInfo];
                        SendEmailJob::dispatch($details);
                        HallBookingInfo::where('id', $valueData->id)->update($accetptstauts);
                    }
                }
            }           
                
        }
        
        return redirect()->route('admin.quotahall.quotahallDetails',[$id,'show'])->with('success', 'Quota updated successfully');
    }

    public function settingtatusChange(Request $request, $id, $status) {  
        $quotaRoom = QuotaRoom::select('id','user_id')->where('id',$id)->first();      
        if (isset($quotaRoom) && !empty($quotaRoom)) {
            $quotaRoom->update(['status' => $request->status]);
        }
        return redirect()->route('admin.quota-hall.comman',[$id,'show'])->with('success', 'HallInfo status updated successfully!');        
    }

    public function destroy($id)
    {
        QuotaRoom::find($id)->delete();
        return redirect()->route('admin.quota-hall.comman')->with('success', 'Member deleted successfully');
    }

    public function multipleQuotaHallDelete(Request $request)
    {
        $input = $request->all();
		if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $quota) {
				if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete') {
                    QuotaHall::where('id', $quota)->delete();
				 }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'enable'){
                    $quotaInof = QuotaHall::select('id','status')->where('id', $quota)->first();
                    QuotaHall::where('id',$quotaInof->id)->update(['status'=>'1']);
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'disable'){
                    $quotaInof = QuotaHall::select('id','status')->where('id', $quota)->first();
                    QuotaHall::where('id',$quotaInof->id)->update(['status'=>'0']);
                }else{
                    QuotaHall::where('id', $quota)->update(['status'=>'1']);
                }
            }
        }
        return redirect()->back();
    }
	
	 public function getquotahalldetails(Request $request){
        $id = $request->id;
        if (isset($id) && !empty($id)) {
            $data = QuotaHall::where('id',$id)->first();
			return response()->json($data);
        }
    }

}

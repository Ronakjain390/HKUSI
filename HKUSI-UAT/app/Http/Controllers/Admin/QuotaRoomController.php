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
use App\Models\HallBookingInfo;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmailJob;
use Auth;

class QuotaRoomController extends Controller
{
    //
    function __construct()
    {
        $this->middleware('permission:quota-room-list|quota-room-create|quota-room-edit|quota-room-delete', ['only' => ['index','store']]);
        $this->middleware('permission:quota-room-create', ['only' => ['create','store']]);
        $this->middleware('permission:quota-room-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:quota-room-delete', ['only' => ['destroy']]);
    }

    public function roomDetail(Request $request , $id ,$type){
        $quotaRoomInfo = [];
        $dataId = $id;
        $dataType = $type;
        if ($type == 'create') {
            $quotaHall = QuotaHall::where('id',$id)->first();
            $headerTitle = "New Room";
            return view('admin.quota-room.create',compact('headerTitle','quotaHall','dataId','dataType'));
        }elseif($type == 'show'){
            $quotaRoomInfo = QuotaRoom::find($id);
            $headerTitle = "Room Detail";
        }elseif($type == 'edit'){
            $quotaRoomInfo = QuotaRoom::find($id);
            $headerTitle = "Room Detail";
        }elseif($type == 'room'){
            $quotaRoomInfo = QuotaRoom::find($id);
            $headerTitle = "Room Detail";
        }elseif($type == 'record'){
            $quotaRoomInfo = QuotaRoom::find($id);
            $headerTitle = "Room Record";
        }else{
            return redirect()->route('admin.accommondation-setting.index');
        }
        return view('admin.quota-room.comman',compact('headerTitle','quotaRoomInfo','dataId','dataType'));
    }


    public function store(Request $request){
        $input                              = $request->all();
        $hallSetting = QuotaHall::select('id','male','female','quota_id')->where('id',$input['quota_hall_id'])->first();
        $quota                              = new QuotaRoom();
        $quota['hall_setting_id']           =  $hallSetting->getQuotaDetail->hall_setting_id;
        $quota['quota_id']                  =  $hallSetting->getQuotaDetail->id;
        $quota['quota_hall_id']             =  $input['quota_hall_id'];
        $quota['room_code']                 =  $input['room_code'];
        $quota['college_name']              =  $input['college_name'];
        $quota['start_date']                =  strtotime($input['start_date']);
        $quota['end_date']                  =  strtotime($input['end_date']);
        $quota['gender']                    =  $input['gender'];
        $quota['status']                    =  1;
        $quota->save();
        if(isset($input['quota_hall_id']) && !empty($input['quota_hall_id'])){
            if ($input['gender'] == 'Male') {
                $getGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                        })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->orderBy('hall_booking_infos.id','ASC')->where('quota_hall_id',$hallSetting->id)->whereNotNull('quota_room_id')->count();
                if (isset($getGenderMaleBooking) && ($hallSetting->male >= $getGenderMaleBooking)) {
                    $totalGenderMaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                            })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Male')->orderBy('hall_booking_infos.id','ASC')->where('quota_hall_id',$hallSetting->id)->whereNull('quota_room_id')->first();
                    //dd($totalGenderMaleBooking);
                // dd($getGenderMaleBooking,$hallSetting->male,$totalGenderMaleBooking);
                    if(!empty($totalGenderMaleBooking)){
                        $totalGenderMaleBooking->update(['quota_room_id'=>$quota->id]);
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
                    }
                }
            }else{
                $getGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                        })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->orderBy('hall_booking_infos.id','ASC')->where('quota_hall_id',$hallSetting->id)->whereNotNull('quota_room_id')->count();
                if (isset($getGenderFemaleBooking) && ($hallSetting->female >= $getGenderFemaleBooking)) {
                    $totalGenderFemaleBooking = HallBookingInfo::select('hall_booking_infos.id','hall_booking_infos.user_type_id')->leftJoin('member_infos', function ($join) { $join->on('hall_booking_infos.user_type_id', '=', 'member_infos.id');
                            })->whereNull('member_infos.deleted_at')->where('member_infos.gender','Female')->orderBy('hall_booking_infos.id','ASC')->where('quota_hall_id',$hallSetting->id)->whereNull('quota_room_id')->first();
                    if(!empty($totalGenderFemaleBooking)){
                        $totalGenderFemaleBooking->update(['quota_room_id'=>$quota->id]);
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
                }
            }
        }
        return redirect()->route('admin.hallDetails',[$hallSetting->getQuotaDetail->hall_setting_id,'rooms'])->with('success','Quota created successfully');
    }


    public function update(Request $request, $id){
        $input                          = $request->all();
        $data                           = [];
        $data['room_code']              =  $input['room_code'];
        $data['college_name']           =  $input['college_name'];
        $data['start_date']             = strtotime($input['start_date']);
        $data['end_date']               = strtotime($input['end_date']);
        $data['gender']                 =  $input['gender'];
        $data['status']                 =  $input['status'];
        QuotaRoom::where('id',$id)->update($data);  
        return redirect()->route('admin.room.roomDetail',[$id,'show'])->with('success', 'Room  updated successfully');
    }

    public function settingtatusChange(Request $request, $id, $status) {  
        $quotaRoom = QuotaRoom::select('id','user_id')->where('id',$id)->first();      
        if (isset($quotaRoom) && !empty($quotaRoom)) {
            $quotaRoom->update(['status' => $request->status]);
        }
        return redirect()->route('admin.quota-room.comman',[$id,'show'])->with('success', 'HallInfo status updated successfully!');
    }

    public function destroy($id)
    {
        QuotaRoom::find($id)->delete();
        return redirect()->route('admin.quota-room.comman')->with('success', 'Member deleted successfully');
    }

    public function multipleRoomDelete(Request $request)
    {        
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $quota) {
                QuotaRoom::where('id', $quota)->delete();
            }
        }
        return redirect()->back();
    }

}

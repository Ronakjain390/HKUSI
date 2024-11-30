<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Programme;
use App\Models\Language;
use App\Models\Category;
use App\Models\HallSetting;
use App\Models\PrivateEventSetting;
use App\Models\PrivateEventOrder;
use App\Models\PrivateEventSettingImage;
use App\Models\PrivateEventProgramme;
use App\Traits\UploadTraits;
use Auth, Storage, Config;

class PrivateEventSettingController extends Controller
{
    use UploadTraits;

    // This controller created by Akash 
    function __construct()
    {
        $this->middleware('permission:event-list|event-create|event-edit|event-delete', ['only' => ['index','store']]);
        $this->middleware('permission:event-create', ['only' => ['create','store']]);
        $this->middleware('permission:event-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:event-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "Settings (Private-Event)";
        return view('admin.private-event-setting.index',compact('headerTitle'));
    } 

    public function create(){
        $headerTitle = "Private Event Create";
        $programme = Programme::orderBy('programme_name','ASC')->get();
        $language = Language::where('status',1)->get();
        $category = Category::where('status',1)->get();
        $HallSetting = HallSetting::where('status',1)->get();
        return view('admin.private-event-setting.create',compact('headerTitle','programme','language','category','HallSetting'));
    }

    public function privateEventSettingDetails(Request $request , $id ,$type){ 
        $dataId = $id;        
        $dataType = $type;
        $eventImages = $eventInfo = $programme = $language = $category = $eventprograme=$HallSetting=[];
        $eventInfo = PrivateEventSetting::find($id);
        if ($dataType=="show") {
            $headerTitle = "Private Event Details";
        }elseif($dataType=="edit"){
            $language = Language::where('status',1)->get();
            $category = Category::where('status',1)->get();
            $HallSetting = HallSetting::where('status',1)->get();
            $findProgram=PrivateEventProgramme::where('event_id', '=', $id)->get();
            $eventprograme = PrivateEventProgramme::where('event_id',$id)->distinct()->pluck('program_id')->toArray();
            if(count($eventprograme)){
                $programme = Programme::whereIn('id',$eventprograme)->get();
            }
            $programme = Programme::get();
            $headerTitle = "Private Event Details";
        }elseif($dataType=="images"){
            $headerTitle = "Private Event Details";
            $eventImages = PrivateEventSettingImage::where('event_setting_id',$eventInfo->id)->get();
        }elseif($dataType=="programme"){
              $headerTitle = "Private Event Programme  Details";
        }elseif($dataType=="editimage"){
            $headerTitle = "Private Event Details";
            $eventImages = PrivateEventSettingImage::where('event_setting_id',$eventInfo->id)->get();
        }else{
            return redirect()->route('admin.private-event-setting.index');
        }
        if (!empty($eventInfo)) {
            return view('admin.private-event-setting.comman',compact('headerTitle','eventInfo','programme','dataId','dataType','eventImages','language','category','eventprograme','HallSetting'));          
        }else{
            return redirect()->route('admin.private-event-setting.index');
        }        
    }

    public function store(Request $request){
        $input = $request->all();
        //dd($input);
         $this->validate($request, [
           'event_name'      => 'required',
        ]); 
        $main_image = $thumb_image = '';
        if (isset($input['main_image']) && !empty($input['main_image'])) {
            $image = $this->uploadSingleImage($input['main_image'],'event','');
            if ($image != "") {
                $frontImage = basename($image);
                $filenametostore    = "event/thumb_".$frontImage;
                // if(Storage::disk($DISK_NAME)->exists($filenametostore)){
                //     $thumb_image  =  $filenametostore;
                // }
                $main_image = $image;
            }
        }

        // dd($main_image,$thumb_image);
        $eventData                           =  new PrivateEventSetting();
        $eventData['hall_setting_id']        =  $input['hall_setting_id'];
        $eventData['event_category_id']      =  $input['event_category_id'];
        $eventData['event_name']             =  $input['event_name'];
        $eventData['short_description']      =  $input['short_description'];
        $eventData['description']            =  $input['description'];
        $eventData['location']               =  $input['location'];
        $eventData['assembly_location']      =  $input['assembly_location'];
        $eventData['assembly_start_time']    =  strtotime($input['assembly_start_time']);
        $eventData['assembly_end_time']      =  strtotime($input['assembly_end_time']);
        $eventData['date']                   =  strtotime($input['date']. '00:00:00');
        $eventData['start_time']             =  strtotime($input['start_time']);
        $eventData['end_time']               =  strtotime($input['end_time']);
        $eventData['application_deadline']   =  strtotime($input['application_deadline']. '23:59:59');
        $eventData['quota']                  =  $input['quota'];
        $eventData['quota_balance']          =  $input['quota_balance'];
        $eventData['unit_price']             =  $input['unit_price'];
        $eventData['additional_info']        =  $input['additional_info'];
        $eventData['notes']                  =  $input['notes'];
        $eventData['terms_condition']        =  $input['terms_condition'];
        $eventData['terms_link']             =  $input['terms_link'];
        $eventData['pre_arrival']            =  $input['pre_arrival'];
        $eventData['pre_link']               =  $input['pre_link'];
        $eventData['booking_limit']          =  $input['booking_limit'];
        $eventData['main_image']             =  $main_image;
        $eventData['thumb_image']            =  $thumb_image;
        $eventData['status']                 =  $input['status'];
        $eventData['language_id']            =  $input['language_id'];
        $eventData->save();

        $last_id = $eventData->id;
        $date = date('Y-m-d H:i');
        if(isset($input['programme_id'])){
            for($i=0;$i<count($input['programme_id']);$i++){
                PrivateEventProgramme::insert(['event_id'=>$last_id,'program_id'=>$input['programme_id'][$i]]);
            }
        }
        

        if(isset($eventData->id)){
            if (isset($input['images']) && count($input['images'])) {
                foreach ($input['images'] as $key => $programmeValue) {
                    $event_main_image = $event_thumb_image = '';

                    if (isset($programmeValue) && !empty($programmeValue)) {
                        $images = $this->uploadSingleImage($programmeValue,'event','');
                        if ($images != "") {
                            $frontImage = basename($images);
                            $filenametostore    = "event/thumb_".$frontImage;
                            // if(Storage::disk($DISK_NAME)->exists($filenametostore)){
                            //     $event_thumb_image  =  $filenametostore;
                            // }
                            $event_main_image = $images;
                        }
                    }
                    PrivateEventSettingImage::insert(['event_setting_id'=>$eventData->id,'main_image'=>$event_main_image,'thumb_image'=>$event_thumb_image]);
                }
            }
        }
        return redirect()->route('admin.private-event-setting.index')->with('message', 'Event create successfully.');
    }

    public function multiplePrivateEventDelete(Request $request)
    {        
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $hallbooking) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Delete') {
                    
                    PrivateEventOrder::where('event_id', $hallbooking)->delete();
                    PrivateEventSetting::where('id', $hallbooking)->delete();
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Cancelled'){

                    $hallbookingRecord = PrivateEventSetting::where('id',$hallbooking)->first();

                        $eventBooking = PrivateEventOrder::where('event_id', $hallbookingRecord->id )->whereIn('booking_status',['Paid','Pending','Updated'])->get();

                        if(count($eventBooking)){
                            foreach ($eventBooking as $key => $value) {
                                $eventData = PrivateEventSetting::where('id',$value->event_id)->first();
                                    if(!empty($eventData)){
                                            $eventData->increment('quota_balance',$value->no_of_seats);
                                    }
                                  PrivateEventOrder::where('event_id',$value->event_id)->update(['booking_status'=>'Cancelled']);

                                $memberDetail = MemberInfo::where('application_number',$value->application_id)->where('status',1)->first();
                                if(!empty($memberDetail)){
                                    $mailInfo = [
                                        'given_name'            => $memberDetail->given_name,
                                        'application_number'    => $memberDetail->application_number,
                                        'event_name'            => $hallbookingRecord->event_name,
                                        'date'                  => $hallbookingRecord->date,
                                    ];
                                    $CancelledEmail = ['type'=>'PrivateEventCancelled','email' =>$memberDetail->getUserDetail->email,'mailInfo' => $mailInfo];

                                    SendEmailJob::dispatch($CancelledEmail);
                                    \Log::info($memberDetail->given_name.' / '.$memberDetail->application_number.'Private Event Cancelled Event Mail');
                                }
                            }
                        }
                    $hallbookingRecord = PrivateEventSetting::where('id',$hallbooking)->first();
                    $hallbookingRecord->update(['status'=>'Cancelled']);

                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Disabled'){

                    $hallbookingRecord = PrivateEventSetting::where('id',$hallbooking)->first();
                    $eventBooking = PrivateEventOrder::where('event_id',$hallbookingRecord->id)->whereIn('booking_status',['Paid','Updated'])->get();
                       // dd($eventBooking);
                        if(count($eventBooking)){
                            foreach ($eventBooking as $key => $value) {
                                PrivateEventOrder::where('event_id',$value->event_id)->update(['booking_status'=>'Updated']);
                                $memberDetail = MemberInfo::where('application_number',$value->application_id)->where('status',1)->first();
                                if(!empty($memberDetail)){
                                    $mailInfo = [
                                        'given_name'            => $memberDetail->given_name,
                                        'application_number'    => $memberDetail->application_number,
                                        'event_name'            => $hallbookingRecord->event_name,
                                        'date'                  => $hallbookingRecord->date,
                                        'time'                  => $hallbookingRecord->time,
                                        'location'              => $hallbookingRecord->location,
                                        'unit_price'            => $hallbookingRecord->unit_price,
                                        'assembly_location'     => $hallbookingRecord->assembly_location,
                                        'assembly_start_time'         => $hallbookingRecord->assembly_start_time,
                                        'assembly_end_time'         => $hallbookingRecord->assembly_end_time,
                                    ];
                                    $CancelledEmail = ['type'=>'EventInformationUpdate','email' =>$memberDetail->getUserDetail->email,'mailInfo' => $mailInfo];
                                    SendEmailJob::dispatch($CancelledEmail);
                                    \Log::info($memberDetail->given_name.' / '.$memberDetail->application_number.'Private Event Cancelled Event Mail');
                                }
                            }
                        }
                    $hallbookingRecord->update(['status'=>'Disabled']);
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Enabled'){
                    PrivateEventSetting::where('id',$hallbooking)->update(['status'=>'Enabled']);
                }else{
                     $hallbookingRecord = PrivateEventSetting::where('id',$hallbooking)->first();
                        $eventBooking = PrivateEventOrder::where('event_id',$hallbookingRecord->id)->whereIn('booking_status',['Paid','Updated'])->get();
                        if(count($eventBooking)){
                            foreach ($eventBooking as $key => $value) {
                                if($value->booking_status=='Paid' ){
                                    PrivateEventOrder::where('event_id',$value->event_id)->update(['booking_status'=>'Updated']);
                                }           
                                $memberDetail = MemberInfo::where('application_number',$value->application_id)->where('status',1)->first();
                                if(!empty($memberDetail)){
                                    $mailInfo = [
                                        'given_name'            => $memberDetail->given_name,
                                        'application_number'    => $memberDetail->application_number,
                                        'event_name'            => $hallbookingRecord->event_name,
                                        'date'                  => $hallbookingRecord->date,
                                        'start_time'            => $hallbookingRecord->start_time,
                                        'end_time'              => $hallbookingRecord->end_time,
                                        'location'              => $hallbookingRecord->location,
                                        'unit_price'            => $hallbookingRecord->unit_price,
                                        'assembly_location'     => $hallbookingRecord->assembly_location,
                                        'assembly_start_time'         => $hallbookingRecord->assembly_start_time,
                                        'assembly_end_time'         => $hallbookingRecord->assembly_end_time,
                                    ];
                                    $CancelledEmail = ['type'=>'EventInformationUpdate','email' =>$memberDetail->getUserDetail->email,'mailInfo' => $mailInfo];
                                   // dd($CancelledEmail);
                                    SendEmailJob::dispatch($CancelledEmail);
                                    \Log::info($memberDetail->given_name.' / '.$memberDetail->application_number.'Private Event Cancelled Event Mail');
                                }
                            }
                        }
                    PrivateEventSetting::where('id',$hallbooking)->update(['status'=>'Enabled']);
                }
            }
        }
        return redirect()->back();
    }

    public function update(Request $request, $id){
        $input = $request->all();
        if (isset($input['submit_type']) && !empty($input['submit_type']) && $input['submit_type'] == 'basic') {
            $eventData                           =  [];
            $eventData['hall_setting_id']        =  $input['hall_setting_id'];
            $eventData['event_category_id']      =  $input['event_category_id'];
            $eventData['event_name']             =  $input['event_name'];
            $eventData['short_description']      =  $input['short_description'];
            $eventData['description']            =  $input['description'];
            $eventData['location']               =  $input['location'];
            $eventData['assembly_location']      =  $input['assembly_location'];
            $eventData['assembly_start_time']    =  strtotime($input['assembly_start_time']);
            $eventData['assembly_end_time']      =  strtotime($input['assembly_end_time']);
            $eventData['date']                   =  strtotime($input['date']. '00:00:00');
            $eventData['start_time']             =  strtotime($input['start_time']);
            $eventData['end_time']               =  strtotime($input['end_time']);
            $eventData['application_deadline']   =  strtotime($input['application_deadline']. '23:59:59');
            $eventData['quota']                  =  $input['quota'];
            $eventData['quota_balance']          =  $input['quota_balance'];
            $eventData['unit_price']             =  $input['unit_price'];
            $eventData['additional_info']        =  $input['additional_info'];
            $eventData['notes']                  =  $input['notes'];
            $eventData['terms_condition']        =  $input['terms_condition'];
            $eventData['terms_link']             =  $input['terms_link'];
            $eventData['pre_arrival']            =  $input['pre_arrival'];
            $eventData['pre_link']               =  $input['pre_link'];
            $eventData['booking_limit']          =  $input['booking_limit'];
            // $eventData['type']                   =  $input['type'];
            $eventData['status']                 =  $input['status'];
            $eventData['language_id']            =  $input['language_id'];
            $events = PrivateEventSetting::where('id',$id)->first();
            if ($input['status']=="Cancelled") {
                $eventBooking = PrivateEventOrder::where('event_id',$events->id)->whereIn('booking_status',['Paid','Pending','Updated'])->get();
                    if(count($eventBooking)){
                        foreach ($eventBooking as $key => $value) {
                            $eventData = PrivateEventSetting::where('id',$value->event_id)->first();
                            if(!empty($eventData)){
                                    $eventData->increment('quota_balance',$value->no_of_seats);
                            }
                            PrivateEventOrder::where('event_id',$value->event_id)->update(['booking_status'=>'Cancelled']);
                            $memberDetail = MemberInfo::where('application_number',$value->application_id)->where('status',1)->first();
                            if(!empty($memberDetail)){
                                $mailInfo = [
                                    'given_name'            => $memberDetail->given_name,
                                    'application_number'    => $memberDetail->application_number,
                                    'event_name'            => $events->event_name,
                                    'date'                  => $events->date,
                                ];
                                $CancelledEmail = ['type'=>'PrivateEventCancelled','email' =>$memberDetail->getUserDetail->email,'mailInfo' => $mailInfo];
                                SendEmailJob::dispatch($CancelledEmail);
                                \Log::info($memberDetail->given_name.' / '.$memberDetail->application_number.'Private Event Cancelled Event Mail');
                            }
                            $value->update(['status'=>'Cancelled']);
                        }
                    }
            }else{
                $eventBooking = PrivateEventOrder::where('event_id',$events->id)->whereIn('booking_status',['Paid','Updated'])->get();
                if(count($eventBooking)){
                    foreach ($eventBooking as $key => $value) {
                        if($value->booking_status=='Paid'){
                            PrivateEventOrder::where('event_id',$value->event_id)->update(['booking_status'=>'Updated']);
                        }    
                        $oldeventType       = $events->event_category_id;       
                        $oldyear            = $events->hall_setting_id;       
                        $oldDescription     = $events->description;       
                        $oldShortDescription= $events->short_description;       
                        $oldQouta           = $events->quota;       
                        if($oldeventType!=$request->event_category_id || $oldyear!=$request->hall_setting_id || $oldDescription!=$request->description || $oldShortDescription!=$request->short_description || $oldQouta!=$request->quota ){
                            $memberDetail = MemberInfo::where('application_number',$value->application_id)->where('status',1)->first();
                            if(!empty($memberDetail)){
                                $mailInfo = [
                                    'given_name'            => $memberDetail->given_name,
                                    'application_number'    => $memberDetail->application_number,
                                    'event_name'            => $events->event_name,
                                    'date'                  => $events->date,
                                    'start_time'            => $events->start_time,
                                    'end_time'              => $events->end_time,
                                    'location'              => $events->location,
                                    'unit_price'            => $events->unit_price,
                                    'assembly_location'     => $events->assembly_location,
                                    'assembly_start_time'         => $events->assembly_start_time,
                                    'assembly_end_time'         => $events->assembly_end_time,
                                ];
                                $CancelledEmail = ['type'=>'EventInformationUpdate','email' =>$memberDetail->getUserDetail->email,'mailInfo' => $mailInfo]; 
                                SendEmailJob::dispatch($CancelledEmail);
                                \Log::info($memberDetail->given_name.' / '.$memberDetail->application_number.' Event Updated Event Mail');
                            }
                        }                           
                    }
                }
            }
            PrivateEventProgramme::where('event_id', '=', $id)->delete();
            if (isset($input['programme_id']) && !empty($input['programme_id'])) {
                foreach ($input['programme_id'] as $key => $programval) {
                   PrivateEventProgramme::insert(['event_id'=>$id,'program_id'=>$programval]);
                }
            }
        }elseif(isset($input['submit_type']) && !empty($input['submit_type']) && $input['submit_type'] == 'images'){
            PrivateEventSettingImage::where('event_setting_id',$id)->delete();
            if (isset($input['images']) && count($input['images'])) {
                foreach ($input['images'] as $key => $programmeValue) {
                    $event_main_image = $event_thumb_image = '';

                    if (isset($programmeValue) && !empty($programmeValue)) {
                        $images = $this->uploadSingleImage($programmeValue,'event','');
                        if ($images != "") {
                            $frontImage = basename($images);
                            $filenametostore    = "event/thumb_".$frontImage;
                            
                            $event_main_image = $images;
                        }
                    }
                    PrivateEventSettingImage::insert(['event_setting_id'=>$id,'main_image'=>$event_main_image,'thumb_image'=>$event_thumb_image]);
                }
            }
            if (isset($input['old_images']) && count($input['old_images'])) {
                foreach ($input['old_images'] as $key => $programmeValue) {
                    $old_event_main_image = $programmeValue;
                    $old_event_thumb_image = '';
                    PrivateEventSettingImage::insert(['event_setting_id'=>$id,'main_image'=>$old_event_main_image,'thumb_image'=>$old_event_thumb_image]);
                }
            }
            $event = PrivateEventSetting::where('id',$id)->first();
            $main_image = $thumb_image = '';
            if (isset($input['main_image']) && !empty($input['main_image'])) {
                $image = $this->uploadSingleImage($input['main_image'],'event','');
                if ($image != "") {
                    $frontImage = basename($image);
                    $filenametostore    = "event/thumb_".$frontImage;
                    $main_image = $image;
                }
            }else{
                $main_image = $event->main_image;
                $thumb_image = $event->thumb_image;
            }
            // dd($main_image);
            $event->update(['main_image'=>$main_image,'thumb_image'=>$thumb_image]);

            return redirect()->back()->with('success' ,'Private Event images update successfully.');
        }
        if (!empty($eventData) && is_array($eventData)==true) {
            PrivateEventSetting::where('id',$id)->update($eventData);     
        }
        return redirect()->route('admin.private-event-setting.index')->with('success' ,'Private Event update successfully.');
    }
    
    public function destroy($id)
    {
        PrivateEventSetting::find($id)->delete();
        return redirect()->route('admin.private-event-setting.index')->with('success', 'Private deleted successfully');
    }

    public function getYearProgramme(Request $request){
        $year_id = $request->id;
        if (isset($year_id) && !empty($year_id)) {
            $yearselectid =ProgrammeHallSetting::where('hall_setting_id',$year_id)->pluck('programme_id')->toArray();
            $datavalues = Programme::select('id','programme_name','programme_code')->whereIn('id',$yearselectid)->orderBy('id','DESC')->get();
            return response()->json($datavalues);
        }
    }
}

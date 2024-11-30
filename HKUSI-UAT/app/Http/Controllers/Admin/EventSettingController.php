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
use App\Models\Programme;
use App\Models\EventSetting;
use App\Models\EventSettingImage;
use App\Models\EventProgramme;
use App\Models\Category;
use App\Models\Language;
use App\Models\EventPayment;
use App\Models\EventBooking;
use App\Models\ProgrammeHallSetting;
use App\Models\HallSetting;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmailJob;
use App\Traits\UploadTraits;
use Auth, Storage, Config;

class EventSettingController extends Controller
{
    use UploadTraits;
    //
    function __construct()
    {
        $this->middleware('permission:event-list|event-create|event-edit|event-delete', ['only' => ['index','store']]);
        $this->middleware('permission:event-create', ['only' => ['create','store']]);
        $this->middleware('permission:event-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:event-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "Event Setting";
        return view('admin.event-setting.index',compact('headerTitle'));
    }    

    public function create(){
        $headerTitle = "Event Create";
        $programme = Programme::orderBy('programme_name','ASC')->get();
        $language = Language::where('status',1)->get();
        $category = Category::where('status',1)->get();
        $HallSetting = HallSetting::where('status',1)->get();
        return view('admin.event-setting.create',compact('headerTitle','programme','language','category','HallSetting'));
    }
    
    public function eventSettingDetails(Request $request , $id ,$type){ 
        $dataId = $id;        
        $dataType = $type;
        $eventImages = $eventInfo = $programme = $language = $category = $eventprograme=$HallSetting=[];
        $eventInfo = EventSetting::find($id);
        if ($dataType=="show") {
            $headerTitle = "Event Details";
        }elseif($dataType=="edit"){
            $language = Language::where('status',1)->get();
            $category = Category::where('status',1)->get();
            $HallSetting = HallSetting::where('status',1)->get();
            $findProgram=EventProgramme::where('event_id', '=', $id)->get();
            $eventprograme = EventProgramme::where('event_id',$id)->distinct()->pluck('program_id')->toArray();
            if(count($eventprograme)){
                $programme = Programme::whereIn('id',$eventprograme)->get();
            }
            $programme = Programme::get();
            $headerTitle = "Event Details";
        }elseif($dataType=="images"){
            $headerTitle = "Event Details";
            $eventImages = EventSettingImage::where('event_setting_id',$eventInfo->id)->get();
        }elseif($dataType=="programme"){
              $headerTitle = "Event Programme  Details";
        }elseif($dataType=="editimage"){
            $headerTitle = "Event Details";
            $eventImages = EventSettingImage::where('event_setting_id',$eventInfo->id)->get();
        }else{
            return redirect()->route('admin.event-setting.index');
        }
        if (!empty($eventInfo)) {
            return view('admin.event-setting.comman',compact('headerTitle','eventInfo','programme','dataId','dataType','eventImages','language','category','eventprograme','HallSetting'));          
        }else{
            return redirect()->route('admin.event-setting.index');
        }        
    }


    public function store(Request $request){
        $input = $request->all();
        //dd($input);
         $this->validate($request, [
           'event_name'                     => 'required',
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
        $eventData                           =  new EventSetting();
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
                EventProgramme::insert(['event_id'=>$last_id,'program_id'=>$input['programme_id'][$i]]);
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
                    EventSettingImage::insert(['event_setting_id'=>$eventData->id,'main_image'=>$event_main_image,'thumb_image'=>$event_thumb_image]);
                }
            }
        }
        return redirect()->route('admin.event-setting.index')->with('message', 'Event create successfully.');

    }




    public function multipleEventDelete(Request $request)
	{        
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $hallbooking) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Delete') {
                    $eventBooking = EventBooking::leftJoin('event_payments', function ($join){
                        $join->on('event_payments.payment_id', '=', 'event_bookings.payment_id');
                    })->where('event_bookings.event_id',$hallbooking)->whereIn('event_bookings.booking_status',['Paid','Pending','Updated'])->where('event_payments.service_type','Event Booking')->get();
                    if(count($eventBooking)){
                        foreach ($eventBooking as $key => $value) {
                            EventBooking::where('payment_id',$value->payment_id)->where('event_id',$value->event_id)->delete();
                        }
                    }
                    EventSetting::where('id', $hallbooking)->delete();
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Cancelled'){
                    $hallbookingRecord = EventSetting::where('id',$hallbooking)->first();
                        $eventBooking = EventBooking::leftJoin('event_payments', function ($join){
                            $join->on('event_payments.payment_id', '=', 'event_bookings.payment_id');
                        })->where('event_bookings.event_id',$hallbookingRecord->id)->whereIn('event_bookings.booking_status',['Paid','Pending','Updated'])->where('event_payments.service_type','Event Booking')->get();
                        if(count($eventBooking)){
                            foreach ($eventBooking as $key => $value) {
                                $eventData = EventSetting::where('id',$value->event_id)->first();
                                    if(!empty($eventData)){
                                            $eventData->increment('quota_balance',$value->no_of_seats);
                                    }
                                  EventBooking::where('payment_id',$value->payment_id)->where('event_id',$value->event_id)->update(['booking_status'=>'Cancelled']);
                                $memberDetail = MemberInfo::where('application_number',$value->application_id)->where('status',1)->first();
                                if(!empty($memberDetail)){
                                    $mailInfo = [
                                        'given_name'            => $memberDetail->given_name,
                                        'application_number'    => $memberDetail->application_number,
                                        'event_name'            => $hallbookingRecord->event_name,
                                        'date'                  => $hallbookingRecord->date,
                                    ];
                                    $CancelledEmail = ['type'=>'EventCancelled','email' =>$memberDetail->getUserDetail->email,'mailInfo' => $mailInfo];

                                    SendEmailJob::dispatch($CancelledEmail);
                                    \Log::info($memberDetail->given_name.' / '.$memberDetail->application_number.' Event Cancelled Event Mail');
                                }
                            }
                        }
                    $hallbookingRecord = EventSetting::where('id',$hallbooking)->first();
                    $hallbookingRecord->update(['status'=>'Cancelled']);
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Disabled'){
                    $hallbookingRecord = EventSetting::where('id',$hallbooking)->first();
                    $eventBooking = EventBooking::leftJoin('event_payments', function ($join){
                        $join->on('event_payments.payment_id', '=', 'event_bookings.payment_id');
                        })->where('event_bookings.event_id',$hallbookingRecord->id)->whereIn('event_bookings.booking_status',['Paid','Updated'])->where('event_payments.service_type','Event Booking')->get();
                       // dd($eventBooking);
                        if(count($eventBooking)){
                            foreach ($eventBooking as $key => $value) {
                                EventBooking::where('payment_id',$value->payment_id)->where('event_id',$value->event_id)->update(['booking_status'=>'Updated']);
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
                                        'assembly_start_time'   => $hallbookingRecord->assembly_start_time,
										'assembly_end_time'     => $hallbookingRecord->assembly_end_time,
                                    ];
                                    $CancelledEmail = ['type'=>'EventInformationUpdate','email' =>$memberDetail->getUserDetail->email,'mailInfo' => $mailInfo];
                                   // dd($CancelledEmail);
                                    SendEmailJob::dispatch($CancelledEmail);
                                    \Log::info($memberDetail->given_name.' / '.$memberDetail->application_number.' Event Cancelled Event Mail');
                                }
                            }
                        }
                    $hallbookingRecord->update(['status'=>'Disabled']);
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Enabled'){
                    EventSetting::where('id',$hallbooking)->update(['status'=>'Enabled']);
                }else{
                     $hallbookingRecord = EventSetting::where('id',$hallbooking)->first();
                        $eventBooking = EventBooking::leftJoin('event_payments', function ($join){
                        $join->on('event_payments.payment_id', '=', 'event_bookings.payment_id');
                        })->where('event_bookings.event_id',$hallbookingRecord->id)->whereIn('event_bookings.booking_status',['Paid','Updated'])->where('event_payments.service_type','Event Booking')->get();
                        if(count($eventBooking)){
                            foreach ($eventBooking as $key => $value) {
                                if($value->booking_status=='Paid' ){
                                    EventBooking::where('payment_id',$value->payment_id)->where('event_id',$value->event_id)->update(['booking_status'=>'Updated']);
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
                                        'assembly_time'   		=> @$hallbookingRecord->assembly_time,
                                        'assembly_start_time'   => $hallbookingRecord->assembly_start_time,
										'assembly_end_time'     => $hallbookingRecord->assembly_end_time,
                                    ];
                                    $CancelledEmail = ['type'=>'EventInformationUpdate','email' =>$memberDetail->getUserDetail->email,'mailInfo' => $mailInfo];
                                   // dd($CancelledEmail);
                                    SendEmailJob::dispatch($CancelledEmail);
                                    \Log::info($memberDetail->given_name.' / '.$memberDetail->application_number.' Event Cancelled Event Mail');
                                }
                            }
                        }
                    EventSetting::where('id',$hallbooking)->update(['status'=>'Enabled']);
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
            $events = EventSetting::where('id',$id)->first();
            if ($input['status']=="Cancelled") {
                $eventBooking = EventBooking::leftJoin('event_payments', function ($join){
                        $join->on('event_payments.payment_id', '=', 'event_bookings.payment_id');
                    })->where('event_bookings.event_id',$events->id)->where('event_payments.service_type','Event Booking')->whereIn('event_bookings.booking_status',['Paid','Pending','Updated'])->get();
                    if(count($eventBooking)){
                        foreach ($eventBooking as $key => $value) {
                            $eventData = EventSetting::where('id',$value->event_id)->first();
                            if(!empty($eventData)){
                                    $eventData->increment('quota_balance',$value->no_of_seats);
                            }
                            EventBooking::where('payment_id',$value->payment_id)->where('event_id',$value->event_id)->update(['booking_status'=>'Cancelled']);
                            $memberDetail = MemberInfo::where('application_number',$value->application_id)->where('status',1)->first();
                            if(!empty($memberDetail)){
                                $mailInfo = [
                                    'given_name'            => $memberDetail->given_name,
                                    'application_number'    => $memberDetail->application_number,
                                    'event_name'            => $events->event_name,
                                    'date'                  => $events->date,
                                ];
                                $CancelledEmail = ['type'=>'EventCancelled','email' =>$memberDetail->getUserDetail->email,'mailInfo' => $mailInfo];
                                SendEmailJob::dispatch($CancelledEmail);
                                \Log::info($memberDetail->given_name.' / '.$memberDetail->application_number.' Event Cancelled Event Mail');
                            }
                            $value->update(['status'=>'Cancelled']);
                        }
                    }
            }else{
                $eventBooking = EventBooking::leftJoin('event_payments', function ($join){
                    $join->on('event_payments.payment_id', '=', 'event_bookings.payment_id');
                })->where('event_bookings.event_id',$events->id)->whereIn('event_bookings.booking_status',['Paid','Updated'])->where('event_payments.service_type','Event Booking')->get();
                if(count($eventBooking)){
                    foreach ($eventBooking as $key => $value) {
                        if($value->booking_status=='Paid'){
                            EventBooking::where('payment_id',$value->payment_id)->where('event_id',$value->event_id)->update(['booking_status'=>'Updated']);
                        }    
                        $oldevent_name      = $events->event_name;       
                        $oldlocation        = $events->location;       
                        $oldDescription     = $events->description;       
                        $oldadditional_info = $events->additional_info;       
                        $oldterms_condition = $events->terms_condition;       
                        $oldterms_link      = $events->terms_link;       
                        $oldpre_arrival     = $events->pre_arrival;       
                        $oldpre_link        = $events->pre_link;       
                        $oldpre_link        = $events->pre_link;       
                        $oldunit_price      = $events->unit_price;       
                        $oldapplication_deadline = $events->application_deadline;       
                        $olddate            = $events->date;       
                        $oldstart_time      = $events->start_time;       
                        $oldend_time        = $events->end_time;       
                        $oldassembly_location= $events->assembly_location;     
						$oldassembly_start_time    = $events->assembly_start_time;
						$oldassembly_end_time    = $events->assembly_end_time;
                        $oldbooking_limit    = $events->booking_limit;       
                        if($oldevent_name!=$request->event_name || $oldlocation!=$request->location || $oldDescription!=$request->description || $oldadditional_info!=$request->additional_info || $oldterms_condition!=$request->terms_condition || $oldterms_link!=$request->terms_link || $oldpre_arrival!=$request->pre_arrival || $oldpre_link!=$request->pre_link || $oldunit_price!=$request->unit_price || $oldapplication_deadline!= strtotime($request->application_deadline.'23:59:59') || $olddate!= strtotime($request->date.'00:00:00') || $oldstart_time!= strtotime($request->start_time) || $oldend_time!= strtotime($request->end_time) || $oldassembly_location!=$request->assembly_location || $oldassembly_start_time!=strtotime($request->assembly_start_time) || $oldassembly_end_time!=strtotime($request->assembly_end_time) || $oldbooking_limit !=$request->booking_limit){
                            $memberDetail = MemberInfo::where('application_number',$value->application_id)->where('status',1)->first();
                            if(!empty($memberDetail)){
                                $mailInfo = [
                                    'given_name'            => $memberDetail->given_name,
                                    'application_number'    => $memberDetail->application_number,
                                    'event_name'            => $request->event_name,
                                    'date'                  => strtotime($request->date.'00:00:00'),
                                    'start_time'            => strtotime($request->start_time),
                                    'end_time'              => strtotime($request->end_time),
                                    'location'              => $request->location,
                                    'unit_price'            => $request->unit_price,
                                    'assembly_location'     => $request->assembly_location,
									'assembly_start_time'   => strtotime($request->assembly_start_time),
									'assembly_end_time'   	=> strtotime($request->assembly_end_time),
                                ];
                                $infoupdated = ['type'=>'EventInformationUpdate','email' =>$memberDetail->getUserDetail->email,'mailInfo' => $mailInfo]; 
                                SendEmailJob::dispatch($infoupdated);
                                \Log::info($memberDetail->given_name.' / '.$memberDetail->application_number.' Event Updated Event Mail');
                            }
                        }                           
                    }
                }
            }
            EventProgramme::where('event_id', '=', $id)->delete();
            if (isset($input['programme_id']) && !empty($input['programme_id'])) {
                foreach ($input['programme_id'] as $key => $programval) {
                   EventProgramme::insert(['event_id'=>$id,'program_id'=>$programval]);
                }
            }
        }elseif(isset($input['submit_type']) && !empty($input['submit_type']) && $input['submit_type'] == 'images'){
            EventSettingImage::where('event_setting_id',$id)->delete();
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
                    EventSettingImage::insert(['event_setting_id'=>$id,'main_image'=>$event_main_image,'thumb_image'=>$event_thumb_image]);
                }
            }
            if (isset($input['old_images']) && count($input['old_images'])) {
                foreach ($input['old_images'] as $key => $programmeValue) {
                    $old_event_main_image = $programmeValue;
                    $old_event_thumb_image = '';
                    EventSettingImage::insert(['event_setting_id'=>$id,'main_image'=>$old_event_main_image,'thumb_image'=>$old_event_thumb_image]);
                }
            }
            $event = EventSetting::where('id',$id)->first();
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
            }else{
                $main_image = $event->main_image;
                $thumb_image = $event->thumb_image;
            }
            // dd($main_image);
            $event->update(['main_image'=>$main_image,'thumb_image'=>$thumb_image]);

            return redirect()->back()->with('success' ,'Event images update successfully.');
        }
        if (!empty($eventData) && is_array($eventData)==true) {
			EventSetting::where('id',$id)->update($eventData);     
        }
        return redirect()->route('admin.event-setting.index')->with('success' ,'Event update successfully.');
    }
    
    public function destroy($id)
    {
        EventSetting::find($id)->delete();
        return redirect()->route('admin.event-setting.index')->with('success', 'Hall Booking Info deleted successfully');
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

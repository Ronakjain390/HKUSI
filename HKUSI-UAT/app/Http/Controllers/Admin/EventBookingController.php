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
use App\Models\HallBookingInfo;
use App\Models\EventBooking;
use App\Models\EventPayment;
use App\Models\EventSetting;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmailJob;
use App\Traits\UploadTraits;
use Auth;

class EventBookingController extends Controller
{
    use UploadTraits;
    //
    function __construct()
    {
        $this->middleware('permission:eventbooking-list|eventbooking-create|eventbooking-edit|eventbooking-delete', ['only' => ['index','store']]);
        $this->middleware('permission:eventbooking-create', ['only' => ['create','store']]);
        $this->middleware('permission:eventbooking-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:eventbooking-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "Event Booking";
        return view('admin.eventbooking.index',compact('headerTitle'));
    }    

    public function create(){
        $headerTitle = "Event Booking Create";
        return view('admin.eventbooking.create',compact('headerTitle'));
    }
    
    public function eventBookingDetail(Request $request , $id ,$type){
        $dataId = $id;        
        $dataType = $type;
        $paymnet_id = '';
        if ($dataType=="show") {
            $headerTitle = "Event Booking Details";
        }elseif($dataType=="edit"){
            $headerTitle = "Event Booking Details";
        }elseif($dataType=="create"){
            $headerTitle = "Add Event Booking";
        }elseif($dataType=="payment"){
            $headerTitle = "Event Booking Payment";
        }else{
            return redirect()->route('admin.eventbooking.index');
        }
        $getbookings = EventBooking::select('payment_id')->pluck('payment_id')->toArray();
        $eventbookingInfo = EventPayment::whereIn('payment_id',$getbookings)->where('id',$id)->first();
        $users = User::role('Super Admin')->get();
        if (!empty($eventbookingInfo)) {
            $memberinfo = MemberInfo::where('application_number',$eventbookingInfo->application_id)->first();
            return view('admin.eventbooking.comman',compact('headerTitle','eventbookingInfo','dataId','dataType','memberinfo','paymnet_id','users'));          
        }else{
            return redirect()->route('admin.eventbooking.index');
        }        
    }

   
    public function destroy($id){
        $eventbookings =  EventPayment::where('id',$id)->first();
            if(isset($eventbookings->getEventBookingDetails) && count($eventbookings->getEventBookingDetails)){
                foreach ($eventbookings->getEventBookingDetails as $keyEvent => $valueEvent) {
                    if($valueEvent->booking_status=='Paid'){
                        $eventBooking = EventBooking::where('id',$valueEvent->id)->first();
                        if(!empty($eventBooking)){
                            $eventData = EventSetting::find($eventBooking->event_id);
                            if(!empty($eventData)){
                                $eventData->increment('quota_balance',$eventBooking->no_of_seats);
                                $eventBooking->delete();
                            }
                        }
                    }else{
                        $eventBooking = EventBooking::where('id',$valueEvent->id)->delete();
                    }
                }
            }
        return redirect()->route('admin.eventbooking.index')->with('success', 'Event Booking Info deleted successfully');
    }

    public function eventbookingstatuschange(Request $request, $id) {  
        return redirect()->route('admin.eventbooking',[$id,'edit'])->with('success', 'Hall Booking status updated successfully!');  
    }

    public function update(Request $request, $id){
        $input = $request->all();
        foreach ($request->id as $key => $value) {
            $eventBooking = EventBooking::find($request->id[$key]);
            $eventName = EventSetting::where('id',$eventBooking->event_id)->first();
            if(!empty($eventBooking)){
                $oldStatus = $eventBooking->booking_status;
                $data = array(                 
                  'booking_status'=> ucfirst(strtolower($request->status[$key])),        
                  'check_in_date'=>strtotime($request->check_in_date[$key]),        
                  'check_in_time'=>strtotime($request->check_in_time[$key]),        
                  'check_operater'=>$request->check_operater[$key],        
                ); 
                $eventBooking->update($data);
                $memberinfo = MemberInfo::where('application_number',$eventBooking->application_id)->first();
                if($oldStatus!=$request->status[$key] && ($oldStatus=='Cancelled' || $oldStatus=='Pending')){
                        if(!empty($memberinfo)){      
                        \Log::info('EventPaymentSuccessfull');                      
                        $mailInfo = [
                            'given_name'            => $memberinfo->given_name,
                            'application_number'    => $memberinfo->application_number,
                            'event_details'         => $eventName,
                        ];
                        $paymentsuccess = ['type'=>'EventPaymentSuccessfull','email' =>$memberinfo->email_address,'mailInfo' => $mailInfo];
                        SendEmailJob::dispatch($paymentsuccess);
                         \Log::info('EventPaymentSuccessfull'.'-'.$memberinfo->email_address);
                    }
                    if($oldStatus!=$request->status[$key] && $oldStatus=='Cancelled'){
                        $eventData = EventSetting::find($eventBooking->event_id);
                        if(!empty($eventData)){
                            $eventData->decrement('quota_balance',$eventBooking->no_of_seats);
                        }
                    }
                }elseif($request->status[$key]=="Cancelled" && $oldStatus!=$request->status[$key]){
                    $eventData = EventSetting::find($eventBooking->event_id);
                    if(!empty($eventData)){
                            $eventData->increment('quota_balance',$eventBooking->no_of_seats);
                    }
                }
                EventPayment::where('payment_id',$eventBooking->payment_id)->update(['event_payment_status'=>$input['event_payment_status']]); 
            }
        }
        
        return redirect()->route('admin.eventbooking.index')->with('success' ,'Hall booking update successfully.');
    }

    public function multipleEventsBookings(Request $request){
        $input = $request->all();
        //dd($input);
         if (isset($input['id']) && count($input['id'])) {
             foreach ($input['id'] as $eventbooking) {
                $payments = EventPayment::where('id',$eventbooking)->first();
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Paid') {
                     // if(isset($payments->getEventBookingDetails) && count($payments->getEventBookingDetails)){
                     //    foreach ($payments->getEventBookingDetails as $keyEvent => $valueEvent) {
                     //        $memberinfo = MemberInfo::where('application_number',$valueEvent->application_id)->first();
                     //            $mailInfo = [
                     //                'given_name'            => $memberinfo->given_name,
                     //                'application_number'    => $memberinfo->application_number,
                     //                'event_details'         => $valueEvent->getEventSetting,
                     //            ];
                     //            $paymentsuccess = ['type'=>'EventPaymentSuccessfull','email' =>$memberinfo->email_address,'mailInfo' => $mailInfo];
                     //            SendEmailJob::dispatch($paymentsuccess);
                     //        }    
                        /*if(isset($payments->getEventBookingDetails) && count($payments->getEventBookingDetails)){
                            foreach ($payments->getEventBookingDetails as $keyEvent => $valueEvent) {                     
                                $eventBooking = EventBooking::find($valueEvent->id);
                                if(!empty($eventBooking)){
                                    $eventBooking->update(['booking_status'=>'Paid']);
                                }
                            }
                        }    */
                    //}
                    $payments->update(['event_payment_status'=>'Paid']);
                }elseif (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Cancelled') {
                    // if(isset($payments->getEventBookingDetails) && count($payments->getEventBookingDetails)){
                    //     foreach ($payments->getEventBookingDetails as $keyEvent => $valueEvent) {
                    //         $memberinfo = MemberInfo::where('application_number',$valueEvent->application_id)->first();
                    //         $mailInfo = [
                    //             'given_name'            => $memberinfo->given_name,
                    //             'application_number'    => $memberinfo->application_number,
                    //             'event_name'            => $valueEvent->getEventSetting->event_name,
                    //             'date'                  => $valueEvent->getEventSetting->date,
                    //         ];
                    //         $eventcancelled = ['type'=>'EventCancelled','email' =>$memberinfo->email_address,'mailInfo' => $mailInfo];
                    //         SendEmailJob::dispatch($eventcancelled);
                    //     }
                    // }  
                    /*if(isset($payments->getEventBookingDetails) && count($payments->getEventBookingDetails)){
                        foreach ($payments->getEventBookingDetails as $keyEvent => $valueEvent) {                     
                            $eventBooking = EventBooking::find($valueEvent->id);
                            if(!empty($eventBooking)){
                                $eventBooking->update(['booking_status'=>'Cancelled']);
                            }
                        }
                    }*/ 
                    $payments->update(['event_payment_status'=>'Cancelled']);
                }elseif (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete') {
                        if(isset($payments->getEventBookingDetails) && count($payments->getEventBookingDetails)){
                            foreach ($payments->getEventBookingDetails as $keyEvent => $valueEvent) {
                                if($valueEvent->booking_status=='Paid'){
                                    $eventBooking = EventBooking::where('id',$valueEvent->id)->first();
                                    if(!empty($eventBooking)){
                                        $eventData = EventSetting::find($eventBooking->event_id);
                                        if(!empty($eventData)){
                                            $eventData->increment('quota_balance',$eventBooking->no_of_seats);
                                            $eventBooking->delete();
                                        }
                                    }
                                }else{
                                    $eventBooking = EventBooking::where('id',$valueEvent->id)->delete();
                                }
                            }
                        }
                      // if(isset($payments->getEventBookingDetails) && count($payments->getEventBookingDetails)){
                      //       foreach ($payments->getEventBookingDetails as $keyEvent => $valueEvent) {                     
                      //           $eventBooking = EventBooking::find($valueEvent->id);
                      //           if(!empty($eventBooking)){
                      //               $eventBooking->delete();
                      //           }
                      //       }
                      //   }
                      //  EventPayment::where('id',$eventbooking)->delete();
                }else{
                    if(isset($payments->getEventBookingDetails) && count($payments->getEventBookingDetails)){
                        foreach ($payments->getEventBookingDetails as $keyEvent => $valueEvent) {                     
                            $eventBooking = EventBooking::find($valueEvent->id);
                            if(!empty($eventBooking)){
                                $eventBooking->update(['booking_status'=>'Pending']);
                            }
                        }
                    }
                    $payments->update(['event_payment_status'=>'Pending']);
                }
            }
        }
        return redirect()->back();
    }

}

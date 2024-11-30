<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MemberInfo;
use App\Models\User;
use App\Models\PrivateEventOrder;
use App\Models\PrivateEventSetting;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmailJob;
use App\Traits\UploadTraits;
use Auth, Storage, Config;

class PrivateEventController extends Controller
{
    use UploadTraits;
    // All Methods created by Akash
    function __construct()
    {
        $this->middleware('permission:event-list|event-create|event-edit|event-delete', ['only' => ['index','store']]);
        $this->middleware('permission:event-create', ['only' => ['create','store']]);
        $this->middleware('permission:event-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:event-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $headerTitle = "Private Event Booking";
        return view('admin.private-event-order.index',compact('headerTitle'));
    }    

    public function create(){
        $headerTitle = "Private Event Booking Create";
        return view('admin.private-event-order.create',compact('headerTitle'));
    }
    
    public function privateEventOrderDetail(Request $request , $id ,$type){ 
        $dataId = $id;        
        $dataType = $type;

        $HallSetting = $memberInfo =[];
        $bookingInfo = PrivateEventOrder::where('id', $id)->first();
        $eventInfo = $bookingInfo->getEventDetails;
        if ($dataType=="show") {
            $headerTitle = "Private Event Booking Details";
        }elseif($dataType=="edit"){
            $memberInfo = User::select('id', 'name', 'email', 'given_name')->role(['Super Admin'])->get();
            $headerTitle = "Private Event Booking Edit";

        }else{
            return redirect()->route('admin.private-event-order.index');
        }
        if (!empty($eventInfo)) {
            return view('admin.private-event-order.comman',compact('headerTitle','eventInfo','bookingInfo','dataId','dataType', 'memberInfo'));          
        }else{
            return redirect()->route('admin.private-event-order.index');
        }        
    }


    public function store(Request $request){

    }




    public function multiplePrivateEventOrderDelete(Request $request)
    {        
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $privateEvent) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Delete') {
                    
                    PrivateEventOrder::where('id', $privateEvent)->delete();

                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Cancelled'){
                    $privateEventRecord = PrivateEventOrder::where('id',$privateEvent)->first();
                    $privateEventRecord->booking_status = 'Cancelled';
                    $privateEventRecord->save();

                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Pending'){
                    $privateEventRecord = PrivateEventOrder::where('id',$privateEvent)->first();
                    $privateEventRecord->booking_status = 'Pending';
                    $privateEventRecord->save();

                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Paid'){
                    $privateEventRecord = PrivateEventOrder::where('id',$privateEvent)->first();
                    $privateEventRecord->booking_status = 'Paid';
                    $privateEventRecord->save();

                }
            }
        }
        return redirect()->back();
    }

    public function update(Request $request, $id){
        $input = $request->all();
       
        if (isset($input['submit_type']) && !empty($input['submit_type']) && $input['submit_type'] == 'basic') {
            $eventData                           =  [];
           
            $eventData['booking_status']         =  $input['booking_status'];
            $eventData['event_group']         =  $input['event_group'];
            $eventData['event_status']         =  $input['event_status'];
            $eventData['check_in_date']          =  strtotime($input['check_in_date']. '00:00:00');
            $eventData['check_in_time']          =  strtotime($input['check_in_time']);
            $eventData['check_operator']         =  $input['check_operator'];

           
        }
        if (!empty($eventData) && is_array($eventData)==true) {
            PrivateEventOrder::where('id',$id)->update($eventData);     
        }
        return redirect()->route('admin.private-event-order.index')->with('success' ,'Private Event Booking update successfully.');
    }
    
    public function destroy($id)
    {
        PrivateEventOrder::find($id)->delete();
        return redirect()->route('admin.private-event-order.index')->with('success', 'Private Event Booking deleted successfully');
    }

    
}

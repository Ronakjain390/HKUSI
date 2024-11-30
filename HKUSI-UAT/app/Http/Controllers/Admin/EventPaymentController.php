<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\MemberInfo;
use App\Models\ImageBank;
use App\Models\Payment;
use App\Models\EventPayment;
use App\Models\EventSetting;
use App\Models\Eventbooking;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmailJob;
use App\Traits\UploadTraits;
use Auth;

class EventPaymentController extends Controller
{
    use UploadTraits;
    //
    function __construct()
    {
        $this->middleware('permission:eventpayment-list|eventpayment-create|eventpayment-edit|eventpayment-delete', ['only' => ['index','store']]);
        $this->middleware('permission:eventpayment-create', ['only' => ['create','store']]);
        $this->middleware('permission:eventpayment-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:eventpayment-delete', ['only' => ['destroy']]);
    }


    public function index(Request $request)
    {
        $headerTitle = "Event Payment";
        return view('admin.event-payment.index',compact('headerTitle'));
    }    
    public function eventpaymentDetails(Request $request , $id ,$type){
        $dataId = $id;
        $dataType = $type;
        if ($dataType=="show") {
            $headerTitle = "Event Payment Details";
        }elseif($dataType=="edit"){
            $headerTitle = "Event Payment Details";
        }else{
            return redirect()->route('admin.eventpayment.index');
        }
        $PaymentInfo = EventPayment::find($id);
        if (!empty($PaymentInfo)) {            
            return view('admin.event-payment.comman',compact('headerTitle','PaymentInfo','dataId','dataType'));          
        }else{
            return redirect()->route('admin.eventpayment.index');
        }
        return redirect()->route('admin.eventpayment.index');
        
    }

    public function multipleEventpaymentdelete(Request $request)
    {
        $input = $request->all();
        if (isset($input['id']) && count($input['id'])) {
            foreach ($input['id'] as $paymentdata) {
                if (isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'delete') {
                    EventPayment::where('id', $paymentdata)->delete();
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Confirmed'){
                     EventPayment::where('id', $paymentdata)->update(['status'=>1]);
                }elseif(isset($input['select_type']) && !empty($input['select_type']) && $input['select_type'] == 'Cancelled'){
                    EventPayment::where('id',$paymentdata)->update(['status'=>0]);
                }else{
                    EventPayment::where('id',$paymentdata)->update(['status'=>1]);
                }
            }
        }
        return redirect()->back();
    }

    public function update(Request $request, $id){
        $input = $request->all();
        $paymentdata = EventPayment::where('id',$id)->where('service_type','Event Booking')->first();
        if (!empty($paymentdata)) {
            EventPayment::where('id',$id)->update(['payment_status' => $input['payment_status']]);
        }
        return redirect()->back()->with('Event  Booking Info update successfully');
    }
    
    public function destroy($id)
    {
        Payment::where('id',$id)->delete();
        return redirect()->route('admin.eventpayment.index')->with('success', 'Hall Booking Info deleted successfully');
    }

}

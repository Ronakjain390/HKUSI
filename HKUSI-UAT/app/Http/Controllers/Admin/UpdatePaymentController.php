<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HallBookingInfo;
use App\Models\Payment;
use App\Jobs\SendEmailJob;

class UpdatePaymentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:payment-list|payment-create|payment-edit|payment-delete', ['only' => ['index','store']]);
        $this->middleware('permission:payment-create', ['only' => ['create','store']]);
        $this->middleware('permission:payment-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:payment-delete', ['only' => ['destroy']]);
    }

    /*public function updatePaymentRecords(){
        $paymentArr = [""];
        //print_r($paymentArr);exit;
        if(count($paymentArr)){
            foreach ($paymentArr as $key => $value) {
                $paymentRecords = Payment::where('order_no',$value)->first();
                if(empty($paymentRecords)){
                    $arra = explode("-", $value);
                    if(isset($arra[0]) && !empty($arra)){
                        $hallBooking = HallBookingInfo::where('booking_number',$arra[0])->first();
                        if(!empty($hallBooking)){
                            Payment::create([
                                'application_id'        => $hallBooking->application_id,
                                'payment_id'            => $arra[0],
                                'amount'                => $hallBooking->amount,
                                'order_no'              => $value,
                                'service_type'          => 'Hall Booking',
                                'payment_status'        => 'EXPIRED',
                                'status'                => 0,
                            ]);
                        }
                    }
                }
            }
        }
    }*/



    /*public function updatePaymentRecords(){
        $paymentArr = [""];
        //print_r($paymentArr);exit;
        if(count($paymentArr)){
            foreach ($paymentArr as $key => $value) {
                $paymentRecords = Payment::where('order_no',$value)->first();
                if(!empty($paymentRecords)){
                    $arra = explode("-", $value);
                    if(isset($arra[0]) && !empty($arra)){
                        $hallBooking = HallBookingInfo::where('booking_number',$arra[0])->where('status','Accepted')->first();
                        if(!empty($hallBooking)){
                            //$newDate = strtotime('2023-05-02 00:01:00');
                            HallBookingInfo::where('id',$hallBooking->id)->update([
                                'created_at'        => '2023-05-02 00:01:00'
                            ]);
                        }
                    }
                }
            }
        }
    }*/



    /*public function updatePaymentDateTime(){

        $dateTimeArr = [""];

        $paymentArr = [""];
        //print_r($paymentArr);exit;
        if(count($paymentArr)){
            foreach ($paymentArr as $key => $value) {
                $paymentRecords = Payment::where('order_no',$value)->first();
                if(!empty($paymentRecords)){
                    Payment::where('id',$paymentRecords->id)->update([
                        'pay_time'        => strtotime($dateTimeArr[$key])
                    ]);
                }
            }
        }
    }*/


    public function updatePaymentDeadlineDate()
    {
        $hallBooking = HallBookingInfo::where('status','Accepted')->WhereNull('payment_deadline_date')->get();
		if(count($hallBooking)){
            foreach ($hallBooking as $key => $value) {
                $updateTime = strtotime($value->updated_at);
                $value->update(['payment_deadline_date'=>$updateTime]);
            }
        }
		echo "Done"; die; 
		/*
        $hallBooking = HallBookingInfo::where('status','Pending')->get();
        if(count($hallBooking)){
            foreach ($hallBooking as $key => $value) {
                $updateTime = strtotime($value->updated_at);
                $value->update(['hall_result_date'=>$updateTime]);
            }
        }
		*/
        // code...
    }





}


<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EventSetting;
use App\Models\MemberInfo;
use App\Models\EventBooking;
use App\Models\EventPayment;
use App\Jobs\SendEmailJob;

class BookingCancelled extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:cancelled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Event booking canceled mail send';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            \Log::info('booking cancelled');
            $startDate = date("Y-m-d H:i:s",strtotime('-45 minutes'));
            $endDate = date("Y-m-d H:i:s",strtotime('-46 minutes'));            
            $paymentsData = EventPayment::where('service_type','Event Booking')->whereIn('payment_status',['Pending','PROCESSING'])->where('created_at','<=',$startDate)->get();
            if(count($paymentsData)){
                foreach ($paymentsData as $payments) {
                    $eventBookings = EventBooking::where('payment_id',$payments->payment_id)->get();
                    if(count($eventBookings)){
                        foreach($eventBookings as $bookingevent){
                            if(!empty($bookingevent->unit_price)){
                                $bookingevent->update(['booking_status'=>'Cancelled']);
                                $payments->update(['payment_status'=>'CANCELLED','event_payment_status'=>'Cancelled']);
                            }
                            $eventData = EventSetting::find($bookingevent->event_id);
                            if(!empty($eventData)){
                                $eventData->increment('quota_balance',$bookingevent->no_of_seats);
                            }
                        }
                    }
                }
            }

            /* Update payment Status */

            $paymentsNewData = EventPayment::where('service_type','Event Booking')->whereNotIn('payment_status',['PAID','EXPIRED','CANCELLED','UATPAID','REFUNDED'])->where('status',0)->whereNotNull('order_no')->orderBy('id','DESC')->get();
            if (isset($paymentsNewData) && count($paymentsNewData)) {
                foreach ($paymentsNewData as $key => $value) {
                    $orderNumber    = $value->order_no;
					/* local 
                    $md5string      = '{"deptCode":"60380","orderNo":"'.$orderNumber.'","systemCode":"HKUSI_ORMA"}b52c458411d44075a00b7bf2aa7d412f';
                    $md5key         = md5($md5string);

                    $curl           = curl_init();
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => 'https://icgw.feo.hku.hk/hkuapi/incomeCollectionGateway/queryPay',
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS =>'{
                        "requestBody": {
                            "deptCode": "60380",
                            "orderNo": "'.$orderNumber.'",
                            "systemCode": "HKUSI_ORMA"
                        },
                        "sign": "'.$md5key.'"
                    }',
                      CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                      ),
                    ));
					*/
					
					$md5string      = '{"deptCode":"60380","orderNo":"'.$orderNumber.'","systemCode":"HKUSI_ORMA"}b52c458411d44075a00b7bf2aa7d412f';
                    $md5key         = md5($md5string);

                    $curl           = curl_init();
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => 'https://icgw.feo.hku.hk/hkuapi/incomeCollectionGateway/queryPay',
                      CURLOPT_RETURNTRANSFER => true,
                      CURLOPT_ENCODING => '',
                      CURLOPT_MAXREDIRS => 10,
                      CURLOPT_TIMEOUT => 0,
                      CURLOPT_FOLLOWLOCATION => true,
                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                      CURLOPT_CUSTOMREQUEST => 'POST',
                      CURLOPT_POSTFIELDS =>'{
                        "requestBody": {
                            "deptCode": "60380",
                            "orderNo": "'.$orderNumber.'",
                            "systemCode": "HKUSI_ORMA"
                        },
                        "sign": "'.$md5key.'"
                    }',
                      CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                      ),
                    ));
					
                    $response = json_decode(curl_exec($curl),true);
                    curl_close($curl);
                    if (isset($response['data']['transactions']) && count($response['data']['transactions'])) {
                        \Log::info('Event status update transation'.json_encode($response['data']['transactions']));
                        $key = 0;
                        $key = count($response['data']['transactions']) - 1;
                        $event_payment_status = 'Pending';
                        if($response['data']['transactions'][$key]['status'] == 'PAID'){
                            $event_payment_status = 'Paid';
                        }
                        if($response['data']['transactions'][$key]['status'] == 'CANCELLED' || $response['data']['transactions'][$key]['status'] == 'EXPIRED' || $response['data']['transactions'][$key]['status'] == 'REJECTED' || $response['data']['transactions'][$key]['status'] == 'REFUNDED'){
                            $event_payment_status = 'Cancelled';
                        }
                        $bookighall = EventPayment::where('id',$value->id)->update([
                            'transaction_id'        => $response['data']['transactions'][$key]['payNo'],
                            'reference_no'          => $response['data']['transactions'][$key]['refNo'],
                            'card_no'               => $response['data']['transactions'][$key]['cardNo'],
                            'approval_code'         => $response['data']['transactions'][$key]['approvalCode'],
                            'merchant_id'           => $response['data']['transactions'][$key]['merchantId'],
                            'expiry_time'           => strtotime($response['data']['transactions'][$key]['expiryTime']),
                            'pay_time'              => strtotime($response['data']['transactions'][$key]['payTime']),
                            'amount'                => $response['data']['transactions'][$key]['amt'],
                            'payment_method'        => $response['data']['transactions'][$key]['paymentMethod'],
                            'pay_type'              => $response['data']['transactions'][$key]['paymentType'],
                            'payment_status'        => $response['data']['transactions'][$key]['status'],
                            'status'                => ($response['data']['transactions'][$key]['status'] == 'PAID')?'1':'0',
                            'event_payment_status'  => $event_payment_status,
                            'pay_result'            => $response,
                        ]);

                        if (isset($response['data']['transactions'][$key]['status']) && $response['data']['transactions'][$key]['status'] == 'PAID'){
                            EventBooking::where('payment_id',$value->payment_id)->update(['booking_status'=>'Paid']);
                            if($value->getEventBookingDetails && count($value->getEventBookingDetails)){
                                foreach ($value->getEventBookingDetails as $keyEvent => $valueEvent) {
                                    $memberInfo = MemberInfo::where('application_number',$valueEvent->application_id)->first();
                                    if(!empty($memberInfo)){
                                        $mailInfo = [
                                            'given_name'            => $memberInfo->given_name,
                                            'application_number'    => $memberInfo->application_number,
                                            'event_details'         => $valueEvent->getEventSetting,
                                        ];
                                        $paymentsuccess = ['type'=>'EventPaymentSuccessfull','email' =>$memberInfo->getUserDetail->email,'mailInfo' => $mailInfo];
                                        SendEmailJob::dispatch($paymentsuccess);
                                    }
                                }
                            }
                        }

                        if ($response['data']['transactions'][$key]['status'] == 'CANCELLED' || $response['data']['transactions'][$key]['status'] == 'EXPIRED' || $response['data']['transactions'][$key]['status'] == 'REJECTED' || $response['data']['transactions'][$key]['status'] == 'REFUNDED'){
                            $eventBook = EventBooking::where('payment_id',$value->payment_id)->first();
                            EventBooking::where('payment_id',$value->payment_id)->update(['booking_status'=>'Cancelled']);
                            if($value->getEventBookingDetails && count($value->getEventBookingDetails)){
                                foreach ($value->getEventBookingDetails as $keyEvent => $valueEvent) {
                                    $eventData = EventSetting::find($valueEvent->event_id);
                                    if(!empty($eventData)){
                                        $eventData->increment('quota_balance',$valueEvent->no_of_seats);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            \Log::info('Booking event status updated event mail');
        } catch (Exception $e) {
            \Log::info('cron error'.$e->getMessage());
        }
    }
}

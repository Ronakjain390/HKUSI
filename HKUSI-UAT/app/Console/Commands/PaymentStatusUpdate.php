<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Models\HallBookingInfo;
use App\Jobs\SendEmailJob;

class PaymentStatusUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paymentstatus:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Payment status update successfully.';

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
            \Log::info('Payment status update cron run');
            $payment = Payment::select('id','order_no','status','payment_status')->where('service_type','Hall Booking')->whereNotIn('payment_status',['PAID','EXPIRED','CANCELLED','UATPAID'])->where('status',0)->whereNotNull('order_no')->orderBy('id','DESC')->get();
            //\Log::info($payment);
            if (isset($payment) && count($payment)) {
                foreach ($payment as $key => $value) {
                    $orderNumber    = $value->order_no;
                    $md5string      = '{"deptCode":"60380","orderNo":"'.$orderNumber.'","systemCode":"HKUSI_ORMA_V2"}'.env('INCOME_COLLECTION_GATEWAY_KEY');
                    $md5key         = md5($md5string);

                    $curl           = curl_init();
                    curl_setopt_array($curl, array(
                      CURLOPT_URL => env('INCOME_COLLECTION_GATEWAY_URL'),
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
                            "systemCode": "HKUSI_ORMA_V2"
                        },
                        "sign": "'.$md5key.'"
                    }',
                      CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json'
                      ),
                    ));

                    $response = json_decode(curl_exec($curl),true);

                    curl_close($curl);
                    
                    //\Log::info($response);
                    if (isset($response['data']['transactions']) && count($response['data']['transactions'])) {
                        $key = 0;
                        $key = count($response['data']['transactions']) - 1;
                        $payment_id = explode('-', $response['data']['orderNo']);
                        $getBookingDetails =  HallBookingInfo::where('booking_number',$payment_id[0])->first();
                        $bookighall = Payment::where('id',$value->id)->update([
                                    'application_id'        => $getBookingDetails->getMemberdata->application_number,
                                    'payment_id'            => $payment_id[0],
                                    'transaction_id'        => $response['data']['transactions'][$key]['payNo'],
                                    'order_no'              => $response['data']['orderNo'],
                                    'reference_no'          => $response['data']['transactions'][$key]['refNo'],
                                    'card_no'               => $response['data']['transactions'][$key]['cardNo'],
                                    'approval_code'         => $response['data']['transactions'][$key]['approvalCode'],
                                    'merchant_id'           => $response['data']['transactions'][$key]['merchantId'],
                                    'expiry_time'           => strtotime($response['data']['transactions'][$key]['expiryTime']),
                                    'pay_time'              => strtotime($response['data']['transactions'][$key]['payTime']),
                                    'amount'                => $response['data']['transactions'][$key]['amt'],
                                    'payment_method'        => $response['data']['transactions'][$key]['paymentMethod'],
                                    'service_type'          => 'Hall Booking',
                                    'pay_type'              => $response['data']['transactions'][$key]['paymentType'],
                                    'payment_status'        => $response['data']['transactions'][$key]['status'],
                                    'status'                => ($response['data']['transactions'][$key]['status'] == 'PAID')?'1':'0',
                                    'pay_result'            => $response,
                        ]);
                        if (isset($getBookingDetails) && !empty($getBookingDetails)) {
                            if (isset($response['data']['transactions'][$key]['status']) && $response['data']['transactions'][$key]['status'] == 'PAID') {
                                $getBookingDetails->update(['status'=>'Paid']);
                                $hall_payment_days = (isset($getBookingDetails->getHallsetting->hall_payment_days) && !empty($getBookingDetails->getHallsetting->hall_payment_days))?$getBookingDetails->getHallsetting->hall_payment_days:'0';
                                $hall_confirmation_date = $getBookingDetails->payment_deadline_date + ($hall_payment_days * 86400);
                                $mailInfo = [
                                    'given_name'                => $getBookingDetails->getMemberdata->given_name,
                                    'application_number'        => $getBookingDetails->getMemberdata->application_number,
                                    'Hall_confirmation_Date'    => date('Y-m-d',$hall_confirmation_date),
                                ];
                                $paymentsuccess = ['type'=>'PaymentSuccessfull','email' =>$getBookingDetails->getMemberdata->email_address,'mailInfo' => $mailInfo];
                                SendEmailJob::dispatch($paymentsuccess);

                                \Log::info('Payment Successful.');
                            }else{
                                \Log::info('Payment failed.');
                            }
                        }else{
                            \Log::info('Booking not found.');
                        }
                    }
                }
            }

            \Log::info('Payment status update cron end');
        } catch (Exception $e) {
            \Log::info('Payment status update cron error'.$e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Payment;
use App\Models\HallBookingInfo;
use App\Jobs\SendEmailJob;
use Auth;

class DashboardController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $headerTitle = "Dashboard";
        return view('admin.dashboard',compact('headerTitle'));
    }

    public function redirect(Request $request){
        return redirect()->route('admin.dashboard');
    }

    public function pendingPaymentStatus(Request $request){
        try {
            \Log::info('Payment status update cron run');
            $payment = Payment::select('id','order_no','status')->where('id','<=','326')->where('status',0)->whereNotNull('order_no')->orderBy('id','DESC')->get();
            \Log::info($payment);
            if (isset($payment) && count($payment)) {
                foreach ($payment as $key => $value) {
                    $orderNumber    = $value->order_no;
                    $md5string      = '{"deptCode":"60380","orderNo":"'.$orderNumber.'","systemCode":"HKUSI_ORMA"}'.env('INCOME_COLLECTION_GATEWAY_KEY');
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
                    
                    \Log::info($response);
                    if (isset($response['data']['transactions']) && !empty($response['data']['transactions'])) {
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
                                    'pay_result'            => $response['data']['transactions'][$key]['payResult'],
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
            return redirect()->route('admin.dashboard');
        } catch (Exception $e) {
            \Log::info('Payment status update cron error'.$e->getMessage());
        }
    }


    public function insertRecord(Request $request){
        $payment = [
            '0000023-j9SBaMpTsc',
            '0000030-evRHMOp4Sm',
            '0000024-Yk7oHmqBMY',
            '0000032-uJ87kFLvLO',
            '0000048-mpXR6VOj0e',
            '0000017-heQsxYKdcF',
            '0000039-kIzaXpHrH0',
            '0000075-6GkfiUA0Ez',
            '0000072-uA3AgsjT2g',
            '0000089-k4IHt8HpLE',
            '0000108-oWm4AHu8ET',
            '0000136-vdb4AAa1uG',
            '0000161-EBUs10rRWI',
            '0000156-hVKwInDrSM',
            '0000132-t2UMnJ802E',
            '0000165-21Pq4DzfKH',
            '0000172-ZGmfKYGbdt',
            '0000189-RdDwV9Gv9O',
            '0000192-hqbbT7guh1',
            '0000194-QH6nn0KIT6',
            '0000188-w5vTeW0szq',
            '0000204-vhuGYOXTGs',
            '0000209-sTZnVKt06M',
            '0000230-eCbS9mjIxQ',
            '0000237-onRVJBQ4ui',
            '0000253-7cIZhXGrpk',
            '0000190-a1kPS5b7mI',
            '0000259-TKPJ6XFAIg',
            '0000204-satypbVxNt',
            '0000027-PE8dfNoYyF',
            '0000282-OGnB1B8VAP',
            '0000025-KN9NAwrgNq',
            '0000190-16cRgpgjcE',
            '0000295-heQWy9YCOW',
            '0000229-fcoJ2yl4Ns',
            '0000204-AqwOPhnCry',
            '0000306-zZ4oAKoITh',
            '0000305-07IVUPZaHt',
            '0000131-tnSruzvIwv',
            '0000053-YWO7UvKTtw',
            '0000190-Nig2SJNlrN',
            '0000218-8kuEEKNXNr',
            '0000261-jqUtl6qfDq',
            '0000138-6N353vz3iL',
            '0000105-0pwfUPCzJe',
            '0000205-kpP4Zp9h7a',
            '0000198-X4S1nWWDEY',
            '0000189-m9xig54pTk',
            '0000346-W0CpAA12k9',
            '0000189-hLpDxrCXup',
            '0000089-5mmFYpN3bA',
            '0000112-yMQliDenqJ',
            '0000041-F74UNOuqYI',
            '0000125-uU8bCxpv0N',
            '0000256-Gu2nrwfKKi',
            '0000061-CeVqS9sLJA',
            '0000161-sCGDpct5XI',
            '0000137-GYMlH4C7sD',
            '0000189-rkzOxT6BzQ',
            '0000095-NRJtttVYtW',
            '0000389-qSFproo8LQ',
            '0000273-uCCumsfpFA',
            '0000396-96FUKJaJn6',
            '0000224-XmfgCtfUSJ',
            '0000368-LIVUze8ZLe',
            '0000415-trDfSAe3lD',
            '0000436-xJJFPi0iM5',
            '0000255-A0nvWkrY8Z',
            '0000087-Rq1Qy3HhQS',
            '0000120-E0YNTiRzZV',
            '0000326-nQ2Jgjn43t',
            '0000326-kebeLfn8xQ',
        ];

        foreach ($payment as $key => $paymentValue) {
            $exitsPyament = Payment::where('order_no',$paymentValue)->first();
            if (empty($exitsPyament)) {
                $booking_order = explode('-', $paymentValue);
                $bookigHall = HallBookingInfo::select('id','booking_number','application_id')->where('booking_number',$booking_order[0])->first();
                if (isset($bookigHall) && !empty($bookigHall)) {
                    Payment::create([
                        'application_id'        => $bookigHall->application_id,
                        'payment_id'            => $booking_order[0],
                        'order_no'              => $paymentValue,
                        'service_type'          => 'Hall Booking',
                        'payment_status'        => 'Processing',
                        'status'                => 0,
                    ]);
                }
            }
        }
    }
}

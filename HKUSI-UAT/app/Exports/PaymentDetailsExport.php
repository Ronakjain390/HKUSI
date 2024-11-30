<?php

namespace App\Exports;

use App\Models\Payment;
use App\Models\ExportDataInfo;
use App\Models\ExportPaymentInfo;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Auth;

class PaymentDetailsExport implements FromView
{
    protected $data;
    public function __construct($data)
    {
        $this->data = $data;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {   
        $Export             = new ExportDataInfo();
        $Export['user_id']  = Auth::user()->id;
        $Export['type']     = 'Payment';
        $Export['status']   = (isset($this->data) && count($this->data))?'1':'0';
        $Export->save();

        $payment = $this->data;
        if (isset($payment) && count($payment) && !empty($Export->id)) {
            foreach ($payment as $key => $value) {
                ExportPaymentInfo::create([
                    'export_data_info_id'   => $Export->id,
                    'application_id'        => $value->application_id,
                    'payment_id'            => $value->payment_id,
                    'transaction_id'        => $value->transaction_id,
                    'order_no'              => $value->order_no,
                    'service_type'          => $value->service_type,
                    'reference_no'          => $value->reference_no,
                    'card_no'               => $value->card_no,
                    'approval_code'         => $value->approval_code,
                    'merchant_id'           => $value->merchant_id,
                    'expiry_time'           => $value->expiry_time,
                    'pay_time'              => $value->pay_time,
                    'amount'                => $value->amount,
                    'payment_method'        => $value->payment_method,
                    'pay_type'              => $value->pay_type,
                    'status'                => $value->status,
                    'pay_result'            => $value->pay_result,
                    'payment_status'        => $value->payment_status,
                ]);
            }
        }
        return view('admin.payment.paymentsheet', [
             'payment' => $payment
        ]);
    }
}

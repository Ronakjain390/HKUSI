<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HallBookingInfo;
use App\Jobs\SendEmailJob;

class PaymentDeadline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:deadline';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Payment deadline mail send successfully.';

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
            \Log::info('Payment deadline cron run');
            $hallBooking = HallBookingInfo::select('id','hall_setting_id','quota_id','user_type_id','payment_deadline_date')->whereNotNull('payment_deadline_date')->where('status','Accepted')->orderBy('id','ASC')->get();
            if (isset($hallBooking) && count($hallBooking)) {
                foreach ($hallBooking as $key => $bookingInfo) {
                    $hallPaymentDays = isset($bookingInfo->getHallsetting->hall_payment_days)?$bookingInfo->getHallsetting->hall_payment_days:0;
                    $bookingDate    = $bookingInfo->payment_deadline_date + ($hallPaymentDays * 86400);
                    $hallPaymentDate  = date('Y-m-d' , $bookingDate);
                    $today = date('Y-m-d', strtotime("yesterday"));
                    if ($hallPaymentDate == $today) {
                        $bookingInfo->update(['status'=>'Cancelled']);
                        $bookingInfo->getQuotaDetail->updateBookingQuota('pluse');
                        $mailInfo = [
                            'given_name'  => isset($bookingInfo->getMemberdata->given_name)?$bookingInfo->getMemberdata->given_name:'',
                            'application_number' => $bookingInfo->getMemberdata->application_number,
                        ];
                        $details = ['type'=>'PaymentDeadlineMail','email' => $bookingInfo->getMemberdata->email_address,'mailInfo' => $mailInfo];
                        SendEmailJob::dispatch($details);
                        \Log::info($bookingInfo->getMemberdata->given_name.' / '.$bookingInfo->getMemberdata->application_number.' Booking cancelled successfully. His payment deadline '.$hallPaymentDate);
                    }
                }
            }
            \Log::info('Payment deadline cron end');
        } catch (Exception $e) {
            \Log::info('cron error'.$e->getMessage());
        }
    }
}

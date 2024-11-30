<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HallBookingInfo;
use App\Jobs\SendEmailJob;

class FullyBooked extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fully:booked';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Accommodation fully booked male send successfully';

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
            \Log::info('Fully booked cron run');
            $hallBooking = HallBookingInfo::select('id','hall_setting_id','quota_id','user_type_id','hall_result_date')->whereNotNull('hall_result_date')->where('status','Pending')->orderBy('id','ASC')->get();
            if (isset($hallBooking) && count($hallBooking)) {
                foreach ($hallBooking as $key => $bookingInfo) {
                    $hallResultDays = isset($bookingInfo->getHallsetting->hall_result_days)?$bookingInfo->getHallsetting->hall_result_days:0;
                    $bookingDate    = $bookingInfo->hall_result_date + ($hallResultDays * 86400);
                    $hallResultDate = date('Y-m-d' , $bookingDate);
                    $today = date('Y-m-d');
                    if ($hallResultDate == $today) {
                        HallBookingInfo::where('id',$bookingInfo->id)->update(['status'=>'Cancelled']);
                        $mailInfo = [
                            'given_name'  => isset($bookingInfo->getMemberdata->given_name)?$bookingInfo->getMemberdata->given_name:'',
                            'application_number' => $bookingInfo->getMemberdata->application_number,
                            'link' => 'https://hkusibookings.hku.hk/registration/login',
                        ];
                        $details = ['type'=>'FullyBookedMale','email' => $bookingInfo->getMemberdata->email_address,'mailInfo' => $mailInfo];
                        SendEmailJob::dispatch($details);
                        \Log::info($bookingInfo->getMemberdata->given_name.' / '.$bookingInfo->getMemberdata->application_number.' Booking cancelled successfully. His result date '.$hallResultDate);
                    }
                }
            }
            \Log::info('Fully booked cron end');
        } catch (Exception $e) {
            \Log::info('cron error'.$e->getMessage());
        }
    }
}

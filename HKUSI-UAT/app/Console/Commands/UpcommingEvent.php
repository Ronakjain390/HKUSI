<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EventSetting;
use App\Models\MemberInfo;
use App\Models\EventBooking;
use App\Jobs\SendEmailJob;

class UpcommingEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upcoming:event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Need to send mail before two days of event';

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
            \Log::info('Upcomming event');
            $startDate = strtotime('+2 day 00:00:00');
            $endDate = strtotime('+2 day 23:59:59');
            $eventSetting = EventSetting::where('status','Enabled')->whereBetween('date',[$startDate,$endDate])->orderBy('id','ASC')->get();
            if (count($eventSetting)) {
                foreach ($eventSetting as $key => $eventBookingInfo){
                    $eventBooking = EventBooking::leftJoin('event_payments', function ($join){
                        $join->on('event_payments.payment_id', '=', 'event_bookings.payment_id');
                    })->where('event_bookings.event_id',$eventBookingInfo->id)->where('event_payments.service_type','Event Booking')->where('event_payments.payment_status','PAID')->get();
                    if(count($eventBooking)){
                        foreach ($eventBooking as $key => $value) {
                            $memberDetail = MemberInfo::where('application_number',$value->application_id)->where('status',1)->first();
                            if(!empty($memberDetail)){
                                $mailInfo = [
                                    'given_name'            => $memberDetail->given_name,
                                    'application_number'    => $memberDetail->application_number,
                                    'event_details'         => $eventBookingInfo,
                                ];
                                $paymentsuccess = ['type'=>'EventReminder','email' =>$memberDetail->getUserDetail->email,'mailInfo' => $mailInfo];
                                SendEmailJob::dispatch($paymentsuccess);
                                \Log::info($memberDetail->given_name.' / '.$memberDetail->application_number.' Upcomming Event Mail');
                            }
                        }
                    }
                }
            }
            \Log::info('Upcoming event mail');
        } catch (Exception $e) {
            \Log::info('cron error'.$e->getMessage());
        }
    }
}

<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\HallBookingInfo;
use App\Models\Payment;

class HallBookingCancelled extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hallbooking:cancelled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hall booking canceled mail send';

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
            \Log::info('Hall booking cancelled');
            $startDate = date("Y-m-d H:i:s",strtotime('-45 minutes'));
            $endDate = date("Y-m-d H:i:s",strtotime('-46 minutes'));            
            $paymentsData = Payment::where('service_type','Hall Booking')->whereIn('payment_status',['Processing',''])->where('created_at','<=',$startDate)->get();
            if(count($paymentsData)>0){
                foreach ($paymentsData as $payments) {
                    $HallBookings = HallBookingInfo::where('booking_number',$payments->payment_id)->get();
                    if(count($HallBookings)){
                        foreach($HallBookings as $bookings){
                            //$bookings->update(['status'=>'Cancelled']);
                            $payments->update(['payment_status'=>'CANCELLED']);
                            //$bookings->getQuotaDetail->updateBookingQuota('pluse');
                        }
                    }
                }
            }
            \Log::info('Hall Booking Cancelled request');
        } catch (Exception $e) {
            \Log::info('cron error'.$e->getMessage());
        }
    }
}

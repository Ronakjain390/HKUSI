<?php

namespace App\Exports;
use App\Models\ExportDataInfo;
use App\Models\ExportHallBookingInfo;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Auth;

class HallBookingExport implements FromView
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
        $Export['type']     = 'HallBooking';
        $Export['status']   = (isset($this->data) && count($this->data))?'1':'0';
        $Export->save();
        $hallbooking = $this->data;
        if (isset($hallbooking) && count($hallbooking) && !empty($Export->id)) {
            foreach ($hallbooking as $key => $value) {
                $days = 0;
                $date1 = $value->check_in_date - 86400;
                $date2 = $value->check_out_date;
                $days = (int)(($date2 - $date1)/86400);
                $totalbooking = 0 ;
                if($value->booking_type == 'g'){
                 $totalbooking =  count($value->getGroupHallInfo);                 
                }else{
                    $totalbooking = 1;
                }
                ExportHallBookingInfo::create([
                    'export_data_info_id'   => $Export->id,
                    'hall_booking_info_id'  => $value->id,
                    'hall_setting_id'       => $value->hall_setting_id,
                    'quota_id'              => $value->quota_id,
                    'quota_hall_id'         => $value->quota_hall_id,
                    'quota_room_id'         => $value->quota_room_id,
                    'user_type_id'          => $value->user_type_id,
                    'user_type'             => $value->user_type,
                    'start_date'            => $value->start_date,
                    'end_date'              => $value->end_date,
                    'check_in_date'         => $value->check_in_date,
                    'check_in_time'         => $value->check_in_time,
                    'check_out_date'        => $value->check_out_date,
                    'check_out_time'        => $value->check_out_time,
                    'amount'                => $value->amount,
                    'programme_code'        => $value->programme_code,
                    'application_id'        => $value->application_id,
                    'booking_type'          => $value->booking_type,
                    'nights'                => $days-1,
                    'total_bookings'        => $totalbooking,
                    'booking_number'        => $value->booking_number,
                    'status'                => $value->status,
                ]);
            }
        }
        return view('admin.hallbooking.hallbookingexport', [
             'hallbooking' => $hallbooking
        ]);
    }
}

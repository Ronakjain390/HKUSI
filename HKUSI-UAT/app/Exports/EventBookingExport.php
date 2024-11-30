<?php

namespace App\Exports;
use App\Models\ExportDataInfo;
use App\Models\ExportEventBookingInfo;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Auth;

class EventBookingExport implements FromView
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
        $Export['type']     = 'EventBooking';
        $Export['status']   = (isset($this->data) && count($this->data))?'1':'0';
        $Export->save();
        $eventbooking = $this->data;
        if (isset($eventbooking) && count($eventbooking) && !empty($Export->id)) {
            foreach ($eventbooking as $key => $value) {
                $unit_price = (isset($value->unit_price) && !empty($value->unit_price))?$value->unit_price:'0';
                $amount = $value->no_of_seats * $unit_price ;
                ExportEventBookingInfo::create([
                    'export_data_info_id'   => $Export->id,
                    'application_number'    => $value->application_id,
                    'booking_id'            => $value->payment_id,
                    'event_id'              => $value->event_id,
                    'event_name'            => $value->getEventSetting->event_name,
                    'event_date'            => $value->getEventSetting->date,
                    'session'               => $value->getEventSetting->time,
                    'location'              => $value->getEventSetting->location,
                    'amount'                => $amount,
                    'assembly_time'         => $value->getEventSetting->assembly_time,
                    'assembly_location'     => $value->getEventSetting->assembly_location,
                    'no_of_seats'           => $value->no_of_seats,
                    'unit_price'            => $value->unit_price,
                    'check_in_date'         => $value->check_in_date,
                    'check_in_time'         => $value->check_in_time,
                    'check_operater'        => $value->check_operater,
                    'booking_status'        => $value->booking_status,
                ]);
            }
        }
        return view('admin.eventbooking.eventbookingexport', [
             'eventbooking' => $eventbooking
        ]);
    }
}

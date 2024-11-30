<?php

namespace App\Exports;
use App\Models\ExportDataInfo;
use App\Models\ExportPrivateEventBookingInfo;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Auth;

class PrivateEventBookingExport implements FromView
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
        $Export['type']     = 'PrivateEventBooking';
        $Export['status']   = (isset($this->data) && count($this->data))?'1':'0';
        $Export->save();
        $eventbooking = $this->data;
        if (isset($eventbooking) && count($eventbooking) && !empty($Export->id)) {
            foreach ($eventbooking as $key => $value) {
                ExportPrivateEventBookingInfo::create([
                    'export_data_info_id'   => $Export->id,
                    'application_number'    => $value->application_id,
                    'booking_id'            => $value->booking_id,
                    'event_id'              => $value->event_id,
                    'event_name'            => $value->getEventDetails->event_name,
                    'event_date'            => $value->getEventDetails->date,
                    'start_time'               => $value->getEventDetails->start_time,
                    'end_time'               =>  $value->getEventDetails->end_time,
                    'location'              => $value->getEventDetails->location,
                    'assembly_start_time'         => $value->getEventDetails->assembly_start_time,
                    'assembly_end_time'         => $value->getEventDetails->assembly_end_time,
                    'assembly_location'     => $value->getEventDetails->assembly_location,
                    'check_in_date'         => $value->check_in_date,
                    'check_in_time'         => $value->check_in_time,
                    'check_operator'        => $value->check_operator,
                    'booking_status'        => $value->booking_status,
                    'event_status'        => $value->event_status,
                ]);
            }
        }
        return view('admin.private-event-order.eventbookingexport', [
             'eventbooking' => $eventbooking
        ]);
    }
}

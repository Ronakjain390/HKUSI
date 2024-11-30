<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\PrivateEventOrder;
use App\Models\MemberInfo;
use App\Models\HallSetting;
use App\Jobs\SendEmailJob;
use Auth,DB;
use App\Exports\PrivateEventBookingExport;
use Maatwebsite\Excel\Facades\Excel;

class PrivateEventOrderManagement extends Component
{
    use WithPagination;
    // Private Event Booking Management By Akash
    public $search ,$from , $to ,$daterange=false ,$daterange1=false ,$searchSubmit=false , $status=null,$Completed,$Pending,$Accepted,$Paid,$Cancelled,$Updated,$Rejected,$countries=[],$collages=[],$hall_setting_id,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$import_data_info_id,$countMember,$statusfind=null,$user_type_id,$clickRowId,$hideRow,$booking_type,$programme,$inFrom,$outFrom,$outTo,$inTo,$Yeardata=[], $member_id; 

    public $createMode = false; 
    public $privateeventbookingExport;

    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFrom()
    {
        $this->resetPage();
    }

    public function updatingTo()
    {
        $this->resetPage();
    }

    public function render()
    {
        $eventData = (new PrivateEventOrder)->newQuery();
        
        if($this->daterange == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $eventData = $eventData->whereBetween('created_at',[$form_date,$to_date]);
                
            }
        }

        if($this->daterange1 == true){
           
            if (isset($this->hall_setting_id) && !empty($this->hall_setting_id)) {
                $eventData = $eventData->whereHas('getEventDetails', function($query)
                {
                    $query->where('hall_setting_id',$this->hall_setting_id);
                });
            }
    
            if (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Today') {
                $eventData = $eventData->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This week') {
                $a = date('w');
                $eventData = $eventData->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime('- '.$a.' days')),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d',strtotime('- '.$a.' days'));
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This month') {
                $eventData = $eventData->whereBetween('created_at',[date('Y-m-01 00:00:00'),date('Y-m-t 23:59:59')]);
                $this->from = date('Y-m-01');
                $this->to   = date('Y-m-t');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Custom range') {
                if (isset($this->start_date) && !empty($this->start_date) && isset($this->end_date) && !empty($this->end_date)) {
                    $form_date  = date('Y-m-d',strtotime($this->start_date)).' 00:00:00';
                    $to_date    = date('Y-m-d',strtotime($this->end_date)).' 23:59:59';
                    $eventData = $eventData->whereBetween('created_at',[$form_date,$to_date]);
                }
            }
            
        }


        if (isset($this->statusfind) && !empty($this->statusfind)) {
            $users =  $eventData->where(function ($o) {
                $o->where('booking_status',$this->statusfind);
            });
        }

        // if (isset($this->is_import) && $this->is_import != '') {
        //     $users =  $eventData->where('is_import',$this->is_import);
        // }
        if (isset($this->statusfind) && $this->statusfind != '') {
            $users =  $eventData->where('booking_status',$this->statusfind);
        }

        if ($this->searchSubmit == true) {
            $eventData = $eventData->where(function ($k) {
                $k->where('event_name', 'like', '%' . trim($this->search) . '%')
                ->orwhere('location', 'like', '%' . trim($this->search) . '%')
                ->orwhere('booking_id', 'like', '%' . trim($this->search) . '%')
                ->orwhere('event_id', 'like', '%' . trim($this->search) . '%')
                ->orwhere('application_id', 'like', '%' . trim($this->search) . '%')
                ->orwhere('id', 'like', '%' . trim($this->search) . '%');
            });
        }
        
        if (isset($this->order_type) && $this->order_type != '') {
            $eventData = $eventData->orderBy($this->order_type,$this->order_by);
        }

        $this->Yeardata = HallSetting::where('status','1')->get();

        if ( isset($this->member_id) && $this->member_id != null) {
            
            $memberInfo = MemberInfo::where('id',$this->member_id)->first();
            $eventData = $eventData->where('application_id',$memberInfo->application_number)->paginate($this->paginate);
            $this->pending = PrivateEventOrder::where('application_id',$memberInfo->application_number)->where('booking_status','Pending')->count();
            $this->cancelled = PrivateEventOrder::where('application_id',$memberInfo->application_number)->where('booking_status','Cancelled')->count();
            $this->paid = PrivateEventOrder::where('application_id',$memberInfo->application_number)->where('booking_status','Paid')->count();
            $this->countMember = PrivateEventOrder::where('application_id',$memberInfo->application_number)->count();

            $exportdata = PrivateEventOrder::where('application_id',$memberInfo->application_number)->get();


        }else{

            $eventData = $eventData->paginate($this->paginate);
            $this->pending = PrivateEventOrder::where('booking_status','Pending')->count();
            $this->cancelled = PrivateEventOrder::where('booking_status','Cancelled')->count();
            $this->paid = PrivateEventOrder::where('booking_status','Paid')->count();
            $this->countMember = PrivateEventOrder::count();

            $exportdata = PrivateEventOrder::get();
            
        }

        $this->privateeventbookingExport = $exportdata;


        return view('livewire.admin.private-event-order.index',compact('eventData'));
    }


    public function destroy($id)
    {
        PrivateEventOrder::find($id)->delete();
        session()->flash('success', 'Private Event Booking delete successfully.');
    }

    public function statusChange($id , $status) 
    {   
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $events = PrivateEventOrder::where('id',$id)->first();
        PrivateEventOrder::where('id', $id)->update(['booking_status' => $status]);
        session()->flash('message', 'Private Event Booking status updated successfully.'); 
    }

    public function eventBookingExport()
    {
        $data = $this->privateeventbookingExport;
        if (count($data) >0) {
            return Excel::download(new PrivateEventBookingExport($data), 'PrivateEventBooking_'.date("d-m-Y-h-i-s").'.xlsx');
        }else{
            return redirect()->back()->with('eventbooking','Data Not found');
        }
        
    }

}

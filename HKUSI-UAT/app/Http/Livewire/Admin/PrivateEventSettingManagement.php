<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\PrivateEventSetting;
use App\Models\Programme;
use App\Models\MemberInfo;
use App\Models\HallSetting;
use App\Jobs\SendEmailJob;
use Auth,DB;
use App\Exports\HallBookingExport;
use Maatwebsite\Excel\Facades\Excel;

class PrivateEventSettingManagement extends Component
{
    use WithPagination;

    // Controller Created By Akash

    public $search ,$from , $to ,$daterange=false ,$daterange1=false ,$searchSubmit=false , $status=null,$Completed,$Pending,$Accepted,$Paid,$Cancelled,$Updated,$Rejected,$countries=[],$collages=[],$hall_setting_id,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$import_data_info_id,$countMember,$statusfind=null,$user_type_id,$clickRowId,$hideRow,$booking_type,$programme,$inFrom,$outFrom,$outTo,$inTo,$Yeardata=[]; 

    public $createMode = false; 
    public $hallbookingExport;

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
        $eventData = (new PrivateEventSetting)->newQuery();
        
        if($this->daterange == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $eventData = $eventData->whereBetween('created_at',[$form_date,$to_date]);
                
            }
        }

        if($this->daterange1 == true){
            if (isset($this->programme) && !empty($this->programme)){
                $eventData = $eventData->where('programme_id' , $this->programme);
            }
            if (isset($this->inFrom) && !empty($this->inFrom) && isset($this->inTo) && !empty($this->inTo)) {
                $eventData = $eventData->whereBetween('date',[strtotime($this->inFrom),strtotime($this->inTo)]);
            }
            if (isset($this->outFrom) && !empty($this->outFrom) && isset($this->outTo) && !empty($this->outTo)) {
                $eventData = $eventData->whereBetween('application_deadline',[strtotime($this->outFrom),strtotime($this->outTo)]);
            }
            if (isset($this->booking_type) && !empty($this->booking_type)) {
                $eventData = $eventData->where('booking_type',$this->booking_type);
            }
            if (isset($this->hall_setting_id) && !empty($this->hall_setting_id)) {
                $eventData = $eventData->where('hall_setting_id',$this->hall_setting_id);
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
                $o->where('status',$this->statusfind);
            });
        }

        if (isset($this->is_import) && $this->is_import != '') {
            $users =  $eventData->where('is_import',$this->is_import);
        }
        if (isset($this->statusfind) && $this->statusfind != '') {
            $users =  $eventData->where('status',$this->statusfind);
        }
        if (isset($this->language) && $this->language != '') {
            $eventData = $eventData->where('language',$this->language);
        }

        if ($this->searchSubmit == true) {
            $eventData = $eventData->where(function ($k) {
                $k->where('event_name', 'like', '%' . trim($this->search) . '%')
                ->orwhere('unit_price', 'like', '%' . trim($this->search) . '%')
                ->orwhere('id', 'like', '%' . trim($this->search) . '%');
            });
           
        }
        
        if (isset($this->order_type) && $this->order_type != '') {
            $eventData = $eventData->orderBy($this->order_type,$this->order_by);
        }

       
            $eventData = $eventData->paginate($this->paginate);
       
       
            $this->enabled = PrivateEventSetting::where('status','Enabled')->count();
            $this->disabled = PrivateEventSetting::where('status','Disabled')->count();
            $this->cancelled = PrivateEventSetting::where('status','Cancelled')->count();
            $this->full = PrivateEventSetting::where('status','Full')->count();
            $this->Yeardata = HallSetting::where('status','1')->get();
            $this->countMember = PrivateEventSetting::count();
        
        $programmerecord = Programme::where('status',1)->orderBy('programme_name','ASC')->get();
        return view('livewire.admin.private-event-setting.index',compact('eventData','programmerecord'));
    }


    public function destroy($id)
    {
        PrivateEventSetting::find($id)->delete();
        session()->flash('success', 'Event delete successfully.');
    }

    public function statusChange($id , $status) 
    {   
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $events = PrivateEventSetting::where('id',$id)->first();
        PrivateEventSetting::where('id', $id)->update(['status' => $status]);
        session()->flash('message', 'Event status updated successfully.'); 
    }

}

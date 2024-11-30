<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\ExportPrivateEventBookingInfo;
use App\Models\MemberInfo;
use App\Models\User;
use App\Models\Country;
use Auth,DB;

class ExportPrivateEventBookingManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false ,$daterange1=false ,$searchSubmit=false , $status=null,$Completed,$Pending,$Accepted,$Paid,$Cancelled,$Updated,$Rejected,$countries=[],$nationality,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$import_data_info_id,$countMember,$statusfind=null,$user_type_id,$export_data_info_id; 

    public $createMode = false;

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
        $hallbooking = (new ExportPrivateEventBookingInfo)->newQuery();
        
        if($this->daterange == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $hallbooking = $hallbooking->whereBetween('created_at',[$form_date,$to_date]);
                
            }
        }

        if($this->daterange1 == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $hallbooking = $hallbooking->whereBetween('created_at',[$form_date,$to_date]);
            }
            
            
        }
       if (isset($this->statusfind) && $this->statusfind != '') {
            $users = $hallbooking->where(function($v){                    
                $v->where('event_status',$this->statusfind);
            });
        }
        if ($this->searchSubmit == true) {
            $hallbooking = $hallbooking->where(function ($k) {
                $k->where('event_name', 'like', '%' . trim($this->search) . '%')
                ->orwhere('location', 'like', '%' . trim($this->search) . '%')
                ->orwhere('booking_id', 'like', '%' . trim($this->search) . '%')
                ->orwhere('event_id', 'like', '%' . trim($this->search) . '%')
                ->orwhere('application_number', 'like', '%' . trim($this->search) . '%')
                ->orwhere('id', 'like', '%' . trim($this->search) . '%');
            });
        }
        $hallbooking = $hallbooking->where('export_data_info_id',$this->export_data_info_id)->paginate($this->paginate);
        
        
        $this->Completed = ExportPrivateEventBookingInfo::where('event_status','Completed')->where('export_data_info_id',$this->export_data_info_id)->count();
        $this->Pending = ExportPrivateEventBookingInfo::where('event_status','Pending')->where('export_data_info_id',$this->export_data_info_id)->count();
        $this->Accepted = ExportPrivateEventBookingInfo::where('event_status','Accepted')->where('export_data_info_id',$this->export_data_info_id)->count();
        $this->Paid = ExportPrivateEventBookingInfo::where('event_status','Paid')->where('export_data_info_id',$this->export_data_info_id)->count();
        $this->Cancelled = ExportPrivateEventBookingInfo::where('event_status','Cancelled')->where('export_data_info_id',$this->export_data_info_id)->count();
        $this->Updated = ExportPrivateEventBookingInfo::where('event_status','Updated')->where('export_data_info_id',$this->export_data_info_id)->count();
        
       
        $this->countMember = ExportPrivateEventBookingInfo::count();
        return view('livewire.admin.private-event-order.export-index',compact('hallbooking'));
    }
}

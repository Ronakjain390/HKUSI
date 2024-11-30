<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\ExportHallBookingInfo;
use App\Models\MemberInfo;
use App\Models\User;
use App\Models\Country;
use App\Jobs\SendEmailJob;
use Auth,DB;
use App\Exports\HallBookingExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportHallBookingManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false ,$daterange1=false ,$searchSubmit=false , $status=null,$Completed,$Pending,$Accepted,$Paid,$Cancelled,$Updated,$Rejected,$countries=[],$nationality,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$import_data_info_id,$countMember,$statusfind=null,$user_type_id,$export_data_info_id; 

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
        $hallbooking = (new ExportHallBookingInfo)->newQuery();
        
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
            if (isset($this->nationality) && !empty($this->nationality)) {
                $hallbooking = $hallbooking->where('nationality',$this->nationality);
            }
            if (isset($this->gender) && !empty($this->gender)) {
                $hallbooking = $hallbooking->where('gender',$this->gender);
            }
            if (isset($this->study_country) && !empty($this->study_country)) {
                $hallbooking = $hallbooking->where('study_country',$this->study_country);
            }
            if (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Basic') {
                $hallbooking = $hallbooking->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 00:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Today') {
                $hallbooking = $hallbooking->whereBetween('created_at',[date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d');
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This week') {
                $a = date('w');
                $hallbooking = $hallbooking->whereBetween('created_at',[date('Y-m-d 00:00:00',strtotime('- '.$a.' days')),date('Y-m-d 23:59:59')]);
                $this->from = date('Y-m-d',strtotime('- '.$a.' days'));
                $this->to   = date('Y-m-d');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'This month') {
                $hallbooking = $hallbooking->whereBetween('created_at',[date('Y-m-01 00:00:00'),date('Y-m-t 23:59:59')]);
                $this->from = date('Y-m-01');
                $this->to   = date('Y-m-t');
            }elseif (isset($this->record_period) && !empty($this->record_period) && $this->record_period == 'Custom range') {
                if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                    $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                    $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                    $hallbooking = $hallbooking->whereBetween('created_at',[$form_date,$to_date]);
                }
            }
            
        }


        if (isset($this->statusfind) && $this->statusfind == 'Completed') {
            $users =  $hallbooking->where(function ($o) {
                $o->where('status','Completed');
            });
        }elseif(isset($this->statusfind) && $this->statusfind == 'Pending'){
            $users =  $hallbooking->where(function ($o) {
                $o->where('status','Pending');
            });
        }elseif(isset($this->statusfind) && $this->statusfind == 'Accepted'){
            $users =  $hallbooking->where(function ($o) {
                $o->where('status','Accepted');
            });
        }elseif(isset($this->statusfind) && $this->statusfind == 'Paid'){
            $users =  $hallbooking->where(function ($o) {
                $o->where('status','Paid');
            });
        }elseif(isset($this->statusfind) && $this->statusfind == 'Cancelled'){
            $users =  $hallbooking->where(function ($o) {
                $o->where('status','Cancelled');
            });
        }elseif(isset($this->statusfind) && $this->statusfind == 'Updated'){
            $users =  $hallbooking->where(function ($o) {
                $o->where('status','Updated');
            });
        }elseif(isset($this->statusfind) && $this->statusfind == 'Rejected'){
            $users =  $hallbooking->where(function ($o) {
                $o->where('status','Rejected');
            });
        }

        if (isset($this->is_import) && $this->is_import != '') {
            $users =  $hallbooking->where('is_import',$this->is_import);
        }
        if (isset($this->statusfind) && $this->statusfind != '') {
            $users =  $hallbooking->where('status',$this->statusfind);
        }
        if (isset($this->language) && $this->language != '') {
            $hallbooking = $hallbooking->where('language',$this->language);
        }

        if ($this->searchSubmit == true) {
            $hallbooking = $hallbooking->wherehas( 'gethallbookinghallinfo', function ($k) {
                $k->where('booking_number', 'like', '%' . trim($this->search) . '%')
                ->orwhere('programme_code', 'like', '%' . trim($this->search) . '%')
                ->orwhere('college_name', 'like', '%' . trim($this->search) . '%')
                ->orwhere('application_id', 'like', '%' . trim($this->search) . '%')
                ->orwhere('amount', 'like', '%' . trim($this->search) . '%');
            });
        }
        
        if (isset($this->order_type) && $this->order_type == 'email') {
            $hallbooking = $hallbooking->whereHas('getUserDetail', function ($o) {$o->orderBy($this->order_type,$this->order_by);});
        }else{
            $hallbooking = $hallbooking->orderBy($this->order_type,$this->order_by);
        }

        if (isset($this->user_type_id) && !empty($this->user_type_id)) {
            $exportdata = $hallbooking->where('user_type_id',$this->user_type_id)->get();
            $hallbooking = $hallbooking->where('user_type_id',$this->user_type_id)->paginate($this->paginate);
        }else{
            $exportdata = $hallbooking->get();
            $hallbooking = $hallbooking->where('export_data_info_id',$this->export_data_info_id)->paginate($this->paginate);
        }
        
        if (isset($this->user_type_id) && !empty($this->user_type_id)) {
            $this->Completed = ExportHallBookingInfo::where('export_data_info_id',$this->export_data_info_id)->where('user_type_id',$this->user_type_id)->where('status','Completed')->count();
            $this->Completed = ExportHallBookingInfo::where('export_data_info_id',$this->export_data_info_id)->where('user_type_id',$this->user_type_id)->where('status','Completed')->count();
            $this->Pending = ExportHallBookingInfo::where('export_data_info_id',$this->export_data_info_id)->where('user_type_id',$this->user_type_id)->where('status','Pending')->count();
            $this->Accepted = ExportHallBookingInfo::where('export_data_info_id',$this->export_data_info_id)->where('user_type_id',$this->user_type_id)->where('status','Accepted')->count();
            $this->Paid = ExportHallBookingInfo::where('user_type_id',$this->user_type_id)->where('status','Paid')->count();
            $this->Cancelled = ExportHallBookingInfo::where('export_data_info_id',$this->export_data_info_id)->where('user_type_id',$this->user_type_id)->where('status','Cancelled')->count();
            $this->Updated = ExportHallBookingInfo::where('export_data_info_id',$this->export_data_info_id)->where('user_type_id',$this->user_type_id)->where('status','Updated')->count();
            $this->Rejected = ExportHallBookingInfo::where('export_data_info_id',$this->export_data_info_id)->where('user_type_id',$this->user_type_id)->where('status','Rejected')->count();
        }else{
            $this->Completed = ExportHallBookingInfo::where('status','Completed')->where('export_data_info_id',$this->export_data_info_id)->count();
            $this->Completed = ExportHallBookingInfo::where('status','Completed')->where('export_data_info_id',$this->export_data_info_id)->count();
            $this->Pending = ExportHallBookingInfo::where('status','Pending')->where('export_data_info_id',$this->export_data_info_id)->count();
            $this->Accepted = ExportHallBookingInfo::where('status','Accepted')->where('export_data_info_id',$this->export_data_info_id)->count();
            $this->Paid = ExportHallBookingInfo::where('status','Paid')->where('export_data_info_id',$this->export_data_info_id)->count();
            $this->Cancelled = ExportHallBookingInfo::where('status','Cancelled')->where('export_data_info_id',$this->export_data_info_id)->count();
            $this->Updated = ExportHallBookingInfo::where('status','Updated')->where('export_data_info_id',$this->export_data_info_id)->count();
            $this->Rejected = ExportHallBookingInfo::where('status','Rejected')->where('export_data_info_id',$this->export_data_info_id)->count();
        } 
       
        $this->countMember = ExportHallBookingInfo::count();
        $this->countries = Country::where('status','1')->orderBy('name','ASC')->get();
        $this->hallbookingExport = $exportdata;
        return view('livewire.admin.hallbooking.export-index',compact('hallbooking'));
    }
}

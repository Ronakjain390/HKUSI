<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\HallBookingInfo;
use App\Models\MemberInfo;
use App\Models\User;
use App\Models\Country;
use App\Jobs\SendEmailJob;
use Auth;

class MemberHallBookingManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false ,$searchSubmit=false , $status=null,$Completed,$Pending,$Accepted,$Paid,$Cancelled,$Updated,$Rejected,$countries=[],$nationality,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$import_data_info_id,$countMember,$statusfind=null,$user_type_id; 

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
        $hallbooking = (new HallBookingInfo)->newQuery();
        
        if($this->daterange == true){
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
            if (isset($this->start_date) && !empty($this->start_date)) {
                $hallbooking = $hallbooking->where('start_date',$this->start_date);
            }
            if (isset($this->end_date) && !empty($this->end_date)) {
                $hallbooking = $hallbooking->where('end_date',$this->end_date);
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
                if (isset($this->start_date) && !empty($this->start_date) && isset($this->end_date) && !empty($this->end_date)) {
                    $form_start_date  = date('Y-m-d',strtotime($this->start_date)).' 00:00:00';
                    $to_end_date    = date('Y-m-d',strtotime($this->end_date)).' 23:59:59';
                    $hallbooking = $hallbooking->whereBetween('created_at',[$form_start_date,$to_end_date]);
                }
            }
            
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
            $hallbooking = $hallbooking->where(function ($k) {
                $k->where('booking_number', 'like', '%' . trim($this->search) . '%')
                ->orwhere('programme_code', 'like', '%' . trim($this->search) . '%')
                ->orwhere('application_id', 'like', '%' . trim($this->search) . '%')
                ->orwhere('amount', 'like', '%' . trim($this->search) . '%');
            });
        }
        if (isset($this->order_type) && $this->order_type == 'email') {
            $hallbooking = $hallbooking->whereHas('getUserDetail', function ($o) {$o->orderBy($this->order_type,$this->order_by);});
        }else{
            $hallbooking = $hallbooking->orderBy($this->order_type,$this->order_by);
        }
        $hallbooking = $hallbooking->where('user_type_id',$this->user_type_id)->paginate($this->paginate);
        $this->Completed = HallBookingInfo::where('user_type_id',$this->user_type_id)->where('status','Completed')->count();
        $this->Pending = HallBookingInfo::where('user_type_id',$this->user_type_id)->where('status','Pending')->count();
        $this->Accepted = HallBookingInfo::where('user_type_id',$this->user_type_id)->where('status','Accepted')->count();
        $this->Paid = HallBookingInfo::where('user_type_id',$this->user_type_id)->where('status','Paid')->count();
        $this->Cancelled = HallBookingInfo::where('user_type_id',$this->user_type_id)->where('status','Cancelled')->count();
        $this->Updated = HallBookingInfo::where('user_type_id',$this->user_type_id)->where('status','Updated')->count();
        $this->Rejected = HallBookingInfo::where('user_type_id',$this->user_type_id)->where('status','Rejected')->count();
        $this->countMember = HallBookingInfo::count();
        $this->countries = Country::where('status','1')->orderBy('name','ASC')->get();
        return view('livewire.admin.member-hallbooking.index',compact('hallbooking'));
    }


    public function destroy($id)
    {
        HallBookingInfo::find($id)->delete();
        session()->flash('success', 'Member delete successfully.');
    }

    public function statusChange($id) 
    {   
        $statusdata = HallBookingInfo::find($id); 
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $memberinfo = MemberInfo::where('application_number',$statusdata->application_id)->first();
        if(!empty($memberinfo)){
            $data =[];
            $data['status'] = $this->status;
            if ($data['status']=="Accepted") {
                $mailInfo = [
                    'given_name'     => $memberinfo->given_name,
                    'application_number' => $memberinfo->application_number,
                ];
                $details = ['type'=>'HallReservationConfirm','email' =>$memberinfo->email_address,'mailInfo' => $mailInfo];
                SendEmailJob::dispatch($details);
                $data['payment_deadline_date'] = time();
            }elseif ($data['status']=="Paid") {
                $mailInfo = [
                    'given_name'     => $memberinfo->given_name,
                    'application_number' => $memberinfo->application_number,
                ];
                $paymentsuccess = ['type'=>'PaymentSuccessfull','email' =>$memberinfo->email_address,'mailInfo' => $mailInfo];
                SendEmailJob::dispatch($paymentsuccess);
            }
            HallBookingInfo::where('id', $statusdata->id)->update($data);
            session()->flash('message', 'Member status updated successfully.'); 
        }
    }

    

}

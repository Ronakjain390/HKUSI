<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Http\Request;
use App\Models\HallBookingInfo;
use App\Models\MemberInfo;
use App\Models\User;
use App\Models\Country;
use App\Models\HallSetting;
use App\Jobs\SendEmailJob;
use Auth,DB;
use App\Exports\HallBookingExport;
use Maatwebsite\Excel\Facades\Excel;

class HallBookingManagement extends Component
{
    use WithPagination;

    public $search ,$from , $to ,$daterange=false ,$daterange1=false ,$searchSubmit=false , $status=null,$Completed,$Pending,$Accepted,$Paid,$Cancelled,$Updated,$Rejected,$countries=[],$collages=[],$nationality,$gender,$study_country,$record_period,$start_date,$end_date,$order_by='DESC',$order_type='created_at',$paginate='20',$language, $delete ,$import_data_info_id,$countMember,$statusfind=null,$user_type_id,$clickRowId,$hideRow,$booking_type,$collage,$outFrom,$outTo,$inFrom,$inTo,$hall_setting_id; 
    public $years = [];

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
        $hallbooking = (new HallBookingInfo)->newQuery();
        
        if($this->daterange == true){
            if (isset($this->from) && !empty($this->from) && isset($this->to) && !empty($this->to)) {
                $form_date  = date('Y-m-d',strtotime($this->from)).' 00:00:00';
                $to_date    = date('Y-m-d',strtotime($this->to)).' 23:59:59';
                $hallbooking = $hallbooking->whereBetween('created_at',[$form_date,$to_date]);
            }
        }

        if($this->daterange1 == true){
            if (isset($this->collage) && !empty($this->collage)) {
                $hallbooking = $hallbooking->whereHas('getQuotaHallDetail',function ($t) { $t->where('college_name','like' , '%' . $this->collage . '%');});
            }
            if (isset($this->inFrom) && !empty($this->inFrom) && isset($this->inTo) && !empty($this->inTo)) {
                $hallbooking = $hallbooking->whereHas('getQuotaDetail',function ($t) { $t->whereBetween('check_in_date',[strtotime($this->inFrom),strtotime($this->inTo)]);});
            }
            if (isset($this->outFrom) && !empty($this->outFrom) && isset($this->outTo) && !empty($this->outTo)) {
                $hallbooking = $hallbooking->whereHas('getQuotaDetail',function ($t) { $t->whereBetween('check_out_date',[strtotime($this->outFrom),strtotime($this->outTo)]);});
            }
            if (isset($this->booking_type) && !empty($this->booking_type)) {
                $hallbooking = $hallbooking->where('booking_type',$this->booking_type);
            }
            if (isset($this->hall_setting_id) && !empty($this->hall_setting_id)) {
                $hallbooking = $hallbooking->where('hall_setting_id',$this->hall_setting_id);
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
                    $form_date  = date('Y-m-d',strtotime($this->start_date)).' 00:00:00';
                    $to_date    = date('Y-m-d',strtotime($this->end_date)).' 23:59:59';
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
            $hallbooking = $hallbooking->whereHas('getQuotaHallDetail',function ($k) {
                $k->where('college_name','like', '%' . trim($this->search) . '%')
                ->orwhere('booking_number', 'like', '%' . trim($this->search) . '%')
                ->orwhere('programme_code', 'like', '%' . trim($this->search) . '%')
                ->orwhere('quota_id', 'like', '%' . trim($this->search) . '%')
                ->orwhere('application_id', 'like', '%' . trim($this->search) . '%')
                ->orwhere('booking_type', 'like', '%' . trim($this->search) . '%')
                ->orwhere('amount', 'like', '%' . trim($this->search) . '%');
            });            
        }
        
        if (isset($this->order_type) && $this->order_type == 'email') {
            $hallbooking = $hallbooking->whereHas('getUserDetail', function ($o) {$o->orderBy($this->order_type,$this->order_by);});
        }else{
            $hallbooking = $hallbooking->orderBy($this->order_type,$this->order_by);
        }

        if (isset($this->user_type_id) && !empty($this->user_type_id)) {
            $exportdata = $hallbooking->where('user_type_id',$this->user_type_id)->groupBy('booking_number')->get();
            $hallbooking = $hallbooking->where('user_type_id',$this->user_type_id)->paginate($this->paginate);
        }else{
            $exportdata = $hallbooking->groupBy('booking_number')->get();
            $hallbooking = $hallbooking->paginate($this->paginate);
        }
        
        if (isset($this->user_type_id) && !empty($this->user_type_id)) {
            $this->Completed = HallBookingInfo::where('user_type_id',$this->user_type_id)->where('status','Completed')->count();
            $this->Completed = HallBookingInfo::where('user_type_id',$this->user_type_id)->where('status','Completed')->count();
            $this->Pending = HallBookingInfo::where('user_type_id',$this->user_type_id)->where('status','Pending')->count();
            $this->Accepted = HallBookingInfo::where('user_type_id',$this->user_type_id)->where('status','Accepted')->count();
            $this->Paid     = HallBookingInfo::where('user_type_id',$this->user_type_id)->where('status','Paid')->count();
            $this->Cancelled = HallBookingInfo::where('user_type_id',$this->user_type_id)->where('status','Cancelled')->count();
            $this->Updated = HallBookingInfo::where('user_type_id',$this->user_type_id)->where('status','Updated')->count();
            $this->Rejected = HallBookingInfo::where('user_type_id',$this->user_type_id)->where('status','Rejected')->count();
        }else{
            $this->Completed = HallBookingInfo::where('status','Completed')->count();
            $this->Completed = HallBookingInfo::where('status','Completed')->count();
            $this->Pending = HallBookingInfo::where('status','Pending')->count();
            $this->Accepted = HallBookingInfo::where('status','Accepted')->count();
            $this->Paid = HallBookingInfo::where('status','Paid')->count();
            $this->Cancelled = HallBookingInfo::where('status','Cancelled')->count();
            $this->Updated = HallBookingInfo::where('status','Updated')->count();
            $this->Rejected = HallBookingInfo::where('status','Rejected')->count();
        } 
       
        $this->countMember = HallBookingInfo::count();
        $this->collages = HallBookingInfo::select('quota_halls.college_name')->whereNotNull('quota_hall_id')->leftJoin('quota_halls', function ($join){ $join->on('hall_booking_infos.quota_hall_id', '=' , 'quota_halls.id');})->get();
        $this->countries = Country::where('status','1')->orderBy('name','ASC')->get();
        $this->hallbookingExport = $exportdata;
        $this->years = HallSetting::where('status','1')->get();

        return view('livewire.admin.hallbooking.index',compact('hallbooking'));
    }


    public function destroy($id)
    {
        $hallbooking =  HallBookingInfo::find($id);
        $hallbooking->getQuotaDetail->updateBookingQuota('pluse');
        HallBookingInfo::find($id)->delete();
        session()->flash('success', 'Member delete successfully.');
    }

    public function statusChange($id) 
    {   
        $statusdata = HallBookingInfo::find($id); 
        if (empty($id)) {
            return $this->InvalidUrl();
        }
        $memberinfo = MemberInfo::where('id',$statusdata->user_type_id)->first();
		if(!empty($memberinfo)){
            $data =[];
            $data['status'] = $this->status;
            if ($data['status']=="Accepted") {
                $mailInfo = [
                    'given_name'     => $memberinfo->given_name,
                    'application_number' => $memberinfo->application_number,
                    'hall_payment_days' => $statusdata->getHallsetting->hall_payment_days,
                ];
                $details = ['type'=>'HallReservationConfirm','email' =>$memberinfo->email_address,'mailInfo' => $mailInfo];
                SendEmailJob::dispatch($details);
                $statusdata->update(['payment_deadline_date'=>time()]);
            }elseif ($data['status']=="Paid") {
                $hall_payment_days = (isset($statusdata->getHallsetting->hall_payment_days) && !empty($statusdata->getHallsetting->hall_payment_days))?$statusdata->getHallsetting->hall_payment_days:'0';
                $hall_confirmation_date = $statusdata->payment_deadline_date + ($hall_payment_days * 86400);
                $mailInfo = [
                    'given_name'                => $memberinfo->given_name,
                    'application_number'        => $memberinfo->application_number,
                    'Hall_confirmation_Date'    => date('Y-m-d',$hall_confirmation_date),
                ];
                $paymentsuccess = ['type'=>'PaymentSuccessfull','email' =>$memberinfo->email_address,'mailInfo' => $mailInfo];
                SendEmailJob::dispatch($paymentsuccess);
            }elseif($data['status']=="Cancelled"){
                $statusdata->getQuotaDetail->updateBookingQuota('pluse');
            }elseif($data['status']=="Rejected"){
                $statusdata->getQuotaDetail->updateBookingQuota('pluse');
                $mailInfo = [
                    'given_name'     => $memberinfo->given_name,
                    'application_number' => $memberinfo->application_number,
                ];
                $rejected = ['type'=>'FullyBooked','email' =>$memberinfo->email_address,'mailInfo' => $mailInfo];
                SendEmailJob::dispatch($rejected);
            }elseif($data['status']=="Pending"){
                $statusdata->update(['hall_result_date'=>time()]);
            }
        }
        HallBookingInfo::where('id', $statusdata->id)->update($data);
        session()->flash('message', 'Member status updated successfully.'); 
    }

    public function create(){

        $this->createMode = true;
    }


    public function ungroup($newQuotaId,$memberId){
        // dd($newQuotaId,$memberId);
        dd('pending');
    }

    public function hallBookingExportData()
    {
        $data = $this->hallbookingExport;
        if (count($data) >0) {
            return Excel::download(new HallBookingExport($data), 'hallbooking_'.date("d-m-Y-h-i-s").'.xlsx');
        }else{
            return redirect()->back()->with('hallbooking','Data Not found');
        }
        
    }

    public function showRecord($id){
		if (!empty($this->hideRow) && $this->hideRow == $id) {
            $this->clickRowId = '';
            $this->hideRow = '';
        }else{
			$this->clickRowId = $id;
            $this->hideRow = $id;
        }
    }
}
